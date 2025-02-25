@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <h1>Dashboard</h1>

    <h2>My Messages</h2>
    <ul>
        @foreach ($messages as $message)
            <li>
                <strong>{{ $message->title }}</strong>: {{ $message->content }}
            </li>
        @endforeach
    </ul>
@endsection
