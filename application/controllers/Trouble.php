<?php
/**
 * 文件名称:Trouble.php
 * 摘    要:
 * 修改日期: 2017/5/24
 */
class Trouble extends PublicController
{
    public function contact()
    {
        $this->page("trouble/contact.html");
    }


    public function develop()
    {
        $this->page("trouble/develop.html");
    }
}