@extends('layouts.app')

@section('title', 'Login – Barangay Bulletin – Purok Perez')

@section('content')
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="text-xl font-bold mb-4">Login</h1>

        {{-- POST to AuthController@login; @csrf adds the hidden token Laravel checks on every POST --}}
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium mb-1">Email</label>
                {{-- old('email') refills the email after a failed login --}}
                <input id="email" type="email" name="email" value="{{ old('email') }}"
                       class="w-full rounded border border-gray-300 px-3 py-2">
                {{-- "Wrong email or password." from the controller --}}
                @error('email')
                    <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium mb-1">Password</label>
                {{-- no old() on purpose: passwords are never sent back to the page --}}
                <input id="password" type="password" name="password"
                       class="w-full rounded border border-gray-300 px-3 py-2">
            </div>

            <button class="w-full rounded bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2">Login</button>
        </form>
    </div>
@endsection
