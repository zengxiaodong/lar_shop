<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class WeChatController extends Controller
{


    public function login(Request $request)
    {
        $WeChatUserInfo = $this->auth();
        $UserInfo = User::where('openid',$WeChatUserInfo['id'])->first();
        if(!$UserInfo){
        //用户的密码需要他在第一次登录的时候进行设置
        //手机号码需要进行绑定,需要根据用户id来进行异构索引表分表
            $result = User::create([
              'id' => $this->uuid(),
              'openid' => $WeChatUserInfo['id'],
              'username' => $WeChatUserInfo['name'],
              'role_id' => 1,//角色默认1位普通用户
              'vender_type' => 2,
              'status' => 0,
              'login_ip' => $request->getClientIp()
            ]);
        }else{
            $result = $UserInfo;
        }
        //登录验证
      Auth::login($result,true);

      $redirect_url = $request->redirect_url;
      if ($redirect_url == '') {
        // code...
        return \redirect('/products');
      }else{
        return \redirect($redirect_url);
      }
    }

    public function auth()
    {
        $user = session('wechat.oauth_user.default');
        if($user) return $user;
        return response()->json([
                'msg'=>'授权失败'
            ]
        );
    }





//    public function auth(Request $request)
//    {
//      $WeChatUserInfo = session('wechat.oauth_user.default');
//
//      // dd($WeChatUserInfo);
//      // exit
//
//      $UserInfo = User::where('Openid',$WeChatUserInfo['id'])->first();
//
//      if (!$UserInfo) {
//        //用户的密码需要他在第一次登录的时候进行设置
//        //手机号码需要进行绑定,需要根据用户id来进行异构索引表分表
//        $result = User::create([
//          'id' => $this->uuid(),
//          'Openid' => $WeChatUserInfo['id'],
//          'username' => $WeChatUserInfo['name'],
//          'role_id' => 1,//角色默认1位普通用户
//          'vender_type' => 2,
//          'status' => 0,
//          'login_ip' => $request->getClientIp()
//        ]);
//      }else{
//        $result = $UserInfo;
//      }
//
//      //登录验证
//      Auth::login($result,true);
//
//      $redirect_url = $request->redirect_url;
//      if ($redirect_url == '') {
//        // code...
//        return \redirect('/products');
//      }else{
//        return \redirect($redirect_url);
//      }
//    }

    public function uuid($prefix='')
    {
      // code...
      $str = md5(uniqid(mt_rand(), true));
      $uuid  = substr($str,0,8) . '-';
      $uuid .= substr($str,8,4) . '-';
      $uuid .= substr($str,12,4) . '-';
      $uuid .= substr($str,16,4) . '-';
      $uuid .= substr($str,20,12);
      return $prefix . $uuid;
    }
}
