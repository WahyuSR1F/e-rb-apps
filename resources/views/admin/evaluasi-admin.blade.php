
@extends('component.component-admin-dasboard.body-admin-dasboard')

@section('judul', 'E-RB')

@section('viewer')
    <div class="mt-[5rem]">
        
        <div class="container mx-auto mt-2 px-6">
            <div class="bg-white shadow-md rounded-lg p-6">
                <div class="mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <!-- Button to download Excel -->
                        <button onclick="downloadData()" class="bg-green-500 hover:bg-green-700 text-white py-2 px-4 rounded">
                            Download Data
                        </button>
                    </div>
                    
                <h2 class="text-2xl font-semibold mb-4">RENCANA AKSI REFORMASI BIROKRASI TEMATIK DIGITALISASI STUNTING PADA DINAS KESEHATAN TAHUN 2024</h2>
    
                <!-- Table -->
                <div class="table-responsive">
                    <table id="approvedDataTable" class="min-w-full bg-white border border-gray-300 rounded-lg overflow-hidden">
                        <thead>
                            <tr>
                                <th scope="col" rowspan="2" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">No</th>
                                <th scope="col" rowspan="2" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">PERMASALAHAN</th>
                                <th scope="col" rowspan="2" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">SASARAN</th>
                                <th scope="col" rowspan="2" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">INDIKATOR</th>
                                <th scope="col" rowspan="2" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">TARGET</th>
                                <th scope="col" rowspan="2" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">RENCANA AKSI</th>
                                <th scope="col" colspan="2" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">OUTPUT</th>
                                <th scope="col" colspan="4" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">TARGET PENYELESAIAN</th>
                                <th scope="col" rowspan="2" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">JENIS KEGIATAN AKSI</th>
                                <th scope="col" colspan="4" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">TARGET ANGGARAN</th>
                                <th scope="col" rowspan="2" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">JUMLAH ANGGARAN</th>
                                <th scope="col" colspan="2" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">UNIT / SATUAN PELAKSANA</th>
                                <th scope="col" rowspan="2" class="py-2 px-6 bg-gray-100 text-center text-gray-600 font-semibold">AKSI</th>
                            </tr>
                            <tr>
                                <td scope="col" class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">INDIKATOR</td>
                                <td scope="col" class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">SATUAN</td>
                                <td scope="col" class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW I</td>
                                <td scope="col" class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW II</td>
                                <td scope="col" class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW III</td>
                                <td scope="col" class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW IV</td>
                                <td scope="col" class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW I</td>
                                <td scope="col" class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW II</td>
                                <td scope="col" class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW III</td>
                                <td scope="col" class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">TW IV</td>
                                <td scope="col" class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">KOORDINATOR</td>
                                <td scope="col" class="px-6 py-2 bg-gray-100 text-center text-gray-600 border font-semibold">PELAKSANA</td>
                            </tr>
                        </thead>
                        <tbody>
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
                                    <button class="bg-green-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-green-600 approve-btn" onclick="approveRow(this.closest('tr'))">Approve</button>
                                    <button class="bg-red-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-red-600 reject-btn" onclick="toggleEditButton(this.closest('tr'))">Reject</button>
                                    <button class="bg-blue-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-blue-600 edit-btn hidden" onclick="saveRow(this.closest('tr'))">Save</button>
                                    <button class="bg-yellow-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-yellow-600 reassign-btn hidden" onclick="reassignRow(this.closest('tr'))">Reassign</button>
                                </td>
                            </tr>
                            <tr class="border-b table-row">
                                <td class="py-2 px-4">2</td>
                                <td class="py-2 px-4">Kesehatan Ibu</td>
                                <td class="py-2 px-4">100% Ibu Sehat</td>
                                <td class="py-2 px-4">Angka Kesehatan</td>
                                <td class="py-2 px-4">90%</td>
                                <td class="py-2 px-4">Program Penyuluhan Gizi Ibu</td>
                                <td class="py-2 px-4">Indikator</td>
                                <td class="py-2 px-4">%</td>
                                <td class="py-2 px-4">30%</td>
                                <td class="py-2 px-4">60%</td>
                                <td class="py-2 px-4">80%</td>
                                <td class="py-2 px-4">100%</td>
                                <td class="py-2 px-4">Pemeriksaan Rutin</td>
                                <td class="py-2 px-4">120 Juta</td>
                                <td class="py-2 px-4">220 Juta</td>
                                <td class="py-2 px-4">320 Juta</td>
                                <td class="py-2 px-4">420 Juta</td>
                                <td class="py-2 px-4">520 Juta</td>
                                <td class="py-2 px-4">Puskesmas</td>
                                <td class="py-2 px-4">RSUD</td>
                                <td class="py-2 px-4 checkbox-center flex flex-wrap gap-2">
                                    <button class="bg-green-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-green-600 approve-btn" onclick="approveRow(this.closest('tr'))">Approve</button>
                                    <button class="bg-red-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-red-600 reject-btn" onclick="toggleEditButton(this.closest('tr'))">Reject</button>
                                    <button class="bg-blue-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-blue-600 edit-btn hidden" onclick="saveRow(this.closest('tr'))">Save</button>
                                    <button class="bg-yellow-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-yellow-600 reassign-btn hidden" onclick="reassignRow(this.closest('tr'))">Reassign</button>
                                </td>
                            </tr>
                            <tr class="border-b table-row">
                                <td class="py-2 px-4">3</td>
                                <td class="py-2 px-4">Imunisasi Anak</td>
                                <td class="py-2 px-4">100% Anak Terimunisasi</td>
                                <td class="py-2 px-4">Tingkat Imunisasi</td>
                                <td class="py-2 px-4">85%</td>
                                <td class="py-2 px-4">Program Imunisasi Lengkap</td>
                                <td class="py-2 px-4">Indikator</td>
                                <td class="py-2 px-4">%</td>
                                <td class="py-2 px-4">20%</td>
                                <td class="py-2 px-4">40%</td>
                                <td class="py-2 px-4">60%</td>
                                <td class="py-2 px-4">80%</td>
                                <td class="py-2 px-4">Penyuluhan Imunisasi</td>
                                <td class="py-2 px-4">80 Juta</td>
                                <td class="py-2 px-4">150 Juta</td>
                                <td class="py-2 px-4">220 Juta</td>
                                <td class="py-2 px-4">300 Juta</td>
                                <td class="py-2 px-4">400 Juta</td>
                                <td class="py-2 px-4">Klinik Kesehatan</td>
                                <td class="py-2 px-4">Dinas Kesehatan</td>
                                <td class="py-2 px-4 checkbox-center flex flex-wrap gap-2">
                                    <button class="bg-green-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-green-600 approve-btn" onclick="approveRow(this.closest('tr'))">Approve</button>
                                    <button class="bg-red-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-red-600 reject-btn" onclick="toggleEditButton(this.closest('tr'))">Reject</button>
                                    <button class="bg-blue-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-blue-600 edit-btn hidden" onclick="saveRow(this.closest('tr'))">Save</button>
                                    <button class="bg-yellow-500 text-white w-full max-w-xs px-4 py-2 rounded hover:bg-yellow-600 reassign-btn hidden" onclick="reassignRow(this.closest('tr'))">Reassign</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div id="reassignModal" class="modal">
        <div class="modal-content">
            <h2 class="text-xl font-semibold mb-4">Send Revision Note</h2>
            <textarea id="revisionNote" class="form-input w-full mb-4" placeholder="Add your revision note here..."></textarea>
            <div class="flex justify-end">
                <button onclick="submitRevision()" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">Send</button>
                <button onclick="closeModal()" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded ml-2">Cancel</button>
            </div>
        </div>
    </div>

    <script src="{{ asset('assets/js/js_admin/evaluasi.js') }}"></script>

@endsection
