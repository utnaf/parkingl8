@extends('layouts.admin')

@section('admin.content')
    <div class="card-header">{{ __('general.issues_list') }}</div>

    <div class="card-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ __('general.operation_success') }}, <a href="{{ route('dashboard') }}">{{ __('frontend.go_back') }}</a>
            </div>
        @endif

        <div class="py-2">
            <a href="{{ route('dashboard') }}" class="btn btn-primary">{{ __('frontend.go_back') }}</a>
        </div>


        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">{{ __('general.issue_related_to') }}</th>
                <th scope="col">{{ __('general.issue_type') }}</th>
                <th scope="col">{{ __('general.issue_solved') }}</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody>
            @forelse($issues as $issue)
                <tr>
                    <td>{{ $issue->id }}</td>
                    <td>
                        @if ($issue->entry instanceof \Parking\Entry)
                            {{ __('general.issue_entry', ['id' => $issue->entry->id]) }}
                        @else
                            {{ __('general.issue_lot', ['id' => $issue->parking_lot_id]) }}
                        @endif
                    </td>

                    <td>{{ __('general.' . $issue->type) }}</td>
                    <td>
                        @if ($issue->solved === 1)
                            <span class="oi oi-check"></span>
                        @endif
                        @if ($issue->completedBy)
                            {{ __('general.by') }} {{ $issue->completedBy->name }}
                        @endif
                    </td>
                    <td class="text-right">
                        <form action="{{ route('issues.update', $issue->id) }}" method="POST">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="action" value="solve">
                            {{ method_field('patch') }}

                            @if (!$issue->solved)
                            <button type="submit" class="btn btn-primary btn-sm">
                                <span class="oi oi-circle-check"></span>
                                {{ __('general.issue_mark_solved') }}
                            </button>
                            @endif
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        {{ __('general.no_results') }}
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection