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

    public function details(Request $request, $slug)
    {
        // Get ALL variants of this slug (same color)
        $variants = product::where('status','product')
            ->where('slug',$slug)
            ->get();

        if ($variants->isEmpty()) {
            abort(404);
        }

        // If ?variant=ID exists
        $selectedVariant = null;

        if ($request->filled('variant')) {
            $selectedVariant = $variants->firstWhere('id', $request->variant);
        }

        // fallback to first size
        if (!$selectedVariant) {
            $selectedVariant = $variants->first();
        }

        // Build single color group (Amazon style)
        $variant_group = [
            'color' => $selectedVariant->value1,
            'image' => $selectedVariant->product_image,
            'sizes' => []
        ];

        foreach ($variants as $v) {
            $variant_group['sizes'][] = [
                'id'    => $v->id,
                'size'  => $v->value2,
                'price' => $v->price,
                'image' => $v->product_image
            ];
        }

        // Natural size sorting
        usort($variant_group['sizes'], fn($a,$b) =>
        strnatcasecmp($a['size'],$b['size'])
        );

        return view('website.product.details',[
            'product'        => $selectedVariant,
            'variant_group'  => $variant_group,
            'defaultSize'    => $selectedVariant->value2,
            'initialPrice'   => $selectedVariant->price,
            'initialImage'   => $selectedVariant->product_image,
        ]);
    }






}
