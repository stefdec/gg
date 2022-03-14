<?php

namespace App\Http\Controllers;

use App\Staff;
use App\Customers;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;

class customerController extends Controller
{
    public function index() {
        echo 'index';
     }

     public function openList(){
        $customers = Customers::all()->where('user_id', Auth::user()->id)->where('open', 1)->where('master_id', 0);
        return view('/customers/list', [
            'customers' => $customers,
        ]);
     }

     public function file($id){
        $customer = Customers::findOrFail($id);

        //function to return sub files
        $isMaster = 0;
        $subFiles = $this->getSubFiles($id);
        if($subFiles->count() > 0){
            $isMaster = 1;
        }

        //function to ge the total paid called from activitiescontroller
        $totalPaid = $this->getTotalPaid($id);
        //function to ge the total of corrections from CustomersCorrectionsController
        $totalCorrections = $this->getTotalCorrections($id);

        //function to get the total called from activitiescontroller
        $totalSpent = $this->getTotalSpent($id);
        $totalSpent += $totalCorrections;

        $balance = $totalSpent - $totalPaid;

        $activeDisciplines = Auth::user()->activeDisciplines;

        return view('customers/file', [
            'customer' => $customer,
            'isMaster' => $isMaster,
            'subFiles' => $subFiles,
            'totalSpent' => $totalSpent . ' ' . Auth::user()->currency,
            'totalPaid' => $totalPaid . ' ' . Auth::user()->currency,
            'balance' => $balance . ' ' . Auth::user()->currency,
            'activeDisciplines' => $activeDisciplines
        ]);
     }

     public function create() {
        echo 'create';
     }
     public function store(Request $request) {
        $last_name = $request->lastname;
        $first_name = $request->firstname;
        $email = $request->email;
        $phone = $request->phone;
        $country = $request->country;
        $departure_date = $request->departuredate;
        $emmergency_contact = $request->emergencyContact;
        $notes = $request->notes;
        $imgDatas = $request->imgData;

        // $request->validate([
        //     'last_name' => ['required', 'max:255', 'min:1'],
        // ]);

        if(!isset($emmergency_contact)){
            $emmergency_contact = 'none';
        }

        $newCustomer = Customers::create([
            'user_id'=> Auth::user()->id,
            'last_name'=>$last_name,
            'first_name'=>$first_name,
            'email'=>$email,
            'phone'=>$phone,
            'country'=>$country,
            'departure_date'=>'2021-12-12',
            'emmergency_contact'=>$emmergency_contact,
            'notes'=>$notes
        ]);

         //Saving the signature as a PNG on the server
         //Only of the signature exists
         if(! is_null($imgDatas)){
            $imgName = '/images/tmp/' . $newCustomer->id . '-' . $newCustomer->last_name . '.png';

            $imgData = str_replace(' ','+',$imgDatas);
            $imgData =  substr($imgData,strpos($imgData,",")+1);
            $imgData = base64_decode($imgData);

            // Path where the image is going to be saved
            $filePath = $_SERVER['DOCUMENT_ROOT']. $imgName;

            // Write $imgData into the image file
            $file = fopen($filePath, 'w');
            fwrite($file, $imgData);
            fclose($file);
         }
        //dd($name);

        return redirect()->route('customersOpen');
     }

     public function update(Request $request) {
        $customerId = $request->customerId;
        $lastName = $request->lastname;
        $firstName = $request->firstname;
        $email = $request->email;
        $phone = $request->phone;
        $country = $request->country;
        $departureDate = $request->departuredate;
        $notes = $request->notes;

        Customers::where('id', $customerId)
        ->update([
                    'last_name' => $lastName,
                    'first_name'=>$firstName,
                    'email'=>$email,
                    'phone'=>$phone,
                    'country'=>$country,
                    'departure_date'=>Carbon::parse($departureDate)->format('Y-m-d'),
                    'notes'=>$notes
                ]);

        return redirect()->route('customer.show', ['id' => $customerId]);

     }

     public function show($id) {
        echo 'show';
     }
     public function edit($id) {
        echo 'edit';
     }

     public function destroy($id) {
        echo 'destroy';
     }

     public function getCustomerFileIds($customerId){
         //This function returns an array of customers_id-> One if unique, multiple is it's a master file
        $idList = DB::table('customers')->where('master_id', $customerId)->select('id')->get();
        $ids = array();
        $ids[] = $customerId;

        foreach($idList as $id){
            $ids[] = $id->id;
        }
        return $ids;
     }

     public function getBalance($customerId){
        $balance = $this->getTotalSpent($customerId) + $this->getTotalCorrections($customerId) - $this->getTotalPaid($customerId);

        return $balance;
     }

     public function getTotalSpent($customerId){
        $fileIds = $this->getCustomerFileIds($customerId);

        $spent = DB::table('activities')
                ->whereIn('customer_id', $fileIds)
                ->sum('total_amount');

        return $spent;
     }

     public function getTotalPaid($customerId){
        $fileIds = $this->getCustomerFileIds($customerId);

        $paid = DB::table('customers_payments')
                ->whereIn('customer_id', $fileIds)
                ->sum('amount');

        return $paid;
     }

     private function getTotalCorrections($customerId){
        $fileIds = $this->getCustomerFileIds($customerId);

        $correctionsTotal = DB::table('customers_corrections')
                ->whereIn('customer_id', $fileIds)
                ->sum('amount');

        return $correctionsTotal;
     }

     private function getSubFiles($customerId){
        $subfiles = Customers::all()->where('master_id', $customerId);
        return $subfiles;
     }

     public function getCommissionablestaff($disciplineId){
        return Staff::join('staff_commissions', 'staff_commissions.staff_id', '=', 'staff.id')
                            ->where('staff_commissions.discipline_id', '=', $disciplineId)
                            ->distinct()
                            ->select('staff.name as staffName', 'staff.id as staffId')
                            ->get();
     }

     public function getCommissionablePackages($disciplineId){
        return DB::table('packages')->where('discipline_id', $disciplineId)->where('commissionable', 1)->where('visible', 1)->get();
     }

     public function generatePDF($customerId, $customerLastName, $customerFirstName)
    {

        $data = [
            'customerId' => $customerId,
            'customerLastName' => $customerLastName,
            'customerFirstName' => $customerFirstName
        ];

        $pdf = PDF::loadView('/customers/invoices/invoice', $data);

        return $pdf->stream('invoice.pdf');
    }
}
