<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\Article;
use App\Models\Category;
class ArticleController extends APIController
{
	/**
     * Show the application article list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.articles');
    }
    /**
     * [Get Data article for datatable]
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
    	$records=Article::orderBy($field,$sort_type);
    	if($search!=null)
    	{
    		$records=$records->where(function($query) use($search){
                 $query->orWhere('title','like','%'.$search.'%');
            });    
    	}
        if($category_id!=null)
        {
            $records=$records->where('category_id','=',$category_id);
        }
    	$count=$records->count();
        
    	$records=$records->offset(($pagination['page'] - 1) * $pagination['perpage'])->limit($pagination['perpage']);
        $records=$records->with('categories');
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
     * [Get Article by ID]
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

        $record=Article::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
        	return $this->sendResponse($record,'Lấy thông tin tin tức thành công');
        }
        else{
        	return $this->sendError('Không tìm thấy tin tức này',[], 500);
        }
    }

    /**
     * [Delete Article by ID]
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

        $record=Article::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
        	if($record->delete())
        	{
        		return $this->sendResponse($record,'Xóa tin tức thành công');
        	}
        	else{
        		return $this->sendError('Có lỗi xảy ra trong quá trình xóa tin tức',[], 500);
        	}
        }
        else{
        	return $this->sendError('Không tìm thấy tin tức này',[], 500);
        }
    }

   /**
     * [create Article]
     * @param  Request $request [title,description,image,order]
     * @return [json]          
     */
    public function create(Request $request){
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:500',
            'category_id'=> 'required|exists:categories,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=new Article;
        $record->title=$request->title;
        $record->description=$request->description;
        $record->category_id=$request->category_id;
        $record->image=$request->image;
        $record->status=true;
        $record->content=$request->content;
        if($record->save())
        {
            return $this->sendResponse($record,'Thêm tin tức thành công');
        }
        else{
            return $this->sendError('Có lỗi xảy ra trong quá trình thêm tin tức',[], 500);
        }
        
    }
    /**
     * [update Article]
     * @param  Request $request [id,title,description,image,order]
     * @return [json]          
     */
    public function update(Request $request){
        $validator = Validator::make($request->all(), [
            'id'=>'required',
            'title' => 'required|max:500',
            'category_id'=> 'required|exists:categories,id'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=Article::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            $record->title=$request->title;
            $record->description=$request->description;
            $record->category_id=$request->category_id;
            $record->image=$request->image;
            $record->status=true;
            $record->content=$request->content;
            if($record->update())
            {
                return $this->sendResponse($record,'Cập nhật tin tức thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình cập nhật tin tức',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy tin tức này',[], 500);
        }
        
    }
    /**
     * [Block Article by ID]
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

        $record=Article::where('id','=',$request->id)->first();
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
                return $this->sendResponse($record,'Khóa tin tức thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình khóa tin tức',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy tin tức này',[], 500);
        }
    }
    /**
     * [Block Article by ID]
     * @param  Request $request [id]
     * @return [json]          
     */
    public function hot(Request $request){
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        $record=Article::where('id','=',$request->id)->first();
        if($record!=NULL)
        {
            if($record->is_hot)
            {
                $record->is_hot=false;
            }
            else{
                $record->is_hot=true;
            }

            if($record->update())
            {
                return $this->sendResponse($record,'Cài đặt nổi bật tin tức thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình Cài đặt nổi bật tin tức',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy tin tức này',[], 500);
        }
    }
}
