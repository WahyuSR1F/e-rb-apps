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
    protected $jumlahTargetPenyelesaian;
    protected $jumlahRealisasiPenyelesaian;
    protected $jumlahTargetAnggaran;
    protected $jumlahRealisasiAnggaran;

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
            'unique_namespace_permasalahan' =>  'required|string',
            'sasaran' => 'required|string',
            'target' =>  'required|string',
            

            //Packet Rencana Aksi
            'idRenaksi' => 'required|string',
            'rencana_aksi' => 'required|string',
            'unique_namespace_renaksi' =>  'required|string',
            'indikator_rencana_aksi' => 'required|string',
            'satuan' => 'required|string',
            'koordinator' => 'required|string',
            'pelaksana' => 'required|string',

            //packet Target Penyelesaian
            'twI_target_penyelesaian' => 'required',
            'twII_target_penyelesaian' => 'required',
            'twIII_target_penyelesaian' => 'required',
            'twIV_target_penyelesaian' => 'required',
            'subjek' => 'required',

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
                'unique_namespace' => $request->unique_namespace_permasalahan,
                'sasaran' =>  $request->sasaran,
                'indikator' =>  $request->unique_namespace_permasalahan,
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
                'indikator' => $request->indikator_rencana_aksi,
                'unique_namespace' => $request->unique_namespace_renaksi,
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

            if($dataTargetPenyelesaian->type == 'parsial'){
                $this->jumlahTargetPenyelesaian = $this->parsial($request->twI_target_penyelesaian,$request->twII_target_penyelesaian, $request->twIII_target_penyelesaian, $request->twIV_target_penyelesaian);
            } else {
                $this->jumlahTargetPenyelesaian =  $this->kumulatif($request->twI_target_penyelesaian,$request->twII_target_penyelesaian, $request->twIII_target_penyelesaian, $request->twIV_target_penyelesaian);
            }
            $dataTargetPenyelesaian->update([
                'twI' => $request -> twI_target_penyelesaian,
                'twII' => $request -> twII_target_penyelesaian,
                'twIII' => $request -> twIII_target_penyelesaian,
                'twIV' => $request -> twIV_target_penyelesaian,
                'jumlah' =>  $this->jumlahTargetPenyelesaian,
                'type' =>  $dataTargetPenyelesaian->type,
                'subjek' =>  $request->subjek
            ]);

            // update realisasi penyelesaian
            $dataRealisasiPenyelesaian = $dataRencanaAksi->realisasiPenyelesaian()->first();
            if ($dataRealisasiPenyelesaian == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }
            
            if($dataRealisasiPenyelesaian->type == 'parsial'){
               
                $this->jumlahRealisasiPenyelesaian = $this->parsial( $request -> twI_realisasi_penyelesaian, $request -> twII_realisasi_penyelesaian, $request -> twIII_realisasi_penyelesaian, $request -> twIV_realisasi_penyelesaian);
            } else {
                $this->jumlahRealisasiPenyelesaian =  $this->kumulatif($request -> twI_realisasi_penyelesaian,$request -> twII_realisasi_penyelesaian,$request -> twIII_realisasi_penyelesaian, $request -> twIV_realisasi_penyelesaian);
            }

            
            $persentase = $this->persentase($this->jumlahTargetPenyelesaian,$this->jumlahRealisasiPenyelesaian);
            $capaian = $this->jumlahRealisasiPenyelesaian.'/'.$this->jumlahTargetPenyelesaian;
            $data = $dataRealisasiPenyelesaian->update([
                'twI' => $request -> twI_realisasi_penyelesaian,
                'twII' => $request -> twII_realisasi_penyelesaian,
                'twIII' => $request -> twIII_realisasi_penyelesaian,
                'twIV' => $request -> twIV_realisasi_penyelesaian,
                'type' =>  $dataRealisasiPenyelesaian->type,
                'jumlah' =>  $this->jumlahRealisasiPenyelesaian, 
                'capaian' => $capaian,
                'presentase' =>  $persentase
            ]);
            

            // update target anggaran
            $dataTargetAnggaran = $dataRencanaAksi->targetAnggaran()->first();
            if ($dataTargetAnggaran == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }

            $this->jumlahTargetAnggaran = $this->kumulatif( $request -> twI_target_anggaran, $request -> twII_target_anggaran, $request -> twIII_target_anggaran,$request -> twIV_target_anggaran);
            $dataTargetAnggaran->update([
                'twI' => $request -> twI_target_anggaran,
                'twII' => $request -> twII_target_anggaran,
                'twIII' => $request -> twIII_target_anggaran,
                'twIV' => $request -> twIV_target_anggaran,
                'jumlah' => $this->jumlahTargetAnggaran,
            ]);

            // update realisasi anggaran
            $dataRealisasiAnggaran = $dataRencanaAksi->realisasiAnggaran()->first();
            if ($dataRealisasiAnggaran == null) {
                return response()->json([
                    'status' => 'failed',
                    'message' => 'data permasalahan not found',
                ], 404);
            }

            $this->jumlahRealisasiAnggaran = $this->kumulatif($request -> twI_realisasi_anggaran, $request -> twII_realisasi_anggaran, $request -> twIII_realisasi_anggaran, $request -> twIV_realisasi_anggaran);
            $persentase = $this->persentase($this->jumlahTargetAnggaran,$this->jumlahRealisasiAnggaran);
            $capaian = $this->jumlahRealisasiAnggaran.'/'.$this->jumlahTargetAnggaran;
            $dataRealisasiAnggaran->update([
                'twI' => $request -> twI_realisasi_anggaran,
                'twII' => $request -> twII_realisasi_anggaran,
                'twIII' => $request -> twIII_realisasi_anggaran,
                'twIV' => $request -> twIV_realisasi_anggaran,
                'jumlah' => $this->jumlahRealisasiAnggaran,
                'capaian' => $capaian,
                'presentase' =>  $persentase
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
        $jumlahTarget = max(intval($twI), intval($twII), intval($twIII), intval($twIV));
        return $jumlahTarget;
    }

    protected function kumulatif($twI, $twII, $twIII, $twIV) {
        $jumlahTarget = intval($twI) + intval($twII) + intval($twIII) + intval($twIV);
        return $jumlahTarget;
    }

    protected function persentase($target, $capaian){
        $persentase = (($capaian/$target) * 100);
        return $persentase;
    }

}