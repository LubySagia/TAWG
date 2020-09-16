<?php

namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use Illuminate\Support\Str;
use Image;
use Storage;
use Illuminate\Support\Facades\URL;
class ImageController extends APIController
{
    public function upload(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:png,gif,jpeg,jpg,bmp'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $photo = $request->file;
        $originalName = $photo->getClientOriginalName();
        $extension = $photo->getClientOriginalExtension();
        $originalNameWithoutExt = substr($originalName, 0, strlen($originalName) - strlen($extension) - 1);

        $filename = $this->sanitize($originalNameWithoutExt);

        $allowed_filename = Str::random(10).time().".". $extension;
        Storage::disk('uploads')->put("/data/".date('Ymd',time())."/".$allowed_filename,file_get_contents($photo));
        $img = Image::make(public_path()."/data/".date('Ymd',time())."/".$allowed_filename);
        if ($extension!="gif"&&$extension!=".gif") {
           $img->save(public_path()."/data/".date('Ymd',time())."/".$allowed_filename,100);
        }
        if( !$img ) {
            return $this->sendError('Có lỗi xảy ra trong quá trình upload ảnh',[], 500);
        }
         return $this->sendResponse(URL::to('/').'/data/'.date('Ymd',time())."/".$allowed_filename,'Upload ảnh thành công');
    }
    
    private function sanitize($string, $force_lowercase = true, $anal = false)
    {
        $strip = array("~", "`", "!", "@", "#", "$", "%", "^", "&", "*", "(", ")", "_", "=", "+", "[", "{", "]",
            "}", "\\", "|", ";", ":", "\"", "'", "&#8216;", "&#8217;", "&#8220;", "&#8221;", "&#8211;", "&#8212;",
            "â€”", "â€“", ",", "<", ".", ">", "/", "?");
        $clean = trim(str_replace($strip, "", strip_tags($string)));
        $clean = preg_replace('/\s+/', "-", $clean);
        $clean = ($anal) ? preg_replace("/[^a-zA-Z0-9]/", "", $clean) : $clean ;

        return ($force_lowercase) ?
            (function_exists('mb_strtolower')) ?
                mb_strtolower($clean, 'UTF-8') :
                strtolower($clean) :
            $clean;
    }
}
