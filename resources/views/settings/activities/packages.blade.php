@extends('layouts.app', [
    'parentSection' => 'tables',
    'elementName' => 'datatables'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.settings')
            @slot('title')
                {{ __('Packages') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('page.index', 'datatables') }}">{{ __('Tables') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Datatables') }}</li>
        @endcomponent
    @endcomponent

    <x-overlay_form formText='New Package' :formAction="route('settings.package.store')" formId="formCreate">
        <input type="hidden" name="activityId" value="{{ $activityId }}" />
        <input type="hidden" name="activityName" value="{{ $activityName }}" />
        <div class="form-group mb-3">
            <div class="input-group input-group-merge input-group-alternative">
                <input class="form-control" placeholder="Name of package" name='name' type="text">
            </div>
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name='commissionnable' id="commissionnable">
            <label class="custom-control-label" for="commissionnable">Commissionnable to team members</label>
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
                                    <th scope="col">{{ __('Options') }}</th>
                                    <th scope="col">{{ __('Commission') }}</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody class="list">
                                @forelse ($packages as $package)
                                <tr>
                                    <th scope="row">
                                        <span class="name mb-0 text-sm">{{ $package->name }}</span>
                                    </th>
                                    <td>
                                        @forelse (App\Http\Controllers\PackagesController::getPackageOptions($package->id) as $option)
                                            {{ $option->name . " | " }}
                                        @empty
                                            <i>{{__('No option created for this package')}}</i>
                                        @endforelse
                                    </td>
                                    <td>
                                        @if ($package->commissionable == 1)
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
                                                <a class="dropdown-item" href="{{ route('package.show', ['id'=>$package->id,'packageName'=>$package->name]) }}">{{ __('Manage options') }}</a>
                                                <a class="dropdown-item" href="#">{{ __('Edit') }}</a>
                                                <a class="dropdown-item" href="#">{{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan='4' align='center'><i>{{ __('No package created yet for this activity')}}</i></td>
                                </tr>

                                @endforelse


                            </tbody>
                        </table>
                    </div>

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
