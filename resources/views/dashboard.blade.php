@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="w-3/4 p-6">
            <h1 class="text-red-500 text-lg">Dashboard</h1>
            <h2>{{ $user->username }}</h2>
        </div>
    </div>
@endsection
