<?php require 'header.php'; ?>
<div class="archive-banner">
    <h2><?php echo $_GET['tag'] ?></h2>
    <p class="archive-banner-badge"># 文章标签</p>
    <em class="archive-banner-count">{{ tag_info.posts_count }} 篇内容</em>
</div>
<div class="blog-container" v-show="loaded" style="width:60%;margin-top:5vh">
    <el-card shadow="hover" v-for="(post,index) in posts" v-if="index <= display_count" class="stream-card-archive">
    <div class="archive-post-div">
    <div :style="'background-image: url('+post.info.Img+')'" class="archive-img" v-if="!!post.info.Img">
    </div>
    <div :style="post.info.Img ? 'width:65%' : 'width:100%'">    
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
</div>
</div>
    </el-card>
    <el-card shadow="hover" class="stream-card" v-loading="loading" v-show="loading">
    </el-card>
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
    $(document).ready(function() {
        $('#view').css('opacity', '1');
        new Vue({
            el: '#view',
            data() {
                return {
                    loading: 1,
                    posts: [],
                    nav_items: [],
                    tag_info: {
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
                axios.get('get_header.php')
                    .then(e => {
                        this.nav_items = e.data;
                    })
                    .then(() => {
                        axios.get('fetch_posts.php?pos=1&tags=<?php echo $_GET['tag'] ?>')
                            .then(e => {
                                this.posts = e.data.posts;
                                this.tag_info.posts_count = e.data.counts.posts_count;
                                axios.get('get_info.php?key=tags')
                                    .then(e => {
                                        this.tags = e.data;
                                        this.site_info.tags_count = e.data.counts.key_count;
                                        axios.get('get_info.php?key=cate')
                                            .then(e => {
                                                this.cates = e.data;
                                                this.site_info.cates_count = e.data.counts.key_count;
                                                this.loaded = 1;
                                                if (this.display_count >= this.tag_info.posts_count) {
                                                    this.loading = 0;
                                                }
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
            }
        })
    })
</script>
</body>

</html>