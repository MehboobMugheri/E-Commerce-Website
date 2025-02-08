@if (Session::has('error'))
<div class="alert alert-danger">
        {{-- <p>{{ $message }}</p> --}}
        <p>Error</p>
       {{ Session::get('error') }}    
</div>
@endif

@if (Session::has('success'))
<div class="alert alert-success">
        {{-- <p>{{ $message }}</p> --}}
        <p>Success</p>
       {{ Session::get('success') }}    
</div>
@endif