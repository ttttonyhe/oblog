<?php
/*
    获取匹配指定字段的所有文章
    请求 key 可名为 cate、tags、author、pos
    或 cate、tags、author 任意两者或三者
    pos 不可单独请求
*/

$host = dirname(__FILE__) . '/posts/'; //要读取的文件夹
$filesnames = preg_grep('/^([^.])/', scandir($host)); //得到所有的文件
$i = 0;
$data = [];
$fetch_count = 0;

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

if (!empty($_GET['author']) && empty($_GET['cate']) && empty($_GET['tags'])) {
    $get = 'author';
    $get_method = 1;
} elseif (!empty($_GET['cate']) && empty($_GET['author']) && empty($_GET['tags'])) {
    $get = 'cate';
    $get_method = 1;
} elseif (!empty($_GET['tags']) && empty($_GET['cate']) && empty($_GET['author'])) {
    $get = 'tags';
    $get_method = 1;
} elseif (!empty($_GET['cate']) && !empty($_GET['author']) && empty($_GET['tags'])) {
    $get_method = 21;
} elseif (!empty($_GET['cate']) && !empty($_GET['tags']) && empty($_GET['author'])) {
    $get_method = 22;
} elseif (!empty($_GET['author']) && !empty($_GET['tags']) && empty($_GET['cate'])) {
    $get_method = 23;
} elseif (!empty($_GET['author']) && !empty($_GET['tags']) && !empty($_GET['cate'])) {
    $get_method = 24;
}

if (!empty($get_method)) {
    foreach ($filesnames as $name) {
            $file_path = $host . $name;
            if (file_exists($file_path)) {
                $file_arr = file($file_path);


                /* 获取文章详情 */
                for ($o = 0; $o < 5; $o++) { //循环获取文章字段
                    if ((explode(':', $file_arr[$o]))[0] == 'Author') {
                        $current_author = explode(':', $file_arr[$o])[1];
                    }
                    if ((explode(':', $file_arr[$o]))[0] == 'Cate') {
                        $current_cate = explode(':', $file_arr[$o])[1];
                    }
                    if ((explode(':', $file_arr[$o]))[0] == 'Tags') {
                        $current_tags = explode(',', explode(':', $file_arr[$o])[1]);
                        for($p=0;$p<count($current_tags);++$p){ //对标签数组每个内容格式化
                            $current_tags[$p] = $fy->infofy($current_tags[$p]);
                        }
                    }
                }




                switch ($get_method) {

                    case 1:
                    if ($get == 'tags') {
                        if (!in_array($_GET[$get], $current_tags)) { //匹配请求值
                            $status = 0; //不匹配
                        } else {
                            $status = 1; //匹配
                            $fetch_count++; //增加文章总数
                        }
                    } elseif ($get == 'cate') {
                        if ($fy->infofy($current_cate) !== $_GET[$get]) { //匹配请求值
                            $status = 0; //不匹配
                        } else {
                            $status = 1; //匹配
                            $fetch_count++; //增加文章总数
                        }
                    } elseif ($get == 'author') {
                        if ($fy->infofy($current_author) !== $_GET[$get]) { //匹配请求值
                            $status = 0; //不匹配
                        } else {
                            $status = 1; //匹配
                            $fetch_count++; //增加文章总数
                        }
                    }
                    break;

                    case 21 :
                    if ($fy->infofy($current_author) !== $_GET['author'] || $fy->infofy($current_cate) !== $_GET['cate']) { //匹配请求值
                        $status = 0; //不匹配
                    } else {
                        $status = 1; //匹配
                        $fetch_count++; //增加文章总数
                    }
                    break;

                    case 22 :
                    if (!in_array($_GET['tags'], $current_tags) || $fy->infofy($current_cate) !== $_GET['cate']) { //匹配请求值
                        $status = 0; //不匹配
                    } else {
                        $status = 1; //匹配
                        $fetch_count++; //增加文章总数
                    }
                    break;

                    case 23 :
                    if (!in_array($_GET['tags'], $current_tags) || $fy->infofy($current_author) !== $_GET['author']) { //匹配请求值
                        $status = 0; //不匹配
                    } else {
                        $status = 1; //匹配
                        $fetch_count++; //增加文章总数
                    }
                    break;

                    case 24:
                    if (!in_array($_GET['tags'], $current_tags) || $fy->infofy($current_author) !== $_GET['author'] || $fy->infofy($current_cate) !== $_GET['cate']) { //匹配请求值
                        $status = 0; //不匹配
                    } else {
                        $status = 1; //匹配
                        $fetch_count++; //增加文章总数
                    }
                    break;
                }





                /* 获取文章详情结束 */

                if ($status) { //匹配请求条件的文章
                    $data['posts'][$i]['filename'] = explode('.', $name)[0];
                    for ($k = 0; $k < 5; $k++) { //获取当前文章信息
                        $temp_data_array = explode(':', $file_arr[$k]);
                        $data['posts'][$i]['info'][$fy->infofy($temp_data_array[0])] = $fy->infofy($temp_data_array[1]);
                    }
                    $data['posts'][$i]['info']['Date'] = date('Y/m/d H:s',filectime($file_path));
                    /* 获取文章内容 */
                    $temp_data_content = '';
                    for ($j = 5; $j < count($file_arr); $j++) {
                        $temp_data_content .= $fy->contentfy($file_arr[$j]);
                    }
                    if(!empty($_GET['pos']) && $_GET['pos'] == 1){
                        $data['posts'][$i]['content'] = mb_substr($temp_data_content,0,90,'utf8');
                    }else{
                        $data['posts'][$i]['content'] = $temp_data_content;
                    }
                    /* 获取文章内容结束 */
                }
            }
        }
    $data['counts']['posts_count'] = $fetch_count;
} else {
    $data['code'] = 'Invalid Request';
}
echo json_encode($data);
