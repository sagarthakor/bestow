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
        $query = product::query()
            ->where('status','product')
            ->selectRaw('
                MIN(id)            as id,
                slug,
                item_code,
                MIN(product_name)  as product_name,
                MIN(cover_image)   as cover_image,
                MIN(product_image) as product_image,
                MIN(price)         as price,
                MIN(category)      as category,
                MIN(subcategory)   as subcategory,
                MIN(brand)         as brand,
                MIN(value1)        as value1,
                MIN(value2)        as value2,
                (SELECT COUNT(DISTINCT p2.value1)
                 FROM product p2
                 WHERE p2.slug      = product.slug
                   AND p2.item_code = product.item_code
                   AND p2.status    = "product"
                   AND p2.value1 IS NOT NULL
                   AND p2.value1   != ""
                ) as color_count
            ')
            ->groupBy('slug', 'item_code');

        /* =======================
   KEYWORD SEARCH
======================= */
        if ($request->filled('keyword')) {

            $keyword = trim($request->keyword);

            $keywords = explode(' ', $keyword);

            $query->where(function ($q) use ($keywords) {

                foreach ($keywords as $word) {
                    $q->where(function ($sub) use ($word) {
                        $sub->where('product_name', 'like', "%{$word}%")
                            ->orWhere('item_code', 'like', "%{$word}%")
                            ->orWhere('slug', 'like', "%{$word}%")
                            ->orWhere('value1', 'like', "%{$word}%") // color
                            ->orWhere('value2', 'like', "%{$word}%"); // size
                    });
                }

            });
        }

        
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
        if ($request->filled('min_price')) {
            $query->where('price','>=',$request->min_price);
        }
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

        $colors = product::where('status', 'product')
            ->whereNotNull('value1')->where('value1', '!=', '')
            ->distinct()->pluck('value1')
            ->map(fn($v) => trim($v))
            ->filter(fn($v) => $v !== '')
            ->unique(fn($v) => strtolower($v))
            ->values();

        $sizes = product::where('status', 'product')
            ->whereNotNull('value2')->where('value2', '!=', '')
            ->distinct()->pluck('value2')
            ->map(fn($v) => trim($v))
            ->filter(fn($v) => $v !== '')
            ->unique(fn($v) => strtolower($v))
            ->sort(SORT_NATURAL | SORT_FLAG_CASE)
            ->values();

        $brands = brand::all();

        // counts per brand (unfiltered)
        $brandCounts = product::where('status','product')
            ->whereNotNull('brand')->where('brand','!=','')
            ->select('brand', DB::raw('count(distinct item_code) as cnt'))
            ->groupBy('brand')
            ->pluck('cnt','brand');

        // counts per category
        $categoryCounts = product::where('status','product')
            ->whereNotNull('category')->where('category','!=','')
            ->select('category', DB::raw('count(distinct item_code) as cnt'))
            ->groupBy('category')
            ->pluck('cnt','category');

        // price min / max for slider
        $priceMin = (int) product::where('status','product')->min('price');
        $priceMax = (int) product::where('status','product')->max('price');

        return view('website.product.index',[
            'items'          => $products,
            'categories'     => category::with('subcategories')->get(),
            'colors'         => $colors,
            'sizes'          => $sizes,
            'brands'         => $brands,
            'brandCounts'    => $brandCounts,
            'categoryCounts' => $categoryCounts,
            'priceMin'       => $priceMin,
            'priceMax'       => $priceMax,
        ]);
    }

    public function listingByCategory(Request $request, $category_slug = null)
    {
        if ($category_slug) {
            $request->merge(['category' => $category_slug]);
        }

        return $this->index($request);
    }

    public function listingByBrand(Request $request, $brand_slug = null)
    {
        if ($brand_slug) {
            $request->merge(['brand' => $brand_slug]);
        }

        return $this->index($request);
    }

    public function details(Request $request, $slug, $code = null)
    {
        // Find the anchor product — by ID when available (avoids duplicate-slug collision), else by slug
        if ($code) {
            $anchor = product::where('status','product')->where('id', $code)->first();
        }
        if (empty($anchor)) {
            $anchor = product::where('status','product')->where('slug',$slug)->first();
        }
        if (!$anchor) abort(404);

        // Always scope variants to same slug + item_code so unrelated
        // products sharing item_code don't bleed into this product's colors/sizes.
        $allVariants = product::where('status','product')
            ->where('slug', $anchor->slug)
            ->where('item_code', $anchor->item_code)
            ->get();

        // Build color groups — each group also carries the representative product id for URL building
        $colorGroups = [];
        foreach ($allVariants as $v) {
            $color = trim($v->value1 ?? '');
            $groupKey = $color !== '' ? $color : $v->slug;

            if (!isset($colorGroups[$groupKey])) {
                $colorGroups[$groupKey] = [
                    'id'    => $v->id,
                    'color' => $color !== '' ? $color : $v->product_name,
                    'slug'  => $v->slug,
                    'image' => $v->cover_image ?: $v->product_image,
                    'sizes' => [],
                ];
            }
            if (trim($v->value2 ?? '') !== '') {
                $colorGroups[$groupKey]['sizes'][] = [
                    'id'    => $v->id,
                    'size'  => $v->value2,
                    'price' => $v->price,
                    'image' => $v->product_image,
                ];
            }
        }

        // Sort sizes naturally within each color
        foreach ($colorGroups as &$grp) {
            usort($grp['sizes'], fn($a,$b) => strnatcasecmp($a['size'], $b['size']));
        }
        unset($grp);
        $colorGroups = array_values($colorGroups);

        // Determine which variant is currently selected
        $selectedVariant = null;
        if ($request->filled('variant')) {
            $selectedVariant = $allVariants->firstWhere('id', $request->variant);
        }
        if (!$selectedVariant) {
            $selectedVariant = $anchor;
        }

        // Match the color label exactly as stored in colorGroups so JS can auto-select it
        $anchorColorVal = trim($selectedVariant->value1 ?? '');
        if ($anchorColorVal !== '') {
            $selectedColor = $anchorColorVal;
        } else {
            $matchingGroup = collect($colorGroups)->firstWhere('slug', $selectedVariant->slug);
            $selectedColor = $matchingGroup['color'] ?? 'Default';
        }

        return view('website.product.details', [
            'product'       => $selectedVariant,
            'colorGroups'   => $colorGroups,
            'selectedColor' => $selectedColor,
            'defaultSize'   => $selectedVariant->value2,
            'initialPrice'  => $selectedVariant->price,
            'initialImage'  => $selectedVariant->product_image,
        ]);
    }






}
