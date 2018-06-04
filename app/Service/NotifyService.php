<?php
namespace App\Service;


use App\Model\Stock;

class NotifyService {


    public static function sendToGroup() {
        $userMap = config("user");
        $model = new Stock();
        $list = $model->where('status', 0)->limit(100)->get();
        if ($list) {
            foreach ($list as $key => $val) {
                $detail = json_decode($val->show_detail, true);
                $groupName = '';
                if (!empty($detail['g_id'])) {
                    $groupName = $userMap[$val['uid']]['group'][$detail['g_id']];

                }
                $content = '';
                if ($groupName) {
                    $content .= "【".$groupName."】";
                }
                $content .= strip_tags($detail['content']);
                $qqGroupName = $userMap[$val['uid']]['qq'];
                $return = exec("/usr/local/bin/qq send group $qqGroupName " . $content . "\r\n", $res);
                if ($return == '无法连接 QQBot-Term 服务器') {

                } else {
                    $model->where("id", $val->id)->update([
                        'status' => 1
                    ]);
                }

            }
        }

    }



}