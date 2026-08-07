<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\HtmlString;

class product extends Model
{
    protected $table="product";
    public $timestamps=false;
    protected $casts=['variant_images'=>'array'];
    /**
     * @var mixed
     */
    private $website_id;
    /**
     * @var mixed
     */
    private $description;
    /**
     * @var mixed
     */
    private $uom;
    /**
     * @var mixed
     */
    private $price;
    /**
     * @var mixed
     */
    private $gst;
    /**
     * @var mixed
     */
    private $thikness;
    /**
     * @var mixed
     */
    private $hsn;
    /**
     * @var mixed
     */
    private $outer_diameter;
    /**
     * @var mixed
     */
    private $inner_diameter;
    /**
     * @var mixed
     */
    private $material;
    /**
     * @var mixed
     */
    private $category;
    /**
     * @var mixed
     */
    private $item_total;
    /**
     * @var false|mixed|string
     */
    private $created_time;
    /**
     * @var mixed
     */
    private $product_name;
    /**
     * @var mixed
     */
    private $user_id;
    /**
     * @var mixed|string
     */
    private $status;

    /**
     * Strip leading [size] [item_code] prefix from product_name.
     * e.g. "5 211 YELLOW SOCKS NAVY PATTI LYCRA" → "YELLOW SOCKS NAVY PATTI LYCRA"
     */
    public function getCleanNameAttribute(): string
    {
        $name = trim($this->product_name ?? '');
        if ($name === '') return '';

        // 1. Strip leading value2 (size) if present at start
        $size = trim($this->value2 ?? '');
        if ($size !== '' && stripos($name, $size . ' ') === 0) {
            $name = trim(substr($name, strlen($size)));
        }

        // 2. Strip leading item_code if present at start
        $code = trim($this->item_code ?? '');
        if ($code !== '' && stripos($name, $code . ' ') === 0) {
            $name = trim(substr($name, strlen($code)));
        }

        return $name ?: trim($this->product_name ?? '');
    }

    /**
     * Variant pieces as ["Size: X", "Color: Y"], skipping empty values.
     * Plain scalars so callers can pass either an Eloquent product's
     * columns or a stdClass row from a raw/joined query.
     */
    public static function variantParts($value1, $value2): array
    {
        $parts = [];
        if (trim((string) $value2) !== '') {
            $parts[] = 'Size: ' . trim((string) $value2);
        }
        if (trim((string) $value1) !== '') {
            $parts[] = 'Color: ' . trim((string) $value1);
        }
        return $parts;
    }

    /**
     * "Size: X, Color: Y" — comma separated, no product name.
     */
    public static function variantLabel($value1, $value2): string
    {
        return implode(', ', self::variantParts($value1, $value2));
    }

    /**
     * Product name plus the variant stacked underneath in muted text:
     *
     *     Product Name
     *     Size: 7 | Color: Red
     *
     * Escaped + inline styled so the same call works in a normal Blade view
     * and inside a dompdf print view (dompdf ignores most stylesheet rules on
     * table cells, so the muted line carries its own style).
     */
    public static function nameWithVariant($name, $value1, $value2, bool $print = false): HtmlString
    {
        $html = e(trim((string) $name));
        $parts = self::variantParts($value1, $value2);

        if ($parts) {
            $size = $print ? '9px' : '11px';
            $html .= '<span class="product-variant-line" style="display:block;font-size:' . $size
                . ';line-height:1.35;color:#777777;">' . e(implode(' | ', $parts)) . '</span>';
        }

        return new HtmlString($html);
    }

    /**
     * Single-line variant of the above, for places that cannot hold markup:
     * <option> text, select2 labels, Excel exports, JS-built strings.
     *
     *     Product Name (Size: 7, Color: Red)
     */
    public static function nameWithVariantInline($name, $value1, $value2): string
    {
        $name = trim((string) $name);
        $label = self::variantLabel($value1, $value2);

        return $label === '' ? $name : $name . ' (' . $label . ')';
    }

    public function getVariantLabelAttribute(): string
    {
        return self::variantLabel($this->value1, $this->value2);
    }

    public function getNameWithVariantAttribute(): HtmlString
    {
        return self::nameWithVariant($this->product_name, $this->value1, $this->value2);
    }

    public function getNameWithVariantInlineAttribute(): string
    {
        return self::nameWithVariantInline($this->product_name, $this->value1, $this->value2);
    }

    // app/Models/Product.php

    public function category()
    {
       return $this->belongsTo(category::class, 'category', 'id');
    }

    public function subcategory()
    {
       return $this->belongsTo(subcategory::class, 'subcategory');
    }

    public function uomName()
    {
        return $this->belongsTo(uom::class, 'uom', 'id');
    }

    public static function repair()
    {
        // Get old products (ERP table) - adjust condition if needed
        $oldProducts = DB::table('product')->where('id', '>=', 66)->get();

        // Group by item_code (main product code)
        $grouped = $oldProducts->groupBy('item_code');

        foreach ($grouped as $code => $items) {
            $sample = $items->first();
            $productId = $sample->id; // Existing ERP product ID

            foreach ($items as $item) {
                // Generate SKU and Variant Name
                $sku = $code . '-' . strtoupper($item->value1 ?? 'GEN') . '-' . strtoupper($item->value2 ?? 'STD');
                $variantName = ($item->value1 ?? '') . ' / ' . ($item->value2 ?? '');

                // Check if variant with this SKU already exists
                $variant = ProductVariant::updateOrCreate(
                    ['sku' => $sku], // If SKU exists, update
                    [
                        'product_id' => $productId,
                        'variant_name' => $variantName,
                        'price' => $item->price ?? 0,
                        'stock' => $item->opening_stock ?? 0,
                        'image' => $item->product_image
                    ]
                );

                // Handle Variant Attributes
                $attributes = [];

                if ($item->attribute1 && $item->value1) {
                    $attributes[] = [
                        'variant_id' => $variant->id,
                        'attribute_name' => $item->attribute1,
                        'attribute_value' => $item->value1,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                if ($item->attribute2 && $item->value2) {
                    $attributes[] = [
                        'variant_id' => $variant->id,
                        'attribute_name' => $item->attribute2,
                        'attribute_value' => $item->value2,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }

                // Insert attributes if any
                if (!empty($attributes)) {
                    foreach ($attributes as $attr) {
                        // Avoid duplicate attributes
                        $exists = ProductVariantAttribute::where('variant_id', $attr['variant_id'])
                            ->where('attribute_name', $attr['attribute_name'])
                            ->where('attribute_value', $attr['attribute_value'])
                            ->first();
                        if (!$exists) {
                            ProductVariantAttribute::create($attr);
                        }
                    }
                }
            }
        }

        echo "Repair completed successfully!";

    }
}
