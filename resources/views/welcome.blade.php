<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reformasi Birokrasi Indonesia</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
        .bg-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%239C92AC' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center bg-pattern">
    <div class="container mx-auto px-4">
        <div class="max-w-6xl mx-auto bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="md:flex">
                <div class="md:flex-1 p-8 bg-[#0f766e] text-white flex flex-col justify-center items-start">
                    <h2 class="text-3xl font-bold mb-4">Reformasi Birokrasi</h2>
                    <p class="text-lg mb-6">Menuju pemerintahan yang efisien, transparan, dan berorientasi pada pelayanan publik.</p>
                    <button id="learnMore" class="bg-white text-[#0f766e] font-semibold py-2 px-6 rounded-full hover:bg-opacity-90 transition duration-300">
                        Pelajari Lebih Lanjut
                    </button>
                </div>
                <div class="md:flex-1 p-8">
                    <div class="text-right mb-8">
                        <a href="{{ route('login') }}">
                            <button id="loginBtn" class="bg-[#0f766e] hover:bg-opacity-90 text-white font-bold py-2 px-6 rounded-full transition duration-300">
                                Login
                            </button>
                        </a>
                        
                    </div>
                    <h1 class="text-4xl font-bold text-gray-800 mb-4">Transformasi Digital Birokrasi Indonesia</h1>
                    <p class="text-lg text-gray-600 mb-6">Kami berkomitmen untuk menciptakan sistem pemerintahan yang modern, efektif, dan responsif terhadap kebutuhan masyarakat.</p>
                    <div class="flex space-x-4 mb-8">
                        <div class="flex-1 bg-gray-100 p-4 rounded-lg">
                            <h3 class="font-semibold text-lg mb-2">Efisiensi</h3>
                            <p class="text-gray-600">Mengoptimalkan proses kerja untuk pelayanan yang lebih cepat</p>
                        </div>
                        <div class="flex-1 bg-gray-100 p-4 rounded-lg">
                            <h3 class="font-semibold text-lg mb-2">Transparansi</h3>
                            <p class="text-gray-600">Menjamin akses informasi dan akuntabilitas publik</p>
                        </div>
                        <div class="flex-1 bg-gray-100 p-4 rounded-lg">
                            <h3 class="font-semibold text-lg mb-2">Inovasi</h3>
                            <p class="text-gray-600">Menerapkan solusi teknologi untuk peningkatan layanan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', (event) => {
            gsap.from(".container > div", {duration: 1, y: 50, opacity: 0, ease: "power3.out"});
            gsap.from("#loginBtn", {duration: 0.5, y: -20, opacity: 0, ease: "back.out(1.7)", delay: 0.5});
            gsap.from(".bg-gray-100", {duration: 0.5, scale: 0.9, opacity: 0, ease: "power2.out", stagger: 0.2, delay: 0.7});
        });
    </script>
</body>
</html>