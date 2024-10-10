<?php

namespace App\Http\Controllers\Admin\Reject;

use App\Models\Reject;
use App\Models\RencanaAksi;
use Illuminate\Support\Str;
use App\Exports\AdminExport;
use App\Models\Permasalahan;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel as Excel;

class RejectCpntroller extends Controller
{

    public function getAllDataRB( Request $request){
        $data = Permasalahan::with(['allRelatedData','pembuat'])->whereYear('created_at', now()->year)->where('erb_type_id', $request->id)->get();
        return response()->json(['message' => 'berhasil', 'data' =>  $data],200);
    }
    public function setReject(Request $request)
    {
        
        try {
            $request->validate([
                'rencana_aksi_id' => 'required|string',
                'user_id' => 'required|string',
                'comment' => 'required|string',
                'status' => 'required|string'
            ]);

            $data = Reject::create([
                'id' => Str::uuid(),
                'rencana_aksi_id' => $request->rencana_aksi_id,
                'user_id' => $request->user_id,
                'comment' => $request->comment,
                'status' => $request->status
            ]);

            return back()->with('success', 'Data berhasil disimpan');
        } catch (ValidationException $e) {
            return back()->with('error', 'Inputan wajib diisi');
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try {
            $request->validate([
                'rencana_aksi_id' => 'required|string',
                'user_id' => 'required|string',
                'comment' => 'required|string',
                'status' => 'required|string'
            ]);

            $data = Reject::where('id', $request->id)->first();
            $data->update([
                'rencana_aksi_id' => $request->rencana_aksi_id,
                'user_id' => $request->user_id,
                'comment' => $request->comment,
                'status' => $request->status
            ]);

            return back()->with('success', 'Data berhasil disimpan');
        } catch (ValidationException $e) {
            return back()->with('error', 'Inputan wajib diisi');
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    public function getAll(Request $request)
    {
        try {
            $data = Reject::all();
            return response()->json(['status' => 'success', 'message' => 'data berhasil', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    public function getByUserId(Request $request)
    {
        try {
            $data = Reject::where('user_id', $request->user_id)->get();

            return response()->json(['status' => 'success', 'message' => 'data berhasil', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try {
            $data = Reject::where('id', $request->id)->first();
            $data->delete();

            return response()->json(['status' => 'success', 'message' => 'data berhasil', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

     // fungsi update data
    public function updateEvaluasi(Request $request){
      
        // validasi inputan permasalahan
        $request->validate([
            //packet permasalahan
            'idPermasalahan' => 'required|string',
            'permasalahan' => 'required|string',
            'sasaran' => 'required|string',
            'indikator' => 'required|string',
            'target' =>  'required|string',

            //Packet Rencana Aksi
            'idRenaksi' => 'required|string',
            'rencana_aksi' => 'required|string',
            'indikator_rencana_aksi' => 'required|string',
            'satuan' => 'required|string',
            'koordinator' => 'required|string',
            'pelaksana' => 'required|string',

            //packet Target Penyelesaian
            'twI_target_penyelesaian' => 'required',
            'twII_target_penyelesaian' => 'required',
            'twIII_target_penyelesaian' => 'required',
            'twIV_target_penyelesaian' => 'required',

            //packet Realisasi Penyelesaian
            'twI_realisasi_penyelesaian' => 'required',
            'twII_realisasi_penyelesaian' => 'required',
            'twIII_realisasi_penyelesaian' => 'required',
            'twIV_realisasi_penyelesaian' => 'required',

            'twI_target_anggaran' => 'required',
            'twII_target_anggaran' => 'required',
            'twIII_target_anggaran' => 'required',
            'twIV_target_anggaran' => 'required',

            'twI_realisasi_anggaran' => 'required',
            'twII_realisasi_anggaran' => 'required',
            'twIII_realisasi_anggaran' => 'required',
            'twIV_realisasi_anggaran' => 'required',
        ]);

        try {
            // update permasalahan
            $data = Permasalahan::where('id', $request->idPermasalahan)->first();
            if ($data == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            $data->update([
                'permasalahan' =>  $request->permasalahan,
                'sasaran' =>  $request->sasaran,
                'indikator' =>  $request->indikator,
                'target' =>  $request->target,
            ]);
            
            // update rencana aksi
            $dataRencanaAksi = RencanaAksi::where('id',$request->idRenaksi)->where('permasalahan_id', $request->idPermasalahan)->first();
            if ($dataRencanaAksi == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            $dataRencanaAksi->update([
                'rencana_aksi' => $request->rencana_aksi,
                'indikator_rencana_aksi' => $request->indikator_rencana_aksi,
                'satuan' => $request ->satuan,
                'koordinator' => $request ->koordinator,
                'pelaksana' => $request ->pelaksana,
            ]);

            // update target penyelesaian
            $dataTargetPenyelesaian = $dataRencanaAksi->targetPenyelesaian()->first();
            if ($dataTargetPenyelesaian == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            $dataTargetPenyelesaian->update([
                'twI_target_penyelesaian' => $request -> twI_target_penyelesaian,
                'twII_target_penyelesaian' => $request -> twII_target_penyelesaian,
                'twIII_target_penyelesaian' => $request -> twIII_target_penyelesaian,
                'twIV_target_penyelesaian' => $request -> twIV_target_penyelesaian,
            ]);

            // update realisasi penyelesaian
            $dataRealisasiPenyelesaian = $dataRencanaAksi->realisasiPenyelesaian()->first();
            if ($dataRealisasiPenyelesaian == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            $dataRealisasiPenyelesaian->update([
                'twI_realisasi_penyelesaian' => $request -> twI_realisasi_penyelesaian,
                'twII_realisasi_penyelesaian' => $request -> twII_realisasi_penyelesaian,
                'twIII_realisasi_penyelesaian' => $request -> twIII_realisasi_penyelesaian,
                'twIV_realisasi_penyelesaian' => $request -> twIV_realisasi_penyelesaian,
            ]);

            // update target anggaran
            $dataTargetAnggaran = $dataRencanaAksi->targetAnggaran()->first();
            if ($dataTargetAnggaran == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            $dataTargetAnggaran->update([
                'twI_target_anggaran' => $request -> twI_target_anggaran,
                'twII_target_anggaran' => $request -> twII_target_anggaran,
                'twIII_target_anggaran' => $request -> twIII_target_anggaran,
                'twIV_target_anggaran' => $request -> twIV_target_anggaran,
            ]);

            // update realisasi anggaran
            $dataRealisasiAnggaran = $dataRencanaAksi->realisasiAnggaran()->first();
            if ($dataRealisasiAnggaran == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            $dataRealisasiAnggaran->update([
                'twI_realisasi_anggaran' => $request -> twI_realisasi_anggaran,
                'twII_realisasi_anggaran' => $request -> twII_realisasi_anggaran,
                'twIII_realisasi_anggaran' => $request -> twIII_realisasi_anggaran,
                'twIV_realisasi_anggaran' => $request -> twIV_realisasi_anggaran,
            ]);

            // update reject
            $dataReject = $dataRencanaAksi->reject()->first();
            if($dataReject){
                $dataReject->update([
                    'status' => 'Pending'
                ]);
            }else{
                $dataReject =   Reject::create([
                    'id' => Str::uuid(),
                    'rencana_aksi_id'=> $request->idRenaksi,
                    'user_id' => $dataRencanaAksi->user_id,
                    'comment' => null,
                    'status' => 'Pending'
                ]);
            }

           

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

    public  function approve (Request $request){
         try {
            
            $renaksi = RencanaAksi::where('id', $request->id)->first();
            if(!$renaksi){
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data renaksi not found',
                ], 404);
            }
            $reject = Reject::where('rencana_aksi_id', $request->id)->first();
            if($reject){
                $reject->update(['status' => 'Approved']);
            }else{
                $reject =  Reject::create([
                    'id' => Str::uuid(),
                    'rencana_aksi_id' => $renaksi->id,
                    'user_id' => $renaksi->user_id,
                    'comment' => null,
                    'status' => 'Approved'
                ]);
            }

            return response()->json([
                'status' => 'success',
                'messesage' => 'data berhasil diperbarui!',
                'data' => ["statusReject" => $reject]
                ], 200);

         }catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()]);
        }
    }

    public function rejected (Request $request) {
        
        try {
        $request->validate([
            'note' => 'required|string',
        ]);
        $renaksi = RencanaAksi::where('id', $request->idRenaksi)->first();
        if(!$renaksi){
            return response()->json([
                'status' => 'failed',
                'message' => 'data renaksi not found',
            ], 404);
        }
        $reject = Reject::where('rencana_aksi_id', $request->idRenaksi)->first();
        if($reject){
            $reject->update(['comment' => $request->note ,'status' => 'Rejected']);
        }else{
            $reject = Reject::create([
                'id' => Str::uuid(),
                'rencana_aksi_id' => $renaksi->id,
                'user_id' => $renaksi->user_id,
                'comment' => $request->comment,
                'status' => 'Rejected'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'messesage' => 'data berhasil diperbarui!',
            'data' => ["statusReject" => $reject]
            ], 200);

        }catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()]);
        }

    }

    public function exportExcelApprove(Request $request)
    {
       $idTema = $request->id;
        return Excel::download(new AdminExport($idTema), Str::uuid() . '_user.xlsx');
    }

    protected function parsial ($twI, $twII, $twIII, $twIV){
        return $jumlahTarget = max(intval($request->twI), intval($request->twII), intval($request->twIII), intval($request->twIV));
    }
}