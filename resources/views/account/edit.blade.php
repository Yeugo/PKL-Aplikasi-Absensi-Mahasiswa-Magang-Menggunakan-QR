@extends('layouts.app')

@section('content')
<div class="container">
    @include('partials.alerts')

    <form method="POST" action="{{ route('account.update') }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <div class="w-100">
                <!-- Email Field -->
                <div class="form-group mb-3">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ auth()->user()->email }}" required>
                </div>

                <!-- Password Field -->
                <div class="form-group mb-3">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password">
                    <small class="text-muted">Leave blank if you don't want to change the password.</small>
                </div>

                <!-- Confirm Password Field -->
                <div class="form-group mb-3">
                    <label for="password_confirmation">Confirm New Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="form-control" placeholder="Confirm new password">
                </div>
            </div>
        </div>
        

        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
@endsection
{{-- @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif --}}
