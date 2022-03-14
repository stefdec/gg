@extends('layouts.app', [
    'parentSection' => 'tables',
    'elementName' => 'datatables'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.customersFile', ['customerId' => $customer->id, 'customerLastName'=>$customer->last_name, 'customerFirstName'=>$customer->first_name])
            @slot('title')
                {{ __('Customers') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('page.index', 'datatables') }}">{{ __('Tables') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Datatables') }}</li>
        @endcomponent
    @endcomponent

    @inject('customerController', 'App\Http\Controllers\customerController')
    @inject('activitiesController', 'App\Http\Controllers\ActivitiesController')
    @inject('correctionController', 'App\Http\Controllers\CustomersCorrectionsController')
    @inject('paymentController', 'App\Http\Controllers\CustomersPaymentsController')

    {{-- <x-customer-update-form
        customerId='{{ $customer->id }}'
        lastName='{{ $customer->last_name }}'
        firstName='{{ $customer->first_name }}'
        email='{{ $customer->email }}'
        phone ='{{ $customer->phone }}'
        country='{{ $customer->country }}'
        notes='{{ $customer->notes }}'></x-customer-update-form> --}}

<x-customer-update-form customerId='{{ $customer->id }}'
    departureDate='{{ $customer->departure_date }}'
    lastName='{{ $customer->last_name }}'
    firstName='{{ $customer->first_name }}'
    email='{{ $customer->email }}'
    phone='{{ $customer->phone }}'
    country='{{ $customer->country }}'
    notes='{{ $customer->notes }}'
    />

    <x-customer_sign_in_form />


    <div class="container-fluid mt--6">

        {{-- cards --}}
        <div class="card-deck">

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-2"><i class="ni ni-single-02"></i> Information</h5>
                    <hr class="mt-0 mb-2">
                    <div class="table-responsive p-0 m-0">
                        <div>
                            <table class="table table-borderless table-sm align-items-center">
                                <tr>
                                    <td class='font-weight-bold text-right w-25'>{{ __('Full name')}} : </td>
                                    <td>{{ $customer->last_name . ' ' . $customer->first_name }}</td>
                                </tr>
                                <tr>
                                    <td class='font-weight-bold text-right w-25'>{{ __('Creation date')}} : </td>
                                    <td>{{ Carbon\Carbon::parse($customer->created_at)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class='font-weight-bold text-right w-25'>{{ __('Departure date')}} : </td>
                                    <td>{{ Carbon\Carbon::parse($customer->departure_date)->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <td class='font-weight-bold text-right w-25'>{{ __('Email')}} : </td>
                                    <td>{{ $customer->email }}</td>
                                </tr>
                                <tr>
                                    <td class='font-weight-bold text-right w-25'>{{ __('Phone')}} : </td>
                                    <td>{{ $customer->phone }}</td>
                                </tr>
                                <tr>
                                    <td class='font-weight-bold text-right w-25'>{{ __('Country')}} : </td>
                                    <td>{{ $customer->country }}</td>
                                </tr>
                                @if ($isMaster == 1)
                                <tr>
                                    <td class='font-weight-bold text-right w-25'>{{ __('Attached files')}} : </td>
                                    <td>
                                        @foreach ($subFiles as $subFile)
                                            <span>{{ $subFile->first_name }}</span>
                                            @if(!$loop->last)
                                            ,
                                            @endif
                                        @endforeach
                                    </td>
                                </tr>
                                @endif

                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-2"><i class="ni ni-money-coins"></i> Balance</h5>
                    <hr class="mt-0 mb-2">
                    <div class="table-responsive p-0 m-0">
                        <div>
                            <table class="table table-borderless table-sm align-items-center">
                                <tr>
                                    <td class='font-weight-bold text-right w-25'>{{ __('Total bill')}} : </td>
                                    <td>{{ $totalSpent }}</td>
                                </tr>
                                <tr>
                                    <td class='font-weight-bold text-right w-25'>{{ __('Total paid')}} : </td>
                                    <td>{{ $totalPaid }}</td>
                                </tr>
                                <tr>
                                    <td class='font-weight-bold text-right w-25'>{{ __('Left to pay')}} : </td>
                                    <td>{{ $balance }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <h5 class="card-title mb-2 mt-4"><i class="ni ni-folder-17"></i> Notes</h5>
                    <hr class="mt-0 mb-2">
                    <p class="card-text">
                        {{ $customer->notes ?? 'No notes for this customer'}}
                    </p>
                </div>
            </div>
        </div>
        {{-- cards --}}

        {{-- Tabs --}}
        <div class="nav-wrapper">
            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">

                @foreach ($activeDisciplines as $discipline)
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0 @if($loop->first) active @endif" id="{{ str_replace(' ', '-', $discipline->name) }}-tab" data-toggle="tab" href="#{{ str_replace(' ', '-', $discipline->name) }}" role="tab" aria-controls="{{ str_replace(' ', '-', $discipline->name) }}" aria-selected="false">
                        {{ $discipline->name }}
                    </a>
                </li>
                @endforeach

                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-summary-tab" data-toggle="tab" href="#tabs-summary" role="tab" aria-controls="tabs-summary" aria-selected="true">
                        {{ __('Summary') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-corrections-tab" data-toggle="tab" href="#tabs-corrections" role="tab" aria-controls="tabs-corrections" aria-selected="false">
                        {{ __('Corrections') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link mb-sm-3 mb-md-0" id="tabs-payments-tab" data-toggle="tab" href="#tabs-payments" role="tab" aria-controls="tabs-payments" aria-selected="false">
                        {{ __('Payments') }}
                    </a>
                </li>
            </ul>
        </div>
        <div class="card shadow">
            <div class="card-body">
                <div class="tab-content" id="myTab">

                    @foreach ($activeDisciplines as $discipline)
                    <div class="tab-pane fade @if($loop->first)show active @endif" id="{{ str_replace(' ', '-', $discipline->name) }}" role="tabpanel" aria-labelledby="{{ str_replace(' ', '-', $discipline->name) }}-tab">
                        <div class="table-responsive">
                            <div>
                                <form method='post' action='{{route('customer.activity.store')}}'>
                                    @csrf

                                    <input type="hidden" name='isMaster' value='{{ $isMaster }}'>
                                    <input type="hidden" name='customerId' value='{{ $customer->id }}'>
                                    <input type="hidden" name='disciplineId' value='{{ $discipline->id }}'>

                                    <table class="table align-items-center">
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Date')}}</label>
                                                    <input name='activityDate' class="form-control form-control-sm" type="date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" id="example-date-input">
                                                </div>
                                            </td>

                                            {{-- To add activities on attached files --}}
                                            @if ($isMaster == 1)
                                            <td>
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Customer')}}</label>
                                                    <select name='activityCustomer' class="form-control form-control-sm">
                                                        <option value='{{ $customer->id }}'>{{ $customer->first_name }}</option>
                                                        @foreach ($subFiles as $subFile)
                                                        <option value='{{ $subFile->id }}'>{{ $subFile->first_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            @endif

                                            <td>
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Instructor')}}</label>
                                                    <select name='activityStaff' class="form-control form-control-sm">
                                                        @foreach ($customerController->getCommissionablestaff($discipline->id) as $staff)
                                                        <option value='{{ $staff->staffId }}'>{{ $staff->staffName }}</option>
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Package')}}</label>
                                                    <select name='activityPackage' class="form-control form-control-sm">
                                                        @foreach ($customerController->getCommissionablePackages($discipline->id) as $package)
                                                        <option value='{{ $package->id }}'>{{ $package->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td style="width:10%">
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Quantity')}}</label>
                                                    <input name='activityQuantity' type="text" class="form-control form-control-sm" value='0'>
                                                </div>
                                            </td>
                                            <td><button type="submit" class="btn btn-sm btn-success">{{ __('Add activity')}}</button></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <div>
                            <table>
                                <tr>
                                    <td class='pb-4 pl-4'>
                                        <button type="submit" class="btn btn-sm btn-success">{{ __('Transfer selected')}}</button>
                                        <button type="submit" class="btn btn-sm btn-danger">{{ __('Delete selected')}}</button>
                                    </td>
                                </tr>
                            </table>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <div>
                            <table class="table align-items-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">{{ __('Date') }}</th>
                                        @if ($isMaster == 1)
                                        <th scope="col">{{ __('Customer') }}</th>
                                        @endif
                                        <th scope="col">{{ __('Instructor') }}</th>
                                        <th scope="col">{{ __('Package') }}</th>
                                        <th scope="col">{{ __('Quantity') }}</th>
                                        <th scope="col">{{ __('Total') . ' ' . Auth::user()->currency }}</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody class="list">

                                    @foreach ($activitiesController->customerActivitiesList($discipline->id, $customer->id) as $activity)
                                    <tr>
                                        <td><input type="checkbox"></td>
                                        <td>
                                            {{ $activity->activityDate }}
                                        </td>
                                        @if ($isMaster == 1)
                                        <td>
                                            {{ $activity->customerName }}
                                        </td>
                                        @endif
                                        <td>
                                            {{ $activity->staffName }}
                                        </td>
                                        <td>
                                            {{ $activity->packageName }}
                                        </td>
                                        <td>
                                            {{ $activity->activityQty }}
                                        </td>
                                        <td>
                                            {{ $activity->totalAmount }}
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                  <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="{{ route('customer.activity.delete', ['activityId'=> $activity->activityId, 'customerId'=>$customer->id]) }}">{{ __('Delete') }}</a>
                                                    {{-- <a class="dropdown-item" href="#">Another action</a>
                                                    <a class="dropdown-item" href="#">Something else here</a> --}}
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    @endforeach


                    {{-- ######################################## Summary --}}
                    <div class="tab-pane fade" id="tabs-summary" role="tabpanel" aria-labelledby="tabs-summary-tab">

                        <div class="table-responsive">
                            <div>
                            <table class="table align-items-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col">{{ __('Date') }}</th>
                                        @if ($isMaster == 1)
                                        <th scope="col">{{ __('Customer') }}</th>
                                        @endif
                                        <th scope="col">{{ __('Instructor') }}</th>
                                        <th scope="col">{{ __('Package') }}</th>
                                        <th scope="col">{{ __('Quantity') }}</th>
                                        <th scope="col">{{ __('Total') . ' ' . Auth::user()->currency }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach ($activitiesController->customerActivitiesSummary($customer->id) as $activity)
                                    <tr>
                                        <td>
                                            {{ $activity->activityDate }}
                                        </td>
                                        @if ($isMaster == 1)
                                        <td>
                                            {{ $activity->customerName }}
                                        </td>
                                        @endif
                                        <td>
                                            {{ $activity->staffName }}
                                        </td>
                                        <td>
                                            {{ $activity->packageName }}
                                        </td>
                                        <td>
                                            {{ $activity->activityQty }}
                                        </td>
                                        <td>
                                            {{ $activity->totalAmount }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>

                    {{-- ######################################## Corrections --}}
                    <div class="tab-pane fade" id="tabs-corrections" role="tabpanel" aria-labelledby="tabs-corrections-tab">
                        <div class="table-responsive">
                            <div>
                                <form method='post' action='{{ route('customer.correction.store') }}'>
                                    @csrf
                                    <input type="hidden" name='isMaster' value='{{ $isMaster }}'>
                                    <input type="hidden" name='customerId' value='{{ $customer->id }}'>

                                    <table class="table align-items-center">
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Date')}}</label>
                                                    <input name='correctionDate' class="form-control form-control-sm" type="date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" id="example-date-input">
                                                </div>
                                            </td>
                                            @if ($isMaster == 1)
                                            <td>
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Customer')}}</label>
                                                    <select name='correctionCustomer' class="form-control form-control-sm">
                                                        <option value='{{ $customer->id }}'>{{ $customer->first_name }}</option>
                                                        @foreach ($subFiles as $subFile)
                                                        <option value='{{ $subFile->id }}'>{{ $subFile->first_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            @endif
                                            <td style="width:10%">
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Amount') . ' ' . Auth::user()->currency }}</label>
                                                    <input name='correctionAmount' type="text" class="form-control form-control-sm text-right" value='0'>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Type')}}</label>
                                                    <select name='correctionType' class="form-control form-control-sm">
                                                        <option value='1'>{{ __('Deduction') }}</option>
                                                        <option valie='2'>{{ __('Supplement') }}</option>
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Description')}}</label>
                                                    <input name='correctionDescription' type="text" class="form-control form-control-sm vw-100" placeholder="{{ __('Optionnal')}}">
                                                </div>
                                            </td>
                                            <td><button type="submit" class="btn btn-sm btn-success">{{ __('Add correction')}}</button></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive" style="display:none" id="correctionsOptions">
                            <div>
                            <table>
                                <tr>
                                    <td class='pb-4 pl-4'>
                                        <input type="checkbox" id='correctionsOptionsChkAll' class="mr-4 parent" name='correctionsOptionsChkAll' onclick="selectAll('correctionsOptionsChk')">
                                        <button type="button" onclick="getSelectedIds('correctionsOptionsChk')" id='deleteCorrection' class="btn btn-sm btn-danger">{{ __('Delete selected')}}</button>
                                    </td>
                            </table>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <div>
                            <table class="table align-items-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">{{ __('Date') }}</th>
                                        @if ($isMaster == 1)
                                        <th scope="col">{{ __('Customer') }}</th>
                                        @endif
                                        <th scope="col">{{ __('Amount') }}</th>
                                        <th scope="col">{{ __('Type') }}</th>
                                        <th scope="col">{{ __('Description') }}</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach ($correctionController->customerCorrectionsList($customer->id) as $correction)
                                    <tr>
                                        <td><input type="checkbox" name='correctionsOptionsChk' id='correctionsOptionsChk' value='{{ $correction->correctionId }}' name='selectedcorrections' onclick="showDiv('correctionsOptions')"></td>
                                        <td>
                                            {{ $correction->correctionDate }}
                                        </td>
                                        @if ($isMaster == 1)
                                        <td>
                                            {{ $correction->customerName }}
                                        </td>
                                        @endif
                                        <td>
                                            {{ $correction->correctionAmount }}
                                        </td>
                                        <td>
                                            @if ($correction->correctionType == 1)
                                                {{ __('Deduction') }}
                                            @else
                                            {{ __('Supplement') }}
                                            @endif
                                        </td>
                                        <td>
                                            {{ $correction->correctionDescription }}
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                  <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="{{ route('customer.correction.delete', ['correctionId' => $correction->correctionId, 'customerId'=>$customer->id]) }}">{{ __('Delete')}}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>

                    {{-- ######################################## Payments --}}
                    <div class="tab-pane fade" id="tabs-payments" role="tabpanel" aria-labelledby="tabs-payments-tab">
                        <div class="table-responsive">
                            <div>
                                <form method='post' action='{{ route('customer.payment.store') }}'>
                                    @csrf
                                    <input type="hidden" name='isMaster' value='{{ $isMaster }}'>
                                    <input type="hidden" name='customerId' value='{{ $customer->id }}'>

                                    <table class="table align-items-center">
                                        <tr>
                                            <td>
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Date')}}</label>
                                                    <input name='paymentDate' class="form-control form-control-sm" type="date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" id="example-date-input">
                                                </div>
                                            </td>
                                            @if ($isMaster == 1)
                                            <td>
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Customer')}}</label>
                                                    <select name='paymentCustomer' class="form-control form-control-sm">
                                                        <option value='{{ $customer->id }}'>{{ $customer->first_name }}</option>
                                                        @foreach ($subFiles as $subFile)
                                                        <option value='{{ $subFile->id }}'>{{ $subFile->first_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            @endif
                                            <td style="width:10%">
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Amount') . ' ' . Auth::user()->currency }}</label>
                                                    <input name='paymentAmount' type="text" class="form-control form-control-sm text-right" value='0'>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Type')}}</label>
                                                    <select name='paymentType' class="form-control form-control-sm">
                                                        @foreach (Auth::user()->paymentTypes as $paymentType)
                                                        <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <label for="example-date-input" class="form-control-label">{{ __('Description')}}</label>
                                                    <input name='paymentDescription' type="text" class="form-control form-control-sm vw-100" placeholder="{{ __('Optionnal')}}">
                                                </div>
                                            </td>
                                            <td><button type="submit" class="btn btn-sm btn-success">{{ __('Add payment')}}</button></td>
                                        </tr>
                                    </table>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive" style="display:none" id="paymentsOptions">
                            <div>
                            <table>
                                <tr>
                                    <td class='pb-4 pl-4'>
                                        <input type="checkbox" id='paymentsOptionsChkAll' class="mr-4 parent" name='paymentsOptionsChkAll' onclick="selectAll('paymentsOptionsChk')">
                                        <button type="button" onclick="getSelectedIds('paymentsOptionsChk')" id='deletePayments' class="btn btn-sm btn-danger">{{ __('Delete selected')}}</button>
                                    </td>
                            </table>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <div>
                            <table class="table align-items-center">
                                <thead class="thead-light">
                                    <tr>
                                        <th scope="col"></th>
                                        <th scope="col">{{ __('Date') }}</th>
                                        @if ($isMaster == 1)
                                        <th scope="col">{{ __('Customer') }}</th>
                                        @endif
                                        <th scope="col">{{ __('Amount') }}</th>
                                        <th scope="col">{{ __('Type') }}</th>
                                        <th scope="col">{{ __('Description') }}</th>
                                        <th scope="col"></th>
                                    </tr>
                                </thead>
                                <tbody class="list">
                                    @foreach ($paymentController->customerPaymentsList($customer->id) as $payment)
                                    <tr>
                                        <td><input type="checkbox" name='paymentsOptionsChk' id='paymentsOptionsChk' value='{{ $payment->paymentId }}' name='selectedPayments' onclick="showDiv('paymentsOptions')"></td>
                                        <td>
                                            {{ $payment->paymentDate }}
                                        </td>
                                        @if ($isMaster == 1)
                                        <td>
                                            {{ $payment->customerName }}
                                        </td>
                                        @endif
                                        <td>
                                            {{ $payment->paymentAmount }}
                                        </td>
                                        <td>
                                            {{ $payment->paymentType }}
                                        </td>
                                        <td>
                                            {{ $payment->paymentDescription }}
                                        </td>
                                        <td class="text-right">
                                            <div class="dropdown">
                                                <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                  <i class="fas fa-ellipsis-v"></i>
                                                </a>
                                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                    <a class="dropdown-item" href="{{ route('customer.payment.delete', ['paymentId' => $payment->paymentId, 'customerId'=>$customer->id]) }}">{{ __('Delete')}}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
        {{-- Tabs --}}

        <!-- Footer -->
        {{-- @include('layouts.footers.auth') --}}
    </div>

    @if ($customer->open == 0)
    <script>
        const buttons = document.getElementsByTagName("button");
        for (let i = 0; i < buttons.length; i++) {
          buttons[i].disabled = true;
        }
    </script>
    @endif

    <script>


        function showDiv(div){
            myCount = countCheckboxes(div);
            myDiv = document.getElementById(div);
            if(myCount > 0){
                document.getElementById(div).style.display = "block";
            } else {
                document.getElementById(div).style.display = "none";
            }

        }

        function selectAll(chkNames){
            myChkName = chkNames + 'All';
            myChk = document.getElementById(myChkName);
            var checkboxes = document.getElementsByName(chkNames);
            for (var checkbox of checkboxes) {
                checkbox.checked = myChk.checked;
            }
            if (!myChk.checked){
                document.getElementById('paymentsOptions').style.display = "none";
            }
        }

        function getSelectedIds(chkNames){
            var checkboxes = document.getElementsByName(chkNames);
            var selectedIds = [];

            for (var i=0; i<checkboxes.length; i++) {
                if(checkboxes[i].checked){
                    selectedIds.push(checkboxes[i].value);
                }
            }
            var customerId = JSON.parse("{{ json_encode($customer->id) }}");
            //var url = {{ route('customer.activity.store')}};
            alert(selectedIds + ' c:'+ customerId);

        }

        function countCheckboxes(chkNames){
           var inputElems = document.getElementsByName(chkNames + 'Chk'),
            count = 0;
            for (var i=0; i<inputElems.length; i++) {
                if (inputElems[i].type === "checkbox" && inputElems[i].checked === true){
                    count++;
                }
            }
            return count;
        }



    </script>
@endsection


@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.css">
@endpush

@push('js')
    <script src="{{ asset('argon') }}/vendor/datatables.net/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/dataTables.buttons.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.html5.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.flash.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-buttons/js/buttons.print.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/datatables.net-select/js/dataTables.select.min.js"></script>
    <script src="{{ asset('argon') }}/vendor/sweetalert2/dist/sweetalert2.min.js"></script>
@endpush

