<?php
/**
 * 文件名称:Dashboard.php
 * 摘    要:
 * 修改日期: 2017/5/23
 */
class Dashboard extends PublicController
{
    public function index()
    {
//        $this->vars['footer'] = true;
//        $this->vars['fix_footer'] = true;
//        $this->page('dashboard/front.html');
        $this->display("jianli/jianli.html");
    }
}