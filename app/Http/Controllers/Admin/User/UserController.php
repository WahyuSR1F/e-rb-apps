<?php

namespace App\Http\Controllers\Admin\User;

use Exception;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Helpers\HelpersController;

class UserController extends Controller
{
    public function  check () {
     if(Auth::check()){
        return true;
     }
     return false;
        
    }
    public function getPaginate(Request $request)
    {
        
        try{
        $startTime = microtime(true);
        $search = $request->input('search');
        $perPage =  $request->input('perPage', 10);

        $cluster = User::query();
        if ($search) {
            $cluster->where('nama', 'like', "%{$search}%")->orWhere('subidang',  'like', "%{$search}%");
        }

        $data = $cluster->paginate($perPage);
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

    public function getUserById (Request $request){
        try{
            $data =  User::where('id', $request->id)->first();
            return response()->json(['data' => $data],200);

        }catch (Exception $e){
           return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }

 
    public function create (Request $request) {
            try {
            $request->validate([
                'username' => 'required|string',
                'password' => 'required|string|confirmed',
                'active_user' => 'required|boolean',
                'nama' =>  'required|string',
                'subidang' =>'required|string',
                'IdPegawai' => 'required|string',
            ]);

            User::create([
                'id' => Str::uuid(),
                'username' => $request->username,
                'password' => bcrypt($request->password),
                'nama' =>  $request->nama,
                'active_user' => $request->active_user,
                'subidang' => $request->subidang,
                'IdPegawai' => $request->IdPegawai,
                'token' => Str::random(10),
                'role' => 'user',
            ]);
            return back()->with('success', 'Data berhasil disimpan');
        } catch (ValidationException $e) {
            return back()->with('error', 'Inputan wajib diisi');
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }


    }
    public function update (Request $request){
        try {
           
            $request->validate([
                'id' => 'required|string',
                'username' => 'required|string',
                'active_user' => 'required|boolean',
                'nama' =>  'required|string',
                'subidang' =>'required|string',
                'IdPegawai' => 'required|string',
            ]);
            $user = User::where('id', $request->id)->first();
            $user->update([
                'username' => $request->username,
                'nama' =>  $request->nama,
                'active_user' => $request->active_user,
                'subidang' => $request->subidang,
                'IdPegawai' => $request->IdPegawai,
            ]);
            return back()->with('success', 'Data berhasil diperbarui');
        } catch (ValidationException $e) {
            return back()->with('error', 'Inputan wajib diisi');
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }

    }

    public function reset (Request $request){
        try {
            if(Auth::user()->role == 'admincs'){
                $request->validate([
                    'password' => 'required|string|confirmed',
                ]);
                $user = User::where('id', $request->id)->first();
                $user->update([
                    'password' => bcrypt($request->password),
                ]);
                return back()->with('success', 'Data berhasil direset');
            }
            return back()->with('error', 'anda tida memiliki akses ini');
          
        } catch (ValidationException $e) {
            return back()->with('error', 'Inputan wajib diisi');
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }

    }


    public function delete(Request $request)
    {
        try {
            // Validasi input ID
            $request->validate([
                'id' => 'required|string',
            ]);
    
            // Cari user berdasarkan ID
            $user = User::where('id', $request->id)->first();
            if (!$user) {
                return back()->with('error', 'User tidak ditemukan');
            }
    
            // Hapus user
            $user->delete();
    
            // Jika berhasil, kembalikan pesan sukses
            return back()->with('success', 'Data berhasil dihapus');
    
        } catch (ValidationException $e) {
            // Jika validasi gagal
            return back()->with('error', 'Inputan wajib diisi');
        } catch (Exception $e) {
            if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
                return back()->with('error', 'User ini memiliki arsip di rencana birokrasi, penghapusan tidak dapat dilakukan.');
            }
            // Error umum lainnya
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }
    
}
