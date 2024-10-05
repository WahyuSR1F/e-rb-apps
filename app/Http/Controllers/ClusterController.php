<?php

namespace App\Http\Controllers;

use App\Models\Cluster;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Helpers\HelpersController;

class ClusterController extends Controller
{
    public function get(Request $request)
    {
        
        $cluster = Cluster::all();

        return response()->json(['data' => $cluster]);
    }

    public function getPaginate (Request $request){
        try{
            $startTime =  microtime(true);
    
            // ambil query untuk pagination dan search
            $search = $request->input('search');
            $perPage =  $request->input('perPage', 10);
    
            $cluster = Cluster::query();
            if ($search) {
                $cluster->where('cluster', 'like', "%{$search}%");
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
    public function getById(Request $request)
    {
        $cluster = Cluster::where('id', $request->id)->first();

        return response()->json(['data' => $cluster]);
    }

    public function create(Request $request)
    {
        try {
        $request->validate([
            'nama' => 'string|required'
        ]);

        $cluster = Cluster::create([
            'id' => Str::uuid(),
            'cluster' =>  $request->nama
        ]);

        return back()->with('success','data berhasil disimpan');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan saat proses update');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request)
    {
        try{
        $request->validate([
            'id' => 'required|string',
            'cluster' => 'string|required'
        ]);

        $cluster = Cluster::where('id', $request->id)->update([
            'cluster' =>  $request->cluster
        ]);

        return back()->with('success','data berhasil diperbarui');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan saat proses update');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }

    public function delete(Request $request)
    {
        try{
        $request->validate([
            'id' => 'required|string',
        ]);

        $cluster = Cluster::where('id', $request->id)->delete();
        return back()->with('success','data berhasil dihapus');
        } catch (ValidationException $e) {
            return back()->with('error', 'Ada kesalahan saat proses update');
        } catch (Exception $e) {
            return response()->json(['messesage' => $e->getMessage()], 500);
        }
    }
}