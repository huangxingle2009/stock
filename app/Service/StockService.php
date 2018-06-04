<?php
namespace App\Service;
use App\Model\Stock;
use Curl\Curl;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class StockService {
    static $md;


    public static function worm($uid = null, $before = null){
        if (!$uid)
            return;
        try {
            self::$md = self::_getMd();

            try {
                #$cookie = Storage::get("cookie_file.txt");
                #$cookie = json_decode($cookie, true);

            } catch (\Exception $e) {

                #$cookie = self:: _checkLoin();
            }

            $curl = new Curl();
            $url = 'https://www.aigupiao.com/api/liver_msg.php?act=liver_center&source=pc&md=' . self::$md;
            $curl->setUserAgent("Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36");
            #$curl->setCookies($cookie);
            $body = [
                'id' => $uid,
                'u_id' => '296904',
                'before' => $before ?: time(),
                'source' => 'pc'
            ];
            $curl->post($url, $body);
            $list = json_decode($curl->response, true);
            if ($list) {
                if (count($list['msg_list']) > 0) {
                    foreach ($list['msg_list'] as $val) {
                        if ($val['kind'] == 'vip') {
                            $item = self::getDetail($val['id']);
                            if (!$item) {
                                echo $val['id'];exit;
                            }
                            $stock = new Stock();
                            $stock->create([
                                'show_detail' => json_encode($item),
                                'uid' => $uid,
                                'created_at' => $item['rec_time'],
                                'updated_at' => $item['rec_time']
                            ]);
                        }
                    }
                    $last = array_pop($list['msg_list']);
//                    usleep(20);
                    self::worm($uid, $last['rec_time']);

                }

            }


        } catch (\Exception $e) {
            //unique 重复
            if ($e->getCode() == '23000') {
//                echo $uid . "\r\n";

            } else {
                //发短信给管理员
                var_dump($e->getMessage());

            }

        }

    }

    public static function getDetail($id) {
        $curl = new Curl();
        $url = 'https://www.aigupiao.com/api/live.php';
        $curl->setUserAgent("Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36");
        $curl->get($url, [
            'act' => 'load_detail',
            'oid' => $id,
            'source' => 'pc',
            'md' => self::$md
        ]);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";exit;
        } else {
           $arr = json_decode($curl->response, true)['show_detail'];
           return array_pop($arr);
        }

    }

    public static function _checkLoin()
    {
        $login_url = 'https://www.aigupiao.com/Api/User/login/md/' . self::$md;
        $proxy_url = file_get_contents("http://api.ip.data5u.com/dynamic/get.html?order=bade50d04dab15880be6860312b38bd1&sep=3");
        list($proxy_url, ) = explode("\n", $proxy_url);

        $curl = new Curl();
        $curl->setOpt(CURLOPT_HTTPPROXYTUNNEL, 1);
        $curl->setOpt(CURLOPT_PROXY, $proxy_url);
//        $curl->setHeaders([
//            "X-FORWARDED-FOR" => '220.181.57.216',
//            "CLIENT-IP" => '220.181.57.216'
//        ]);
        $curl->setReferrer("https://www.aigupiao.com/index?act=all_msg");
        $curl->setUserAgent("Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.57 Safari/536.11");
        $curl->post($login_url, [
            'account' => '13634133578',
            'passwd' => '123456'
        ]);
        if ($curl->error) {
            echo 'Error: ' . $curl->errorCode . ': ' . $curl->errorMessage . "\n";exit;
        } else {
            $cookie = $curl->getResponseCookies();
            Storage::put("cookie_file.txt", json_encode($cookie));
        }
        return $cookie;

    }


    private static function _getMd() {
        $md = Cache::remember('md', 60, function () {
            $url = 'https://www.aigupiao.com/user/regist.php';
            $content = file_get_contents($url);
            preg_match_all("/_Libs={\"md\":\"(.*)\"}/", $content, $match);
            if (empty($match[1])) {
                //发短信通知
            }
            $md = $match[1][0];
            return $md;
        });
        return $md;
    }

    public static function getLastQueryTime($uid) {
        $created_at = Cache::rememberForever($uid . '_latestTime', function () use($uid){
            $model = new Stock();
            $res = $model->where("uid", $uid)->orderBy("created_at", "desc")->first();
            if (!$res)
                return 0;
            else
                return $res->toArray()['created_at'];

        });
        return $created_at;
    }


}