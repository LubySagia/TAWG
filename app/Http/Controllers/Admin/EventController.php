<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\Event;
use App\Models\Club;
use App\Models\User;
use App\Models\EventMember;
use App\Models\EventComment;
use App\Models\EventCommentReport;
use Carbon\Carbon;
class EventController extends APIController
{
	/**
     * Show the application event list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.events');
    }
    /**
     * [Get Data event for datatable]
     * @param  Request $request 
     * @return [json]           
     */
    public function data(Request $request)
    {
    	$pagination=isset($request->pagination)?(array)$request->pagination:[];
    	$data=$request->all();
    	$query=isset($data['query'])?(array)$data['query']:[];
    	$sort=isset($request->sort)?(array)$request->sort:[];
    	$field=isset($sort['field'])?trim($sort['field']):'id';
    	$sort_type=isset($sort['sort'])?trim($sort['sort']):'desc';
    	$search=isset($query['generalSearch'])?trim($query['generalSearch']):null;
        $club_id=isset($query['club_id'])?trim($query['club_id']):null;
        $from=isset($query['from'])?trim($query['from']):null;
        $to=isset($query['to'])?trim($query['to']):null;
    	$records=Event::orderBy($field,$sort_type);
    	if($search!=null)
    	{
    		$records=$records->where(function($query) use($search){
                 $query->orWhere('title','like','%'.$search.'%');
            });    
    	}
        if($club_id!=null)
        {
            $records=$records->where('club_id','=',$club_id);
        }
        if($from!=null)
        {
            $records=$records->where('from','>=',Carbon::parse($from)->format('Y-m-d H:i:s'));
        }
        if($to!=null)
        {
            $records=$records->where('to','<=',Carbon::parse($to)->format('Y-m-d H:i:s'));
        }
    	$count=$records->count();
    	$records=$records->offset(($pagination['page'] - 1) * $pagination['perpage'])->limit($pagination['perpage']);
        $records=$records->with('club')->with('comment');
    	$meta=[
    		"page"=>$pagination['page'],
    		"pages"=>round($count/$pagination['page']),
    		"perpage"=>$pagination['perpage'],
    		"total"=>$count,
    		"sort"=>$sort_type,
    		"field"=>$field
    	];
    	$data['meta']=$meta;
        $last_data=$records->get();
        foreach ($last_data as $key => $value) {
            $comment=$value->comment()->get()->pluck('id');
            $value->comment=$comment;
            $report=EventCommentReport::whereIn('comment_id',$comment)->get()->count();
            $value->report=$report;
        }
    	$data['data']=$last_data;
    	return response()->json($data, 200);
    }

    /**
     * [Get Event by ID]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function view(Request $request){
    	$validator = Validator::make($request->all(), [
	        'id' => 'required'
	    ]);
    	if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=Event::where('id','=',$request->id)->with('club')->first();
        if($record!=NULL)
        {
        	return $this->sendResponse($record,'Lấy thông tin sự kiện thành công');
        }
        else{
        	return $this->sendError('Không tìm thấy sự kiện này',[], 500);
        }
    }

    /**
     * [Delete Event by ID]
     * @param  Request $request [id]
     * @return [json]          
     */
    public function delete(Request $request){
    	$validator = Validator::make($request->all(), [
	        'id' => 'required'
	    ]);
    	if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
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

   /**
     * [create Event]
     * @param  Request $request [title,description,image,order]
     * @return [json]          
     */
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:500',
            'club_id'=> 'required|exists:clubs,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        if(strtotime($request->from) > strtotime($request->to)){
             return $this->sendError('Thời gian kết thúc phải lớn hơn thời gian bắt đầu',$request->all(), 500);
        }
        $record=new Event;
        $record->name=$request->name;
        $record->description=$request->description;
        $record->club_id=$request->club_id;
        $record->image=$request->image;
        $record->status=$request->status;
        $record->private=$request->private;
        $record->content=$request->content;
        $record->from=Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $record->to=Carbon::parse($request->to)->format('Y-m-d H:i:s');
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
     * [update Event]
     * @param  Request $request [id,title,description,image,order]
     * @return [json]          
     */
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name' => 'required|max:500',
            'club_id'=> 'required|exists:clubs,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        if(strtotime($request->from) > strtotime($request->to)){
             return $this->sendError('Thời gian kết thúc phải lớn hơn thời gian bắt đầu',$request->all(), 500);
        }
        $record=Event::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            $record->name=$request->name;
            $record->description=$request->description;
            $record->club_id=$request->club_id;
            $record->image=$request->image;
            $record->status=$request->status;
            $record->private=$request->private;
            $record->content=$request->content;
            $record->from=Carbon::parse($request->from)->format('Y-m-d H:i:s');
            $record->to=Carbon::parse($request->to)->format('Y-m-d H:i:s');
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

    /**
     * [Block Article by ID]
     * @param  Request $request [id]
     * @return [json]          
     */
    public function status(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=Event::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            if($record->status)
            {
                $record->status=false;
            }
            else{
                $record->status=true;
            }

            if($record->update())
            {
                return $this->sendResponse($record,'Cập nhật trạng thái sự kiện thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình Cập nhật trạng thái sự kiện ',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy sự kiện này',[], 500);
        }
    }
    /**
     * [Block Article by ID]
     * @param  Request $request [id]
     * @return [json]          
     */
    public function private_status(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=Event::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            if($record->private)
            {
                $record->private=false;
            }
            else{
                $record->private=true;
            }

            if($record->update())
            {
                return $this->sendResponse($record,'Cập nhật trạng thái sự kiện thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình Cập nhật trạng thái sự kiện ',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy sự kiện này',[], 500);
        }
    }
    
    /**
     * Show the application club list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function view_member (Request $request,$event_id)
    {
        return view('admin.view_member_event')->with('event_id',$event_id);
    }

    public function member_list(Request $request,$event_id)
    {
        $pagination=isset($request->pagination)?(array)$request->pagination:[];
        $data=$request->all();
        $query=isset($data['query'])?(array)$data['query']:[];
        $sort=isset($request->sort)?(array)$request->sort:[];
        $field=isset($sort['field'])?trim($sort['field']):'id';
        $sort_type=isset($sort['sort'])?trim($sort['sort']):'desc';
        $search=isset($query['generalSearch'])?trim($query['generalSearch']):null;
        $check_in=isset($query['check_in'])?(bool)($query['check_in']):null;
        $from=isset($query['from'])?trim($query['from']):null;
        $to=isset($query['to'])?trim($query['to']):null;
        $records=EventMember::where('event_id','=',$event_id)->orderBy($field,$sort_type);
        if($search!=null)
        {
            $users=User::orWhere('first_name','like','%'.$search.'%')->orWhere('last_name','like','%'.$search.'%')->orWhere('email','like','%'.$search.'%')->orWhere('phone','like','%'.$search.'%')->orWhere('address','like','%'.$search.'%')->get()->pluck('id')->toArray();
            $records=$records->whereIn('user_id',$users)  ; 
        }
        if($check_in!=null)
        {
            $records=$records->where('check_in','=',$check_in);
        }

        $count=$records->count();
        $records=$records->offset(($pagination['page'] - 1) * $pagination['perpage'])->limit($pagination['perpage']);
        $records=$records->with('user');
        $meta=[
            "page"=>$pagination['page'],
            "pages"=>round($count/$pagination['page']),
            "perpage"=>$pagination['perpage'],
            "total"=>$count,
            "sort"=>$sort_type,
            "field"=>$field
        ];
        $data['meta']=$meta;
        $data['data']=$records->get();
        return response()->json($data, 200);
    }
    public function delete_member(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $record=EventMember::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            if($record->delete())
            {
                return $this->sendResponse($record,'Xóa  thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình xóa thành viên',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy thành viên sự kiện này',[], 500);
        }
    }

    public function check_in(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $record=EventMember::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            if($record->check_in)
            {
                $record->check_in=false;
                $record->check_in_time=null;
            }
            else{
                $record->check_in=true;
                $record->check_in_time=date('Y-m-d H:i:s',time());
            }
            if($record->update())
            {
                return $this->sendResponse($record,'Thay đổi điểm danh  thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình điểm danh thành viên',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy thành viên sự kiện này',[], 500);
        }
    }
    public function delete_member_array(Request $request){
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'member_id_array'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $event=Event::where('id','=',$request->event_id)->first();

        if($event==NULL)
        {
            return $this->sendError('Không tìm thấy sự kiện này này',[], 500);
        }
        foreach ($request->member_id_array as $key => $value) {
            $member=EventMember::where('id','=',$value)->first();
            if($member!=null)
            {
                $member->delete();
            }
        }
        return $this->sendResponse('','Thành công');
    }
    public function checkin_member_array(Request $request){
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'member_id_array'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $event=Event::where('id','=',$request->event_id)->first();

        if($event==NULL)
        {
            return $this->sendError('Không tìm thấy sự kiện này này',[], 500);
        }
        foreach ($request->member_id_array as $key => $value) {
            $member=EventMember::where('id','=',$value)->first();
            if($member->check_in)
            {
                $member->check_in=false;
                $member->check_in_time=null;
            }
            else{
                if($event->number_member>0 && $event->number_member!=null)
                {
                    $check_event_checkin=EventMember::where('user_id','=',$request->user_id)->where('event_id','=',$event->id)->where('check_in','=',true)->count();
                    if($check_event_checkin<$event->number_member)
                    {
                        $member->check_in=true;
                        $member->check_in_time=date('Y-m-d H:i:s',time());
                    }
                    
                }
                else{
                     $member->check_in=true;
                     $member->check_in_time=date('Y-m-d H:i:s',time());
                }
               
            }
            $member->update();
        }
        return $this->sendResponse('','Thành công');
    }
    public function delete_comment(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $record=EventComment::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            if($record->delete())
            {
                return $this->sendResponse($record,'Xóa  thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình xóa bình luận',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy bình luận sự kiện này',[], 500);
        }
    }
    public function delete_comment_array(Request $request){
        $validator = Validator::make($request->all(), [
            'event_id' => 'required',
            'comment_id_array'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $event=Event::where('id','=',$request->event_id)->first();

        if($event==NULL)
        {
            return $this->sendError('Không tìm thấy sự kiện này này',[], 500);
        }
        foreach ($request->comment_id_array as $key => $value) {
            $comment=EventComment::where('id','=',$value)->first();
            if($comment!=null)
            {
                $comment->delete();
            }
        }
        return $this->sendResponse('','Thành công');
    }
    public function comment_list(Request $request,$event_id)
    {
        $pagination=isset($request->pagination)?(array)$request->pagination:[];
        $data=$request->all();
        $query=isset($data['query'])?(array)$data['query']:[];
        $sort=isset($request->sort)?(array)$request->sort:[];
        $field=isset($sort['field'])?trim($sort['field']):'id';
        $sort_type=isset($sort['sort'])?trim($sort['sort']):'desc';
        $search=isset($query['generalSearch'])?trim($query['generalSearch']):null;
        $records=EventComment::where('event_id','=',$event_id)->orderBy($field,$sort_type);
        $report=isset($query['report'])?trim($query['report']):NULL;
        if($search!=null)
        {
            $users=User::orWhere('first_name','like','%'.$search.'%')->orWhere('last_name','like','%'.$search.'%')->orWhere('email','like','%'.$search.'%')->orWhere('phone','like','%'.$search.'%')->orWhere('address','like','%'.$search.'%')->get()->pluck('id')->toArray();
            $records=$records->whereIn('user_id',$users)  ; 
        }

        $count=$records->count();
        $records=$records->offset(($pagination['page'] - 1) * $pagination['perpage'])->limit($pagination['perpage']);
        $records=$records->with('user')->with('report');
        $meta=[
            "page"=>$pagination['page'],
            "pages"=>round($count/$pagination['page']),
            "perpage"=>$pagination['perpage'],
            "total"=>$count,
            "sort"=>$sort_type,
            "field"=>$field
        ];
        $data['meta']=$meta;
        if($report!=NULL)
        {
            $last_data=$records->get();
            if($report==1)
            {
                $temp_data;
                foreach ($last_data as $key => $value) {
                    if(count($value->report)>0)
                    {
                        $temp_data[]=$value;
                    }
                    
                }
                $last_data=$temp_data;
            }
            if($report==0)
            {
                $temp_data;
                foreach ($last_data as $key => $value) {
                    if(count($value->report)==0)
                    {
                        $temp_data[]=$value;
                    }
                    
                }
                $last_data=$temp_data;
            }
            $data['data']=$last_data;
        }
        else{
            $data['data']=$records->get();
        }
        
        return response()->json($data, 200);
    }

    public function view_comment(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $event_comment=EventComment::where('id','=',$request->id)->with('user')->with('report')->first();

        if($event_comment==NULL)
        {
            return $this->sendError('Không tìm thấy bình luận  này',[], 500);
        }
        foreach ($event_comment->report as $key => $value) {
            $value->user=$value->user()->first();
        }
        return $this->sendResponse($event_comment,'Thành Công');
    }

    public function list_comment (Request $request,$event_id)
    {
        return view('admin.view_comment_event')->with('event_id',$event_id);
    }

    public function delete_comment_report(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $report=EventCommentReport::where('id','=',$request->id)->first();
        if($report==NULL)
        {
            return $this->sendError('Không tìm thấy báo cáo này',[], 500);
        }
          $report->delete();
        $event_comment=EventComment::where('id','=',$report->comment_id)->with('user')->with('report')->first();
        if($event_comment==NULL)
        {
            return $this->sendError('Không tìm thấy bình luận  này',[], 500);
        }
        foreach ($event_comment->report as $key => $value) {
            $value->user=$value->user()->first();
        }

        return $this->sendResponse($event_comment,'Thành Công');
    }
}