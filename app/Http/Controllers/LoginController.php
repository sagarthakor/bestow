<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\master_website_user;
use App\master_module_rights;
use App\master_website_rights;
use App\website;
use Illuminate\Support\Facades\Hash;
use Session;
use App\website_user;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
class LoginController extends Controller
{
    //
    function password_send(Request $request)
    {
        $find=website_user::where('email',$request->email)->first();
        if(empty($find))
        {
            return back()->with('message','Email id not found');
        }else{

            $hashed_random_password =Str::random(8);
            $data = array('password'=>$hashed_random_password);
            $to=$request->email;
            Mail::send(['text'=>'emails.password_generate'], $data, function($message) use($to) {
                $message->to($to, 'Tutorials Point')->subject
                ('New password for login in erp');
                $message->from('erp@nowtowow.co.in','Password');
            });

            $find->password=$hashed_random_password;


            if($find->save())
            {
                return redirect()->route("user/login")->with('status','New password is send to your registered mail id');

            }
            //redirect()->back()->getTargetUrl();
           //return view("login")->with('message','new password is send to your registered mail id');
        }
    }
    function forgot_password()
    {
        return view("forgotten_password");
    }
    function master_logout(Request $request)
    {
    	Session::forget('master_user_id');
		Session::forget('master_user_name');
		Session::forget('master_module');
		Session::forget('master_website');

		if(!Session::has('master_user_id'))
		{
		      return view('master_panel/master_login');
		}
    }
    function master_login(Request $request)
    {
    	return view('master_panel/master_login');
    }
    function login_master(Request $request)
    {
    	$check=master_website_user::where('user_name',$request->username)
    	->where('password',$request->password)
    	->first();

    		if(empty($check))
			{
				return back()->with('message','username or password invalid');
			}else{

				$rights=master_module_rights::select('master_module_rights.*','master_module.module_name','master_module.module_url','master_module.id as mid')
				->leftJoin('master_module','master_module.id','master_module_rights.module_id')
				->where('master_module_rights.user_id',$check->id)
				->get();

				$websiterights=master_website_rights::select('master_website_rights.*','website.website_name','website.id as webid')
				->leftJoin('website','website.id','master_website_rights.website_id')
				->where('master_website_rights.user_id',$check->id)
				->get();


				Session::put('master_user_id',$check->id);
				Session::put('master_user_name',$check->user_name);
				Session::put('master_module',$rights);
				Session::put('master_website',$websiterights);

				return view('master_panel/index');
			}
    }
}
