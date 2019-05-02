<?php
$host = dirname(__FILE__) . '/posts/'; //要读取的文件夹
$filesnames = scandir($host); //得到所有的文件
$i = 0;
$data = [];

class fy{
    function infofy($string){
        return $string = str_replace("\n","",$string);
    }
    function contentfy($string){
        return $string = str_replace("\n"," \n ",$string);
    }
}

$fy = new fy();

if(empty($_GET['view'])){
foreach ($filesnames as $name) {
    ++$i;
    if ($i > 2) {
        $file_path = $host.$name;
        if (file_exists($file_path)) {
            $file_arr = file($file_path);
            
            $data['posts'][$i - 2]['filename'] = explode('.',$name)[0];
            /* 获取文章详情 */
            for ($k = 0; $k < 6; $k++) {
                $temp_data_array = explode(':',$file_arr[$k]);
                $data['posts'][$i - 2]['info'][$fy->infofy($temp_data_array[0])] = $fy->infofy($temp_data_array[1]);
            }
            /* 获取文章详情结束 */

            /* 获取文章内容 */
            $temp_data_content = '';
            for ($j = 6; $j < count($file_arr); $j++) {
                $temp_data_content .= $fy->contentfy($file_arr[$j]);
            }
            $data['posts'][$i - 2]['content'] = $temp_data_content;
            /* 获取文章内容结束 */
        }
    }
}

$data['counts']['posts_count'] = $i - 2;

}else{
    $file_path = $host.$_GET['view'].'.md';
        if (file_exists($file_path)) {
            $file_arr = file($file_path);

            /* 获取文章详情 */
            for ($k = 0; $k < 6; $k++) {
                $temp_data_array = explode(':',$file_arr[$k]);
                $data['info'][$fy->infofy($temp_data_array[0])] = $fy->infofy($temp_data_array[1]);
            }
            /* 获取文章详情结束 */

            /* 获取文章内容 */
            $temp_data_content = '';
            for ($j = 6; $j < count($file_arr); $j++) {
                $temp_data_content .= $fy->contentfy($file_arr[$j]);
            }
            $data['content'] = $temp_data_content;
            /* 获取文章内容结束 */
        }
}

echo json_encode($data);
