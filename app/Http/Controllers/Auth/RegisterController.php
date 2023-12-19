<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Session;


class RegisterController extends Controller
{
   /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

   use RegistersUsers;
   protected $auth;

   /**
    * Where to redirect users after registration.
    *
    * @var string
    */
   protected $redirectTo = RouteServiceProvider::HOME;
   public function __construct(FirebaseAuth $auth)
   {
      $this->middleware('guest');
      $this->auth = $auth;
   }

   /**
    * Get a validator for an incoming registration request.
    *
    * @param  array  $data
    * @return \Illuminate\Contracts\Validation\Validator
    */
   protected function validator(array $data)
   {
      return Validator::make($data, [
         'name' => ['required', 'string', 'max:255'],
         'email' => ['required', 'string', 'email', 'max:255'],
         'phone' => ['required', 'min:10', 'numeric'],
         'password' => ['required', 'string', 'min:8', 'confirmed'],
      ]);
   }

   /**
    * Create a new user instance after a valid registration.
    *
    * @param  array  $data
    * @return \App\Models\User
    */
   protected function register(Request $request)
   {
      // dd($request);
      try {
         $this->validator($request->all())->validate();
         $userProperties = [
            'email' => $request->input('email'),
            'emailVerified' => false,
            'password' => $request->input('password'),
            'name' => $request->input('name')
         ];
         $createdUser = $this->auth->createUser($userProperties);
         $detailRef = app('firebase.firestore')->database()->collection('user')->Document($createdUser->uid);
      $detailRef->set([
        'name' => $request->input('name'),
        'email' => $request->input('email'),
        'password' => bcrypt($request->input('password')),
        'mobileNo' => $request->input('phone'),
        'seller' => false,
      ]);
         return redirect()->route('login');
      } catch (FirebaseException $e) {
         Session::flash('error', $e->getMessage());
         return back()->withInput();
      }
   }
}
