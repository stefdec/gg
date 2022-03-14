
<div class='row'>
    <div class="col-md-4">
        <div class="modal fade" id="modal-form" tabindex="-1" role="dialog" aria-labelledby="modal-form" aria-hidden="true">
            <div class="modal-dialog modal- modal-dialog-centered modal-sm" role="document">
                <div class="modal-content">

                    <div class="modal-body p-0">
                        <div class="card bg-secondary border-0 mb-0">

                            <div class="card-body px-lg-5 py-lg-5">
                                <div class="text-center text-muted mb-4">
                                    <small>{{ $formText}}</small>
                                </div>
                                <form role="form" method='post' action='{{ $formAction }}'>
                                    @csrf

                                    {{ $slot }}

                                    {{-- <div class="form-group mb-3">
                                        <div class="input-group input-group-merge input-group-alternative">
                                            <input class="form-control" placeholder="Name of discipline" name='name' type="text">
                                        </div>
                                    </div>
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" class="custom-control-input" name='commissionnable' id="commissionnable">
                                        <label class="custom-control-label" for="commissionnable">Commissionnable to agents</label>
                                    </div> --}}

                                    <div class="text-center">
                                        <button type="submit" class="btn btn-primary my-4">Save</button>
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
