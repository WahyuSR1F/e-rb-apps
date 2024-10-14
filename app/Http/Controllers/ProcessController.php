<?php

namespace App\Http\Controllers;

use Exception;
use App\Exports\RBExport;
use App\Models\RencanaAksi;
use Illuminate\Support\Str;
use App\Models\Permasalahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yaza\LaravelGoogleDriveStorage\Gdrive;
use Maatwebsite\Excel\Facades\Excel as Excel;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Helpers\HelpersController;


class ProcessController extends Controller
{

    public function LoginProccess(Request $request)
    {

        try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string'
            ]);

            if (Auth::attempt(['username' => $request->username, 'password' => $request->password])) {
            
                if(Auth::user()->role == 'admincs'){
                   
                    return redirect()->route('admin-dasboard');
                }else{
                    if(Auth::user()->active_user === 1){
                        return redirect()->route('dasboard');
                    } else{
                        Auth::logout();
                        $request->session()->flush();
                        return back()->with('error', 'User ini Kepegawaian tidak aktif!');
                    }
                    
                }

                
            } else {
                return back()->with('error', 'Username atau Password Salah!');
            }
        } catch (ValidationException $e) {
            return back()->with('error', 'Inputan username dan password wajib diisi');
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        // Menghapus sesi
        Auth::logout();

        // Menghapus semua data sesi
    
        $request->session()->flush();

        // Mengembalikan tampilan login
        return redirect()->route('login');
    }

    public function createPermasalahan(Request $request)
    {
        $user = auth()->user();
        
        try {
            $request->validate([
                'permasalahan' => 'required|string',
                'sasaran' => 'required|string',
                'indikator' => 'required|string',
                'target' =>  'required|string',
                'unique_namespace' =>  'required|string',
            ]);


            //melakukan create
            Permasalahan::create([
                'id' => Str::uuid(),
                'unique_namespace' => $request->unique_namespace,
                'erb_type_id' => $request->erb_type,
                'user_id' =>  $user->id,
                'permasalahan' =>  $request->permasalahan,
                'sasaran' => $request->sasaran,
                'indikator' => $request->indikator,
                'target' =>  $request->target
            ]);

            return back()->with('success', 'Data berhasil disimpan');
        } catch (ValidationException $e) {
            return back()->with('error', 'Inputan username dan password wajib diisi');
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    public function getPermasalahan(Request $request)
    {
        try {
            $startTime =  microtime(true);

            // ambil query untuk pagination dan search
            $search = $request->input('search');
            $perPage =  $request->input('perPage', 10);

            $query = Permasalahan::query();
            if ($search) {
                $query->where('permasalahan', 'like', "%{$search}%")->orWhere('sasaran', 'like', "%{$search}")->orWhere('indikator', 'like', "%{$search}%")->orWhere('target', 'like', '%{$search}%');
            }

            $data =  $query->where('erb_type_id', $request->erb_type_id)->paginate($perPage);

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
            ], 200);
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }

    public function getPermasalahanByTema(Request $request)
    {
        try {

            $data = Permasalahan::query();
            $data->where('erb_type_id', $request->id);

            if ($request->years) {

                $data->whereYear('created_at', $request->years);
            }

            if ($request->dateAwal && $request->dateAkhir) {
                $data->where('created_at', '>=', $request->dateAwal)->where('created_at', '<=', $request->dateAkhir);
            }

            $data = $data->get();

            return response()->json(['status' => 'success', 'message' => 'data berhasil', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }

    public function getPermasalahanById(Request $request)
    {
        try {
            $startTime =  microtime(true);
            $request->validate([
                'id' => 'required|string'
            ]);

            $data = Permasalahan::where('id', $request->id)->first();

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
            return response()->json(['message' =>  $e->getMessage()], 500);
        }
    }
    public function updatePermsalahan(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string',
                'permasalahan' => 'required|string',
                'sasaran' => 'required|string',
                'indikator' =>  'required|string',
                'target' => "required|string",
                'unique_namespace' =>  'required|string',

            ]);

            $update = Permasalahan::where('id', $request->id)->first();
            $update->update([
                'permasalahan' => $request->permasalahan,
                'sasaran' => $request->sasaran,
                'indikator' => $request->indikator,
                'target' => $request->target,
                'unique_namespace' => $request->unique_namespace,
            ]);

            return back()->with('success', 'Data berhasil diperbarui!');
        } catch (ValidationException $e) {
            return back()->with('error', 'ada kesalahan saat proses update');
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    public function deletePermasalahan(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string',
            ]);

            $permasalahan = Permasalahan::where('id', $request->id)->first();
            $renaksi = RencanaAksi::where('permasalahan_id', $request->id)->with('FileAssets')->get();

            foreach ($renaksi as $data) {
                if ($data->FileAssets) {
                    $path =  $data->FileAssets->file_path . '/' . $data->FileAssets->file_name;
                    $this->GDDeleteFile($path);
                }
            }

            $permasalahan->delete();

            return back()->with('success', 'Data berhasil diperbarui!');
        } catch (ValidationException $e) {
            return back()->with('error', 'ada kesalahan saat proses delete');
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }

    public function getRenaksi(Request $request)
    {
        try {
            $startTime =  microtime(true);

            // ambil query untuk pagination dan search
            $search = $request->input('search');
            $perPage =  $request->input('perPage', 10);

            $query = RencanaAksi::query();
            if ($search) {
                $query->where('rencana_aksi', 'like', "%{$search}%")->orWhere('indikator', 'like', "%{$search}%")->orWhere('satuan', 'like', '%{$search}%');
            }

            $data =  $query->where('permasalahan_id', $request->id)->paginate($perPage);
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
            ], 200);
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }
    public function getRenaksiByPermasalahan(Request $request)
    {
        try {
            $data = RencanaAksi::query();
            $data->where('permasalahan_id', $request->id);

            if ($request->years) {

                $data->whereYear('created_at', $request->years);
            }

            if ($request->dateAwal && $request->dateAkhir) {
                $data->where('created_at', '>=', $request->dateAwal)->where('created_at', '<=', $request->dateAkhir);
            }
            $data = $data->get();


            return response()->json(['status' => 'success', 'message' => 'data berhasil', 'data' => $data], 200);
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }


    public function getRenaksiById(Request $request)
    {
        try {
            $data = RencanaAksi::where('id', $request->id)->first();

            $status = 'success';
            $message = 'data berhasil didapat';

            return response()->json([
                'status' => $status,
                'message' => $message,
                'data' => $data
            ], 200);
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }

    public function createRenaksi(Request $request)
    {
        $user =  Auth::user();
        try {
            $request->validate([
                'id' => 'required|string',
                
                'rencana-aksi' => 'required|string',
                'indikator' => 'required|string',
                'satuan' => 'required|string',
                'koordinator' => 'required|string',
                'pelaksana' => 'required|string',
                'unique_namespace' => 'required|string'
            ]);

            $id = Str::uuid();

            RencanaAksi::create([
                'id' =>  $id,
                'permasalahan_id' =>  $request->id,
                'user_id' => $user->id,
                'unique_namespace' => $request->unique_namespace,
                'rencana_aksi' => $request->input('rencana-aksi'),
                'indikator' => $request->indikator,
                'satuan' =>  $request->satuan,
                'koordinator' => $request->koordinator,
                'pelaksana' => $request->pelaksana
            ]);

            return back()->with('success', 'Data berhasil disimpan');
        } catch (ValidationException $e) {
            return back()->with('error', 'ada kesalahan saat proses delete');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()]);
        }
    }

    public function updateRenaksi(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|string',
                'rencana_aksi' => 'required|string',
                'indikator' => 'required|string',
                'satuan' => 'required|string',
                'koordinator' => 'required|string',
                'pelaksana' => 'required|string',
                'unique_namespace' => 'required|string'
            ]);

            $data = RencanaAksi::where('id', $request->id)->first();

            $data->update([
                'rencana_aksi' => $request->input('rencana_aksi'),
                'unique_namespace' => $request->unique_namespace,
                'indikator' => $request->indikator,
                'satuan' => $request->satuan,
                'koordinator' => $request->koordinator,
                'pelaksana' => $request->pelaksana
            ]);




            return back()->with('success', 'Data berhasil di[erbarui');
        } catch (ValidationException $e) {
            return back()->with('error', 'ada kesalahan saat proses delete');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()]);
        }
    }

    public function deleteRenaksi(Request $request)
    {
        try {

            $request->validate([
                'id' => 'required|string',
            ]);

            $data = RencanaAksi::where('id', $request->id)->with('FileAssets')->first();
            if ($data->FileAssets) {
                $this->GDDeleteFile($data->FileAssets->file_path . '/' . $data->FileAssets->file_name);
            }
            $data->delete();


            return back()->with('success', 'Data berhasil dihapus');
        } catch (ValidationException $e) {
            return back()->with('error', 'ada kesalahan saat proses delete');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()]);
        }
    }
    public function exportExcel(Request $request)
    {
        
        $idTema =  $request->id;
        $year = $request->year;

        return Excel::download(new RBExport($idTema, $year), Str::uuid() . '_user.xlsx');
    }

    protected function GDPutFile($folder, $fileName, $request): void
    {
        Gdrive::put($folder . '/' . $fileName, $request->file('file'));
    }
    protected function GDDeleteFile($link): void
    {
        Gdrive::delete($link);
    }

    public function deleteRenaksiById(Request $id)
    {
        try {
            $data = RencanaAksi::where('id', $id)->first();
            $data->delete();
            return back()->with('success', 'Data berhasil dihapus');
        } catch (ValidationException $e) {
            return back()->with('error', 'ada kesalahan saat proses delete');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()]);
        }
    }
}