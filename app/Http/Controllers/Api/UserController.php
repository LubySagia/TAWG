<?php

namespace App\Http\Controllers\Api;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Image;
use Storage;
use Illuminate\Support\Facades\URL;
use App\Models\PasswordReset;
use App\Notifications\ResetPasswordRequest;
use App\Models\LeaderClub;
use App\Models\PointLog;

class UserController extends APIController
{
    /**
     * [create new user]
     * @param  Request $request 
     * @return [json]           
     */
	public function create(Request $request)
    {
        // validator input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:150|unique:users',
            'password' => 'required|min:6|max:200',
            'first_name'=> 'max:150',
            'last_name'=> 'max:150',
            'phone'=> 'max:150',
            'gender'=> 'max:50',
            'birthday'=> 'date|date_format:Y-m-d',
            'address'=> 'max:200',
            'job'=> 'max:150',
            'level'=> 'max:150',
        ]);

        // return validator input have error
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=new User;
        $record->email=$request->email;
        $record->password=bcrypt($request->password);
        $record->first_name=$request->first_name;
        $record->last_name=$request->last_name;
        $record->phone=$request->phone;
        $record->gender=$request->gender;
        if($request->birthday!=null)
        {
            $record->birthday=Carbon::parse($request->birthday)->format('Y-m-d');
        }
        $record->address=$request->address;
        $record->job=$request->job;
        $record->level=$request->level;
        if($record->save())
        {
            return $this->sendResponse($record,'Thêm thành viên mới thành công.');
        }
        else{
            return $this->sendError('Thêm thành viên mới thất bại',$record, 500);
        }
    }

    /**
     * [update  user]
     * @param  Request $request 
     * @return [json]           
     */
    public function update(Request $request)
    {
        // validator input
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'first_name'=> 'max:150',
            'last_name'=> 'max:150',
            'phone'=> 'max:150',
            'gender'=> 'max:50',
            'birthday'=> 'date|date_format:Y-m-d',
            'address'=> 'max:200',
            'job'=> 'max:150',
            'level'=> 'max:150',
        ]);

        // return validator input have error
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=User::where('id','=',$request->id)->first();
        if($record==null){
            return $this->sendError('Thành viên không tồn tại',$request->all(), 500);
        }
        $record->first_name=$request->first_name;
        $record->last_name=$request->last_name;
        $record->phone=$request->phone;
        $record->gender=$request->gender;
        if($request->birthday!=null)
        {
            $record->birthday=Carbon::parse($request->birthday)->format('Y-m-d');
        }
        
        $record->address=$request->address;
        $record->job=$request->job;
        $record->level=$request->level;
        if($record->update())
        {
            return $this->sendResponse($record,'Cập nhật thành viên thành công.');
        }
        else{
            return $this->sendError('Thêm thành viên mới thất bại',$record, 500);
        }
    }

    /**
     * [change_password Change password]
     * @param  Request $request 
     * @return [json]          
     */
    public function change_password(Request $request){
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6|max:100',
            'old_password' => 'required|min:6|max:100',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),'', 422);
        }
        if (Hash::check($request->old_password, JWTAuth::user()->password)) {
            $update = User::findOrFail(JWTAuth::user()->id);
            $update->password=bcrypt($request->password);
            $update->update();
            return $this->sendResponse($update,'Đổi mật khẩu thành công');
        }
        else{
            return $this->sendError('Mật khẩu cũ không chính xác',[], 500);
        }
    }
    /**
     * [change_avatar description]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function avatar (Request $request){
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
        $record = User::findOrFail(JWTAuth::user()->id);
        $record->avatar=URL::to('/').'/data/'.date('Ymd',time())."/".$allowed_filename;
        if($record->update())
        {
            return $this->sendResponse($record,'Upload ảnh thành công');
        }
        else{
            return $this->sendError('Có lỗi xảy ra trong quá trình upload ảnh',[], 500);
        }
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
    public function sendMail(Request $request)
    {
         // validator input
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:150'
        ]);

        // return validator input have error
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $record=User::where('email','=',$request->email)->first();
        if($record==null){
            return $this->sendError('Thành viên không tồn tại ',$request->all(), 500);
        }
        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $record->email,
        ], [
            'token' => Str::random(60),
        ]);
        if ($passwordReset) {
            $record->notify(new ResetPasswordRequest($passwordReset->token));
        }
  
        return $this->sendResponse('','Vui lòng kiểm tra email để có thể khôi phục mật khẩu');
    }

    public function add_point(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'club_id'=> 'required|exists:clubs,id',
            'point'=>'required|numeric'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $user=User::where('id','=',$request->id)->first();

        $check_leader=LeaderClub::where('user_id','=',Auth::user()->id)->where('club_id','=',$request->club_id)->first();

        if($check_leader==NULL)
        {
            return $this->sendError('Bạn không phải nhóm trưởng CLB này',[], 500);
        }
        if($user!=NULL){
            $point=isset($request->point)?(int)$request->point:0;
            $user->point=$user->point+$point;
            if($user->update())
            {

                $log=new PointLog;
                $log->user_id=$user->id;
                $log->point=$point;
                $log->log=json_encode('['.Auth::user()->id.'] - '.Auth::user()->first_name. ' '.Auth::user()->last_name. ' - ' .Auth::user()->email);
                $log->save();
                return $this->sendResponse($user,'Cộng điểm thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình cộng điểm',[], 500);
            }
        }
        else{
            return $this->sendError('Thành viên không tồn tại ',$request->all(), 500);
        }
    }
    public function subtract_point(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'club_id'=> 'required|exists:clubs,id',
            'point'=>'required|numeric'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $user=User::where('id','=',$request->id)->first();

        $check_leader=LeaderClub::where('user_id','=',Auth::user()->id)->where('club_id','=',$request->club_id)->first();

        if($check_leader==NULL)
        {
            return $this->sendError('Bạn không phải nhóm trưởng CLB này',[], 500);
        }
        if($user!=NULL){
            $point=isset($request->point)?(int)$request->point:0;
            $last_point=$user->point-$point;
            if($last_point<0) $last_point=0;
            $user->point=$last_point;
            if($user->update())
            {
                $log=new PointLog;
                $log->user_id=$user->id;
                $log->point=0-$point;
                $log->log=json_encode('['.Auth::user()->id.'] - '.Auth::user()->first_name. ' '.Auth::user()->last_name. ' - ' .Auth::user()->email);
                $log->save();
                return $this->sendResponse($user,'Trừ điểm thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình cộng điểm',[], 500);
            }
        }
        else{
            return $this->sendError('Thành viên không tồn tại ',$request->all(), 500);
        }
    }
}
