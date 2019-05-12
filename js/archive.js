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
var view_id = decodeURIComponent($.getUrlVar('cate'));

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
                loading: 1,
                posts: [],
                nav_items: [],
                cate_info: {
                    'posts_count': 0
                },
                site_info: {
                    'cates_count': 0,
                    'tags_count': 0
                },
                display_count: 5,
                tags: [],
                cates: [],
                loaded: 0
            }
        },
        mounted() {
            axios.get('api/get_header.php')
                .then(e => {
                    this.nav_items = e.data;
                })
                .then(() => {
                    axios.get('api/fetch_posts.php?pos=1&cate=' + view_id)
                        .then(e => {
                            this.posts = e.data.posts;
                            this.cate_info.posts_count = e.data.counts.posts_count;
                            axios.get('api/get_info.php?key=tags')
                                .then(e => {
                                    this.tags = e.data;
                                    this.site_info.tags_count = e.data.counts.key_count;
                                    axios.get('api/get_info.php?key=cate')
                                        .then(e => {
                                            this.cates = e.data;
                                            this.site_info.cates_count = e.data.counts.key_count;
                                            this.loaded = 1;
                                            if (this.display_count >= this.cate_info.posts_count) {
                                                this.loading = 0;
                                            }
                                        })
                                })
                        })
                })
        },
        methods: {
            new_page: function () { //加载下一页文章列表
                this.display_count += 6;
                if (this.display_count >= this.site_info.posts_count) {
                    this.loading = 0;
                }
            },
        }
    })
})