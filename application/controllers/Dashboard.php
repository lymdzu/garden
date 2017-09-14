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
        ob_end_clean();
        $pdf = file_get_contents(dirname(BASEPATH) . "/public/resume/resume_and_works_of_raoxiaohua0914.pdf");
        header('Content-Type: application/pdf');
        echo $pdf;
        //$this->display("jianli/jianli.html");
    }
}