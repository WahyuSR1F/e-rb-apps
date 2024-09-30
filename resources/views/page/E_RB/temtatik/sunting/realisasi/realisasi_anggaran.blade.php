@extends('component.component-dasboard.body-dasboard')

@section('judul', 'Realisasi Anggaran')

@if (session('success'))
    <div id="alert"
        class="fixed bottom-4 right-4 z-50 bg-green-500 text-white px-4 py-3 rounded-md shadow-lg animate-bounce-once">
        <span><i class="fa-solid fa-circle-check text-lg mx-1 text-white"></i></span>{{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div id="alert"
        class="fixed bottom-4 right-4 z-50 bg-red-500 text-white px-4 py-3 rounded-md shadow-lg animate-bounce-once">
        <span><i class="fa-solid fa-circle-exclamation text-lg mx-1 text-white"></i></span>{{ session('error') }}
    </div>
@endif


@section('viewer')
    <div class="mt-[3.5rem]">
        <div class="relative w-full overflow-x-auto shadow-md sm:rounded-lg">
            <div class="w-full">
                <a href="{{ route('rencana-aksi') }}">
                    <button class="px-6 py-2 bg-blue-400 hover:bg-blue-700 rounded-lg text-md text-white font-semibold"><i
                            class="fa-solid fa-chevron-left text-lg text-white mx-1"></i>Back</button>
                </a>
                <h1 class="text-xl font-bold">Realisasi Anggaran</h1>
                <div class="w-full flex justify-between mt-4">
                    <div class="mt-1 p-3 rounded-lg bg-white dark:bg-gray-800 text-white shadow-md">

                        <p class="text-lg font-bold">Detail Realisasi Anggaran</p>
                        <table>
                            <tr>
                                <td>
                                    <p class="text-md font-bold text-gray-900 dark:text-white">Aspek</p>
                                </td>
                                <td>:</td>
                                <td>
                                    <p id="title-problem"
                                        class=" text-md font-bold text-gray-900 dark:text-white m-0 p-0 mx-3"></p>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-md font-bold text-gray-900 dark:text-white">Sasaran</p>
                                </td>
                                <td>:</td>
                                <td>
                                    <p id="title-sasaran"
                                        class="text-md font-bold text-gray-900 dark:text-white m-0 p-0 mx-3"></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>

            <div class="flex justify-end mt-6 mb-2">
                <a href="{{ route('realisasi-penyelesaian') }}">
                    <button class="p-2 mx-3 bg-green-400 text-white font-bold hover:bg-green-700 rounded-lg">Realisasi
                        Penyelesaian</button>
                </a>

                <button id="createButton" data-modal-target="create-modal" data-modal-toggle="create-modal"
                    class="p-2 bg-blue-400 text-white font-bold hover:bg-blue-700 rounded-lg"><span><i
                            class="fa-solid fa-plus text-white text-xl mx-1"></i></span>Create Target Anggaran</button>
            </div>
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 rounded-lg">
                <caption
                    class="p-5 text-lg font-semibold  rounded-t-lg text-left rtl:text-right text-gray-900 bg-white dark:text-white dark:bg-gray-800 rounded-top">
                    Target Anggaran
                    <p class="mt-1 text-sm font-normal text-gray-500 dark:text-gray-400">Data Program Sunting di Kab
                        Banyuwangi</p>
                    <div class="flex justify-end">
                        <div class="mx-3 mt-1 flex justify-start">
                            <label for="" class="mx-1 mt-1 text-sm">Show</label>
                            <select id="countries"
                                class="bg-gray-50 border border-gray-300 text-gray-900 h-8 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected value="10">10</option>
                                <option value="20">20</option>
                                <option value="50">50</option>
                                <option value="100">100</option>
                            </select>
                            <label for="" class="mx-1 mt-1 text-sm">Entrise</label>
                        </div>
                        <div class="w-60 mx-5">
                            <form class="w-full mx-auto">
                                <label for="default-search"
                                    class="mb-2 text-sm font-medium text-gray-900 sr-only dark:text-white">Search</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                                        <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                                        </svg>
                                    </div>
                                    <input type="search" id="default-search"
                                        class="block w-full py-2 px-4 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                                        placeholder="Search....." required />
                                    <button type="submit"
                                        class="text-white absolute end-1.5 bottom-1.5 bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-1 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Search</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </caption>
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                    <tr>
                        <th scope="col" rowspan="2" class="px-6 py-3 border">No</th>
                        <th scope="col" colspan="4" class="px-6 py-4 text-center border ">Target Anggaran</th>
                        <th scope="col" rowspan="2" class="px-6 py-3 text-left border ">JUMLAH ANGGARAN (RP.)</th>
                        <th scope="col" rowspan="2" class="px-6 py-3 text-left border ">Realisasi Anggaran</th>
                        <th scope="col" rowspan="2" class="px-6 py-3 text-left border ">Capaian(%)</th>

                        <th scope="col" rowspan="2" class="px-6 py-3 border  text-center">Detail</th>
                    </tr>
                    <tr>
                        <th scope="col" class="px-6 py-4 text-center border ">TW I</th>
                        <th scope="col" class="px-6 py-4 text-center border ">TW II</th>
                        <th scope="col" class="px-6 py-4 text-center border ">TW III</th>
                        <th scope="col" class="px-6 py-4 text-center border ">TW IV</th>

                    </tr>
                </thead>
                <tbody id="table-body">


                </tbody>
            </table>
            <div class="flex justify-center mt-2">
                <nav aria-label="Page navigation example">
                    <ul id="pagination" class="flex items-center -space-x-px h-10 text-base">

                    </ul>
                </nav>
                </nav>
            </div>
        </div>

    </div>

    <!-- modal -->

    <div id="popup-modal" tabindex="-1"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button type="button"
                    class="absolute top-3 end-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                    data-modal-hide="popup-modal">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                <div class="p-4 md:p-5 text-center">
                    <form action="{{ route('realisasi-anggaran-delete') }}" method="post">
                        @csrf
                        <input type="hidden" name="id" id="id-delete">
                        <svg class="mx-auto mb-4 text-gray-400 w-12 h-12 dark:text-gray-200" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 11V6m0 8h.01M19 10a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                        <h3 class="mb-5 text-lg font-normal text-gray-500 dark:text-gray-400">Are you sure you want to
                            delete this product?</h3>
                        <button data-modal-hide="popup-modal" type="submit"
                            class="text-white bg-red-600 hover:bg-red-800 focus:ring-4 focus:outline-none focus:ring-red-300 dark:focus:ring-red-800 font-medium rounded-lg text-sm inline-flex items-center px-5 py-2.5 text-center">
                            Yes, I'm sure
                        </button>
                        <button data-modal-hide="popup-modal" type="button"
                            class="py-2.5 px-5 ms-3 text-sm font-medium text-gray-900 focus:outline-none bg-white rounded-lg border border-gray-200 hover:bg-gray-100 hover:text-blue-700 focus:z-10 focus:ring-4 focus:ring-gray-100 dark:focus:ring-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:text-white dark:hover:bg-gray-700">No,
                            cancel</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <!-- edit modal -->
    <div id="crudModal1" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Realisasi Anggaran
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="crudModal1">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <form action="{{ route('realisasi-anggaran-edit') }}" method="post" class="p-4 md:p-5">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <h6 class="font-bold text-white text-xs">Edit Realisasi Anggaran</h6>
                        <input type="hidden" name="id" id="idRealisasiPenyelesaian">
                        <input type="hidden" name="rencana_aksi_id" id="idRencanaAksi">


                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tri
                                Wulan I / <span id="twITarget"></span></label>
                            <input type="number" name="twI" id="twIEdit"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type product name" autocomplete="off">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tri
                                Wulan II / <span id="twIITarget"></span></label>
                            <input type="number" name="twII" id="twIIEdit"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type product name" autocomplete="off">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tri
                                Wulan III / <span id="twIIITarget"></span></label>
                            <input type="number" name="twIII" id="twIIIEdit"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type product name" autocomplete="off">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tri
                                Wulan IV / <span id="twIVTarget"></span></label>
                            <input type="number" name="twIV" id="twIVEdit"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type product name" autocomplete="off">
                        </div>

                    </div>
                    <button id="excute1" type="submit"
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- create modal -->
    <div id="create-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-md max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Create Target Anggaran
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="create-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>


                </div>
                <!-- Modal body -->
                <form action="{{ route('realisasi-anggaran-create') }}" method="post" class="p-4 md:p-5">
                    @csrf
                    <div class="grid gap-4 mb-4 grid-cols-2">
                        <h6 class="font-bold text-white text-xs">Tambah Penyelesaian</h6>
                        <input type="hidden" name="id" id="id-renaksi">
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tri
                                Wulan I / <span id="twITarget1"></span></label>
                            <input type="number" name="twI" id="twICreate"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type product name" autocomplete="off">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tri
                                Wulan II / <span id="twIITarget1"></span><label>
                                    <input type="number" name="twII" id="twIICreate"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Type product name" autocomplete="off">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tri
                                Wulan III / <span id="twIIITarget1"></span></label>
                            <input type="number" name="twIII" id="twIIICreate"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type product name" autocomplete="off">
                        </div>
                        <div class="col-span-2">
                            <label for="name" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tri
                                Wulan IV / <span id="twIVTarget1"></span></label>
                            <input type="number" name="twIV" id="twIVCreate"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                placeholder="Type product name" autocomplete="off">
                        </div>

                    </div>
                    <button id="excute" type="submit"
                        class="text-white inline-flex items-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        <svg class="me-1 -ms-1 w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        Simpan
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/realisasiAnggaran.js') }}"></script>
@endsection
