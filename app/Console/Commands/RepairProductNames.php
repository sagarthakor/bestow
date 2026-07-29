<?php

namespace App\Console\Commands;

use App\product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RepairProductNames extends Command
{
    /**
     * An old bug in the product-save form used to build product_name as
     * "{size} {item_code} {typed name}" instead of just the typed name.
     * This strips that leading "{size} {item_code} " prefix back off,
     * restoring a clean product_name - matching what getCleanNameAttribute()
     * already does on read, but permanently in the database this time.
     *
     * Only the leading size+item_code pair is touched. Words like "White"/
     * "Navy" that sometimes appear right after are left alone - those are
     * ambiguous (could be real product-name text or a stale color value)
     * and not safe to strip automatically.
     */
    protected $signature = 'product:repair-names {--apply : Save the changes. Without this flag, only a preview is shown.} {--limit=20 : How many sample rows to print}';

    protected $description = 'Strip the merged size/item_code prefix out of product_name for old products';

    public function handle()
    {
        $products = product::where('status', 'product')->get(['id', 'item_code', 'value2', 'product_name']);

        $changes = [];
        foreach ($products as $p) {
            $name = trim($p->product_name ?? '');
            if ($name === '') {
                continue;
            }
            $original = $name;

            // A handful of rows have their own size/item_code column already
            // corrupted with extra words (e.g. item_code = "SECUIRTY RECTANGAL"
            // instead of a real code) - trusting those as a strip prefix would
            // eat real product-name text. Only strip a single clean token.
            $size = trim($p->value2 ?? '');
            if ($size !== '' && strpos($size, ' ') === false && stripos($name, $size . ' ') === 0) {
                $name = trim(substr($name, strlen($size)));
            }

            $code = trim($p->item_code ?? '');
            if ($code !== '' && strpos($code, ' ') === false && stripos($name, $code . ' ') === 0) {
                $name = trim(substr($name, strlen($code)));
            }

            if ($name !== '' && $name !== $original) {
                $changes[] = ['id' => $p->id, 'old' => $original, 'new' => $name];
            }
        }

        $this->info(count($changes) . ' of ' . $products->count() . ' product(s) have a merged size/item_code prefix in product_name.');

        $limit = (int) $this->option('limit');
        $sample = array_slice($changes, 0, $limit);
        $this->table(['ID', 'Old product_name', 'New product_name'], $sample);
        if (count($changes) > $limit) {
            $this->line('... and ' . (count($changes) - $limit) . ' more not shown.');
        }

        if (empty($changes)) {
            return 0;
        }

        if (!$this->option('apply')) {
            $this->warn('Dry run only - nothing was saved. Re-run with --apply to save these changes.');
            return 0;
        }

        $backupPath = storage_path('app/product_name_repair_backup_' . date('Y_m_d_His') . '.csv');
        $fh = fopen($backupPath, 'w');
        fputcsv($fh, ['id', 'old_product_name', 'new_product_name']);
        foreach ($changes as $c) {
            fputcsv($fh, [$c['id'], $c['old'], $c['new']]);
        }
        fclose($fh);
        $this->info('Backup of every old name written to: ' . $backupPath);

        DB::transaction(function () use ($changes) {
            foreach ($changes as $c) {
                DB::table('product')->where('id', $c['id'])->update(['product_name' => $c['new']]);
            }
        });

        $this->info('Updated ' . count($changes) . ' product name(s).');
        return 0;
    }
}
