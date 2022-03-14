@extends('layouts.app', [
    'parentSection' => 'tables',
    'elementName' => 'datatables'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.staffList')
            @slot('title')
                {{ __('Staff List') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('page.index', 'datatables') }}">{{ __('Tables') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Datatables') }}</li>
        @endcomponent
    @endcomponent

    @inject('StaffController', 'App\Http\Controllers\StaffController')

    <x-staff_form />

    <div class="container-fluid mt--6">
        <!-- Table -->
        <div class="row">
            <div class="col">
                <div class="card">
                    <!-- Card header -->

                    <div class="table-responsive py-4">
                        <table class="table table-flush" id="datatable-basic">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Start Date</th>
                                    <th>Description</th>
                                    <th>Fixed salary</th>
                                    <th>Active</th>
                                    <th></th>
                                </tr>
                            </thead>


                            <tbody>
                                @forelse($staffs as $staff)
                                <tr>
                                    <td>{{ $staff->name }}</td>
                                    <td>{{ $staff->start_date }}</td>
                                    <td>{{ $staff->description }}</td>
                                    <td>{{ $StaffController->getFixedSalary($staff->id) }}</td>
                                    <td class="text-center">
                                        @if($staff->active == 1)
                                        <span class="badge badge-dot mr-4">
                                            <i class="bg-success"></i>
                                        </span>
                                        @else
                                        <span class="badge badge-dot mr-4">
                                            <i class="bg-warning"></i>
                                        </span>
                                        @endif
                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" href="#">Edit</a>
                                                <a class="dropdown-item" href="#">Close</a>
                                                <a class="dropdown-item text-black" href="{{ route('customer.show', ['id' => $staff->id]) }}">View file</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td class='text-center' colspan='6'><i>{{ __('No staff created yet') }}</i></td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
