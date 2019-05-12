<?php
$array = array();
$array[0]['name'] = '首页';
$array[0]['url'] = 'http://localhost/oblog';
$array[1]['name'] = '博客';
$array[1]['url'] = 'https://www.ouorz.com';
$array[2]['name'] = '伙伴';
$array[2]['url'] = 'http://localhost/oblog/archives.php?cate=伙伴链接';
$array[3]['name'] = '关于';
$array[3]['url'] = 'https://www.ouorz.com/126';


echo json_encode($array);