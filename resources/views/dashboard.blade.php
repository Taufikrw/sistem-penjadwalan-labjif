@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="flex">
        <x-sidebar />

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <x-topbar />

            <div class="p-10 flex-1">
                <h1 class="text-3xl font-bold mb-6">Jadwal Praktikum Hari Ini</h1>

                <x-data-table url="{{ route('api.today-schedules') }}" action-url="schedule/"
                    :columns="[
                        ['label' => 'Name', 'field' => 'practicum_name'],
                        ['label' => 'Dosen', 'field' => 'dosen'],
                        ['label' => 'Laboratorium', 'field' => 'laboratorium_name'],
                        ['label' => 'Asisten', 'field' => 'assistant_names'],
                        ['label' => 'Jadwal', 'field' => 'jadwal'],
                    ]"
                    :has-actions="false" table-id="today-schedule-table" has-assistant="true" />
            </div>
        </div>
    @endsection
