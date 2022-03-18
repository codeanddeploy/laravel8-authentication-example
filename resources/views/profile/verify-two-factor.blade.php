@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-5 rounded">

        <h3>Two Factor Verification</h3>

        @include('layouts.partials.messages')

        @if(Session::get('authy_error', false))
            <div class="alert alert-warning" role="alert">
                <i class="fa fa-check"></i>
                {{ Session::get('authy_error'); }}
            </div>
        @endif

        <p>
            @lang('We have sent you a verification token via SMS. Please provide the token
            below to verify your phone number.')
        </p>

        <form method="post" action="{{ route('profile.postVerifyTwoFactor') }}">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <div class="mb-3">
                <label for="authy_token" class="form-label">Token</label>
                <input type="text" class="form-control" name="authy_token" placeholder="Authy Token">
                @if ($errors->has('authy_token'))
                    <span class="text-danger text-left">{{ $errors->first('authy_token') }}</span>
                @endif
            </div>

            <button type="submit" class="btn btn-primary">Verify</button>
        </form>
        
    </div>
@endsection
