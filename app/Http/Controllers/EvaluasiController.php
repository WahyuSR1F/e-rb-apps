<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\RencanaAksi;
use App\Models\Permasalahan;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class EvaluasiController extends Controller
{
    // fungsi get all data untuk di evaluasi
    public function getEvaluasi($user_id){

        try {
            $startTime =  microtime(true);
            // query get data
            // $query = RencanaAksi::query();
            $data = Permasalahan::whereHas('renaksi.reject', function ($query) {
                $query->where('status', 'Rejected');
            })
            ->with(['renaksi' => function ($query) {
                $query->whereHas('reject', function ($q) {
                    $q->where('status', 'Rejected');
                })->with('reject');
            }])
            ->with(['renaksi.targetAnggaran','renaksi.targetPenyelesaian','renaksi.realisasiAnggaran','renaksi.realisasiPenyelesaian','renaksi.reject'])

            ->where('user_id', $user_id)
            ->get();
            // check if data rencana aksi not found
            if ($data == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data rencana aksi not found',
                ], 404);
            }

            $endTime =  microtime(true);
            $time_access =  $endTime -  $startTime;

            return response()->json([
                'status' => 'success',
                'message' => 'data rencana aksi found',
                'data' => $data,
                'time_access' => $time_access
            ], 200);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    // fungsi update data
    public function updateEvaluasi(Request $request, $user_id){
        // validasi inputan permasalahan
    
        $request->validate([
            'permasalahan' => 'required|array',
            'permasalahan.permasalahan' => 'required|string',
            'permasalahan.sasaran' => 'required|string',
            'permasalahan.indikator' => 'required|string',
            'permasalahan.target' =>  'required|string',

            'rencana_aksi.rencana_aksi' => 'required|string',
            'rencana_aksi.indikator' => 'required|string',
            'rencana_aksi.satuan' => 'required|string',
            'rencana_aksi.koordinator' => 'required|string',
            'rencana_aksi.pelaksana' => 'required|string',

            'target_penyelesaian.twI' => 'required',
            'target_penyelesaian.twII' => 'required',
            'target_penyelesaian.twIII' => 'required',
            'target_penyelesaian.twIV' => 'required',

            'realisasi_penyelesaian.twI' => 'required',
            'realisasi_penyelesaian.twII' => 'required',
            'realisasi_penyelesaian.twIII' => 'required',
            'realisasi_penyelesaian.twIV' => 'required',
            'realisasi_penyelesaian.type' => 'required|string',

            'target_anggaran.twI' => 'required',
            'target_anggaran.twII' => 'required',
            'target_anggaran.twIII' => 'required',
            'target_anggaran.twIV' => 'required',

            'realisasi_anggaran.twI' => 'required',
            'realisasi_anggaran.twII' => 'required',
            'realisasi_anggaran.twIII' => 'required',
            'realisasi_anggaran.twIV' => 'required',
            

            'reject.status' => 'required|string',
        ]);

        try {
            // update permasalahan
            $data = Permasalahan::where('user_id', $user_id)->where('id', $request->permasalahan['id'])->first();
            if ($data == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            $data->update($request->permasalahan);
            
            // update rencana aksi
            $dataRencanaAksi = RencanaAksi::where('user_id', $user_id)->where('permasalahan_id', $request->rencana_aksi['permasalahan_id'])->first();
            if ($dataRencanaAksi == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            $dataRencanaAksi->update($request->rencana_aksi);

            // update target penyelesaian
            $dataTargetPenyelesaian = $dataRencanaAksi->targetPenyelesaian()->first();
            if ($dataTargetPenyelesaian == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            $dataTargetPenyelesaian->update($request->target_penyelesaian);

            // update realisasi penyelesaian
            $dataRealisasiPenyelesaian = $dataRencanaAksi->realisasiPenyelesaian()->first();
            if ($dataRealisasiPenyelesaian == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            $dataRealisasiPenyelesaian->update($request->realisasi_penyelesaian);

            // update target anggaran
            $dataTargetAnggaran = $dataRencanaAksi->targetAnggaran()->first();
            if ($dataTargetAnggaran == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            $dataTargetAnggaran->update($request->target_anggaran);

            // update realisasi anggaran
            $dataRealisasiAnggaran = $dataRencanaAksi->realisasiAnggaran()->first();
            if ($dataRealisasiAnggaran == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            $dataRealisasiAnggaran->update($request->realisasi_anggaran);

            // update reject
            $dataReject = $dataRencanaAksi->reject()->first();
            if ($dataReject == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data reject not found',
                ], 404);
            }
            $dataReject->update($request->reject);

            return response()->json([
                    'status' => 'success',
                    'messesage' => 'data berhasil diperbarui!',
                    'data' => ["statusReject" => $dataReject,"permasalahan" => $data, "rencana_aksi" => $dataRencanaAksi, "target_penyelesaian" => $dataTargetPenyelesaian, "target_anggaran" => $dataTargetAnggaran]
                    ], 200);
        } catch (ValidationException $e) {
            return back()->with('error', 'ada kesalahan saat proses update');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()]);
        }
    }
}