<?php require 'header.php'; ?>

<transition name="el-fade-in-linear">
<div style="
              width: 75%;
              margin: 10vh auto;
          " v-show="loading">
    <el-row :gutter="20">


        <el-col :span="6">
            <div class="side-blog-author">
                <el-card shadow="hover">
                    <div class="side-author-banner"></div>
                    <div class="side-author-avatar"><img src="https://i.loli.net/2019/05/02/5ccaea93b09c2.jpg">
                        <div class="side-author-info">
                            <h2>TonyHe</h2>
                            <p>Just A Poor Lifesinger</p>
                            <em>已发布 {{ site_info.posts_count }} 篇内容</em>
                        </div>
                    </div>
                </el-card>
            </div>
        </el-col>



        <el-col :span="12">
            <div>
                <el-card shadow="hover" v-for="post in posts" class="stream-card">
                    <p class="stream-info">
                        <em>{{ post.info.Author }}</em>
                        <em>{{ post.info.Date }}</em>
                        <em>{{ post.info.Cate }}</em>
                        <em style="color: rgb(136, 142, 148);background: rgb(231, 236, 240);" v-for="tag in post.info.Tags.split(',')">{{ tag }}</em>
                    </p>
                    <h1 v-html="post.info.Title"></h1>
                    <div v-html="md.render(post.content.substr(0, 100) + '...')" class="stream-content"></div>
                    <a :href="'posts.php?view=' + post.filename" class="stream-view">浏览全文</a>
                </el-card>
            </div>
        </el-col>



        <el-col :span="6">
            <div>
                <el-card shadow="hover" v-for="post in posts" class="stream-card">
                    <p class="stream-info">
                        <em>{{ post.info.Author }}</em>
                        <em>{{ post.info.Date }}</em>
                        <em>{{ post.info.Cate }}</em>
                        <em v-for="tag in post.info.Tags.split(',')">{{ tag }}</em>
                    </p>
                    <h1 v-html="post.info.Title"></h1>
                    <div v-html="md.render( post.content.substr(0, 100) + '...' )"></div>
                    <a :href="'posts.php?view=' + post.filename">浏览全文</a>
                </el-card>
            </div>
        </el-col>



    </el-row>

</div>
</transition>
<script>
    var md = window.markdownit({
        html: true,
        xhtmlOut: false,
        breaks: true,
        linkify: true
    });
    new Vue({
        el: '#view',
        data() {
            return {
                loading: 0,
                posts: [],
                nav_items: [],
                site_info : {
                    'posts_count' : 0
                }
            }
        },
        mounted() {
            axios.get('get_header.php')
                .then(e => {
                    this.nav_items = e.data;
                })
                .then(() => {
                    axios.get('get_posts.php')
                        .then(e => {
                            this.posts = e.data.posts;
                            this.site_info.posts_count = e.data.counts.posts_count;
                            this.loading = 1;
                        })
                })
        }
    })
</script>
</body>

</html>