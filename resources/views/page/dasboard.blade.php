@extends('component.component-dasboard.body-dasboard')

@section('judul', 'E-RB')

@section('viewer')

    <div class="mt-[5rem]">
        <div class=" w-full px-5">
            <h4 class="text-[3rem] font-bold text-gray-800">Monitoring E-RB</h4>
        </div>
        <main class="container mx-auto mt-8 bg-gray-100 p-8 rounded-lg shadow-lg ">
            <div class="bg-white shadow-lg rounded-lg p-2 w-full  mb-9">
                <h2 class="text-lg font-semibold text-gray-700 mb-5 text-start">Form Pilih Tahun & Periode</h2>

                <form class="space-y-4">
                    <div class="flex flex-wrap -mx-4">
                        <!-- Select Tahun -->
                        <div class="w-full sm:w-1/3 px-4">
                            <label for="selectTahun" class="block text-sm font-medium text-gray-700 mb-2">Pilih Tahun</label>
                            <select id="selectTahun"
                                class="block w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">--Pilih Tahun--</option>
                                <script>
                                    const currentYear = new Date().getFullYear();
                                    document.write(
                                        `<option value="${currentYear}" selected>${currentYear}</option>`
                                        ); // Menambahkan tahun saat ini sebagai opsi terpilih
                                    for (let year = currentYear - 1; year >= 2022; year--) {
                                        document.write(`<option value="${year}">${year}</option>`);
                                    }
                                </script>
                            </select>
                        </div>

                        <!-- Input Periode Awal -->
                        <div class="w-full sm:w-1/3 px-4">
                            <label for="periodeAwal" class="block text-sm font-medium text-gray-700 mb-2">Periode
                                Awal</label>
                            <input type="text" id="periodeAwal" placeholder="Pilih tanggal awal"
                                class="block w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>

                        <!-- Input Periode Akhir -->
                        <div class="w-full sm:w-1/3 px-4">
                            <label for="periodeAkhir" class="block text-sm font-medium text-gray-700 mb-2">Periode
                                Akhir</label>
                            <input type="text" id="periodeAkhir" placeholder="Pilih tanggal akhir"
                                class="block w-full p-2 border border-gray-300 rounded-lg shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    <!-- Clear Button and Submit Button -->
                    <div class="flex justify-start space-x-4 mt-4">
                        <button type="button" id="clearPeriode"
                            class="bg-red-500 text-white p-2 rounded-lg hover:bg-red-600 focus:outline-none focus:ring-2 focus:ring-red-500">Clear
                            Periode</button>
                        <button type="button" id="submitButton"
                            class="bg-indigo-600 text-white p-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Search</button>
                    </div>
                </form>
            </div>

            <div class="flex gap-6 mb-6">
                <div class="w-1/4">
                    <div
                        class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex gap-6 justify-start">
                            <svg class=" w-7 h-7 text-gray-500 dark:text-gray-400 mb-3" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M18 5h-.7c.229-.467.349-.98.351-1.5a3.5 3.5 0 0 0-3.5-3.5c-1.717 0-3.215 1.2-4.331 2.481C8.4.842 6.949 0 5.5 0A3.5 3.5 0 0 0 2 3.5c.003.52.123 1.033.351 1.5H2a2 2 0 0 0-2 2v3a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V7a2 2 0 0 0-2-2ZM8.058 5H5.5a1.5 1.5 0 0 1 0-3c.9 0 2 .754 3.092 2.122-.219.337-.392.635-.534.878Zm6.1 0h-3.742c.933-1.368 2.371-3 3.739-3a1.5 1.5 0 0 1 0 3h.003ZM11 13H9v7h2v-7Zm-4 0H2v5a2 2 0 0 0 2 2h3v-7Zm6 0v7h3a2 2 0 0 0 2-2v-5h-5Z" />
                            </svg>
                            <p id="pemasalahan-count" class="mr-4 text-white font-bold text-2xl">NULL</p>
                        </div>

                        <a href="#">
                            <h5 class="mb-2 text-lg font-semibold tracking-tight text-gray-900 dark:text-white">Pemasalahan
                            </h5>
                        </a>

                    </div>
                </div>
                <div class="w-1/4">
                    <div
                        class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex gap-6 justify-start">
                            <svg class=" w-7 h-7 text-gray-500 dark:text-gray-400 mb-3" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M18 5h-.7c.229-.467.349-.98.351-1.5a3.5 3.5 0 0 0-3.5-3.5c-1.717 0-3.215 1.2-4.331 2.481C8.4.842 6.949 0 5.5 0A3.5 3.5 0 0 0 2 3.5c.003.52.123 1.033.351 1.5H2a2 2 0 0 0-2 2v3a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V7a2 2 0 0 0-2-2ZM8.058 5H5.5a1.5 1.5 0 0 1 0-3c.9 0 2 .754 3.092 2.122-.219.337-.392.635-.534.878Zm6.1 0h-3.742c.933-1.368 2.371-3 3.739-3a1.5 1.5 0 0 1 0 3h.003ZM11 13H9v7h2v-7Zm-4 0H2v5a2 2 0 0 0 2 2h3v-7Zm6 0v7h3a2 2 0 0 0 2-2v-5h-5Z" />
                            </svg>
                            <p id="rencana-aksi-count" class=" mr-4 text-white font-bold text-2xl">NULL</p>
                        </div>
                        <a href="#">
                            <h5 class="mb-2 text-lg font-semibold tracking-tight text-gray-900 dark:text-white">Rencana Aksi
                            </h5>
                        </a>

                    </div>
                </div>
                <div class="w-1/4">
                    <div
                        class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex gap-6 justify-start">
                            <svg class=" w-7 h-7 text-gray-500 dark:text-gray-400 mb-3" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M18 5h-.7c.229-.467.349-.98.351-1.5a3.5 3.5 0 0 0-3.5-3.5c-1.717 0-3.215 1.2-4.331 2.481C8.4.842 6.949 0 5.5 0A3.5 3.5 0 0 0 2 3.5c.003.52.123 1.033.351 1.5H2a2 2 0 0 0-2 2v3a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V7a2 2 0 0 0-2-2ZM8.058 5H5.5a1.5 1.5 0 0 1 0-3c.9 0 2 .754 3.092 2.122-.219.337-.392.635-.534.878Zm6.1 0h-3.742c.933-1.368 2.371-3 3.739-3a1.5 1.5 0 0 1 0 3h.003ZM11 13H9v7h2v-7Zm-4 0H2v5a2 2 0 0 0 2 2h3v-7Zm6 0v7h3a2 2 0 0 0 2-2v-5h-5Z" />
                            </svg>
                            <p id="penyelesaian-count" class=" mr-4 text-white font-bold text-2xl">NULL</p>
                        </div>
                        <a href="#">
                            <h5 class="mb-2 text-lg font-semibold tracking-tight text-gray-900 dark:text-white">Penyelesaian
                            </h5>
                        </a>

                    </div>
                </div>
                <div class="w-1/4">
                    <div
                        class="max-w-sm p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex gap-6 justify-start">
                            <svg class=" w-7 h-7 text-gray-500 dark:text-gray-400 mb-3" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M18 5h-.7c.229-.467.349-.98.351-1.5a3.5 3.5 0 0 0-3.5-3.5c-1.717 0-3.215 1.2-4.331 2.481C8.4.842 6.949 0 5.5 0A3.5 3.5 0 0 0 2 3.5c.003.52.123 1.033.351 1.5H2a2 2 0 0 0-2 2v3a1 1 0 0 0 1 1h18a1 1 0 0 0 1-1V7a2 2 0 0 0-2-2ZM8.058 5H5.5a1.5 1.5 0 0 1 0-3c.9 0 2 .754 3.092 2.122-.219.337-.392.635-.534.878Zm6.1 0h-3.742c.933-1.368 2.371-3 3.739-3a1.5 1.5 0 0 1 0 3h.003ZM11 13H9v7h2v-7Zm-4 0H2v5a2 2 0 0 0 2 2h3v-7Zm6 0v7h3a2 2 0 0 0 2-2v-5h-5Z" />
                            </svg>
                            <p id="anggaran-count" class="mr-4 text-white font-bold text-2xl">NULL</p>
                        </div>
                        <a href="#">
                            <h5 class="mb-2 text-lg font-semibold tracking-tight text-gray-900 dark:text-white">Anggaran
                            </h5>
                        </a>

                    </div>
                </div>
            </div>
            <h2 class="text-2xl font-semibold mb-6 text-gray-800">Grafik Batang Penyelesaian P1 hingga P20</h2>

            <div class="flex gap-6 mb-6">
                <!-- Cluster Dropdown -->
                <div class="w-1/2">
                    <label for="cluster-select" class="block text-lg font-bold mb-2 text-gray-700">Cluster:</label>
                    <select id="cluster-select"
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Cluster</option>
                    </select>
                </div>

                <!-- Tema Dropdown (Hidden by Default) -->
                <div id="theme-dropdown" class="w-1/2 hidden">
                    <label for="theme-select" class="block text-lg font-bold mb-2 text-gray-700">Tema:</label>
                    <select id="theme-select"
                        class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Pilih Tema</option>

                    </select>
                </div>
            </div>

            <!-- Grafik Tema: Dibuat lebih kecil dan responsif -->
            <div id="theme-chart-container" class="chart-container h-96 bg-white rounded-lg shadow-md p-4 hidden">
                <canvas id="myChart" class="w-full h-full"></canvas>

            </div>

            <div id="theme-chart-container2" class="chart-container h-96 bg-white rounded-lg shadow-md p-4 hidden">
                <canvas id="myChart2" class=" w-full h-full"></canvas>
            </div>

            <!-- Dropdown Permasalahan dan Renaksi (Hidden by Default) -->
            <div id="dropdown-container" class="block w-full mt-6 hidden">
                <div class="flex gap-6">
                    <!-- Dropdown Permasalahan -->
                    <div id="problem-dropdown" class="w-1/2">
                        <label for="problem-select"
                            class="block text-lg font-bold mb-2 text-gray-700">Permasalahan:</label>
                        <select id="problem-select"
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Permasalahan</option>

                        </select>
                    </div>

                    <!-- Dropdown Renaksi -->
                    <div id="renaksi-dropdown" class="w-1/2 hidden">
                        <label for="renaksi-select" class="block text-lg font-bold mb-2 text-gray-700">Renaksi:</label>
                        <select id="renaksi-select"
                            class="w-full p-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Pilih Renaksi</option>

                        </select>
                    </div>
                </div>
            </div>

            <!-- Grafik Permasalahan -->
            <div id="problem-chart" class="flex gap-6 w-full mt-6 hidden">
                <!-- Grafik Permasalahan di Kiri -->
                <div class="w-1/2 bg-white rounded-lg shadow-md p-4 h-full">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Grafik Permasalahan:</h3>
                    <div class="chart-container h-96 w-full pb-8">
                        <canvas id="myChartProblem"></canvas>
                    </div>
                    <div class="chart-container h-96 w-full pb-8">
                        <canvas id="myChartProblem2"></canvas>
                    </div>
                </div>

                <!-- Grafik Renaksi di Kanan -->
                <div id="renaksi-chart-container" class="w-1/2 bg-white rounded-lg shadow-md p-4 h-96 hidden ">
                    <h3 class="text-xl font-semibold mb-4 text-gray-800">Grafik Renaksi </h3>
                    <div class="h-[calc(100%-4rem)] w-full pb-8">
                        <canvas id="myChartRenaksi"></canvas>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script src="{{ asset('assets/js/dasboard.js') }}"></script>

@endsection
