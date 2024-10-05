<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\FileAsset;
use App\Models\RencanaAksi;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\TargetPenyelesaian;
use Illuminate\Support\Facades\Auth;
use App\Models\RealisasiPenyelesaian;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Helpers\HelpersController;

class PenyelesaianController extends Controller
{
    public function getTargetPenyelesaian(Request $request)
    {

        try {
            $startTime = microtime(true);
            $search = $request->input('search');
            $perPage =  $request->input('perPage', 10);
            $query =  TargetPenyelesaian::query();
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

    public function getTargetPenyelesaianById(Request $request)
    {
        try {
            $startTime =  microtime(true);
            $request->validate([
                'id' => 'required|string'
            ]);

            $data =  TargetPenyelesaian::where('id', $request->id)->orWhere('rencana_aksi_id', $request->id)->first();

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

    public function createTargetPenyelesaian(Request $request)
    {
        $user =  Auth::user();
        try {


            $request->validate([
                'id' => 'required|string',
                'twI' => 'required|integer',
                'twII' => 'required|integer',
                'twIII' => 'required|integer',
                'twIV' => 'required|integer',
                'subjek' => 'required|string',
                'type' => 'required|string'
            ]);

            if ($request->type === 'Parsial') {
                $jumlahTarget = max(intval($request->twI), intval($request->twII), intval($request->twIII), intval($request->twIV));
            } else {
                $jumlahTarget = intval($request->twI ?? 0) + intval($request->twII ?? 0) + intval($request->twIII ?? 0) + intval($request->twIV ?? 0);
            }


            TargetPenyelesaian::create([
                'id' => Str::uuid(),
                'rencana_aksi_id' => $request->id,
                'user_id' => $user->id,
                'twI' => $request->twI,
                'twII' => $request->twII,
                'twIII' => $request->twIII,
                'twIV' => $request->twIV,
                'jumlah' => $jumlahTarget,
                'type' => $request->type,
                'subjek' => $request->subjek
            ]);

            return back()->with('success', 'Data berhasil disimpan!');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan saat proses update');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }

    public function updateTargetPenyelesaian(Request $request)
    {
        try {
            $request->validate([
                'id' =>  'required|string',
                'twI' => 'required|string',
                'twII' => 'required|string',
                'twIII' => 'required|string',
                'twIV' => 'required|string',
                'subjek' => 'required|string'
            ]);



            $update = TargetPenyelesaian::where('id', $request->id)->first();
            if ($update->type === 'Parsial') {
                $jumlahTarget = max(intval($request->twI), intval($request->twII), intval($request->twIII), intval($request->twIV));
            } else {
                $jumlahTarget = intval($request->twI ?? 0) + intval($request->twII ?? 0) + intval($request->twIII ?? 0) + intval($request->twIV ?? 0);
            }
            $update->update([
                'twI' => $request->twI,
                'twII' => $request->twII,
                'twIII' => $request->twIII,
                'twIV' => $request->twIV,
                'jumlah' => $jumlahTarget,
                'subjek' => $request->subjek
            ]);

            return back()->with('success', 'Data berhasil diperbarui!');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan saat proses update');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }


    public function deleteTargetPenyelesaian(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string'
            ]);

            $data = TargetPenyelesaian::where('id', $request->id)->first();
            $data->delete();

            return back()->with('success', 'Data berhasil dihapus!');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
            //throw $th;
        }
    }


    //realisasi

    public function getRealisasiPenyelesaian(Request $request)
    {
        try {

            $startTime = microtime(true);
            $search = $request->input('search');
            $perPage =  $request->input('perPage', 10);
            $query =  RealisasiPenyelesaian::query();
            if ($search) {
                $query->where('twI', 'like', "%{$search}%")->orWhere('twII', 'like', "%{$search}%")->orWhere('twIII', 'like', "%{$search}%")->orWhere('twIV', 'like', '%{$search}%')->orWhere('jumlah', 'like', '%{$search}%')->orWhere('presentase', 'like', '%{$search}%');
            }
            $data = $query->with('FileAsset')->where('rencana_aksi_id', $request->id)->paginate($perPage);
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

    public function getRealisasiPenyelesaianById(Request $request)
    {
        try {

            $startTime =  microtime(true);
            $request->validate([
                'id' => 'required|string'
            ]);

            $data =  RealisasiPenyelesaian::where('id', $request->id)->with('FileAsset')->first();

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

    public function createRealisasiPenyelesaian(Request $request)
    {
        $user =Auth::user();
        try {

            $request->validate([
                'id' => 'required|string',
                'twI' => 'required|integer',
                'twII' => 'nullable|integer',
                'twIII' => 'nullable|integer',
                'twIV' => 'nullable|integer',
                'file' => 'nullable|file|mimes:pdf|max:5000'
            ]);


            $data =  TargetPenyelesaian::where('rencana_aksi_id', $request->id)->first();
            if ($data->type == 'Parsial') {
                $jumlahTarget = max(intval($data->twI), intval($data->twII), intval($data->twIII), intval($data->twIV));
                $jumlahRealisasi = max(intval($request->twI ?? 0), intval($request->twII ?? 0), intval($request->twIII ?? 0), intval($request->twIV ?? 0));
            } else {
                $jumlahTarget = (int) $data->twI  + (int) $data->twII + (int) $data->twIII + (int)$data->twIV;
                $jumlahRealisasi = intval($request->twI ?? 0) + intval($request->twII ?? 0) + intval($request->twIII ?? 0) + intval($request->twIV ?? 0);
            }

            //perhitungan jumlah yang telah diselesaiakan

            $presentasiCapaian = ($jumlahRealisasi / $jumlahTarget) * 100;

            $renaksi = RencanaAksi::where('id', $request->id)->first();



            $capaian = $jumlahRealisasi . "/" . $jumlahTarget;


            RealisasiPenyelesaian::create([
                'id' => Str::uuid(),
                'rencana_aksi_id' => $request->id,
                'user_id' => $user->id,
                'twI' => $request->twI,
                'twII' => $request->twII ?? 0,
                'twIII' => $request->twIII ?? 0,
                'twIV' => $request->twIV ?? 0,
                'jumlah' => $jumlahRealisasi,
                'capaian' => $capaian,
                'presentase' => $presentasiCapaian,
                'type' => $data->type,

            ]);

            //excute filenya jika ada

            if ($request->file('file')) {
                $fileName = $request->id . '_' . $renaksi->rencana_aksi;
                $folder = 'Documentation_Asset';
                $this->GDPutFile($folder, $fileName, $request);
                //get namespace and go input to database
                FileAsset::create([
                    'id' => Str::uuid(),
                    'rencana_aksi_id' => $request->id,
                    'file_name' =>  $fileName,
                    'file_path' =>  $folder
                ]);
            }


            return back()->with('success', 'Data berhasil disimpan!');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan saat proses update');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }


    public function updateRealisasiPenyelesaian(Request $request)
    {
        try {

            $request->validate([
                'id' => 'required|string',
                'rencana_aksi_id' => 'required|string',
                'twI' => 'required|integer',
                'twII' => 'nullable|integer',
                'twIII' => 'nullable|integer',
                'twIV' => 'nullable|integer',
                'file' => 'nullable|file|mimes:pdf|max:5000'
            ]);

            $data =  TargetPenyelesaian::where('rencana_aksi_id', $request->rencana_aksi_id)->first();

            $jumlahTarget =  (int)$data->twI  + (int)$data->twII + (int)$data->twIII + (int)$data->twIV;



            //perhitungan jumlah yang telah diselesaiakan
            $jumlahRealisasi = intval($request->twI ?? 0) + intval($request->twII ?? 0) + intval($request->twIII ?? 0) + intval($request->twIV ?? 0);


            $presentasiCapaian = ($jumlahRealisasi / $jumlahTarget) * 100;


            $capaian = $jumlahRealisasi . "/" . $jumlahTarget;

            $data = RealisasiPenyelesaian::where('id', $request->id)->first();

            $renaksi = RencanaAksi::where('id', $request->rencana_aksi_id)->first();

            $data->update([
                'twI' => $request->twI,
                'twII' => $request->twII,
                'twIII' => $request->twIII,
                'twIV' => $request->twIV,
                'jumlah' => $jumlahRealisasi,
                'capaian' => $capaian,
                'presentase' => $presentasiCapaian

            ]);

            if ($request->file('file')) {
                $file_path = FileAsset::where('rencana_aksi_id', $request->rencana_aksi_id)->select('file_name', 'file_path')->first();
                if (!empty($file_path)) {
                    $file_path = $file_path->file_path . '/' . $file_path->file_name;
                    FileAsset::where('rencana_aksi_id', $request->rencana_aksi_id)->delete();
                    $this->GDDeleteFile($file_path);
                }

                $fileName = $request->id . '_' . $renaksi->rencana_aksi;
                $folder = 'Documentation_Asset';
                $this->GDPutFile($folder, $fileName, $request);
                //get namespace and go input to database
                FileAsset::create([
                    'id' => Str::uuid(),
                    'rencana_aksi_id' => $request->rencana_aksi_id,
                    'file_name' =>  $fileName,
                    'file_path' =>  $folder
                ]);
            }


            return back()->with('success', 'data berhasil diupdate');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan pada Inputan');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }

    public function deleteRealisasiPenyelesaian(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string'
            ]);

            $data = RealisasiPenyelesaian::where('id', $request->id)->first();

            //delete to file asset and delete GD
            $file = FileAsset::where('rencana_aksi_id', $data->rencana_aksi_id)->first();

            $file_path =  $file->file_path . '/' . $file->file_name;
            $this->GDDeleteFile($file_path);

            // on proccess delete
            $file->delete();

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
        Gdrive::put($folder . '/' . $fileName, $request->file('file'));
    }
    protected function GDDeleteFile($link): void
    {
        Gdrive::delete($link);
    }

    public function GDGetFile(Request $request)
    {

        $id = $request->input('id');

        $data = Gdrive::get($id);
        if ($data->file) {
            $fileContent = $data->file; // Pastikan ini adalah binary PDF data



            return response($fileContent, 200)
                ->header('Content-Type', 'application/pdf') // Pastikan ini adalah PDF
                ->header('Content-Disposition', 'inline; filename="' . basename($id) . '.pdf"');
        } else {
            return response('File not found', 404); // Tangani jika file tidak ditemukan
        }
    }


    public function deleteRealisasiPenyelesaianById($id)
    {
        try {


            $data = RealisasiPenyelesaian::where('id', $id)->first();

            //delete to file asset and delete GD
            $file = FileAsset::where('rencana_aksi_id', $data->rencana_aksi_id)->first();

            $file_path =  $file->file_path . '/' . $file->file_name;
            $this->GDDeleteFile($file_path);

            // on proccess delete
            $file->delete();

            $data->delete();

            return back()->with('success', 'data berhasil dihapus');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan pada Inputan');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }

    public function deleteTargetPenyelesaianById($id)
    {
        try {


            $data = TargetPenyelesaian::where('id', $id)->first();
            $data->delete();

            return back()->with('success', 'Data berhasil dihapus!');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
            //throw $th;
        }
    }
}