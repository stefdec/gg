<div class="table-responsive">
    <div>
        <form method='post' action='#'>
            @csrf
            <table class="table align-items-center">
                <tr>
                    <td>
                        <div class="form-group">
                            <label for="example-date-input" class="form-control-label">{{ __('Date')}}</label>
                            <input class="form-control form-control-sm" type="date" value="{{ Carbon\Carbon::now()->format('Y-m-d') }}" id="example-date-input">
                        </div>
                    </td>
                    <td class='w-25'>
                        <div class="form-group">
                            <label for="example-date-input" class="form-control-label">{{ __('Amount') . ' ' . Auth::user()->currency }}</label>
                            <input type="text" class="form-control form-control-sm text-right" value='0'>
                        </div>
                    </td>
                    <td class='w-25'>
                        <div class="form-group">
                            <label for="example-date-input" class="form-control-label">{{ __('Type')}}</label>
                            <select class="form-control form-control-sm">
                                @foreach (Auth::user()->paymentTypes as $paymentType)
                                <option value="{{ $paymentType->id }}">{{ $paymentType->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </td>
                    <td  class='w-50'>
                        <div class="form-group">
                            <label for="example-date-input" class="form-control-label">{{ __('Description')}}</label>
                            <input type="text" class="form-control form-control-sm vw-100" placeholder="{{ __('Optionnal')}}">
                        </div>
                    </td>
                    <td><button type="submit" class="btn btn-sm btn-success">{{ __('Add payment')}}</button></td>
                </tr>
            </table>
        </form>
    </div>
</div>
