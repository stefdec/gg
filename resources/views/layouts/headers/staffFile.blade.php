<div class="row align-items-center py-4">
    <div class="col-lg-6 col-7">
        <h6 class="h2 text-white d-inline-block mb-0">{{ $title }}</h6>
        <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                <li class="breadcrumb-item"><a href="{{ route('home') }}"><i class="fas fa-home"></i></a></li>
                {{ $slot }}
            </ol>
        </nav>
    </div>
    @if (isset($calendar))
        {{ $calendar }}
    @else
        <div class="col-lg-6 col-5 text-right">
            <a href="#" class="btn btn-default" data-toggle="modal" data-target="#modal-form">{{ __('Edit info') }}</a>
            <button class="btn btn-danger" data-toggle="sweet-alert" data-sweet-alert="confirm">{{ __('Close file') }}</button>
            <a href="#" target='_blank' class="btn btn-neutral">{{ __('Salary slip') }}</a>
        </div>
    @endif
</div>
{{-- {{ route('customer.invoice', ['staffId'=>$staffId, 'staffName'=>$staffName, 'logo'=>'logo']) }} --}}
