<?php

namespace App\Http\Controllers;

use App\delivery_challan;
use App\finacial_year;
use App\invoice;
use App\purchase;
use App\salesorder;
use Illuminate\Http\Request;
use App\website;
use App\website_user;
use App\module;
use App\module_rights;
use Illuminate\Support\Facades\Auth;
use Session;
use Illuminate\Support\Facades\DB;
use App\master_module;
use App\master_website_user;
use App\master_module_rights;
use App\master_website_rights;
use App\customers;
use App\quotation;
use App\product;
use App\service_renewal;
use App\sub_module;
use App\sub_module_rights;
use File;
use Config;
use Artisan;
use App\LogActivity;
class MasterController extends Controller
{
    function main_user_edit(Request $request)
    {

    }

    function main_user_list()
    {
        $data=website_user::select('website_user.*','website.website_name')
            ->leftJoin('website','website.id','website_user.website')
            ->where('master_user','1')
            ->orderBy('website_user.id','desc')
            ->get();

        $totuser=website_user::select('website_user.*','website.website_name')
            ->leftJoin('website','website.id','website_user.website')
            ->where('master_user','1')
            ->orderBy('website_user.id','desc')
            ->count();
        //dd($totuser);
        //$website=[''=>'select website']+website::orderBy('website_name','asc')->get()->pluck('website_name','id')->toArray();

        return view("master_panel/main_user_list")->with(['data'=>$data,'totuser'=>$totuser]);
    }
    function main_user_save(Request $request)
    {

        $wu=new website_user();

        $wu->website=$request->website;
        $wu->user_name=$request->user_name;
        $wu->email=$request->email;
        $wu->password=$request->password;
        $wu->master_user=1;
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
            return redirect()->route('master/main_user_list')->with('message','user add successfully');

        }
    }
    function main_user_add()
    {
        $website=website::orderBy('website_name','asc')
            ->get()
            ->pluck('website_name','id')
            ->toArray();
        //dd($website);
        $module=module::orderBy('module_name','asc')
            ->get();
        $str='<table class="table table-striped table-bordered"><tr><th>Module Name</th><th>Add</th><th>Edit</th><th>Delete</th><th>View</th></tr>';

        foreach($module as $avaliblevalue)
        {
            $str .= "<tr style='background: #aaa6b0;color: #fff;'>";
            $str .='<td>'.$avaliblevalue->module_name.'</td>';
            $str .='<td style="display:none">
     <input type="hidden" value="'.$avaliblevalue->id.'" name="module[]">
     </td>';
            $str .='<td>
     <input type="checkbox" value="1,'.$avaliblevalue->id.'" class="checkBoxClass" name="module_add[]">
     </td>';
            $str .='<td>
     <input type="checkbox" value="1,'.$avaliblevalue->id.'" class="checkBoxClass" name="module_edit[]">
     </td>';
            $str .='<td>
     <input type="checkbox" value="1,'.$avaliblevalue->id.'" class="checkBoxClass" name="module_delete[]">
     </td>';
            $str .='<td>
     <input type="checkbox" value="1,'.$avaliblevalue->id.'" class="checkBoxClass" name="module_view[]">
     </td>';

            $subm=sub_module::orderBy('submodule_name','asc')
                ->where('module',$avaliblevalue->id)
                ->get();
            foreach ($subm as $subavaliblevalue) {
                $str .='<tr>';
                $str .='<td style="display:none">
      <input type="hidden" value="'.$subavaliblevalue->id.'" name="submodule[]">
      </td>';
                $str .='<td>'.$subavaliblevalue->submodule_name.'</td>';
                $str .='<td>
      <input type="checkbox" value="1,'.$subavaliblevalue->id.'" class="checkBoxClass" name="sub_module_add[]">
      </td>';
                $str .='<td>
      <input type="checkbox" value="1,'.$subavaliblevalue->id.'" class="checkBoxClass" name="sub_module_edit[]">
      </td>';
                $str .='<td>
      <input type="checkbox" value="1,'.$subavaliblevalue->id.'" class="checkBoxClass" name="sub_module_delete[]">
      </td>';
                $str .='<td>
      <input type="checkbox" value="1,'.$subavaliblevalue->id.'" class="checkBoxClass" name="sub_module_view[]">
      </td>';
                $str .='</tr>';
            }

            $str .= "</tr>";
        }
        $str .="</table>";
        return view('master_panel/main_user',compact('str','website'));
    }
    function submodule_delete(Request $request)
    {
        $sub_module=sub_module::find($request->id);

        if($sub_module->delete())
        {
            return redirect()->route("master/submodule")->with("message","submodule delete successfully");
        }
    }
    function submodule_update(Request $request)
    {
        $sub_module=sub_module::find($request->id);
        $sub_module->module=$request->module;
        $sub_module->submodule_name=$request->submodule_name;
        $sub_module->submodule_url=$request->submodule_url;
        $sub_module->icon=$request->icon;
        $sub_module->srno=$request->srno;
        if($sub_module->save())
        {
            return redirect()->route("master/submodule")->with("message","submodule Update successfully");
        }
    }
    function submodule_edit(Request $request)
    {
        $module=[''=>'select module']+module::orderBy('module_name','asc')
                ->get()->pluck('module_name','id')->toArray();

        $data=sub_module::find($request->id);

        return view("master_panel/submodule_edit")->with(['module'=>$module,'data'=>$data]);
    }
    function sub_module_save(Request $request)
    {
        $sub_module=new sub_module();
        $sub_module->module=$request->module;
        $sub_module->submodule_name=$request->submodule_name;
        $sub_module->submodule_url=$request->submodule_url;
        $sub_module->icon=$request->icon;
        $sub_module->srno=$request->srno;

        if($sub_module->save())
        {
            return redirect()->route("master/submodule")->with("message","submodule save successfully");
        }
    }

    function submodule_add()
    {
        $module=[''=>'select module']+module::orderBy('module_name','asc')
                ->get()->pluck('module_name','id')->toArray();
        return view("master_panel/submodule_add")->with(['module'=>$module]);

    }
    function submodule_list()
    {
        $data=sub_module::select("sub_module.*",'module.module_name')
            ->leftJoin('module','module.id','sub_module.module')
            ->get();
        return view("master_panel/submodule_list")->with(['data'=>$data]);
    }
    function master_user_delete(Request $request)
    {
        master_website_user::where('id',$request->id)->delete();
        master_module_rights::where('user_id',$request->id)->delete();
        master_website_rights::where('user_id',$request->id)->delete();

        return redirect()->route('master/master_user_list')->with('message','Master user delete successfully');
    }

    function master_user_update(Request $request)
    {
        $wu=master_website_user::find($request->id);

        $wu->user_name=$request->user_name;
        $wu->email=$request->email;
        $wu->password=$request->password;
        if($wu->save())
        {
            master_module_rights::where('user_id',$request->id)->delete();
            if(empty($request->module))
            {}else{




                $totmodules=count($request->module);

                for($i=0;$i<$totmodules;$i++)
                {
                    $module_rights=new master_module_rights();
                    $module_rights->user_id=$wu->id;
                    $module_rights->module_id=$request->module[$i];
                    $module_rights->save();

                }
            }
            master_website_rights::where('user_id',$request->id)->delete();
            if(empty($request->website))
            {}else{




                $totweb=count($request->website);

                for($i=0;$i<$totweb;$i++)
                {
                    $web_rights=new master_website_rights();
                    $web_rights->user_id=$wu->id;
                    $web_rights->website_id=$request->website[$i];
                    $web_rights->save();

                }
            }

            return redirect()->route('master/master_user_list')->with('message','Master user add successfully');
        }
    }

    function master_user_edit(Request $request)
    {

        $webuser=master_website_user::find($request->id);

        $rights=master_module_rights::select('master_module_rights.*','master_module.module_name','master_module.module_url','master_module.id as mid')
            ->leftJoin('master_module','master_module.id','master_module_rights.module_id')
            ->where('master_module_rights.user_id',$request->id)
            ->get();
        $str='';
        $module[]='';
        foreach($rights as $r)
        {
            $module[] = $r->mid;

        }
        $avalible_module=master_module::orderBy('module_name','asc')->whereNotIn('id',$module)->get();


        $websiterights=master_website_rights::select('master_website_rights.*','website.website_name','website.id as webid')
            ->leftJoin('website','website.id','master_website_rights.website_id')
            ->where('master_website_rights.user_id',$request->id)
            ->get();

        $str='';
        //dd($websiterights);
        $web[]='';
        foreach($websiterights as $r1)
        {
            $web[] = $r1->mid;

        }

        $avalible_website=website::orderBy('website_name','asc')->whereNotIn('id',$web)->get();

        return view('master_panel.master_user_edit')->with(['webuser'=>$webuser,'rights'=>$rights,'avalible_module'=>$avalible_module,'avalible_website'=>$avalible_website,'websiterights'=>$websiterights]);
    }

    function master_user_save(Request $request)
    {
        $wu=new master_website_user();

        $wu->user_name=$request->user_name;
        $wu->email=$request->email;
        $wu->password=$request->password;
        if($wu->save())
        {
            if(empty($request->module))
            {}else{
                $totmodules=count($request->module);

                for($i=0;$i<$totmodules;$i++)
                {
                    $module_rights=new master_module_rights();
                    $module_rights->user_id=$wu->id;
                    $module_rights->module_id=$request->module[$i];
                    $module_rights->save();

                }
            }

            if(empty($request->website))
            {}else{
                $totwebsite=count($request->website);

                for($i=0;$i<$totwebsite;$i++)
                {
                    $website_rights=new master_website_rights();
                    $website_rights->user_id=$wu->id;
                    $website_rights->website_id=$request->website[$i];
                    $website_rights->save();

                }
            }

            return redirect()->route('master/master_user_list')->with('message','user add successfully');
        }
    }

    function master_user_add()
    {
        $website=website::orderBy('website_name','asc')->get();

        $module_list=master_module::orderBy('module_name','asc')->get();

        return view("master_panel/master_user_add")->with(['website'=>$website,'module_list'=>$module_list]);

    }

    function master_list(Request $request)
    {
        $data=master_website_user::select('master_website_user.*')
            ->orderBy('master_website_user.id','desc')
            ->get();

        //$website=[''=>'select website']+website::orderBy('website_name','asc')->get()->pluck('website_name','id')->toArray();

        return view("master_panel/master_user_list")->with(['data'=>$data]);
    }

    function master_module_delete(Request $request)
    {
        $m=master_module::find($request->id);
        if($m->delete())
        {
            return redirect()->route('master/master_module_list')->with('message','module delete successfully');
        }
    }

    function master_module_update(Request $request)
    {
        $m=master_module::find($request->id);
        $m->module_name=$request->module_name;
        $m->module_url=$request->module_url;
        if($m->save())
        {
            return redirect()->route('master/master_module_list')->with('message','module update successfully');
        }
    }

    function master_module_edit(Request $request)
    {
        $m=master_module::find($request->id);

        return view('master_panel/master_module_edit')->with(['data'=>$m]);
    }
    function master_module_save(Request $request)
    {
        $m=new master_module();
        $m->module_name=$request->module_name;
        $m->module_url=$request->module_url;
        if($m->save())
        {
            return redirect()->route('master/master_module_list')->with('message','module save successfully');
        }
    }

    function master_module_add()
    {
        return view('master_panel/master_module_add');
    }
    function master_module_list()
    {
        $data=master_module::orderBy('module_name','asc')->get();
        return view('master_panel/master_module_list')->with(['data'=>$data]);
    }


    function user_update(Request $request)
    {
        // echo $totsubmodule=count(array_unique($request->submodule));
        // dd($request->all());
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
                $totsubmodule=count(array_unique($request->submodule));
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
            return redirect()->route('master/user_list')->with('message','user update successfully');
        }
//						$wu=website_user::find($request->id);
//
//						$wu->website=$request->website;
//						$wu->user_name=$request->user_name;
//						$wu->email=$request->email;
//						$wu->password=$request->password;
//						if($wu->save())
//						{
//							module_rights::where('user_id',$request->id)->delete();
//							if(empty($request->module))
//								{}else{
//
//
//
//									$totmodules=count($request->module);
//
//									for($i=0;$i<$totmodules;$i++)
//									{
//										$module_rights=new module_rights();
//										$module_rights->user_id=$wu->id;
//										$module_rights->module_id=$request->module[$i];
//										$module_rights->save();
//
//									}
//								}
//
//								sub_module_rights::where('user_id',$request->id)->delete();
//								if(empty($request->submodule))
//									{}else{
//										$totsubmodule=count($request->submodule);
//										for($i=0;$i<$totsubmodule;$i++)
//										{
//											$module_rights=new sub_module_rights();
//											$module_rights->user_id=$wu->id;
//											$main_module=sub_module::where('id',$request->submodule[$i])->first();
//											$module_rights->module_id=$request->submodule[$i];
//											$module_rights->main_module=$main_module->module;
//											$module_rights->save();
//										}
//									}
//									return redirect()->route('master/user_list')->with('message','user add successfully');
//								}
    }

    function user_edit(Request $request)
    {
        $website=[''=>'select website']+website::orderBy('website_name','asc')->get()->pluck('website_name','id')->toArray();
        $data=website_user::find($request->id);

        $str='<table class="table table-striped table-bordered"><tr><th>Module Name</th><th>Add</th><th>Edit</th><th>Delete</th><th>View</th></tr>';
        $module=module_rights::select('module.module_name','module.id as mid','module_rights.*')
            ->join('module','module.id','module_rights.module_id')
            ->where('module_rights.user_id',$request->id)
            ->orderBy('module.srno',"asc")
            ->get();

        $avamodules[]=$avsubmodules[]="";
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


            // $submodule=sub_module_rights::select('sub_module_rights.*','sub_module.submodule_name','sub_module.id as subid')
            //     ->join('sub_module','sub_module.id','sub_module_rights.module_id')
            //     ->where('sub_module_rights.main_module',$value->mid)
            //     ->where('sub_module_rights.user_id',$request->id)
            //     ->groupBy("sub_module.submodule_name")
            //     ->orderBy('sub_module.srno',"asc")
            //     ->get();

            $submodule = DB::table('sub_module_rights')
                ->join('sub_module','sub_module.id','sub_module_rights.module_id')
                ->where('sub_module_rights.main_module',$value->mid)
                ->where('sub_module_rights.user_id',$request->id)
                ->orderBy('sub_module.srno',"asc")
                ->distinct("sub_module.submodule_name")
                ->select('sub_module_rights.*','sub_module.submodule_name','sub_module.id as subid')
                ->get();



            foreach ($submodule as $value1) {
                $avsubmodules[]=$value1->module_id;
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
        //dd($avalible_module);

        $avalible_sub_module=sub_module::orderBy('submodule_name','asc')
            ->whereNotIn('id',$avsubmodules)
            ->get();

        foreach ($avalible_module as $avaliblevalue) {
            $str .= "<tr style='background: #aaa6b0;color: #fff;'>";
            $str .='<td>'.$avaliblevalue->module_name.'</td>';
            $str .='<td style="display:none">
                     <input type="text" value="'.$avaliblevalue->id.'" name="module[]">
                     </td>';
            $str .='<td>
                     <input type="checkbox" value="1,'.$avaliblevalue->id.'" class="checkBoxClass" name="module_add[]">
                     </td>';
            $str .='<td>
                     <input type="checkbox" value="1,'.$avaliblevalue->id.'" class="checkBoxClass" name="module_edit[]">
                     </td>';
            $str .='<td>
                     <input type="checkbox" value="1,'.$avaliblevalue->id.'" class="checkBoxClass" name="module_delete[]">
                     </td>';
            $str .='<td>
                     <input type="checkbox" value="1,'.$avaliblevalue->id.'" class="checkBoxClass" name="module_view[]">
                     </td>';

            $subm=sub_module::orderBy('submodule_name','asc')
                ->where('module',$avaliblevalue->id)
                ->get();
            foreach ($subm as $subavaliblevalue) {
                $str .='<tr>';
                $str .='<td style="display:none">
                      <input type="text" value="'.$subavaliblevalue->id.'" name="submodule[]">
                      </td>';
                $str .='<td>'.$subavaliblevalue->submodule_name.'</td>';
                $str .='<td>
                      <input type="checkbox" value="1,'.$subavaliblevalue->id.'" class="checkBoxClass" name="sub_module_add[]">
                      </td>';
                $str .='<td>
                      <input type="checkbox" value="1,'.$subavaliblevalue->id.'" class="checkBoxClass" name="sub_module_edit[]">
                      </td>';
                $str .='<td>
                      <input type="checkbox" value="1,'.$subavaliblevalue->id.'" class="checkBoxClass" name="sub_module_delete[]">
                      </td>';
                $str .='<td>
                      <input type="checkbox" value="1,'.$subavaliblevalue->id.'" class="checkBoxClass" name="sub_module_view[]">
                      </td>';
                $str .='</tr>';
            }


            $str .= "</tr>";


        }

        foreach ($avalible_sub_module as $asubm) {
            $str .='<tr>';
            $str .='<td style="display:none">
                      <input type="text" value="'.$asubm->id.'" name="submodule[]">
                      </td>';
            $str .='<td>'.$asubm->submodule_name.'</td>';
            $str .='<td>
                      <input type="checkbox" value="1,'.$asubm->id.'" class="checkBoxClass" name="sub_module_add[]">
                      </td>';
            $str .='<td>
                      <input type="checkbox" value="1,'.$asubm->id.'" class="checkBoxClass" name="sub_module_edit[]">
                      </td>';
            $str .='<td>
                      <input type="checkbox" value="1,'.$asubm->id.'" class="checkBoxClass" name="sub_module_delete[]">
                      </td>';
            $str .='<td>
                      <input type="checkbox" value="1,'.$asubm->id.'" class="checkBoxClass" name="sub_module_view[]">
                      </td>';
            $str .='</tr>';
        }
        $str .="</table>";

        $str;
        //d($str);
        return view('master_panel.user_edit',compact('website','str','data'));

//								$website=[''=>'select website']+website::orderBy('website_name','asc')->get()->pluck('website_name','id')->toArray();
//								$webuser=website_user::find($request->id);
//								$rights=module_rights::select('module_rights.*','module.module_name','module.module_url','module.id as mid')
//								->leftJoin('module','module.id','module_rights.module_id')
//								->where('module_rights.user_id',$request->id)
//								->get();
//								$str='';
//								$module[]='';
//								foreach($rights as $r)
//								{
//									$module[] = $r->mid;
//
//								}
//
//								$subrights=sub_module_rights::select('sub_module_rights.*','sub_module.submodule_name','sub_module.submodule_url','sub_module.id as smid')
//								->Join('sub_module','sub_module.id','sub_module_rights.module_id')
//								->where('sub_module_rights.user_id',$request->id)
//								->get();
//				//dd($subrights);
//								$str='';
//								$submodule[]='';
//								foreach($subrights as $r)
//								{
//									$submodule[] = $r->smid;
//
//								}
//
//								$avalible_module=module::orderBy('module_name','asc')->whereNotIn('id',$module)->get();
//
//								$avalible_submodule=sub_module::orderBy('submodule_name','asc')->whereNotIn('id',$submodule)->get();
//
//		//dd($avalible_submodule);
//
//								return view('master_panel.user_edit')->with(['webuser'=>$webuser,'rights'=>$rights,'avalible_module'=>$avalible_module,'website'=>$website,'subrights'=>$subrights,'avalible_submodule'=>$avalible_submodule]);
//
    }

    function logout(Request $request)
    {
        Auth::logout();
        return redirect()->route('login');
        Session::forget('user_id');
        Session::forget('user_name');
        Session::forget('user_module');
        Session::forget('website_id');

        if(!Session::has('user_id'))
        {
            return redirect()->route("user/login");
        }
    }
    function user_login(Request $request)
    {
        date_default_timezone_set("Asia/Kolkata");

        $request->validate([
            'username' => 'required',
            'password' => 'required',
            'finacial_year' => 'required',
        ]);

        //dd($request->all());

        $check=website_user::where('user_name',$request->username)
            ->where('password',$request->password)
            ->first();

        $finacial_year=finacial_year::find($request->finacial_year);
        //dd($check);
        if(empty($check))
        {
            return back()->with('message','username or password invalid');
        }else{

            $rights=module_rights::select('module_rights.*','module.module_name','module.module_url','module.icon')
                ->leftJoin('module','module.id','module_rights.module_id')
                ->where('module_rights.user_id',$check->id)
                ->where('module_rights.access','=',1)
                ->orderBy('module.srno','asc')
                ->get();

            //dd($rights);
            Session::put('parent_module',$rights);

            $wensite_detail=website::select('software_title')
                ->where('id',$check->website)->first();

            $menu="";
            $subrights1=sub_module_rights::select('sub_module_rights.*','sub_module.submodule_name','sub_module.submodule_url','sub_module.srno')
                ->leftJoin('sub_module','sub_module.id','sub_module_rights.module_id')
                ->where('sub_module_rights.user_id',$check->id)
                ->where('sub_module_rights.access','=',1)
                ->orderBy('sub_module.srno','asc')
                ->get();
            Session::put('child_module',$subrights1);
            foreach($rights as $r)
            {
                $subrights=sub_module_rights::select('sub_module_rights.*','sub_module.submodule_name','sub_module.submodule_url','sub_module.srno')
                    ->leftJoin('sub_module','sub_module.id','sub_module_rights.module_id')
                    ->where('sub_module_rights.user_id',$check->id)
                    ->where('sub_module_rights.main_module',$r->module_id)
                    ->where('sub_module_rights.access','=',1)
                    ->orderBy('sub_module.srno','asc')
                    ->get();

                $subcount=sub_module_rights::select('sub_module_rights.*','sub_module.submodule_name','sub_module.submodule_url','sub_module.srno')
                    ->leftJoin('sub_module','sub_module.id','sub_module_rights.module_id')
                    ->where('sub_module_rights.user_id',$check->id)
                    ->where('sub_module_rights.main_module',$r->module_id)
                    ->where('sub_module_rights.access','=',1)
                    ->orderBy('sub_module.srno','asc')
                    ->count();

                if($subcount==0)
                {

                    if(empty($r->module_url))
                    {
                        $menu .='<li class="has_sub">
												<a href="#" class="waves-effect">
												<i class="'.$r->icon.'"></i>
												<span>'.$r->module_name.' </span></a>';
                        $menu .='</li>';
                    }else{


                        $menu .='<li class="has_sub">
												<a href="'.url($r->module_url).'" class="waves-effect">
												<i class="'.$r->icon.'"></i>
												<span>'.$r->module_name.' </span></a>';
                        $menu .='</li>';
                    }
                }else{

                    $menu .='<li class="has_sub">
											<a href="javascript:void(0);" class="waves-effect">
											<i class="'.$r->icon.'"></i>
											<span> '.$r->module_name.' </span>
											<span class="menu-arrow"></span>
											</a>
											<ul class="list-unstyled">';
                    foreach($subrights as $sright)
                    {
                        if(empty($sright->submodule_url))
                        {

                        }else{
                            $menu .='
												<li><a href="'.url($sright->submodule_url).'">'.$sright->submodule_name.'</a></li>
												';
                        }

                    }

                    $menu .='</ul></li>';
                }

            }

            Session::put('user_id',$check->id);
            Session::put('user_name',$check->user_name);
            Session::put('user_detail',$check);
            Session::put('user_module',$rights);
            Session::put('website_id',$check->website);
            Session::put('menu',$menu);
            Session::put("finacial_year_name",$finacial_year->finacial_year);
            Session::put("finacial_year_id",$finacial_year->id);
            Session::put("finacial_year_start_date",$finacial_year->start_date);
            Session::put("finacial_year_end_date",$finacial_year->end_date);

            Session::put('software_title',$wensite_detail->software_title);

            $company_settings = \App\company::first();
            Session::put('records_per_page', $company_settings->records_per_page ?? 30);


            $totcustomer=customers::query()->count();

            $totquotation=quotation::query()
                ->where("finacial_year",Session::get('finacial_year_id'))
                ->count();

            $totsales=salesorder::query()
                ->where("finacial_year",Session::get('finacial_year_id'))
                ->count();

            $totpurchase=purchase::query()
                ->where("finacial_year",Session::get('finacial_year_id'))
                ->count();

            $totdelivery=delivery_challan::query()
                ->where("finacial_year",Session::get('finacial_year_id'))
                ->count();

            $totinvoice=invoice::query()
                ->where("finacial_year",Session::get('finacial_year_id'))
                ->count();

            $totproduct=product::query()->count();

            $service_renewal=service_renewal::select('service_renewal.*','customers.customer_name','uom.uom_name','category.category_name','product.product_name')
                ->leftJoin('customers','customers.id','service_renewal.customer')
                ->leftJoin('uom','uom.id','service_renewal.usage_unit')
                ->leftJoin('category','category.id','service_renewal.category')
                ->leftJoin('product','product.id','service_renewal.service')
                ->orderBy('service_renewal.support_expiry_date','desc')
                ->get();


            $start =date('Y-m-d', strtotime('-10 days'));
            $end =date('Y-m-d', strtotime('-5 days'));

            $product = new quotation();


            $product=$product->select('quotation.*','customers.customer_name','customers.primary_email','customers.secondary_email');
            $product=$product->leftJoin('customers','customers.id','quotation.customer');
            $product=$product;
            $product=$product->whereBetween('quotation.quot_date', [$start, $end]);
            $product=$product->orderBy('id','desc');
            $product=$product->where("quotation.finacial_year",$request->finacial_year);
            $result=$product->get();

            return view('admin/index')
                ->with(['totinvoice'=>$totinvoice,'totdelivery'=>$totdelivery,'totpurchase'=>$totpurchase,'totsales'=>$totsales,'totcustomer'=>$totcustomer,'totquotation'=>$totquotation,'totproduct'=>$totproduct,'service_renewal'=>$service_renewal,'menu'=>$menu,'quot'=>$result]);

            //return redirect()->route("index");


        }
    }

    function login(Request $request)
    {
        $finacial_year=finacial_year::orderBy("id","asc")
            ->get();

        return view('login',compact("finacial_year"));
    }

    function user_save(Request $request)
    {
//								$wu=new website_user();
//
//								$wu->website=$request->website;
//								$wu->user_name=$request->user_name;
//								$wu->email=$request->email;
//								$wu->password=$request->password;
//								if($wu->save())
//								{
//									if(empty($request->module))
//										{}else{
//											$totmodules=count($request->module);
//
//											for($i=0;$i<$totmodules;$i++)
//											{
//												$module_rights=new module_rights();
//												$module_rights->user_id=$wu->id;
//												$module_rights->module_id=$request->module[$i];
//												$module_rights->save();
//
//											}
//										}
//										return redirect()->route('master/user_list')->with('message','user add successfully');
//									}

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
            return redirect()->route('master/user_list')->with('message','user add successfully');

        }
    }

    function module_delete(Request $request)
    {
        $m=module::find($request->id);
        if($m->delete())
        {
            return redirect()->route('master/module_list')->with('message','module delete successfully');
        }
    }

    function module_update(Request $request)
    {

        $request->validate([

            'module_name' => 'required',
        ]);


        $m=module::find($request->id);
        $m->module_name=$request->module_name;
        $m->module_url=$request->module_url;
        $m->srno=$request->srno;
        $m->icon=$request->icon;

        if($m->save())
        {
            return redirect()->route('master/module_list')->with('message','module update successfully');
        }
    }

    function module_edit(Request $request)
    {
        $m=module::find($request->id);
        return view('master_panel/module_edit')->with(['data'=>$m]);
    }

    function module_save(Request $request)
    {
        $m=new module();
        $request->validate([
            'srno' => 'required',
            'module_name' => 'required',
        ]);

        $m->module_name=$request->module_name;
        $m->module_url=$request->module_url;
        $m->srno=$request->srno;
        $m->icon=$request->icon;
        if($m->save())
        {
            return redirect()->route('master/module_list')->with('message','module save successfully');
        }
    }
    function module_add(Request $request)
    {
        return view('master_panel/module_add');
    }
    function module_list(Request $request)
    {
        $data=module::orderBy('srno','asc')->get();
        return view('master_panel/module_list')->with(['data'=>$data]);
    }

    function user_add()
    {
//									$website=[''=>'select website']+website::orderBy('website_name','asc')->get()->pluck('website_name','id')->toArray();
//
//									$module_list=module::orderBy('module_name','asc')->get();

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

        return view("master_panel/user_add",compact('website','str'));

    }

    function user_list()
    {
        $data=website_user::select('website_user.*','website.website_name')
            ->leftJoin('website','website.id','website_user.website')
            ->whereNull('website_user.master_user')
            ->orderBy('website_user.id','desc')
            ->get();

        //$website=[''=>'select website']+website::orderBy('website_name','asc')->get()->pluck('website_name','id')->toArray();

        return view("master_panel/user_list")->with(['data'=>$data]);
    }

    function website_delete(Request $request)
    {
        $web=website::find($request->id);
        if($web->delete())
        {
            return redirect()->route('master/website-list')->with('message','website delete successfully');
        }
    }

    function website_update(Request $request)
    {
        $web=website::find($request->id);
        $web->customer_name=$request->customer_name;
        $web->primary_phone=$request->primary_phone;
        $web->secondary_phone=$request->secondary_phone;
        $web->primary_email=$request->primary_email;
        $web->secondary_email=$request->secondary_email;
        $web->owner_gst=$request->owner_gst;
        $web->owner_pan=$request->owner_pan;
        $web->website=$request->website;
        $web->software_title=$request->software_title;
        $web->owner_name=$request->owner_name;
        $web->owner_mobile=$request->owner_mobile;
        $web->alternate_no=$request->alternate_no;
        $web->owner_email=$request->owner_email;
        $web->work_email=$request->work_email;
        $web->description=$request->description;
        if($web->save())
        {
            return redirect()->route('master/website-list')->with('message','website update successfully');
        }
    }

    function website_edit(Request $request)
    {
        $web=website::find($request->id);
        return view('master_panel/website_edit')->with(['data'=>$web]);
    }
    function website_save(Request $request)
    {
        $web=new website();
        $web->customer_name=$request->customer_name;
        $web->primary_phone=$request->primary_phone;
        $web->secondary_phone=$request->secondary_phone;
        $web->primary_email=$request->primary_email;
        $web->secondary_email=$request->secondary_email;
        $web->owner_gst=$request->owner_gst;
        $web->owner_pan=$request->owner_pan;
        $web->website=$request->website;
        $web->software_title=$request->software_title;
        $web->owner_name=$request->owner_name;
        $web->owner_mobile=$request->owner_mobile;
        $web->alternate_no=$request->alternate_no;
        $web->owner_email=$request->owner_email;
        $web->work_email=$request->work_email;
        $web->description=$request->description;


        // $new_db = DB::statement('CREATE DATABASE `aqeel`');
        //       //DB::statement("CREATE USER 'aqeel_root'@'localhost' IDENTIFIED BY 'aqeelroot' ");
        //       //DB::statement("GRANT ALL PRIVILEGES ON *.* TO 'aqeel_root'@'localhost'");

        //       if ($new_db) {

        //           $config = Config::get('database.connections.main');

        //           $config['database'] = env('DB_DATABASE', 'aqeel');
        //           $config['username'] = env('DB_USERNAME', 'root');
        //           $config['password'] = env('DB_PASSWORD', '');
        //           $config['driver'] = env('driver', 'mysql');

        //           Config::set('database.connections.main', $config);
        //           Config::set('database.default', 'main');
        //           Artisan::call('migrate');
        //       }
        if($web->save())
        {
            return redirect()->route('master/website-list')->with('message','website save successfully');
        }

        //return $this->recurse_copy($source,$destination);
        //return recurse_copy();
    }

    function recurse_copy($src, $dst) {

        $dir = opendir($src);
        $result = ($dir === false ? false : true);

        if ($result !== false) {
            $result = @mkdir($dst);

            if ($result === true) {
                while(false !== ( $file = readdir($dir)) ) {
                    if (( $file != '.' ) && ( $file != '..' ) && $result) {
                        if ( is_dir($src . '/' . $file) ) {
                            return $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                        }     else {
                            $result = copy($src . '/' . $file,$dst . '/' . $file);
                        }
                    }
                }
                closedir($dir);
            }
        }

        //return $result;
    }

    function website_add()
    {
        return view('master_panel/website_add');
    }

    function index()
    {
        return view('master_panel/index');
    }
    function website_list()
    {
        $data=website::orderBy('website_name','asc')->get();
        return view('master_panel/website_list')->with(['data'=>$data]);
    }
}
