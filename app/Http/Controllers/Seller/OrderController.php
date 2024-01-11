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
        $orders = app('firebase.firestore')->database()->collection('order')->documents();
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
        $order = app('firebase.firestore')->database()->collection('order')->document($id)->snapshot();
        return view('seller.orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $orderRef = app('firebase.firestore')->database()->collection('order')->document($id);
        $orderRef->update([
            ['path' => 'status', 'value' => $request->status],
        ]);
        return redirect()->route('seller.orders.index')->with([
            'message' => 'Đã Thay đổi trạng thái !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
