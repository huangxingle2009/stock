<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

    </head>
    <body>

        web socket
    </body>
</html>

<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>

<script type="text/javascript">
    ws = new WebSocket("ws://localhost:8282");
    // 服务端主动推送消息时会触发这里的onmessage
    ws.onmessage = function(e){
        console.info(e)
        // json数据转换成js对象
        var data = eval("("+e.data+")");
        console.info(data);
        var type = data.type || '';
        switch(type){
            // Events.php中返回的init类型的消息，将client_id发给后台进行uid绑定
            case 'init':
                // 利用jquery发起ajax请求，将client_id发给后端进行uid绑定
                $.get('./bind', {client_id: data.client_id}, function(data){});
                break;
            // 当mvc框架调用GatewayClient发消息时直接alert出来
            default :
                alert(e.data);

        }
    };
    ws.onclose = function () {
        alert(111);

    }
</script>