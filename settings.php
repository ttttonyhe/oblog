<?php 

class site_info{
    //站点名
    public $name = 'TonyHe';
    //站点描述
    public $des = 'Just A Poor Lifesinger';
    //站点头像
    public $avatar = 'https://static.ouorz.com/tonyhe.jpg';
    //站点头像背景
    public $banner = 'https://static.ouorz.com/5ccaea1daaf36.jpg';
    //站点联系方式
    //'class 名','icon 名','内容'
    public $con = array(
        array(
            'side-contact-w','czs-weixin','Helipeng_tony'
        ),
        array(
            'side-contact-e','czs-message-l','he@holptech.com'
        ),
        array(
            'side-contact-q','czs-qq','36624065'
        ),
        array(
            'side-contact-g','czs-github-logo','HelipengTony'
        ),
        array(
            'side-contact-we','czs-weibo',' 小半阅读'
        ),
        array(
            'side-contact-z','czs-zhihu','helipengtony'
        )
    );
    //首页排除分类选项(单个)
    //'cate 名称'
    public $index_exclude = '伙伴链接';
    //站点导航栏
    //'名称','链接'
    public $header = array(
        array(
            '首页','http://localhost/oblog'
        ),
        array(
            '博客','https://www.ouorz.com'
        ),
        array(
            '伙伴','http://localhost/oblog/archives.php?cate=伙伴链接'
        ),
        array(
            '关于','https://www.ouorz.com/126'
        )
    );
    //站点导航栏按钮
    //'按钮文字',array'内容'
    public $header_btn = array(
        'Buy me a coffee',
        array(
            '<i class="czs-alipay"></i> 13408697095',
            '<i class="czs-weixinzhifu"></i> Helipeng_tony'
        )
    );
}

$site = new site_info();
