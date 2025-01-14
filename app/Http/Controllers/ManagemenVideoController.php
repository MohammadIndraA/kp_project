<?php

namespace App\Http\Controllers;

use App\Models\ManagemenVideo;
use App\Models\Multimedia;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ManagemenVideoController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $ManagemenVideo = ManagemenVideo::with('multimedias')->orderBy('id', 'desc');
            return datatables($ManagemenVideo)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $editButton = '';
                    $deleteButton = '';
                
                    // Tambahkan tombol edit jika memiliki izin
                        $editButton = '
                            <button onclick="editFunc(`' . $row->id . '`)" class="btn btn-primary btn-flat btn-sm" title="Edit">
                                <i class="dripicons-document-edit"></i>
                            </button>
                        ';
                
                        $deleteButton = '
                            <button onclick="deleteFunc(`' . $row->id . '`)" class="btn btn-danger btn-flat btn-sm" title="Delete">
                                <i class="dripicons-trash"></i>
                            </button>
                        ';
                
                    // Gabungkan semua tombol dalam satu grup
                    return '
                        <div class="d-flex gap-1">
                            ' . $editButton . '
                            ' . $deleteButton . '
                        </div>
                    ';
                })
                ->addColumn('path', function ($row) {
                    $images = '';
                    foreach ($row->multimedias as $multimedia) {
                        $images .= '<img src="' . asset('storage/' . $multimedia->path) . '" width="50" height="50" alt="Image" class="mr-2">';
                    }
                    return $images;
                })
                ->rawColumns(['action', 'path'])                
                ->make(true);
        }
        return view('managemen-video.index');
    }

    public function store(Request $request)  
    {  
        $validator = Validator::make($request->all(), [  
            'judul' => 'required|string|max:255',  
            'deskripsi' => 'required|string',  
            'path' => 'required|array', // Ubah menjadi array  
            'path.*' => 'file|mimetypes:video/mp4,image/jpeg,image/png|max:10240', // Validasi untuk setiap file  
        ]);  
    
        // Cek validasi  
        if ($validator->fails()) {  
            return response()->json([  
                "status" => false,  
                "errors" => $validator->errors(),  
                "message" => "Validasi data gagal"  
            ], 422);  
        }  
    
        try {  
            // Simpan data ManagemenVideo   
            $managemenVideo = ManagemenVideo::create([  
                'judul' => $request->input('judul'),  
                'deskripsi' => $request->input('deskripsi'),  
            ]);   
    
            // Proses upload multiple files  
            if ($request->hasFile('path')) {   
                $uploadedFiles = $request->file('path');  
                
                foreach ($uploadedFiles as $file) {  
                    $fileName = time() . '_' . $file->getClientOriginalName();  
                    $filePath = $file->storeAs('uploads', $fileName, 'public');  
                    
                    // Simpan setiap file ke tabel Multimedia  
                    Multimedia::create([  
                        'managemen_video_id' => $managemenVideo->id,  
                        'path' => $filePath  
                    ]);  
                }  
    
                return response()->json([  
                    "status" => true,  
                    "data" => $managemenVideo,  
                    "message" => "Data berhasil ditambahkan"  
                ], 201);  
            }  
    
            return response()->json([  
                "status" => false,  
                "message" => "File tidak ditemukan"  
            ], 400);  
    
        } catch (\Exception $e) {  
            // Log error untuk debugging  
            Log::error('Error storing video/image: ' . $e->getMessage());  
    
            return response()->json([  
                "status" => false,  
                "message" => "Terjadi kesalahan saat menyimpan data",  
                "error" => $e->getMessage()  
            ], 500);  
        }  
    }

    public function edit(Request $request)
    {
            $fakultasProdi = ManagemenVideo::with('multimedias')->findOrFail($request->id);
        return response()->json([
            "status" => true,
            "data" => $fakultasProdi,
        ]);
    }

    public function update(Request $request, $id)  
    {  
        // Validasi input  
        $validator = Validator::make($request->all(), [  
            'judul' => 'sometimes|required|string|max:255',  
            'deskripsi' => 'sometimes|required|string',  
            'path.*' => 'file|mimetypes:video/mp4,image/jpeg,image/png|max:10240',   
            'existing_files' => 'sometimes|array', // Untuk mentrack file yang sudah ada  
        ]);  
    
        // Cek validasi  
        if ($validator->fails()) {  
            return response()->json([  
                "status" => false,  
                "errors" => $validator->errors(),  
                "message" => "Validasi data gagal"  
            ], 422);  
        }  
    
        try {  
            // Cari data ManagemenVideo yang akan diupdate  
            $managemenVideo = ManagemenVideo::with('multimedias')->findOrFail($request->id);
            
           
    
            // Update data dasar jika ada  
            $updateData = [];  
            if ($request->has('judul')) {  
                $updateData['judul'] = $request->input('judul');  
            }  
            if ($request->has('deskripsi')) {  
                $updateData['deskripsi'] = $request->input('deskripsi');  
            }  
    
            // Update data dasar jika ada perubahan  
            if (!empty($updateData)) {  
                $managemenVideo->update($updateData);  
            }  
    
            // Proses pengelolaan file  
            foreach ($managemenVideo->multimedias as $multimedia) {
                if (Storage::disk('public')->exists($multimedia->path)) {
                    Storage::disk('public')->delete($multimedia->path);
                    Multimedia::where('managemen_video_id', $managemenVideo->id)->delete();  
                }
            }
            // 2. Tambahkan file baru jika ada  
            if ($request->hasFile('path')) {  
                $uploadedFiles = $request->file('path');  
                
                foreach ($uploadedFiles as $file) {  
                    $fileName = time() . '_' . $file->getClientOriginalName();  
                    $filePath = $file->storeAs('uploads', $fileName, 'public');  
                    
                    // Simpan setiap file baru ke tabel Multimedia  
                    Multimedia::create([  
                        'managemen_video_id' => $managemenVideo->id,  
                        'path' => $filePath  
                    ]);  
                }  
            }  
    
            // Ambil data multimedia terbaru  
            $updatedMultimedia = Multimedia::where('managemen_video_id', $managemenVideo->id)->get();  
    
            return response()->json([  
                "status" => true,  
                "data" => [  
                    "managemen_video" => $managemenVideo,  
                    "multimedia" => $updatedMultimedia  
                ],  
                "message" => "Data berhasil diupdate"  
            ], 200);  
    
        } catch (\Exception $e) {  
            // Log error untuk debugging  
            Log::error('Error updating video/image: ' . $e->getMessage());  
    
            return response()->json([  
                "status" => false,  
                "message" => "Terjadi kesalahan saat mengupdate data",  
                "error" => $e->getMessage()  
            ], 500);  
        }  
    }
    
    public function destroy(Request $request)
    {
        try {
            $managemenVideo = ManagemenVideo::with('multimedias')->findOrFail($request->id);
            
            foreach ($managemenVideo->multimedias as $multimedia) {
                if (Storage::disk('public')->exists($multimedia->path)) {
                    Storage::disk('public')->delete($multimedia->path);
                }
            }
            
            $managemenVideo->delete();
            
            return response()->json([
                "status" => true,
                "message" => "Data Manajemen Video berhasil dihapus"
            ], 200); // HTTP Status 200 untuk sukses
        } catch (ModelNotFoundException $e) {
            return response()->json([
                "status" => false,
                "message" => "Data Manajemen Video tidak ditemukan",
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
