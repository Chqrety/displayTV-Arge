@extends('layouts.main') @section('content')
    <section class="flex h-screen flex-col">
        <div class="fixed right-5 top-5 flex gap-2 rounded-lg bg-black/30 p-4 text-white">
            <div class="flex flex-col text-right">
                <span class="text-xl font-medium">{{ now()->translatedFormat('l') }}</span>
                <span class="text-xl font-medium">{{ now()->format('d-m-Y') }}</span>
            </div>
            <div class="text-6xl font-bold" id="clock"></div>
        </div>
        <div class="flex h-full">
            <div class="flex w-full flex-col">
                <div class="bg-gradient-to-r from-black/50 px-5 py-5">
                    <span class="text-8xl font-bold text-white">LOGO</span>
                </div>
                <div class="h-full">
                    <div class="grid h-full grid-cols-3 grid-rows-2 gap-2 p-5">
                        <div class="flex items-center justify-center rounded-lg bg-black/30">
                            <span class="text-8xl font-bold text-gray-50">LOKET <span
                                    class="text-yellow-300">C</span></span>
                        </div>
                        <div class="col-span-2 row-span-2 flex flex-col rounded-lg bg-black/30">
                            <div class="w-full p-2">
                                <span class="text-xl font-medium text-white">Antrian Selanjutnya</span>
                            </div>
                            <div class="flex h-full flex-col justify-around gap-5 px-5 py-3">
                                @for ($j = 1; $j <= 5; $j++)
                                    <div class="flex h-full w-full items-center justify-around bg-white/5 text-white">
                                        <span class="text-7xl font-medium">C.05</span>
                                        <span class="text-3xl font-medium">Poli 3</span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 rounded-lg bg-black/30 px-5 py-2 text-center">
                            <div>
                                <span class="text-xl font-bold text-white">NOMOR ANTRIAN</span>
                            </div>
                            <div class="bg-white/15 flex h-5/6 flex-col items-center justify-center rounded-lg text-white">
                                <span class="text-9xl font-bold text-yellow-300" id="no_antrian">C.10</span>
                                <span class="text-3xl font-medium" id="nama_poli">POLI 5</span>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="mt-4 rounded bg-white/10 p-2 text-white" id="playButton">Play</button>
            </div>
        </div>
        <div class="w-full overflow-hidden bg-black/30 py-1">
            <div class="flex animate-marquee space-x-8 whitespace-nowrap">
                @foreach ($messages as $message)
                    <span class="text-xl font-medium text-white">{{ $message }}</span>
                @endforeach
            </div>
        </div>
    </section>
    <script>
        function updateClock() {
            const clockElement = document.getElementById("clock");
            const now = new Date();
            const hours = String(now.getHours()).padStart(2, "0");
            const minutes = String(now.getMinutes()).padStart(2, "0");
            const seconds = String(now.getSeconds()).padStart(2, "0");
            clockElement.textContent = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateClock, 1000);
        updateClock();

        function splitText() {
            var no_antrian = document.getElementById('no_antrian').textContent;
            var nama_poli = document.getElementById('nama_poli').textContent;

            // Split no_antrian menjadi elemen individu lalu filter dot
            var no_antrian_elements = no_antrian.split('').filter(char => char !== '.');

            // buat variable penguraian elemen no_antrian
            var parsedElements = [];
            for (let i = 0; i < no_antrian_elements.length; i++) {
                let current = no_antrian_elements[i];
                let next = no_antrian_elements[i + 1];
                let prev = no_antrian_elements[i - 1];

                // Handle satuan
                if (current === '0') {
                    parsedElements.push(current, next)
                    i++;
                } else if (current === '1') { // Handle belasan
                    if (next === '0') {
                        parsedElements.push('10');
                        i++;
                    } else if (next === '1') {
                        parsedElements.push('11');
                        i++;
                    } else {
                        parsedElements.push(next, 'belas');
                        i++;
                    }
                } else if (current >= '2' && current <= '9') { // Handle puluhan
                    if (next === '0') {
                        parsedElements.push(current, 'puluh');
                        i++;
                    } else {
                        if (next !== undefined) {
                            parsedElements.push(current, 'puluh', next);
                            i++;
                        } else {
                            parsedElements.push(current);
                        }
                    }

                } else { // Handle variable
                    parsedElements.push(current);
                }
            }

            // Split nama_poli dari jarak menjadi kata yang terpisah
            var nama_poli_elements = nama_poli.split(' ');

            // gabungkan antara array tadi
            var textToSpeech = [...parsedElements, ...nama_poli_elements];

            console.log(textToSpeech);
            return textToSpeech;
        }

        function playSpeech() {
            var elements = splitText();
            var path = 'http://127.0.0.1:8000/assets/google_voices/';

            function playSequentially(index) {
                if (index < elements.length) {
                    var audio = new Audio(path + elements[index] + '.mp3');
                    audio.play().then(() => {
                        audio.onended = function() {
                            playSequentially(index + 1);
                        };
                    }).catch(error => {
                        console.log('Error playing audio:', error);
                    });
                }
            }

            // mulai mainkan elemen audio
            playSequentially(0);
        }

        // Add event listener to the play button
        document.getElementById('playButton').addEventListener('click', function() {
            playSpeech();
        });
    </script>
@endsection
