<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class product extends Model
{
    protected $table="product";
    public $timestamps=false;
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
