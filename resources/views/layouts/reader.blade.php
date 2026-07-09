@extends('layouts.app')

@section('content')

<div class="container py-2">
    <div class="row">

        @include('partials.sidebar-reader')

        <div class="col-lg-8 col-xl-9">
            @yield('reader-content')
        </div>

    </div>
</div>

@endsection