<?php require 'header.php' ?>
<link rel="stylesheet" href="md.css">
<script src="https://cdn.bootcss.com/blueimp-md5/2.10.0/js/md5.min.js"></script>
<div class="reading-bar"></div>
<el-collapse-transition>
    <div class="blog-container" v-show="loading" style="width:67%">

        <el-row :gutter="20">
            <el-col :span="7">

                <?php require 'single-sidebar.php'; ?>

                <el-card shadow="hover" class="index-div">
                    <div style="padding:0px 25px">
                        <h2 style="font-weight: 600;margin: 0px;text-align:center">页面引索</h4>
                    </div>
                    <ul id="article-index" class="index-ul">
                    </ul>
                </el-card>



            </el-col>
            <el-col :span="17">


                <el-card shadow="never" style="
                padding: 20px 40px;
                margin-bottom:20px
            ">
                    <h1 v-html="title" class="post-h1"></h1>
                    <p class="post-p">
                        <em>页面</em>
                        <em>{{ date }}</em>
                    </p>
                    <div v-html="content" class="post-content markdown-body" id="content"></div>

                    <?php require 'comments.php'; ?>

                </el-card>



            </el-col>
        </el-row>
    </div>

    </div>
</el-collapse-transition>
<script>
    let cookie = {
    "set": function setCookie(name, value) {
        var Days = 30;
        var exp = new Date();
        exp.setTime(exp.getTime() + Days * 24 * 60 * 60 * 1000);
        document.cookie = name + "=" + escape(value) + ";expires=" + exp.toGMTString();
    },
    "get": function getCookie(name) {
        var arr, reg = new RegExp("(^| )" + name + "=([^;]*)(;|$)");
        if (arr = document.cookie.match(reg))
            return unescape(arr[2]);
        else
            return null;
    },
    "del": function delCookie(name) {
        var exp = new Date();
        exp.setTime(exp.getTime() - 1);
        var cval = cookie.get(name);
        if (cval != null)
            document.cookie = name + "=" + cval + ";expires=" + exp.toGMTString();
    }
}

    function time(time) {
        var newDate = new Date();
        newDate.setTime(time * 1000);
        return newDate.toLocaleString();
    }

    var md = window.markdownit({
        html: true,
        xhtmlOut: false,
        breaks: true,
        linkify: true
    });
    $(document).ready(function() {
        $('#view').css('opacity', '1');

        new Vue({
            el: '#view',
            data() {
                return {
                    loading: 0,
                    post: [],
                    nav_items: [],
                    title: null,
                    date: null,
                    content: ' ',
                    comments: null,
                    comment: {
                        comment_content: null,
                        comment_name: cookie.get('oblog_comment_name'),
                        comment_email: cookie.get('oblog_comment_email'),
                        comment_reply: null
                    }
                }
            },
            mounted() {
                axios.get('get_header.php')
                    .then(e => {
                        this.nav_items = e.data;
                    })
                axios.get('get_pages.php?view=' + '<?php echo urldecode($_GET['view']); ?>')
                    .then(e => {
                        this.post = e.data;
                        this.title = this.post.info.Title;
                        this.date = this.post.info.Date;
                        this.content = md.render(this.post.content);
                        this.loading = 1;

                        //获取评论内容列表
                        axios.get('comments/page-<?php echo urldecode($_GET['view']) ?>.json')
                            .then(e => {
                                this.comments = e.data;
                            })


                        $('#content').ready(function() {

                            //文章阅读进度条
                            var content_offtop = $('#content').offset().top;
                            var content_height = $('#content').innerHeight();
                            $(window).scroll(function() {
                                if (($(this).scrollTop() > content_offtop)) { //滑动到内容部分
                                    if (($(this).scrollTop() - content_offtop) <= content_height) { //在内容部分内滑动
                                        this.reading_p = Math.round(($(this).scrollTop() - content_offtop) / content_height * 100);
                                    } else { //滑出内容部分
                                        this.reading_p = 100;
                                    }
                                } else { //未滑到内容部分
                                    this.reading_p = 0;
                                }
                                $('.reading-bar').css('width', this.reading_p + '%');
                            });

                            /* 文章目录 */
                            var h = 0;
                            var pf = 0;
                            var i = 0;
                            $('#article-index').html('');
                            var count_ti = count_in = count_ar = count_sc = count_hr = count_e = 1;
                            var offset = new Array;
                            var min = 0;
                            var c = 0;
                            var icon = '';

                            //获取最高级别h标签
                            $("#content>:header").each(function() {
                                h = $(this).eq(0).prop("tagName").replace('H', '');
                                if (c == 0) {
                                    min = h;
                                    c++;
                                } else {
                                    if (h <= min) {
                                        min = h;
                                    }
                                }
                            });

                            //获取h标签内容
                            $("#content>:header").each(function() {
                                h = $(this).eq(0).prop("tagName").replace('H', ''); //标签级别
                                for (i = 0; i < Math.abs(h - min); ++i) { //偏移程度
                                    pf += 10;
                                }
                                if (pf !== 0) { //图标
                                    icon = 'czs-square-l';
                                } else {
                                    icon = 'czs-circle-l';
                                }

                                $('#article-index').html($('#article-index').html() + '<li id="ti' + (count_ti++) +
                                    '" style="padding-left:' + pf + 'px"><a><i class="' + icon + '"></i>&nbsp;&nbsp;' + $(this).eq(
                                        0).text().replace(/[ ]/g, "") + '</a></li>'); //创建目录
                                $(this).eq(0).attr('id', 'in' + (count_in++)); //添加id
                                offset[0] = 0;
                                offset[count_ar++] = $(this).eq(0).offset().top; //位置存入数组
                                count_e++;
                                pf = 0; //设置初始偏移值
                                i = 0; //设置循环开始
                            })

                            //跳转对应位置事件
                            $('#article-index li').click(function() {
                                $('html,body').animate({
                                    scrollTop: ($('#in' + $(this).eq(0).attr('id').replace('ti', '')).offset().top - 100)
                                }, 500);
                            });

                            if (count_e !== 1) { //若存在h3标签

                                $(window).scroll(function() { //滑动窗口时
                                    var scroH = $(this).scrollTop() + 130;
                                    var navH = offset[count_sc]; //从1开始获取当前h3位置
                                    var navH_prev = offset[count_sc - 1]; //获取上一个h3位置(以备回滑)
                                    if (scroH >= navH) { //滑过当前h3位置
                                        $('#ti' + (count_sc - 1)).attr('class', '');
                                        $('#ti' + count_sc).attr('class', 'active');
                                        count_sc++; //调至下一个h3位置
                                    }
                                    if (scroH <= navH_prev) { //滑回上一个h3位置,调至上一个h3位置
                                        $('#ti' + (count_sc - 2)).attr('class', 'active');
                                        count_sc--;
                                        $('#ti' + count_sc).attr('class', '');
                                    }
                                });

                            } else {
                                $('.index-div').css('display', 'none');
                                this.exist_index = false;
                            }
                            /* 文章目录 */
                        })


                        var navH = 600;
        $(window).scroll(function(){
            var scroH = $(this).scrollTop();
            if(scroH >= navH){
                $('.index-div').attr('class','index-div-scroll');
                $('.index-div-scroll').css('opacity','1');
            }else{
                $('.index-div-scroll').css('opacity','0');
            }
        });

                    })
            },
            methods: {
                send_comment: function(key) {
                    if (!this.comment.comment_name || !this.comment.comment_email || !this.comment.comment_content) {
                        this.$message({
                            showClose: true,
                            message: '信息不全',
                            type: 'error'
                        });
                    } else {
                        if (!this.comment.comment_reply) {
                            var params = new URLSearchParams();
                            params.append('name', this.comment.comment_name);
                            params.append('email', this.comment.comment_email);
                            params.append('content', this.comment.comment_content);
                            params.append('pid', 'page-<?php echo $_GET['view'] ?>');
                            params.append('ver', 'comment_ver');
                            axios.post('save_comments.php', params)
                                .then(response => {
                                    this.comment.comment_content = null;
                                    this.$message({
                                        showClose: true,
                                        message: '提交成功',
                                        type: 'success'
                                    });
                                    axios.get('comments/page-<?php echo urldecode($_GET['view']) ?>.json?nocache=' + (new Date()).getTime())
                                        .then(e => {
                                            this.comments = e.data;
                                        })
                                    cookie.set('oblog_comment_name',this.comment.comment_name);
                                    cookie.set('oblog_comment_email',this.comment.comment_email);
                                });
                        } else {
                            var params = new URLSearchParams();
                            params.append('name', this.comment.comment_name);
                            params.append('email', this.comment.comment_email);
                            params.append('content', this.comment.comment_content);
                            params.append('pid', 'page-<?php echo $_GET['view'] ?>');
                            params.append('ver', 'comment_ver');
                            params.append('reply', this.comment.comment_reply - 1);
                            axios.post('save_comments.php', params)
                                .then(response => {
                                    this.comment.comment_content = null;
                                    this.$message({
                                        showClose: true,
                                        message: '提交成功',
                                        type: 'success'
                                    });
                                    axios.get('comments/pages-<?php echo urldecode($_GET['view']) ?>.json?nocache=' + (new Date()).getTime())
                                        .then(e => {
                                            this.comments = e.data;
                                        })
                                })
                        }
                    }
                },
                reply: function(key) {
                    if (key !== 'c') {
                        this.comment.comment_reply = key;
                    } else {
                        this.comment.comment_reply = null;
                    }
                }
            }
        })
    })
</script>
</body>

</html>