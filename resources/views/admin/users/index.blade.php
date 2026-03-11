@extends('layouts.admin')

@section('title', __('messages.Users'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Users') }}</h3>
                    @can('user-add')
                        <a href="{{ route('users.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> {{ __('messages.Add New User') }}
                        </a>
                    @endcan
                </div>

                <div class="card-body">
                    <!-- Search and Filter Form -->
                    <form method="GET" action="{{ route('users.index') }}" class="mb-3">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control"
                                       placeholder="{{ __('messages.Search') }}"
                                       value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="activate" class="form-control">
                                    <option value="">{{ __('messages.All Status') }}</option>
                                    <option value="1" {{ request('activate') == '1' ? 'selected' : '' }}>
                                        {{ __('messages.Active') }}
                                    </option>
                                    <option value="2" {{ request('activate') == '2' ? 'selected' : '' }}>
                                        {{ __('messages.Inactive') }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-info">
                                    <i class="fas fa-search"></i> {{ __('messages.Search') }}
                                </button>
                            </div>
                            <div class="col-md-2">
                                <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> {{ __('messages.Reset') }}
                                </a>
                            </div>
                        </div>
                    </form>


                    <!-- Users Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>#</th>
                                    <th>{{ __('messages.Photo') }}</th>
                                    <th>{{ __('messages.Name') }}</th>
                                    <th>{{ __('messages.Phone') }}</th>
                          
                                    <th>{{ __('messages.Status') }}</th>
                                    <th>{{ __('messages.Actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                    <tr>
                                        <td>{{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}</td>
                                        <td>
                                            @if($user->photo)
                                                <img src="{{ asset('assets/admin/uploads/' . $user->photo) }}"
                                                     alt="{{ $user->name }}"
                                                     class="img-thumbnail"
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-secondary d-flex align-items-center justify-content-center text-white"
                                                     style="width: 50px; height: 50px; border-radius: 4px;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                        </td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{  $user->phone }}</td>
                                        <td>
                                            @if($user->activate == 1)
                                                <span class="badge bg-success">{{ __('messages.Active') }}</span>
                                            @else
                                                <span class="badge bg-danger">{{ __('messages.Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                @can('user-edit')
                                                    <a href="{{ route('users.show', $user) }}"
                                                       class="btn btn-sm btn-info" title="{{ __('messages.View') }}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="{{ route('users.edit', $user) }}"
                                                       class="btn btn-sm btn-warning" title="{{ __('messages.Edit') }}">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                @endcan

                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">{{ __('messages.No users found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $users->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
