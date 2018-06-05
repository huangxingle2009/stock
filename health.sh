#! /bin/bash
count=`ps -ef | grep artisan | grep qq:send | wc -l`
#echo $count
if [ 0 == $count ]; then
    nohup /usr/bin/php /data/html/stock/artisan qq:send > /data/logs/qq-send.log 2>&1 &
fi