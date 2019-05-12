<?php require 'header.php'; ?>

<el-collapse-transition>
    <div class="blog-container" v-show="loaded">
        <el-row :gutter="20">


            <el-col :span="6">
                <div class="side-blog-author">
                    <el-card shadow="hover">
                        <div class="side-author-banner"></div>
                        <div class="side-author-avatar"><img src="https://static.ouorz.com/tonyhe.jpg">
                            <div class="side-author-info">
                                <h2>TonyHe</h2>
                                <p>Just A Poor Lifesinger</p>
                                <em>已发布 {{ site_info.total_posts_count }} 篇内容</em>
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
                    <el-card shadow="hover" style="margin-top: 10px;">
                        <p class="side-contact-g">
                            <i class="czs-github-logo"></i>
                            HelipengTony
                        </p>
                    </el-card>
                    <el-card shadow="hover" style="margin-top: 10px;">
                        <p class="side-contact-we">
                            <i class="czs-weibo"></i>
                             小半阅读
                        </p>
                    </el-card>
                    <el-card shadow="hover" style="margin-top: 10px;">
                        <p class="side-contact-z">
                            <i class="czs-zhihu"></i>
                            helipengtony
                        </p>
                    </el-card>
                </div>
            </el-col>



            <el-col :span="12">
                <div>
                    <el-card shadow="hover" class="first-stream-card">
                        <h3>共包含 {{ site_info.posts_count }} 篇内容</h3>
                        <el-dropdown @command="handleDisplay">
                            <el-button>
                                加载方式<i class="el-icon-arrow-down el-icon--right"></i>
                            </el-button>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item command="3">按需加载</el-dropdown-item>
                                <el-dropdown-item command="2">全部加载</el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </el-card>

                    <el-card shadow="hover" v-for="(post,index) in posts" v-if="index <= display_count" class="stream-card">
                        <img :src="post.info.Img" v-if="!!post.info.Img && post.info.Img !== ':'">
                        <p class="stream-info">
                            <em>{{ post.info.Author ? post.info.Author : 'Whoever'}}</em>
                            <em>{{ post.info.Date ? post.info.Date : 'Whenever'}}</em>
                            <em>{{ post.info.Cate ? post.info.Cate : 'Wherever' }}</em>
                            <em style="color: rgb(136, 142, 148);background: rgb(231, 236, 240);" v-for="tag in post.info.Tags.split(',')">{{ tag }}</em>
                        </p>
                        <a :href="'posts.php?view=' + post.filename">
                            <h1 v-html="post.info.Title"></h1>
                            <div v-html="md.render(post.content.replace(/\n*/g,'') + '...')" class="stream-content"></div>
                        </a>
                        <a :href="'posts.php?view=' + post.filename" class="stream-view">浏览全文</a>
                    </el-card>
                    <el-card shadow="hover" class="stream-card" v-loading="loading" v-show="loading">
                    </el-card>
                </div>
            </el-col>


            <?php require 'sidebar.php'; ?>



        </el-row>

        <?php require 'footer.php'; ?>
    </div>
    <el-collapse-transition>
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
                        axios.get('get_header.php')
                            .then(e => {
                                this.nav_items = e.data;
                            })
                            .then(() => {
                                axios.get('get_posts.php?pos=1&exclude_type=cate&exclude_value=伙伴链接')
                                    .then(e => {
                                        this.posts = e.data.posts;
                                        this.site_info.posts_count = e.data.counts.posts_count;
                                        this.site_info.total_posts_count = e.data.counts.total_posts_count;
                                        axios.get('get_info.php?key=tags')
                                            .then(e => {
                                                this.tags = e.data;
                                                this.site_info.tags_count = e.data.counts.key_count;
                                                axios.get('get_info.php?key=cate')
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
                        new_page: function() { //加载下一页文章列表
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
                        }
                    }
                })

            });
        </script>
        </body>

        </html>