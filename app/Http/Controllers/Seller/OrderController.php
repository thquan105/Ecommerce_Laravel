<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $ordertRef = app('firebase.firestore')->database()->collection('order');
        $orders = $ordertRef->where('idShop', '=', session()->get('uid'))->documents();
        return view('seller.orders.index', compact('orders'));
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $orderRef = app('firebase.firestore')->database()->collection('order')->document($id);
        $orderItems = $orderRef->collection('option')->documents();
        $order = $orderRef->snapshot();
        return view('seller.orders.show', compact('order', 'orderItems'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $orderRef = app('firebase.firestore')->database()->collection('order')->document($id);
        $orderRef->update([
            ['path' => 'status', 'value' => 'đang vận chuyển'],
        ]);
        return redirect()->route('seller.orders.index')->with([
            'message' => 'Đã nhận đơn !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // $orderRef = app('firebase.firestore')->database()->collection('order')->document($id);
        // $orderRef->update([
        //     ['path' => 'status', 'value' => $request->status],
        // ]);
        // return redirect()->route('seller.orders.index')->with([
        //     'message' => 'Đã Thay đổi trạng thái !',
        //     'alert-type' => 'success'
        // ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $orderRef = app('firebase.firestore')->database()->collection('order')->document($id);
        $orderRef->update([
            ['path' => 'status', 'value' => 'đã hủy'],
        ]);
        return redirect()->route('seller.orders.index')->with([
            'message' => 'Đã hủy đơn !',
            'alert-type' => 'danger'
        ]);
    }
}
