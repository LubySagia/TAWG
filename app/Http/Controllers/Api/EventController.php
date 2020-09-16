<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use App\Models\Club;
use App\Models\EventMember;
use App\Models\MemberClub;
use App\Models\LeaderClub;
use App\Models\EventComment;
use App\Models\EventCommentReport;
use App\Models\Setting;
use App\Models\PointLog;
use App\Models\User;
use Auth;
use JWTAuth;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Image;
use Storage;
use Illuminate\Support\Facades\URL;
class EventController extends APIController
{
    /**
     * [data Event]
     * @param  Request $request [id]
     * @return [type]           [Json]
     */
    public function data(Request $request)
    {
        
        $club_id=isset($request->club_id)?trim($request->club_id):null;
        $user_id=isset($request->user_id)?trim($request->user_id):null;
        $check_in=isset($request->check_in)?(bool)($request->check_in):true;
        $name=isset($request->name)?trim($request->name):null;
        $member_event=null;
        if($user_id!=NULL)
        {
            $member_event=EventMember::where('user_id','=',$user_id)->get()->pluck('event_id')->toArray();
        }
        $record=Event::orderBy('id','desc');
        if($club_id!=null)
        {
            $check_club=Club::where('id','=',$request->club_id)->first();
            if($check_club==null)
            {
                return $this->sendError('Không tìm thấy clb này',[], 500);
            }
            $record=$record->where('club_id','=',$club_id);
        }
        if($member_event!=null)
        {
            $record=$record->whereIn('id',$member_event); 
        }
        if($name!=null)
        {
            $record=$record->where('name','like','%'.$name.'%');  
        }
        $record=$record->paginate(20)->makeHidden('content');
        return $this->sendResponse($record,'');
    }
    /**
     * [list Event]
     * @param  Request $request [id]
     * @return [type]           [Json]
     */
    public function view(Request $request)
    {
        // validator input
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        // validator input have error
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $record=Event::where('id','=',$request->id)->where('status','=',true)->with('club')->first();
        $record->users=EventMember::where('event_id','=',$record->id)->with('user')->get();
        if($record==null)
        {
            return $this->sendError('Không tìm thấy sự kiện  này',[], 500);
        }
      
        return $this->sendResponse($record,'');
    }

    /**
     * [create Event]
     * @param  Request $request [title,description,image,order]
     * @return [json]          
     */
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:500',
            'club_id'=> 'required|exists:clubs,id',
            'image' => 'nullable|mimes:png,gif,jpeg,jpg,bmp'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        if(strtotime($request->from) > strtotime($request->to)){
             return $this->sendError('Thời gian kết thúc phải lớn hơn thời gian bắt đầu',$request->all(), 500);
        }

        $check_leader=LeaderClub::where('user_id','=',Auth::user()->id)->where('club_id','=',$request->club_id)->first();

        if($check_leader==NULL)
        {
            return $this->sendError('Bạn không phải nhóm trưởng CLB này',[], 500);
        }
        $record=new Event;
        if($request->image!=null)
        {
            $photo = $request->image;
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
            $record->image=URL::to('/').'/data/'.date('Ymd',time())."/".$allowed_filename;
        }
        
        $record->name=$request->name;
        $record->description=$request->description;
        $record->club_id=$request->club_id;
       
        $record->status=isset($request->status)?(bool)$request->status:true;
        $record->private=isset($request->private)?(bool)$request->private:false;
        $record->content=$request->content;
        $record->from=$request->from;
        $record->to=$request->to;
        $record->number_member=$request->number_member;
        if($record->save())
        {
            return $this->sendResponse($record,'Thêm sự kiện thành công');
        }
        else{
            return $this->sendError('Có lỗi xảy ra trong quá trình thêm sự kiện',[], 500);
        }
        
    }
    /**
     * [create Event]
     * @param  Request $request [title,description,image,order]
     * @return [json]          
     */
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name' => 'required|max:500',
            'club_id'=> 'required|exists:clubs,id',
            'image' => 'nullable|mimes:png,gif,jpeg,jpg,bmp'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        if(strtotime($request->from) > strtotime($request->to)){
             return $this->sendError('Thời gian kết thúc phải lớn hơn thời gian bắt đầu',$request->all(), 500);
        }

        
        $check_leader=LeaderClub::where('user_id','=',Auth::user()->id)->where('club_id','=',$request->club_id)->first();

        if($check_leader==NULL)
        {
            return $this->sendError('Bạn không phải nhóm trưởng CLB này',[], 500);
        }
        $record=Event::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            if($request->image!=null&&$record->image!=$request->image)
            {
                $photo = $request->image;
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
                $record->image=URL::to('/').'/data/'.date('Ymd',time())."/".$allowed_filename;
            }
        
            $record->name=$request->name;
            $record->description=$request->description;
            $record->club_id=$request->club_id;
           
            $record->status=isset($request->status)?(bool)$request->status:true;
            $record->private=isset($request->private)?(bool)$request->private:false;
            $record->content=$request->content;
            $record->from=$request->from;
            $record->to=$request->to;
            $record->number_member=$request->number_member;
            if($record->update())
            {
                return $this->sendResponse($record,'Cập nhật sự kiện thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình cập nhật sự kiện',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy sự kiện này',[], 500);
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

     /**
     * [Delete Event by ID]
     * @param  Request $request [id]
     * @return [json]          
     */
    public function delete(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'club_id'=> 'required|exists:clubs,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $check_leader=LeaderClub::where('user_id','=',Auth::user()->id)->where('club_id','=',$request->club_id)->first();

        if($check_leader==NULL)
        {
            return $this->sendError('Bạn không phải nhóm trưởng CLB này',[], 500);
        }
        $record=Event::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            if($record->delete())
            {
                return $this->sendResponse($record,'Xóa sự kiện thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình xóa sự kiện',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy sự kiện này',[], 500);
        }
    }

    public function join(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id'=> 'required|exists:events,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $event=Event::where('id','=',$request->event_id)->first();
        if($event->private)
        {
            $member_event=MemberClub::where('user_id','=',Auth::user()->id)->where('club_id','=',$event->club_id)->first();
            if($member_event==null){
                return $this->sendError('Sự kiện chỉ dành cho thành viên trong câu lạc bộ. Bạn không thể gtham dự sự kiện này',[], 500);
            }
        }
        if(strtotime($event->to) < time()){
             return $this->sendError('Đã quá thời gian đăng ký tham gia sự kiện',[], 500);
        }
        $check_join=EventMember::where('user_id','=',Auth::user()->id)->where('event_id','=',$event->id)->first();
        if($check_join!=null)
        {
            return $this->sendError('Bạn đã đăng ký tham dự sự kiện này',[], 500);
        }
        $record=new EventMember();
        $record->user_id=Auth::user()->id;
        $record->event_id=$event->id;
        $record->check_in=false;
        if($record->save())
        {
            return $this->sendResponse($record,'Đăng ký tham dự thành công');
        }
        else{
            return $this->sendError('Có lỗi xảy ra trong quá trình đăng ký tham gia sự kiện',[], 500);
        }
    }

    public function unjoin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id'=> 'required|exists:events,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $event=Event::where('id','=',$request->event_id)->first();

        $check_join=EventMember::where('user_id','=',Auth::user()->id)->where('event_id','=',$event->id)->first();
        if($check_join==null)
        {
            return $this->sendError('Bạn đã chưa đăng ký tham gia sự kiện này',[], 500);
        }
        if($check_join->check_in)
        {
            return $this->sendError('Bạn đã được điểm danh sự kiện này, không thể hủy tham gia',[], 500);
        }

        if($check_join->delete())
        {
            return $this->sendResponse($check_join,'Hủy đăng ký tham dự thành công');
        }
        else{
            return $this->sendError('Có lỗi xảy ra trong quá trình hủy đăng ký tham gia sự kiện',[], 500);
        }
    }

    public function checkin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id'=> 'required|exists:events,id',
            'user_id'=> 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $event=Event::where('id','=',$request->event_id)->first();
        $check_leader=LeaderClub::where('user_id','=',Auth::user()->id)->where('club_id','=',$event->club_id)->first();
        if($check_leader==NULL)
        {
            return $this->sendError('Bạn không phải nhóm trưởng CLB này',[], 500);
        }

        if($event->number_member>0 && $event->number_member!=null)
        {
            $check_event_checkin=EventMember::where('event_id','=',$event->id)->where('check_in','=',true)->count();
            if($check_event_checkin>=$event->number_member)
            {
                return $this->sendError('Đã quả số lượng người điểm danh',[], 500);
            }
            
        }

        $check_join=EventMember::whereIn('user_id',$request->user_id)->where('event_id','=',$event->id)->get();

        foreach ($check_join as $key => $value) {
            if(!$value->check_in)
            {
                $value->check_in=true;
                $value->check_in_time=date('Y-m-d H:i:s',time());
                $value->update();
                if($value->user_id)
                {
                    $setting=Setting::first();
                    $user=User::where('id','=',$value->user_id)->first();
                    $user->point=$user->point+$setting->check_in_point;
                    $user->update();
                    $log=new PointLog;
                    $log->user_id=$user->id;
                    $log->point=$setting->check_in_point;
                    $log->log=json_encode('[System Auto Check In Add Point]');
                    $log->save();
                }
            }
                
        }
       
        return $this->sendResponse($check_join,'Điểm danh thành công');
    }

    public function delete_checkin(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id'=> 'required|exists:events,id',
            'user_id'=> 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $event=Event::where('id','=',$request->event_id)->first();
        $check_leader=LeaderClub::where('user_id','=',Auth::user()->id)->where('club_id','=',$event->club_id)->first();
        if($check_leader==NULL)
        {
            return $this->sendError('Bạn không phải nhóm trưởng CLB này',[], 500);
        }

        $check_join=EventMember::whereIn('user_id',$request->user_id)->where('event_id','=',$event->id)->get();

        foreach ($check_join as $key => $value) {
            if($value->check_in)
            {
                $value->check_in=false;
                $value->check_in_time=date('Y-m-d H:i:s',time());
                $value->update();
                if($value->user_id)
                {
                    $setting=Setting::first();
                    $user=User::where('id','=',$value->user_id)->first();
                    $user->point=$user->point-$setting->check_in_point;
                    $user->update();
                    $log=new PointLog;
                    $log->user_id=$user->id;
                    $log->point=0-$setting->check_in_point;
                    $log->log=json_encode('[System Auto Check In Subtract Point]');
                    $log->save();
                }
            }
                
        }
       
        return $this->sendResponse($check_join,'Điểm danh thành công');
        
    }
    public function comment(Request $request){
        $validator = Validator::make($request->all(), [
            'event_id'=> 'required|exists:events,id',
            'content'=> 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $event=Event::where('id','=',$request->event_id)->first();
        $comment=new EventComment;
        $comment->event_id=$request->event_id;
        $comment->user_id=Auth::user()->id;
        $comment->content=$request->content;
        if($comment->save())
        {
            $res_comment=EventComment::where('id','=',$comment->id)->with('user')->first();
            return $this->sendResponse($res_comment,'Bình luận thành công');
        }
        else{
            return $this->sendError('Có lỗi xảy ra trong quá trình bình luận sự kiện',[], 500);
        }
    }
    public function comment_update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=> 'required|exists:event_comments,id',
            'content'=> 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $comment=EventComment::where('id','=',$request->id)->with('user')->first();
        if($comment->user_id!=Auth::user()->id)
        {
            return $this->sendError('Bạn không phải là người bình luận nội dung này
                ',[], 500);
        }
        $comment->content=$request->content;
        if($comment->update())
        {
            return $this->sendResponse($comment,'Cập nhật bình luận thành công');
        }
        else{
            return $this->sendError('Có lỗi xảy ra trong quá trình cập nhật bình luận sự kiện',[], 500);
        }
    }

    public function comment_delete(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=> 'required|exists:event_comments,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $comment=EventComment::where('id','=',$request->id)->first();
        if($comment->user_id!=Auth::user()->id)
        {
            $check_leader=LeaderClub::where('user_id','=',Auth::user()->id)->where('club_id','=',$event->club_id)->first();
            if($check_leader!=null)
            {
                return $this->sendError('Bạn không phải nhóm trưởng hoặc người tạo ra bình luận này',[], 500);
            }
        }
        if($comment->delete())
        {
            return $this->sendResponse($comment,'Xóa bình luận thành công');
        }
        else{
            return $this->sendError('Có lỗi xảy ra trong quá trình xóa bình luận sự kiện',[], 500);
        }
    }

    public function comment_data(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'event_id'=> 'required|exists:events,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $record=EventComment::where('event_id','=',$request->event_id)->orderBy('id','desc');
        $record=$record->with('user')->with('report')->paginate(20);
        return $this->sendResponse($record,'');
    }
    
    public function comment_report(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'=> 'required|exists:users,id',
            'comment_id'=> 'required|exists:event_comments,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $record=new EventCommentReport;
        $record->user_id=$request->user_id;
        $record->comment_id=$request->comment_id;
        $record->content=$request->content;
        if($record->save())
        {
            return $this->sendResponse($record,'');
        }
        else{
            return $this->sendError('Có lỗi xảy ra trong quá trình báo cáo bình luận sự kiện',[], 500);
        }
        
    }
}
