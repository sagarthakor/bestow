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
        $query = product::where('status', 'product')
            ->select(
                'item_code',
                DB::raw('MIN(id) as id'),
                DB::raw('MIN(slug) as slug'),
                DB::raw('MIN(product_name) as product_name'),
                DB::raw('MIN(category) as category'),
                DB::raw('MIN(subcategory) as subcategory'),
                DB::raw('MIN(product_image) as product_image'),
                DB::raw('MIN(price) as price')
            )
            ->groupBy('item_code');

        // ✅ Keyword Search
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('product_name', 'like', "%{$keyword}%")
                    ->orWhere('slug', 'like', "%{$keyword}%");
            });
        }

        // ✅ Category + Subcategory Filter
        $categoryIds = $subcategoryIds = [];
        if ($request->filled('slugs')) {
            $categoryIds = category::whereIn('slug', (array)$request->slugs)->pluck('id')->toArray();
        }
        if ($request->filled('child_category')) {
            $subcategoryIds = subcategory::whereIn('slug', (array)$request->child_category)->pluck('id')->toArray();
        }
        if (!empty($categoryIds) && empty($subcategoryIds)) {
            $query->whereIn('category', $categoryIds);
        } elseif (empty($categoryIds) && !empty($subcategoryIds)) {
            $query->whereIn('subcategory', $subcategoryIds);
        } elseif (!empty($categoryIds) && !empty($subcategoryIds)) {
            $query->where(function ($q) use ($categoryIds, $subcategoryIds) {
                $q->whereIn('category', $categoryIds)
                    ->orWhereIn('subcategory', $subcategoryIds);
            });
        }

        // ✅ Brand Filter
        if ($request->filled('brand')) {
            $brandIds = (array)$request->brand;
            $query->whereIn('brand', $brandIds); // Adjust if column name differs
        }

        // ✅ Price Filter
        if ($request->filled('min_price')) {
            $query->having('price', '>=', (float)$request->min_price);
        }
        if ($request->filled('max_price')) {
            $query->having('price', '<=', (float)$request->max_price);
        }

        // ✅ Sorting
        switch ($request->sort_by) {
            case 'oldest': $query->orderBy(DB::raw('MIN(id)'), 'asc'); break;
            case 'price-asc': $query->orderBy('price', 'asc'); break;
            case 'price-desc': $query->orderBy('price', 'desc'); break;
            default: $query->orderBy(DB::raw('MIN(id)'), 'desc'); break;
        }

        // ✅ Paginate
        $products = $query->paginate(32);

        // ✅ Clean Name & Default Variant
        $items = $products->getCollection()->map(function ($p) {
            $p->clean_name = preg_replace('/^\d+\s+\d+\s+/', '', $p->product_name);
            $variant = product::where('item_code', $p->item_code)
                ->where('status', 'product')
                ->select('id', 'value1', 'value2', 'product_image', 'price')
                ->first();
            $p->default_variant_id = $variant->id ?? $p->id;
            $p->default_color = $variant->value1 ?? null;
            $p->default_size = $variant->value2 ?? null;
            $p->default_image = $variant->product_image ?? $p->product_image;
            $p->default_price = $variant->price ?? $p->price;
            return $p;
        });
        $products->setCollection($items);

        // ✅ Pass to View
        $categories = category::with('subcategories')->get();
        $brands = Brand::select('id','brand_name')->get();

        return view('website.product.index', [
            'items'      => $products,
            'categories' => $categories,
            'brands'     => $brands,
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
