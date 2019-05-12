<?php require 'func/header.php'; ?>
<div class="archive-banner">
    <h2><?php echo $_GET['cate'] ?></h2>
    <p class="archive-banner-badge"># 文章分类</p>
    <em class="archive-banner-count">{{ cate_info.posts_count }} 篇内容</em>
</div>
<div class="blog-container" v-show="loaded" style="width:60%;margin-top:5vh">

<template v-for="(post,index) in posts" v-if="post.info.Cate !== '伙伴链接'">
    <el-card shadow="hover" v-if="index <= display_count" class="stream-card-archive">
    <div class="archive-post-div">
    <div v-if="post.info.Img !== ':'" :style="'background-image: url('+post.info.Img+')'" class="archive-img">
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
    </template>

    <template v-else>
    <el-card shadow="hover" class="stream-card-archive-friend">
    <div class="archive-post-div">
            <img :src="post.info.Img" class="archive-f-img">
            <a :href="post.filename"><h1 v-html="post.info.Title" class="archive-f-h1"></h1></a>
            <p v-html="post.content" class="archive-f-p"></p>
        </el-card>
    </template>

    <el-card shadow="hover" class="stream-card" v-loading="loading" v-show="loading">
    </el-card>
    <?php require 'func/footer.php'; ?>
</div>
</transition>








<script src="js/archive.js" type="text/javascript"></script>
</body>

</html>