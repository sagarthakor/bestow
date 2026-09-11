<?php

namespace App\Http\Controllers\Website;

use App\brand;
use App\Http\Controllers\Controller;
use App\product;
use App\category;
use App\subcategory;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = product::query()->where('status','product');

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
           GROUP INTO ONE CARD PER PRODUCT FAMILY
           `slug` isn't a reliable "one product" key on its own (see productFamilyKey()
           for why — legacy imports sometimes give each size/color its own slug), so collapse
           matching rows here the same way the details page does, instead of a SQL GROUP BY.
        ======================= */
        $rows = $query->get();

        $cards = $rows->groupBy(fn($r) => $this->productFamilyKey($r))->map(function ($members) {
            $rep = $members->sortBy('id')->first();
            $cheapest = $members->sortBy('price')->first();
            $rep->price = $cheapest->price;
            $rep->mrp   = $cheapest->mrp;
            $rep->product_image = $members->pluck('product_image')->first(fn($v) => !empty($v)) ?: $rep->product_image;
            $rep->color_count = $members->pluck('value1')
                ->map(fn($v) => trim($v ?? ''))
                ->filter(fn($v) => $v !== '')
                ->unique(fn($v) => strtolower($v))
                ->count();
            return $rep;
        })->values();

        /* =======================
           SORT
        ======================= */
        if ($request->sort == 'price_low') {
            $cards = $cards->sortBy('price')->values();
        } elseif ($request->sort == 'price_high') {
            $cards = $cards->sortByDesc('price')->values();
        } else {
            $cards = $cards->sortByDesc('id')->values();
        }

        $perPage = 50;
        $page = LengthAwarePaginator::resolveCurrentPage();
        $products = new LengthAwarePaginator(
            $cards->forPage($page, $perPage)->values(),
            $cards->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );

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

        // Scope variants to the same product family. `slug` + item_code alone is not always
        // reliable — legacy bulk imports left some unrelated products sharing an identical
        // slug for the same item_code, which merges their sizes/colors together on this page
        // (e.g. item_code "07": a "HALF TERRY" sock and several completely different sock
        // designs all share one slug). Narrow that down using a normalized product name (any
        // trailing "-{color}-{size}" or "-{size}" the import baked onto the name is stripped
        // before comparing), which separates those unrelated products correctly.
        //
        // Some other legacy imports use a naming style this can't parse (e.g. a leading size
        // index like "1 201 BLACK SOCKS..", "2 201 BLACK SOCKS.."), where every row would look
        // like its own unique product and the narrowed match would wrongly find nothing else.
        // When that happens (narrowed match count <= 1) fall back to the original, wider
        // slug + item_code match so genuinely-fine product families are never broken.
        $itemCodeCandidates = product::where('status','product')
            ->where('item_code', $anchor->item_code)
            ->get();

        $anchorKey = $this->productFamilyKey($anchor);
        $allVariants = $itemCodeCandidates->filter(fn($v) => $this->productFamilyKey($v) === $anchorKey)->values();

        if ($allVariants->count() <= 1) {
            $allVariants = $itemCodeCandidates->where('slug', $anchor->slug)->values();
        }

        // Build color groups — each group also carries the representative product id for URL building.
        // $allVariants is already scoped to one product family (see productFamilyKey() above), so when
        // color is blank every row here belongs in the same single "no color" bucket — falling back to
        // $v->slug here would wrongly re-split them, since sibling sizes can carry different slugs.
        $colorGroups = [];
        foreach ($allVariants as $v) {
            $color = trim($v->value1 ?? '');
            $groupKey = $color !== '' ? $color : '__default__';

            if (!isset($colorGroups[$groupKey])) {
                $colorGroups[$groupKey] = [
                    'id'    => $v->id,
                    'color' => $color !== '' ? $color : $v->product_name,
                    'slug'  => $v->slug,
                    'image' => $v->cover_image ?: $v->product_image,
                    'price' => $v->price,
                    'mrp'   => $v->mrp,
                    'sizes' => [],
                ];
            }
            if (trim($v->value2 ?? '') !== '') {
                $colorGroups[$groupKey]['sizes'][] = [
                    'id'    => $v->id,
                    'size'  => $v->value2,
                    'price' => $v->price,
                    'mrp'   => $v->mrp,
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
            $matchingGroup = collect($colorGroups)->first(function ($grp) use ($selectedVariant) {
                return $grp['id'] == $selectedVariant->id
                    || collect($grp['sizes'])->contains('id', $selectedVariant->id);
            });
            $selectedColor = $matchingGroup['color'] ?? 'Default';
        }

        return view('website.product.details', [
            'product'       => $selectedVariant,
            'colorGroups'   => $colorGroups,
            'selectedColor' => $selectedColor,
            'defaultSize'   => $selectedVariant->value2,
            'initialPrice'  => $selectedVariant->price,
            'initialMrp'    => $selectedVariant->mrp,
            'initialImage'  => $selectedVariant->product_image,
        ]);
    }

    // Normalized identity for "same product, different size/color" grouping. Legacy imports
    // used two different conventions for baking the size into the product name instead of
    // relying only on the value2 column, so strip whichever one applies before comparing names
    // — otherwise every size looks like a different product:
    //   1) trailing: "...HALF TERRY-White-01" for size 01 (color+size, or just size, suffixed)
    //   2) leading:  "1 211 BLACK SOCKS.." / "2 211 BLACK SOCKS.." for sizes 01/02 (a plain
    //      numeric index prefixed, unrelated to the actual value2 string like "7JR"/"8FREE")
    // Rows that don't follow either convention simply compare on their full (unchanged) name.
    private function productFamilyKey($product)
    {
        $name  = trim($product->product_name ?? '');
        $color = trim($product->value1 ?? '');
        $size  = trim($product->value2 ?? '');

        $stripped = $name;
        foreach ([trim($color.'-'.$size, '-'), $size, $color] as $suffix) {
            if ($suffix !== '' && Str::endsWith($name, '-'.$suffix)) {
                $stripped = substr($name, 0, -strlen('-'.$suffix));
                break;
            }
        }

        if ($stripped === $name) {
            $stripped = preg_replace('/^\d+\s+/', '', $name);
        }

        return $product->item_code.'|'.Str::lower(trim($stripped));
    }






}
