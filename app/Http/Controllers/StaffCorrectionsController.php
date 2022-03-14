<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\StaffCorrections;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StaffCorrectionsController extends Controller
{

    public function storeCorrection(Request $request)
    {
        $staffId = $request->staffId;
        $correctionDate = $request->correctionDate;
        $correctionAmount = $request->correctionAmount;
        $correctionType = $request->correctionType;
        $correctionDescription = $request->correctionDescription;

        if($correctionType == 1){
            //Deduction, convert amount to negative
            $correctionAmount = $correctionAmount * -1;
        }

        $newCorrection = StaffCorrections::create([
            'staff_id'=> $staffId,
            'correction_date'=>$correctionDate,
            'correction_type'=>$correctionType,
            'amount' => $correctionAmount,
            'description' => $correctionDescription,
        ]);

        return redirect()->route('staff.file', ['staffId' => $staffId, 'period' => Carbon::now()->format('Y-m-d')]);
    }

    public function deleteCorrection($correctionId, $staffId){
        StaffCorrections::where('id', $correctionId)->delete();

        return redirect()->route('staff.file', ['staffId' => $staffId, 'period' => Carbon::now()->format('Y-m-d')]);

    }



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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\StaffCorrections  $staffCorrections
     * @return \Illuminate\Http\Response
     */
    public function show(StaffCorrections $staffCorrections)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\StaffCorrections  $staffCorrections
     * @return \Illuminate\Http\Response
     */
    public function edit(StaffCorrections $staffCorrections)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\StaffCorrections  $staffCorrections
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, StaffCorrections $staffCorrections)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\StaffCorrections  $staffCorrections
     * @return \Illuminate\Http\Response
     */
    public function destroy(StaffCorrections $staffCorrections)
    {
        //
    }
}
