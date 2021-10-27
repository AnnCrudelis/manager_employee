@extends('layouts.app')

@section('content')
    <div class="w-4/5 m-auto text-center">
        <div class="py-15 border-b border-gray-200">
            <h1 class="text-6xl">
                Blog Posts
            </h1>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="w-4/5 m-auto mt-10 pl-2">
            <p class="w-2/6 mb-4 text-gray-50 bg-green-500 rounded-2xl py-4" style="text-align: center">
                {{ session()->get('message') }}
            </p>
        </div>
    @endif

    @if (Auth::check())
        @can('publish articles')
        <div class="pt-15 w-4/5 m-auto">
            <a
                href="/users/create"
                class="bg-blue-500 uppercase bg-transparent text-gray-100 text-xs font-extrabold py-3 px-5 rounded-3xl">
                Register employee
            </a>
        </div>
        @endcan
    @endif


    @foreach ($users as $user)
        <div class="sm:grid grid-cols-2 gap-20 w-4/5 mx-auto py-15 border-b ">
            <div>
                    <span class="font-bold italic text-gray-800">{{ $user->id }}</span>
                    <span class="font-bold italic text-gray-800">{{ $user->name }}</span>
                    <span class="font-bold italic text-gray-800">{{ $user->email }}</span>
                    <span class="font-bold italic text-gray-800">{{ $user->email_verified_at }}</span>
            </div>
        </div>
    @endforeach


@endsection
