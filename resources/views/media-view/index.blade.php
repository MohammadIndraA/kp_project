<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TV Media</title>
    <link href="{{ asset('css/styleTv.css') }}" rel="stylesheet" type="text/css">
</head>

<body>
    <div class="container">
        <div class="header">
            <h1 id="title"></h1>
        </div>
        <div class="content">
            <div class="media">
                <div class="carousel" id="carousel"></div>
            </div>
            <div class="description">
                <p id="description"></p>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const data = @json($videos); // Ambil data dari controller
            const carouselContainer = document.getElementById('carousel');
            const titleElement = document.getElementById('title');
            const descriptionElement = document.getElementById('description');

            let currentIndex = 0;

            function loadMedia() {
                const currentData = data[currentIndex];

                // Set judul dan deskripsi
                titleElement.textContent = currentData.judul;
                descriptionElement.textContent = currentData.deskripsi;

                // Bersihkan konten carousel sebelumnya
                carouselContainer.innerHTML = '';

                currentData.multimedias.forEach(media => {
                    let mediaElement;

                    if (media.path.endsWith('.mp4')) {
                        mediaElement = document.createElement('video');
                        mediaElement.controls = true;
                        mediaElement.autoplay = false;
                        mediaElement.muted = true;

                        const sourceElement = document.createElement('source');
                        sourceElement.src = `/storage/${media.path}`;
                        sourceElement.type = 'video/mp4';
                        mediaElement.appendChild(sourceElement);
                    } else {
                        mediaElement = document.createElement('img');
                        mediaElement.src = `/storage/${media.path}`;
                        mediaElement.alt = currentData.judul;
                    }

                    // Tambahkan class 'carousel-item'
                    mediaElement.classList.add('carousel-item');
                    carouselContainer.appendChild(mediaElement);
                });

                startCarousel();
            }

            function startCarousel() {
                const items = document.querySelectorAll('.carousel-item');
                let carouselIndex = 0;
                let isVideoPlaying = false;

                function showNextItem() {
                    if (isVideoPlaying) return;

                    items[carouselIndex].classList.remove('active');

                    if (items[carouselIndex].tagName === 'VIDEO') {
                        items[carouselIndex].pause();
                        items[carouselIndex].currentTime = 0;
                    }

                    carouselIndex = (carouselIndex + 1) % items.length;
                    items[carouselIndex].classList.add('active');

                    if (items[carouselIndex].tagName === 'VIDEO') {
                        items[carouselIndex].play();
                        isVideoPlaying = true;

                        items[carouselIndex].addEventListener('ended', () => {
                            isVideoPlaying = false;
                            showNextItem();
                        }, {
                            once: true
                        });
                    }
                }

                setInterval(() => {
                    if (!isVideoPlaying) {
                        showNextItem();
                    }
                }, 4000); // Ganti setiap 4 detik

                // Tampilkan item pertama
                items[carouselIndex].classList.add('active');
                if (items[carouselIndex].tagName === 'VIDEO') {
                    items[carouselIndex].play();
                    isVideoPlaying = true;
                    items[carouselIndex].addEventListener('ended', () => {
                        isVideoPlaying = false;
                        showNextItem();
                    }, {
                        once: true
                    });
                }
            }

            // Jalankan fungsi pertama kali
            loadMedia();

            // Pindah ke data berikutnya setiap 3 menit
            setInterval(() => {
                currentIndex = (currentIndex + 1) % data.length;
                loadMedia();
            }, 3000); // 1 menit
        });
    </script>
</body>

</html>
