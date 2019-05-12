<?php
/*
    获取所有文章里存在的字段
    请求 key 名为 key
    key 为 cate、tags、author
*/

$host = dirname(dirname(__FILE__)) . '/posts/'; //要读取的文件夹
$filesnames = preg_grep('/^([^.])/', scandir($host)); //得到所有的文件
$i = 0;
$data = [];
$key_count = 0;

class fy
{
    function infofy($string)
    {
        return $string = str_replace("\n", "", $string);
    }
    function contentfy($string)
    {
        return $string = str_replace("\n", " \n ", $string);
    }
}

$fy = new fy();

$key = $_GET['key'];

if (!empty($key)) {
    foreach ($filesnames as $name) {
        ++$i;
        $file_path = $host . $name;
        if (file_exists($file_path)) {
            $file_arr = file($file_path);

            if ($key !== 'tags') { //若不为 tags
                for ($k = 0; $k < 5; $k++) {
                    if ((explode(':', $file_arr[$k]))[0] == ucfirst($key)) { //获取到字段
                        $data[$key][] = $fy->infofy((explode(':', $file_arr[$k]))[1]); //添加入数组
                    }
                }
            } else { //若为 tags
                for ($k = 0; $k < 5; $k++) {
                    if ((explode(':', $file_arr[$k]))[0] == 'Tags') { //获取到字段
                        $temp_array = explode(',', (explode(':', $file_arr[$k]))[1]); //分隔字符串
                        for ($i = 0; $i < count($temp_array); ++$i) { //添加到数组
                            $data['tags'][] = $fy->infofy($temp_array[$i]);
                        }
                    }
                }
            }
        }
    }
    $data[$key] = array_unique($data[$key]); //删除重复
    $key_count = count($data[$key]); //获取数量
    $data['counts']['key_count'] = $key_count;
} else {

    $data['code'] = 'Invalid Request';
}

echo json_encode($data);
