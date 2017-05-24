<?php

/**
 * 文件名称:Trouble.php
 * 摘    要:
 * 修改日期: 2017/5/24
 */
class Trouble extends PublicController {
    public function __construct() {
        parent::__construct();
        $this->vars['page'] = "trouble";
    }

    public function contact() {
        $this->page("trouble/contact.html");
    }

    public function develop() {
        $this->page("trouble/develop.html");
    }

    /**
     * 添加问题
     * @author liuyongming@shopex.cn
     */
    public function add() {
        $this->page("trouble/add.html");
    }
    public function upload()
    {
        $imglist = array(
            "data:image/jpeg" => ".jpg",
            "data:image/jpg" => ".jpg",
            "data:image/png" => ".png",
        );
        $file = $this->input->post("base64");
        list($meta_data, $pic_encode) = explode(";", $file);
        list($encode, $pic_base) = explode(",", $pic_encode);
        $pic = base64_decode($pic_base);
        $meta = substr($meta_data, 5);
        file_put_contents("test.jpg", $pic);
//        header("Content-type:" . $meta);
//        echo $pic;
    }
}