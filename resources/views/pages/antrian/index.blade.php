@extends('layouts.main')

@section('content')
    <section class="flex h-screen w-full items-center justify-center gap-5">
        <form action="{{ route('antrian.store') }}" method="POST">
            @csrf
            <div class="flex gap-5">
                <div class="grid grid-rows-2 items-center rounded-lg bg-zinc-200 px-10 py-5">
                    <div class="row-span-2 mb-10">
                        <span class="text-3xl font-bold">Auto Generate Text Poli</span>
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
                    <div class="flex gap-5">
                        <button
                            class="rounded-lg bg-[#A1C398] px-3 py-2 transition-all hover:scale-105 hover:bg-lime-800 hover:text-white"
                            id="generate_button_api" type="button">
                            <span class="text-xl font-semibold">Generate To Store API</span>
                        </button>
                        <button
                            class="rounded-lg bg-[#A1C398] px-3 py-2 transition-all hover:scale-105 hover:bg-lime-800 hover:text-white"
                            id="store_button_api" type="button">
                            <span class="text-xl font-semibold">Store API</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
        <form action="{{ route('antrian.store') }}" method="POST">
            @csrf
            <div class="flex gap-5">
                <div class="grid grid-rows-2 items-center rounded-lg bg-zinc-200 px-10 py-5">
                    <div class="row-span-2 mb-10">
                        <span class="text-3xl font-bold">Auto Generate Text Rekam Medis</span>
                    </div>
                    <div class="mb-5 flex gap-x-10">
                        <div class="flex flex-col">
                            <label for="no_antrian_rm">Nomor Antrian Rekam Medis</label>
                            <input class="rounded-lg border border-black bg-zinc-200 px-3 py-2 focus:outline-none"
                                id="no_antrian_rm" name="no_antrian_rm" type="text">
                        </div>
                    </div>
                    <div class="flex gap-5">
                        <button
                            class="rounded-lg bg-[#A1C398] px-3 py-2 transition-all hover:scale-105 hover:bg-lime-800 hover:text-white"
                            id="generate_button_api_rm" type="button">
                            <span class="text-xl font-semibold">Generate To Store API</span>
                        </button>
                        <button
                            class="rounded-lg bg-[#A1C398] px-3 py-2 transition-all hover:scale-105 hover:bg-lime-800 hover:text-white"
                            id="store_button_api_rm" type="button">
                            <span class="text-xl font-semibold">Store API</span>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </section>
    <script>
        document.getElementById('store_button_api_rm').addEventListener('click', function() {
            // Generate random nomor antrian dan nomor poli
            // const randomAntrianRM = generateRandomAntrianRM();

            // Masukkan nomor antrian dan nomor poli ke dalam form
            const randomAntrianRM = document.getElementById('no_antrian_rm').value;

            // // Kirim permintaan POST ke URL API untuk menyimpan data
            fetch('{{ route('antrian.tv.data') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        no_antrian_rm: randomAntrianRM,
                        status: 'rekam medis',
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Data stored successfully:', data);
                })
                .catch(error => console.error('Error storing data:', error));
        });

        document.getElementById('generate_button_api_rm').addEventListener('click', function() {
            // Generate random nomor antrian dan nomor poli
            const randomAntrianRM = generateRandomAntrianRM();

            // Masukkan nomor antrian dan nomor poli ke dalam form
            document.getElementById('no_antrian_rm').value = randomAntrianRM;

            // // Kirim permintaan POST ke URL API untuk menyimpan data
            fetch('{{ route('antrian.tv.data') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        no_antrian_rm: randomAntrianRM,
                        status: 'rekam medis',
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Data stored successfully:', data);
                })
                .catch(error => console.error('Error storing data:', error));
        });

        function generateRandomAntrianRM() {
            const choices = ['Merah', 'Biru'];
            const randomChoices = choices[Math.floor(Math.random() * choices.length)];
            const randomNumber = String(Math.floor(Math.random() * 99) + 1).padStart(2, '0'); // 01-99
            return `${randomNumber} ${randomChoices}`;
        }

        document.getElementById('store_button_api').addEventListener('click', function() {

            // Masukkan nomor antrian dan nomor poli ke dalam form
            const randomAntrian = document.getElementById('no_antrian').value;
            const randomPoli = document.getElementById('no_poli').value;

            // // Kirim permintaan POST ke URL API untuk menyimpan data
            fetch('{{ route('antrian.tv.data') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        no_antrian: randomAntrian,
                        no_poli: randomPoli,
                        status: 'poli'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Data stored successfully:', data);
                })
                .catch(error => console.error('Error storing data:', error));
        });

        document.getElementById('generate_button_api').addEventListener('click', function() {
            // Generate random nomor antrian dan nomor poli
            const randomAntrian = generateRandomAntrian();
            const randomPoli = 'POLI ' + generateRandomPoli();

            // Masukkan nomor antrian dan nomor poli ke dalam form
            document.getElementById('no_antrian').value = randomAntrian;
            document.getElementById('no_poli').value = randomPoli;

            // // Kirim permintaan POST ke URL API untuk menyimpan data
            fetch('{{ route('antrian.tv.data') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        no_antrian: randomAntrian,
                        no_poli: randomPoli,
                        status: 'poli'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Data stored successfully:', data);
                })
                .catch(error => console.error('Error storing data:', error));
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
    </script>
@endsection
