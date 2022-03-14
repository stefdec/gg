<?php

namespace App\Http\Controllers;

use App\PackagesOptions;
use Illuminate\Http\Request;

class PackagesOptionsController extends Controller
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
        $packageId = $request->packageId;
        $packageName = $request->packageName;
        $name = $request->name;
        $amount = $request->price;
        $variable = $request->variable;
        $amount_per = $request->amount_per;
        $p_condition = $request->p_condition;
        $nb_condition = $request->nb_condition;
        $data_condition = $request->data_condition;

        if(!isset($variable)){
            $p_condition = 'fixed';
            $nb_condition = 0;
        }

        //dd($packageName);

        $newOption = PackagesOptions::create([
            'package_id'=> $packageId,
            'name'=>$name,
            'amount'=>$amount,
            'amount_per'=>$amount_per,
            'p_condition'=>$p_condition,
            'nb_condition'=>$nb_condition,
            'data_condition'=>$data_condition
        ]);

        return redirect()->route('package.show', ['id'=>$packageId,'packageName'=>$packageName]);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\PackagesOptions  $packagesOptions
     * @return \Illuminate\Http\Response
     */
    public function show(PackagesOptions $packagesOptions)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PackagesOptions  $packagesOptions
     * @return \Illuminate\Http\Response
     */
    public function edit(PackagesOptions $packagesOptions)
    {
        //
    }

    public function invisible(Request $request)
    {
        $optionId = $request->optionId;
        $packageId = $request->packageId;
        $packageName = $request->packageName;

        //PackagesOptions::where('id', $optionId)->update(['visible' => 1]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PackagesOptions  $packagesOptions
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PackagesOptions $packagesOptions)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\PackagesOptions  $packagesOptions
     * @return \Illuminate\Http\Response
     */
    public function destroy(PackagesOptions $packagesOptions)
    {
        //
    }
}
