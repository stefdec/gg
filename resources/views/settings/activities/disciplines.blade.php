@extends('layouts.app', [
    'parentSection' => 'tables',
    'elementName' => 'datatables'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.settings')
            @slot('title')
                {{ __('Activities') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('page.index', 'datatables') }}">{{ __('Tables') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Datatables') }}</li>
        @endcomponent
    @endcomponent

<x-overlay_form formText='New Activity' :formAction="route('settings.discipline.store')" formId="formCreate">
    <div class="form-group mb-3">
        <div class="input-group input-group-merge input-group-alternative">
            <input class="form-control" placeholder="Name of activity" name='name' type="text">
        </div>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name='commissionnable' id="commissionnable">
        <label class="custom-control-label" for="commissionnable">Commissionnable to instructors</label>
    </div>
    <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" name='commissionnableTo' id="commissionnableTo">
        <label class="custom-control-label" for="commissionnableTo">Commissionnable to agents</label>
    </div>
</x-overlay_form>

    <div class="container-fluid mt--6">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->

                    <div class="table-responsive">
                        <div>
                        <table class="table align-items-center">
                            <thead class="thead-light">
                                <tr>
                                    <th scope="col" class="sort" data-sort="name">{{ __('Name') }}</th>
                                    <th scope="col">{{ __('Packages') }}</th>
                                    <th scope="col">{{ __('Commission') }}</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody class="list">
                                @forelse ($disciplines as $activity)
                                <tr>
                                    <th scope="row">
                                        <span class="name mb-0 text-sm">{{ $activity->name }}</span>
                                    </th>
                                    <td>
                                        @forelse (App\Http\Controllers\DisciplinesController::getDisciplinePackages($activity->id) as $package)
                                            {{ $package->name . " | " }}
                                        @empty
                                            <i>{{__('No package created for this activity')}}</i>
                                        @endforelse
                                    </td>
                                    <td>
                                        @if ($activity->commissionable == 1)
                                        <span class="badge badge-dot mr-4">
                                            <i class="bg-green"></i>
                                            <span class="status">{{__('Yes')}}</span>
                                        </span>
                                        @else
                                        <span class="badge badge-dot mr-4">
                                            <i class="bg-warning"></i>
                                            <span class="status">{{__('No')}}</span>
                                        </span>
                                        @endif

                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" href="{{ route('discipline.show', ['id'=>$activity->id,'activityName'=>$activity->name]) }}">{{ __('Manage packages') }}</a>
                                                <a class="dropdown-item" href="#">{{ __('Edit') }}</a>
                                                <a class="dropdown-item" href="#">{{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan='4' align='center'><i>{{ __('No activity created yet')}}</i></td>
                                </tr>

                                @endforelse


                            </tbody>
                        </table>
                    </div>

                    </div>

                    {{-- <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Packages</th>
                                    <th>Commissions</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th></th>
                                    <th>Name</th>
                                    <th>Packages</th>
                                    <th>Commissions</th>
                                </tr>
                            </tfoot>

                            <tbody>
                                @foreach($disciplines as $activity)
                                <tr>
                                    <td><a href='{{ route('customer.show', ['id' => $customer->id, 'name' => 'toto']) }}'><i class="ni ni-folder-17"></i></a></td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->name }}</td>
                                    <td>{{ $customer->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div> --}}
                </div>
            </div>
        </div>
        <!-- Footer -->
        @include('layouts.footers.auth')
    </div>
@endsection

@push('css')
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-bs4/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css">
    <link rel="stylesheet" href="{{ asset('argon') }}/vendor/datatables.net-select-bs4/css/select.bootstrap4.min.css">
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
@endpush
