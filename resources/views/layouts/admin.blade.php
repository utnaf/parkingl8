@extends('layouts.app')

@section('content')
    <div class="container" id="app">
        <div class="row justify-content-center">
            <div class="col-md-12">

                <div class="card">
                    @yield('admin.content')
                </div>
            </div>
        </div>
    </div>
@endsection