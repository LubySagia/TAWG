<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;
class SettingController extends APIController
{
	/**
     * Show the application category list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('admin.setting');
    }


    /**
     * [Get Setting by ID]
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function view(Request $request){

        $record=Setting::where('id','=',1)->first();
        if($record!=NULL)
        {
        	return $this->sendResponse($record,'Lấy thông tin cài đặt thành công');
        }
        else{
        	return $this->sendError('Không tìm thấy cài đặt này',[], 500);
        }
    }


    /**
     * [update Setting]
     * @param  Request $request [id,title,description,image,order]
     * @return [json]          
     */
    public function update(Request $request){
        $record=Setting::where('id','=',1)->first();
        if($record!=NULL)
        {
            $record->check_in_point=$request->check_in_point;
            if($record->update())
            {
                return $this->sendResponse($record,'Cập nhật thành công');
            }
            else{
                return $this->sendError('Có lỗi xảy ra trong quá trình cập nhật cài đặt',[], 500);
            }
        }
        else{
            return $this->sendError('Không tìm thấy cài đặt này',[], 500);
        }
        
    }
    
   
}
