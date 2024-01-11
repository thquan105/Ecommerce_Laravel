<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ShipController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $shipes = app('firebase.firestore')->database()->collection('shippingUnit')->documents();
        return view('admin.shipUnits.index', compact('shipes'));
    }

    public function create()
    {
        return view('admin.shipUnits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([  
            'name' => 'required',
            'price' => 'required',
            'time' => 'required',
        ], [
            'name.required' => 'Tên dịch vụ ship là bắt buộc.',
            'price.required' => 'Giá ship là bắt buộc.',
            'time.required' => 'Thời gian ship là bắt buộc.',
        ]);
        $ship = app('firebase.firestore')->database()->collection('shippingUnit');
        $ship->add([
            'name' => $request->name,
            'price' => (int)$request->price,
            'deliveryTime' => $request->time,
        ]);
        return redirect()->route('admin.ship.index')->with([
            'message' => 'Thêm thành công !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function edit($Id)
    {
        $shipes = app('firebase.firestore')->database()->collection('shippingUnit')->document($Id)->snapshot();
        return view('admin.shipUnits.edit', compact('shipes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $Id)
    {
        $request->validate([  
            'name' => 'required',
            'price' => 'required',
            'time' => 'required',
        ], [
            'name.required' => 'Tên dịch vụ ship là bắt buộc.',
            'price.required' => 'Giá ship là bắt buộc.',
            'time.required' => 'Thời gian ship là bắt buộc.',
        ]);
        $shipes = app('firebase.firestore')->database()->collection('shippingUnit')->document($Id);
        $shipes->update([
                    ['path' => 'name', 'value' => $request->name],
                    ['path' => 'price', 'value' => (int)$request->price],
                    ['path' => 'deliveryTime', 'value' => $request->time],
                ]);
        return redirect()->route('admin.ship.index')->with([
            'message' => 'Cập nhật thành công !',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($Id)
    {
        $shipes = app('firebase.firestore')->database()->collection('shippingUnit')->document($Id)->delete();
        return redirect()->back()->with([
            'message' => 'Đã xóa !',
            'alert-type' => 'danger'
        ]);
    }
}
