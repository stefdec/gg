<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\StaffPayments;
use Illuminate\Http\Request;

class StaffPaymentsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $staffId = $request->staffId;
        $paymentDate = $request->paymentDate;
        $paymentAmount = $request->paymentAmount;
        $paymentDescription = $request->paymentDescription;

        $newPayment = StaffPayments::create([
            'staff_id'=> $staffId,
            'payment_date'=>$paymentDate,
            'amount' => $paymentAmount,
            'description' => $paymentDescription,
        ]);

        return redirect()->route('staff.file', ['staffId' => $staffId, 'period'=> Carbon::today()->startOfMonth()->toDateString()]);
    }

    public function deletePayment($paymentId, $staffId){
        StaffPayments::where('id', $paymentId)->delete();

        return redirect()->route('staff.file', ['staffId' => $staffId, 'period'=> Carbon::today()->startOfMonth()->toDateString()]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StaffPayments  $staffPayments
     * @return \Illuminate\Http\Response
     */
    public function show(StaffPayments $staffPayments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StaffPayments  $staffPayments
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffPayments $staffPayments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StaffPayments  $staffPayments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaffPayments $staffPayments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StaffPayments  $staffPayments
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffPayments $staffPayments)
    {
        //
    }
}
