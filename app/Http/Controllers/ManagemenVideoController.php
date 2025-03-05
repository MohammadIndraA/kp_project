<?php

namespace App\Http\Controllers;

use App\Models\ManagemenVideo;
use App\Models\Multimedia;
use FFMpeg\FFMpeg;
use FFMpeg\Filters\Video\WatermarkFilter;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controllers\Middleware;

class ManagemenVideoController extends Controller
{
    public static function middleware()
    {
        return [
            new Middleware('permission:view-video', ['only' => ['index','show']]),
            new Middleware('permission:create-video', ['only' => ['create','store']]),
            new Middleware('permission:edit-video', ['only' => ['edit','update']]),
            new Middleware('permission:delete-video', ['only' => ['destroy']]),
        ];
    }

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
                    if (auth()->user()->can('edit-video')) {
                        $editButton = '
                        <button onclick="editFunc(`' . $row->id . '`)" class="btn btn-primary btn-flat btn-sm" title="Edit">
                        <i class="dripicons-document-edit"></i>
                        </button>
                        ';
                    }
                
                        // Tambahkan tombol show jika memiliki izin
                            $showButton = '
                            <button onclick="showFunc(`' . $row->id . '`)" class="btn btn-secondary btn-flat btn-sm" title="Show">
                            <i class="mdi mdi-eye"></i>
                            </button>
                            ';
                            
                
                        // Tambahkan tombol delete jika memiliki izin
                        if (auth()->user()->can('delete-video')) {
                            $deleteButton = '
                            <button onclick="deleteFunc(`' . $row->id . '`)" class="btn btn-danger btn-flat btn-sm" title="Delete">
                            <i class="dripicons-trash"></i>
                            </button>
                            ';
                            }
                
                    // Gabungkan semua tombol dalam satu grup
                    return '
                        <div class="d-flex gap-1">
                            ' . $editButton . '
                            ' . $showButton . '
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
                ->editColumn('status', function ($row) {
                    return $row->status ? '<span class="badge bg-success rounded-pill">Aktif</span>' :  '<span class="badge bg-danger rounded-pill">Tidak Aktif</span>';
                })
                ->rawColumns(['action', 'path','status'])                
                ->make(true);
        }
        return view('managemen-video.index');
    }

    public function store(Request $request)  
    {  
        $validator = Validator::make($request->all(), [  
            'judul' => 'required|string|max:255',  
            'deskripsi' => 'required|string|max:140',  
            'status' => 'required|boolean',
            'path' => 'required|array', // Ubah menjadi array  
            'path.*' => 'file|mimetypes:video/mp4,image/jpeg,image/png|max:10240', // Validasi untuk setiap file  
        ]);  
        
        // Check validation  
        if ($validator->fails()) {  
            return response()->json([  
                "status" => false,  
                "errors" => $validator->errors(),  
                "message" => "Validasi data gagal"  
            ], 422);  
        }  
        
        try {  
            // Save data to ManagemenVideo   
            $managemenVideo = ManagemenVideo::create([  
                'judul' => $request->input('judul'),  
                'deskripsi' => $request->input('deskripsi'), 
                'status' => $request->input('status')
            ]);   
        
            // Process multiple file uploads  
            if ($request->hasFile('path')) {   
                $uploadedFiles = $request->file('path');  
                
                foreach ($uploadedFiles as $file) {  
                    $fileName = time() . '_' . $file->getClientOriginalName();  
                    $filePath = $file->storeAs('uploads', $fileName, 'public');  
                    
                    // Call make_vid with the correct file path  
                    $this->video($request->title, $request->description); // Pass the file path to make_vid  
                    
                    // Save each file to Multimedia table  
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
            // Log error for debugging  
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
            'status' => 'required|boolean',
            'path.*' => 'file|mimetypes:video/mp4,image/jpeg,image/png|max:10240',   
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
            $managemenVideo = ManagemenVideo::with('multimedias')->findOrFail($id);  
            
            // Update data dasar jika ada  
            $updateData = [];  
            if ($request->has('judul')) {  
                $updateData['judul'] = $request->input('judul');  
            }  
            if ($request->has('deskripsi')) {  
                $updateData['deskripsi'] = $request->input('deskripsi');  
            }  
            if ($request->has('status')) {  
                $updateData['status'] = $request->input('status');  
            }  
    
            // Update data dasar jika ada perubahan  
            if (!empty($updateData)) {  
                $managemenVideo->update($updateData);  
            }  
    
            // Proses pengelolaan file  
            if ($request->hasFile('path')) {  
                // Hapus file lama  
                foreach ($managemenVideo->multimedias as $multimedia) {  
                    if (Storage::disk('public')->exists($multimedia->path)) {  
                        Storage::disk('public')->delete($multimedia->path);  
                        $multimedia->delete();  // Hapus multimedia yang ada  
                    }  
                }  
    
                // Tambahkan file baru  
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
    private function makeVideo($imagePath, $title, $description)
    {
        $videoPath = 'videos/' . uniqid() . '.mp4';
        $ffmpeg = FFMpeg::create([
            'ffmpeg.binaries'  => 'D:\ffmpeg\bin\ffmpeg.exe',
            'ffprobe.binaries' => 'D:\ffmpeg\bin\ffprobe.exe',
            'timeout'          => 3600, // The timeout for the underlying process
            'ffmpeg.threads'   => 12,   // The number of threads to use
        ]);
    
        try {
            $video = $ffmpeg->open($imagePath); // Use $request->file('path')->getRealPath()
    
            $video
                ->filter(function ($filters) use ($title, $description) {
                    $filters
                        ->complexFilter([
                            // Display description text at the bottom with height 15% of the video height
                            "movie=text='{$description}':x=(w-tw)/2:y=h-(2*lh):fontcolor=white:fontsize=16:enable='between(t,0,10)'",
                            // Display title text on the right with width 10% of the video width
                            "movie=text='{$title}':x=w-tw-10:y=(h-th)/2:fontcolor=white:fontsize=20:enable='between(t,0,10)'",
                            // Scale the image/video to fit within 640x360
                            "scale=w=640:h=360:force_original_aspect_ratio=decrease,pad=640:360:(ow-iw)/2:(oh-ih)/2,setsar=1"
                        ]);
                })
                ->save(public_path($videoPath)); // Ensure the path is public
    
            return $videoPath;
        } catch (\Exception $e) {
            Log::error('Error processing video/image: ' . $e->getMessage());
            throw $e;
        }
    }

    private function make_vid($filePath = "uploads/1738238996_249475_small.mp4")  
    {  
        // Initialize the FFMpeg instance  
        $ffmpeg = \FFMpeg\FFMpeg::create([  
            'ffmpeg.binaries'  => 'D:\ffmpeg\bin\ffmpeg.exe',  
            'ffprobe.binaries' => 'D:\ffmpeg\bin\ffprobe.exe',  
            'timeout'          => 3600, // The timeout for the underlying process  
            'ffmpeg.threads'   => 12,   // The number of threads to use  
        ]);  
        
        try {  
            // Check if the video file exists  
            // if (!Storage::disk('public')->exists($filePath)) {  
            //     Log::error('Video file does not exist: ' . $filePath);  
            //     return;  
            // }  
    
            $watermarkPath = 'uploads/save.png';  
            // Check if the watermark file exists  
            if (!Storage::disk('public')->exists($watermarkPath)) {  
                Log::error('Watermark file does not exist: ' . $watermarkPath);  
                return;  
            }  
    
            // Open the video file using the provided path  
            $video = $ffmpeg->open($filePath);  
        
            // Add the watermark  
            $video->filters()->watermark($watermarkPath, array(  
                'right' => 25,  
                'bottom' => 25  
            ));  
        
            // Export the video  
            $format = new \FFMpeg\Format\Video\X264();  
            $outputPath = 'uploads/' . time() . '_with_watermark.mp4'; // Create a unique output path  
            $video->save($format, $outputPath);  
        
            // Log success  
            Log::info('Watermark added successfully to video: ' . $outputPath);  
        } catch (\Exception $e) {  
            // Log error if unable to process video  
            Log::error('Error processing video: ' . $e->getMessage());  
            Log::error('File Path: ' . $filePath); // Log the file path for debugging  
        }  
    }
  
    public function video($title, $description) {  
        // Paths to input and output files  
        $inputFilePath = 'C:/Users/Dell/Downloads/small.mp4';  
        $outputFilePath = 'C:/xampp/htdocs/Aplication/kp_project/public/videos/processed_video.mp4';  
    
        // Check if input file exists  
        if (!file_exists($inputFilePath)) {  
            return response()->json(['error' => 'Input file not found.'], 404);  
        }  
    
        // Construct the FFmpeg command  
        $command = 'C:\ffmpeg\bin\ffmpeg.exe -i ' . escapeshellarg($inputFilePath) .   
                   ' -vf "drawbox=y=0:color=black@1:width=iw:height=15%:t=fill,' .   
                   'drawtext=text=' . escapeshellarg($title) . ':fontcolor=white:box=1:boxcolor=black@0.5:x=(w-text_w)/2:y=5,' .   
                   'drawbox=y=h-15%:color=black@1:width=iw:height=15%:t=fill,' .   
                   'drawtext=text=' . escapeshellarg($description) . ':fontcolor=white:box=1:boxcolor=black@0.5:x=(w-text_w)/2:y=h-15%-5"' .   
                   ' -c:v libx264 -y ' . escapeshellarg($outputFilePath);  
    
        // Execute the command  
        exec($command . ' 2>&1', $output, $returnVar);  
    
        // Check execution result  
        if ($returnVar !== 0) {  
            \Log::error('FFmpeg encoding failed: ' . implode("\n", $output));  
            return response()->json(['error' => 'Encoding failed: ' . implode("\n", $output)], 500);  
        }  
    
        return response()->json(['success' => 'Video processed successfully.']);  
    }
    }   
    
    
