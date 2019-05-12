<div class="side-blog-author">
    <el-card shadow="hover">
        <div class="side-author-banner" style="background-image:url(<?php echo $site->banner; ?>)"></div>
        <div class="side-author-avatar"><img src="<?php echo $site->avatar; ?>">
            <div class="side-author-info">
                <h2><?php echo $site->name; ?></h2>
                <p><?php echo $site->des; ?></p>
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