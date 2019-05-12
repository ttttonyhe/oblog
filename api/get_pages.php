<?php
/*
    获取所有页面
    请求 key 可名为 pos
    pos 为 1 则获取 90字摘要
*/

$host = dirname(dirname(__FILE__)) . '/pages/'; //要读取的文件夹
$filesnames = preg_grep('/^([^.])/', scandir($host)); //得到所有的文件
$i = 0;
$data = [];
$count = 0;

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

if (empty($_GET['view'])) {

    foreach ($filesnames as $name) {
        ++$i;
        $file_path = $host . $name;
        if (file_exists($file_path)) {
            $file_arr = file($file_path);
            /* 获取页面详情 */
            $data['pages'][$i]['filename'] = explode('.', $name)[0]; //获取文件名
            $temp_data_array = explode(':', $file_arr[0]);
            $data['pages'][$i]['info']['Title'] = $fy->infofy($temp_data_array[1]);
            $data['pages'][$i]['info']['Date'] = date('Y/m/d H:s', filectime($file_path));
            $data['pages'][$i]['info']['Type'] = 'page';
            /* 获取页面详情结束 */

            /* 获取文章内容 */
            $temp_data_content = '';
            for ($j = 1; $j < count($file_arr); $j++) {
                $temp_data_content .= $fy->contentfy($file_arr[$j]);
            }
            if (!empty($_GET['pos']) && $_GET['pos'] == 1) {
                $data['pages'][$i]['content'] = mb_substr($temp_data_content, 0, 90, 'utf8');
            } else {
                $data['pages'][$i]['content'] = $temp_data_content;
            }
            /* 获取文章内容结束 */
        }
    }

    $data['counts']['total_posts_count'] = $i;
} else {

    $file_path = $host . $_GET['view'] . '.md';
    if (file_exists($file_path)) {
        $file_arr = file($file_path);

        /* 获取文章详情 */
        $temp_data_array = explode(':', $file_arr[0]);
        $data['info']['Title'] = $fy->infofy($temp_data_array[1]);
        $data['info']['Date'] = date('Y/m/d H:s', filectime($file_path));
        /* 获取文章详情结束 */

        /* 获取文章内容 */
        $temp_data_content = '';
        for ($j = 1; $j < count($file_arr); $j++) {
            $temp_data_content .= $fy->contentfy($file_arr[$j]);
        }
        $data['content'] = $temp_data_content;
        /* 获取文章内容结束 */
    }
}

echo json_encode($data);
