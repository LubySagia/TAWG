<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\Category;
use JWTAuth;
class CategoryController extends APIController
{
    /**
     * [data Category]
     * @param  Request $request [id]
     * @return [type]           [Json]
     */
    public function data(Request $request)
    {
        $id=isset($request->id)?trim($request->id):null;
        if($id!=null)
        {
            $record=Category::where('id','=',$request->id)->first();
            if($record==null)
            {
                return $this->sendError('Không tìm thấy danh mục này',[], 500);
            }
        }
        else{
            $record=Category::orderBy('order','asc')->get();
        }
        return $this->sendResponse($record,'');
    }
}
