<!DOCTYPE html>
<html lang="zh-cn">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OBlog</title>
    <script src="https://static.ouorz.com/jquery-3.3.1.min.js"></script>
    <script type="text/javascript" src="https://static.ouorz.com/vue.min.js"></script>
    <script>
        Vue.config.devtools = true
    </script>
    <script type="text/javascript" src="https://static.ouorz.com/axios.min.js"></script>
    <script src="https://cdn.bootcss.com/markdown-it/8.4.2/markdown-it.min.js"></script>
    <script type="text/javascript" src="element/index.js"></script>
    <link rel="stylesheet" type="text/css" href="element/index.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    </link>
</head>

<body>
    <div id="view">
        <el-menu class="el-menu-demo" mode="horizontal">
            <el-menu-item>
                <a href="http://localhost/oblog">
                    <img style="width:120px;margin-top:-5px"
                        src="https://demo.ouorz.com/build/static/eugrade_logo_with_text@3x.png">
                </a>
            </el-menu-item>
            <el-menu-item v-for="item in nav_items">
                <a :href="item.url">{{ item.name }}</a>
            </el-menu-item>
            <el-menu-item style="float:right">
                <a href="https://www.ouorz.com">
                    <el-button type="primary" style="margin-top: -5px;">About Me</el-button>
                </a>
            </el-menu-item>
        </el-menu>