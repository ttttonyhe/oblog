<?php
//获取 settings 中定义的导航栏内容
require '../settings.php';
echo json_encode($site->header);