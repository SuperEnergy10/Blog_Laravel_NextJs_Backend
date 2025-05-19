<?php

namespace App\Http\Controllers;

use App\Mail\VerificationCodeMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AdminController extends Controller
{
    //

    public function AdminLogout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }


    public function AdminLogin(Request $request){
        $credentials = $request->only('email','password');
    
        if (Auth::attempt($credentials)) {
            $user = Auth::user();
    
            // Generate code
            $code = random_int(100000, 999999);
    
            // Save to session
            session([
                'verification_code' => $code,
                'user_id' => $user->id
            ]);
    
            // Send email
            Mail::to($user->email)->send(new VerificationCodeMail($code));
    
            // Logout until verified
            Auth::logout();
    
            return redirect()
                ->route('custom.verification.form')
                ->with('status', 'Verification code sent to your email');
        }
    
        // Wrong credentials
        return redirect()->back()->withErrors([
            'email' => 'Invalid Credentials Provided'
        ]);
    }



    public function ShowVerification(){
        return view('auth.verify');
    }


    public function VerificationVerify(Request $request)
{
    $request->validate([
        'code' => 'required|numeric'
    ]);

    if ($request->code == session('verification_code')) {
        Auth::loginUsingId(session('user_id'));

        session()->forget(['verification_code', 'user_id']);

        return redirect()->intended('/dashboard');
    }

    return back()->withErrors(['code' => 'Invalid Verification Code']);
}

    
}
