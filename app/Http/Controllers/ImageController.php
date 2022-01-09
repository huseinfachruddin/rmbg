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
    public function test(Request $request){
        // $data = exec('rembg -o ./images/test.png ./upload/GcFVd2XX0T.jpg',$o,$i);
        $data = exec('whoami8',$o,$i);8800444444440
        dd([$data,$o,$i]);
    }
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
        });
        $compress->save(public_path('/upload').'/'.$upload);

        // exec('rembg -o '.'./images/'.$name.'.png ./upload/'.$upload,$out, $retval);
        exec('ls',$out, $retval);
        // // File::delete('./upload/'.$upload);
        // if (!empty($retval)) {
        //     $response = [
        //         'success'   => false,
        //         'errors'      => [$out,$retval],
        //         'data'=> 'rembg -o '.'./images/'.$name.'.png ./upload/'.$upload
        //     ];
        //     return response($response,402); 
        // }
        $response = [
            'success'   => true,
            'image'      => url('/').'/images/'.$name.'.png',
            'data'=>[$out,$retval]
        ];

        return response($response,200); 
    }

    public function removeUrl(Request $request){
        $request->validate([
            'name' => 'nullable',
            'content' => 'required|url',
        ]);
        if (empty($request->name)){
            $name = Str::random(10);
        }else{
            $name = $request->input('name',Str::random(10));
        }
        
        $data = file_get_contents($request->content);

        Storage::disk('public')->put($name.'.jpg', $data);

        exec('rembg -o '.'./images/'.$name.'.png ./upload/'.$name.'.jpg',$out, $retval);

        if (!empty($retval)) {
            $response = [
                'success'   => false,
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
