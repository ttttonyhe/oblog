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
    <script src="jquery.goup.js"></script>
    <link type="text/css" rel="stylesheet" href="caomei/style.css">
</head>

<body>
    <div id="view" style="opacity:0;">


        <div class="navbar-fixed-top new-header-div" id="header">
            <div id="new-header" class="container new-header">
                <h3>TonyHe</h3>
                <p>
                    <a v-for="item in nav_items" v-html="item.name" :href="item.url">Home</a>
                </p>
            </div>
        </div>




        <script>
            $(window).scroll(function() {
                var to_top_header = $(document).scrollTop();
                if (to_top_header <= 0) {
                    $('#header').attr('class', 'navbar-fixed-top new-header-div');
                } else {
                    $('#header').attr('class', 'navbar-fixed-top new-header-div-scoll');
                }
            });
        </script>