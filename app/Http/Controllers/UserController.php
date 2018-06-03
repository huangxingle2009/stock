<?php

namespace App\Http\Controllers;



use GatewayClient\Gateway;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public function bind(Request $request) {
        $data = $request->all();

        Gateway::$registerAddress = '127.0.0.1:1238';

        // 假设用户已经登录，用户uid和群组id在session中
        $uid      = rand(0, 10000);//$_SESSION['uid'];
        $group_id = 1;//$_SESSION['group'];
        // client_id与uid绑定
        Gateway::bindUid($data['client_id'], $uid);
        // 加入某个群组（可调用多次加入多个群组）
        Gateway::joinGroup($data['client_id'], $group_id);
        $message = json_encode(array(
            'type'      => 'message',
            'client_id' => $data['client_id']
        ));

        Gateway::sendToUid($uid, $message);
        return $message;
    }

    public function say(Request $request) {
        $data = $request->all();
        Gateway::$registerAddress = '127.0.0.1:1238';
        $message = json_encode(array(
            'type'      => 'say',
            'client_id' => $data['client_id']
        ));
        Gateway::sendToGroup(1, $message);

    }

}