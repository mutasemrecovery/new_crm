@extends('layouts.admin')

@section('title', __('messages.Add New User'))

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">{{ __('messages.Add New User') }}</h3>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> {{ __('messages.Back to Users') }}
                    </a>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('users.store') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="row">
                            <!-- Name -->
                            <div class="col-md-3 mb-3">
                                <label for="name" class="form-label">{{ __('messages.Name') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>


                            <!-- Phone -->
                            <div class="col-md-3 mb-3">
                                <label for="phone" class="form-label">{{ __('messages.Phone') }} <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="col-md-3 mb-3">
                                <label for="activate" class="form-label">{{ __('messages.Status') }} <span class="text-danger">*</span></label>
                                <select class="form-control @error('activate') is-invalid @enderror"
                                        id="activate" name="activate" required>
                                    <option value="1" {{ old('activate', '1') == '1' ? 'selected' : '' }}>{{ __('messages.Active') }}</option>
                                    <option value="2" {{ old('activate') == '2' ? 'selected' : '' }}>{{ __('messages.Inactive') }}</option>
                                </select>
                                @error('activate')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-md-3 mb-3">
                                <label for="password" class="form-label">{{ __('messages.Password') }} <span class="text-danger">*</span></label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       id="password" name="password" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>



                            <!-- Photo -->
                            <div class="col-md-12 mb-3">
                                <label for="photo" class="form-label">{{ __('messages.Photo') }}</label>
                                <input type="file" class="form-control @error('photo') is-invalid @enderror"
                                       id="photo" name="photo" accept="image/*">
                                @error('photo')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">{{ __('messages.Allowed formats: JPG, PNG, GIF. Max size: 2MB') }}</div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> {{ __('messages.Save User') }}
                                </button>
                                <a href="{{ route('users.index') }}" class="btn btn-secondary ms-2">
                                    <i class="fas fa-times"></i> {{ __('messages.Cancel') }}
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
