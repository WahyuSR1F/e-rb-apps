<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\RencanaAksi;
use Illuminate\Support\Str;
use App\Models\Permasalahan;
use Illuminate\Http\Request;
use App\Models\TargetAnggaran;
use App\Models\RealisasiAnggaran;
use App\Models\TargetPenyelesaian;
use App\Models\RealisasiPenyelesaian;
use Google\Service\CloudDeploy\Target;
use Illuminate\Validation\ValidationException;

class EvaluasiController extends Controller
{
    // fungsi get all data untuk di evaluasi
    public function getEvaluasi($user_id){

        try {
            $startTime =  microtime(true);
            // query get data
            // $query = RencanaAksi::query();
            $data = Permasalahan::
            with(['renaksi.targetAnggaran','renaksi.targetPenyelesaian','renaksi.realisasiAnggaran','renaksi.realisasiPenyelesaian','renaksi.reject'])
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
    
        $validate = $request->validate([
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

            'target_penyelesaian.twI' => 'required|integer|min:1',
            'target_penyelesaian.twII' => 'required|integer|min:1',
            'target_penyelesaian.twIII' => 'required|integer|min:1',
            'target_penyelesaian.twIV' => 'required|integer|min:1',

            'realisasi_penyelesaian.twI' => 'required|integer|min:1',
            'realisasi_penyelesaian.twII' => 'required|integer|min:1',
            'realisasi_penyelesaian.twIII' => 'required|integer|min:1',
            'realisasi_penyelesaian.twIV' => 'required|integer|min:1',
            'target_penyelesaian.type' => 'required|string',

            'target_anggaran.twI' => 'required|integer|min:1',
            'target_anggaran.twII' => 'required|integer|min:1',
            'target_anggaran.twIII' => 'required|integer|min:1',
            'target_anggaran.twIV' => 'required|integer|min:1',

            'realisasi_anggaran.twI' => 'required|integer|min:1',
            'realisasi_anggaran.twII' => 'required|integer|min:1',
            'realisasi_anggaran.twIII' => 'required|integer|min:1',
            'realisasi_anggaran.twIV' => 'required|integer|min:1',
            

            'reject.status' => 'required|string',
        ]);

        if ($validate === false) {
            return response()->json([
                'success' => false,
                'message' => 'validation failed'+ $validate,
            ]);
        }

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

            $maxTotal = max($request->target_penyelesaian["twI"], $request->target_penyelesaian["twII"], $request->target_penyelesaian["twIII"], $request->target_penyelesaian["twIV"]);
            $sumTotal = $request->target_penyelesaian["twI"] + $request->target_penyelesaian["twII"] + $request->target_penyelesaian["twIII"] + $request->target_penyelesaian["twIV"];

            // save target penyelesaian
            $dataTargetPenyelesaian = $dataRencanaAksi->targetPenyelesaian()->first();

            // check if target penyelesaian exists then update
            if ($dataTargetPenyelesaian) {
                // check if type is partial
                if ($request->target_penyelesaian['type'] == 'Partial') {
                    $dataTargetPenyelesaian->update([
                        'twI' => $request->target_penyelesaian['twI'],
                        'twII' => $request->target_penyelesaian['twII'],
                        'twIII' => $request->target_penyelesaian['twIII'],
                        'twIV' => $request->target_penyelesaian['twIV'],
                        'type' => $request->target_penyelesaian['type'],
                        'jumlah' => $maxTotal,
                    ]);
                }else if ($request->target_penyelesaian['type'] == 'Kumulatif') {
                    $dataTargetPenyelesaian->update([
                        'twI' => $request->target_penyelesaian['twI'],
                        'twII' => $request->target_penyelesaian['twII'],
                        'twIII' => $request->target_penyelesaian['twIII'],
                        'twIV' => $request->target_penyelesaian['twIV'],
                        'type' => $request->target_penyelesaian['type'],
                        'jumlah' => $sumTotal,
                    ]);
                }
            // create new target penyelesaian
            }else{
                // check if type is partial
                if($request->target_penyelesaian['type'] == 'Partial') {
                    TargetPenyelesaian::create([
                        'id' => Str::uuid(),
                        'rencana_aksi_id' => $dataRencanaAksi->id,
                        'user_id' => $user_id,
                        'twI' => $request->target_penyelesaian['twI'],
                        'twII' => $request->target_penyelesaian['twII'],
                        'twIII' => $request->target_penyelesaian['twIII'],
                        'twIV' => $request->target_penyelesaian['twIV'],
                        'type' => $request->target_penyelesaian['type'],
                        'jumlah' => $maxTotal,
                    ]);
                }else if ($request->target_penyelesaian['type'] == 'Kumulatif') {
                    TargetPenyelesaian::create([
                        'id' => Str::uuid(),
                        'rencana_aksi_id' => $dataRencanaAksi->id,
                        'user_id' => $user_id,
                        'twI' => $request->target_penyelesaian['twI'],
                        'twII' => $request->target_penyelesaian['twII'],
                        'twIII' => $request->target_penyelesaian['twIII'],
                        'twIV' => $request->target_penyelesaian['twIV'],
                        'type' => $request->target_penyelesaian['type'],
                        'jumlah' => $sumTotal,
                    ]);
                }
            }


            $maxTotalReal = max($request->realisasi_penyelesaian["twI"], $request->realisasi_penyelesaian["twII"], $request->realisasi_penyelesaian["twIII"], $request->realisasi_penyelesaian["twIV"]);
            $sumTotalReal = $request->realisasi_penyelesaian["twI"] + $request->realisasi_penyelesaian["twII"] + $request->realisasi_penyelesaian["twIII"] + $request->realisasi_penyelesaian["twIV"];

            // save realisasi penyelesaian
            $dataRealisasiPenyelesaian = $dataRencanaAksi->realisasiPenyelesaian()->first();
            // check if realisasi penyelesaian exists then update
            if ($dataRealisasiPenyelesaian) {
                if ($request->target_penyelesaian['type'] == 'Partial') {
                    $dataRealisasiPenyelesaian->update([
                        'twI' => $request->realisasi_penyelesaian['twI'],
                        'twII' => $request->realisasi_penyelesaian['twII'],
                        'twIII' => $request->realisasi_penyelesaian['twIII'],
                        'twIV' => $request->realisasi_penyelesaian['twIV'],
                        'jumlah' => $maxTotalReal,
                        'presentase' => $sumTotalReal / $sumTotal * 100,
                    ]);
                }else if ($request->target_penyelesaian['type'] == 'Kumulatif') {
                    $dataRealisasiPenyelesaian->update([
                        'twI' => $request->realisasi_penyelesaian['twI'],
                        'twII' => $request->realisasi_penyelesaian['twII'],
                        'twIII' => $request->realisasi_penyelesaian['twIII'],
                        'twIV' => $request->realisasi_penyelesaian['twIV'],
                        'jumlah' => $sumTotalReal,
                        'presentase' => $sumTotalReal / $sumTotal * 100,
                    ]);
                }
            }else{
                if ($request->target_penyelesaian['type'] == 'Partial') {
                    RealisasiPenyelesaian::create([
                        'id' => Str::uuid(),
                        'rencana_aksi_id' => $dataRencanaAksi->id,
                        'user_id' => $user_id,
                        'twI' => $request->realisasi_penyelesaian['twI'],
                        'twII' => $request->realisasi_penyelesaian['twII'],
                        'twIII' => $request->realisasi_penyelesaian['twIII'],
                        'twIV' => $request->realisasi_penyelesaian['twIV'],
                        'jumlah' => $maxTotalReal,
                        'presentase' => $sumTotalReal / $sumTotal * 100,
                    ]);
                }else if ($request->target_penyelesaian['type'] == 'Kumulatif') {
                    RealisasiPenyelesaian::create([
                        'id' => Str::uuid(),
                        'rencana_aksi_id' => $dataRencanaAksi->id,
                        'user_id' => $user_id,
                        'twI' => $request->realisasi_penyelesaian['twI'],
                        'twII' => $request->realisasi_penyelesaian['twII'],
                        'twIII' => $request->realisasi_penyelesaian['twIII'],
                        'twIV' => $request->realisasi_penyelesaian['twIV'],
                        'jumlah' => $sumTotalReal,
                        'presentase' => $sumTotalReal / $sumTotal * 100,
                    ]);
                }
            }



            $sumtotalTargetAnggaran = $validate['target_anggaran']['twI'] + $validate['target_anggaran']['twII'] + $validate['target_anggaran']['twIII'] + $validate['target_anggaran']['twIV'];
            
            // // save target anggaran
            $dataTargetAnggaran = $dataRencanaAksi->targetAnggaran()->first();
            // // check if target anggaran exists
            if ($dataTargetAnggaran) {
                // update target anggaran
                    $dataTargetAnggaran->update([
                        'twI' => $validate['target_anggaran']['twI'],
                        'twII' => $validate['target_anggaran']['twII'],
                        'twIII' => $validate['target_anggaran']['twIII'],
                        'twIV' => $validate['target_anggaran']['twIV'],
                        'jumlah' => $sumtotalTargetAnggaran,
                    ]);
                
            }else{
                //check if type is partial
                    TargetAnggaran::create([
                        'id' => Str::uuid(),
                        'rencana_aksi_id' => $dataRencanaAksi->id,
                        'user_id' => $user_id,
                        'twI' => $validate['target_anggaran']['twI'],
                        'twII' => $validate['target_anggaran']['twII'],
                        'twIII' => $validate['target_anggaran']['twIII'],
                        'twIV' => $validate['target_anggaran']['twIV'],
                        'jumlah' => $sumtotalTargetAnggaran,
                    ]);
            }
            

            $sumtotalRealisasiAnggaran = $validate['realisasi_anggaran']['twI'] + $validate['realisasi_anggaran']['twII'] + $validate['realisasi_anggaran']['twIII'] + $validate['realisasi_anggaran']['twIV'];

            // // save realisasi anggaran
            $dataRealisasiAnggaran = $dataRencanaAksi->realisasiAnggaran()->first();
            // // check if realisasi anggaran exists
            if ($dataRealisasiAnggaran) {
                    $dataRealisasiAnggaran->update([
                        'twI' => $validate['realisasi_anggaran']['twI'],
                        'twII' => $validate['realisasi_anggaran']['twII'],
                        'twIII' => $validate['realisasi_anggaran']['twIII'],
                        'twIV' => $validate['realisasi_anggaran']['twIV'],
                        'jumlah' => $sumtotalRealisasiAnggaran,
                        'presentase' => $sumtotalRealisasiAnggaran / $sumtotalTargetAnggaran * 100,

                    ]);
            }else{
                    RealisasiAnggaran::create([
                        'id' => Str::uuid(),
                        'rencana_aksi_id' => $dataRencanaAksi->id,
                        'user_id' => $user_id,
                        'twI' => $validate['realisasi_anggaran']['twI'],
                        'twII' => $validate['realisasi_anggaran']['twII'],
                        'twIII' => $validate['realisasi_anggaran']['twIII'],
                        'twIV' => $validate['realisasi_anggaran']['twIV'],
                        'jumlah' => $sumtotalRealisasiAnggaran,
                        'presentase' => $sumtotalRealisasiAnggaran / $sumtotalTargetAnggaran * 100,
                    ]);
            }


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
                    'data' => ["statusReject" => $dataReject,"permasalahan" => $data, "rencana_aksi" => $dataRencanaAksi, "target_penyelesaian" => $dataTargetPenyelesaian]
                    ], 200);
        } catch (ValidationException $e) {
            return back()->with('error', 'ada kesalahan saat proses update');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()]);
        }
    }
}