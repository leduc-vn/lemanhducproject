<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Face;

class FaceController extends Controller
{

    public function index()
    {
        return view('upload');
    }

    public function history()
    {
        $faces = Face::latest()->get();

        return view('history', compact('faces'));
    }

    public function dashboard()
    {
        $totalImages = Face::count();
        $totalFaces = Face::sum('faces_count');

        return view('dashboard', compact('totalImages','totalFaces'));
    }

    public function detect(Request $request)
    {

        if(!$request->hasFile('image')){
            return back()->with('error','Không có ảnh được gửi.');
        }

        $image = $request->file('image');

        $flaskUrl = rtrim(config('services.flask.url'), '/').'/detect';

        $response = Http::attach(
            'image',
            file_get_contents($image),
            $image->getClientOriginalName()
        )->post($flaskUrl);

        if(!$response->successful()){
            return back()->with('error','Flask API không phản hồi.');
        }

        $data = $response->json();

        Face::create([
            'faces_count' => $data['faces_detected'],
            'image' => $data['image']
        ]);

        return view('upload', [
            'faces' => $data['faces_detected'],
            'image' => $data['image']
        ]);
    }

}
