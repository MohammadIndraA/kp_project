<?php

namespace App\Http\Controllers;

use App\Http\Requests\PermissionRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionController extends Controller
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view-permission', ['only' => ['index','show']]),
            new Middleware('permission:create-permission', ['only' => ['create','store']]),
            new Middleware('permission:edit-permission', ['only' => ['edit','update']]),
            new Middleware('permission:delete-permission', ['only' => ['destroy']]),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permission = Permission::orderBy('id', 'desc');
            return datatables($permission)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editButton = '';
                    $deleteButton = '';
                
                    // Tambahkan tombol edit jika memiliki izin
                    if (auth()->user()->can('edit-permission')) {
                        $editButton = '
                            <button onclick="editFunc(`' . $row->id . '`)" class="btn btn-primary btn-flat btn-sm" title="Edit">
                                <i class="dripicons-document-edit"></i>
                            </button>
                        ';
                    }
                
                    // Tambahkan tombol delete jika memiliki izin
                    if (auth()->user()->can('delete-permission')) {
                        $deleteButton = '
                            <button onclick="deleteFunc(`' . $row->id . '`)" class="btn btn-danger btn-flat btn-sm" title="Delete">
                                <i class="dripicons-trash"></i>
                            </button>
                        ';
                    }
                
                    // Gabungkan semua tombol dalam satu grup
                    return '
                        <div class="btn-group">
                            ' . $editButton . '
                            ' . $deleteButton . '
                        </div>
                    ';
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('permissions.index');
    }

    public function store(PermissionRequest $request)
    {
        try {
            // Data sudah tervalidasi melalui PermissionRequest
            $permission = Permission::create($request->validated());
            return response()->json([
                "status" => true,
                "data" => $permission,
                "message" => "Data Permission berhasil ditambahkan"
            ], 201); // HTTP Status 201 untuk created
        } catch (\Exception $e) {
            // Tangkap error yang terjadi
            return response()->json([
                "status" => false,
                "message" => "Terjadi kesalahan saat menyimpan data",
                "error" => $e->getMessage(), // Pesan error untuk debugging
            ], 500); // HTTP Status 500 untuk internal server error
        }
    }

    public function edit(Request $request)
    {
            $permission = Permission::findOrFail($request->id);
        return response()->json([
            "status" => true,
            "data" => $permission,
        ]);
    }

    public function update(PermissionRequest $request, $id)
    {
        try {
            $permission = Permission::findOrFail($id);
            $data = $request->validated();  

            $permission->update($data); 

            return response()->json([
                "status" => true,
                "data" => $permission,
                "message" => "Data Permission berhasil diupdate"
            ], 200); // HTTP Status 200 untuk sukses
        } catch (ModelNotFoundException $e) {
            // Jika user dengan ID tersebut tidak ditemukan
            return response()->json([
                "status" => false,
                "message" => "Data Permission tidak ditemukan",
            ], 404); // HTTP Status 404 untuk not found
        } catch (\Exception $e) {
            // Tangkap error lain yang mungkin terjadi
            return response()->json([
                "status" => false,
                "message" => "Terjadi kesalahan saat mengupdate data",
                "error" => $e->getMessage(), // Opsional, untuk debugging
            ], 500); // HTTP Status 500 untuk internal server error
        }
    }
    
    public function destroy(Request $request)
    {
        try {
            $permission = Permission::findOrFail($request->id);
            $permission->delete();
            return response()->json([
                "status" => true,
                "message" => "Data Permission berhasil dihapus"
            ], 200); // HTTP Status 200 untuk sukses
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "status" => false,
                "message" => "Data Permission tidak ditemukan",
            ], 404); // HTTP Status 404 untuk not found
        } catch (\Exception $e) {
            return response()->json([
                "status" => false,
                "message" => "Terjadi kesalahan saat menghapus data",
                "error" => $e->getMessage(), // Opsional, untuk debugging
            ], 500); // HTTP Status 500 untuk internal server error
        }
    }
}
