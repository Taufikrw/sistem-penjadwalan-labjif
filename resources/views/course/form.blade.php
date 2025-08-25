<form id="course-form"
    action="
        @isset($courseItem)
            {{ route('course.update', [$courseItem->id]) }}
        @else
            {{ route('course.store') }}
        @endisset"
    method="POST">
    @csrf
    @if (isset($courseItem))
        @method('PUT')
    @endif

    <div id="title-hidden" class="hidden">
        <div class="flex gap-3 items-center">
            @isset($courseItem)
                <x-heroicon-s-pencil-square class="w-4 h-4" />
                <span>Edit Jadwal Kuliah</span>
            @else
                <x-heroicon-s-plus class="w-4 h-4" />
                <span>Tambah Jadwal Kuliah</span>
            @endisset
        </div>
    </div>

    <div class="grid grid-cols-2 gap-4">
        <div class="col-span-2">
            <x-input-label for="course" class="mb-1 text-sm" value="Mata Kuliah" />
            <x-text-input name="course" id="course" class="w-full" :value="old('course', isset($courseItem) ? $courseItem->course : '')"
                placeholder="Masukkan nama mata kuliah" required />
        </div>

        <div>
            <x-input-label for="name" class="mb-1 text-sm" value="Kelas" />
            <x-text-input name="name" id="name" class="w-full" :value="old('name', isset($courseItem) ? $courseItem->name : '')"
                placeholder="Masukkan nama kelas (IF/SI-X)" required />
        </div>

        <div>
            <x-input-label for="day" class="mb-1 text-sm" value="Hari" />
            <x-select-input id="day" name="day" :options="collect(App\Enums\Day::cases())
                ->mapWithKeys(fn($day) => [$day->value => $day->value])
                ->toArray()" placeholder="Pilih hari" :selected="old('day', isset($courseItem) ? $courseItem->day->value : null)"
                required />
        </div>

        <div>
            <x-input-label for="start_time" class="mb-1 text-sm" value="Jam Mulai" />
            <x-text-input type="time" name="start_time" id="start_time" class="w-full" :value="old('start_time', isset($courseItem) ? $courseItem->start_time->format('H:i') : '')" required />
        </div>

        <div>
            <x-input-label for="end_time" class="mb-1 text-sm" value="Jam Selesai" />
            <x-text-input type="time" name="end_time" id="end_time" class="w-full" :value="old('end_time', isset($courseItem) ? $courseItem->end_time->format('H:i') : '')" required />
        </div>

        <input type="hidden" name="owner" id="owner" value="{{ $assistant->nim }}">
    </div>

    <div class="mt-8 flex justify-between items-center w-full">
        <x-button-secondary class="rounded-xl py-3 px-6" type="button" id="btn-cancel-course" onclick="closeModal()">
            Batal
        </x-button-secondary>
        <x-button-primary class="rounded-xl py-3 px-6" type="submit" id="btn-submit-course">
            Simpan
        </x-button-primary>
    </div>
</form>

<script type="module">
    $(document).ready(function() {
        const title = $('#title-hidden').html();
        $('#title').html(title);

    });
</script>
