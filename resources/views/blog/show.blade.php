@extends('layouts.app')

@section('content')
    <div class="w-4/5 m-auto text-left">
        <div class="py-15">
            <h1 class="text-6xl">
                {{ $post->title }}
            </h1>
        </div>
    </div>


    <div class="w-4/5 m-auto pt-20">
        @foreach($post->category as $category)
            <a href="{{route('category', $category->id)}}" class="text-gray-700 font-bold text-3xl pb-4">
                {{ $category->name }}
            </a>
        @endforeach

    <span class="text-gray-500">
        By <span class="font-bold italic text-gray-800">{{ $post->user->name }}</span>, Created on {{ date('jS M Y', strtotime($post->updated_at)) }}
    </span>
        <img src="{{ asset('images/' . $post->image_path) }}" alt="" class="gap-20 w-3/5 mx-auto py-15">
        <p class="text-xl text-gray-700 pt-8 pb-10 leading-8 font-light">
            {{ $post->description }}
        </p>
    </div>

@endsection
