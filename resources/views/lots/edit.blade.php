@extends('layouts.admin')

@section('admin.content')
    <div class="card-header">{{ __('general.edit_lot') }}</div>

    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="my-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @elseif(isset($success) && $success===true)
            <div class="alert alert-success">
                {{ __('general.operation_success') }}, <a href="{{ route('dashboard') }}">{{ __('frontend.go_back') }}</a>
            </div>
        @endif

        <form action="{{ route('lot.update', ['id'=>$lot->id]) }}" method="POST">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="form-group">
                <label for="name">{{ __('frontend.name') }} *</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="{{ __('frontend.name') }}"
                    value="{{ $lot->name }}" required>
            </div>

            <div class="form-group">
                <label for="name">{{ __('frontend.hourly_fare') }} *</label>
                <input type="text" class="form-control" name="hourly_fare" id="hourly_fare"
                       aria-describedby="hourlyFareHelp"
                       placeholder="{{ __('frontend.hourly_fare') }}"
                       value="{{ $lot->hourly_fare }}" required>
                <small id="hourlyFareHelp" class="form-text text-muted">{{ __('general.hourly_fare_help') }}</small>
            </div>

            <div class="form-group">
                <label for="name">{{ __('frontend.capacity') }} *</label>
                <input type="number" class="form-control" name="capacity" id="capacity" placeholder="{{ __('frontend.capacity') }}"
                       value="{{ $lot->capacity }}" required>
            </div>

            <div class="form-group">
                <label for="name">{{ __('frontend.threshold_minutes') }} *</label>
                <input type="number" class="form-control" name="threshold_minutes" id="threshold_minutes" placeholder="{{ __('frontend.threshold_minutes') }}"
                       value="{{ $lot->threshold_minutes }}" required>
            </div>

            <button type="submit" class="btn btn-primary">{{ __('general.save') }}</button>
            <a href="{{ route('dashboard') }}" class="btn btn-light">{{ __('frontend.go_back') }}</a>
        </form>
    </div>
@endsection