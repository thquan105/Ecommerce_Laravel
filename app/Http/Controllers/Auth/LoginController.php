<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Kreait\Firebase\Contract\Auth as FirebaseAuth;
use Kreait\Firebase\Auth\SignInResult\SignInResult;
use Kreait\Firebase\Exception\FirebaseException;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class LoginController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  protected $auth;
  protected $redirectTo = RouteServiceProvider::HOME;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct(FirebaseAuth $auth)
  {
    $this->middleware('guest')->except('logout');
    $this->auth = $auth;
  }

  protected function login(Request $request)
  {
    $validator = Validator::make($request->all(), [
      'email' => ['required', 'string', 'email', 'max:255'],
      'password' => ['required', 'string', 'min:8'],
    ]);

    if ($validator->fails()) {
      return redirect('login')
        ->withErrors($validator)
        ->withInput();
    }
    try {
      $auth = app('firebase.auth');
      $signInResult = $auth->signInWithEmailAndPassword($request['email'], $request['password']);
      $user = new User($signInResult->data());

      //uid Session
      $loginuid = $signInResult->firebaseUserId();
      Session::put('uid', $loginuid);

      Auth::login($user);

      $userDetails = app('firebase.auth')->getUserByEmail($request['email']);
      // dd($userDetails);
      if (isset($userDetails->customClaims['admin']) && $userDetails->customClaims['admin'] ) {
        return redirect()->route('admin.dashboard');
      } 
      return redirect()->route('verify');
    } catch (FirebaseException $e) {
      throw ValidationException::withMessages([$this->username() => [trans('auth.failed')],]);
    }
  }
  public function username()
  {
    return 'email';
  }
  public function handleCallback(Request $request, $provider)
  {
    $socialTokenId = $request->input('social-login-tokenId', '');
    try {
      $verifiedIdToken = $this->auth->verifyIdToken($socialTokenId);
      // dd($verifiedIdToken);
      $detailRef = app('firebase.firestore')->database()->collection('user')->Document($verifiedIdToken->claims()->get('user_id'));
      if ($detailRef->snapshot()->data() == null) {
        $detailRef->set([
          'name' => $verifiedIdToken->claims()->get('name'),
          'email' => $verifiedIdToken->claims()->get('email'),
          'photo' => $verifiedIdToken->claims()->get('picture'),
          'seller' => false,
        ]);
      } 

      // Auth::login($verifiedIdToken->claims());
      Session::put('uid', $verifiedIdToken->claims()->get('user_id'));
      return redirect($this->redirectPath());
    } catch (\InvalidArgumentException $e) {
      return redirect()->route('login');
    } catch (InvalidToken $e) {
      return redirect()->route('login');
    }
  }
}
