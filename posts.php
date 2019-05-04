<?php require 'header.php' ?>
<link rel="stylesheet" href="md.css">
<div class="reading-bar"></div>
<el-collapse-transition>
    <div class="blog-container" v-show="loading">

        <el-row :gutter="20">
            <el-col :span="6">


                <div class="side-blog-author">
                    <el-card shadow="hover">
                        <div class="side-author-banner"></div>
                        <div class="side-author-avatar"><img src="https://static.ouorz.com/tonyhe.jpg">
                            <div class="side-author-info">
                                <h2>TonyHe</h2>
                                <p>Just A Poor Lifesinger</p>
                            </div>
                        </div>
                    </el-card>
                    <el-card shadow="hover" style="margin-top: 10px;">
                        <p class="side-contact-w">
                            <i class="czs-weixin"></i>
                            Helipeng_tony
                        </p>
                    </el-card>
                    <el-card shadow="hover" style="margin-top: 10px;">
                        <p class="side-contact-e">
                            <i class="czs-message-l"></i>
                            he@holptech.com
                        </p>
                    </el-card>
                    <el-card shadow="hover" style="margin-top: 10px;">
                        <p class="side-contact-q">
                            <i class="czs-qq"></i>
                            36624065
                        </p>
                    </el-card>
                </div>

                <el-card shadow="hover" class="index-div">
                    <div style="padding:0px 25px">
                        <h2 style="font-weight: 600;margin: 0px;text-align:center">文章引索</h4>
                    </div>
                    <ul id="article-index" class="index-ul">
                    </ul>
                </el-card>



            </el-col>
            <el-col :span="16">


                <el-card shadow="hover" style="
                padding: 20px 40px;
                margin-bottom:20px
            ">
                    <h1 v-html="title" class="post-h1"></h1>
                    <p class="post-p">
                        <em>{{ author }}</em>
                        <em>{{ date }}</em>
                        <em>{{ '归类在: ' + cate }}</em>
                        <em style="margin-right:5px" v-for="tag in tags.split(',')">{{ tag }}</em>
                    </p>
                    <div v-html="content" class="post-content markdown-body" id="content"></div>
                </el-card>



            </el-col>
        </el-row>
    </div>

    </div>
</el-collapse-transition>
<script>
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
                    author: null,
                    cate: null,
                    date: null,
                    img: null,
                    tags: '',
                    content: ' '
                }
            },
            mounted() {
                axios.get('get_header.php')
                    .then(e => {
                        this.nav_items = e.data;
                    })
                axios.get('get_posts.php?view=' + '<?php echo $_GET['view']; ?>')
                    .then(e => {
                        this.post = e.data;
                        this.title = this.post.info.Title;
                        this.img = this.post.info.Img;
                        this.date = this.post.info.Date;
                        this.cate = this.post.info.Cate;
                        this.tags = this.post.info.Tags;
                        this.author = this.post.info.Author;
                        this.content = md.render(this.post.content);
                        this.loading = 1;



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




                    })
            }
        })
    })
</script>
</body>

</html>