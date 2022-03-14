
{{-- <script>
    $(function() {
        alert('ready');
        var select = document.getElementById('country');
        var option;

        for (var i=0; i<select.options.length; i++) {
            option = select.options[i];

            if (option.value == '{{ $country }}') {
            // or
            // if (option.text == 'Malaysia') {
                option.setAttribute('selected', true);

                // For a single select, the job's done
                return;
            }
        }
    });

</script> --}}

<div class='row'>
    <div class="col-md-4">
        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">

                    <div class="modal-body p-0">
                        <div class="card bg-secondary border-0 mb-0">

                            <div class="card-body px-lg-5 py-lg-5">
                                <div class="text-center text-muted mb-4">
                                    <small>{{ __('New customer file')}}</small>
                                </div>
                                <form role="form" method='post' action='{{route('customer.update')}}'>
                                    @csrf

                                    <input type="hidden" name='customerId' value='{{ $customerId }}'>
                                    <div class="form-group mb-3">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                                            </div>
                                            <input class="form-control datepicker" placeholder="Departure date" name='departuredate' type="text"
                                            value='{{Carbon\Carbon::parse($departureDate)->format('d-m-Y')}} '>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" placeholder="Family Name" name='lastname' type="text" value='{{ $lastName }}'>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" placeholder="First Name" name='firstname' type="text" value='{{ $firstName }}'>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" placeholder="Email" name='email' type="text" value='{{ $email }}'>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" placeholder="Phone" name='phone' type="text" value='{{ $phone }}'>
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <x-country-list />
                                        </div>
                                    </div>
                                    <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <textarea class="form-control" id="notes" name='notes' rows="3" placeholder='Notes about this customer'>{{ $notes }}</textarea>
                                        </div>
                                    </div>
                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary my-4">{{ __('Update customer')}}</button>
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



