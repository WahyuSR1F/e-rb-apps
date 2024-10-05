<?php

namespace App\Http\Controllers;

use App\Models\ERBType;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Helpers\HelpersController;

class TemaController extends Controller
{
    public function getType(Request $request)
    {
        $startTime = microtime(true);
        $data = ERBType::query();
        if ($request && $request->id) {
            $data = $data->where('cluster_id', $request->id)->get();
        }
        $data = $data->all();



        $status = 'berhasil';
        $message = 'data berhasil didapat';

        $endtime = microtime(true);
        $time_access =  $endtime -  $startTime;

        return response()->json([
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'time_access' => $time_access
        ], 200);
    }
    public function getId(Request $request)
    {
        try{
        $startTime = microtime(true);
        $data = ERBType::where('cluster_id', $request->id)->orWhere('id', $request->id)->get();

        $status = 'berhasil';
        $message = 'data berhasil didapat';

        $endtime = microtime(true);
        $time_access =  $endtime -  $startTime;

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

    public function geByIdFirst (Request $request)
    {
        try{
        $startTime = microtime(true);
        $data = ERBType::where('id', $request->id)->with('cluster')->first();

        $status = 'berhasil';
        $message = 'data berhasil didapat';

        $endtime = microtime(true);
        $time_access =  $endtime -  $startTime;

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


    public function getPaginate(Request $request)
    {
        try{
        $startTime = microtime(true);
        $search = $request->input('search');
        $perPage =  $request->input('perPage', 10);

        $cluster = ERBType::query()->with('cluster');
        if ($search) {
            $cluster->where('nama', 'like', "%{$search}%");
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

    public function create (Request $request) {
        
        try {
        $request->validate([
            'nama' =>  'required|string',
            'cluster_id' =>'required|string',
        ]);

        ERBType::create([
            'id' => Str::uuid(),
            'nama' =>  $request->nama,
            'cluster_id' => $request->cluster_id,
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
                'nama' =>  'required|string',
                'cluster_id' =>'required|string',
            
            ]);
            $tema = ERBType::where('id', $request->id)->first();
            $tema->update([
                'nama' =>  $request->nama,
                'cluster_id' =>$request->cluster_id,
            ]);
            return back()->with('success', 'Data berhasil diperbarui');
        } catch (ValidationException $e) {
            return back()->with('error', 'Inputan wajib diisi');
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }

    }
    public function delete (Request $request) {
        try {
            $request->validate([
                'id' => 'required|string',
            ]);
            $user = ERBType::where('id', $request->id)->first();
            $user->delete();
            return back()->with('success', 'Data berhasil dihapus');
        } catch (ValidationException $e) {
            return back()->with('error', 'Inputan wajib diisi');
        } catch (Exception $e) {
            return response()->json(['error' => true, 'message' => $e->getMessage()], 500);
        }
    }
}