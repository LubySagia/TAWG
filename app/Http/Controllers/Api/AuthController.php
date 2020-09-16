<?php

namespace App\Http\Controllers\Api;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\APIController as APIController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\MemberClub;
use App\Models\LeaderClub;
use QrCode;
use Illuminate\Support\Facades\URL;
use Socialite;
use \Google\Auth\OAuth2;
class AuthController extends APIController
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
    	// validator input
    	$validator = Validator::make($request->all(), [
	        'email' => 'required|email|max:100',
	        'password' => 'required|min:6|max:100',
	    ]);
    	// validator input have error
    	if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }

        
        $credentials = request(['email', 'password']);
        //check email password
        if (! $token = JWTAuth::attempt($credentials)) {
             return $this->sendError('Email hoặc mật khẩu không chính xác','', 401);
        }

        if(JWTAuth::user()->is_block)
        {
        	 return $this->sendError('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên','', 401);
        }
        $user=User::where('id','=',JWTAuth::user()->id)->first();
        $user->auth_token=$token;
        $user->update();
        return $this->sendResponse($token,'Đăng nhập thành công.');
    }

    /**
     * Get a JWT via facebook.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login_facebook(Request $request)
    {
    	// validator input
        $validator = Validator::make($request->all(), [
	        'email' => 'required|email|max:100',
	        'id_facebook' => 'required',
	        'access_token_facebook'=> 'required',
	    ]);

        // validator input have error
	      if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        // $fb = new \Facebook\Facebook([
        //   'app_id' => '2699436790136912',
        //   'app_secret' => 'c5cd861ee7a95cbbed03ca80b2b540d3',
        //   'default_graph_version' => 'v6.0',
        //   //'default_access_token' => '{access-token}', // optional
        // ]);
        // try {
        //   // Returns a `Facebook\FacebookResponse` object
        //   $response = $fb->get(
        //     '/debug_token?input_token='.$request->access_token_facebook,
        //     $request->access_token_facebook
        //   );
        // } catch(\Facebook\Exceptions\FacebookResponseException $e) {
        //   return $this->sendError('Graph returned an error: ' . $e->getMessage(), 500);

        // } catch(\Facebook\Exceptions\FacebookSDKException $e) {
        //   return $this->sendError('Facebook SDK returned an error: ' . $e->getMessage(), 500);
        // }
        // $graphNode = $response->getGraphNode();

        // if($graphNode['app_id']!='2699436790136912' || $request->id_facebook!=$graphNode['user_id']){
        //     return $this->sendError('Facebook SDK failed, access token is not correct', 500);
        // }
        // else{
        //     try {
        //       // Get the \Facebook\GraphNodes\GraphUser object for the current user.
        //       // If you provided a 'default_access_token', the '{access-token}' is optional.
        //       $response = $fb->get('/me?fields=name,email', $request->access_token_facebook);
        //     } catch(\Facebook\Exceptions\FacebookResponseException $e) {
        //       // When Graph returns an error
        //       return $this->sendError('Graph returned an error: ' . $e->getMessage(), 500);
        //     } catch(\Facebook\Exceptions\FacebookSDKException $e) {
        //       // When validation fails or other local issues
        //        return $this->sendError('Facebook SDK returned an error: ' . $e->getMessage(), 500);
        //     }
        //     $me = $response->getGraphUser();
           
        //     if($me['email']!=$request->email){
        //         return $this->sendError('Facebook SDK failed, access token is not correct', 500);
        //     }
        // }

      	$user=User::where('email','=',$request->email)->first();
      	if($user!=null)
      	{
      		if($user->id_facebook==null)
      		{
      			$user->id_facebook=$request->id_facebook;
      		}
      		else{
      			if($user->id_facebook!=$request->id_facebook)
	      		{
	      			$user->id_facebook=$request->id_facebook;
	      		}
      		}
      		$user->access_token_facebook=$request->access_token_facebook;
      		$user->avatar=$request->avatar;
      		$user->first_name=$request->first_name;
      		$user->last_name=$request->last_name;
      		$user->save();
      	}
      	else{
      		$record=new User;
      		$record->email=$request->email;
      		$record->id_facebook=$request->id_facebook;
      		$record->avatar=$request->avatar;
      		$record->first_name=$request->first_name;
      		$record->last_name=$request->last_name;
      		$record->save();
      		$user=User::where('email','=',$request->email)->first();
      	}

      	if (!$userToken=JWTAuth::fromUser($user)) {
             return $this->sendError('Thông tin đăng nhập không chính xác',$request->all(), 401);
        }
        if($user->is_block)
        {
        	 return $this->sendError('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên','', 401);
        }
        return $this->sendResponse($userToken,'Đăng nhập thành công.');
    }

    /**
     * Get a JWT via facebook.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login_google(Request $request)
    {
       
        $validator = Validator::make($request->all(), [
	        'email' => 'required|email|max:100',
	        'id_google' => 'required',
	        'access_token_google'=> 'required',
	    ]);

        // validator input have error
	      if ($validator->fails()) {
            return $this->sendError($validator->errors(),$request->all(), 422);
        }
        // try {
        //   $publicKey = file_get_contents(__DIR__.'/../../../../key/tawgplatform-2599ad54a420.json');
        //   $oauth = new OAuth2([
        //       'clientId' => 'tawgplatform@appspot.gserviceaccount.com',
        //       'additionalClaims' => [
        //           'hd' => 'tawg.org'
        //       ]
        //   ]);
        //   $oauth->setIdToken($request->access_token_google);
        //   $payload = $oauth->verifyIdToken($request->id_google);
        //   // $client = new \Google_Client();  // Specify the CLIENT_ID of the app that accesses the backend
        //  // $client = new \Google_Client(); 
        //   //321674299899-g7jv5nliv43kr6hl5pvi5l660semtr49.apps.googleusercontent.com
        //   //l3bd8GTMnIRc1_9THLwpq3_d
        //   //$client->useApplicationDefaultCredentials();
        //   //$payload=$client->fetchAccessTokenWithAuthCode($request->access_token_google);
        //   //$payload = $client->verifyIdToken($request->id_google);
        //   dd($payload);
        //     //$client = new Google_Client();
        //   // $client->setApplicationName("TAWGPlatform");
        //   // $client->setDeveloperKey(__DIR__.'/../../../key/tawgplatform-2599ad54a420.json');
        // }catch(Exceptions $e){
        //   return $this->sendError('Google API',$e->getMessage(), 500);
        // }
      	$user=User::where('email','=',$request->email)->first();
      	if($user!=null)
      	{
      		if($user->id_google==null)
      		{
      			$user->id_facebook=$request->id_google;
      		}
      		else{
      			if($user->id_google!=$request->id_google)
	      		{
	      			$user->id_facebook=$request->id_google;
	      		}
      		}
      		$user->access_token_google=$request->access_token_google;
      		$user->avatar=$request->avatar;
      		$user->first_name=$request->first_name;
      		$user->last_name=$request->last_name;
      		$user->save();
      	}
      	else{
      		$record=new User;
      		$record->email=$request->email;
      		$record->id_google=$request->id_google;
      		$record->avatar=$request->avatar;
      		$record->first_name=$request->first_name;
      		$record->last_name=$request->last_name;
      		$record->save();
      		$user=User::where('email','=',$request->email)->first();
      	}

      	if (!$userToken=JWTAuth::fromUser($user)) {
             return $this->sendError('Thông tin đăng nhập không chính xác',$request->all(), 401);
        }

        if($user->is_block)
        {
        	 return $this->sendError('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên','', 401);
        }
        return $this->sendResponse($userToken,'Đăng nhập thành công.');
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
    	  if(JWTAuth::user()->is_block)
        {
        	 return $this->sendError('Tài khoản của bạn đã bị khóa. Vui lòng liên hệ quản trị viên','', 401);
        }
        $current_user=auth()->user();
        
        if( $current_user->id_facebook!=null || $current_user->id_google !=NULL)
        {
        	$current_user->login_social=true;
        }
        else{
        	$current_user->login_social=false;
        }
        $clubs=MemberClub::where('user_id','=',$current_user->id)->get()->pluck('club_id');
        $leaders=LeaderClub::where('user_id','=',$current_user->id)->get()->pluck('club_id');
        $current_user->clubs=$clubs;
        $current_user->leaders=$leaders;
        if($current_user->qrcode==NULL)
        {
          $file="/data/qr/".md5($current_user->id.$current_user->email).'.png';
          $user=User::where('id','=',$current_user->id)->first();
          $json=base64_encode(strrev(str_rot13(base64_encode(json_encode($user)))));
          $json=str_replace('==','@tawg',$json);
          QrCode::format('png')->size(300)->generate($json, public_path().$file);
          $current_user->qrcode=URL::to('/').$file;
         
          $user->qrcode=URL::to('/').$file;
          $user->save();
        }
        return $this->sendResponse($current_user,'Thành Công');
    
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}