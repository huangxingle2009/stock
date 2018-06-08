<?php

namespace App\Http\Controllers;




use App\Model\Stock;
use App\Service\NotifyService;
use App\Service\StockService;
use Curl\Curl;
use Illuminate\Support\Facades\Storage;

class StockController extends Controller
{


    public function worm(){
        new NotifyServic();
        exit;
        NotifyService::sendToGroup();
        exit;
        //login
        StockService::worm(8);
        exit;

//        $curl->setCookie('PHPSESSID', 'ma4cght76o1olc01laduosoc02');


//        exit;
//        $url = 'https://www.aigupiao.com/api/live.php';
//
//        $curl->get($url, [
//            'act' => 'load_detail',
//            'oid' => 945765,
//            'source' => 'pc',
//            'md' => '1e9557247f91902bed5c7a75471f7dcc'
//        ]);


//            var_dump($cookie);
        try {
            $cookie = Storage::get("cookie_file.txt");
            $cookie = json_decode($cookie, true);

        } catch (\Exception $e) {

            $cookie = $this->_checkLoin();
        }


        $curl = new Curl();
        $url = 'https://www.aigupiao.com/api/liver_msg.php?act=liver_center&source=pc&md=1e9557247f91902bed5c7a75471f7dcc';
        $curl->setUserAgent("Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36");
        $curl->setCookies($cookie);
        $curl->post($url, [
            'id' => '8',
            'u_id' => '296904',
            'before' => '1525274592',
            'source' => 'pc'
        ]);
        dd(json_decode($curl->response, true));
        exit;

    }

    public function _checkLoin()
    {
        $login_url = 'https://www.aigupiao.com/Api/User/login&md=1e9557247f91902bed5c7a75471f7dcc';

        $curl = new Curl();
        $curl->setUserAgent("Mozilla/5.0 (Macintosh; Intel Mac OS X 10_12_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3359.181 Safari/537.36");
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

}