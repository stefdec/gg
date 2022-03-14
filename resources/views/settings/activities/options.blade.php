@extends('layouts.app', [
    'parentSection' => 'tables',
    'elementName' => 'datatables'
])

@section('content')
    @component('layouts.headers.auth')
        @component('layouts.headers.settings')
            @slot('title')
                {{ __('Options') }}
            @endslot

            <li class="breadcrumb-item"><a href="{{ route('page.index', 'datatables') }}">{{ __('Tables') }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ __('Datatables') }}</li>
        @endcomponent
    @endcomponent

    <script>
        function showVariableOptions(myDiv){

            mDiv = document.getElementById(myDiv);

            if(mDiv.style.display == "none"){
                mDiv.style.display = "block";
            }else{
                mDiv.style.display = "none";
            }
        }
    </script>

    <x-overlay_form :formText="__('New price option for ') . $packageName" :formAction="route('settings.option.store')" formId="formCreate">
        <input type="hidden" name="packageId" value="{{ $packageId }}" />
        <input type="hidden" name="packageName" value="{{ $packageName }}" />
        <div class="form-group mb-3">
            <div class="input-group input-group-merge input-group-alternative">
                <input class="form-control" placeholder="Name of option" name='name' type="text">
            </div>
        </div>
        <div class="form-group mb-3">
            <div class="input-group input-group-merge input-group-alternative">
                <input class="form-control" placeholder="Price {{ auth()->user()->currency }}" name='price' type="text">
            </div>
        </div>
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" name='variable' id="variable" onClick="showVariableOptions('variables');">
            <label class="custom-control-label" for="variable">Variable price</label>
        </div>

        <div id='variables' style="display: none;">

            <div class="form-group mb-3 mt-3">
                <div class="input-group input-group-merge input-group-alternative">
                    <select name='amount_per' class='form-control'>
                        <option value='{{ __('Per unit') }}'>{{ __('Per unit') }}</option>
                        <option value='{{ __('Per hour') }}'>{{ __('Per hour') }}</option>
                        <option value='{{ __('Per half day') }}'>{{ __('Per half day') }}</option>
                        <option value='{{ __('Per day') }}'>{{ __('Per day') }}</option>
                        <option value='{{ __('Per week') }}'>{{ __('Per week') }}</option>
                        <option value='{{ __('Per month') }}'>{{ __('Per month') }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group mb-3">
                <div class="input-group input-group-merge input-group-alternative">
                    <select name='p_condition' class='form-control'>
                        <option value='{{ __('No conidtion') }}'>{{ __('No conidtion') }}</option>
                        <option value='{{ __('If equals to') }}'>{{ __('If equals to') }}</option>
                        <option value='{{ __('If inferior to') }}'>{{ __('If inferior to') }}</option>
                        <option value='{{ __('If superior to') }}'>{{ __('If superior to') }}</option>
                    </select>
                </div>
            </div>
            <div class="form-group mb-3">
                <div class="input-group input-group-merge input-group-alternative">
                    <input class="form-control" placeholder="Number for condtion" name='nb_condition' type="text">
                </div>
            </div>
            <div class="form-group mb-3 mt-3">
                <div class="input-group input-group-merge input-group-alternative">
                    <select name='data_condition' class='form-control'>
                        <option value='{{ __('unit(s)') }}'>{{ __('unit(s)') }}</option>
                        <option value='{{ __('hour(s)') }}'>{{ __('hour(s)') }}</option>
                        <option value='{{ __('half day(s)') }}'>{{ __('half day(s)') }}</option>
                        <option value='{{ __('day(s)') }}'>{{ __('day(s)') }}</option>
                        <option value='{{ __('week(s)') }}'>{{ __('week(s)') }}</option>
                        <option value='{{ __('month(s)') }}'>{{ __('month(s)') }}</option>
                    </select>
                </div>
            </div>

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
                                    <th scope="col">{{ __('Description') }}</th>
                                    <th></th>
                                </tr>
                            </thead>

                            <tbody class="list">
                                @forelse ($options as $option)
                                <tr>
                                    <th scope="row">
                                        <span class="name mb-0 text-sm">{{ $option->name }}</span>
                                    </th>
                                    <td>
                                        <span class="status">
                                            {{ $option->amount . ' ' . auth()->user()->currency }}

                                            @if ($option->p_condition != 'fixed')
                                                {{ $option->amount_per . ' ' . $option->p_condition . ' ' . $option->nb_condition . ' ' . $option->data_condition }}
                                            @endif
                                        </span>
                                        {{-- @if ($package->commissionable == 1)
                                        <span class="badge badge-dot mr-4">
                                            <i class="bg-green"></i>
                                            <span class="status">{{__('Yes')}}</span>
                                        </span>
                                        @else
                                        <span class="badge badge-dot mr-4">
                                            <i class="bg-warning"></i>
                                            <span class="status">{{__('No')}}</span>
                                        </span>
                                        @endif --}}

                                    </td>
                                    <td class="text-right">
                                        <div class="dropdown">
                                            <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                              <i class="fas fa-ellipsis-v"></i>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                                                <a class="dropdown-item" href="#">{{ __('Edit') }}</a>
                                                <a class="dropdown-item" href="#">{{ __('Delete') }}</a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan='4' align='center'><i>{{ __('No option created yet for this package')}}</i></td>
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
