<?php
/*
    获取非指定排除字段的所有文章
    请求 key 可名为 exclude_type、exlude_value、pos
    exclude_type 为排除字段名 可为 author、cate、tags、date、img、title
    exclude_value 为排除字段值 当字段名为 tags 时只能为一个值
*/

$host = dirname(dirname(__FILE__)) . '/posts/'; //要读取的文件夹
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

            $status = 1;

            if (!empty($_GET['exclude_type']) && !empty($_GET['exclude_value'])) { //存在排除判断
                /* 判断文章详情 */
                for ($k = 0; $k < 5; $k++) { //循环获取到要排除的 key
                    $temp_data_array = explode(':', $file_arr[$k]);
                    if ($fy->infofy($temp_data_array[0]) == ucfirst($_GET['exclude_type'])) { //获取到要排除的 key
                        if ($_GET['exclude_type'] == 'tags') { //若为 tags
                            if (in_array($_GET['exclude_value'], explode(',', $fy->infofy($temp_data_array[1])))) { //若要排除的 key 有要排除的 value
                                $status = 0;
                            }
                        } else {
                            if ($fy->infofy($temp_data_array[1]) == $_GET['exclude_value']) { //若要排除的 key 有要排除的 value
                                $status = 0;
                            }
                        }
                    }
                }
                /* 判断文章详情结束 */
            }

            if ($status) {
                ++$count;
                /* 获取文章详情 */
                $data['posts'][$i]['filename'] = explode('.', $name)[0]; //获取文件名
                for ($k = 0; $k < 5; $k++) {
                    $temp_data_array = explode(':', $file_arr[$k]);
                    if ($temp_data_array[0] == 'Img') {
                        @$data['posts'][$i]['info'][$fy->infofy($temp_data_array[0])] = $fy->infofy($temp_data_array[1]) . ':' . $fy->infofy($temp_data_array[2]);
                    } else {
                        $data['posts'][$i]['info'][$fy->infofy($temp_data_array[0])] = $fy->infofy($temp_data_array[1]);
                    }
                }
                $data['posts'][$i]['info']['Date'] = date('Y/m/d H:s', filectime($file_path));
                /* 获取文章详情结束 */

                /* 获取文章内容 */
                $temp_data_content = '';
                for ($j = 5; $j < count($file_arr); $j++) {
                    $temp_data_content .= $fy->contentfy($file_arr[$j]);
                }
                if (!empty($_GET['pos']) && $_GET['pos'] == 1) {
                    $data['posts'][$i]['content'] = mb_substr($temp_data_content, 0, 90, 'utf8');
                } else {
                    $data['posts'][$i]['content'] = $temp_data_content;
                }
                /* 获取文章内容结束 */
            }
        }
    }

    $data['counts']['posts_count'] = $count;
    $data['counts']['total_posts_count'] = $i;
} else {

    $file_path = $host . $_GET['view'] . '.md';
    if (file_exists($file_path)) {
        $file_arr = file($file_path);

        /* 获取文章详情 */
        for ($k = 0; $k < 5; $k++) {
            $temp_data_array = explode(':', $file_arr[$k]);
            if ($temp_data_array[0] == 'Img') {
                @$data['info'][$fy->infofy($temp_data_array[0])] = $fy->infofy($temp_data_array[1]) . ':' . $fy->infofy($temp_data_array[2]);
            } else {
                $data['info'][$fy->infofy($temp_data_array[0])] = $fy->infofy($temp_data_array[1]);
            }
        }
        $data['info']['Date'] = date('Y/m/d H:s', filectime($file_path));
        /* 获取文章详情结束 */

        /* 获取文章内容 */
        $temp_data_content = '';
        for ($j = 5; $j < count($file_arr); $j++) {
            $temp_data_content .= $fy->contentfy($file_arr[$j]);
        }
        $data['content'] = $temp_data_content;
        /* 获取文章内容结束 */
    }
}

echo json_encode($data);
