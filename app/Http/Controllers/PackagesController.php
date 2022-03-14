<?php

namespace App\Http\Controllers;

use App\Packages;
use App\PackagesOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PackagesController extends Controller
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

    public static function getPackageOptions($packageId){
        $options = PackagesOptions::all()->where('package_id', $packageId)->where('visible', 1);
        return $options;
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
        $activityId = $request->activityId;
        $activityName = $request->activityName;

        if($request->commissionnable == 'on'){
            $isComm = 1;
        } else {
            $isComm = 0;
        }

        $newDiscipline = Packages::create([
            'discipline_id'=> $request->activityId,
            'name'=>$request->name,
            'commissionable'=>$isComm,
        ]);

        return redirect()->route('discipline.show', ['id'=>$activityId,'activityName'=>$activityName]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $packageName)
    {
        $options = PackagesOptions::all()->where('package_id', $id);
        return view('/settings/activities/options', [
            'options' => $options,
            'packageId' => $id,
            'packageName' => $packageName,
        ]);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
