<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class WechatController extends Controller
{
    protected $wechat;


    public function __construct()
    {
        $wechat = app('wechat');

        $this->wechat = $wechat;
    }


    /**
     * 处理微信的请求消息
     *
     * @return string
     */
    public function serve()
    {
        $this->wechat->server->setMessageHandler(function($message){

            switch ($message->MsgType) {
                case 'event':
                    return '收到事件消息';
                    break;
                case 'text':
                    return '收到文字消息';
                    break;
                case 'image':
                    return '收到图片消息';
                    break;
                case 'voice':
                    return '收到语音消息';
                    break;
                case 'video':
                    return '收到视频消息';
                    break;
                case 'location':
                    return '收到坐标消息';
                    break;
                case 'link':
                    return '收到链接消息';
                    break;
                // ... 其它消息
                default:
                    return '收到其它消息';
                    break;
            }

        });

        return $this->wechat->server->serve();
    }

    public function addMenu()
    {
        try{

            $buttons = [
                [
                    "type" => "click",
                    "name" => "乐其意",
                    "key"  => "V1001_LE71"
                ],
                [
                    "name"       => "菜单",
                    "sub_button" => [
                        [
                            "type" => "view",
                            "name" => "首页",
                            "url"  => "http://dc.le71.cn/"
                        ],
                        [
                            "type" => "view",
                            "name" => "测试1",
                            "url"  => "http://dc.le71.cn/wechat/test"
                        ],
                        [
                            "type" => "click",
                            "name" => "测试2",
                            "key"  => "V1001_GOOD",
                        ],
                    ],
                ],
            ];

            $menu = $this->wechat->menu;

            $menu->add($buttons);

            $menus = $menu->all();


        }catch (\Exception $e){

            return response()->json(['success'=>false,'msg'=>$e->getMessage()]);

        }

        return response($menus);

    }

    public function test(){
	
        $oauth = $this->wechat->oauth;

        $js    = $this->wechat->js;

        if (!Session::has('w_user')){

            return $oauth->redirect();

    	}

        $user = Session::get('w_user');

        return view('test',['user'=>$user,'js'=>$js]);

    }

    public function text(){

        $js = $this->wechat->js;

        return view('test',['js'=>$js]);

    }

    public  function oauth(){

        $oauth = $this->wechat->oauth;

        $user  = $oauth->user();
	
        Session::push('w_user',$user->toArray());

        return redirect('wechat/test');

    }

    	

}
