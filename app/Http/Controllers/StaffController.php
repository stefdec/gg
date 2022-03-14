<?php

namespace App\Http\Controllers;

use App\User;
use App\Staff;
use Carbon\Carbon;
use App\Activities;
use App\StaffPayments;
use Carbon\CarbonPeriod;
use App\StaffCommissions;
use App\StaffCorrections;
use App\StaffFixedSalaries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $staffList = Staff::all()->where('user_id', Auth::user()->id)->where('active', 1)->sortByDesc('start_date')->sortBy('active');

        return view('/settings/staff/staff_list', [
            'staffs' => $staffList,
        ]);
    }

    public function staffList()
    {
        $staffList = Staff::all()->where('user_id', Auth::user()->id)->where('active', 1)->sortByDesc('start_date');

        return view('/staff/staffList', [
            'staffs' => $staffList,
        ]);
    }

    public function file($staffId, $period){
        $staff = Staff::findOrFail($staffId);

        //get fixed salary for this staff
        $fixedSalary = $this->getFixedSalary($staffId);


        //Corrections
        $totalCorrectionAmount = $this->getTotalCorrectionAmount($staffId);

        //function to get the total earned for the selected period
        $monthlyEarnings = $this->getMonthlyEarnings($staffId, $period);
        //Get the balance for the selected period
        $monthlyBalance = $this->getMonthlyBalance($staffId, $period);

        //function to get the total earned since the start_date
        $totalEarnings = $this->getTotalEarnings($staffId) + $totalCorrectionAmount;

        //Get the balance since start_date
        $totalBalance = $this->getTotalBalance($staffId);

        //function to get the payment list
        $staffPayments = $this->getMonthPayments($staffId, $period);
        //function to get the correction list
        $staffCorrections = $this->getMonthCorrections($staffId, $period);

        $activeDisciplines = Auth::user()->activeDisciplines;

        $monthsSinceStart = $this->listOfMonthsSinceStart($staffId, $staff->start_date);

        return view('staff/staffFile', [
            'staff' => $staff,
            'month' => $period,
            'fixedSalary' => $fixedSalary,
            'monthlyEarnings' => $monthlyEarnings . ' ' . Auth::user()->currency,
            'totalEarnings' => $totalEarnings . ' ' . Auth::user()->currency,
            'monthlyBalance' => $monthlyBalance . ' ' . Auth::user()->currency,
            'totalBalance' => $totalBalance . ' ' . Auth::user()->currency,
            'staffPayments' => $staffPayments,
            'staffCorrections' => $staffCorrections,
            'monthsSinceStart' => $monthsSinceStart,
            'activeDisciplines' => $activeDisciplines
        ]);
     }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $startDate = $request->startDate;
        $name = $request->name;
        $email = $request->staffEmail;
        $password = $request->staffPassword;
        $phone = $request->phone;
        $description = $request->description;
        $fixedSalary = $request->fixedSalary;

        //Commissions
        $commissionPackageId = $request->commissionPackageId;
        $commissionDisciplineId = $request->commissionDisciplineId;
        $commissionAmount = $request->commissionAmount;
        $commissionType = $request->commissionType;

        $newStaff = Staff::create([
            'user_id'=> Auth::user()->id,
            'name'=>$name,
            'email'=>$email,
            'password'=>$password,
            'phone'=>$phone,
            'description'=>$description,
            'start_date'=>$startDate,
        ]);

        StaffFixedSalaries::create([
            'staff_id'=>$newStaff->id,
            'amount'=>$this->returnZeroIfNull($fixedSalary),
        ]);

        //insert the commissions per package
        foreach($commissionAmount as $key => $commAmount ) {
            StaffCommissions::create([
                'staff_id'=>$newStaff->id,
                'package_id'=>$commissionPackageId[$key],
                'amount'=>$this->returnZeroIfNull($commissionAmount[$key]),
                'commission_type'=>$commissionType[$key],
                'discipline_id'=>$commissionDisciplineId[$key],
            ]);
        }

        return redirect()->route('settings.staff.show');
    }

    private function returnZeroIfNull($data){
        if(is_null($data)){
            $data = 0;
        }
        return $data;
    }

    public function getTotalBalance($staffId){
        $totalEarnings = $this->getTotalEarnings($staffId);
        $totalCorrections = $this->getTotalCorrectionAmount($staffId);
        $totalPayments = $this-> getTotalPayments($staffId);
        $totalBalance = $totalEarnings + $totalCorrections - $totalPayments;

        return $totalBalance;
    }

    public function getMonthlyBalance($staffId, $month){
        $monthlyEarnings = $this->getMonthlyEarnings($staffId, $month);
        $monthlyCorrections = $this->getMonthlyCorrectionAmount($staffId, $month);
        $monthlyPayments = $this-> getMonthPaymentsTotal($staffId, $month);
        $monthlyBalance = $monthlyEarnings + $monthlyCorrections - $monthlyPayments;

        return $monthlyBalance;
    }

    public function getMonthlyEarnings($staffId, $month){
        $startDate = Carbon::parse($month)->startOfMonth()->toDateString();
        $endDate = Carbon::parse($month)->endOfMonth()->toDateString();

        $fixedSalary = $this->getFixedSalary($staffId);

        $earnings = Activities::whereBetween(DB::raw('DATE(activity_date)'), [$startDate, $endDate])
                            ->where('staff_id', $staffId)
                            ->sum('staff_commission_sum');

        $corrections = $this->getMonthlyCorrectionAmount($staffId, $month);

        return $fixedSalary + $earnings + $corrections;
    }


    private function getTotalEarnings($staffId){
        return Activities::where('staff_id', $staffId)
                            ->sum('staff_commission_sum');
    }

    public function getMonthlyCorrectionAmount($staffId, $month){
        $startDate = Carbon::parse($month)->startOfMonth()->toDateString();
        $endDate = Carbon::parse($month)->endOfMonth()->toDateString();

        return StaffCorrections::whereBetween(DB::raw('DATE(correction_date)'), [$startDate, $endDate])
                                ->where('staff_id', $staffId)->sum('amount');
    }

    private function getTotalCorrectionAmount($staffId){
        return StaffCorrections::where('staff_id', $staffId)->sum('amount');
    }

    public function getFixedSalary($staffId){
        $salary = DB::table('staff_fixed_salaries')->where('staff_id', $staffId)->orderByDesc('created_at')->get();
        $mySalary = $salary->first()->amount;
        if (is_null($mySalary)) {
            $mySalary = 0;
        }
        return $mySalary;
     }

    private function getMonthCorrections($staffId, $month){
        $startDate = Carbon::parse($month)->startOfMonth()->toDateString();
        $endDate = Carbon::parse($month)->endOfMonth()->toDateString();

        return StaffCorrections::where('staff_id', $staffId)
                    ->whereBetween(DB::raw('DATE(correction_date)'), [$startDate, $endDate])
                    ->where('staff_id', $staffId)
                    ->get();
    }

    private function getMonthPayments($staffId, $month){
        $startDate = Carbon::parse($month)->startOfMonth()->toDateString();
        $endDate = Carbon::parse($month)->endOfMonth()->toDateString();

        return StaffPayments::whereBetween(DB::raw('DATE(payment_date)'), [$startDate, $endDate])
                                ->where('staff_id', $staffId)->get();
    }

    private function getMonthPaymentsTotal($staffId, $month){
        $startDate = Carbon::parse($month)->startOfMonth()->toDateString();
        $endDate = Carbon::parse($month)->endOfMonth()->toDateString();

        return StaffPayments::whereBetween(DB::raw('DATE(payment_date)'), [$startDate, $endDate])
                                ->where('staff_id', $staffId)->sum('amount');
    }

    private function getTotalPayments($staffId){
        return StaffPayments::where('staff_id', $staffId)->sum('amount');
    }

     //Get the commission amount for the staff per discipline
     public function getCommissions($discipline_id){
        return DB::table('staff_commissions')->where('discipline_id', $discipline_id)->where('amount', '>', 0)->get();
     }

     private function listOfMonthsSinceStart($staffId, $startDate){
        $period = CarbonPeriod::create('2019-01-01', Carbon::now()->format('Y-m-d'))->month();

        return collect($period)->map(function (Carbon $date) {
           return  $date->format('F Y');
        })->toArray();
     }

    /**
     * Display the specified resource.
     *
     * @param  \App\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function show(Staff $staff)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function edit(Staff $staff)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $staffId, $period)
    {
        $startDate = $request->startDate;
        $startDate = Carbon::parse($startDate)->format('Y-m-d');
        $name = $request->name;
        $email = $request->staffEmail;
        $password = $request->staffPassword;
        $phone = $request->phone;
        $description = $request->description;
        $fixedSalary = $request->salary;

        //Commissions
        $commissionPackageId = $request->commissionPackageId;
        $commissionDisciplineId = $request->commissionDisciplineId;
        $commissionAmount = $request->commissionAmount;
        $commissionType = $request->commissionType;

        Staff::where('id', $staffId)
                ->update([
                    'name'=>$name,
                    'email'=>$email,
                    'password'=>$password,
                    'phone'=>$phone,
                    'description'=>$description,
                    'start_date'=>$startDate,
                ]);

        StaffFixedSalaries::updateOrCreate(
            ['staff_id'=>$staffId],
            ['amount'=>$this->returnZeroIfNull($fixedSalary)]
        );

        // StaffFixedSalaries::where('staff_id', $staffId)
        //             ->update(['amount'=>$this->returnZeroIfNull($fixedSalary)]);

        //insert the commissions per package
        foreach($commissionAmount as $key => $commAmount ) {

            StaffCommissions::updateOrCreate(
                ['staff_id'=>$staffId, 'package_id' => $commissionPackageId[$key], 'discipline_id' => $commissionDisciplineId[$key]],
                ['amount' => $this->returnZeroIfNull($commissionAmount[$key]), 'commission_type' => $commissionType[$key]]
            );
        }

        return redirect()->route('staff.file', ['staffId'=>$staffId, 'period'=>$period]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Staff  $staff
     * @return \Illuminate\Http\Response
     */
    public function destroy(Staff $staff)
    {
        //
    }
}
