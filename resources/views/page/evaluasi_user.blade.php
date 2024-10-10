@extends('component.component-dasboard.body-dasboard')

@section('judul', 'Evaluasi E-RB')

@section('viewer')
    <div class="mt-[5rem]">
        <div class="container mx-auto mt-2 px-6">
            <div class="bg-white shadow-md rounded-lg px-6 pt-6 pb-3">
                <div class="mb-6">

                    <h2 class="text-2xl font-semibold mb-4">DAFTAR RENCANA AKSI YANG BELUM MEMENUHI SYARAT</h2>

                    <!-- Table -->
                    <div class="overflow-x-auto">
                        <table id="approvedDataTable"
                            class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden">
                            <thead>
                                <tr>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">No</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">
                                        PERMASALAHAN
                                    </th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">SASARAN
                                    </th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">
                                        INDIKATOR</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">TARGET
                                    </th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">RENCANA
                                        AKSI
                                    </th>
                                    <th scope="col" colspan="2"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">OUTPUT
                                    </th>
                                    <th scope="col" colspan="8"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">
                                        PENYELESAIAN</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">JENIS
                                        KEGIATAN
                                        AKSI</th>
                                    <th scope="col" colspan="8"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">
                                        ANGGARAN</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">JUMLAH
                                        ANGGARAN</th>
                                    <th scope="col" colspan="2"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">UNIT /
                                        SATUAN
                                        PELAKSANA</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">STATUS
                                    </th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">
                                        KOMENTAR</th>
                                    <th scope="col" rowspan="3"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">AKSI
                                    </th>
                                </tr>
                                <tr>
                                    <td scope="col" rowspan="2"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">
                                        INDIKATOR</td>
                                    <td scope="col" rowspan="2"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">SATUAN
                                    </td>
                                    <th scope="col" colspan="4"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">
                                        TARGET</th>
                                    <th scope="col" colspan="4"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">
                                        REALISASI</th>
                                    <th scope="col" colspan="4"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">
                                        TARGET</th>
                                    <th scope="col" colspan="4"
                                        class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold border">
                                        REALISASI</th>
                                    <td scope="col" rowspan="2"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">
                                        KOORDINATOR</td>
                                    <td scope="col" rowspan="2"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">
                                        PELAKSANA</td>
                                </tr>
                                <tr>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW I
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW II
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW III
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW IV
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW I
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW II
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW III
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW IV
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW I
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW II
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW III
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW IV
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW I
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW II
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW III
                                    </td>
                                    <td scope="col"
                                        class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW IV
                                    </td>
                                </tr>
                            </thead>
                            <tbody id="table-body">
                                <!-- Row with revision enabled -->
                                <tr class="border-b table-row">
                                    <td class="py-2 px-4">1</td>
                                    <td class="py-2 px-4">Kesehatan Balita</td>
                                    <td class="py-2 px-4">100% Balita Sehat</td>
                                    <td class="py-2 px-4">Angka Kesehatan</td>
                                    <td class="py-2 px-4">95%</td>
                                    <td class="py-2 px-4">Program Gizi Seimbang</td>
                                    <td class="py-2 px-4">Indikator</td>
                                    <td class="py-2 px-4">%</td>
                                    <td class="py-2 px-4">25%</td>
                                    <td class="py-2 px-4">50%</td>
                                    <td class="py-2 px-4">75%</td>
                                    <td class="py-2 px-4">100%</td>
                                    <td class="py-2 px-4">Pemberian Vitamin A</td>
                                    <td class="py-2 px-4">100 Juta</td>
                                    <td class="py-2 px-4">200 Juta</td>
                                    <td class="py-2 px-4">300 Juta</td>
                                    <td class="py-2 px-4">400 Juta</td>
                                    <td class="py-2 px-4">500 Juta</td>
                                    <td class="py-2 px-4">Dinas Kesehatan</td>
                                    <td class="py-2 px-4">Dinas Sosial</td>
                                    <td class="py-2 px-4 checkbox-center flex flex-wrap gap-2">
                                        <button
                                            class="bg-green-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-green-600 approve-btn"
                                            onclick="approveRow(this.closest('tr'))">Approve</button>
                                        <button
                                            class="bg-red-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-red-600 reject-btn"
                                            onclick="toggleEditButton(this.closest('tr'))">Reject</button>
                                        <button
                                            class="bg-blue-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-blue-600 edit-btn hidden"
                                            onclick="saveRow(this.closest('tr'))">Save</button>
                                        <button
                                            class="bg-yellow-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-yellow-600 reassign-btn hidden"
                                            onclick="reassignRow(this.closest('tr'))">Reassign</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="pagination mt-5 flex justify-end" id="pagination"></div>
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

        <script>
            const user_id = @json(auth()->user() ? auth()->user()->id : null);
        </script>
        <script src="{{ asset('assets/js/evaluasi_user.js') }}"></script>
    @endsection