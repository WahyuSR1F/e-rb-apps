@extends('component.component-admin-dasboard.body-admin-dasboard')
@section('judul', 'E-RB')
@section('viewer')

    <div class="mt-[5rem]">
        <div class="container mx-auto mt-2 px-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="mb-6">
                    <h3 class="text-xl font-bold text-black">Monitoring Reformasi Birokrasi</h3>
                    
                    <div class="flex justify-start items-center mb-4">
                        <!-- Button to download Excel -->
                        <div class=" mx-2">
                            <label for="countries"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Export</label>
                                    <button id="export-button"
                                    class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded">
                                    Download Data
                                     </button>
                        </div>

                        <div class=" mx-2">
                            <label for="countries"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cluster</label>
                            <select id="cluster-select" name="cluster"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected>Pilih Cluster</option>

                            </select>
                        </div>
                        <div id="theme-dropdown" class="mx-2 hidden">
                            <label for="theme-select"
                                class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Tema</label>
                            <select id="theme-select" name="tema"
                                class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500">
                                <option selected>Pilih Tema</option>

                            </select>
                        </div>

                    </div>

                    <h2 class="text-2xl font-semibold mb-4">DAFTAR RENCANA AKSI REFORMASI DIGITALISA
                        PADA DINAS KESEHATAN TAHUN {{ date('Y') }}                    </h2>

                    <div class="p-2 hidden" id="refresContainer">
                        <button id="refreshButton"
                            class="px-2 py-1 font-bold text-sm text-white bg-blue-400 rounded-lg hover:bg-blue-500"><span><i
                                class="fa-solid fa-repeat text-white text-md  mx-2"></i></span>Refresh</button>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table id="approvedDataTable"
                            class="min-w-full bg-white border border-gray-500 rounded-lg overflow-hidden">
                            <thead>
                                <tr>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">No</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">PEMBUAT

                                    </th>
         
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">
                                        PERMASALAHAN</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">DASBOARD NAMESPACE
    
                                    </th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">SASARAN
                                    </th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">
                                        INDIKATOR</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">TARGET
                                    </th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">RENCANA
                                        AKSI</th>
                                        <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">DASBOARD NAMESPACE RENAKSI
    
                                       </th>
                                    <th scope="col" colspan="2"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">OUTPUT
                                    </th>
                                    <th scope="col" colspan="10"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">
                                        PENYELESAIAN</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">CAPAIAN
                                        %</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold w-[600px]">
                                        JENIS KEGIATAN AKSI \ N (TERKAIT ATAU TIDAK TERKAIT LANGSUNG DENGAN MASYARAKAT
                                        STAKEHOLDER UTAMA)</th>
                                    <th scope="col" colspan="10"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">
                                        ANGGARAN</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">CAPAIAN
                                        %</th>
                                    <th scope="col" colspan="2"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">UNIT /
                                        SATUAN PELAKSANA</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">Documentasi File
                                    </th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">STATUS
                                    </th>

                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 border font-semibold">AKSI
                                    </th>
                                </tr>
                                <tr>
                                    <th scope="col" rowspan="2"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">
                                        INDIKATOR</th>
                                    <th scope="col" rowspan="2"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">SATUAN
                                    </th>
                                    <th scope="col" colspan="5"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TARGET
                                    </th>
                                    <th scope="col" colspan="5"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">
                                        REALISASI</th>
                                    <th scope="col" colspan="5"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TARGET
                                    </th>
                                    <th scope="col" colspan="5"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">
                                        REALISASI</th>
                                    <th scope="col" rowspan="2"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">
                                        KOORDINATOR</th>
                                    <th scope="col" rowspan="2"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">
                                        PELAKSANA</th>
                                </tr>
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW I
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW II
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW III
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW IV
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TOTAL
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW I
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW II
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW III
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW IV
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TOTAL
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW I
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW II
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW III
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW IV
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TOTAL
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW I
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW II
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW III
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW IV
                                    </th>
                                    <th scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TOTAL
                                    </th>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <!-- Row with revision enabled -->


                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- Modal for Reassign -->
        <div id="reassignModal" class="modal hidden fixed inset-0 flex items-center justify-center z-50">
            <div
                class="modal-content bg-white p-4 rounded-lg shadow-lg w-full max-w-md sm:max-w-sm md:max-w-md lg:max-w-lg">
                <h2 class="text-xl font-semibold mb-4">Send Revision Note</h2>
                <textarea id="revisionNote" class="form-input w-full mb-4 h-32 border border-gray-300 p-2 rounded-lg"
                    placeholder="Add your revision note here..."></textarea>
                <div class="flex justify-end gap-2">
                    <button onclick="submitRevision()"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Send</button>
                    <button onclick="closeModal()"
                        class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded">Cancel</button>
                </div>
            </div>
        </div>

        <!-- modal document user -->
        <div id="ShowFile" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-2xl h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5 mt-9">
                <!-- Modal header -->
                <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Show File
                    </h3>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-toggle="ShowFile">
                        <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                            xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd"
                                d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->

                <div class=" mb-4 ">
                    <iframe id="pdfViewer" type="application/pdf" width="100%" height="600px"></iframe>
                </div>

            </div>
        </div>
    </div>

        <script src="{{ asset('assets/js/js_admin/evaluasi.js') }}"></script>

    @endsection