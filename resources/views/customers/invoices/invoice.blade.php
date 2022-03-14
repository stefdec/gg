<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

</head>


@inject('activitiesController', 'App\Http\Controllers\ActivitiesController')
@inject('customerController', 'App\Http\Controllers\customerController')


<style>
    body {
        font-family: Arial, Helvetica, sans-serif;
    }
    .pretty {
        border: 1px solid black;
        border-collapse: collapse;
        font-size:11px;

    }

    td{ padding:4px;}
</style>

<body>
    <table class='table w-100 table-borderless'>
        <tr>
            <td class='w-50' align='center'>
                <img src='pictures/logos/ktn-logo.jpg' class='w-75'>
            </td>
            <td style='padding-left:20%; padding-top:5%'>
                {{ $customerFirstName .' ' . $customerLastName}}
            </td>
        </tr>
    </table>

    <br>

    <p align='center' style='font-size:20px'><b>{{ __('INVOICE') }}</b></p>

    <br>

    <table class='table table-bordered pretty'>
        <tr>
            <th class='pretty' style="text-align:center">{{ __('Date') }}</th>
            <th class='pretty' style="text-align:center">{{ __('Customer') }}</th>
            <th class='pretty' style="text-align:center">{{ __('Description') }}</th>
            <th class='pretty' style="text-align:center">{{ __('Package') }}</th>
            <th class='pretty' style="text-align:center">{{ __('Unit price')  . ' ' . Auth::user()->currency }}</th>
            <th class='pretty' style="text-align:center">{{ __('Qty') }}</th>
            <th class='pretty' style="text-align:center">{{ __('Total'). ' ' . Auth::user()->currency }}</th>
        </tr>
        @foreach ($activitiesController->customerActivitiesSummary($customerId) as $activity)
        <tr>
            <td class='pretty'>
                {{ $activity->activityDate }}
            </td>
            <td class='pretty'>
                {{ $activity->customerName }}
            </td>
            <td class='pretty'>
                {{ $activity->description }}
            </td>
            <td class='pretty'>
                {{ $activity->packageName }}
            </td>
            <td align='right' class='pretty'>
                {{ $activity->activityPrice }}
            </td>
            <td align='right' class='pretty'>
                {{ $activity->activityQty }}
            </td>
            <td align='right' class='pretty'>
                {{ $activity->totalAmount }}
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan='6' class='pretty text-end fw-bold'>{{  __('TOTAL') . ' ' . Auth::user()->currency }}</td>
            <td class='pretty text-end fw-bold'>{{ $customerController->getTotalSpent($customerId) }}</td>
        </tr>
        <tr>
            <td colspan='6' class='pretty text-end fw-bold'>{{  __('PAID') . ' ' . Auth::user()->currency }}</td>
            <td class='pretty text-end fw-bold'>{{ $customerController->getTotalPaid($customerId) }}</td>
        </tr>
        <tr>
            <td colspan='6' class='pretty text-end fw-bold'>{{  __('BALANCE') . ' ' . Auth::user()->currency }}</td>
            <td class='pretty text-end fw-bold'>{{ $customerController->getBalance($customerId) }}</td>
        </tr>
    </table>
    <br>
    <br>
    <p class='text-center'>{{ __('THANK YOU FOR YOUR BUSINESS') }}</p>
</body>
</html>
