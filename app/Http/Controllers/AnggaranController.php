<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TargetAnggaran;
use App\Models\RealisasiAnggaran;
use Illuminate\Support\Facades\Auth;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Helpers\HelpersController;

class AnggaranController extends Controller
{
    public function getAnggaran(Request $request)
    {
        try {
            $startTime = microtime(true);
            $search = $request->input('search');
            $perPage =  $request->input('perPage', 10);

            $query = TargetAnggaran::query();
            if ($search) {
                $query->where('twI', 'like', '%' . $search . '%')->orWhere('twII', 'like', '%' . $search . '%')->orWhere('twIII', 'like', '%' . $search . '%')->orWhere('twIV', 'like', '%' . $search . '%')->orWhere('jumlah', 'like', '%' . $search . '%')->orWhere('koordinator', 'like', '%' . $search . '%')->orWhere('pelaksana', 'like', '%' . $search . '%');
            }
            $data = $query->where('rencana_aksi_id', $request->id)->paginate($perPage);
            $data = (new HelpersController())->ChangeFormatArray($data);
            $status = 'success';
            $message = 'data berhasil didapat';
            $endTime = microtime(true);
            $time_access = $endTime -  $startTime;
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data['data'],
                'total' => $data['total'],
                'per_page' => $data['per_page'],
                'current_page' => $data['current_page'],
                'last_page' => $data['last_page'],
                'time_access' => $time_access,
            ]);
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }
    public function getTargetAnggaranById(Request $request)
    {
        try {
            $startTime =  microtime(true);
            $request->validate([
                'id' => 'required|string'
            ]);
            $data =  TargetAnggaran::where('id', $request->id)->orWhere('rencana_aksi_id', $request->id)->first();

            $status = 'success';
            $message = 'data berhasil didapat';

            $endTime =  microtime(true);
            $time_access =  $endTime - $startTime;
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data,
                'time_access' => $time_access
            ], 200);
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }

    public function createTargetAnggaran(Request $request)
    {
        $user = Auth::user();
        try {

            $request->validate([
                'id' => 'required|string',
                'TWI' => 'required|integer',
                'TWII' => 'required|integer',
                'TWIII' => 'required|integer',
                'TWIV' => 'required|integer',


            ]);
            $jumlah  = intval($request->TWI ?? 0) + intval($request->TWII ?? 0) + intval($request->TWIII ?? 0) + intval($request->TWIV ?? 0);

            TargetAnggaran::create([
                'id' =>  Str::uuid(),
                'rencana_aksi_id' =>  $request->id,
                'user_id' => $user->id,
                'twI' => $request->TWI,
                'twII' => $request->TWII,
                'twIII' => $request->TWIII,
                'twIV' => $request->TWIV,
                'jumlah' =>  $jumlah,
            ]);


            return back()->with('success', 'Data berhasil disimpan!');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan saat proses update');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }
    public function updateTargetAnggaran(Request $request)
    {
        try {

            $request->validate([
                'id' =>  'required|string',
                'TWI' => 'required|integer',
                'TWII' => 'nullable|integer',
                'TWIII' => 'nullable|integer',
                'TWIV' => 'nullable|integer'
            ]);
            $jumlah  = intval($request->TWI ?? 0) + intval($request->TWII ?? 0) + intval($request->TWIII ?? 0) + intval($request->TWIV ?? 0);
            $update = TargetAnggaran::where('id', $request->id)->first();
            $update->update([
                'twI' => $request->TWI ?? 0,
                'twII' => $request->TWII ?? 0,
                'twIII' => $request->TWIII ?? 0,
                'twIV' => $request->TWIV ?? 0,
                'twIV' => $request->TWIV ?? 0,
                'jumlah' =>  $jumlah,

            ]);

            return back()->with('success', 'Data berhasil diperbarui!');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan saat proses update');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }
    public function deleteTargetAnggaran(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string'
            ]);

            $data = TargetAnggaran::where('id', $request->id)->first();
            $data->delete();

            return back()->with('success', 'Data berhasil dihapus!');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
            //throw $th;
        }
    }

    public function getRealisasiAnggaran(Request $request)
    {
        try {
            $startTime = microtime(true);
            $search =  $request->input('search');
            $perPage = $request->input('perPage', 10);

            $query =  RealisasiAnggaran::query();

            if ($search) {
                $query->where('twI', 'like', "%{$search}%")->orWhere('twII', 'like', "%{$search}%")->orWhere('twIII', 'like', "%{$search}%")->orWhere('twIV', 'like', '%{$search}%')->orWhere('subjek', 'like', '%{$search}%');
            }
            $data = $query->where('rencana_aksi_id', $request->id)->paginate($perPage);
            $data = (new HelpersController())->ChangeFormatArray($data);
            $status = 'success';
            $message = 'data berhasil didapat';

            $endTime =  microtime(true);
            $time_access =  $endTime -  $startTime;
            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data['data'],
                'total' => $data['total'],
                'per_page' => $data['per_page'],
                'current_page' => $data['current_page'],
                'last_page' => $data['last_page'],
                'time_access' => $time_access,
            ]);
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }
    public function getRealisasiAnggaranById(Request $request)
    {
        try {
            $startTime =  microtime(true);
            $request->validate([
                'id' => 'required|string'
            ]);

            $data =  RealisasiAnggaran::where('id', $request->id)->first();

            $status = 'success';
            $message = 'data berhasil didapat';

            $endTime =  microtime(true);
            $time_access =  $endTime -  $startTime;


            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data,
                'time_access' => $time_access
            ], 200);
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }
    public function createRealisasiAnggaran(Request $request)
    {
        $user = Auth::user();
        try {

            $request->validate([
                'id' => 'required|string',
                'twI' => 'required|integer',
                'twII' => 'nullable|integer',
                'twIII' => 'nullable|integer',
                'twIV' => 'nullable|integer',
            ]);

            $data = TargetAnggaran::where('rencana_aksi_id', $request->id)->first();


            $jumlahTarget =  (int)$data->twI  + (int)$data->twII + (int)$data->twIII + (int)$data->twIV;
            $jumlahRealisasi = intval($request->twI ?? 0) + intval($request->twII ?? 0) + intval($request->twIII ?? 0) + intval($request->twIV ?? 0);
            $presentasiCapaian = ($jumlahRealisasi / $jumlahTarget) * 100;
            $capaian = $jumlahRealisasi . "/" . $jumlahTarget;


            RealisasiAnggaran::create([
                'id' => Str::uuid(),
                'rencana_aksi_id' => $request->id,
                'user_id' => $user->id,
                'twI' => $request->twI,
                'twII' => $request->twII,
                'twIII' => $request->twIII,
                'twIV' => $request->twIV,
                'jumlah' => $jumlahRealisasi,
                'capaian' => $capaian,
                'presentase' => $presentasiCapaian
            ]);

            return back()->with('success', 'Data berhasil disimpan!');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan saat proses update');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }
    public function updateRealisasiAnggaran(Request $request)
    {
        try {


            $request->validate([
                'id' => 'required|string',
                'rencana_aksi_id' => 'required|string',
                'twI' => 'required|integer',
                'twII' => 'required|integer',
                'twIII' => 'required|integer',
                'twIV' => 'required|integer',
            ]);
            $data =  TargetAnggaran::where('rencana_aksi_id', $request->rencana_aksi_id)->first();

            $jumlahTarget =  (int)$data->twI  + (int)$data->twII + (int)$data->twIII + (int)$data->twIV;
            $jumlahRealisasi = intval($request->twI ?? 0) + intval($request->twII ?? 0) + intval($request->twIII ?? 0) + intval($request->twIV ?? 0);


            $presentasiCapaian = ($jumlahRealisasi / $jumlahTarget) * 100;


            $capaian = $jumlahRealisasi . "/" . $jumlahTarget;

            $data = RealisasiAnggaran::where('id', $request->id)->first();

            $data->update([
                'twI' => $request->twI,
                'twII' => $request->twII,
                'twIII' => $request->twIII,
                'twIV' => $request->twIV,
                'jumlah' => $jumlahRealisasi,
                'capaian' => $capaian,
                'presentase' => $presentasiCapaian

            ]);


            return back()->with('success', 'data berhasil diupdate');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan pada Inputan');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }

    public function deleteRealisasiAnggaran(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string'
            ]);

            $data = RealisasiAnggaran::where('id', $request->id)->first();
            $data->delete();

            return back()->with('success', 'data berhasil dihapus');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan pada Inputan');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }

    protected function GDPutFile($folder, $fileName, $request): void
    {
        Gdrive::put($folder . $fileName, $request->file('file'));
    }

    public function GDGetFile($folder, $fileName)
    {
        $data = Gdrive::get($folder . $fileName);

        return response($data->file, 200)
            ->header('Content-Type', $data->ext);
    }


    public function deleteTargetAnggaranById($id)
    {
        try {

            $data = TargetAnggaran::where('id', $id)->first();
            $data->delete();

            return back()->with('success', 'Data berhasil dihapus!');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
            //throw $th;
        }
    }

    public function deleteRealisasiAnggaranById($id)
    {
        try {

            $data = RealisasiAnggaran::where('id', $id)->first();
            $data->delete();

            return back()->with('success', 'data berhasil dihapus');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan pada Inputan');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }
}