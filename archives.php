<?php require 'header.php'; ?>
    <div style="
              width: 75%;
              margin: 10vh auto;
          " v-show="loaded">
        <el-row :gutter="20">


            <el-col :span="6">
                <div class="side-blog-author">
                    <el-card shadow="hover">
                        <div class="side-author-avatar" style="margin: 70px 0;">
                            <div class="side-author-info">
                                <h2><?php echo $_GET['cate'] ?></h2>
                                <p> 文章分类</p>
                                <em>已发布 {{  cate_info.posts_count }} 篇内容</em>
                            </div>
                        </div>
                    </el-card>
                </div>
            </el-col>



            <el-col :span="12">
            <div>
                    <el-card shadow="hover" v-for="(post,index) in posts" v-if="index <= display_count" class="stream-card">
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
</transition>
<script>
    var md = window.markdownit({
        html: true,
        xhtmlOut: false,
        breaks: true,
        linkify: true
    });
    $(document).ready(function(){
    $('#view').css('opacity','1');
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
                    'tags_count' : 0
                },
                display_count: 5,
                tags: [],
                cates: [],
                loaded:0
            }
        },
        mounted() {
            axios.get('get_header.php')
                .then(e => {
                    this.nav_items = e.data;
                })
                .then(() => {
                    axios.get('fetch_posts.php?pos=1&cate=<?php echo $_GET['cate'] ?>')
                        .then(e => {
                            this.posts = e.data.posts;
                            this.cate_info.posts_count = e.data.counts.posts_count;
                            axios.get('get_info.php?key=tags')
                                .then(e => {
                                    this.tags = e.data;
                                    this.site_info.tags_count = e.data.counts.key_count;
                                    axios.get('get_info.php?key=cate')
                                        .then(e => {
                                            this.cates = e.data;
                                            this.site_info.cates_count = e.data.counts.key_count;
                                            this.loaded = 1;
                                            if(this.display_count >= this.cate_info.posts_count){
                                                this.loading = 0;
                                            }
                                        })
                                })
                        })
                })
        },
        methods : {
            new_page : function(){ //加载下一页文章列表
                this.display_count += 6;
                if(this.display_count >= this.site_info.posts_count){
                    this.loading = 0;
                }
            },
        }
    })
    })
</script>
</body>

</html>