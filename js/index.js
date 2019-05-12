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
                loaded: 0,
                posts: [],
                nav_items: [],
                site_info: {
                    'posts_count': 0,
                    'cates_count': 0,
                    'tags_count': 0,
                    'total_posts_count': 0
                },
                display_count: 3,
                tags: [],
                cates: []
            }
        },
        mounted() {
            axios.get('api/get_header.php')
                .then(e => {
                    this.nav_items = e.data;
                })
                .then(() => {
                    axios.get('api/get_posts.php?pos=1' + index_get_option)
                        .then(e => {
                            this.posts = e.data.posts;
                            this.site_info.posts_count = e.data.counts.posts_count;
                            this.site_info.total_posts_count = e.data.counts.total_posts_count;
                            axios.get('api/get_info.php?key=tags')
                                .then(e => {
                                    this.tags = e.data;
                                    this.site_info.tags_count = e.data.counts.key_count;
                                    axios.get('api/get_info.php?key=cate')
                                        .then(e => {
                                            this.cates = e.data;
                                            this.site_info.cates_count = e.data.counts.key_count;
                                            this.loaded = 1;
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
            handleDisplay(command) {
                if (command == 2) {
                    this.display_count = this.site_info.posts_count + 1;
                    this.loading = 0;
                    this.$message({
                        message: '已加载全部文章',
                        type: 'success'
                    });
                } else {
                    this.display_count = 3;
                    this.loading = 1;
                    this.$message({
                        message: '只加载部分文章',
                        type: 'success'
                    });
                }
            },
            handleDisplay_content(command) {
                if (command == 2) {
                    axios.get('api/get_pages.php?pos=1')
                        .then(c => {
                            this.posts = c.data.pages;
                            this.site_info.posts_count = this.site_info.total_posts_count = c.data.counts.total_posts_count;
                            this.display_count = c.data.counts.total_posts_count + 1;
                        })
                    this.loading = 0;
                    this.$message({
                        message: '已加载页面内容',
                        type: 'success'
                    });
                } else {
                    axios.get('api/get_posts.php?pos=1&exclude_type=cate&exclude_value=伙伴链接')
                        .then(e => {
                            this.posts = e.data.posts;
                            this.site_info.posts_count = e.data.counts.posts_count;
                            this.site_info.total_posts_count = e.data.counts.total_posts_count;
                        })
                    this.$message({
                        message: '已加载文章内容',
                        type: 'success'
                    });
                }
            }
        }
    })

});