<?php namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Session;

class PaymentController extends Controller {

    public function getLogin() {
        if (session('can_pay') != true) {
            return view('frontend.auth.login2');
        }
        return redirect('/payment');
    }
            
    public function postLogin(Request $request) {
        if (Hash::check($request->input('password2'), Auth::user()->payment_password)) {
            session(['can_pay' => true]);
            return redirect()->back();
        }
        
        return view('frontend.auth.login2');
    }
}