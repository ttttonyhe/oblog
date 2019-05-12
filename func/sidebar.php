<el-col :span="6">
    <div class="side-tags">
        <el-card class="box-card" shadow="hover">
            <div slot="header" class="clearfix">
                <span class="side-title">文章标签</span>
                <el-button style="float: right; padding: 3px 0" type="text">共 {{ site_info.tags_count }} 个</el-button>
            </div>
            <a :href="'tags.php?pos=1&tag='+tag" v-for="tag in tags.tags">
                <el-tag v-html="tag" class="text item"></el-tag>
            </a>
        </el-card>
        <el-card class="box-card" style="margin-top:20px" shadow="hover">
            <div slot="header" class="clearfix">
                <span class="side-title">文章分类</span>
                <el-button style="float: right; padding: 3px 0" type="text">共 {{ site_info.cates_count }} 个</el-button>
            </div>
            <a :href="'archives.php?pos=1&cate='+cate" v-for="cate in cates.cate">
                <el-tag type="success" v-html="cate" class="text item"></el-tag>
            </a>
        </el-card>
        <el-card class="box-card baijia-plan" style="margin-top:20px" shadow="hover">
            <div slot="header" class="clearfix">
                <span class="side-title">败家计划</span>
                <el-button style="float: right; padding: 3px 0" type="text">共值 0 元</el-button>
            </div>
            <h2 class="side-h2" style="margin-top:0px">Digital Products</h2>
            <p><i class="czs-mobile-l"></i> Meizu Pro7-H 128GB</p>
            <p><i class="czs-laptop-l"></i> MacBook Pro 2015 early 13-inch</p>
            <p><i class="czs-computer-l"></i> Acer Predator G3-605</p>
            <p><i class="czs-mobile-l"></i> iPad mini 4 64GB</p>
            <h2 class="side-h2">Accessories</h2>
            <p><i class="czs-headset-l"></i> Meizu Pop Headphone</p>
            <p><i class="czs-watch-l"></i> Huawei Watch 2</p>
            <p><i class="czs-computer-l"></i> Hi-Res Monitor x2</p>
        </el-card>
    </div>
</el-col>