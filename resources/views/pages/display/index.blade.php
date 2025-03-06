@extends('layouts.main')

@section('content')
    <section class="flex h-screen flex-col">
        <div class="fixed right-5 top-5 flex gap-2 rounded-lg bg-black/30 p-4 text-white">
            <div class="flex flex-col text-right">
                <span class="text-xl font-medium" id="nama-hari"></span>
                <span class="text-xl font-medium" id="tanggal"></span>
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
                            <div class="flex h-full flex-col justify-around gap-5 px-5 py-3" id="riwayat_antrian">
                                @for ($j = 1; $j <= 5; $j++)
                                    <div class="flex h-full w-full items-center justify-around bg-white/5 text-white">
                                        <span class="text-7xl font-medium" id="no_antrian_{{ $j }}"></span>
                                        <span class="text-7xl font-medium" id="no_antrian_rm_{{ $j }}"></span>
                                        <span class="text-3xl font-medium" id="no_poli_{{ $j }}"></span>
                                    </div>
                                @endfor
                            </div>
                        </div>
                        <div class="flex flex-col gap-2 rounded-lg bg-black/30 px-5 py-2 text-center">
                            <div>
                                <span class="text-xl font-bold text-white" id="text_no_antrian">NOMOR ANTRIAN</span>
                            </div>
                            <div class="bg-white/15 flex h-5/6 flex-col items-center justify-center rounded-lg text-white"
                                id="no_antrian_display">
                            </div>
                        </div>
                    </div>
                </div>
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
        function updateTanggalHari() {
            // Daftar nama hari dalam bahasa Indonesia
            const namaHariIndonesia = [
                'Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'
            ];

            // Daftar nama bulan dalam bahasa Indonesia
            const namaBulanIndonesia = [
                'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
                'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
            ];

            // Mendapatkan tanggal saat ini
            const today = new Date();

            // Mendapatkan nama hari dalam bahasa Indonesia berdasarkan indeks hari (0 untuk Minggu, 1 untuk Senin, dst.)
            const namaHari = namaHariIndonesia[today.getDay()];

            // Mendapatkan nama bulan dalam bahasa Indonesia berdasarkan indeks bulan (0 untuk Januari, 1 untuk Februari, dst.)
            const namaBulan = namaBulanIndonesia[today.getMonth()];

            // Mendapatkan tanggal dalam format 'd NamaBulan Y'
            const tanggal = today.getDate() + ' ' + namaBulan + ' ' + today.getFullYear();

            // Mengatur teks inner HTML dari elemen span
            document.getElementById('nama-hari').innerHTML = namaHari;
            document.getElementById('tanggal').innerHTML = tanggal;
        }

        // Memanggil fungsi updateTanggalHari() untuk pertama kali saat halaman dimuat
        updateTanggalHari();

        // Mengatur agar fungsi updateTanggalHari() dijalankan setiap 24 jam (86400000 milidetik)
        setInterval(updateTanggalHari, 86400000);

        function updateClock() {
            const now = new Date();
            const clockElement = document.getElementById("clock");
            const hours = String(now.getHours()).padStart(2, "0");
            const minutes = String(now.getMinutes()).padStart(2, "0");
            const seconds = String(now.getSeconds()).padStart(2, "0");
            clockElement.textContent = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateClock, 1000);
        updateClock();

        var base_url = '{{ $baseUrl }}';
        var urlAPI = base_url + '/api/antrian/tv';

        // window.addEventListener('load', function() {
        //     // Fetch untuk menghapus data pada API saat halaman dimuat ulang
        //     fetch(urlAPI, {
        //             method: 'DELETE',
        //             headers: {
        //                 'Content-Type': 'application/json',
        //                 'X-CSRF-TOKEN': '{{ csrf_token() }}' // Pastikan untuk menyertakan token CSRF jika menggunakan Laravel
        //             }
        //         })
        //         .then(response => {
        //             if (response.ok) {
        //                 console.log('API data cleared successfully.');
        //             } else {
        //                 console.error('Failed to clear API data.');
        //             }
        //         })
        //         .catch(error => console.error('Error:', error));
        // });

        async function getDataAndUpdateUI() {
            const getURL = '{{ route('antrian.tv.get') }}';

            try {
                const response = await fetch(getURL);
                if (!response.ok) {
                    throw new Error('Failed to fetch data from GET route');
                }
                const data = await response.json();

                updateNoAntrianDisplay(data)

            } catch (error) {
                console.error(error);
            }
        }

        // Fungsi untuk memulai polling
        function startPolling() {
            // Atur interval polling (misalnya, setiap 1 detik)
            setInterval(getDataAndUpdateUI, 1000); // Polling setiap 1 detik
        }

        // Panggil startPolling() untuk memulai polling
        startPolling();

        // function updateNoAntrianDisplay(data) {
        //     const latestData = data[0];
        //     const firstFiveData = data.slice(0, 5);
        //     firstFiveData.forEach((item, index) => {
        //         item.id = index + 1; // Menambahkan properti id ke setiap elemen data

        //         // Dapatkan elemen div
        //         const riwayat_antrian = document.getElementById('riwayat_antrian');
        //         const noAntrianDisplay = document.getElementById('no_antrian_display');

        //         if (latestData.status === "poli") {
        //             const noAntrianSpan = document.getElementById(`no_antrian_${item.id}`);
        //             const noAntrianRMSpan = document.getElementById(`no_antrian_rm_${item.id}`);
        //             const noPoliSpan = document.getElementById(`no_poli_${item.id}`);


        //             if (noAntrianSpan && noAntrianRMSpan && noPoliSpan) {
        //                 noAntrianSpan.textContent = item.no_antrian;
        //                 noAntrianRMSpan.textContent = item.no_antrian_rm;
        //                 noPoliSpan.textContent = item.no_poli;
        //             }

        //             noAntrianDisplay.innerHTML = `
    //         <span id='no_antrian_latest' class="text-9xl font-bold text-yellow-300">${latestData.no_antrian}</span>
    //         <span id='no_poli_latest' class="text-3xl font-medium">${latestData.no_poli}</span>
    //         `;

        //         } else if (latestData.status === "rekam medis") {
        //             const noAntrianSpan = document.getElementById(`no_antrian_${item.id}`);
        //             const noAntrianRMSpan = document.getElementById(`no_antrian_rm_${item.id}`);
        //             const noPoliSpan = document.getElementById(`no_poli_${item.id}`);


        //             if (noAntrianSpan && noAntrianRMSpan && noPoliSpan) {
        //                 noAntrianSpan.textContent = item.no_antrian;
        //                 noAntrianRMSpan.textContent = item.no_antrian_rm;
        //                 noPoliSpan.textContent = item.no_poli;
        //             }
        //             noAntrianDisplay.innerHTML = `
    //         <span id='no_antrian_rm_latest' class="text-9xl font-bold text-yellow-300">${latestData.no_antrian_rm}</span>
    //         `;
        //         } else {
        //             noAntrianDisplay.innerHTML = `
    //         <span class="text-xl font-medium">Tidak ada status</span>
    //         `;
        //         }
        //     });
        // }

        function updateNoAntrianDisplay(data) {
            // Ambil 5 data unik dari data yang diterima
            const uniqueData = Array.from(new Set(data.map(item => item.no_antrian + item.no_antrian_rm + item.no_poli)))
                .slice(0, 5)
                .map(key => data.find(item => item.no_antrian + item.no_antrian_rm + item.no_poli === key));

            // Ambil data terbaru dari data yang sudah difilter
            const latestData = uniqueData[uniqueData.length - 1];

            // Dapatkan elemen div
            const riwayat_antrian = document.getElementById('riwayat_antrian');
            const noAntrianDisplay = document.getElementById('no_antrian_display');

            if (!latestData) {
                noAntrianDisplay.innerHTML = `
        <span class="text-xl font-medium">Tidak ada data</span>
    `;
                return;
            }

            if (latestData.status === "poli") {
                noAntrianDisplay.innerHTML = `
        <span id='no_antrian_latest' class="text-9xl font-bold text-yellow-300">${latestData.no_antrian}</span>
        <span id='no_poli_latest' class="text-3xl font-medium">${latestData.no_poli}</span>
    `;
            } else if (latestData.status === "rekam medis") {
                noAntrianDisplay.innerHTML = `
        <span id='no_antrian_rm_latest' class="text-9xl font-bold text-yellow-300">${latestData.no_antrian_rm}</span>
    `;
            } else {
                noAntrianDisplay.innerHTML = `
        <span class="text-xl font-medium">Tidak ada status</span>
    `;
            }

            // Loop untuk menampilkan 5 data di riwayat_antrian
            uniqueData.forEach((item, index) => {
                item.id = index + 1; // Menambahkan properti id ke setiap elemen data

                const noAntrianSpan = document.getElementById(`no_antrian_${item.id}`);
                const noAntrianRMSpan = document.getElementById(`no_antrian_rm_${item.id}`);
                const noPoliSpan = document.getElementById(`no_poli_${item.id}`);

                if (noAntrianSpan && noAntrianRMSpan && noPoliSpan) {
                    noAntrianSpan.textContent = item.no_antrian;
                    noAntrianRMSpan.textContent = item.no_antrian_rm;
                    noPoliSpan.textContent = item.no_poli;
                }
            });
        }

        function splitText() {
            if (document.getElementById('no_antrian_latest')) {
                return splitTextPoli();
            } else if (document.getElementById('no_antrian_rm_latest')) {
                return splitTextRM();
            } else {
                return null;
            }
        }

        function splitTextPoli() {
            var text_no_antrian = document.getElementById('text_no_antrian').textContent;
            var no_antrian_now = document.getElementById('no_antrian_latest').textContent;
            var no_poli_now = document.getElementById('no_poli_latest').textContent;

            // Memisahkan no_antrian menjadi elemen individu dan memfilter karakter titik
            var no_antrian_elements = no_antrian_now.split('').filter(char => char !== '.');

            // Array untuk menyimpan elemen yang sudah diproses
            var parsedElements = [];
            for (let i = 0; i < no_antrian_elements.length; i++) {
                let current = no_antrian_elements[i];
                let next = no_antrian_elements[i + 1];
                let prev = no_antrian_elements[i - 1];

                // Menangani satuan
                if (current === '0') {
                    parsedElements.push(current, next);
                    i++;
                } else if (current === '1') { // Menangani belasan
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
                } else if (current >= '2' && current <= '9') { // Menangani puluhan
                    if (next === '0') {
                        parsedElements.push(current, 'puluh');
                        i++;
                    } else {
                        parsedElements.push(current, next);
                        i++;
                    }
                } else { // Menangani nilai lainnya
                    parsedElements.push(current);
                }
            }

            // Memisahkan no_poli_now dengan spasi menjadi elemen yang terpisah
            var no_poli_now_elements = no_poli_now.split(' ');
            if (no_poli_now_elements.length === 2) {
                no_poli_now_elements = [`ke ${no_poli_now_elements[0]}`, no_poli_now_elements[1]];
            }

            // Menggabungkan semua elemen ke dalam array textToSpeech
            var textToSpeech = [text_no_antrian, ...parsedElements, ...no_poli_now_elements];

            return textToSpeech;
        }

        function splitTextRM() {
            var text_no_antrian = document.getElementById('text_no_antrian').textContent;
            var no_antrian_rm_now = document.getElementById('no_antrian_rm_latest').textContent;

            // Memisahkan no_antrian_rm_now dengan spasi menjadi elemen yang terpisah
            var no_antrian_rm_elements = no_antrian_rm_now.split(' ');

            const no_antrian_rm = no_antrian_rm_elements.find(element => !isNaN(element));
            const warna_antrian = no_antrian_rm_elements.find(element => isNaN(element));

            // Array untuk menyimpan elemen yang sudah diproses
            var parsedElements = [];
            for (let i = 0; i < no_antrian_rm.length; i++) {
                let current = no_antrian_rm[i];
                let next = no_antrian_rm[i + 1];
                let prev = no_antrian_rm[i - 1];

                // Menangani satuan
                if (current === '0') {
                    parsedElements.push(current, next);
                    i++;
                } else if (current === '1') { // Menangani belasan
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
                } else if (current >= '2' && current <= '9') { // Menangani puluhan
                    if (next === '0') {
                        parsedElements.push(current, 'puluh');
                        i++;
                    } else {
                        parsedElements.push(current, next);
                        i++;
                    }
                } else { // Menangani nilai lainnya
                    parsedElements.push(current);
                }
            }

            // Menggabungkan semua elemen ke dalam array textToSpeech
            var textToSpeech = [text_no_antrian, ...parsedElements, warna_antrian];

            return textToSpeech;
        }

        function playSpeechPoli() {
            var elements = splitText();
            console.log(elements);
            var path = base_url + '/assets/google_voices/';

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

            // mulai audio
            playSequentially(0);
        }

        // Fungsi untuk membuat observer
        function createObserver() {
            const targetNode = document.getElementById('riwayat_antrian');

            // Simpan nilai awal targetNode untuk membandingkan
            let previousValue = targetNode.textContent.trim();

            // Buat instance MutationObserver
            const observer = new MutationObserver((mutationsList, observer) => {
                // Periksa setiap mutasi yang terjadi
                for (const mutation of mutationsList) {
                    if (
                        mutation.type === 'childList' ||
                        mutation.type === 'characterData' ||
                        (mutation.type === 'attributes' && mutation.attributeName === 'data-value')
                    ) {
                        // Periksa nilai saat ini setelah mutasi
                        const currentValue = targetNode.textContent.trim();

                        // Panggil fungsi playAudio jika nilai berubah atau mutasi terjadi
                        if (currentValue !== previousValue) {
                            playSpeechPoli();
                            previousValue = currentValue; // Update nilai sebelumnya
                        }
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
    </script>
@endsection
