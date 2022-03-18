@extends('layouts.app-master')

@section('content')
    <div class="bg-light p-5 rounded">

        <h1>Profile</h1>
        

        <br>

        <h3>Two-step authentication</h3>

        @include('layouts.partials.messages')

        @if (session('authy_errors'))
            <div class="alert alert-danger" role="alert">
                @foreach(session('authy_errors') as $field => $message)
                    Authy Error: {{$field}}: {{$message}} <br>
                @endforeach
            </div>
        @endif

        @if(auth()->user()->authy_status != 1)
            <form method="post" action="{{ route('profile.enableTwoFactor') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <div class="mb-3">
                    <label for="country_code" class="form-label">Country Code</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        name="country_code" 
                        placeholder="63"
                        value="{{ auth()->user()->authy_country_code }}">
                </div>

                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input 
                        type="text" 
                        class="form-control" 
                        name="phone_number" 
                        placeholder="Phone number without country code"
                        value="{{ auth()->user()->authy_phone }}">
                </div>

                <button type="submit" class="btn btn-primary">Enable Two Factor</button>
            </form>
        @else
            <form method="post" action="{{ route('profile.disableTwoFactor') }}">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <button type="submit" class="btn btn-danger">Disable Two Factor</button>
            </form>
        @endif
        
    </div>
@endsection
