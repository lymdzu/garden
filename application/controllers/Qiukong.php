<?php
/**
 * 文件名称:qiukong.php
 * 摘    要:
 * 修改日期: 13/8/17
 */
class Qiukong extends PublicController
{
    public function __construct() {
        parent::__construct();
    }
    public function home()
    {
        $this->display("qiukong/home.html");
    }
    public function about()
    {
        $this->display("qiukong/about.html");
    }
}