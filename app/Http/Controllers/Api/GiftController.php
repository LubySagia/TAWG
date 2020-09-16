<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Support\Facades\Validator;
use App\Models\Gift;
use App\Models\GiftLog;
use App\Models\PointLog;
use App\Models\User;
use JWTAuth;
class GiftController extends APIController
{
    /**
     * [view Gift]
     * @param  Request $request [id]
     * @return [type]           [Json]
     */
    public function data(Request $request)
    {
        $id=isset($request->id)?trim($request->id):null;
        if($id!=null)
        {
            $record=Gift::where('id','=',$request->id)->first();
            if($record==null)
            {
                return $this->sendError('Không tìm thấy danh mục này',[], 500);
            }
        }
        else{
            $record=Gift::orderBy('name','asc')->get();
        }
        return $this->sendResponse($record,'');
    }

    public function exchange(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'gift_id'=> 'required|exists:gifts,id',
            'user_id'=> 'required|exists:users,id',
        ]);
        if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        $user=User::where('id','=',$request->user_id)->first();
        $gift=Gift::where('id','=',$request->gift_id)->first();
        if($gift->quantity<=0)
        {
             return $this->sendError('Quà tặng này đã hết',[], 500);
        }
        if($user->point< $gift->request_point)
        {
             return $this->sendError('Bạn không đủ điểm để đổi quà',[], 500);
        }

        $user->point=$user->point-$gift->request_point;
        $user->update();
        $gift->quantity=$gift->quantity-1;
        $gift->update();
        $record=new GiftLog;
        $record->gift_id=$gift->id;
        $record->user_id=$user->id;
        $record->number=1;
        if($record->save())
        {
            $log=new PointLog;
            $log->user_id=$user->id;
            $log->point=0-$gift->request_point;
            $log->log=json_encode('['.$gift->id.'] - '.$gift->name.':'.$gift->request_point);
            $log->save();
            $record=GiftLog::where('id','=',$record->id)->with('user')->with('gift')->first();
             return $this->sendResponse($record,'Đổi quà thành công');
        }
        else{
            return $this->sendError('Đã có lỗi xảy ra trong quá trình đổi quà',[], 500);
        }
       
    }
    public function history(Request $request)
    {
        $gift_id=isset($request->gift_id)?(int)$request->gift_id:0;
        $user_id=isset($request->user_id)?(int)$request->user_id:0;
         
        $record=GiftLog::orderBy('id','DESC');
        if($gift_id!=0)
        {
            $gift=Gift::where('id','=',$gift_id)->first();
            if($gift==null)
            {
                return $this->sendError('Không tìm thấy quà tặng này',$request->all(), 500);
            }
            $record=$record->where('gift_id','=',$gift_id);
        }

        if($user_id!=0)
        {
            $user=User::where('id','=',$user_id)->first();
            if($user==null)
            {
                return $this->sendError('Không tìm thấy thành viên này',$request->all(), 500);
            }
            $record=$record->where('user_id','=',$user->id);
        }
        $record=$record->with('user');
        $record=$record->with('gift')->get();
        return $this->sendResponse($record,'Thành công');
    }
}
