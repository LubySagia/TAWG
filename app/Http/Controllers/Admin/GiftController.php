<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\Gift;
use App\Models\GiftLog;
class GiftController extends APIController
{
	/**
     * Show the application gift list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.gift');
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
        $category_id=isset($query['category_id'])?trim($query['category_id']):null;
    	$records=Gift::orderBy($field,$sort_type);
    	if($search!=null)
    	{
    		$records=$records->where(function($query) use($search){
                 $query->orWhere('name','like','%'.$search.'%');
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
     * [Get Gift by ID]
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

        $record=Gift::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            $log=GiftLog::where('gift_id','=',$record->id)->orderBy('id','DESC')->with('user')->with('gift')->get();
            $record->log=$log;
        	return $this->sendResponse($record,'Lấy thông tin quà tặng thành công');
        }
        else{
        	return $this->sendError('Không tìm thấy quà tặng này',[], 500);
        }
    }

    /**
     * [Delete Gift by ID]
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

        $record=Gift::where('id','=',$request->id)->first();
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
     * [create Gift]
     * @param  Request $request [name,description,image,order]
     * @return [json]          
     */
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:500',
            'request_point'=>'required|min:0',
            'quantity'=>'required|min:0'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=new Gift;
        $record->name=$request->name;
        $record->description=$request->description;
        $record->request_point=$request->request_point;
        $record->image=$request->image;
        $record->quantity=$request->quantity;
        if($record->save())
        {
            return $this->sendResponse($record,'Thêm quà tặng thành công');
        }
        else{
            return $this->sendError('Có lỗi xảy ra trong quá trình thêm quà tặng',[], 500);
        }
        
    }
    /**
     * [update Gift]
     * @param  Request $request [id,name,description,image,order]
     * @return [json]          
     */
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'name' => 'required|max:500',
            'request_point'=>'required|min:0',
            'quantity'=>'required|min:0'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=Gift::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            $record->name=$request->name;
            $record->description=$request->description;
            $record->request_point=$request->request_point;
            $record->quantity=$request->quantity;
            $record->image=$request->image;
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
     /**
     * [Get all category]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function all(Request $request){
        $record=Gift::orderBy('id','desc')->get();
        if($record!=NULL)
        {
            return $this->sendResponse($record,'Lấy thông tin  thành công');
        }
        else{
            return $this->sendError('Không tìm thấy clb  này',[], 500);
        }
    }
}
