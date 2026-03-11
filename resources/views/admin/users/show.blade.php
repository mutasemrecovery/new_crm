@extends('layouts.admin')

@section('title', __('messages.User Details'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.User Details') }} - {{ $user->name }}</h3>
                    <div>
                        @can('user-edit')
                            <a href="{{ route('users.edit', $user) }}" class="btn btn-warning me-2">
                                <i class="fas fa-edit"></i> {{ __('messages.Edit') }}
                            </a>
                        @endcan
                        <a href="{{ route('users.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Users') }}
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <!-- User Photo -->
                        <div class="col-md-3 text-center mb-4">
                            @if($user->photo)
                                <img src="{{ asset('assets/admin/uploads/' . $user->photo) }}"
                                     alt="{{ $user->name }}"
                                     class="img-fluid rounded-circle border"
                                     style="width: 200px; height: 200px; object-fit: cover;">
                            @else
                                <div class="bg-secondary d-flex align-items-center justify-content-center text-white rounded-circle mx-auto"
                                     style="width: 200px; height: 200px; font-size: 4rem;">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                        </div>

                        <!-- User Information -->
                        <div class="col-md-9">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">{{ __('messages.Name') }}</h6>
                                    <p class="h5">{{ $user->name }}</p>
                                </div>


                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">{{ __('messages.Phone') }}</h6>
                                    <p class="h5">{{ $user->country_code . ' ' . $user->phone }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">{{ __('messages.Status') }}</h6>
                                    @if($user->activate == 1)
                                        <span class="badge bg-success fs-6">{{ __('messages.Active') }}</span>
                                    @else
                                        <span class="badge bg-danger fs-6">{{ __('messages.Inactive') }}</span>
                                    @endif
                                </div>

                               

                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">{{ __('messages.Member Since') }}</h6>
                                    <p class="h5">{{ $user->created_at->format('d M Y') }}</p>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <h6 class="text-muted">{{ __('messages.Last Updated') }}</h6>
                                    <p class="h5">{{ $user->updated_at->format('d M Y - H:i') }}</p>
                                </div>


                            </div>
                        </div>
                    </div>

                    <!-- Additional Actions -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <hr>
                            <h5>{{ __('messages.Actions') }}</h5>
                            <div class="btn-group" role="group">
                                @can('user-edit')
                                    <a href="{{ route('users.edit', $user) }}" class="btn btn-warning">
                                        <i class="fas fa-edit"></i> {{ __('messages.Edit User') }}
                                    </a>
                                @endcan
                                @can('user-delete')
                                    <form method="POST" action="{{ route('users.destroy', $user) }}"
                                          style="display: inline-block;"
                                          onsubmit="return confirm('{{ __('messages.Are you sure you want to delete this user?') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i> {{ __('messages.Delete User') }}
                                        </button>
                                    </form>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
