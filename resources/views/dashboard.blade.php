<x-layouts.app>
    <x-slot:title>
        Jadwal Praktikum Hari Ini
    </x-slot:title>

    <x-data-table url="{{ route('api.today-schedules') }}" action-url="schedule/" :columns="[
        ['label' => 'Name', 'field' => 'practicum_name', 'sortable' => true],
        ['label' => 'Dosen', 'field' => 'dosen', 'sortable' => true],
        ['label' => 'Laboratorium', 'field' => 'laboratorium_name', 'sortable' => true],
        ['label' => 'Asisten', 'field' => 'assistant_names', 'sortable' => true],
        ['label' => 'Jadwal', 'field' => 'jadwal', 'sortable' => true],
    ]" :has-actions="false"
        table-id="today-schedule-table" has-assistant="true" />
</x-layouts.app>
