<?php

namespace App\Http\Controllers\Website;

use App\brand;
use App\Http\Controllers\Controller;
use App\product;
use App\category;
use App\subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = product::query()->where('status','product')->groupBy(['item_code','value1']);

        if ($request->filled('category')) {
            $category = $request->category;
            if(!is_array($request->category)){
                $category = [$request->category];
            }
            $categoryIds = category::whereIn('slug', $category)->pluck('id')->toArray();

            if ($categoryIds) {

                $subIds = subcategory::whereIn('category', $categoryIds)
                    ->pluck('id')->toArray();

                $query->where(function ($q) use ($categoryIds, $subIds) {
                    $q->whereIn('category', $categoryIds)
                        ->orWhereIn('subcategory', $subIds);
                });
            }
        }

// Subcategory Filter
        if ($request->filled('child_category')) {

            $subCategory = $request->child_category;
            if(!is_array($request->child_category)){
                $subCategory = [$request->child_category];
            }
            $subIds = subcategory::whereIn('slug', $subCategory)->pluck('id')->toArray();
            if ($subIds) {
                $query->whereIn('subcategory', $subIds);
            }
        }


        /* =======================
           BRAND FILTER
        ======================= */
        if ($request->filled('brand')) {
            $query->whereIn('brand', (array) $request->brand);
        }

        /* =======================
           COLOR FILTER
        ======================= */
        if ($request->filled('color')) {
            $query->whereIn('value1', (array) $request->color);
        }

        /* =======================
           SIZE FILTER
        ======================= */
        if ($request->filled('size')) {
            $query->whereIn('value2', (array) $request->size);
        }

        /* =======================
           PRICE FILTER
        ======================= */
        if ($request->filled('max_price')) {
            $query->where('price','<=',$request->max_price);
        }

        /* =======================
           SORT
        ======================= */
        if ($request->sort == 'price_low') {
            $query->orderBy('price','asc');
        } elseif ($request->sort == 'price_high') {
            $query->orderBy('price','desc');
        } else {
            $query->orderBy('id','desc');
        }

        $products = $query->paginate(50);

        $colors = product::whereNotNull('value1')->distinct()->pluck('value1');
        $sizes  = product::whereNotNull('value2')->distinct()->pluck('value2');

        $brands = brand::all();

        return view('website.product.index',[
            'items'=>$products,
            'categories'=>category::with('subcategories')->get(),
            'colors'=>$colors,
            'sizes'=>$sizes,
            'brands'=>$brands,
        ]);
    }

    public function details(\Illuminate\Http\Request $request)
    {
        $slug = $request->slug;
        $product = product::where('slug', $slug)->firstOrFail();

        // All variants for the same item_code
        $itemCode = $product->item_code;
        $variants = product::where('status', 'product','uomName')
            ->where('item_code', $itemCode)
            ->where('slug', '=', $slug)
            ->select('id','slug','product_name','product_image','price','attribute1','value1','attribute2','value2','item_code')
            ->orderBy('price','asc')
            ->get();
        // Group by color → sizes
        $groups = [];
        foreach ($variants as $v) {
            $color = $v->value1 ?: 'Default';
            $size  = $v->value2 ?: 'FREE';

            if (!isset($groups[$color])) {
                $groups[$color] = [
                    'color' => $color,
                    'image' => $v->product_image,
                    'sizes' => []
                ];
            }

            $groups[$color]['sizes'][] = [
                'id'            => $v->id,
                'size'          => (string)$size,
                'price'         => (float)$v->price,
                'product_image' => $v->product_image,
                'slug'          => $v->slug,
            ];
        }

        // Natural sort sizes inside each color
        foreach ($groups as &$g) {
            usort($g['sizes'], fn($a,$b) => strnatcasecmp($a['size'], $b['size']));

            if (empty($g['image']) && !empty($g['sizes'][0]['product_image'])) {
                $g['image'] = $g['sizes'][0]['product_image'];
            }
        }
        unset($g);

        $variant_groups = array_values($groups);

        // Defaults
        $defaultColor   = $variant_groups[0]['color'] ?? null;
        $defaultSizeObj = $variant_groups[0]['sizes'][0] ?? null;

        $initialImage = $defaultSizeObj['product_image'] ?? ($variant_groups[0]['image'] ?? $product->product_image);
        $initialPrice = $defaultSizeObj['price'] ?? (float)$product->price;

        // Multi images
        $thumbs = [];
        if (!empty($product->product_multi_image)) {
            $thumbs = array_filter(array_map('trim', explode(',', $product->product_multi_image)));
        }
        if (empty($thumbs) && !empty($product->product_image)) {
            $thumbs = [$product->product_image];
        }

        // clean name
        $product->clean_name = $product->clean_name ?? $product->product_name;

        // ⭐ AMAZON-STYLE SPECIFICATION AUTO BUILDER
        $specs = [

            'Brand'             => $product->brand_name,
            'Model'             => $product->model,
            'Make'              => $product->make,
            'SKU'               => $product->sku,
            'HSN Code'          => $product->hsn,
            'Item Code'         => $product->item_code,
            'UOM'               => $product->uomName->uom_name ?? null,

            'Outer Diameter'    => $product->outer_diameter,
            'Inner Diameter'    => $product->inner_diameter,
            'Thickness'         => $product->thikness,

            'Cotton'            => $product->cotton,
            'Polyester'         => $product->polyester,
            'Nylon'             => $product->nylon,
            'P.P. Yarn'         => $product->p_p_yarn,
            'Spendex'           => $product->spendex,
            'Elastics'          => $product->elastics,

            'Category'          => $product->category_name,
            'Subcategory'       => $product->subcategory_name,
            'Raw Material Group'=> $product->raw_material_group,
            'Item Group'        => $product->group_no,

            // Dynamic attributes
            $product->attribute1 => $product->value1,
            $product->attribute2 => $product->value2,
            $product->attribute3 => $product->value3,
            $product->attribute4 => $product->value4,
            $product->attribute5 => $product->value5,
            $product->attribute6 => $product->value6,
        ];


        // Remove empty values
        $specs = array_filter($specs, function($v) {
            return !is_null($v) && $v !== '';
        });

        // ⭐ DESCRIPTION HANDLING
        $description = $product->product_description ?: $product->description;

        return view('website.product.details', [
            'product'         => $product,
            'variant_groups'  => $variant_groups,
            'defaultColor'    => $defaultColor,
            'initialImage'    => $initialImage,
            'initialPrice'    => $initialPrice,
            'thumbs'          => $thumbs,

            // 👇 NEW
            'specs'           => $specs,
            'description'     => $description,
        ]);
    }



}
