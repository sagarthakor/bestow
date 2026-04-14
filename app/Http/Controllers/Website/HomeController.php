<?php

namespace App\Http\Controllers\Website;

use App\Banner;
use App\brand;
use App\category;
use App\Http\Controllers\Controller;
use App\product;
use App\subcategory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class HomeController extends Controller
{
    public function home()
    {
        // Top carousel banners
        $banners = Banner::where('type', 1)
            ->where('status', 1)
            ->orderByDesc('id')
            ->get();

        // Category strip (show only categories that have products)
        $categories = category::withCount(['products' => function ($q) {
            $q->where('status', 'product');
        }])
            ->having('products_count', '>', 0)
            ->orderBy('category_name')
            ->take(12)
            ->get();

        // New Arrivals: latest one product per category (order by MAX id desc)
        $newArrivals = product::where('status', 'product')
            ->select([
                'category',
                DB::raw('MAX(id) as id'),
                DB::raw('MIN(item_code) as item_code'),
                DB::raw('MIN(slug) as slug'),
                DB::raw('MIN(product_name) as product_name'),
                DB::raw('MIN(product_image) as product_image'),
                DB::raw('MIN(cover_image) as cover_image'),
                DB::raw('MIN(price) as price'),
                DB::raw('COUNT(DISTINCT value1) as color_count'),
            ])
            ->groupBy('category')
            ->orderBy(DB::raw('MAX(id)'), 'desc')
            ->get();

        return view('website.home', compact(
            'banners', 'categories', 'newArrivals'
        ));
    }

    public function all_brands()
    {
        $brands = Brand::orderBy('brand_name')->get();
        return view('website.brands.index', compact('brands'));
    }

    /**
     * ✅ List all categories
     */
    public function all_categories()
    {

        $categories = category::whereNull('slug')->orWhere('slug', '')->get();

        foreach ($categories as $cat) {
            $cat->slug = Str::slug($cat->category_name);
            $cat->save();
        }

        $categories = category::with('subcategories')->orderBy('category_name')->get();
        return view('website.all_category', compact('categories'));
    }

    /**
     * ✅ List all active flash deals
     */
    public function all_flash_deals()
    {
        $flashDeals = FlashDeal::where('status', 1)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now())
            ->orderBy('start_date', 'desc')
            ->get();

        return view('website.flash_deals.index', compact('flashDeals'));
    }

    /**
     * ✅ Flash deal details (products under this deal)
     */
    public function flash_deal_details($slug)
    {
        $flashDeal = FlashDeal::where('slug', $slug)->firstOrFail();

        $products = Product::whereIn('id', function ($q) use ($flashDeal) {
            $q->select('product_id')->from('flash_deal_products')
                ->where('flash_deal_id', $flashDeal->id);
        })->paginate(20);

        return view('website.flash_deals.details', compact('flashDeal', 'products'));
    }

    /**
     * ✅ Policy page
     * Example: /policy/terms-and-conditions/3
     */
    public function policy($slug, $id)
    {
        $policy = Policy::where('id', $id)->where('slug', $slug)->firstOrFail();
        return view('website.policy.show', compact('policy'));
    }

    public function about()
    {
        return view('website.about');
    }

    public function legal()
    {
        return view('website.our-legal');
    }
}
