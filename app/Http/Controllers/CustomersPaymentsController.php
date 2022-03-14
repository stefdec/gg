<?php

namespace App\Http\Controllers;

use App\CustomersPayments;
use Illuminate\Http\Request;

class CustomersPaymentsController extends Controller
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

    public function customerPaymentsList($customerId)
    {
        //Get all this ids for this file (master and sub files)
        $fileIds = app('App\Http\Controllers\customerController')->getCustomerFileIds($customerId);

        return CustomersPayments::join('customers', 'customers_payments.customer_id', '=', 'customers.id')
                    ->join('payment_types', 'payment_types.id', '=', 'customers_payments.payment_type_id')
                    ->whereIn('customers_payments.customer_id', $fileIds)
                    ->orderByDesc('customers_payments.payment_date')
                    ->select('customers_payments.id as paymentId',
                                'customers_payments.payment_date AS paymentDate',
                                'customers.first_name AS customerName',
                                'payment_types.name AS paymentType',
                                'customers_payments.amount AS paymentAmount',
                                'customers_payments.description AS paymentDescription')
                    ->get();
    }

    public function storePayment(Request $request)
    {
        $isMaster = $request->isMaster;
        $customerId = $request->customerId;
        $paymentDate = $request->paymentDate;
        $paymentCustomer = $request->paymentCustomer;
        $paymentAmount = $request->paymentAmount;
        $paymentType = $request->paymentType;
        $paymentDescription = $request->paymentDescription;

        if($isMaster != 1){
            $correctionCustomer = $customerId;
        }

        $newPayment = CustomersPayments::create([
            'customer_id'=> $paymentCustomer,
            'payment_type_id'=>$paymentType,
            'payment_date'=>$paymentDate,
            'amount' => $paymentAmount,
            'description' => $paymentDescription,
        ]);

        return redirect()->route('customer.show', ['id' => $customerId]);
    }

    public function deletePayment($paymentId, $customerId){
        CustomersPayments::where('id', $paymentId)->delete();

        return redirect()->route('customer.show', ['id' => $customerId]);
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

    /**
     * Display the specified resource.
     *
     * @param  \App\CustomersPayments  $customersPayments
     * @return \Illuminate\Http\Response
     */
    public function show(CustomersPayments $customersPayments)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomersPayments  $customersPayments
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomersPayments $customersPayments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomersPayments  $customersPayments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomersPayments $customersPayments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomersPayments  $customersPayments
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomersPayments $customersPayments)
    {
        //
    }
}
