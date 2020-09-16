<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\Club;
use App\Models\User;
use App\Models\MemberClub;
use App\Models\LeaderClub;
use App\Models\SuggestionClub;
class ClubController extends APIController
{
	/**
     * Show the application club list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.clubs');
    }

    /**
     * Show the application club list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function club_member (Request $request,$club_id)
    {
        return view('admin.member_club')->with('club_id',$club_id);
    }
    /**
     * Show the application club list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function club_suggestion (Request $request,$club_id)
    {
        return view('admin.club_suggestion')->with('club_id',$club_id);
    }
    /**
     * [Get Data club for datatable]
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
    	$records=Club::orderBy($field,$sort_type);
    	if($search!=null)
    	{
    		$records=$records->where(function($query) use($search){
                 $query->orWhere('name','like','%'.$search.'%');
            });    
    	}
    	$count=$records->count();
    	$records=$records->offset(($pagination['page'] - 1) * $pagination['perpage'])->limit($pagination['perpage']);
        $records=$records->with('leaders.user')->with('members.user')->with('suggestions.user');
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

    /**
     * [Get Club by ID]
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

        $record=Club::where('id','=',$request->id)->with('leaders.user')->with('members.user')->first();
        if($record!=NULL)
        {
        	return $this->sendResponse($record,'Lấy thông tin clb thành công');
        }
        else{
        	return $this->sendError('Không tìm thấy clb này',[], 500);
        }
    }

    /**
     * [Delete Club by ID]
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

        $record=Club::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
        	if($record->delete())
        	{
        	
        		return $this->sendResponse($record,'Xóa clb thành công');
        	}
        	else{
        		return $this->sendError('Có lỗi xảy ra trong quá trình xóa clb',[], 500);
        	}
        }
        else{
        	return $this->sendError('Không tìm thấy clb này',[], 500);
        }
    }
    /**
     * [Get all category]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function all(Request $request){
        $record=Club::orderBy('id','desc')->get();
        if($record!=NULL)
        {
            return $this->sendResponse($record,'Lấy thông tin clb thành công');
        }
        else{
            return $this->sendError('Không tìm thấy clb  này',[], 500);
        }
    }
   /**
     * [create Club]
     * @param  Request $request [name,description,image,order]
     * @return [json]          
     */
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:500'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=new Club;
        $record->name=$request->name;
        $record->description=$request->description;
        $record->image=$request->image;
        $record->status=true;
        $record->content=$request->content;
        if($record->save())
        {
            return $this->sendResponse($record,'Thêm clb thành công');
        }
        else{
            return $this->sendError('Có lỗi xảy ra trong quá trình thêm clb',[], 500);
        }
        
    }
    /**
     * [update Club]
     * @param  Request $request [id,name,description,image,order]
     * @return [json]          
     */
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name' => 'required|max:500'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=Club::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            $record->name=$request->name;
            $record->description=$request->description;
            $record->image=$request->image;
            $record->status=true;
            $record->content=$request->content;
            if($record->update())
            {
                return $this->sendResponse($record,'Cập nhật clb thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình cập nhật clb',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy clb này',[], 500);
        }
        
    }
    /**
     * [Block Club by ID]
     * @param  Request $request [id]
     * @return [json]          
     */
    public function block(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=Club::where('id','=',$request->id)->first();
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
                return $this->sendResponse($record,'Khóa clb thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình khóa clb',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy clb này',[], 500);
        }
    }

    public function add_leader(Request $request){
        $validator = Validator::make($request->all(), [
            'club_id' => 'required',
            'email'=>'required|email'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $club=Club::where('id','=',$request->club_id)->first();
        if($club==NULL)
        {
            return $this->sendError('Không tìm thấy clb này',[], 500);
        }
        $user=User::where('email','=',$request->email)->first();
        if($user==NULL)
        {
            return $this->sendError('Không tìm thấy thành viên này ',[], 500);
        }

        $leader=LeaderClub::where('club_id','=',$club->id)->where('user_id','=',$user->id)->first();
        if($leader!=null){
            return $this->sendError('Thành viên này đã là nhóm trưởng của clb',[], 500);
        }

        $member=MemberClub::where('club_id','=',$club->id)->where('user_id','=',$user->id)->first();
        if($member!=null){
            return $this->sendError('Thành viên này đã đăng ký gia nhập clb',[], 500);
        }

        $record=new LeaderClub();
        $record->club_id=$club->id;
        $record->user_id=$user->id;
        if($record->save())
        {
            $add_member=new MemberClub();
            $add_member->club_id=$club->id;
            $add_member->user_id=$user->id;
            $add_member->status=true;
            $add_member->save();

            $current_club=Club::where('id','=',$request->club_id)->with('leaders.user')->with('members.user')->first();
            return $this->sendResponse($current_club,'Thêm thành viên thành công');
        }
        else{
            return $this->sendError('Đã có lỗi xảy ra trong quá trình thêm nhóm trưởng',[], 500);
        }
    }
    public function delete_leader(Request $request){
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

        $leader=LeaderClub::where('club_id','=',$club->id)->where('user_id','=',$user->id)->first();
        if($leader==null){
             return $this->sendError('Không tìm thấy nhóm trưởng trong clb này',[], 500);
        }

        $member=MemberClub::where('club_id','=',$club->id)->where('user_id','=',$user->id)->first();
        if($member==null){
            return $this->sendError('Không tìm thấy thành vien trong clb này',[], 500);
        }

        if($leader->delete())
        {
            $current_club=Club::where('id','=',$request->club_id)->with('leaders.user')->with('members.user')->first();
            return $this->sendResponse($current_club,'Xóa thành viên thành công');
        }
        else{
            return $this->sendError('Đã có lỗi xảy ra trong quá trình thêm nhóm trưởng',[], 500);
        }
    }

    public function member_list(Request $request,$club_id)
    {
        if($request->club_id==NULL)
        {
             return $this->sendError('Không tìm thấy clb này',[], 500);
        }
        $club=Club::where('id','=',$club_id)->first();
        if($club==NULL)
        {
            return $this->sendError('Không tìm thấy clb này',[], 500);
        }
        $members=MemberClub::where('club_id','=',$request->club_id)->get();
        $id_array=[];
        $array_status=[];
        foreach ($members as $key => $value) {
           $id_array[]=$value->user_id;
           $array_status[$value->user_id]=$value->status;
        }
        $pagination=isset($request->pagination)?(array)$request->pagination:[];
        $data=$request->all();
        $query=isset($data['query'])?(array)$data['query']:[];
        $sort=isset($request->sort)?(array)$request->sort:[];
        $field=isset($sort['field'])?trim($sort['field']):'id';
        $sort_type=isset($sort['sort'])?trim($sort['sort']):'desc';
        $search=isset($query['generalSearch'])?trim($query['generalSearch']):null;
        $records=User::where('is_admin','=',false)->whereIn('id',$id_array)->orderBy($field,$sort_type);
        if($search!=null)
        {
            $records=$records->where(function($query) use($search){
                 $query->orWhere('first_name','like','%'.$search.'%')->orWhere('last_name','like','%'.$search.'%')->orWhere('email','like','%'.$search.'%')->orWhere('phone','like','%'.$search.'%')->orWhere('address','like','%'.$search.'%');
            });    
        }
        $count=$records->count();
        $records=$records->offset(($pagination['page'] - 1) * $pagination['perpage'])->limit($pagination['perpage']);
        $meta=[
            "page"=>$pagination['page'],
            "pages"=>round($count/$pagination['page']),
            "perpage"=>$pagination['perpage'],
            "total"=>$count,
            "sort"=>$sort_type,
            "field"=>$field
        ];
        $data['meta']=$meta;
        $records=$records->get();
        foreach ($records as $key => $value) {
            $value->status_member=$array_status[$value->id];
            $leader=LeaderClub::where('club_id','=',$request->club_id)->where('user_id','=',$value->id)->first();
            if($leader!=null)
            {
                $value->status_leader=true;
            }
            else{
                $value->status_leader=false;
            }
        }
        $data['data']=$records;
        return response()->json($data, 200);
    }

    public function delete_member(Request $request){
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
        if($member==null){
            return $this->sendError('Không tìm thấy thành viên trong clb này',[], 500);
        }

        if($member->delete())
        {
            $leader=LeaderClub::where('club_id','=',$club->id)->where('user_id','=',$user->id)->first();
            if($leader!=null)
            {
                $leader->delete();
            }
            return $this->sendResponse('','Xóa thành viên thành công');
        }
        else{
            return $this->sendError('Đã có lỗi xảy ra trong quá trình thêm nhóm trưởng',[], 500);
        }
    }
    public function delete_member_array(Request $request){
        $validator = Validator::make($request->all(), [
            'club_id' => 'required',
            'member_id_array'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $club=Club::where('id','=',$request->club_id)->first();

        if($club==NULL)
        {
            return $this->sendError('Không tìm thấy clb này',[], 500);
        }
        foreach ($request->member_id_array as $key => $value) {
            $member=MemberClub::where('id','=',$value)->first();
            if($member!=null)
            {
                if($member->delete())
                {
                    $leader=LeaderClub::where('club_id','=',$club->id)->where('user_id','=',$member->user_id)->first();
                    if($leader!=null)
                    {
                        $leader->delete();
                    }
                   
                }
            }
        }
        return $this->sendResponse('','Thành công');
    }
    public function allow_member_array(Request $request){
        $validator = Validator::make($request->all(), [
            'club_id' => 'required',
            'member_id_array'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $club=Club::where('id','=',$request->club_id)->first();

        if($club==NULL)
        {
            return $this->sendError('Không tìm thấy clb này',[], 500);
        }
        foreach ($request->member_id_array as $key => $value) {
            $member=MemberClub::where('club_id','=',$club->id)->where('user_id','=',$value)->first();
            if($member!=null)
            {
                if($member->status){
                    $member->status=false;   
                }else{
                    $member->status=true;
                }
                
                $member->update();
            }
        }
        return $this->sendResponse('','Thành công');
    }
    public function allow_member(Request $request){
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
        if($member==null){
            return $this->sendError('Không tìm thấy thành viên trong clb này',[], 500);
        }
        $member->status=true;
        if($member->update())
        {
            return $this->sendResponse('','Xóa thành viên thành công');
        }
        else{
            return $this->sendError('Đã có lỗi xảy ra trong quá trình thêm nhóm trưởng',[], 500);
        }
    }
    public function suggestion_list(Request $request,$club_id)
    {
        if($request->club_id==NULL)
        {
             return $this->sendError('Không tìm thấy clb này',[], 500);
        }
        $club=Club::where('id','=',$club_id)->first();
        if($club==NULL)
        {
            return $this->sendError('Không tìm thấy clb này',[], 500);
        }

        $pagination=isset($request->pagination)?(array)$request->pagination:[];
        $data=$request->all();
        $query=isset($data['query'])?(array)$data['query']:[];
        $sort=isset($request->sort)?(array)$request->sort:[];
        $field=isset($sort['field'])?trim($sort['field']):'id';
        $sort_type=isset($sort['sort'])?trim($sort['sort']):'desc';
        $search=isset($query['generalSearch'])?trim($query['generalSearch']):null;
        $records=SuggestionClub::where('club_id','=',$request->club_id)->with('user')->orderBy($field,$sort_type);
        if($search!=null)
        {
            $records=$records->where(function($query) use($search){
                 $query->orWhere('content','like','%'.$search.'%');
            });    
        }
        $count=$records->count();
        $records=$records->offset(($pagination['page'] - 1) * $pagination['perpage'])->limit($pagination['perpage']);
        $meta=[
            "page"=>$pagination['page'],
            "pages"=>round($count/$pagination['page']),
            "perpage"=>$pagination['perpage'],
            "total"=>$count,
            "sort"=>$sort_type,
            "field"=>$field
        ];
        $data['meta']=$meta;
        $records=$records->get();
        $data['data']=$records;
        return response()->json($data, 200);
    }
    public function delete_suggestion(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $record=SuggestionClub::where('id','=',$request->id)->first();
        if($record==null){
            return $this->sendError('Không tìm thấy góp ý này',[], 500);
        }
        if($record->delete())
        {

            return $this->sendResponse('','Xóa góp ýthành công');
        }
        else{
            return $this->sendError('Đã có lỗi xảy ra trong quá xóa góp ý',[], 500);
        }
    }
    public function view_suggestion(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $record=SuggestionClub::where('id','=',$request->id)->first();
        if($record==null){
            return $this->sendError('Không tìm thấy góp ý này',[], 500);
        }

        return $this->sendResponse($record,'Xóa góp ýthành công');
    }
}
