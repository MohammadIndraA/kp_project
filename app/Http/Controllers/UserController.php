<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
class UserController extends Controller
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view-user', ['only' => ['index','show']]),
            new Middleware('permission:create-user', ['only' => ['create','store']]),
            new Middleware('permission:edit-user', ['only' => ['edit','update']]),
            new Middleware('permission:delete-user', ['only' => ['destroy']]),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $users = User::select('*');
            return datatables($users)
                ->addIndexColumn()
                ->addColumn('roles', function ($user) {
                    // Ambil nama role sebagai array
                    return $user->getRoleNames()->toArray();
                })
                ->addColumn('created_at', function ($user) {
                    return $user->created_at->format('d M Y');
                })
                ->addColumn('action', function ($row) {
                    $editButton = '';
                    $deleteButton = '';
                
                    // Tambahkan tombol edit jika memiliki izin
                    if (auth()->user()->can('edit-user')) {
                        $editButton = '
                            <button onclick="editFunc(`' . $row->id . '`)" class="btn btn-primary btn-flat btn-sm" title="Edit">
                                <i class="dripicons-document-edit"></i>
                            </button>
                        ';
                    }
                
                    // Tambahkan tombol delete jika memiliki izin
                    if (auth()->user()->can('delete-user')) {
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
                ->rawColumns(['action', 'roles'])
                ->make(true);
        }
        $roles =  Role::pluck('name')->all();
        return view('users.index', compact('roles'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors(),
                "message" => "Validasi data gagal"
            ], 422);
        }
        try {
            // Data sudah tervalidasi melalui UserRequest
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
            ]);
            $user->assignRole($request->roles);
            return response()->json([
                "status" => true,
                "data" => $user,
                "message" => "Data user berhasil ditambahkan"
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
        $user = User::findOrFail($request->id);
        $role = $user->getRoleNames()->toArray();

        return response()->json([
            "status" => true,
            "data" => $user,
            "role" => $role
        ]);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "status" => false,
                "errors" => $validator->errors(),
                "message" => "Validasi data gagal"
            ], 422);
        }
        try {
            $user = User::findOrFail($id);
            $data = $request->all();  

            // Jika password kosong, ambil password yang dahulu di database  
            if (empty($data['password'])) {  
                unset($data['password']);  
            }  

            $user->update($data); 
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $user->assignRole($request->input('roles'));

            return response()->json([
                "status" => true,
                "data" => $user,
                "message" => "Data user berhasil diupdate"
            ], 200); // HTTP Status 200 untuk sukses
        } catch (ModelNotFoundException $e) {
            // Jika user dengan ID tersebut tidak ditemukan
            return response()->json([
                "status" => false,
                "message" => "Data user tidak ditemukan",
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
            $user = User::findOrFail($request->id);
            $user->syncRoles([]);
            $user->delete();
            return response()->json([
                "status" => true,
                "message" => "Data user berhasil dihapus"
            ], 200); // HTTP Status 200 untuk sukses
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "status" => false,
                "message" => "Data user tidak ditemukan",
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
