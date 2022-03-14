<?php

namespace App\Http\Controllers;

use App\Activities;
use App\CustomersPayments;
use Illuminate\Http\Request;
use App\CustomersCorrections;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ActivitiesController extends Controller
{

    public function customerActivitiesList($disciplineId, $customerId)
    {
        //Get all this ids for this file (master and sub files)
        $fileIds = app('App\Http\Controllers\customerController')->getCustomerFileIds($customerId);

        return Activities::join('packages', 'activities.package_id', '=', 'packages.id')
                            ->join('staff', 'activities.staff_id', '=', 'staff.id')
                            ->join('customers', 'activities.customer_id', '=', 'customers.id')
                            ->where('activities.discipline_id', '=', $disciplineId)
                            ->whereIn('activities.customer_id', $fileIds)
                            ->orderByDesc('activities.activity_date')
                            ->select('activities.id as activityId',
                                    'activities.activity_date as activityDate',
                                    'customers.first_name as customerName',
                                    'staff.name as staffName',
                                    'packages.name as packageName',
                                    'activities.quantity as activityQty',
                                    'activities.total_amount as totalAmount')
                            ->get();
    }

    public function staffMonthlyActivitiesList($disciplineId, $staffId, $month)
    {
        $startDate = Carbon::parse($month)->firstOfMonth()->format('Y-m-d');
        $endDate = Carbon::parse($month)->endOfMonth()->format('Y-m-d');

        return Activities::join('packages', 'activities.package_id', '=', 'packages.id')
                            ->join('customers', 'activities.customer_id', '=', 'customers.id')
                            ->where('activities.discipline_id', '=', $disciplineId)
                            ->where('activities.staff_id', $staffId)
                            ->whereBetween(DB::raw('DATE(activity_date)'), [$startDate, $endDate])
                            ->orderByDesc('activities.activity_date')
                            ->select('activities.id as activityId',
                                    'activities.activity_date as activityDate',
                                    'activities.customer_id as customerId',
                                    'customers.first_name as customerName',
                                    'packages.name as packageName',
                                    'activities.quantity as activityQty',
                                    'activities.staff_commission_sum as totalAmount')
                            ->get();
    }

    public function staffMonthlyActivitysummary($staffId, $month)
    {
        $startDate = Carbon::parse($month)->firstOfMonth()->format('Y-m-d');
        $endDate = Carbon::parse($month)->endOfMonth()->format('Y-m-d');

        return Activities::join('packages', 'activities.package_id', '=', 'packages.id')
                            ->join('customers', 'activities.customer_id', '=', 'customers.id')
                            ->whereBetween(DB::raw('DATE(activity_date)'), [$startDate, $endDate])
                            ->where('activities.staff_id', $staffId)
                            ->orderByDesc('activities.activity_date')
                            ->select('activities.id as activityId',
                                    'activities.activity_date as activityDate',
                                    'activities.customer_id as customerId',
                                    'customers.first_name as customerName',
                                    'packages.name as packageName',
                                    'activities.quantity as activityQty',
                                    'activities.staff_commission_sum as totalAmount')
                            ->get();
    }

    public function customerActivitiesSummary($customerId)
    {
        //Get all this ids for this file (master and sub files)
        $fileIds = app('App\Http\Controllers\customerController')->getCustomerFileIds($customerId);

        return Activities::join('packages', 'activities.package_id', '=', 'packages.id')
                            ->join('staff', 'activities.staff_id', '=', 'staff.id')
                            ->join('customers', 'activities.customer_id', '=', 'customers.id')
                            ->whereIn('activities.customer_id', $fileIds)
                            ->orderByDesc('activities.activity_date')
                            ->orderBy('activities.customer_id')
                            ->select('activities.activity_date as activityDate', 'customers.first_name as customerName', 'staff.name as staffName', 'packages.name as packageName', 'activities.price as activityPrice', 'activities.quantity as activityQty', 'activities.total_amount as totalAmount')
                            ->get();
    }

    public function deleteActivity($activityId, $customerId){
        Activities::where('id', $activityId)->delete();

        return redirect()->route('customer.show', ['id' => $customerId]);
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
        $isMaster = $request->isMaster;
        $customerId = $request->customerId;
        $disciplineId = $request->disciplineId;
        $activityDate = $request->activityDate;
        $activityCustomer = $request->activityCustomer;
        $activityStaff = $request->activityStaff;
        $activityPackage = $request->activityPackage;
        $activityQuantity = $request->activityQuantity;

        if($isMaster != 1){
            $activityCustomer = $customerId;
        }

        //get the type and amount of commission for the selected staff for this package
        $staffCommissions = $this->getStaffCommission($activityStaff, $activityPackage);

        $staff_commission_amount = $staffCommissions->first()->amount;
        $staff_commission_type = $staffCommissions->first()->commission_type;

         //insert the activity in the table before calculating price variations and satff commissions
         $newActivity = Activities::create([
            'customer_id'=> $activityCustomer,
            'activity_date'=>$activityDate,
            'staff_id'=>$activityStaff,
            'staff_commission_amount'=>$staff_commission_amount,
            'staff_commission_type'=>$staff_commission_type,
            'staff_commission_sum'=>0,
            'package_id'=>$activityPackage,
            'discipline_id'=>$disciplineId,
            'quantity'=>$activityQuantity,
            'price'=>0,
            'total_amount'=>0,
        ]);

        //Get the total quantity of activities for this discipline for this customer
        $qtyDone = $this->getQtyDone($activityCustomer, $disciplineId);
        //**********************ADD OPTION TO CALCULATE ON PACKAGE */

        //Get price options for the selected package
        $options = $this->getPriceOptions($activityPackage);

        //Calculation of the actual option price depending if it's fixed or variable
        //in that second case we need to check the conditions and choose the proper one
        if ($options->count() == 1 && $options->first()->p_condition == 'fixed'){
            //Fixed price option
            $optionPrice = $options->first()->amount;

        } elseif ($options->count() > 1){
            //Variable price option
            $optionPrice = $this->getVariableOptionPrice($options, $qtyDone);
        }else {
            $optionPrice = 0;
        }

        //Update of prices and commissions
        $totalAmount = $optionPrice;

        //Create the coeficient to multiply to get the staff commission
        $staffSumCommissionquery = '';

        if ($staff_commission_type == 1){
            //percentage
            $multiplicator = $staff_commission_amount / 100;
            $staffSumCommissionquery = 'price * quantity * ' . $multiplicator ;
        } elseif ($staff_commission_type == 2){
            //fixed
            $staffSumCommissionquery = 'quantity * staff_commission_amount';
        }


        //DB::statement("UPDATE favorite_contents, contents SET favorite_contents.type = contents.type where favorite_contents.content_id = contents.id");
        DB::table('activities')
              ->where('customer_id', $activityCustomer)
              ->where('package_id', $activityPackage)
              ->update(['price' => $optionPrice, 'total_amount' => DB::raw('price * quantity'), 'staff_commission_sum' => DB::raw($staffSumCommissionquery)]);

        return redirect()->route('customer.show', ['id' => $customerId]);
    }



    private function getPriceOptions($packageId){
        return DB::table('packages_options')->where('package_id', $packageId)->select('*')->get();
    }

    private function getQtyDone($customerId, $disciplineId){
        return DB::table('activities')->where('customer_id', $customerId)->where('discipline_id', $disciplineId)->sum('quantity');
    }

    private function getStaffCommission($staffId, $packageId){
        return DB::table('staff_commissions')->where('staff_id', $staffId)->where('package_id', $packageId)->select('amount', 'commission_type')->get();
    }

    public function getStaffCommAmount($staffId, $packageId){
        $staffCommissions = $this->getStaffCommission($staffId, $packageId);
        return $staffCommissions->first()->amount;
    }

    public function getStaffCommType($staffId, $packageId){
        $staffCommissions = $this->getStaffCommission($staffId, $packageId);
        return $staffCommissions->first()->commission_type;
    }

    private function getVariableOptionPrice($options, $qty){
        $conditionFound = FALSE;
        $actualOptionPrice = 0;
        foreach($options as $option){
            $optionPrice = $option->amount;
            $optionCondition = $option->p_condition;
            $optionNbCondition = $option->nb_condition;

            switch ($optionCondition){
                case "equals":
                //equals
                  if($qty == $optionNbCondition && !$conditionFound){
                    $actualOptionPrice = $optionPrice;
                    $conditionFound = TRUE;
                  }
                  break;

                case "If inferior to":
                //inferior to
                  if($qty < $optionNbCondition && !$conditionFound){
                    $actualOptionPrice = $optionPrice;
                    $conditionFound = TRUE;
                  }
                  break;

                case "If superior to":
                //superior to
                if($qty > $optionNbCondition && !$conditionFound){
                //   $actualOptionId = $optionId;
                  $actualOptionPrice = $optionPrice;
                  $conditionFound = TRUE;
                }
                  break;
              }
        }
        return $actualOptionPrice;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Activities  $activities
     * @return \Illuminate\Http\Response
     */
    public function show(Activities $activities)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Activities  $activities
     * @return \Illuminate\Http\Response
     */
    public function edit(Activities $activities)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Activities  $activities
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Activities $activities)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Activities  $activities
     * @return \Illuminate\Http\Response
     */
    public function destroy(Activities $activities)
    {
        //
    }
}
