@extends('layouts.auth-master')

@section('content')
    <form method="post" action="{{ route('token.perform') }}">
        
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <img class="mb-4" src="{!! url('images/bootstrap-logo.svg') !!}" alt="" width="72" height="57">
        
        <h1 class="h3 mb-3 fw-normal">Two Factor Authentication</h1>

        @if(Session::get('authy_error', false))
            <div class="alert alert-warning" role="alert">
                <i class="fa fa-check"></i>
                {{ Session::get('authy_error'); }}
            </div>
        @endif

        <div class="form-group form-floating mb-3">
            <input type="text" class="form-control" name="authy_token" value="{{ old('authy_token') }}" placeholder="Authy Token" required="required" autofocus>
            <label for="floatingName">Authy Token</label>
            @if ($errors->has('authy_token'))
                <span class="text-danger text-left">{{ $errors->first('authy_token') }}</span>
            @endif
        </div>

        <button class="w-100 btn btn-lg btn-primary" type="submit">Verify</button>
        
        @include('auth.partials.copy')
    </form>
@endsection
