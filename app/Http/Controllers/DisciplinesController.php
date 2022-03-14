<?php

namespace App\Http\Controllers;

use App\Packages;
use App\Disciplines;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisciplinesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {
        $disciplines = Disciplines::all()->where('user_id', Auth::user()->id);
        return view('/settings/activities/disciplines', [
            'disciplines' => $disciplines,
        ]);
    }

    public static function getDisciplinePackages($disciplineId){
        $packages = Packages::all()->where('discipline_id', $disciplineId)->where('visible', 1);
        return $packages;
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
        if($request->commissionnable == 'on'){
            $isComm = 1;
        } else {
            $isComm = 0;
        }

        if($request->commissionnableTo == 'on'){
            $isCommTo = 1;
        } else {
            $isCommTo = 0;
        }

        $newDiscipline = Disciplines::create([
            'user_id'=> Auth::user()->id,
            'name'=>$request->name,
            'commissionable'=>$isComm,
            'commissionableTo'=>$isCommTo,
        ]);

        return redirect()->route('settings.disciplines');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $activityName)
    {
        $packages = Packages::all()->where('discipline_id', $id);
        return view('/settings/activities/packages', [
            'packages' => $packages,
            'activityId' => $id,
            'activityName' => $activityName,
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
