<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Routing\Controllers\Middleware;

class RoleController extends Controller
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view-role', ['only' => ['index','show']]),
            new Middleware('permission:create-role', ['only' => ['create','store']]),
            new Middleware('permission:edit-role', ['only' => ['edit','update']]),
            new Middleware('permission:delete-role', ['only' => ['destroy']]),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $permission = Role::orderBy('id', 'desc');
            return datatables($permission)
                ->addIndexColumn()
                ->addColumn('permissions', function ($role) {
                    $badges = $role->permissions->pluck('name')->map(function ($permission) {
                        return '<span class="badge badge-outline-primary rounded-pill">' . $permission . '</span>';
                    })->implode(' '); // Gabungkan setiap badge dengan spasi antar badge
                
                    return $badges;
                })                
                ->addColumn('action', function ($row) {
                    $editButton = '';
                    $deleteButton = '';
                
                    // Tambahkan tombol edit jika memiliki izin
                    if (auth()->user()->can('edit-role')) {
                        $editButton = '
                            <button onclick="editFunc(`' . $row->id . '`)" class="btn btn-primary btn-flat btn-sm" title="Edit">
                                <i class="dripicons-document-edit"></i>
                            </button>
                        ';
                    }
                
                    // Tambahkan tombol delete jika memiliki izin
                    if (auth()->user()->can('delete-role')) {
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
                ->rawColumns(['action', 'permissions'])
                ->make(true);
        }
        $permissions = Permission::all();
        return view('roles.index', compact('permissions'));
    }

    public function store(Request $request)
    {
        try {
            $permissions = Permission::whereIn('id', $request->permissions)->get(['name'])->toArray();
            $role = Role::create(['name' => $request->input('name')]);
            $role->syncPermissions($permissions);
            return response()->json([
                "status" => true,
                "message" => "Data role berhasil ditambahkan",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage(),
            ]);
        }
    }

    public function edit(Request $request) {
        try {
            $role = Role::findOrFail($request->id);
            $rolePermissions = Permission::join("role_has_permissions","role_has_permissions.permission_id","=","permissions.id")
            ->where("role_has_permissions.role_id",$request->id)
            ->get();
            $permissions = Permission::all();
            return response()->json([
                "status" => true,
                "role" => $role,
                "rolePermissions" => $rolePermissions,
                "permissions" => $permissions
            ]);

        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage(),
            ]);
        }
    }

    public function update(Request $request) {
        try {
            $role = Role::findOrFail($request->id);
            $input = $request->only('name');
             $role->update($input);
             $permissionsID = array_map(
                function($value) { return (int)$value; },
                $request->input('permissions')
            );
        
            $role->syncPermissions($permissionsID);
            return response()->json([
                "status" => true,
                "message" => "Data role berhasil diupdate",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage(),
            ]);
        }
    }

    public function destroy(Request $request) {
        try {
            $role = Role::findOrFail($request->id);
            $role->delete();
            return response()->json([
                "status" => true,
                "message" => "Data role berhasil dihapus",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => false,
                "message" => $th->getMessage(),
            ]);
        }
    }
}
