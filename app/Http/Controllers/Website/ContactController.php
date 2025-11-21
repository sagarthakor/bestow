<?php

namespace App\Http\Controllers\Website;

use App\company;
use App\ContactMessage;
use App\Http\Controllers\Controller;
use App\Mail\ContactMail;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email',
                'mobile' => 'required|regex:/^[987][0-9]{9}$/|digits:10',
                'message' => 'required'
            ],[
                'name.required' => 'Name is Required',
                'email.required' => 'Email is Required',
                'email.email' => 'Invalid Email',
                'mobile.required' => 'Mobile Number is Required',
                'mobile.regex' => 'Invalid Mobile Number',
                'mobile.digits' => 'Mobile Number must be 10 digits',
                'message.required' => 'Message is Required'
            ]);

            try {

                //Mail::to(env('MAIL_FROM_ADDRESS'))->queue(new ContactMailManager($array));

                $contact = ContactMessage::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'mobile' => $request->mobile,
                    'message' => $request->message,
                ]);

               $company = company::select('email')->first();
               $companyEmail = isset($company) ? $company->email : 'saggyt19@gmail.com';
                // ✅ Send Email (Optional - Enable when .env mail configured)
                Mail::to($companyEmail)->send(new ContactMail($contact));

            } catch (\Exception $e) {
                error()->with(['error' => 'Something Went wrong']);
                return back();
            }
            return redirect()->back()->with(['success' => 'Contact Request has been Sent, We will contact you soon..!!']);
        }

        return view('website.contact');
    }

    public function grievance(Request $request)
    {
        if ($request->isMethod('post'))
        {
            $this->validate($request, [
                'name' => 'required',
                'email' => 'required|email',
                'telephone' => 'required|regex:/^[987][0-9]{9}$/|digits:10',
                'message' => 'required'
            ],[
                'name.required' => 'Name is Required',
                'email.required' => 'Email is Required',
                'email.email' => 'Invalid Email',
                'telephone.required' => 'Mobile Number is Required',
                'telephone.regex' => 'Invalid Mobile Number',
                'telephone.digits' => 'Mobile Number must be 10 digits',
                'message.required' => 'Message is Required'
            ]);

            try {

                //Mail::to(env('MAIL_FROM_ADDRESS'))->queue(new ContactMailManager($array));

                Inquiry::create([
                    'name' => $request->name,
                    'email' => $request->email,
                    'contact' => $request->telephone,
                    'subject' => $request->subject,
                    'message' => $request->message,
                    'type' => Inquiry::GRIEVANCE,
                ]);

            } catch (\Exception $e) {
                error()->with(['error' => 'Something Went wrong']);
                return back();
            }
            return redirect()->back()->with(['success' => 'Contact Request has been Sent, We will contact you soon..!!']);
        }

        return view('website.grievance');
    }

}
