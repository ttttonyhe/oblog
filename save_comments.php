<?php
error_reporting(E_ALL ^ E_NOTICE);

class post_back
{
    function save($key)
    {
        return !empty($_POST[$key]) ? addslashes($_POST[$key]) : 0;
    }
    function infofy($string)
    {
        return $string = str_replace("\n", "", $string);
    }
    function contentfy($string)
    {
        return $string = str_replace("\n", " \n ", $string);
    }
    function valid_post($pid)
    {
        $host = dirname(__FILE__) . '/posts/'; //要读取的文件夹
        $file_path = $host . $pid . '.md';
        if (file_exists($file_path)) {
            return 1;
        } else {
            return 0;
        }
    }
    function valid_comm_post($pid)
    {
        $host = dirname(__FILE__) . '/comments/'; //要读取的文件夹
        $file_path = $host . $pid . '.json';
        if (file_exists($file_path)) {
            return 1;
        } else {
            return 0;
        }
    }
}

$post = new post_back();

/* 验证信息 */
$ver =  $post->save('ver');
$name = $post->save('name');
$email = $post->save('email');
$content = $post->save('content');
$pid = $_POST['pid'];
$reply = $_POST['reply'];

if ($reply !== NULL) { //回复评论
    $reply_status = 1;
} else {
    $reply_status = 0;
}

$array = array();
if (empty($ver) || empty($name) || empty($email) || empty($content) || empty($pid)) {
    $array['code'] = 101;
    $array['info'] = '信息不全';
    echo json_encode($array, JSON_UNESCAPED_UNICODE);
} else if ($ver == 'comment_ver') {
    /* 结束验证信息 */
    if ($post->valid_post($pid)) { //判断文章是否存在
        //处理评论内容
        if ($post->valid_comm_post($pid)) {
            $json_string = file_get_contents(dirname(__FILE__) . '/comments/' . $pid . ".json"); // 从文件中读取数据到PHP变量
            $data = json_decode($json_string, true); // 把JSON字符串转成PHP数组
            $data_length = count($data);

            if ($reply_status) { //处理回复请求
                //回复的评论是否存在
                if ((int)$reply > ($data_length - 1)  || (int)$reply < 0) {
                    $array['code'] = 104;
                    $array['info'] = '回复不存在';
                    echo json_encode($array, JSON_UNESCAPED_UNICODE);
                    die();
                }

                //获取回复对应评论回复数量
                if (!!$data[$reply]['reply']) {
                    $reply_length = count($data[$reply]['reply']);
                } else {
                    $reply_length = 0;
                }

                //增加在对应评论回复字段
                $data[$reply]['reply'][$reply_length]['id'] = $reply_length;
                $data[$reply]['reply'][$reply_length]['name'] = $name;
                $data[$reply]['reply'][$reply_length]['email'] = $email;
                $data[$reply]['reply'][$reply_length]['content'] = $content;
                $data[$reply]['reply'][$reply_length]['date'] = time();
            } else { //处理评论请求

                $data[$data_length]['id'] = $data_length;
                $data[$data_length]['name'] = $name;
                $data[$data_length]['date'] = time();
                $data[$data_length]['email'] = $email;
                $data[$data_length]['content'] = $content;
            }
            $json_strings = json_encode($data);
            file_put_contents(dirname(__FILE__) . '/comments/' . $pid . ".json", $json_strings); //写入
            $array['code'] = 201;
            $array['info'] = '修改成功';
            echo json_encode($array, JSON_UNESCAPED_UNICODE);
        } else {
            $data = array();
            $data[0]['id'] = 0;
            $data[0]['name'] = $name;
            $data[0]['date'] = time();
            $data[0]['email'] = $email;
            $data[0]['content'] = $content;
            $json_string = json_encode($data);
            // 写入文件
            file_put_contents(dirname(__FILE__) . '/comments/' . $pid . '.json', $json_string);
            $array['code'] = 202;
            $array['info'] = '创建成功';
            echo json_encode($array, JSON_UNESCAPED_UNICODE);
        }
    } else {
        $array['code'] = 102;
        $array['info'] = '文章不存在';
        echo json_encode($array, JSON_UNESCAPED_UNICODE);
    }
} else {
    $array['code'] = 103;
    $array['info'] = '验证错误';
    echo json_encode($array, JSON_UNESCAPED_UNICODE);
}

/* 结束验证信息 */
