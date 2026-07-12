<?php

namespace App\Http\Controllers;

use App\country;
use App\city;
use App\customer_order;
use App\customer_order_item;
use App\customers;
use App\industry;
use App\payment_terms;
use App\salesman;
use App\state;
use App\type;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use App\website_user;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Session;
use App\website;
use App\module_rights;
use App\sub_module_rights;
use App\module;
use App\sub_module;
use Spatie\Permission\Models\Role;
use function GuzzleHttp\Promise\all;

class UserController extends Controller
{
//
    function sales_check_password(Request $request)
    {
        //dd($request->all());

        $check = salesman::where("salesman_email", $request->email)
            ->where("salesman_password", $request->password)
            ->first();
        if (empty($check)) {
            return view("front.salesman.salesman_password")->with(['email' => $request->email, "message" => 'invalid password']);
        } else {

            Session::put("salesman_session", $check->id);
            Session::put("salesman_name", $check->salesman_name);
            Session::put("salesman_code",$check->salesman_code);
            return redirect()->route("index");
        }
    }
    function sales_email(Request $request)
    {
        $check = salesman::where("salesman_email", $request->email)->first();
        if (empty($check)) {
            return back()->with("message", "Email id is invalid");
        } else {
            return view("front.salesman.salesman_password")->with(['email' => $request->email]);
        }
    }

    function salesman_login(Request $request)
    {
        return view("front.salesman.login");
    }
    function user_update(Request $request)
    {
        $save=customers::find($request->id);
        //  dd($request->all());

        $request->validate([
            'customer_name' => 'required',
            'primary_email'=>'email:rfc,dns',
            'owner_name' => 'required|max:255',
            'owner_mobile' => 'required|max:255',
            'owner_email' =>'email:rfc,dns',
            'billing_address' => 'required',
            'shipping_address' => 'required',
            'billing_city' => 'required',
            'shipping_city' => 'required',
            'billing_postalcode' => 'required',
            'shipping_postalcode' => 'required',
            'shipping_country' => 'required',
            'work_email'=>'required',
            'primary_phone'=>'required',
            'alternate_no'=>'required',
            'billing_country'=>'required',
            'billing_state'=>'required',
            'shipping_state'=>'required',
            'department'=>'required',
            'designation'=>'required',
        ]);
        // / dd($request->all());
        $save->customer_name=$request->customer_name;
        $save->website=$request->website;
        $save->primary_phone=$request->primary_phone;
        $save->secondary_phone=$request->secondary_phone;
        $save->primary_email=$request->primary_email;
        $save->secondary_email=$request->secondary_email;
        $save->owner_name=$request->owner_name;
        $save->owner_mobile=$request->owner_mobile;
        $save->owner_email=$request->owner_email;
        $save->work_email=$request->work_email;
        $save->alternate_no=$request->alternate_no;
        $save->owner_gst=$request->owner_gst;
        $save->owner_pan=$request->owner_pan;
        $save->billing_address=$request->billing_address;
        $save->shipping_address=$request->shipping_address;
        $save->billing_pobox=$request->billing_pobox;
        $save->shipping_pobox=$request->shipping_pobox;
        $save->billing_city=$request->billing_city;
        $save->shipping_city=$request->shipping_city;
        $save->billing_state=$request->billing_state;
        $save->shipping_state=$request->shipping_state;
        $save->billing_postalcode=$request->billing_postalcode;
        $save->shipping_postalcode=$request->shipping_postalcode;
        $save->billing_country=$request->billing_country;
        $save->shipping_country=$request->shipping_country;
        $save->description=$request->description;
        $save->tax_preference=$request->tax_preference;
        $save->payment_terms=$request->payment_terms;
        $save->user_id=Session::get('user_id');
        $save->website_id=Session::get('website_id');
        $save->industry=$request->industry;
        $save->type=$request->type;
        $save->department=$request->department;
        $save->designation=$request->designation;
        if($save->save())
        {
            return back()->with('message','Your Details Update successfully');
        }else{
            return back()->with('message','error in save');
        }
    }
    function user_order(Request $request)
    {
        $order=customer_order::where("customer",Session::get("customer_session"))->orderBy("id","desc")
            ->get();

        $order_item=customer_order_item::select("customer_order_item.*","product.product_name","product.product_image")
            ->leftJoin("product","product.id","customer_order_item.product")
            ->get();

        $details=customers::findorFail(Session::get("customer_session"));

        $industry=[''=>'select industry']+industry::query()
                ->orderBy('industry_name')->get()->pluck('industry_name','id')->toArray();
        $type=[''=>'select type']+type::query()
                ->orderBy('type_name')->get()->pluck('type_name','id')->toArray();

        $country=[''=>'select country']+country::orderBy('country_name','asc')
                ->get()->pluck('country_name','id')->toArray();

        $state=[''=>'select state']+state::orderBy('state_name','asc')
                ->get()->pluck('state_name','id')->toArray();

        $city=[''=>'select city']+city::orderBy('city_name','asc')
                ->get()->pluck('city_name','id')->toArray();

        $payment_terms=[''=>'select']+payment_terms::orderBy("id","desc")
                ->get()->pluck("terms_name","days")->toArray();
        return view("front.user.user_side",compact("order","details","order_item","industry","type","country","state","city","payment_terms"));
    }

    function profile_update(Request $request)
    {
        $request->validate([
            'user_name' => 'required|max:255',
            'email'=>'email:rfc,dns',
            'password'=>'required',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'primary_phone' =>'required',
            'department' => 'required',
            'designation' => 'required',
            'city' => 'required',
            'state'=>'required',
            'country'=>'required',
        ]);

        $data=website_user::find($request->id);
        $data->user_name=$request->user_name;
        $data->email=$request->email;
        $data->password=$request->password;
        $data->first_name=$request->first_name;
        $data->last_name=$request->last_name;
        $data->primary_phone=$request->primary_phone;
        $data->secondary_phone=$request->secondary_phone;
        $data->secondary_email=$request->secondary_email;
        $data->aadhar_card=$request->aadhar_card;
        $data->pan_card=$request->pan_card;
        $data->department=$request->department;
        $data->designation=$request->designation;
        $data->address=$request->address;
        $data->city=$request->city;
        $data->state=$request->state;
        $data->country=$request->country;
        $data->description=$request->description;

        if($data->save())
        {
            return back()->with('message','Detail update successfully');
        }
    }

    function user_profile()
    {
        $country=[''=>'select country']+country::orderBy('country_name','asc')
                ->get()->pluck('country_name','id')->toArray();

        $state=[''=>'select state']+state::orderBy('state_name','asc')
                ->get()->pluck('state_name','id')->toArray();

        $city=[''=>'select city']+city::orderBy('city_name','asc')
                ->get()->pluck('city_name','id')->toArray();
        $data=website_user::where('id',Session::get('user_id'))->first();
        return view("admin/user/profile")
            ->with(['data'=>$data,'country'=>$country,'state'=>$state,'city'=>$city]);
    }

    function user_delete(Request $request)
    {
        module_rights::where('user_id',$request->id)->delete();
        sub_module_rights::where('user_id',$request->id)->delete();
        $wu=website_user::find($request->id);
        if($wu->delete())
        {
            return redirect()->route('admin/user_list')->with('message','user delete successfully');
        }
    }

    function web_user_update(Request $request)
    {
//dd($request->all());
        $wu=website_user::find($request->id);

        $wu->website=$request->website;
        $wu->user_name=$request->user_name;
        $wu->email=$request->email;
        $wu->password=$request->password;
        if($wu->save())
        {
            module_rights::where('user_id',$request->id)->delete();
            sub_module_rights::where('user_id',$request->id)->delete();
            if(empty($request->module))
            {}else{

                $totmodules=count($request->module);

                for($i=0;$i<$totmodules;$i++)
                {
                    $module_rights=new module_rights();
                    $module_rights->user_id=$wu->id;
                    $module_rights->module_id=$request->module[$i];
                    $module_rights->module_add=0;
                    $module_rights->module_edit=0;
                    $module_rights->module_delete=0;
                    $module_rights->module_view=0;
                    $module_rights->save();

                }


                if(empty($request->module_add))
                {}else{
                    $totmadd=count($request->module_add);
                    for($i=0;$i<$totmadd;$i++)
                    {
                        $array =explode(',', $request->module_add[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_add'=>$array[0],'access'=>1]);
                    }
                }

                if(empty($request->module_edit))
                {}else{
                    $totmedit=count($request->module_edit);
                    for($i=0;$i<$totmedit;$i++)
                    {
                        $array =  explode(',', $request->module_edit[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_edit'=>$array[0],'access'=>1]);
                    }
                }

                if(empty($request->module_delete))
                {}else{
                    $totmdelete=count($request->module_delete);
                    for($i=0;$i<$totmdelete;$i++)
                    {
                        $array =  explode(',', $request->module_delete[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_delete'=>$array[0],'access'=>1]);
                    }
                }

                if(empty($request->module_view))
                {}else{
                    $totmview=count($request->module_view);
                    for($i=0;$i<$totmview;$i++)
                    {
                        $array =  explode(',', $request->module_view[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_view'=>$array[0],'access'=>1]);
                    }
                }
            }


            if(empty($request->submodule))
            {}else{
                $totsubmodule=count($request->submodule);
                for($i=0;$i<$totsubmodule;$i++)
                {
                    $module_rights=new sub_module_rights();
                    $module_rights->user_id=$wu->id;
                    $main_module=sub_module::where('id',$request->submodule[$i])->first();
                    $module_rights->module_id=$request->submodule[$i] ?? '0';
                    $module_rights->main_module=$main_module->module ?? '0';

                    $module_rights->module_add=0;
                    $module_rights->module_edit=0;
                    $module_rights->module_delete=0;
                    $module_rights->module_view=0;

                    $module_rights->save();

                }

                if(empty($request->sub_module_add))
                {}else{
                    $totsadd=count($request->sub_module_add);
                    for($i=0;$i<$totsadd;$i++)
                    {
                        $array = explode(',', $request->sub_module_add[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('sub_module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_add'=>$array[0],'access'=>1]);
                    }
                }

                if(empty($request->sub_module_edit))
                {}else{
                    $totsedit=count($request->sub_module_edit);
                    for($i=0;$i<$totsedit;$i++)
                    {
                        $array =explode(',', $request->sub_module_edit[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('sub_module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_edit'=>$array[0],'access'=>1]);
                    }
                }

                if(empty($request->sub_module_delete))
                {}else{
                    $totsdelete=count($request->sub_module_delete);
                    for($i=0;$i<$totsdelete;$i++)
                    {
                        $array =explode(',', $request->sub_module_delete[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('sub_module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_delete'=>$array[0],'access'=>1]);
                    }
                }

                if(empty($request->sub_module_view))
                {}else{
                    $totsview=count($request->sub_module_view);
                    for($i=0;$i<$totsview;$i++)
                    {
                        $array =explode(',', $request->sub_module_view[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('sub_module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_view'=>$array[0],'access'=>1]);
                    }
                }

            }
            return redirect()->route('admin/user_list')->with('message','user add successfully');
        }
    }

    function user_edit(Request $request)
    {
        $website=[''=>'select website']+website::orderBy('website_name','asc')->get()->pluck('website_name','id')->toArray();
        $data=website_user::find($request->id);

        $str='<table class="table table-striped table-bordered"><tr><th>Module Name</th><th>Add</th><th>Edit</th><th>Delete</th><th>View</th></tr>';
        $module=module_rights::select('module.module_name','module.id as mid','module_rights.*')
            ->leftJoin('module','module.id','module_rights.module_id')
            ->where('module_rights.user_id',$request->id)
            ->get();

        $avamodules[]="";
        foreach ($module as $value) {
            $avamodules[]=$value->module_id;
// $module=module_rights::select('module.module_name','module.id as mid','module_rights.*')
// ->leftJoin('module','module.id','module_rights.module_id')
// ->where('module_rights.user_id',$request->id)
// ->first();

            $str .="<tr style='background: #aaa6b0;color: #fff;'>
        <td>".$value->module_name."</td>";
            $str .='<td style="display:none">
        <input type="text" value="'.$value->module_id.'" name="module[]"></td>';
            if($value->module_add==0)
            {
                $str .='<td><input type="checkbox" class="checkBoxClass" name="module_add[]" value="1,'.$value->module_id.'"></td>';
            }
            if($value->module_add==1)
            {
                $str .='<td><input type="checkbox" class="checkBoxClass" checked="checked" name="module_add[]" value="1,'.$value->module_id.'"></td>';
            }

            if($value->module_edit==0)
            {
                $str .='<td><input type="checkbox" class="checkBoxClass" name="module_edit[]" value="1,'.$value->module_id.'"></td>';
            }

            if($value->module_edit==1)
            {
                $str .='<td><input type="checkbox" checked="checked" class="checkBoxClass" name="module_edit[]" value="1,'.$value->module_id.'"></td>';
            }

            if($value->module_delete==0)
            {
                $str .='<td><input type="checkbox" class="checkBoxClass" name="module_delete[]" value="1,'.$value->module_id.'"></td>';
            }

            if($value->module_delete==1)
            {
                $str .='<td><input type="checkbox" class="checkBoxClass" name="module_delete[]" checked="checked" value="1,'.$value->module_id.'"></td>';
            }

            if($value->module_view==0)
            {
                $str .='<td><input type="checkbox"  class="checkBoxClass" name="module_view[]" value="1,'.$value->module_id.'"></td>';
            }
            if($value->module_view==1)
            {
                $str .='<td><input type="checkbox" class="checkBoxClass" checked="checked" name="module_view[]" value="1,'.$value->module_id.'"></td>';
            }


            $submodule=sub_module_rights::select('sub_module_rights.*','sub_module.submodule_name','sub_module.id as subid')
                ->leftJoin('sub_module','sub_module.id','sub_module_rights.module_id')
                ->where('sub_module_rights.main_module',$value->mid)
                ->where('sub_module_rights.user_id',$request->id)
                ->get();

            foreach ($submodule as $value1) {

                $str .='<tr><td>'.$value1->submodule_name.'</td>';
                $str .='<td  style="display:none">
          <input type="text" value="'.$value1->module_id.'" name="submodule[]"></td>';
                if($value1->module_add==0)
                {
                    $str .='<td><input type="checkbox" class="checkBoxClass" name="sub_module_add[]" value="1,'.$value1->module_id.'"></td>';
                }
                if($value1->module_add==1)
                {
                    $str .='<td><input type="checkbox" class="checkBoxClass" checked="checked" name="sub_module_add[]" value="1,'.$value1->module_id.'"></td>';
                }

                if($value1->module_edit==0)
                {
                    $str .='<td><input type="checkbox" class="checkBoxClass" name="sub_module_edit[]" value="1,'.$value1->module_id.'"></td>';
                }
                if($value1->module_edit==1)
                {
                    $str .='<td><input type="checkbox" class="checkBoxClass" checked="checked" name="sub_module_edit[]" value="1,'.$value1->module_id.'"></td>';
                }

                if($value1->module_delete==0)
                {
                    $str .='<td><input type="checkbox" class="checkBoxClass" name="sub_module_delete[]" value="1,'.$value1->module_id.'"></td>';
                }
                if($value1->module_delete==1)
                {
                    $str .='<td><input type="checkbox" class="checkBoxClass" checked="checked" name="sub_module_delete[]" value="1,'.$value1->module_id.'"></td>';
                }

                if($value1->module_view==0)
                {
                    $str .='<td><input type="checkbox" class="checkBoxClass" name="sub_module_view[]" value="1,'.$value1->module_id.'"></td>';
                }
                if($value1->module_view==1)
                {
                    $str .='<td><input type="checkbox" class="checkBoxClass" checked="checked" name="sub_module_view[]" value="1,'.$value1->module_id.'"></td>';
                }

                $str .="</tr>";

            }

            $str .="</tr>";
        }

        $avalible_module=module::orderBy('module_name','asc')
            ->whereNotIn('id',$avamodules)
            ->get();

        foreach ($avalible_module as $avaliblevalue) {
            $str .= "<tr style='background: #aaa6b0;color: #fff;'>";
            $str .='<td>'.$avaliblevalue->module_name.'</td>';
            $str .='<td style="display:none">
     <input type="text" value="'.$avaliblevalue->id.'" name="module[]">
     </td>';
            $str .='<td>
     <input type="checkbox" value="1,'.$avaliblevalue->id.'" name="module_add[]">
     </td>';
            $str .='<td>
     <input type="checkbox" value="1,'.$avaliblevalue->id.'" name="module_edit[]">
     </td>';
            $str .='<td>
     <input type="checkbox" value="1,'.$avaliblevalue->id.'" name="module_delete[]">
     </td>';
            $str .='<td>
     <input type="checkbox" value="1,'.$avaliblevalue->id.'" name="module_view[]">
     </td>';

            $subm=sub_module::orderBy('submodule_name','asc')
                ->where('module',$avaliblevalue->id)
                ->get();
            foreach ($subm as $subavaliblevalue) {
                $str .='<tr>';
                $str .='<td style="display:none">
      <input type="text" value="'.$subavaliblevalue->id.'" name="submodule[]">
      </td>';
                $str .='<td>
      <input type="checkbox" value="1,'.$subavaliblevalue->id.'" name="sub_module_add[]">
      </td>';
                $str .='<td>
      <input type="checkbox" value="1,'.$subavaliblevalue->id.'" name="sub_module_edit[]">
      </td>';
                $str .='<td>
      <input type="checkbox" value="1,'.$subavaliblevalue->id.'" name="sub_module_delete[]">
      </td>';
                $str .='<td>
      <input type="checkbox" value="1,'.$subavaliblevalue->id.'" name="sub_module_view[]">
      </td>';
                $str .='</tr>';
            }

            $str .= "</tr>";


        }
        $str .="</table>";

        $str;

        return view("admin/user/user_edit",compact('website','str','data'));
    }

    function web_user_save(Request $request)
    {

        $wu=new website_user();

        $wu->website=$request->website;
        $wu->user_name=$request->user_name;
        $wu->email=$request->email;
        $wu->password=$request->password;
        if($wu->save())
        {
//module_rights::where('user_id',$request->id)->delete();
            if(empty($request->module))
            {}else{

                $totmodules=count($request->module);

                for($i=0;$i<$totmodules;$i++)
                {
                    $module_rights=new module_rights();
                    $module_rights->user_id=$wu->id;
                    $module_rights->module_id=$request->module[$i];
                    $module_rights->module_add=0;
                    $module_rights->module_edit=0;
                    $module_rights->module_delete=0;
                    $module_rights->module_view=0;
                    $module_rights->save();

                }
                if(empty($request->module_add))
                {}else{
                    $totmadd=count($request->module_add);
                    for($i=0;$i<$totmadd;$i++)
                    {
                        $array =explode(',', $request->module_add[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_add'=>$array[0],'access'=>1]);
                    }
                }

                if(empty($request->module_edit))
                {}else{
                    $totmedit=count($request->module_edit);
                    for($i=0;$i<$totmedit;$i++)
                    {
                        $array =  explode(',', $request->module_edit[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_edit'=>$array[0],'access'=>1]);
                    }
                }

                if(empty($request->module_delete))
                {}else{
                    $totmdelete=count($request->module_delete);
                    for($i=0;$i<$totmdelete;$i++)
                    {
                        $array =  explode(',', $request->module_delete[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_delete'=>$array[0],'access'=>1]);
                    }
                }

                if(empty($request->module_view))
                {}else{
                    $totmview=count($request->module_view);
                    for($i=0;$i<$totmview;$i++)
                    {
                        $array =  explode(',', $request->module_view[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_view'=>$array[0],'access'=>1]);
                    }
                }
            }

//sub_module_rights::where('user_id',$request->id)->delete();
            if(empty($request->submodule))
            {}else{
                $totsubmodule=count($request->submodule);
                for($i=0;$i<$totsubmodule;$i++)
                {
                    $module_rights=new sub_module_rights();
                    $module_rights->user_id=$wu->id;
                    $main_module=sub_module::where('id',$request->submodule[$i])->first();
                    $module_rights->module_id=$request->submodule[$i] ?? '0';
                    $module_rights->main_module=$main_module->module ?? '0';

                    $module_rights->module_add=0;
                    $module_rights->module_edit=0;
                    $module_rights->module_delete=0;
                    $module_rights->module_view=0;

                    $module_rights->save();

                }

                if(empty($request->sub_module_add))
                {}else{
                    $totsadd=count($request->sub_module_add);
                    for($i=0;$i<$totsadd;$i++)
                    {
                        $array = explode(',', $request->sub_module_add[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('sub_module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_add'=>$array[0],'access'=>1]);
                    }
                }

                if(empty($request->sub_module_edit))
                {}else{
                    $totsedit=count($request->sub_module_edit);
                    for($i=0;$i<$totsedit;$i++)
                    {
                        $array =explode(',', $request->sub_module_edit[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('sub_module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_edit'=>$array[0],'access'=>1]);
                    }
                }

                if(empty($request->sub_module_delete))
                {}else{
                    $totsdelete=count($request->sub_module_delete);
                    for($i=0;$i<$totsdelete;$i++)
                    {
                        $array =explode(',', $request->sub_module_delete[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('sub_module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_delete'=>$array[0],'access'=>1]);
                    }
                }

                if(empty($request->sub_module_view))
                {}else{
                    $totsview=count($request->sub_module_view);
                    for($i=0;$i<$totsview;$i++)
                    {
                        $array =explode(',', $request->sub_module_view[$i]);
                        $right=$array[0];
                        $right=$array[1];

                        DB::table('sub_module_rights')
                            ->where('user_id',$wu->id)
                            ->where('module_id',$array[1])
                            ->update(['module_view'=>$array[0],'access'=>1]);
                    }
                }

            }
            return redirect()->route('admin/user_list')->with('message','user add successfully');
        }
    }
    function user_add(Request $req)
    {
        $website=website::orderBy('website_name','asc')
            ->get()
            ->pluck('website_name','id')
            ->toArray();

        $website_user=website_user::where('master_user','=','1')->first();

        $str='<table class="table table-striped table-bordered"><tr><th>Module Name</th><th>Add</th><th>Edit</th><th>Delete</th><th>View</th></tr>';

        $module=module_rights::select('module.module_name','module.id as mid','module_rights.*')
            ->leftJoin('module','module.id','module_rights.module_id')
            ->where('module_rights.user_id',$website_user->id)
            ->get();

        foreach ($module as $value) {
            $str .="<tr style='background: #aaa6b0;color: #fff;'>
                    <td>".$value->module_name."</td>";
            $str .='<td style="color:#000;display:none">
                    <input type="text" value="'.$value->module_id.'" name="module[]"></td>';
            $str .='<td><input type="checkbox" name="module_add[]" class="checkBoxClass"  value="1,'.$value->module_id.'"></td>';
            $str .='<td><input type="checkbox" name="module_edit[]" class="checkBoxClass"  value="1,'.$value->module_id.'"></td>';
            $str .='<td><input type="checkbox" name="module_delete[]" class="checkBoxClass"  value="1,'.$value->module_id.'"></td>';
            $str .='<td><input type="checkbox" name="module_view[]" class="checkBoxClass"  value="1,'.$value->module_id.'"></td>';
            $submodule=sub_module_rights::select('sub_module_rights.*','sub_module.submodule_name','sub_module.id as subid')
                ->leftJoin('sub_module','sub_module.id','sub_module_rights.module_id')
                ->where('sub_module_rights.main_module',$value->mid)
                ->where('sub_module_rights.user_id',$website_user->id)
                ->get();

            foreach ($submodule as $value1) {
                $str .='<tr><td>'.$value1->submodule_name.'</td>';
                $str .='<td style="color:#000;display:none">
                     <input type="text" value="'.$value1->module_id.'" name="submodule[]"></td>';
                $str .='<td><input type="checkbox" name="sub_module_add[]" class="checkBoxClass"  value="1,'.$value1->module_id.'"></td>';
                $str .='<td><input type="checkbox" name="sub_module_edit[]" class="checkBoxClass"  value="1,'.$value1->module_id.'"></td>';
                $str .='<td><input type="checkbox" name="sub_module_delete[]" class="checkBoxClass"  value="1,'.$value1->module_id.'"></td>';
                $str .='<td><input type="checkbox" name="sub_module_view[]" class="checkBoxClass"  value="1,'.$value1->module_id.'"></td>';
                $str .="</tr>";
            }
            $str .="</tr>";
        }
        $str .="</table>";

        return view("admin/user/user_add",compact('website','str'));
    }
    function user_list()
    {
        $data=website_user::select('website_user.*','website.website_name')
            ->leftJoin('website','website.id','website_user.website')
            ->orderBy('website_user.id','desc')
            ->get();



        return view("admin/user_list")->with(['data'=>$data]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        $data = User::orderBy('id','DESC')

            ->paginate(15);
        return view('admin.user.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('admin.user.create',compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $user = User::create($input);
        $user->assignRole($request->input('roles'));

        return redirect()->route('admin.user.list')
            ->with('success','User created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function show($id)
    {
        $user = User::find($id);
        return view('users.show',compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name','name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
        return view('admin.user.user_edit',compact('user','roles','userRole'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request)
    {
        $id = $request->id;
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password',
            'roles' => 'required'
        ]);

        $input = $request->all();
        if(!empty($input['password'])){
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));
        }

        $user = User::find($id);
        $user->update($input);
        DB::table('model_has_roles')->where('model_id',$id)->delete();

        $user->assignRole($request->input('roles'));

        return redirect()->route('admin.user.list')
            ->with('success','User updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        User::find($id)->delete();
        return redirect()->route('admin.user.list')
            ->with('success','User deleted successfully');
    }
}
