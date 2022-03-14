
@inject('customerController', 'App\Http\Controllers\customerController')
@inject('activitiesController', 'App\Http\Controllers\ActivitiesController')
<div class='row'>
    <div class="col-md-4">
        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">

                    <div class="modal-body p-0">
                        <div class="card bg-secondary border-0 mb-0">

                            <div class="card-body px-lg-5 py-lg-5">
                                <div class="text-center text-muted mb-4">
                                    <small>{{ __('Modifiy staff file')}}</small>
                                </div>
                                <form role="form" method='post' action='{{route('staff.update', ['staffId'=> $staffId, 'period'=>Carbon\Carbon::today()->format('Y-m'). '-01'])}}'>
                                    @csrf

                                    <input type="hidden" name='customerId' value='{{ $staffId }}'>
                                    <div class="form-group mb-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                            </div>
                                            <input class="form-control datepicker" placeholder="Start date" name='startDate' type="text"
                                            value='{{Carbon\Carbon::parse($startDate)->format('d-m-Y')}} '>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" placeholder="Full Name" name='name' type="text" value='{{ $staffName }}'>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" placeholder="Family Name" name='description' type="text" value='{{ $description }}'>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" placeholder="Email" name='staffEmail' type="text" value='{{ $email }}'>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" placeholder="Password" name='staffPassword' type="password" value='{{ $password }}'>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" placeholder="Phone" name='phone' type="text" value='{{ $phone }}'>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" placeholder="Fixed salary {{ Auth::user()->currency }}" name='salary' type="text" value='{{ $salary }}'>
                                        </div>
                                    </div>

                                    {{-- Activities commissions --}}
                                    <div class="accordion" id="accordionExample">
                                        @foreach (Auth::user()->disciplinesCommissionable as $discipline)
                                        <div class="card">
                                            <div class="card-header" id="heading{{ $discipline->id }}" data-toggle="collapse" data-target="#collapse{{ $discipline->id }}" aria-expanded="false" aria-controls="collapse{{ $discipline->id }}">
                                                <h5 class="mb-0">{{ __('Commissions :') . ' ' . $discipline->name }}</h5>
                                            </div>
                                            <div id="collapse{{ $discipline->id }}" class="collapse" aria-labelledby="heading{{ $discipline->id }}" data-parent="#accordionExample">
                                                <div class="card-body">
                                                    @foreach ($customerController->getCommissionablePackages($discipline->id) as $package)
                                                    <label for="commissionAmount" class="form-control-label">{{ $package->name }}</label>
                                                    <div class="form-group mb-3 text-left custom-control-inline">
                                                        <input type="hidden" name='commissionPackageId[]' value='{{ $package->id }}'>
                                                        <input type="hidden" name='commissionDisciplineId[]' value='{{ $package->discipline_id }}'>
                                                        <input class="form-control w-50" value='{{ $activitiesController->getStaffCommAmount($staffId, $package->id) }}' name='commissionAmount[]' id='commissionAmount' type="text">
                                                        <span>&nbsp;&nbsp;</span>
                                                        <select name='commissionType[]' class='form-control w-50'>
                                                            <option value='1' @if($activitiesController->getStaffCommType($staffId, $package->id) == '1') selected @endif>%</option>
                                                            <option value='2' @if($activitiesController->getStaffCommType($staffId, $package->id) == '2') selected @endif>{{ __('Fixed') . ' ' . Auth::user()->currency}}</option>
                                                        </select>
                                                    </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach

                                    </div>
                                    {{-- End of Activities commissions --}}

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary my-4">{{ __('Update staff file')}}</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



