@extends('layouts.admin')

@section('admin.content')
    <div class="card-header">{{ __('general.users_list') }}</div>

    <div class="card-body">
        @if(session('success'))
        <div class="alert alert-success">
            {{ __('general.operation_success') }}, <a href="{{ route('dashboard') }}">{{ __('frontend.go_back') }}</a>
        </div>
        @endif

        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">{{ __('frontend.name') }}</th>
                <th scope="col">{{ __('auth.email') }}</th>
                <th scope="col">{{ __('general.is_admin') }}</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->id }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    @if ($user->isAdmin())
                        <span class="oi oi-check"></span>
                    @endif
                </td>
                <td class="text-right">
                    @if (!$user->isAdmin())
                        <form action="{{ route('user.update', $user->id) }}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="action" value="upgrade">
                            {{ method_field('patch') }}

                            <button type="submit" class="btn btn-primary btn-sm">
                                <span class="oi oi-circle-check"></span>
                                Upgrade
                            </button>
                        </form>
                    @endif
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection