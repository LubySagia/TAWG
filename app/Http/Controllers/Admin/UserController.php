<?php

namespace App\Http\Controllers\Admin;
use Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\PointLog;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;
use JWTAuth;
use App\Models\PasswordReset;
use App\Notifications\ResetPasswordRequest;
use Carbon\Carbon;
class UserController extends APIController
{
	/**
     * Show the application user list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.users');
    }
    /**
     * [Get Data user for datatable]
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
    	$records=User::where('is_admin','=',false)->orderBy($field,$sort_type);
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
    	$data['data']=$records->get();
    	return response()->json($data, 200);
    }

    /**
     * [Get User by ID]
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

        $record=User::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
        	return $this->sendResponse($record,'Lấy thông tin thành viên thành công');
        }
        else{
        	return $this->sendError('Không tìm thấy thành viên này',[], 500);
        }
    }

    /**
     * [Delete User by ID]
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

        $record=User::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
        	if($record->delete())
        	{
        		if($record->auth_token!=null)
        		{
        			JWTAuth::setToken($record->auth_token)->invalidate();
        		}
        		
        		return $this->sendResponse($record,'Xóa thành viên thành công');
        	}
        	else{
        		return $this->sendError('Có lỗi xảy ra trong quá trình xóa thành viên',[], 500);
        	}
        }
        else{
        	return $this->sendError('Không tìm thấy thành viên này',[], 500);
        }
    }
    /**
     * [Block User by ID]
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

        $record=User::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
        	if($record->is_block)
        	{
        		$record->is_block=false;
        	}
        	else{
        		$record->is_block=true;
        	}

        	if($record->update())
        	{
        		if($record->is_block)
        		{
        			if($record->auth_token!=null)
	        		{
	        			JWTAuth::setToken($record->auth_token)->invalidate();
	        		}
        		}
        		return $this->sendResponse($record,'Khóa thành viên thành công');
        	}
        	else{
        		return $this->sendError('Có lỗi xảy ra trong quá trình khóa thành viên',[], 500);
        	}
        }
        else{
        	return $this->sendError('Không tìm thấy thành viên này',[], 500);
        }
    }
    /**
     * Show the application change pasword.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function change_password_index()
    {
        return view('admin.change_password');
    }
    /**
     * [change_password Change password]
     * @param  Request $request 
     * @return [json]          
     */
    public function change_password(Request $request){
    	$validator = Validator::make($request->all(), [
	        'password' => 'required|confirmed|min:6|max:100',
	        'oldpassword' => 'required|min:6|max:100',
	    ]);
    	if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
    	if (Hash::check($request->oldpassword, Auth::user()->password)) {
            $update = User::findOrFail(Auth::user()->id);
            $update->password=bcrypt($request->password);
            $update->update();
            return $this->sendResponse($update,'Đổi mật khẩu thành công');
        }
        else{
        	return $this->sendError('Mật khẩu cũ không chính xác',[], 500);
        }
    }
    public function reset_view(Request $request)
    {
         
    	return view('auth.passwords.reset')->with('token',$request->token);
    }
    public function reset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|confirmed|min:6|max:100',
            'token'=>'required',
            'email'=>'required'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator->errors());
        }
        $passwordReset = PasswordReset::where('token', $request->token)->firstOrFail();
        if (Carbon::parse($passwordReset->updated_at)->addMinutes(720)->isPast()) {
            $passwordReset->delete();
            return back()->with('error','Mã xác nhận đã hết hạn');
        }
        if($passwordReset->email!=$request->email)
        {
            return back()->with('error','Email không chính xác');
        }
        $user = User::where('email', $passwordReset->email)->first();
        if($user!=NULL)
        {   
            $user->password=bcrypt($request->password);
        	$user->update();
        	$passwordReset->delete();
            return view('auth.passwords.success');
        }
        else{
             dd($user);
            return back()->with('error','Không tìm thấy thành viên nà');
        }
       return view('auth.passwords.success');
       

    }
    public function point(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'point' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $user=User::where('id','=',$request->id)->first();
        if($user!=NULL)
        {
            $user->point = $user->point + $request->point;
            if($user->save())
            {
                $log=new PointLog;
                $log->user_id=$user->id;
                $log->point=$request->point;
                $log->log=json_encode('[Web admin change]');
                $log->save();
                  return $this->sendResponse($user,'Đổi điểm thành công');
            }
            else{
                return $this->sendError('Đã có lỗi trong quá trình thay đổi điểm',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy thành viên này',[], 500);
        }
      
    }
}
