<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\SellerMail;
use App\Mail\DenySellerMail;

class SellerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sellers = app('firebase.firestore')->database()->collection('user')
            ->where('seller', '=', true)
            ->documents();
        return view('admin.seller.index', compact('sellers'));
    }

    public function show()
    {
        $sellers = app('firebase.firestore')->database()->collection('user')->where('status', '=', false)->documents();
        return view('admin.seller.approve', compact('sellers'));
    }

    public function disable(string $id)
    {
        $auth = app('firebase.auth');
        $auth->updateUser($id, ['disabled' => true]);
        return redirect()->route('admin.sellers.index')->with([
            'message' => 'Đã Khóa !',
            'alert-type' => 'danger'
        ]);
    }

    public function enable(string $id)
    {
        $auth = app('firebase.auth');
        $auth->updateUser($id, ['disabled' => false]);
        return redirect()->route('admin.sellers.index')->with([
            'message' => 'Đã Mở Khóa !',
            'alert-type' => 'success'
        ]);
    }

    public function deny(string $id)
    {
        $user = app('firebase.firestore')->database()->collection('user')->Document($id);
        $user->update([
            ['path' => 'status', 'value' => true],
            ['path' => 'seller', 'value' => false],
        ]);
        Mail::to($user->snapshot()->data()['email'])->send(new DenySellerMail());
        return redirect()->route('admin.sellers.show')->with([
            'message' => 'Đã Từ chối !',
            'alert-type' => 'danger'
        ]);
    }

    public function accept(string $id)
    {
        $user = app('firebase.firestore')->database()->collection('user')->Document($id);
        $user->update([
            ['path' => 'status', 'value' => true],
            ['path' => 'seller', 'value' => true],
        ]);
        Mail::to($user->snapshot()->data()['email'])->send(new SellerMail());
        return redirect()->route('admin.sellers.show')->with([
            'message' => 'Đã Duyệt !',
            'alert-type' => 'success'
        ]);
    }
}
