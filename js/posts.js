$.extend({
    getUrlVars: function () {
        var vars = [],
            hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for (var i = 0; i < hashes.length; i++) {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlVar: function (name) {
        return $.getUrlVars()[name];
    }
});
//获取 view 值 id
var view_id = decodeURIComponent($.getUrlVar('view'));

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
$(document).ready(function () {
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
            axios.get('api/get_header.php')
                .then(e => {
                    this.nav_items = e.data;
                })
            axios.get('api/get_posts.php?view=' + view_id)
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

                    axios.get('comments/' + view_id + '.json?nocache=' + (new Date()).getTime())
                        .then(e => {
                            this.comments = e.data;
                        })


                    $('#content').ready(function () {

                        //文章阅读进度条
                        var content_offtop = $('#content').offset().top;
                        var content_height = $('#content').innerHeight();
                        $(window).scroll(function () {
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
                        $("#content>:header").each(function () {
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
                        $("#content>:header").each(function () {
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
                        $('#article-index li').click(function () {
                            $('html,body').animate({
                                scrollTop: ($('#in' + $(this).eq(0).attr('id').replace('ti', '')).offset().top - 100)
                            }, 500);
                        });

                        if (count_e !== 1) { //若存在h3标签

                            $(window).scroll(function () { //滑动窗口时
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
                    $(window).scroll(function () {
                        var scroH = $(this).scrollTop();
                        if (scroH >= navH) {
                            $('.index-div').attr('class', 'index-div-scroll');
                            $('.index-div-scroll').css('opacity', '1');
                        } else {
                            $('.index-div-scroll').css('opacity', '0');
                        }
                    });

                })
        },
        methods: {
            send_comment: function (key) {
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
                        params.append('pid', view_id);
                        params.append('ver', 'comment_ver');
                        axios.post('api/save_comments.php', params)
                            .then(response => {
                                this.comment.comment_content = null;
                                this.$message({
                                    showClose: true,
                                    message: '提交成功',
                                    type: 'success'
                                });
                                axios.get('comments/' + view_id + '.json?nocache=' + (new Date()).getTime())
                                    .then(e => {
                                        this.comments = e.data;
                                    })
                                cookie.set('oblog_comment_name', this.comment.comment_name);
                                cookie.set('oblog_comment_email', this.comment.comment_email);
                            });
                    } else {
                        var params = new URLSearchParams();
                        params.append('name', this.comment.comment_name);
                        params.append('email', this.comment.comment_email);
                        params.append('content', this.comment.comment_content);
                        params.append('pid', view_id);
                        params.append('ver', 'comment_ver');
                        params.append('reply', this.comment.comment_reply - 1);
                        axios.post('api/save_comments.php', params)
                            .then(response => {
                                this.comment.comment_content = null;
                                this.$message({
                                    showClose: true,
                                    message: '提交成功',
                                    type: 'success'
                                });
                                axios.get('comments/' + view_id + '.json?nocache=' + (new Date()).getTime())
                                    .then(e => {
                                        this.comments = e.data;
                                    })
                            })
                    }
                }
            },
            reply: function (key) {
                if (key !== 'c') {
                    this.comment.comment_reply = key;
                } else {
                    this.comment.comment_reply = null;
                }
            }
        }
    })
})