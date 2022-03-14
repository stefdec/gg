<?php

namespace App\Http\Controllers;

use App\CustomersCorrections;
use Illuminate\Http\Request;

class CustomersCorrectionsController extends Controller
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

    public function customerCorrectionsList($customerId)
    {
        //Get all this ids for this file (master and sub files)
        $fileIds = app('App\Http\Controllers\customerController')->getCustomerFileIds($customerId);

        return CustomersCorrections::join('customers', 'customers_corrections.customer_id', '=', 'customers.id')
                    ->whereIn('customers_corrections.customer_id', $fileIds)
                    ->orderByDesc('customers_corrections.correction_date')
                    ->select('customers_corrections.id AS correctionId',
                            'customers_corrections.correction_date AS correctionDate',
                            'customers.first_name AS customerName',
                            'customers_corrections.correction_type AS correctionType',
                            'customers_corrections.amount AS correctionAmount',
                            'customers_corrections.description AS correctionDescription')
                    ->get();
    }

    public function storeCorrection(Request $request)
    {
        $isMaster = $request->isMaster;
        $customerId = $request->customerId;
        $correctionDate = $request->correctionDate;
        $correctionCustomer = $request->correctionCustomer;
        $correctionAmount = $request->correctionAmount;
        $correctionType = $request->correctionType;
        $correctionDescription = $request->correctionDescription;

        if($isMaster != 1){
            $correctionCustomer = $customerId;
        }

        if($correctionType == 1){
            //Deduction, convert amount to negative
            $correctionAmount = $correctionAmount * -1;
        }

        $newCorrection = CustomersCorrections::create([
            'customer_id'=> $correctionCustomer,
            'correction_date'=>$correctionDate,
            'correction_type'=>$correctionType,
            'amount' => $correctionAmount,
            'description' => $correctionDescription,
        ]);

        return redirect()->route('customer.show', ['id' => $customerId]);
    }

    public function deleteCorrection($correctionId, $customerId){
        CustomersCorrections::where('id', $correctionId)->delete();

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
     * @param  \App\CustomersCorrections  $customersCorrections
     * @return \Illuminate\Http\Response
     */
    public function show(CustomersCorrections $customersCorrections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CustomersCorrections  $customersCorrections
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomersCorrections $customersCorrections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CustomersCorrections  $customersCorrections
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomersCorrections $customersCorrections)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CustomersCorrections  $customersCorrections
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomersCorrections $customersCorrections)
    {
        //
    }
}
