<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\company;
use App\customers;
use App\finacial_year;
use App\product;
use App\quotation;
use App\quotation_item;
use App\salesman;
use App\state;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

// Backs the Android salesman app: log in with the same salesman_email /
// salesman_password used by the front.salesman.* storefront login
// (UserController::sales_check_password), browse the product catalogue,
// look up or quick-add a customer, and submit an order. An order submitted
// here is a 'quotation' with quot_stage left at its normal default
// ('Created'), the same as one entered from the admin panel - office
// reviews and converts it to a Sales Order through the existing, already
// wired Quotation screens, so no new admin-side review UI is needed.
class SalesmanApiController extends Controller
{
    function login(Request $request)
    {
        $request->validate([
            'salesman_email' => 'required',
            'salesman_password' => 'required',
        ]);

        // Matched loosely (case/whitespace-insensitive) rather than the exact
        // string equality UserController::sales_check_password uses for the
        // storefront login - a phone keyboard is more likely to drop/add a
        // space than a desk browser, and some salesman_email values in this
        // table have a stray internal space (data-entry noise, not signal).
        $normalizedEmail = strtolower(str_replace(' ', '', (string) $request->salesman_email));

        $salesman = salesman::whereRaw("LOWER(REPLACE(salesman_email, ' ', '')) = ?", [$normalizedEmail])
            ->where('salesman_password', $request->salesman_password)
            ->first();

        if (empty($salesman)) {
            return response()->json(['message' => 'Invalid email or password'], 401);
        }

        $salesman->api_token = Str::random(60);
        $salesman->save();

        return response()->json([
            'token' => $salesman->api_token,
            'salesman' => [
                'id' => $salesman->id,
                'name' => $salesman->salesman_name,
                'code' => $salesman->salesman_code,
                'area' => $salesman->salesman_area,
            ],
        ]);
    }

    function logout(Request $request)
    {
        $salesman = $request->attributes->get('salesman');
        $salesman->api_token = null;
        $salesman->save();

        return response()->json(['message' => 'Logged out']);
    }

    // Same word-order-independent match as AdminController::product_search_options,
    // with the catalogue fields (price/stock/uom/image) the app needs to show a
    // product card, which the admin picker doesn't return.
    function products(Request $request)
    {
        $term = trim((string) $request->get('term', ''));

        $query = product::select('product.*', 'gst.gst_per', 'uom.uom_name', 'stock_status.qty as stockqty')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->leftJoin('uom', 'uom.id', 'product.uom')
            ->leftJoin('stock_status', 'stock_status.product', 'product.id')
            ->where('product.status', 'product');

        $words = preg_split('/\s+/', $term, -1, PREG_SPLIT_NO_EMPTY) ?: [];
        foreach ($words as $word) {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $word) . '%';
            $query->where(function ($q) use ($like) {
                $q->where('product.product_name', 'like', $like)
                    ->orWhere('product.item_code', 'like', $like)
                    ->orWhere('product.value2', 'like', $like)
                    ->orWhere('product.value1', 'like', $like)
                    ->orWhere('product.sku', 'like', $like);
            });
        }

        $products = $query->orderBy('product.product_name', 'asc')
            ->orderBy('product.value2', 'asc')
            ->paginate($request->get('per_page', 30));

        $products->getCollection()->transform(function ($p) {
            return [
                'id' => $p->id,
                'item_code' => $p->item_code,
                'name' => product::nameWithVariantInline($p->product_name, $p->value1, $p->value2),
                'value1' => $p->value1,
                'value2' => $p->value2,
                'price' => $p->price,
                'gst_per' => $p->gst_per,
                'uom' => $p->uom_name,
                'stock_qty' => $p->stockqty,
                'image' => $p->product_image,
            ];
        });

        return response()->json($products);
    }

    function customers(Request $request)
    {
        $term = trim((string) $request->get('term', ''));

        $query = customers::query();
        if ($term !== '') {
            $like = '%' . str_replace(['%', '_'], ['\%', '\_'], $term) . '%';
            $query->where(function ($q) use ($like) {
                $q->where('customer_name', 'like', $like)
                    ->orWhere('primary_phone', 'like', $like)
                    ->orWhere('owner_mobile', 'like', $like);
            });
        }

        $customers = $query->orderBy('customer_name', 'asc')
            ->limit(30)
            ->get(['id', 'customer_name', 'primary_phone', 'billing_address', 'billing_city']);

        return response()->json(['results' => $customers]);
    }

    // Deliberately fewer required fields than AdminController::customer_save -
    // that form is filled at a desk with the customer's full paperwork; this
    // one is filled standing in a shop. Office fills in GST/industry/etc.
    // later from the admin panel when reviewing the resulting quotation.
    function storeCustomer(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|max:255',
            'primary_phone' => 'required|max:255',
            'billing_address' => 'required',
            'billing_city' => 'required',
            'billing_state' => 'required',
        ]);

        $customer = new customers();
        $customer->customer_name = $request->customer_name;
        $customer->primary_phone = $request->primary_phone;
        $customer->primary_email = $request->primary_email;
        $customer->billing_address = $request->billing_address;
        $customer->shipping_address = $request->shipping_address ?: $request->billing_address;
        $customer->billing_city = $request->billing_city;
        $customer->shipping_city = $request->shipping_city ?: $request->billing_city;
        $customer->billing_state = $request->billing_state;
        $customer->shipping_state = $request->shipping_state ?: $request->billing_state;
        $customer->billing_postalcode = $request->billing_postalcode;
        $customer->shipping_postalcode = $request->shipping_postalcode ?: $request->billing_postalcode;
        $customer->billing_country = $request->billing_country ?: 'India';
        $customer->shipping_country = $request->shipping_country ?: $customer->billing_country;
        $customer->created_time = date('Y-m-d h:i:s A');
        $customer->save();

        return response()->json(['id' => $customer->id, 'customer_name' => $customer->customer_name]);
    }

    // items: [{product, qty, price?}]. price defaults to the product's list
    // price; tax split mirrors FrontController::placeorder (CGST+SGST when the
    // customer's state matches the company's, otherwise a single GST/IGST line).
    function storeOrder(Request $request)
    {
        $salesman = $request->attributes->get('salesman');

        $request->validate([
            'customer' => 'required|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product' => 'required|exists:product,id',
            'items.*.qty' => 'required|numeric|min:0.01',
        ]);

        date_default_timezone_set('Asia/Kolkata');

        $customer = customers::select('customers.*', 'state.state_code')
            ->leftJoin('state', 'state.id', 'customers.billing_state')
            ->where('customers.id', $request->customer)
            ->first();

        $company = company::select('company.*', 'state.state_code')
            ->leftJoin('state', 'state.id', 'company.state')
            ->first();

        $now = date('Y-m-d');
        $finacial_year = finacial_year::where('start_date', '<=', $now)->where('end_date', '>=', $now)->first();

        $qno = $finacial_year
            ? quotation::where('finacial_year', $finacial_year->id)->max('quot_no')
            : quotation::max('quot_no');

        $start = $finacial_year ? date('y', strtotime($finacial_year->start_date)) : date('y');
        $end = $finacial_year ? date('y', strtotime($finacial_year->end_date)) : date('y', strtotime('+1 year'));
        $n2 = str_pad(($qno ?: 0) + 1, 4, 0, STR_PAD_LEFT) . '/' . $start . '-' . $end;

        $quot = new quotation();
        $quot->quot_no = ($qno ?: 0) + 1;
        $quot->quotation_no = $n2;
        $quot->quot_date = $now;
        $quot->valid_until = date('Y-m-d', strtotime('+7 days'));
        $quot->customer = $customer->id;
        $quot->customer_name = $customer->customer_name;
        $quot->subject = 'Quotation ' . $n2 . ' - ' . $customer->customer_name;
        $quot->quot_stage = 'Created';
        $quot->billing_address = $customer->billing_address;
        $quot->shipping_address = $customer->shipping_address;
        $quot->billing_city = $customer->billing_city;
        $quot->shipping_city = $customer->shipping_city;
        $quot->billing_state = $customer->billing_state;
        $quot->shipping_state = $customer->shipping_state;
        $quot->billing_postalcode = $customer->billing_postalcode;
        $quot->shipping_postalcode = $customer->shipping_postalcode;
        $quot->billing_country = $customer->billing_country;
        $quot->shipping_country = $customer->shipping_country;
        $quot->term_condition = $request->remark ?: '-';
        $quot->remark = $request->remark;
        $quot->datetime = date('Y-m-d h:i:s a');
        $quot->finacial_year = $finacial_year ? $finacial_year->id : null;
        $quot->salesman_id = $salesman->id;

        $tamt = $tcgst = $tsgst = $tgst = $tgrand = 0;

        // Resolve products before writing anything so a bad id fails the
        // whole submission instead of leaving a half-built quotation.
        $products = product::select('product.*', 'gst.gst_per')
            ->leftJoin('gst', 'gst.id', 'product.gst')
            ->whereIn('product.id', collect($request->items)->pluck('product'))
            ->get()
            ->keyBy('id');

        if (!$quot->save()) {
            return response()->json(['message' => 'Could not create order'], 500);
        }

        foreach ($request->items as $line) {
            $p = $products->get($line['product']);
            $price = isset($line['price']) ? (float) $line['price'] : (float) $p->price;
            $qty = (float) $line['qty'];
            $amount = $price * $qty;
            $tamt += $amount;

            $cgstper = $sgstper = $cgstamt = $sgstamt = $igst = $igstamt = 0;
            if ($customer->tax_preference == 'true') {
                if ($company && $company->state_code == $customer->state_code) {
                    $cgstper = $p->gst_per / 2;
                    $cgstamt = $amount * $cgstper / 100;
                    $sgstper = $p->gst_per / 2;
                    $sgstamt = $amount * $sgstper / 100;
                    $tcgst += $cgstamt;
                    $tsgst += $sgstamt;
                } else {
                    $igst = $p->gst_per;
                    $igstamt = $amount * $igst / 100;
                    $tgst += $igstamt;
                }
            }

            $item = new quotation_item();
            $item->quot_no = $quot->quot_no;
            $item->quotation_no = $n2;
            $item->customer = $customer->id;
            $item->product = $p->id;
            $item->description = $p->product_name;
            $item->item_code = $p->item_code;
            $item->qty = $qty;
            $item->price = $price;
            $item->amount = $amount;
            $item->discount_per = 0;
            $item->discount_amount = 0;
            $item->cgst_per = $cgstper;
            $item->cgst_amount = $cgstamt;
            $item->sgst_per = $sgstper;
            $item->sgst_amount = $sgstamt;
            $item->gst_per = $igst;
            $item->gst_amount = $igstamt;
            $item->grand_total = $amount + $cgstamt + $sgstamt + $igstamt;
            $item->save();

            $tgrand += $item->grand_total;
        }

        $quot->net_amount = $tamt;
        $quot->cgsttotal = $tcgst;
        $quot->sgsttotal = $tsgst;
        $quot->gst_amount = $tgst;
        $quot->grand_total = $tgrand;
        $quot->save();

        return response()->json([
            'id' => $quot->id,
            'quotation_no' => $quot->quotation_no,
            'grand_total' => $quot->grand_total,
            'quot_stage' => $quot->quot_stage,
        ]);
    }

    function myOrders(Request $request)
    {
        $salesman = $request->attributes->get('salesman');

        $orders = quotation::where('salesman_id', $salesman->id)
            ->orderBy('id', 'desc')
            ->paginate($request->get('per_page', 20));

        return response()->json($orders);
    }
}
