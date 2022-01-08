<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Illuminate\Support\Str;
use Image;
use Illuminate\Support\Facades\File;
use Storage;

class ImageController extends Controller
{
    public function removeFile(Request $request){
        $request->validate([
            'name' => 'nullable',
            'content' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        if (empty($request->name)){
            $name = Str::random(10);
        }else{
            $name = $request->input('name',Str::random(10));
        }
        $image = $request->file('content');
        
        $upload = $name.'.'.$image->getClientOriginalExtension();
        $compress = Image::make($image->getRealPath());

        $compress->resize(500, null, function ($constraint) {
            $constraint->aspectRatio();
        })->save(public_path('/upload').'/'.$upload);

        exec('rembg -o '.'./images/'.$name.'.png ./upload/'.$upload,$out, $retval);
        
        File::delete('./upload/'.$upload);
        if (!empty($retval)) {
            $response = [
                'success'   => true,
                'errors'      => [$out,$retval],
            ];
            return response($response,402); 
        }
        $response = [
            'success'   => true,
            'image'      => url('/').'/images/'.$name.'.png'
        ];

        return response($response,200); 
    }

    public function removeUrl(Request $request){
        $request->validate([
            'name' => 'nullable',
            'content' => 'required|url',
        ]);
        $name = $request->input('name',Str::random(10));
        $image = $request->file('content');
        
        $url = "http://www.google.co.in/intl/en_com/images/srpr/logo1w.png";
        $data = file_get_contents($url);

        Storage::disk('public')->put($name.'.jpg', $data);

        exec('rembg -o '.'./images/'.$name.'.png ./upload/'.$name.'.jpg',$out, $retval);

        if (!empty($retval)) {
            $response = [
                'success'   => true,
                'errors'      => [$out,$retval],
            ];
            return response($response,402); 
        }

        $response = [
            'success'   => true,
            'image'      => url('/').'/images/'.$name.'.png'
        ];

        return response($response,200); 
    }

    public function removeFolder(Request $request){
        $request->validate([
            'path' => 'required'
        ]);
        
        $input = $request->path;
        $output = $request->input('output','images');
        
        exec('rembg -p '.'./'.$input.' '.'./'.$output,$out, $retval);    
        if (!empty($retval)) {
            $response = [
                'success'   => true,
                'errors'      => [$out,$retval],
            ];
            return response($response,402); 
        }
        $response = [
            'success'   => true,
            'image'      => url('/').'/'.$output,
        ];

        return response($response,200); 
    }
}
