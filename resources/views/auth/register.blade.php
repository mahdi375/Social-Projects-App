@extends('layouts.app')

@section('content')
<div class="container">
    <div >
        <div >
            <h1 class="text_xl mt_2 mb_1 pl_4">{{ __('Register') }}</h1>

            <div class="w50" >
                <form class="w100 p_3 rounded_2 bc_milky" method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="my_1" >
                        <label for="name">{{ __('Name') }}</label>

                        <div >
                            <input class="mt_1 h2em rounded_1 w50 border_1" id="name" type="text" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                            @error('name')
                                <p  class="text_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="my_1">
                        <label for="email" >{{ __('E-Mail Address') }}</label>

                        <div >
                            <input class="mt_1 h2em rounded_1 w50 border_1"  id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email">

                            @error('email')
                                <p  class="text_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="my_1">
                        <label for="password" >{{ __('Password') }}</label>

                        <div >
                            <input class="mt_1 h2em rounded_1 w50 border_1" id="password" type="password" name="password" required autocomplete="new-password">

                            @error('password')
                                <p class="text_error" role="alert">
                                    <strong>{{ $message }}</strong>
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="my_1">
                        <label for="password-confirm" >{{ __('Confirm Password') }}</label>

                        <div >
                            <input class="mt_1 h2em rounded_1 w50 border_1" id="password-confirm" type="password" name="password_confirmation" required autocomplete="new-password">
                        </div>
                    </div>

                    <div class="my_2">
                        <div >
                            <button class="btn_blue border_1 py_1 px_4 rounded_1 " type="submit">
                                {{ __('Register') }}
                            </button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
