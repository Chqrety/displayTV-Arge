@extends('layouts.main')

@section('content')
    <section class="flex h-screen w-full items-center justify-center">
        <div class="flex gap-5">
            <form action="{{ route('antrian.store') }}" method="POST">
                @csrf
                <div class="grid grid-rows-2 items-center rounded-lg bg-zinc-200 px-10 py-5">
                    <div class="row-span-2 mb-10">
                        <span class="text-3xl font-bold">Auto Generate Text</span>
                    </div>
                    <div class="mb-5 flex gap-x-10">
                        <div class="flex flex-col">
                            <label for="no_antrian">Nomor Antrian</label>
                            <input class="rounded-lg border border-black bg-zinc-200 px-3 py-2 focus:outline-none"
                                id="no_antrian" name="no_antrian" type="text">
                        </div>
                        <div class="flex flex-col">
                            <label for="no_poli">Nomor Poli</label>
                            <input class="rounded-lg border border-black bg-zinc-200 px-3 py-2 focus:outline-none"
                                id="no_poli" name="no_poli" type="text">
                        </div>
                    </div>
                    <div>
                        <button
                            class="hidden rounded-lg bg-[#A1C398] px-3 py-2 transition-all hover:scale-105 hover:bg-lime-800 hover:text-white"
                            id="generate_button" type="button">
                            <span class="text-xl font-semibold">Generate</span>
                        </button>
                        <button
                            class="hidden rounded-lg bg-[#A1C398] px-3 py-2 transition-all hover:scale-105 hover:bg-lime-800 hover:text-white"
                            type="submit">
                            <span class="text-xl font-semibold">Store<span>
                        </button>
                        <button
                            class="rounded-lg bg-[#A1C398] px-3 py-2 transition-all hover:scale-105 hover:bg-lime-800 hover:text-white"
                            id="generate_button_api" type="button">
                            <span class="text-xl font-semibold">Generate To Store API</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <script>
        document.getElementById('generate_button').addEventListener('click', function() {
            const randomAntrian = generateRandomAntrian();
            const randomPoli = generateRandomPoli();

            document.getElementById('no_antrian').value = randomAntrian;
            document.getElementById('no_poli').value = randomPoli;
        });

        function generateRandomAntrian() {
            const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            const randomLetter = letters[Math.floor(Math.random() * letters.length)];
            const randomNumber = String(Math.floor(Math.random() * 99) + 1).padStart(2, '0'); // 01-99
            return `${randomLetter}.${randomNumber}`;
        }

        function generateRandomPoli() {
            return Math.floor(Math.random() * 9) + 1; // 1-9
        }

        document.getElementById('generate_button_api').addEventListener('click', function() {
            fetch('/antrian/generate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // pastikan untuk menyertakan CSRF token jika menggunakan Laravel
                    },
                    body: JSON.stringify({})
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('no_antrian').value = data.nomor_antrian;
                    document.getElementById('no_poli').value = data.poli;
                })
                .catch(error => console.error('Error:', error));
        });
    </script>
@endsection
