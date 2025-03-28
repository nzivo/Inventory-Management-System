@extends('layouts.app')

@section('content')

<div
    class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700">
    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
        <h1 class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white">
            {{ __('Confirm Password') }}
        </h1>
        {{ __('Please confirm your password before continuing.') }}
        <form class="space-y-4 md:space-y-6" method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <label for="password" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">{{
                    __('Password')
                    }}</label>
                <input id="password" type="password"
                    class="bg-gray-50 border border-gray-300 text-gray-900 rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500 @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password">
                @error('password')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
                @enderror
            </div>

            <button type="submit"
                class="w-full text-white bg-red-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800">{{
                __('Confirm Password') }}</button>
            @if (Route::has('password.request'))
            <a class="btn btn-link text-red-600" href="{{ route('password.request') }}">
                {{ __('Forgot Your Password?') }}
            </a>
            @endif
            <p class="text-sm font-light text-gray-500 dark:text-gray-400">

            </p>
        </form>
    </div>
</div>

@endsection