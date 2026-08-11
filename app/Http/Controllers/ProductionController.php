<?php

namespace App\Http\Controllers;

use App\customers;
use App\formula_mst;
use App\formula_mst_item;
use App\machine;
use App\packaging;
use App\packaging_batch_record;
use App\packaging_machine;
use App\packaging_machine_allocate;
use App\pressing;
use App\pressing_batch_record;
use App\pressing_machine;
use App\pressing_machine_allocate;
use App\product;
use App\production_material;
use App\purchase_required_material;
use App\purchase_requirement;
use App\stitching;
use App\stitching_batch_record;
use App\stitching_machine;
use App\stitching_machine_allocate;
use App\stock_book;
use App\stock_status;
use App\washing;
use App\washing_batch_record;
use App\washing_machine;
use App\washing_machine_allocate;
use Illuminate\Http\Request;
use App\production;
use Illuminate\Support\Facades\DB;
use Session;
use App\batch_record;
use App\raw_material_group;

class ProductionController extends Controller
{
    /**
     * Values carried in the query string by "Move to Production" on the sales
     * out-of-stock report, so the batch form opens with the shortfall already
     * filled in instead of the user re-picking the product by hand.
     * Everything is optional - a plain visit just gets an empty form.
     */
    private function productionPrefill(Request $request): array
    {
        return [
            'finish_product' => $request->finish_product ?: null,
            'nos'            => $request->nos ?: null,
            'customer'       => $request->customer ?: null,
            'so_no'          => $request->so_no ?: null,
        ];
    }

    //
    function add_batch(Request $request)
    {
        $machine = machine::find($request->id);
        $customer=customers::orderBy("customer_name","asc")->get();
        $product=product::where("status","product")->orderBy("product_name","asc")->get();
        $prefill = $this->productionPrefill($request);

        return view("admin.production.add_batch",compact("machine","customer","product","prefill"));
    }

    function washing_pending(Request $request)
    {

        $machine = washing_machine::orderBy("machine_name", "asc")->get();

        $production = washing::select("washing.*", 'customers.customer_name', "washing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("washing_machine", "washing_machine.id", "washing.machine")
            ->leftJoin("product", "product.id", "washing.finish_product")
            ->leftJoin("customers", "customers.id", "washing.customer")
            ->orderBy("washing.id", "desc")
            ->where("washing_status","N")
            ->get();
        return view("admin.production.washing_pending_machine", compact("production", "machine"));

    }

    public function pressing_delete(Request $request)
    {
        pressing::whereId($request->id)->delete();

        return redirect()->route('pressing.dashboard')->with('message','pressing delete successfully');
    }

    public function washing_delete(Request $request)
    {
        washing::whereId($request->id)->delete();

        return redirect()->route('washing.dashboard')->with('message','washing delete successfully');
    }

    function pressing_pending(Request $request)
    {

        $machine = pressing_machine::orderBy("machine_name", "asc")->get();

        $production = pressing::select("pressing.*", 'customers.customer_name', "pressing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("pressing_machine", "pressing_machine.id", "pressing.machine")
            ->leftJoin("product", "product.id", "pressing.finish_product")
            ->leftJoin("customers", "customers.id", "pressing.customer")
            ->orderBy("pressing.id", "desc")
            ->where("pressing_status","N")
            ->get();
        return view("admin.production.pressing_pending_machine", compact("production", "machine"));

    }

    function packaging_pending(Request $request)
    {

        $machine = packaging_machine::orderBy("machine_name", "asc")->get();

        $production = packaging::select("packaging.*", 'customers.customer_name', "packaging_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("packaging_machine", "packaging_machine.id", "packaging.machine")
            ->leftJoin("product", "product.id", "packaging.finish_product")
            ->leftJoin("customers", "customers.id", "packaging.customer")
            ->orderBy("packaging.id", "desc")
            ->where("packaging_status","N")
            ->get();
        return view("admin.production.packaging_pending_machine", compact("production", "machine"));

    }

    function stiching_pending(Request $request)
    {

        $machine = stitching_machine::orderBy("machine_name", "asc")->get();

        $production = stitching::select("stitching.*", 'customers.customer_name', "stitching_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("stitching_machine", "stitching_machine.id", "stitching.machine")
            ->leftJoin("product", "product.id", "stitching.finish_product")
            ->leftJoin("customers", "customers.id", "stitching.customer")
            ->orderBy("stitching.id", "desc")
            ->where("stitching_status","N")
            ->get();
        return view("admin.production.stitching_pending_machine", compact("production", "machine"));

    }

    function production_complete_batch(Request $request)
    {
        $machine = machine::find($request->id);

        $list =new production();
        $list=$list->select("production.*", 'customers.customer_name', "machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $list=$list->leftJoin("machine", "machine.id", "production.machine");
        $list=$list->leftJoin("product", "product.id", "production.finish_product");
        $list=$list->leftJoin("customers", "customers.id", "production.customer");

        if(isset($request->batch_no))
        {
            $list=$list->where("production.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $list=$list->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $list=$list->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $list=$list->where("production.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->production_nos))
        {
            $list=$list->where("production.total_production","like",'%'.$request->production_nos.'%');
        }
        if(isset($request->size))
        {
            $list=$list->where("production.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $list=$list->where("production.production_status","like",'%'.$request->status.'%');
        }
        $list=$list->where("production.machine",$request->id);
        $list=$list->where("production.production_status","Y");
        $list=$list->orderBy("production.id", "desc");
        $list=$list->paginate(session('records_per_page', 30));

        return view("admin.production.machineWiseCompleteBatch", compact("list", "machine"));

    }
    function production_complete(Request $request)
    {
        $list = production::select("production.*","customers.customer_name", "machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("machine", "machine.id", "production.machine")
            ->leftJoin("customers", "customers.id", "production.customer")
            ->leftJoin("product", "product.id", "production.finish_product")
            ->where("production_status","=","Y")
            ->orderBy("production.id", "desc")
            ->paginate(session('records_per_page', 30));

        $machine=machine::orderBy("machine_name","asc")->get();

        return view("admin.production.production_complete",compact("machine",'list'));
    }

    function washing_complete(Request $request)
    {
        $production = washing::select("washing.*","customers.customer_name", "washing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("washing_machine", "washing_machine.id", "washing.machine")
            ->leftJoin("customers", "customers.id", "washing.customer")
            ->leftJoin("product", "product.id", "washing.finish_product")
            ->where("washing_status","=","Y")
            ->orderBy("washing.id", "desc")
            ->paginate(session('records_per_page', 30));

        $machine=washing_machine::orderBy("machine_name","asc")->get();

        return view("admin.production.washingCompleteMachine",compact("machine",'production'));
    }

    function pressing_complete(Request $request)
    {
        $production = pressing::select("pressing.*","customers.customer_name", "pressing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("pressing_machine", "pressing_machine.id", "pressing.machine")
            ->leftJoin("customers", "customers.id", "pressing.customer")
            ->leftJoin("product", "product.id", "pressing.finish_product")
            ->where("pressing_status","=","Y")
            ->orderBy("pressing.id", "desc")
            ->paginate(session('records_per_page', 30));

        $machine=pressing_machine::orderBy("machine_name","asc")->get();

        return view("admin.production.pressingCompleteMachine",compact("machine",'production'));
    }

    function packaging_complete(Request $request)
    {
        $production = packaging::select("packaging.*","customers.customer_name", "packaging_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("packaging_machine", "packaging_machine.id", "packaging.machine")
            ->leftJoin("customers", "customers.id", "packaging.customer")
            ->leftJoin("product", "product.id", "packaging.finish_product")
            ->where("packaging_status","=","Y")
            ->orderBy("packaging.id", "desc")
            ->paginate(session('records_per_page', 30));

        $machine=packaging_machine::orderBy("machine_name","asc")->get();

        return view("admin.production.packagingCompleteMachine",compact("machine",'production'));
    }

    function stitching_complete(Request $request)
        {
            $production = stitching::select("stitching.*","customers.customer_name", "stitching_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
                ->leftJoin("stitching_machine", "stitching_machine.id", "stitching.machine")
                ->leftJoin("customers", "customers.id", "stitching.customer")
                ->leftJoin("product", "product.id", "stitching.finish_product")
                ->where("stitching_status","=","Y")
                ->orderBy("stitching.id", "desc")
                ->paginate(session('records_per_page', 30));

            $machine=stitching_machine::orderBy("machine_name","asc")->get();

            return view("admin.production.stitchingCompleteMachine",compact("machine",'production'));
    }

    function production_dashboard(Request $request)
    {
        $production = production::select("production.*", "machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("machine", "machine.id", "production.machine")
            ->leftJoin("product", "product.id", "production.finish_product")
            ->orderBy("production.id", "desc")
            ->count();
       // dd($production);
        $productionComplete=production::select("production.*", "machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("machine", "machine.id", "production.machine")
            ->leftJoin("product", "product.id", "production.finish_product")
            ->where("production.production_status","Y")
            ->orderBy("production.id", "desc")
            ->count();

        $productionPending=production::select("production.*", "machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("machine", "machine.id", "production.machine")
            ->leftJoin("product", "product.id", "production.finish_product")
            ->where("production.production_status","N")
            ->orderBy("production.id", "desc")
            ->count();
       // dd($productionPending);
        $stitching = stitching::select("stitching.*", "stitching_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("stitching_machine", "stitching_machine.id", "stitching.machine")
            ->leftJoin("product", "product.id", "stitching.finish_product")
            ->orderBy("stitching.id", "desc")
            ->count();

        $stitchingComplete = stitching::select("stitching.*", "stitching_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("stitching_machine", "stitching_machine.id", "stitching.machine")
            ->leftJoin("product", "product.id", "stitching.finish_product")
            ->where("stitching_status","Y")
            ->orderBy("stitching.id", "desc")
            ->count();

        $stitchingPending = stitching::select("stitching.*", "stitching_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("stitching_machine", "stitching_machine.id", "stitching.machine")
            ->leftJoin("product", "product.id", "stitching.finish_product")
            ->where("stitching_status","N")
            ->orderBy("stitching.id", "desc")
            ->count();

        $pressingComplete = pressing::select("pressing.*", "pressing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("pressing_machine", "pressing_machine.id", "pressing.machine")
            ->leftJoin("product", "product.id", "pressing.finish_product")
            ->where("pressing_status","Y")
            ->orderBy("pressing.id", "desc")
            ->count();

        $pressingPending = pressing::select("pressing.*", "pressing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("pressing_machine", "pressing_machine.id", "pressing.machine")
            ->leftJoin("product", "product.id", "pressing.finish_product")
            ->Where("pressing_status","N")
            ->orderBy("pressing.id", "desc")
            ->count();

        $washingComplete = washing::select("washing.*", "washing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("washing_machine", "washing_machine.id", "washing.machine")
            ->leftJoin("product", "product.id", "washing.finish_product")
            ->where("washing_status","Y")
            ->orderBy("washing.id", "desc")
            ->count();

        $washingPending = washing::select("washing.*", "washing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("washing_machine", "washing_machine.id", "washing.machine")
            ->leftJoin("product", "product.id", "washing.finish_product")
            ->Where("washing_status","N")
            ->orderBy("washing.id", "desc")
            ->count();

        $packagingComplete = packaging::select("packaging.*", "packaging_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("packaging_machine", "packaging_machine.id", "packaging.machine")
            ->leftJoin("product", "product.id", "packaging.finish_product")
            ->where("packaging_status","Y")
            ->orderBy("packaging.id", "desc")
            ->count();

        $packagingPending = packaging::select("packaging.*", "packaging_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("packaging_machine", "packaging_machine.id", "packaging.machine")
            ->leftJoin("product", "product.id", "packaging.finish_product")
            ->Where("packaging_status","N")
            ->orderBy("packaging.id", "desc")
            ->count();
        //dd($pending);


        return view("admin.production.production_dashboard",compact("packagingComplete","packagingPending","washingComplete","washingPending","pressingComplete","pressingPending","stitchingComplete","stitchingPending","productionPending","productionComplete","production","stitching"));
    }

    function production_process(Request $request)
    {
        $pending = production::select("production.*", "machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("machine", "machine.id", "production.machine")
            ->leftJoin("product", "product.id", "production.finish_product")
            ->where("production_status","N")
            ->orderBy("production.id", "desc")
            ->count();
        //dd($pending);
        $complete = production::select("production.*", "machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("machine", "machine.id", "production.machine")
            ->leftJoin("product", "product.id", "production.finish_product")
            ->where("production_status","=","Y")
            ->orderBy("production.id", "desc")
            ->count();

        $total = production::select("production.*", "machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("machine", "machine.id", "production.machine")
            ->leftJoin("product", "product.id", "production.finish_product")
            ->orderBy("production.id", "desc")
            ->count();


        return view("admin.production.production_process",compact("pending","complete","total"));
    }

    function washing_dashboard(Request $request)
    {
        $pending = washing::select("washing.*", 'customers.customer_name', "washing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("washing_machine", "washing_machine.id", "washing.machine")
            ->leftJoin("product", "product.id", "washing.finish_product")
            ->leftJoin("customers", "customers.id", "washing.customer")
            ->Where("washing_status","=","N")
            ->orderBy("washing.id", "desc")
            ->count();

        $complete = washing::select("washing.*", 'customers.customer_name', "washing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("washing_machine", "washing_machine.id", "washing.machine")
            ->leftJoin("product", "product.id", "washing.finish_product")
            ->leftJoin("customers", "customers.id", "washing.customer")
            ->where("washing_status","=","Y")
            ->orderBy("washing.id", "desc")
            ->count();

        return view("admin.production.washing_dashboard",compact("pending","complete"));
    }

    function packaging_dashboard(Request $request)
    {
        $pending = packaging::select("packaging.*", 'customers.customer_name', "packaging_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("packaging_machine", "packaging_machine.id", "packaging.machine")
            ->leftJoin("product", "product.id", "packaging.finish_product")
            ->leftJoin("customers", "customers.id", "packaging.customer")
            ->Where("packaging_status","=","N")
            ->orderBy("packaging.id", "desc")
            ->count();

        $complete = packaging::select("packaging.*", 'customers.customer_name', "packaging_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("packaging_machine", "packaging_machine.id", "packaging.machine")
            ->leftJoin("product", "product.id", "packaging.finish_product")
            ->leftJoin("customers", "customers.id", "packaging.customer")
            ->where("packaging_status","=","Y")
            ->orderBy("packaging.id", "desc")
            ->count();

        return view("admin.production.packaging_dashboard",compact("pending","complete"));
    }

    function pressing_dashboard(Request $request)
    {
        $pending = pressing::select("pressing.*", 'customers.customer_name', "pressing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("pressing_machine", "pressing_machine.id", "pressing.machine")
            ->leftJoin("product", "product.id", "pressing.finish_product")
            ->leftJoin("customers", "customers.id", "pressing.customer")
            ->Where("pressing_status","=","N")
            ->orderBy("pressing.id", "desc")
            ->count();

        $complete = pressing::select("pressing.*", 'customers.customer_name', "pressing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("pressing_machine", "pressing_machine.id", "pressing.machine")
            ->leftJoin("product", "product.id", "pressing.finish_product")
            ->leftJoin("customers", "customers.id", "pressing.customer")
            ->where("pressing_status","=","Y")
            ->orderBy("pressing.id", "desc")
            ->count();

        return view("admin.production.pressing_dashboard",compact("pending","complete"));
    }

    function stitching_dashboard(Request $request)
    {
        $pending = stitching::select("stitching.*", 'customers.customer_name', "stitching_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("stitching_machine", "stitching_machine.id", "stitching.machine")
            ->leftJoin("product", "product.id", "stitching.finish_product")
            ->leftJoin("customers", "customers.id", "stitching.customer")
            ->where("stitching_status","N")
            ->orderBy("stitching.id", "desc")
            ->count();

        $complete = stitching::select("stitching.*", 'customers.customer_name', "stitching_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("stitching_machine", "stitching_machine.id", "stitching.machine")
            ->leftJoin("product", "product.id", "stitching.finish_product")
            ->leftJoin("customers", "customers.id", "stitching.customer")
            ->where("stitching_status","=","Y")
            ->orderBy("stitching.id", "desc")
            ->count();

        return view("admin.production.stitching_dashboard",compact("pending","complete"));
    }

    function production_washing_record(Request $request)
    {

        date_default_timezone_set("Asia/Kolkata");

        $batch_record = new washing_batch_record();
        $batch_record->washing_id = $request->washing_id;
        $batch_record->batch_no = $request->batch_no;
        $batch_record->process = $request->process;
        $batch_record->remarks = $request->remarks;
        $batch_record->time = date('h:i:s a');
        $batch_record->date = date('Y-m-d');
        $batch_record->operater_name = Session::get("user_id");
        $batch_record->total_washing = $request->total_washing;
        $batch_record->total_washing_wastage_nos = $request->total_washing_wastage_nos;
        $batch_record->total_washing_material_used = $request->total_washing_material_used;
        $batch_record->total_washing_wastage_material = $request->total_washing_wastage_material;
        $batch_record->save();


        $production = washing::find($request->washing_id);
        $production->status = $request->process;
        $production->complete_time = date('d-m-Y h:i:s a');
        $production->total_washing = $request->total_washing;
        $production->total_washing_wastage_nos = $request->total_washing_wastage_nos;
        $production->total_washing_material_used = $request->total_washing_material_used;
        $production->total_washing_wastage_material = $request->total_washing_wastage_material;
        $production->finished_user = Session::get("user_id");
        $production->remarks = $request->remarks;
        $production->washing_status="N";
        $production->save();




        if($request->process =="Move to Packaging")
        {
            $production = washing::find($request->washing_id);
            $production->washing_status="Y";
            $production->save();

            packaging_machine_allocate::truncate();
            $stitching_machine=packaging_machine::orderBy("id","asc")->get();

            foreach ($stitching_machine as $stitchMachine) {

                $stitch=packaging::where("machine",$stitchMachine->id)
                    ->where("packaging_status",'=','N')
                    ->sum("nos");
                $stitching_machine_allocate=new packaging_machine_allocate();
                $stitching_machine_allocate->machine=$stitchMachine->id;
                $stitching_machine_allocate->total_socks=$stitch ?? 0;
                $stitching_machine_allocate->save();
            }
            $minimumSocksMachine=packaging_machine_allocate::select("machine")->first()->min('total_socks');

            $machine=packaging_machine_allocate::where("total_socks",$minimumSocksMachine)->first();


            $stitch=new packaging();
            $stitch->washing_id=$production->id;
            $stitch->machine=$machine->machine;
            $stitch->batch_no=$production->batch_no;
            $stitch->batch=$production->batch;
            $stitch->customer=$production->customer;
            $stitch->finish_product=$production->finish_product;
            $stitch->nos=$request->total_washing;
            $stitch->size=$production->size;
            $stitch->total_material=$request->total_washing_material_used;
            $stitch->timestamp=date('d-m-Y h:i:s a');
            $stitch->user_id=Session::get("user_id");
            $stitch->status="Created";
            $stitch->packaging_status="N";
            $stitch->save();

            $url = url('/admin/production/washing/dashboard/');
            return redirect()->to($url)->with("message","This Batch Send to Packaging Department");
        }else{
            $url = route('admin.production.washing.move',['batch_no' => $request->batch_no]);
            return redirect()->to($url)->with("message","Stage Change Successfully");
        }
    }

    function production_packaging_record(Request $request)
    {

        date_default_timezone_set("Asia/Kolkata");

        $batch_record = new packaging_batch_record();
        $batch_record->packaging_id = $request->packaging_id;
        $batch_record->batch_no = $request->batch_no;
        $batch_record->process = $request->process;
        $batch_record->remarks = $request->remarks;
        $batch_record->time = date('h:i:s a');
        $batch_record->date = date('Y-m-d');
        $batch_record->operater_name = Session::get("user_id");
        $batch_record->total_packaging = $request->total_packaging;
        $batch_record->total_packaging_wastage_nos = $request->total_packaging_wastage_nos;
        $batch_record->total_packaging_material_used = $request->total_packaging_material_used;
        $batch_record->total_packaging_wastage_material = $request->total_packaging_wastage_material;
        $batch_record->save();


        $production = packaging::find($request->packaging_id);
        $production->status = $request->process;
        $production->complete_time = date('d-m-Y h:i:s a');
        $production->total_packaging = $request->total_packaging;
        $production->total_packaging_wastage_nos = $request->total_packaging_wastage_nos;
        $production->total_packaging_material_used = $request->total_packaging_material_used;
        $production->total_packaging_wastage_material = $request->total_packaging_wastage_material;
        $production->finished_user = Session::get("user_id");
        $production->remarks = $request->remarks;
        $production->packaging_status="N";
        $production->save();




        if($request->process =="Move to Stock")
        {
            $production = packaging::find($request->packaging_id);
            $production->packaging_status="Y";

            if($production->save())
            {
                $checkproduct=stock_status::where("product",$production->finish_product)
                    ->first();

                if(empty($checkproduct))
                {
                    $status=new stock_status();
                    $status->product=$production->finish_product;
                    $status->qty=$request->total_packaging;
                    $status->particular="Inward From Production Batch : ".$request->batch_no;
                    $status->inward_date=date('Y-m-d');
                    $status->user_id=Session::get("user_id");
                    $status->created_time=date('d-m-Y h:i:s a');
                    $status->save();
                }else{
                    $qty=$checkproduct->qty+$request->total_packaging;
                    $checkproduct->qty=$qty;
                    $checkproduct->inward_date=date('Y-m-d');
                    $checkproduct->particular="Inward From Production Batch : ".$request->batch_no;
                    $checkproduct->created_time=date('d-m-Y h:i:s a');
                    $checkproduct->save();
                }

                $book=new stock_book();
                $book->product=$production->finish_product;
                $book->inward_date=date('Y-m-d');
                $book->inward_qty=$request->total_packaging;
                $book->remaining_qty=$request->total_packaging;
                $book->particular="Inward From Production Batch : ".$request->batch_no;
                $book->created_time=date('d-m-Y h:i:s a');
                $book->user_id=Session::get("user_id");
                $book->save();
            }

            $url = route('admin.production.packaging.dashboard');
            return redirect()->to($url)->with("message","Stock Inward Successfully");
        }else{
            $url = route('admin.production.packaging.move',['batch_no' => $request->batch_no]);
            return redirect()->to($url)->with("message","Stage Change Successfully");
        }
    }
    function production_pressing_record(Request $request)
    {

        date_default_timezone_set("Asia/Kolkata");

        $batch_record = new pressing_batch_record();
        $batch_record->pressing_id = $request->pressing_id;
        $batch_record->batch_no = $request->batch_no;
        $batch_record->process = $request->process;
        $batch_record->remarks = $request->remarks;
        $batch_record->time = date('h:i:s a');
        $batch_record->date = date('Y-m-d');
        $batch_record->operater_name = Session::get("user_id");
        $batch_record->total_pressing = $request->total_pressing;
        $batch_record->total_pressing_wastage_nos = $request->total_pressing_wastage_nos;
        $batch_record->total_pressing_material_used = $request->total_pressing_material_used;
        $batch_record->total_pressing_wastage_material = $request->total_pressing_wastage_material;
        $batch_record->save();


        $production = pressing::find($request->pressing_id);
        $production->status = $request->process;
        $production->complete_time = date('d-m-Y h:i:s a');
        $production->total_pressing = $request->total_pressing;
        $production->total_pressing_wastage_nos = $request->total_pressing_wastage_nos;
        $production->total_pressing_material_used = $request->total_pressing_material_used;
        $production->total_pressing_wastage_material = $request->total_pressing_wastage_material;
        $production->finished_user = Session::get("user_id");
        $production->remarks = $request->remarks;
        $production->pressing_status="N";
        $production->save();




        if($request->process =="Move to Washing")
        {
            $production = pressing::find($request->pressing_id);
            $production->pressing_status="Y";
            $production->save();

            washing_machine_allocate::truncate();
            $stitching_machine=washing_machine::orderBy("id","asc")->get();

            foreach ($stitching_machine as $stitchMachine) {

                $stitch=washing::where("machine",$stitchMachine->id)
                    ->where("washing_status",'=','N')
                    ->sum("nos");
                $stitching_machine_allocate=new washing_machine_allocate();
                $stitching_machine_allocate->machine=$stitchMachine->id;
                $stitching_machine_allocate->total_socks=$stitch ?? 0;
                $stitching_machine_allocate->save();
            }
            $minimumSocksMachine=washing_machine_allocate::select("machine")->first()->min('total_socks');

            $machine=washing_machine_allocate::where("total_socks",$minimumSocksMachine)->first();


            $stitch=new washing();
            $stitch->pressing_id=$production->id;
            $stitch->machine=$machine->machine;
            $stitch->batch_no=$production->batch_no;
            $stitch->batch=$production->batch;
            $stitch->customer=$production->customer;
            $stitch->finish_product=$production->finish_product;
            $stitch->nos=$request->total_pressing;
            $stitch->size=$production->size;
            $stitch->total_material=$request->total_pressing_material_used;
            $stitch->timestamp=date('d-m-Y h:i:s a');
            $stitch->user_id=Session::get("user_id");
            $stitch->status="Created";
            $stitch->washing_status="N";
            $stitch->save();

            $url = route('admin.production.pressing.dashboard');
            return redirect()->to($url)->with("message","This Batch Send to Washing Department");
        }else{
            $url = route('admin.production.pressing.move',['batch_no' => $request->batch_no]);
            return redirect()->to($url);
        }
    }

    function production_stitching_record(Request $request)
    {

        date_default_timezone_set("Asia/Kolkata");

        $batch_record = new stitching_batch_record();
        $batch_record->stitching_id = $request->stitching_id;
        $batch_record->production_id = $request->production_id;
        $batch_record->batch_no = $request->batch_no;
        $batch_record->process = $request->process;
        $batch_record->remarks = $request->remarks;
        $batch_record->time = date('h:i:s a');
        $batch_record->date = date('Y-m-d');
        $batch_record->operater_name = Session::get("user_id");
        $batch_record->total_stitching = $request->total_stitching;
        $batch_record->total_stitching_wastage_nos = $request->total_stitching_wastage_nos;
        $batch_record->total_stitching_material_used = $request->total_stitching_material_used;
        $batch_record->total_stitching_wastage_material = $request->total_stitching_wastage_material;
        $batch_record->save();


        $production = stitching::find($request->stitching_id);
        $production->status = $request->process;
        $production->complete_time = date('d-m-Y h:i:s a');
        $production->total_stitching = $request->total_stitching;
        $production->total_stitching_wastage_nos = $request->total_stitching_wastage_nos;
        $production->total_stitching_material_used = $request->total_stitching_material_used;
        $production->total_stitching_wastage_material = $request->total_stitching_wastage_material;
        $production->finished_user = Session::get("user_id");
        $production->remarks = $request->remarks;
        $production->stitching_status="N";
        $production->save();




        if($request->process =="Move to Pressing")
        {
            $production = stitching::find($request->stitching_id);
            $production->stitching_status="Y";
            $production->save();

            pressing_machine_allocate::truncate();
            $stitching_machine=pressing_machine::orderBy("id","asc")->get();

            foreach ($stitching_machine as $stitchMachine) {

                $stitch=pressing::where("machine",$stitchMachine->id)
                    ->where("pressing_status",'=','N')
                    ->sum("nos");
                $stitching_machine_allocate=new pressing_machine_allocate();
                $stitching_machine_allocate->machine=$stitchMachine->id;
                $stitching_machine_allocate->total_socks=$stitch ?? 0;
                $stitching_machine_allocate->save();
            }
            $minimumSocksMachine=pressing_machine_allocate::select("machine")->first()->min('total_socks');

            $machine=pressing_machine_allocate::where("total_socks",$minimumSocksMachine)->first();


            $stitch=new pressing();
            $stitch->stitching_id=$production->id;
            $stitch->machine=$machine->machine;
            $stitch->batch_no=$production->batch_no;
            $stitch->batch=$production->batch;
            $stitch->customer=$production->customer;
            $stitch->finish_product=$production->finish_product;
            $stitch->nos=$request->total_stitching;
            $stitch->size=$production->size;
            $stitch->total_material=$request->total_stitching_material_used;
            $stitch->timestamp=date('d-m-Y h:i:s a');
            $stitch->user_id=Session::get("user_id");
            $stitch->status="Created";
            $stitch->pressing_status="N";
            $stitch->save();

            $url = route('admin.production.stitching.dashboard');
            return redirect()->to($url)->with("message","This Batch send to Pressing Department");
        }else{
            $url = route('admin.production.stitching.move',['batch_no' => $request->batch_no]);
            return redirect()->to($url);
        }
    }

    function move_to_washing(Request $request)
    {
        $production = washing::select("washing.*",'customers.customer_name', "washing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("washing_machine", "washing_machine.id", "washing.machine")
            ->leftJoin("product", "product.id", "washing.finish_product")
            ->leftJoin("customers", "customers.id", "washing.customer")
            ->orderBy("washing.id", "desc")
            ->where("washing.batch_no", $request->batch_no)
            ->first();

        $production_material = production_material::select("production_material.*", "product.product_name", "product.value1", "product.value2", "uom.uom_name")
            ->leftJoin("product", "product.id", "production_material.required_material")
            ->leftJoin("uom", "uom.id", "product.uom")
            ->where("production_material.batch_no", $production->batch_no)
            ->get();

        $formula_mst_item=formula_mst_item::where("size",$production->size)->get();

        $batch_record = washing_batch_record::select("washing_batch_record.*", "website_user.first_name", "website_user.last_name")
            ->where("washing_batch_record.washing_id", $production->id)
            ->leftJoin("website_user", "website_user.id", "washing_batch_record.operater_name")
            ->orderBy("washing_batch_record.id", "desc")
            ->get();

            $last_batch_record = washing_batch_record::select("washing_batch_record.*", "website_user.first_name", "website_user.last_name")
            ->where("washing_batch_record.washing_id", $production->id)
            ->leftJoin("website_user", "website_user.id", "washing_batch_record.operater_name")
            ->orderBy("washing_batch_record.id", "desc")
            ->first();

        //dd($batch_record);

        if(empty($last_batch_record))
            {
               $last_record="no";

            }else{
                $last_record="yes";
            }

        return view("admin.production.production_washing", compact("last_record","last_batch_record","formula_mst_item","production", "batch_record","production_material"));

    }

    function move_to_pressing(Request $request)
    {
        $production = pressing::select("pressing.*","stitching.total_stitching_material_used as actualMaterialUsed", 'customers.customer_name', "pressing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("pressing_machine", "pressing_machine.id", "pressing.machine")
            ->leftJoin("product", "product.id", "pressing.finish_product")
            ->leftJoin("customers", "customers.id", "pressing.customer")
            ->leftJoin("stitching", "stitching.batch_no", "pressing.batch_no")
            ->orderBy("pressing.id", "desc")
            ->where("pressing.batch_no", $request->batch_no)
            ->first();

        $production_material = production_material::select("production_material.*", "product.product_name", "product.value1", "product.value2", "uom.uom_name")
            ->leftJoin("product", "product.id", "production_material.required_material")
            ->leftJoin("uom", "uom.id", "product.uom")
            ->where("production_material.batch_no", $production->batch_no)
            ->get();

        $formula_mst_item=formula_mst_item::where("size",$production->size)->get();

        $batch_record = pressing_batch_record::select("pressing_batch_record.*", "website_user.first_name", "website_user.last_name")
            ->where("pressing_batch_record.pressing_id", $production->id)
            ->leftJoin("website_user", "website_user.id", "pressing_batch_record.operater_name")
            ->orderBy("pressing_batch_record.id", "desc")
            ->get();

            $last_batch_record = pressing_batch_record::select("pressing_batch_record.*", "website_user.first_name", "website_user.last_name")
            ->where("pressing_batch_record.pressing_id", $production->id)
            ->leftJoin("website_user", "website_user.id", "pressing_batch_record.operater_name")
            ->orderBy("pressing_batch_record.id", "desc")
            ->first();
        //dd($batch_record);

         if(empty($last_batch_record))
            {
               $last_record="no";

            }else{
                $last_record="yes";
            }

        return view("admin.production.production_pressing", compact("last_record","last_batch_record","formula_mst_item","production", "batch_record","production_material"));

    }

    function move_to_packaging(Request $request)
    {
        $production = packaging::select("packaging.*","production.total_material as actualMaterialUsed", 'customers.customer_name', "packaging_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("packaging_machine", "packaging_machine.id", "packaging.machine")
            ->leftJoin("product", "product.id", "packaging.finish_product")
            ->leftJoin("customers", "customers.id", "packaging.customer")
            ->leftJoin("production", "production.batch_no", "packaging.batch_no")
            ->orderBy("packaging.id", "desc")
            ->where("packaging.batch_no", $request->batch_no)
            ->first();

        $production_material = production_material::select("production_material.*", "product.product_name", "product.value1", "product.value2", "uom.uom_name")
            ->leftJoin("product", "product.id", "production_material.required_material")
            ->leftJoin("uom", "uom.id", "product.uom")
            ->where("production_material.batch_no", $production->batch_no)
            ->get();

        $formula_mst_item=formula_mst_item::where("size",$production->size)->get();

        $batch_record = packaging_batch_record::select("packaging_batch_record.*", "website_user.first_name", "website_user.last_name")
            ->where("packaging_batch_record.packaging_id", $production->id)
            ->leftJoin("website_user", "website_user.id", "packaging_batch_record.operater_name")
            ->orderBy("packaging_batch_record.id", "desc")
            ->get();

            $last_batch_record = packaging_batch_record::select("packaging_batch_record.*", "website_user.first_name", "website_user.last_name")
            ->where("packaging_batch_record.packaging_id", $production->id)
            ->leftJoin("website_user", "website_user.id", "packaging_batch_record.operater_name")
            ->orderBy("packaging_batch_record.id", "desc")
            ->first();
        //dd($batch_record);

         if(empty($last_batch_record))
            {
               $last_record="no";

            }else{
                $last_record="yes";
            }

        return view("admin.production.production_packaging", compact("last_record","last_batch_record","formula_mst_item","production", "production_material", "batch_record"));

    }

    function move_to_stitching(Request $request)
    {
        $production = stitching::select("stitching.*","production.total_material as actualMaterialUsed", 'customers.customer_name', "stitching_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("stitching_machine", "stitching_machine.id", "stitching.machine")
            ->leftJoin("product", "product.id", "stitching.finish_product")
            ->leftJoin("customers", "customers.id", "stitching.customer")
            ->leftJoin("production", "production.batch_no", "stitching.batch_no")
            ->orderBy("stitching.id", "desc")
            ->where("stitching.batch_no", $request->batch_no)
            ->first();

        $production_material = production_material::select("production_material.*", "product.product_name", "product.value1", "product.value2", "uom.uom_name")
            ->leftJoin("product", "product.id", "production_material.required_material")
            ->leftJoin("uom", "uom.id", "product.uom")
            ->where("production_material.production_id", $production->id)
            ->get();

        $formula_mst_item=formula_mst_item::where("size",$production->size)->get();

        $batch_record = stitching_batch_record::select("stitching_batch_record.*", "website_user.first_name", "website_user.last_name")
            ->where("stitching_batch_record.stitching_id", $production->id)
            ->leftJoin("website_user", "website_user.id", "stitching_batch_record.operater_name")
            ->orderBy("stitching_batch_record.id", "desc")
            ->get();

            $last_batch_record = stitching_batch_record::select("stitching_batch_record.*", "website_user.first_name", "website_user.last_name")
            ->where("stitching_batch_record.stitching_id", $production->id)
            ->leftJoin("website_user", "website_user.id", "stitching_batch_record.operater_name")
            ->orderBy("stitching_batch_record.id", "desc")
            ->first();
            if(empty($last_batch_record))
            {
               $last_record="no";
                $last_batch_record=array("process"=>"process");
            }else{
                $last_record="yes";
            }
           // dd($last_batch_record);

        //dd($batch_record);
        return view("admin.production.production_stitching", compact("last_record","last_batch_record","formula_mst_item","production", "production_material", "batch_record"));

    }

    public function stitching_delete(Request $request)
    {
        stitching::where('id', $request->id)->delete();

        return redirect()->route("stitching/dashboard")->with("message","Stitching record deleted");
    }

    function getproduct_image(Request $request)
    {
        $product = product::findorFail($request->product);
        $product_image = $product->product_image ?? "";
        $size=$product->value2 ?? "";
        $colour=$product->value1 ?? "";

        // Raw material category is chosen here, at formula-creation time,
        // instead of being fixed on the product record.
        $categories=[
            "Cotton"=>"Cotton",
            "Spendex"=>"Spendex",
            "Elastics"=>"Elastics",
            "Nylon"=>"Nylon",
            "Polyester"=>"Polyester",
            "P_P_Yarn"=>"P.P Yarn",
        ];

        $str = "<table class='table table-bordered'>";
        $str .= "<tr><th>Category</th><th>Raw Material</th><th>Qty (grams)</th></tr>";
        foreach ($categories as $group=>$label) {
            $materials = DB::table('product')
                ->leftJoin('uom', 'uom.id', 'product.uom')
                ->select('product.id', 'product.product_name', 'product.value1', 'product.value2', 'uom.uom_name')
                ->where('product.raw_material_group', $group)
                ->orderBy('product.product_name', 'asc')
                ->get();

            $str .= "<tr>";
            $str .= "<td>" . $label . "</td>";
            $str .= "<td><select class='form-control' name='material[]' onchange='updateUomHint(this)'>";
            $str .= "<option value=''>Select " . $label . "</option>";
            foreach ($materials as $material) {
                $uom = strtoupper($material->uom_name ?? '');
                $str .= "<option value='" . $material->id . "' data-uom='" . $uom . "'>" . \App\product::nameWithVariantInline($material->product_name, $material->value1 ?? null, $material->value2 ?? null) . "</option>";
            }
            $str .= "</select></td>";
            $str .= "<td><input style='width:100px' oninput='cal(this)' class='form-control required_qty_per' type='text' name='qty[]'>";
            $str .= "<small class='uom-hint text-muted'></small></td>";
            $str .= "</tr>";
        }
        $str .= "</table>";

        return response()->json(["colour"=>$colour,'product_image' => $product_image,"size"=>$size,"str"=>$str]);
    }

    function production_record(Request $request)
    {
       // dd($request->all());
        date_default_timezone_set("Asia/Kolkata");

        $batch_record = new batch_record();
        $batch_record->production_id = $request->production_id;
        $batch_record->batch_no = $request->batch_no;
        $batch_record->process = $request->process;
        $batch_record->remarks = $request->remarks;
        $batch_record->time = date('h:i:s a');
        $batch_record->date = date('Y-m-d');
        $batch_record->operater_name = Session::get("user_id");
        $batch_record->total_production = $request->total_production;
        $batch_record->total_wastage_nos = $request->total_wastage_nos;
        $batch_record->total_material_used = $request->total_material_used;
        $batch_record->total_wastage_used = $request->total_wastage_used;
        $batch_record->save();


        $production = production::find($request->production_id);
        $production->status = $request->process;
        $production->complete_time = date('d-m-Y h:i:s a');
        $production->total_production = $request->total_production;
        $production->total_wastage_nos = $request->total_wastage_nos;
        $production->total_material_used = $request->total_material_used;
        $production->total_wastage_used = $request->total_wastage_used;
        $production->finished_user = Session::get("user_id");
        $production->remarks = $request->remarks;
        $production->production_status="N";
        $production->save();




        if($request->process =="Move to Stitching")
        {
            $production = production::find($request->production_id);
            $production->production_status="Y";
            $production->save();

            if($production->save())
            {
                stitching_machine_allocate::truncate();
                $stitching_machine=stitching_machine::orderBy("id","asc")->get();

                $temp=array();
                $m=array();
                foreach ($stitching_machine as $stitchMachine) {
                    echo $stitchMachine->id."<br>";

                    $stitch=stitching::where("machine",$stitchMachine->id)
                        ->Where("stitching_status","=","N")
                        ->sum("nos");
                    $stitching_machine_allocate=new stitching_machine_allocate();
                    $stitching_machine_allocate->machine=$stitchMachine->id;
                    $stitching_machine_allocate->total_socks=$stitch ?? 0;
                    $stitching_machine_allocate->save();
                }

                $minimumSocksMachine=stitching_machine_allocate::select("machine")->first()->min('total_socks');

                $machine=stitching_machine_allocate::where("total_socks",$minimumSocksMachine)->first();


                $stitch=new stitching();
                $stitch->production_id=$production->id;
                $stitch->machine=$machine->machine;
                $stitch->batch_no=$production->batch_no;
                $stitch->batch=$production->batch;
                $stitch->customer=$production->customer;
                $stitch->finish_product=$production->finish_product;
                $stitch->nos=$production->total_production;
                $stitch->size=$production->size;
                $stitch->total_material=$production->total_material_used;
                $stitch->timestamp=date('d-m-Y h:i:s a');
                $stitch->user_id=Session::get("user_id");
                $stitch->status="Created";
                $stitch->stitching_status="N";
                $stitch->save();
            }
            return redirect()->route("admin.production.dashboard")->with("message","This Batch Production Complete");
        }else{
            $url = route('admin.production.details',['batch_no' => $request->batch_no]);
            return redirect()->to($url);
        }


    }

    function pressing_all(Request $request)
    {
        $machine = pressing_machine::orderBy("machine_name", "asc")->get();
        $production=new pressing();
        $production=$production->select("pressing.*", 'customers.customer_name', "pressing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $production=$production->leftJoin("pressing_machine", "pressing_machine.id", "pressing.machine");
        $production=$production->leftJoin("product", "product.id", "pressing.finish_product");
        $production=$production->leftJoin("customers", "customers.id", "pressing.customer");

        if(isset($request->machine))
        {
            $production=$production->where("pressing_machine.machine_name","like",'%'.$request->machine.'%');
        }
        if(isset($request->batch_no))
        {
            $production=$production->where("pressing.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $production=$production->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $production=$production->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $production=$production->where("pressing.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->total_pressing))
        {
            $production=$production->where("pressing.total_pressing","like",'%'.$request->total_pressing.'%');
        }
        if(isset($request->size))
        {
            $production=$production->where("pressing.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $production=$production->where("pressing.pressing_status","like",'%'.$request->status.'%');
        }
        $production=$production->orderBy("pressing.id", "desc");
        $production=$production->paginate(session('records_per_page', 30));

        return view("admin.production.pressingAllMachineWiseBatch", compact("production", "machine"));

    }
    function washing_all(Request $request)
    {
        $machine = washing_machine::orderBy("machine_name", "asc")->get();
        $production=new washing();
        $production=$production->select("washing.*", 'customers.customer_name', "washing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $production=$production->leftJoin("washing_machine", "washing_machine.id", "washing.machine");
        $production=$production->leftJoin("product", "product.id", "washing.finish_product");
        $production=$production->leftJoin("customers", "customers.id", "washing.customer");

        if(isset($request->machine))
        {
            $production=$production->where("washing_machine.machine_name","like",'%'.$request->machine.'%');
        }
        if(isset($request->batch_no))
        {
            $production=$production->where("washing.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $production=$production->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $production=$production->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $production=$production->where("washing.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->total_washing))
        {
            $production=$production->where("washing.total_washing","like",'%'.$request->total_washing.'%');
        }
        if(isset($request->size))
        {
            $production=$production->where("washing.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $production=$production->where("washing.washing_status","like",'%'.$request->status.'%');
        }
        $production=$production->orderBy("washing.id", "desc");
        $production=$production->paginate(session('records_per_page', 30));

        return view("admin.production.washingAllMachineWiseBatch", compact("production", "machine"));

    }

    function packaging_all(Request $request)
    {
        $machine = packaging_machine::orderBy("machine_name", "asc")->get();
        $production=new packaging();
        $production=$production->select("packaging.*", 'customers.customer_name', "packaging_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $production=$production->leftJoin("packaging_machine", "packaging_machine.id", "packaging.machine");
        $production=$production->leftJoin("product", "product.id", "packaging.finish_product");
        $production=$production->leftJoin("customers", "customers.id", "packaging.customer");

        if(isset($request->machine))
        {
            $production=$production->where("packaging_machine.machine_name","like",'%'.$request->machine.'%');
        }
        if(isset($request->batch_no))
        {
            $production=$production->where("packaging.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $production=$production->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $production=$production->where("packaging.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $production=$production->where("packaging.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->total_packaging))
        {
            $production=$production->where("packaging.total_packaging","like",'%'.$request->total_packaging.'%');
        }
        if(isset($request->size))
        {
            $production=$production->where("packaging.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $production=$production->where("packaging.packaging_status","like",'%'.$request->status.'%');
        }
        $production=$production->orderBy("packaging.id", "desc");
        $production=$production->paginate(session('records_per_page', 30));

        return view("admin.production.packagingAllMachineWiseBatch", compact("production", "machine"));

    }
    function stitching_all(Request $request)
    {
        $machine = stitching_machine::orderBy("machine_name", "asc")->get();
        $production=new stitching();
        $production=$production->select("stitching.*", 'customers.customer_name', "stitching_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $production=$production->leftJoin("stitching_machine", "stitching_machine.id", "stitching.machine");
        $production=$production->leftJoin("product", "product.id", "stitching.finish_product");
        $production=$production->leftJoin("customers", "customers.id", "stitching.customer");

        if(isset($request->machine))
        {
            $production=$production->where("machine.machine_name","like",'%'.$request->machine.'%');
        }
        if(isset($request->batch_no))
        {
            $production=$production->where("stitching.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $production=$production->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $production=$production->where("stitching.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $production=$production->where("stitching.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->stitching_nos))
        {
            $production=$production->where("stitching.total_stitching","like",'%'.$request->stitching_nos.'%');
        }
        if(isset($request->size))
        {
            $production=$production->where("stitching.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {

            $production=$production->where("stitching.stitching_status","like",'%'.$request->status.'%');
        }
        $production=$production->orderBy("stitching.id", "desc");
        $production=$production->paginate(session('records_per_page', 30));

        return view("admin.production.stitchingAllMachineWiseBatch", compact("production", "machine"));

    }

    function stitching_all_batch(Request $request)
    {
        $machine = stitching_machine::find($request->id);

        $list =new stitching();
        $list=$list->select("stitching.*", 'customers.customer_name', "stitching_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $list=$list->leftJoin("stitching_machine", "stitching_machine.id", "stitching.machine");
        $list=$list->leftJoin("product", "product.id", "stitching.finish_product");
        $list=$list->leftJoin("customers", "customers.id", "stitching.customer");

        if(isset($request->batch_no))
        {
            $list=$list->where("stitching.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $list=$list->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $list=$list->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $list=$list->where("stitching.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->size))
        {
            $list=$list->where("stitching.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $list=$list->where("stitching.stitching_status","like",'%'.$request->status.'%');
        }
        $list=$list->where("stitching.machine",$request->id);
        $list=$list->orderBy("washing.id", "desc");
        $list=$list->paginate(session('records_per_page', 30));

        return view("admin.production.stitchingAllMachineWiseBatch", compact("list", "machine"));

    }

    function washing_complete_batch(Request $request)
    {
        $machine = washing_machine::find($request->id);

        $list =new washing();
        $list=$list->select("washing.*", 'customers.customer_name', "washing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $list=$list->leftJoin("washing_machine", "washing_machine.id", "washing.machine");
        $list=$list->leftJoin("product", "product.id", "washing.finish_product");
        $list=$list->leftJoin("customers", "customers.id", "washing.customer");

        if(isset($request->batch_no))
        {
            $list=$list->where("washing.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $list=$list->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $list=$list->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $list=$list->where("washing.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->total_washing))
        {
            $list=$list->where("washing.total_washing","like",'%'.$request->total_washing.'%');
        }
        if(isset($request->size))
        {
            $list=$list->where("washing.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $list=$list->where("washing.pressing_status","like",'%'.$request->status.'%');
        }
        $list=$list->where("washing.machine",$request->id);
        $list=$list->where("washing.washing_status","Y");
        $list=$list->orderBy("washing.id", "desc");
        $list=$list->paginate(session('records_per_page', 30));

        return view("admin.production.washingCompleteMachineWiseBatch", compact("list", "machine"));

    }

    function pressing_complete_batch(Request $request)
    {
        $machine = pressing_machine::find($request->id);

        $list =new pressing();
        $list=$list->select("pressing.*", 'customers.customer_name', "pressing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $list=$list->leftJoin("pressing_machine", "pressing_machine.id", "pressing.machine");
        $list=$list->leftJoin("product", "product.id", "pressing.finish_product");
        $list=$list->leftJoin("customers", "customers.id", "pressing.customer");

        if(isset($request->batch_no))
        {
            $list=$list->where("pressing.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $list=$list->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $list=$list->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $list=$list->where("pressing.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->total_pressing))
        {
            $list=$list->where("pressing.total_pressing","like",'%'.$request->total_pressing.'%');
        }
        if(isset($request->size))
        {
            $list=$list->where("pressing.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $list=$list->where("pressing.pressing_status","like",'%'.$request->status.'%');
        }
        $list=$list->where("pressing.machine",$request->id);
        $list=$list->where("pressing.pressing_status","Y");
        $list=$list->orderBy("pressing.id", "desc");
        $list=$list->paginate(session('records_per_page', 30));

        return view("admin.production.pressingCompleteMachineWiseBatch", compact("list", "machine"));

    }

    function packaging_complete_batch(Request $request)
    {
        $machine = packaging_machine::find($request->id);

        $list =new packaging();
        $list=$list->select("packaging.*", 'customers.customer_name', "packaging_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $list=$list->leftJoin("packaging_machine", "packaging_machine.id", "packaging.machine");
        $list=$list->leftJoin("product", "product.id", "packaging.finish_product");
        $list=$list->leftJoin("customers", "customers.id", "packaging.customer");

        if(isset($request->batch_no))
        {
            $list=$list->where("packaging.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $list=$list->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $list=$list->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $list=$list->where("packaging.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->total_packaging))
        {
            $list=$list->where("packaging.total_packaging","like",'%'.$request->total_packaging.'%');
        }
        if(isset($request->size))
        {
            $list=$list->where("packaging.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $list=$list->where("packaging.packaging_status","like",'%'.$request->status.'%');
        }
        $list=$list->where("packaging.machine",$request->id);
        $list=$list->where("packaging.packaging_status","Y");
        $list=$list->orderBy("packaging.id", "desc");
        $list=$list->paginate(session('records_per_page', 30));

        return view("admin.production.packagingCompleteMachineWiseBatch", compact("list", "machine"));

    }

    function stitching_complete_batch(Request $request)
    {
        $machine = stitching_machine::find($request->id);

        $list =new stitching();
        $list=$list->select("stitching.*", 'customers.customer_name', "stitching_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $list=$list->leftJoin("stitching_machine", "stitching_machine.id", "stitching.machine");
        $list=$list->leftJoin("product", "product.id", "stitching.finish_product");
        $list=$list->leftJoin("customers", "customers.id", "stitching.customer");

        if(isset($request->batch_no))
        {
            $list=$list->where("stitching.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $list=$list->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $list=$list->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $list=$list->where("stitching.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->stitching_nos))
        {
            $list=$list->where("stitching.total_stitching","like",'%'.$request->stitching_nos.'%');
        }
        if(isset($request->size))
        {
            $list=$list->where("stitching.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $list=$list->where("stitching.stitching_status","like",'%'.$request->status.'%');
        }
        $list=$list->where("stitching.machine",$request->id);
        $list=$list->where("stitching.stitching_status","Y");
        $list=$list->orderBy("stitching.id", "desc");
        $list=$list->paginate(session('records_per_page', 30));

        return view("admin.production.stitchingCompleteMachineWiseBatch", compact("list", "machine"));

    }

    function packaging_pending_batch(Request $request)
    {

        $machine = packaging_machine::find($request->id);

        $list =new packaging();
        $list=$list->select("packaging.*", 'customers.customer_name', "packaging_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $list=$list->leftJoin("packaging_machine", "packaging_machine.id", "packaging.machine");
        $list=$list->leftJoin("product", "product.id", "packaging.finish_product");
        $list=$list->leftJoin("customers", "customers.id", "packaging.customer");

        if(isset($request->batch_no))
        {
            $list=$list->where("packaging.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $list=$list->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $list=$list->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $list=$list->where("packaging.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->size))
        {
            $list=$list->where("packaging.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $list=$list->where("packaging.packaging_status","like",'%'.$request->status.'%');
        }
        $list=$list->where("packaging.machine",$request->id);
        $list=$list->where("packaging.packaging_status","N");
        $list=$list->orderBy("packaging.id", "desc");
        $list=$list->paginate(session('records_per_page', 30));

        return view("admin.production.packagingPendingMachineWiseBatch", compact("list", "machine"));

    }

    function washing_pending_batch(Request $request)
    {

        $machine = washing_machine::find($request->id);

        $list =new washing();
        $list=$list->select("washing.*", 'customers.customer_name', "washing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $list=$list->leftJoin("washing_machine", "washing_machine.id", "washing.machine");
        $list=$list->leftJoin("product", "product.id", "washing.finish_product");
        $list=$list->leftJoin("customers", "customers.id", "washing.customer");

        if(isset($request->batch_no))
        {
            $list=$list->where("washing.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $list=$list->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $list=$list->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $list=$list->where("washing.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->size))
        {
            $list=$list->where("washing.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $list=$list->where("washing.washing_status","like",'%'.$request->status.'%');
        }
        $list=$list->where("washing.machine",$request->id);
        $list=$list->where("washing.washing_status","N");
        $list=$list->orderBy("washing.id", "desc");
        $list=$list->paginate(session('records_per_page', 30));

        return view("admin.production.washingPendingMachineWiseBatch", compact("list", "machine"));

    }

    function pressing_pending_batch(Request $request)
    {

        $machine = pressing_machine::find($request->id);

        $list =new pressing();
        $list=$list->select("pressing.*", 'customers.customer_name', "pressing_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $list=$list->leftJoin("pressing_machine", "pressing_machine.id", "pressing.machine");
        $list=$list->leftJoin("product", "product.id", "pressing.finish_product");
        $list=$list->leftJoin("customers", "customers.id", "pressing.customer");

        if(isset($request->batch_no))
        {
            $list=$list->where("pressing.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $list=$list->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $list=$list->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $list=$list->where("pressing.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->size))
        {
            $list=$list->where("pressing.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $list=$list->where("pressing.pressing_status","like",'%'.$request->status.'%');
        }
        $list=$list->where("pressing.machine",$request->id);
        $list=$list->where("pressing.pressing_status","N");
        $list=$list->orderBy("pressing.id", "desc");
        $list=$list->paginate(session('records_per_page', 30));

        return view("admin.production.pressingPendingMachineWiseBatch", compact("list", "machine"));

    }

    function stitching_pending_batch(Request $request)
    {

        $machine = stitching_machine::find($request->id);

        $list =new stitching();
        $list=$list->select("stitching.*", 'customers.customer_name', "stitching_machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $list=$list->leftJoin("stitching_machine", "stitching_machine.id", "stitching.machine");
        $list=$list->leftJoin("product", "product.id", "stitching.finish_product");
        $list=$list->leftJoin("customers", "customers.id", "stitching.customer");

        if(isset($request->batch_no))
        {
            $list=$list->where("stitching.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $list=$list->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $list=$list->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $list=$list->where("stitching.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->size))
        {
            $list=$list->where("stitching.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $list=$list->where("stitching.stitching_status","like",'%'.$request->status.'%');
        }
        $list=$list->where("stitching.machine",$request->id);
        $list=$list->where("stitching.stitching_status","N");
        $list=$list->orderBy("stitching.id", "desc");
        $list=$list->paginate(session('records_per_page', 30));

        return view("admin.production.stitchingPendingMachineWiseBatch", compact("list", "machine"));

    }

    function production_batch(Request $request)
    {
        $machine = machine::find($request->id);

        $list =new production();
        $list=$list->select("production.*", 'customers.customer_name', "machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $list=$list->leftJoin("machine", "machine.id", "production.machine");
        $list=$list->leftJoin("product", "product.id", "production.finish_product");
        $list=$list->leftJoin("customers", "customers.id", "production.customer");

        if(isset($request->batch_no))
        {
            $list=$list->where("production.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $list=$list->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $list=$list->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $list=$list->where("production.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->size))
        {
            $list=$list->where("production.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $list=$list->where("production.production_status","like",'%'.$request->status.'%');
        }
        $list=$list->where("production.machine",$request->id);
        $list=$list->where("production.production_status","N");
        $list=$list->orderBy("production.id", "desc");
        $list=$list->paginate(session('records_per_page', 30));

        return view("admin.production.machineWiseBatch", compact("list", "machine"));

    }

    public function production_batch_delete(Request $request)
    {
        production::where('id', $request->id)->delete();

        return redirect()->route("admin.production.dashboard")->with("message", "Batch is deleted");
    }

    function all_production(Request $request)
    {

        $machine = machine::orderBy("machine_name", "asc")->get();
        $production=new production();
        $production=$production->select("production.*", 'customers.customer_name', "machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image");
        $production=$production->leftJoin("machine", "machine.id", "production.machine");
        $production=$production->leftJoin("product", "product.id", "production.finish_product");
        $production=$production->leftJoin("customers", "customers.id", "production.customer");

        if(isset($request->machine))
        {
            $production=$production->where("machine.machine_name","like",'%'.$request->machine.'%');
        }
        if(isset($request->batch_no))
        {
            $production=$production->where("production.batch_no","like",'%'.$request->batch_no.'%');
        }
        if(isset($request->client_name))
        {
            $production=$production->where("customers.customer_name","like",'%'.$request->client_name.'%');
        }
        if(isset($request->product))
        {
            $production=$production->where("product.product_name","like",'%'.$request->product.'%');
        }
        if(isset($request->nos))
        {
            $production=$production->where("production.nos","like",'%'.$request->nos.'%');
        }
        if(isset($request->size))
        {
            $production=$production->where("production.size","like",'%'.$request->size.'%');
        }
        if(isset($request->status))
        {
            $production=$production->where("production.production_status","like",'%'.$request->status.'%');
        }
        $production=$production->orderBy("production.id", "desc");
        $production=$production->paginate(session('records_per_page', 30));
        return view("admin.production.allProduction", compact("production", "machine"));
    }

    function production_details(Request $request)
    {
        $production = production::select("production.*", 'customers.customer_name', "machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("machine", "machine.id", "production.machine")
            ->leftJoin("product", "product.id", "production.finish_product")
            ->leftJoin("customers", "customers.id", "production.customer")
            ->orderBy("production.id", "desc")
            ->where("production.batch_no", $request->batch_no)
            ->first();
        //dd($production);
        $production_material = production_material::select("production_material.*", "product.product_name", "product.value1", "product.value2", "uom.uom_name")
            ->leftJoin("product", "product.id", "production_material.required_material")
            ->leftJoin("uom", "uom.id", "product.uom")
            ->where("production_material.production_id", $production->id)
            ->get();

        $batch_record = batch_record::select("batch_record.*", "website_user.first_name", "website_user.last_name")
            ->where("batch_record.production_id", $production->id)
            ->leftJoin("website_user", "website_user.id", "batch_record.operater_name")
            ->orderBy("batch_record.id", "desc")
            ->get();

            $last_batch_record = batch_record::select("batch_record.*", "website_user.first_name", "website_user.last_name")
            ->where("batch_record.production_id", $production->id)
            ->leftJoin("website_user", "website_user.id", "batch_record.operater_name")
            ->orderBy("batch_record.id", "desc")
            ->first();

             if(empty($last_batch_record))
            {
               $last_record="no";
                $last_batch_record=array("process"=>"process");
            }else{
                $last_record="yes";
            }


        return view("admin.production.production_details", compact("last_record","production", "production_material", "batch_record","last_batch_record"));
    }

    function allocate_machine(Request $request)
    {
        $machine = machine::orderBy("machine_name", "asc")->get();

        $production = production::select("production.*", 'customers.customer_name', "machine.machine_name", "product.product_name", "product.value1", "product.value2", "product.product_image")
            ->leftJoin("machine", "machine.id", "production.machine")
            ->leftJoin("product", "product.id", "production.finish_product")
            ->leftJoin("customers", "customers.id", "production.customer")
            ->orderBy("production.id", "desc")
            ->where("production_status","N")
            ->get();
        return view("admin.production.allocate_machine", compact("production", "machine"));
    }

    function machine_allocate(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");

    //dd($request->all());
        if($request->purchase_required_mat)
        {
            $purchaseRequiredMaterial=count($request->purchase_required_mat);
        }else{
            $purchaseRequiredMaterial=0;
        }

        //dd($purchaseRequiredMaterial);
        if ($purchaseRequiredMaterial > 0) {
            $order_no = purchase_requirement::max("order_no");
            if (empty($order_no)) {
                $order = 1;
            } else {
                $order = $order_no + 1;
            }

            $purchase_requirement = new purchase_requirement();
            $purchase_requirement->date = date('Y-m-d');
            $purchase_requirement->timestamp = date('d-m-Y h:i:s a');
            $purchase_requirement->order_no = $order;
            // Explicit rather than leaning on the column default, so the purchase
            // list can always tell a socks requirement from a belt one.
            $purchase_requirement->module = 'socks';
            $purchase_requirement->user_id = Session::get("user_id");
            $purchase_requirement->finish_product = $request->finish_product;
            $purchase_requirement->customer = $request->customer;
            if ($purchase_requirement->save()) {
                $totproduct = count($request->purchase_required_mat);
                for ($i = 0; $i < $totproduct; $i++) {
                        $requirement = new purchase_required_material();
                        $requirement->order_id = $purchase_requirement->id;
                        $requirement->raw_material = $request->purchase_required_mat[$i];
                        // Rounded up to the 0.01 the purchase and inward screens are
                        // worked in: a shortfall of 462.2 g is 0.4622 KG, and an order
                        // raised - or received - for 0.46 KG leaves production still
                        // short, which looks like the request never worked.
                        $shortInStockUnit = $request->purchase_required_stock_qty[$i]
                            / $this->materialConversionFactor($request->purchase_required_mat[$i]);
                        $requirement->qty = max(ceil(round($shortInStockUnit * 100, 6)) / 100, 0.01);
                        $requirement->timestamp = date('d-m-Y h:i:s a');
                        $requirement->user_id = Session::get("user_id");
                        $requirement->save();
                }
            }
            return redirect()->route("admin.production.process")->with("message", "request send to purchase department");
        }

        if ($purchaseRequiredMaterial == 0) {
            $batch = production::max("batch");
            if (empty($batch)) {
                $year = date("y");
                $nextyear = $year + 1;

                $n2 = str_pad($batch + 1, 4, 0, STR_PAD_LEFT);
                $batch = 0;
                $n2 .= '_';
                $n2 .= $year;
                $n2 .= '-';
                $n2 .= $nextyear;
            } else {
                $year = date("y");
                $nextyear = $year + 1;
                $n2 = str_pad($batch + 1, 4, 0, STR_PAD_LEFT);
                $n2 .= '_';
                $n2 .= $year;
                $n2 .= '-';
                $n2 .= $nextyear;
            }
            $production = new production();
            $production->machine = $request->machine_id;
            $production->batch_no = $n2;
            $production->batch = $batch + 1;
            $production->nos = $request->nos;
            $production->size = $request->size;
            $production->total_material = $request->total_material;
            $production->timestamp = date('d-m-Y h:i:s a');
            $production->user_id = Session::get("user_id");
            $production->finish_product = $request->finish_product;
            $production->customer = $request->customer;
            $production->production_status ="N";
            if ($production->save()) {
                $tot = count($request->required_mat);
                for ($i = 0; $i < $tot; $i++) {
                    $pmaterial = new production_material();
                    $pmaterial->machine = $request->machine_id;
                    $pmaterial->production_id = $production->id;
                    $pmaterial->batch_no = $n2;
                    $pmaterial->required_material = $request->required_mat[$i];
                    $pmaterial->required_qty = $request->required_qty[$i];
                    $pmaterial->avalible_stock = $request->stock_qty[$i];
                    $pmaterial->need_to_order_stock = $request->required_stock_qty[$i];
                    $pmaterial->timestamp = date('d-m-Y h:i:s a');
                    $pmaterial->user_id = Session::get("user_id");
                    $pmaterial->finish_product = $request->finish_product;
                    $pmaterial->customer = $request->customer;
                    $pmaterial->save();

                    $stock_status = stock_status::where("product", $request->required_mat[$i])->first();
                    $stockqty = $stock_status->qty ?? 0;
                    $materialqty = $request->required_qty[$i] / $this->materialConversionFactor($request->required_mat[$i]);
                    $actualqty = $stockqty - $materialqty;
                    $stock_status->qty = $actualqty;
                    $stock_status->save();

                    $book = new stock_book();

                    $book->product = $request->required_mat[$i];
                    $book->inward_date = date('Y-m-d');
                    $book->outward_qty = $materialqty;
                    $book->remaining_qty = $actualqty;
                    $book->particular = "This Material use in Production";
                    $book->created_time = date('d-m-Y h:i:s a');
                    $book->user_id = Session::get("user_id");
                    $book->save();
                }

            }

            return redirect()->route("admin.production.dashboard")->with("message", "Batch is Created");
        }

    }

    function getproduct_stock(Request $request)
    {
        $stock=stock_status::where("product",$request->material)
        ->first();
        return response()->json(['stockqty'=>$stock->qty ?? 0]);
    }

    /**
     * Formula quantities for KG-tracked raw material (thread) are entered in
     * grams, so stock (kept in KG) needs a x1000 conversion to compare on the
     * same footing. Piece/length-tracked material (buckle, slider, strap)
     * has no such gram convention - formula qty and stock are already in the
     * same unit - so no conversion applies there.
     */
    private function materialConversionFactor($materialId)
    {
        $uomName = DB::table('product')
            ->join('uom', 'uom.id', 'product.uom')
            ->where('product.id', $materialId)
            ->value('uom.uom_name');

        return strtoupper($uomName) === 'KG' ? 1000 : 1;
    }

    function getproduction_mat(Request $request)
    {
        $formula_mast = formula_mst::where("product", $request->finish_product)
            ->first();

        // Belt/buckle products have their own formula in Buckle Formula
        // Master (buckle_formula_mst) rather than the sock Formula Master, so
        // fall back to that when the product has no sock formula.
        $isBuckleFormula = false;
        if (empty($formula_mast)) {
            $formula_mast = \App\BuckleFormulaMst::where("product", $request->finish_product)->first();
            $isBuckleFormula = true;
        }

        // dd($row);
        $rawmaterial = product::orderBy("product_name", "asc")
            ->where("status", "raw material")
            ->get();

        if (empty($formula_mast)) {
            $required_qty = 0;
            $row = "Formula not found";
        } else {
            $required_qty = ($formula_mast->required_qty ?? 1) * $request->nos;

            if ($isBuckleFormula) {
                $item = \App\BuckleFormulaMstItem::select("buckle_formula_mst_item.*", "product.product_name", "product.value1", "product.value2", "stock_status.qty as stockqty", "uom.uom_name")
                    ->leftJoin("product", "product.id", "buckle_formula_mst_item.material")
                    ->leftJoin("stock_status", "stock_status.product", "buckle_formula_mst_item.material")
                    ->leftJoin("uom", "uom.id", "product.uom")
                    ->where("buckle_formula_mst_item.formula_id", $formula_mast->id)
                    ->get();
            } else {
                $item = formula_mst_item::select("formula_mst_item.*", "product.product_name", "product.value1", "product.value2", "stock_status.qty as stockqty","uom.uom_name")
                    ->leftJoin("product", "product.id", "formula_mst_item.material")
                    ->leftJoin("stock_status", "stock_status.product", "formula_mst_item.material")
                    ->leftJoin("uom", "uom.id", "product.uom")
                    ->where("formula_mst_item.formula_id", $formula_mast->id)
                    ->get();
            }

            $row = '<table class="table"><tr><th>Required Material</th><th colspan="2">Required Qty</th><th colspan="2">Avalible Stock</th><th colspan="2">Need to Order Stock</th></tr>';

            $stockstatus = "balance";
            foreach ($item as $item) {

                $percentage = $item->percentage;
                $reqqty = $request->nos * $required_qty;
                $ectual =$request->nos * $item->qty;
                $roundqty = round($ectual, 2);
                $factor = strtoupper($item->uom_name) === 'KG' ? 1000 : 1;
                $unitLabel = $factor == 1000 ? 'Gram' : $item->uom_name;
                $stockqty=($item->stockqty ?? 0)*$factor;
//                $row .="<tr><td>per $percentage</td><td>required $required_qty</td>";

                if ($roundqty > $stockqty) {
                    $stockstatus = "low";
                    $purchaseqty = $roundqty - $stockqty;
                    $row .= "<tr style='border: 1px solid red'>
                        <td style='text-align: center'>
                        <select onchange='getrawmaterial(this)' name='purchase_required_mat[]' class='required_mat form-control'>
                        <option value='$item->material'>" . \App\product::nameWithVariantInline($item->product_name, $item->value1 ?? null, $item->value2 ?? null) . "</option>
                        ";
                    foreach ($rawmaterial as $mat) {
                        $row .="<option value='$mat->id'>" . \App\product::nameWithVariantInline($mat->product_name, $mat->value1 ?? null, $mat->value2 ?? null) . "</option>";
                        }
                    $row .= "</select>
                        </td>
                        <td style='text-align: center'><input type='text' class='required_qty form-control' name='purchase_required_qty[]' value='$roundqty'></td>
                        <td style='text-align: center'>$unitLabel</td>
                        <td style='text-align: center'><input type='text' class='stock_qty form-control' name='stock_qty[]' value='$stockqty'> </td>
                        <td style='text-align: center'>$item->uom_name</td>
                        <td><input type='text' class='required_stock_qty form-control' name='purchase_required_stock_qty[]' value='$purchaseqty'></td>
                        <td style='text-align: center'>$unitLabel</td>
                    </tr>";
                } else {
                    $row .= "<tr style='border: 1px solid #000'>
                        <td style='text-align: center'>
                        <select onchange='getrawmaterial(this)' name='required_mat[]' class='required_mat form-control'>
                        <option value='$item->material'>" . \App\product::nameWithVariantInline($item->product_name, $item->value1 ?? null, $item->value2 ?? null) . "</option>";
                    foreach ($rawmaterial as $mat) {
                        $row .="<option value='$mat->id'>" . \App\product::nameWithVariantInline($mat->product_name, $mat->value1 ?? null, $mat->value2 ?? null) . "</option>";
                    }
                    $row .= "</select>
                        </td>
                        <td style='text-align: center'><input type='text' class='required_qty form-control' name='required_qty[]' value='$roundqty'> </td>
                        <td style='text-align: center'>$unitLabel</td>
                        <td style='text-align: center'><input type='text' class='stock_qty form-control' name='stock_qty[]' value='$item->stockqty'> </td>
                        <td style='text-align: center'>$item->uom_name</td>
                        <td><input type='text' class='required_stock_qty form-control' name='required_stock_qty[]' value='0'></td>
                        <td style='text-align: center'>$unitLabel</td>
                    </tr>";
                }

            }
            $row .= "</table>";
            if ($stockstatus == "low") {
                $row .= "<div class='col-md-2'><button name='stockstatus' value='low' class='btn btn-danger'>Move to Purchase (out of stock material)</button></div>";
            }
            if ($stockstatus == "balance") {
                $row .= "<div class='col-md-2'><button name='stockstatus' value='balance' class='btn btn-danger'>Allocate this machine for Production</button></div>";
            }
        //}
}
        //dd($row);
        return response()->json(['required_qty' => $required_qty, 'item' => $row]);
        //return $required_qty ?? "No Formula Found";
    }

    function machine_list(Request $request)
    {
        $machine = machine::orderBy("machine_name", "Asc")->get();
        $customer = customers::orderBy("customer_name", "asc")->get();
        $product = product::where("status", "product")->orderBy("product_name", "asc")->get();
        // Passed straight through to add_batch on whichever machine is picked.
        $prefill = $this->productionPrefill($request);
        return view("admin.production.machine_list", compact('machine', 'customer', 'product', 'prefill'));
    }
}
