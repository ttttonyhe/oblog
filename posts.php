<?php require 'func/header.php' ?>
<link rel="stylesheet" href="md.css">
<script src="https://cdn.bootcss.com/blueimp-md5/2.10.0/js/md5.min.js"></script>
<div class="reading-bar"></div>
<el-collapse-transition>
    <div class="blog-container" v-show="loading" style="width:67%">

        <el-row :gutter="20">
            <el-col :span="7">

            <?php require 'func/single-sidebar.php'; ?>

                <el-card shadow="hover" class="index-div">
                    <div style="padding:0px 25px">
                        <h2 style="font-weight: 600;margin: 0px;text-align:center">文章引索</h4>
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
                        <em>{{ author }}</em>
                        <em>{{ date }}</em>
                        <em>{{ '归类在: ' + cate }}</em>
                        <em style="margin-right:5px" v-for="tag in tags.split(',')">{{ tag }}</em>
                    </p>
                    <div v-html="content" class="post-content markdown-body" id="content"></div>

                    <?php require 'func/comments.php'; ?>

                </el-card>



            </el-col>
        </el-row>
    </div>

    </div>
</el-collapse-transition>
<?php require 'func/footer.php'; ?>
<script src="js/posts.js" type="text/javascript"></script>
</body>

</html>