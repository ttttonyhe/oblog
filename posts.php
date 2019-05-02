<?php require 'header.php' ?>

<div style="
              width: 60%;
              margin: 10vh auto;
          " v-loading="loading">
    <el-card shadow="hover" style="
                padding: 20px 40px;
                margin-bottom:20px
            ">
        <p>
            <em>{{ post.info.Author }}</em>
            <em>{{ post.info.Date }}</em>
            <em>{{ post.info.Cate }}</em>
            <em style="margin-right:5px" v-for="tag in post.info.Tags.split(',')">{{ tag }}</em>
        </p>
        <h1 v-html="post.info.Title"></h1>
        <div v-html="md.render(post.content)"></div>
    </el-card>
</div>

</div>
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
                loading: 1,
                post: [],
                nav_items: []
            }
        },
        mounted() {
            axios.get('get_header.php')
                .then(e => {
                    this.nav_items = e.data;
                })
                .then(() => {
                    axios.get('get_posts.php?view=' + '<?php echo $_GET['view']; ?>')
                        .then(e => {
                            this.post = e.data;
                            this.loading = 0;
                        })
                })
        }
    })
</script>
</body>

</html>