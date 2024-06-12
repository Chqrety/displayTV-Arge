@extends('layouts.main')

@section('content')
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
                                <span class="text-xl font-medium text-white">Riwayat Antrian</span>
                            </div>
                            <div class="flex h-full flex-col justify-around gap-5 px-5 py-3">
                                @for ($j = 1; $j <= 5; $j++)
                                    <div class="flex h-full w-full items-center justify-around bg-white/5 text-white">
                                        <span class="text-7xl font-medium" id="no_antrian_{{ $j }}"></span>
                                        <span class="text-3xl font-medium" id="no_poli_{{ $j }}"></span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 rounded-lg bg-black/30 px-5 py-2 text-center">
                            <div>
                                <span class="text-xl font-bold text-white" id="text_no_antrian">NOMOR ANTRIAN</span>
                            </div>
                            <div class="bg-white/15 flex h-5/6 flex-col items-center justify-center rounded-lg text-white">
                                <span class="text-9xl font-bold text-yellow-300" id="no_antrian_now">C.56</span>
                                <span class="text-3xl font-medium" id="no_poli_now">POLI 5</span>
                            </div>
                        </div>
                    </div>
                </div>
                <button class="mt-4 hidden rounded bg-white/10 p-2 text-white" id="playButton">Play</button>
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

        var base_url = '{{ $baseUrl }}';
        var urlAPI = base_url + '/antrian/data';

        async function fetchDataAntrian() {
            try {
                const response = await fetch(urlAPI);
                if (!response.ok) {
                    throw new Error('Failed to fetch data');
                }
                const data = await response.json();
                updateUI(data);
            } catch (error) {
                console.error(error);
            }
        }

        function updateUI(data) {
            const reversedData = data.reverse();
            const firstFiveData = reversedData.slice(0, 5);

            for (let i = 0; i < firstFiveData.length; i++) {
                const item = firstFiveData[i];
                const noAntrianSpan = document.getElementById(`no_antrian_${i + 1}`);
                const noPoliSpan = document.getElementById(`no_poli_${i + 1}`);

                if (noAntrianSpan && noPoliSpan) {
                    noAntrianSpan.textContent = item.no_antrian;
                    noPoliSpan.textContent = `POLI ${item.no_poli}`;
                }
            }

            const newestData = firstFiveData[0];
            document.getElementById('no_antrian_now').textContent = newestData.no_antrian;
            document.getElementById('no_poli_now').textContent = `POLI ${newestData.no_poli}`;
        }

        // Panggil fetchDataAntrian() untuk pertama kali
        fetchDataAntrian();

        // Atur polling untuk memperbarui data setiap beberapa detik
        setInterval(fetchDataAntrian, 1000); // Contoh: polling setiap 1 detik

        function splitText() {
            var text_no_antrian = document.getElementById('text_no_antrian').textContent;
            var no_antrian_now = document.getElementById('no_antrian_now').textContent;
            var no_poli_now = document.getElementById('no_poli_now').textContent;

            // Split no_antrian menjadi elemen individu lalu filter dot
            var no_antrian_elements = no_antrian_now.split('').filter(char => char !== '.');

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
                        parsedElements.push(current, next);
                        i++;
                    }

                } else { // Handle variable
                    parsedElements.push(current);
                }
            }

            // Split no_poli_now dari jarak menjadi kata yang terpisah
            var no_poli_now_elements = no_poli_now.split(' ');
            if (no_poli_now_elements.length === 2) {
                no_poli_now_elements = [`ke ${no_poli_now_elements[0]}`, no_poli_now_elements[1]];
            }

            // gabungkan antara array tadi
            var textToSpeech = [text_no_antrian, ...parsedElements, ...no_poli_now_elements];

            console.log(textToSpeech);
            return textToSpeech;
        }

        function playSpeech() {
            var elements = splitText();
            var path = base_url + '/assets/google_voices/';
            var playButton = document.getElementById('playButton');

            // matikan button ketika sedang menjalankan audio
            playButton.disabled = true;

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
                } else {
                    // aktifkan button ketika audio telah selesai
                    playButton.disabled = false;
                }
            }

            // mulai audio
            playSequentially(0);
        }

        // Fungsi untuk membuat observer
        function createObserver() {
            const targetNode = document.getElementById('no_antrian_now');

            // Buat instance MutationObserver
            const observer = new MutationObserver((mutationsList, observer) => {
                // Periksa setiap mutasi yang terjadi
                for (const mutation of mutationsList) {
                    if (mutation.type === 'childList' || mutation.type === 'characterData') {
                        // Panggil fungsi playAudio ketika ada perubahan pada span
                        // playAudio();

                        // Otomatis klik tombol playButton
                        const playButton = document.getElementById('playButton');
                        playButton.click();
                    }
                }
            });

            // Konfigurasi observer
            const config = {
                attributes: true,
                childList: true,
                subtree: true
            };

            // Mulai mengamati target node untuk perubahan
            observer.observe(targetNode, config);

        }

        // Panggil fungsi createObserver() untuk memulai pemantauan
        createObserver();

        // menambahkan event listener untuk button
        document.getElementById('playButton').addEventListener('click', function() {
            playSpeech();
        });
    </script>
@endsection
