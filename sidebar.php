<el-col :span="6">
                <div class="side-tags">
                    <el-card class="box-card" shadow="hover">
                        <div slot="header" class="clearfix">
                            <span class="side-title">文章标签</span>
                            <el-button style="float: right; padding: 3px 0" type="text">共 {{ site_info.tags_count }} 个</el-button>
                        </div>
                        <a :href="'tags.php?pos=1&tag='+tag" v-for="tag in tags.tags"><el-tag v-html="tag" class="text item"></el-tag></a>
                    </el-card>
                    <el-card class="box-card" style="margin-top:20px" shadow="hover">
                        <div slot="header" class="clearfix">
                            <span class="side-title">文章分类</span>
                            <el-button style="float: right; padding: 3px 0" type="text">共 {{ site_info.cates_count }} 个</el-button>
                        </div>
                        <a :href="'archives.php?pos=1&cate='+cate" v-for="cate in cates.cate"><el-tag type="success" v-html="cate" class="text item"></el-tag></a>
                    </el-card>
                </div>
            </el-col>