<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\GiftLog;
class GiftLogController extends APIController
{
	/**
     * Show the application gift list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.gift_log');
    }
    /**
     * [Get Data gift for datatable]
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
        $gift_id=isset($query['gift_id'])?trim($query['gift_id']):null;
        $records=GiftLog::orderBy($field,$sort_type);
    	if($search!=null)
    	{
            $user=User::orWhere('first_name','like','%'.$search.'%')->orWhere('last_name','like','%'.$search.'%')->orWhere('email','like','%'.$search.'%')->orWhere('phone','like','%'.$search.'%')->orWhere('address','like','%'.$search.'%')->get()->pluck('id')->toArray();
    		$records=$records->whereIn('user_id',$user);
    	}
        if($gift_id!=null)
        {
            $records=$records->where('gift_id','=',$gift_id);
        }
        $records=$records->with('user');
        $records=$records->with('gift');
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
     * [Delete GiftLog by ID]
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

        $record=GiftLog::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
        	if($record->delete())
        	{
        		return $this->sendResponse($record,'Xóa quà tặng thành công');
        	}
        	else{
        		return $this->sendError('Có lỗi xảy ra trong quá trình xóa quà tặng',[], 500);
        	}
        }
        else{
        	return $this->sendError('Không tìm thấy quà tặng này',[], 500);
        }
    }
    /**
     * [update GiftLog]
     * @param  Request $request [id,name,description,image,order]
     * @return [json]          
     */
    public function status(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=GiftLog::where('id','=',$request->id)->first();
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
                return $this->sendResponse($record,'Cập nhật quà tặng thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình cập nhật quà tặng',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy quà tặng này',[], 500);
        }
        
    }
    
}
