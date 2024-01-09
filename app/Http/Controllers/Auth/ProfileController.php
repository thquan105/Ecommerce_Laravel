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
    $uid = Session::get('uid');
    $user = app('firebase.firestore')->database()->collection('user')->document(session()->get('uid'))->snapshot();
    return view('auth.makeSeller', compact('uid', 'user'));
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
    $request->validate([
      'name' => 'required|min:3|max:255',
      'email' => 'required', 'string', 'email', 'max:255',
      'phone' => 'required|numeric',
    ]);
    $auth = app('firebase.auth');
    try {
      $user = $auth->getUser($id);
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
        ['path' => 'firstName', 'value' => $request->firstName],
        ['path' => 'lastName', 'value' => $request->lastName],
        ['path' => 'email', 'value' => $request->email],
        ['path' => 'address', 'value' => $request->address],
        ['path' => 'mobileNo', 'value' => $request->phone],
        ['path' => 'postCode', 'value' => $request->postcode],
      ]);
      if (isset($request->shopName) && $request->shopName != null) {
        $user->update([
          ['path' => 'shopName', 'value' => $request->shopName],
        ]);
      }
      Session::flash('message', 'Profile Updated');
      return back()->withInput();
    } catch (\Exception $e) {
      Session::flash('error', $e->getMessage());
      return back()->withInput();
    }
  }

  public function updateImg(Request $request, $id)
  {
    $request->validate([
      'path' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
    ]);
    try {
      $image = $request->file('path');

      $firebase_storage_path = 'userImg/';
      $name     = $id;
      $localfolder = public_path('firebase-temp-uploads') . '/';
      $extension = $image->getClientOriginalExtension();
      $file      = $name . '_' . time() . '.' . $extension;
      if ($image->move($localfolder, $file)) {
        $uploadedfile = fopen($localfolder . $file, 'r');
        app('firebase.storage')->getBucket()->upload($uploadedfile, ['name' => $firebase_storage_path . $file]);

        $storage_bucket = config('services.firebase.storage_bucket');
        $imageDownloadUrl = 'https://storage.googleapis.com/' . $storage_bucket . '/' . $firebase_storage_path . $file;
        // dd($imageDownloadUrl);
        $userRef = app('firebase.firestore')->database()->collection('user')->document($id);

        $URLphoto = $userRef->snapshot()->data()['photo'];

        $path = parse_url($URLphoto, PHP_URL_PATH);
        $filedel = basename($path);

        $imageDeleted = app('firebase.storage')->getBucket()->object("userImg/" . $filedel);
        if ($imageDeleted->exists()) {
          $imageDeleted->delete();
        }

        $userRef->update([
          ['path' => 'photo', 'value' => $imageDownloadUrl],
        ]);

        //will remove from local laravel folder  
        unlink($localfolder . $file);

        return redirect()->route('profile.index')->with([
          'success' => 'Profile Updated !',
          'alert-type' => 'success'
        ]);
      } else {
        return redirect()->route('profile.index')->with([
          'error' => 'Lỗi !',
        ]);
      }
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
    $updatedUser = app('firebase.auth')->deleteUser($id);
    $userRef = app('firebase.firestore')->database()->collection('user')->document($id);
    if (isset($userRef->snapshot()->data()['photo'])) {
      $URLphoto = $userRef->snapshot()->data()['photo'];

      $path = parse_url($URLphoto, PHP_URL_PATH);
      $filedel = basename($path);

      $imageDeleted = app('firebase.storage')->getBucket()->object("userImg/" . $filedel);
      if ($imageDeleted->exists()) {
        $imageDeleted->delete();
      }
    }

    $userRef->delete();
    Session::flush();
    return redirect('/login');
  }

  public function makeSeller(Request $request)
  {
    $request->validate([
      'name' => 'required|min:3|max:255',
      'shopName' => 'required|min:3|max:255',
      'lastName' => 'required|min:3|max:255',
      'firstName' => 'required|min:3|max:255',
      'email' => 'required', 'string', 'email', 'max:255',
      'phone' => 'required|numeric',
      'address' => 'required',
      'postcode' => 'required|numeric',
    ]);
    try {
      $auth = app('firebase.auth');
      $uid = Session::get('uid');
      $userRef = $auth->getUser($uid);
      $properties = [
        'displayName' => $request->name,
        'email' => $request->email,
        'phoneNumber' => $request->phone,
      ];
      $updatedUser = $auth->updateUser($uid, $properties);
      if ($userRef->email != $request->email) {
        $auth->updateUser($uid, ['emailVerified' => false]);
      }
      $user = app('firebase.firestore')->database()->collection('user')->Document($uid);
      $user->update([
        ['path' => 'name', 'value' => $request->name],
        ['path' => 'shopName', 'value' => $request->shopName],
        ['path' => 'firstName', 'value' => $request->firstName],
        ['path' => 'lastName', 'value' => $request->lastName],
        ['path' => 'email', 'value' => $request->email],
        ['path' => 'address', 'value' => $request->address],
        ['path' => 'mobileNo', 'value' => $request->phone],
        ['path' => 'postCode', 'value' => $request->postcode],
        ['path' => 'status', 'value' => false],
      ]);
      return redirect()->route('profile.index')->with([
        'success' => 'Đã đăng ký thành seller. Vui lòng chờ phản hồi từ Admin !',
      ]);
    } catch (FirebaseException $e) {
      $error = str_replace('_', ' ', $e->getMessage());
      Session::flash('error', $error);
      return back()->withInput();
    }
  }
}
