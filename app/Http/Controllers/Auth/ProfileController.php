<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Contract\Auth;
use Kreait\Auth\Request\UpdateUser;
use Kreait\Firebase\Exception\FirebaseException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class ProfileController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  // public function __construct()
  // {
  //   $this->middleware('auth');
  // }

  public function index()
  {
    $uid = Session::get('uid');
    $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'))->snapshot();
    return view('auth.profile', compact('uid', 'user'));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    //
  }

  public function add_email(Request $request)
  {
    $auth = app('firebase.auth');
    // dd($auth->getUser(Session::get('uid')));
    try {
      $request->validate([
        'email' => 'required|string|email|max:255',
      ]);
      $properties = [
        'email' => $request->email
      ];
      $updatedUser = $auth->updateUser(Session::get('uid'), $properties);
      $user = app('firebase.firestore')->database()->collection('user')->Document(Session::get('uid'));
      $user->update([
        ['path' => 'email', 'value' => $request->email],
      ]);
      
      return redirect()->route('verify');
    } catch (\Exception $e) {
      Session::flash('error', $e->getMessage());
      return back()->withInput();
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    //
    $auth = app('firebase.auth');

    $user = $auth->getUser($id);
    try {
      $request->validate([
        'name' => 'required|min:3|max:255',
        'required', 'string', 'email', 'max:255',
        'phone' => 'required|numeric',
      ]);
      $properties = [
        'displayName' => $request->name,
        'email' => $request->email,
        'phoneNumber' => $request->phone,
      ];
      $updatedUser = $auth->updateUser($id, $properties);
      if ($user->email != $request->email) {
        $auth->updateUser($id, ['emailVerified' => false]);
      }
      $user = app('firebase.firestore')->database()->collection('user')->Document($id);
      $user->update([
        ['path' => 'name', 'value' => $request->name],
        ['path' => 'email', 'value' => $request->email],
        ['path' => 'mobileNo', 'value' => $request->phone],
        ['path' => 'postCode', 'value' => $request->postcode],
      ]);
      Session::flash('message', 'Profile Updated');
      return back()->withInput();
    } catch (\Exception $e) {
      Session::flash('error', $e->getMessage());
      return back()->withInput();
    }
  }

  public function changePassword()
  {
    try {
      $email = app('firebase.auth')->getUser(Session::get('uid'))->email;
      $link = app('firebase.auth')->sendPasswordResetLink($email);
      Session::flash('message', 'An change password email has been sent. Please check your inbox.');
      return back()->withInput();
    } catch (FirebaseException $e) {
      $error = str_replace('_', ' ', $e->getMessage());
      Session::flash('error', $error);
      return back()->withInput();
    }
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $updatedUser = app('firebase.auth')->disableUser($id);
    // $user = app('firebase.firestore')->database()->collection('user')->Document($id)->delete();
    Session::flush();
    return redirect('/login');
  }

  public function makeSeller()
  {
    $uid = Session::get('uid');
    $user = app('firebase.firestore')->database()->collection('user')->Document($uid);
    $user->update([
      ['path' => 'seller', 'value' => true],
    ]);
    return redirect()->route('home');
  }
}
