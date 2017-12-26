<?php
$m = new MongoClient("mongodb://115.29.238.250:27017");    // 连接到mongodb
//添加数据
$db = $m->dwz;            // 选择一个数据库
$collection = $db->test; // 选择集合
$collection->remove();
$collection->close;
echo "数据插入成功";
?>