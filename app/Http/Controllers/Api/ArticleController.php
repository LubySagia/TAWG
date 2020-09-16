<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\Article;
use App\Models\Category;
use JWTAuth;
class ArticleController extends APIController
{
    /**
     * [data Article]
     * @param  Request $request [id]
     * @return [type]           [Json]
     */
    public function data(Request $request)
    {
        
        $category_id=isset($request->category_id)?trim($request->category_id):null;
        $title=isset($request->title)?trim($request->title):null;

        $record=Article::orderBy('id','desc');
        if($category_id!=null)
        {
            $check_category=Category::where('id','=',$request->category_id)->first();
            if($check_category==null)
            {
                return $this->sendError('Không tìm thấy danh mục này',[], 500);
            }
            $record=$record->where('category_id','=',$category_id);
        }
        if($title!=null)
        {
            $record=$record->where('title','like','%'.$title.'%');  
        }
        $record=$record->paginate(20)->makeHidden('content');
        return $this->sendResponse($record,'');
    }
    /**
     * [hot Article]
     * @param  Request $request [id]
     * @return [type]           [Json]
     */
    public function hot(Request $request)
    {
        
        $category_id=isset($request->category_id)?trim($request->category_id):null;
        $title=isset($request->title)?trim($request->title):null;

        $record=Article::orderBy('id','desc');
        if($category_id!=null)
        {
            $check_category=Category::where('id','=',$request->category_id)->first();
            if($check_category==null)
            {
                return $this->sendError('Không tìm thấy danh mục này',[], 500);
            }
            $record=$record->where('category_id','=',$category_id);
        }

        $record=$record->where('is_hot','=',true)->get()->makeHidden('content');
        return $this->sendResponse($record,'');
    }
    /**
     * [list Article]
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
        $record=Article::where('id','=',$request->id)->with('categories')->first();
        if($record==null)
        {
            return $this->sendError('Không tìm thấy tin tức này này',[], 500);
        }
      
        return $this->sendResponse($record,'');
    }
}
