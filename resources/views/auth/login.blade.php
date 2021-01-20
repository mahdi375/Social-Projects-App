@extends('layouts.app')

@section('content')
<div class="container">
    <div >
        <h1 class="text_xl mt_2 mb_1 pl_4">{{ __('Login') }}</h1>

        <div class="w50">
            <form class="w100 p_3 rounded_2 bc_milky" method="POST" action="{{ route('login') }}">
                @csrf

                <div class="my_1" >
                    <label for="email" >{{ __('E-Mail Address') }}</label>

                    <div>
                        <input class="mt_1 h2em rounded_1 w50 border_1" id="email" type="email" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                        @error('email')
                            <p class="text_error" role="alert">
                                <strong>{{ $message }}</strong>
                            </p>
                        @enderror
                    </div>
                </div>

                <div class="my_1" >
                    <label for="password" >{{ __('Password') }}</label>

                    <div>
                        <input class="mt_1 h2em rounded_1 w50 border_1"  id="password" type="password"  name="password" required autocomplete="current-password">

                        @error('password')
                            <p class="text_error" role="alert">
                                <strong>{{ $message }}</strong>
                            </p>
                        @enderror
                    </div>
                </div>

                <div >
                    <div >
                        <div >
                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                            <label for="remember">
                                {{ __('Remember Me') }}
                            </label>
                        </div>
                    </div>
                </div>

                <div class="my_2" >
                    <button class="btn_blue border_1 py_1 px_4 rounded_1 " type="submit" >
                        {{ __('Login') }}
                    </button>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
