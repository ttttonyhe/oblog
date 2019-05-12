<?php require 'func/header.php'; ?>

<el-collapse-transition>
    <div class="blog-container" v-show="loaded">
        <el-row :gutter="20">


            <el-col :span="6">
                <div class="side-blog-author">
                    <el-card shadow="hover">
                        <div class="side-author-banner" style="background-image:url(<?php echo $site->banner; ?>)"></div>
                        <div class="side-author-avatar"><img src="<?php echo $site->avatar; ?>">
                            <div class="side-author-info">
                                <h2><?php echo $site->name; ?></h2>
                                <p><?php echo $site->des; ?></p>
                                <em>已发布 {{ site_info.total_posts_count }} 篇内容</em>
                            </div>
                        </div>
                    </el-card>
                    <?php
                    //联系方式选项
                    for ($i = 0; $i < count($site->con); ++$i) { ?>
                        <el-card shadow="hover" style="margin-top: 10px;">
                            <p class="<?php echo $site->con[$i][0] ?>">
                                <i class="<?php echo $site->con[$i][1] ?>"></i>
                                <?php echo $site->con[$i][2] ?>
                            </p>
                        </el-card>
                    <?php } ?>
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
                        <el-dropdown @command="handleDisplay_content" style="margin-right:10px">
                            <el-button>
                                加载内容<i class="el-icon-arrow-down el-icon--right"></i>
                            </el-button>
                            <el-dropdown-menu slot="dropdown">
                                <el-dropdown-item command="1">文章内容</el-dropdown-item>
                                <el-dropdown-item command="2">页面内容</el-dropdown-item>
                            </el-dropdown-menu>
                        </el-dropdown>
                    </el-card>

                    <el-card shadow="hover" class="stream-card" v-for="(post,index) in posts" v-if="index <= display_count">

                        <template v-if="!post.info.Type">
                            <img :src="post.info.Img" v-if="!!post.info.Img && post.info.Img !== ':'">
                            <p class="stream-info">
                                <em>{{ post.info.Author ? post.info.Author : 'Whoever'}}</em>
                                <em>{{ post.info.Date ? post.info.Date : 'Whenever'}}</em>
                                <em>{{ post.info.Cate ? post.info.Cate : 'Wherever' }}</em>
                                <em style="color: rgb(136, 142, 148);background: rgb(231, 236, 240);" v-for="tag in post.info.Tags.split(',')">{{ tag }}</em>
                            </p>
                        </template>

                        <template v-if="!post.info.Type">
                            <a :href="'posts.php?view=' + post.filename">
                                <h1 v-html="post.info.Title"></h1>
                                <div v-html="md.render(post.content.replace(/\n*/g,'') + '...')" class="stream-content"></div>
                            </a>
                            <a :href="'posts.php?view=' + post.filename" class="stream-view">浏览全文</a>
                        </template>

                        <template v-else>
                            <a :href="'pages.php?view=' + post.filename">
                                <h1 v-html="post.info.Title"></h1>
                                <div v-html="md.render(post.content.replace(/\n*/g,'') + '...')" class="stream-content"></div>
                            </a>
                            <a :href="'pages.php?view=' + post.filename" class="stream-view">浏览页面</a>
                        </template>

                    </el-card>


                    <el-card shadow="hover" class="stream-card" v-loading="loading" v-show="loading">
                    </el-card>
                </div>
            </el-col>


            <?php require 'func/sidebar.php'; ?>



        </el-row>

        <?php require 'func/footer.php'; ?>
    </div>
    <el-collapse-transition>

        <script>
            //首页排除文章选项
            <?php if (!!$site->index_exclude) { ?>
                var index_get_option = '&exclude_type=cate&exclude_value=<?php echo $site->index_exclude ?>';
            <?php } else { ?>
                var index_get_option = '';
            <?php } ?>
        </script>

        <script src="js/index.js" type="text/javascript"></script>
        </body>

        </html>