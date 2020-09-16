<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
class CategoryController extends APIController
{
	/**
     * Show the application category list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.categories');
    }
    /**
     * [Get Data category for datatable]
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
    	$records=Category::orderBy($field,$sort_type);
    	if($search!=null)
    	{
    		$records=$records->where(function($query) use($search){
                 $query->orWhere('title','like','%'.$search.'%');
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
     * [Get Category by ID]
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

        $record=Category::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
        	return $this->sendResponse($record,'Lấy thông tin danh mục thành công');
        }
        else{
        	return $this->sendError('Không tìm thấy danh mục này',[], 500);
        }
    }

    /**
     * [Delete Category by ID]
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

        $record=Category::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
        	if($record->delete())
        	{
        		return $this->sendResponse($record,'Xóa danh mục thành công');
        	}
        	else{
        		return $this->sendError('Có lỗi xảy ra trong quá trình xóa danh mục',[], 500);
        	}
        }
        else{
        	return $this->sendError('Không tìm thấy danh mục này',[], 500);
        }
    }

   /**
     * [create Category]
     * @param  Request $request [title,description,image,order]
     * @return [json]          
     */
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:500'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=new Category;
        $record->title=$request->title;
        $record->description=$request->description;
        $record->order=$request->order;
        $record->image=$request->image;
        if($record->save())
        {
            return $this->sendResponse($record,'Thêm danh mục thành công');
        }
        else{
            return $this->sendError('Có lỗi xảy ra trong quá trình thêm danh mục',[], 500);
        }
        
    }
    /**
     * [update Category]
     * @param  Request $request [id,title,description,image,order]
     * @return [json]          
     */
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'title' => 'required|max:500'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=Category::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            $record->title=$request->title;
            $record->description=$request->description;
            $record->order=$request->order;
            $record->image=$request->image;
            if($record->update())
            {
                return $this->sendResponse($record,'Cập nhật  thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình cập nhật danh mục',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy danh mục này',[], 500);
        }
        
    }
    /**
     * [Block Category by ID]
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

        $record=Category::where('id','=',$request->id)->first();
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
                return $this->sendResponse($record,'Khóa danh mục thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình khóa danh mục',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy danh mục này',[], 500);
        }
    }
    /**
     * [Get all category]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function all(Request $request){
        $record=Category::orderBy('id','desc')->get();
        if($record!=NULL)
        {
            return $this->sendResponse($record,'Lấy thông tin  thành công');
        }
        else{
            return $this->sendError('Không tìm thấy clb  này',[], 500);
        }
    }
   
}
