<x-layouts.app>
    <x-slot:title>
        Jadwal Praktikum Hari Ini
    </x-slot:title>

    <x-data-table url="{{ route('api.today-schedules') }}" action-url="schedule/" :columns="[
        ['label' => 'Name', 'field' => 'practicum_name'],
        ['label' => 'Dosen', 'field' => 'dosen'],
        ['label' => 'Laboratorium', 'field' => 'laboratorium_name'],
        ['label' => 'Asisten', 'field' => 'assistant_names'],
        ['label' => 'Jadwal', 'field' => 'jadwal'],
    ]" :has-actions="false"
        table-id="today-schedule-table" has-assistant="true" />
</x-layouts.app>
