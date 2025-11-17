<div class="container-fluid px-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-semibold text-secondary mb-0">{{ $title }}</h1>

        {{-- Optional Action Button --}}
        @isset($button)
            <div>
                {!! $button !!}
            </div>
        @endisset
    </div>
</div>