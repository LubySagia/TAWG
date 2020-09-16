<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\Club;
use App\Models\MemberClub;
use App\Models\LeaderClub;
use App\Models\SuggestionClub;
use App\Models\User;
use JWTAuth;
use Auth;
class ClubController extends APIController
{
    /**
     * [data Club]
     * @param  Request $request [id]
     * @return [type]           [Json]
     */
    public function data(Request $request)
    {
        $title=isset($request->title)?trim($request->title):null;
        $record=Club::orderBy('id','desc');
        if($title!=null)
        {
            $record=$record->where('name','like','%'.$title.'%');  
        }
        $record=$record->paginate(20)->makeHidden('content');
        return $this->sendResponse($record,'');
    }

    /**
     * [list Club]
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
        $record=Club::where('id','=',$request->id)->where('status','=',true)->with('members.user')->first();
        if($record==null)
        {
            return $this->sendError('Không tìm thấy clb này',[], 500);
        }
        $members=$record->members;
        foreach ($members as $key => $value) {
           $is_leader=LeaderClub::where('club_id','=',$value->club_id)->where('user_id','=',$value->user_id)->first();
           if($is_leader!=null)
           {
             $value->is_leader=true;
           }else{
             $value->is_leader=false;
           }
        }
        $record->members=$members;
        return $this->sendResponse($record,'');
    }

    public function add_member(Request $request){
        $validator = Validator::make($request->all(), [
            'club_id' => 'required',
            'user_id'=>'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $club=Club::where('id','=',$request->club_id)->first();
        if($club==NULL)
        {
            return $this->sendError('Không tìm thấy clb này',[], 500);
        }
        $user=User::where('id','=',$request->user_id)->first();
        if($user==NULL)
        {
            return $this->sendError('Không tìm thấy thành viên này ',[], 500);
        }

        $member=MemberClub::where('club_id','=',$club->id)->where('user_id','=',$user->id)->first();
        if($member!=null){
            return $this->sendError('Thành viên này đã đăng ký gia nhập clb',[], 500);
        }
        $record=new MemberClub();
        $record->club_id=$club->id;
        $record->user_id=$user->id;
        $record->status=true;
        if($record->save())
        {
            return $this->sendResponse('','Đăng ký gia nhập thành công');
        }
        else{
            return $this->sendError('Đã có lỗi xảy ra trong quá trình thêm nhóm trưởng',[], 500);
        }
    }

    public function allow_member(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'club_id' => 'required',
            'user_id'=>'required',
            'status'=>'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $club=Club::where('id','=',$request->club_id)->first();
        if($club==NULL)
        {
            return $this->sendError('Không tìm thấy clb này',[], 500);
        }
        $user=User::where('id','=',$request->user_id)->first();
        if($user==NULL)
        {
            return $this->sendError('Không tìm thấy thành viên này ',[], 500);
        }

        $check_leader=LeaderClub::where('user_id','=',Auth::user()->id)->where('club_id','=',$club->id)->first();

        if($check_leader==NULL)
        {
            return $this->sendError('Bạn không phải nhóm trưởng CLB này',[], 500);
        }
        $member=MemberClub::where('club_id','=',$club->id)->where('user_id','=',$user->id)->first();
        if($member==null){
            return $this->sendError('Thành viên này chưa đăng ký đăng ký gia nhập clb',[], 500);
        }
        else{
            $member->status=isset($request->status)?(bool)$request->status:false;
            if($member->update()){
                return $this->sendResponse('','Thành Công');
            }
            else{
                return $this->sendError('Đã có lỗi xảy ra trong quá trình duyệt thành viên',[], 500);
            }
        }
    }
    public function send_suggestion(Request $request){
        $validator = Validator::make($request->all(), [
            'club_id' => 'required',
            'content'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $club=Club::where('id','=',$request->club_id)->first();
        if($club==NULL)
        {
            return $this->sendError('Không tìm thấy clb này',[], 500);
        }
        $check_user=MemberClub::where('user_id','=',Auth::user()->id)->where('club_id','=',$club->id)->first();
        
        if($check_user==NULL)
        {
            return $this->sendError('Bạn không phải thành viên CLB này',[], 500);
        }
        $record=new SuggestionClub();
        $record->club_id=$request->club_id;
        $record->user_id=Auth::user()->id;
        $record->content=$request->content;
        if($record->save())
        {

            return $this->sendResponse('','Thành Công');
        }
        else{
            return $this->sendError('Đã có lỗi xảy ra trong quá gửi góp ý',$record, 500);
        }
    }
    public function suggestion_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'club_id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $club=Club::where('id','=',$request->club_id)->first();
        if($club==NULL)
        {
            return $this->sendError('Không tìm thấy clb này',[], 500);
        }

        $record=SuggestionClub::where('club_id','=',$request->club_id)->with('user');
        $record=$record->paginate(20);
        return $this->sendResponse($record,'');
    }
}
