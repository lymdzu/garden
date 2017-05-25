<?php

/**
 * 文件名称:Trouble.php
 * 摘    要:
 * 修改日期: 2017/5/24
 */
require_once APPPATH . 'vendor/autoload.php';
use OSS\OssClient;
use OSS\Core\OssException;

class Trouble extends PublicController
{
    public function __construct()
    {
        parent::__construct();
        $this->vars['page'] = "trouble";
        $openid = $this->session->userdata("openid");
        if (empty($openid)) {
            //$this->wx_oauth(true);
        }
    }

    /**
     * 问题列表
     * @author liuyongming@shopex.cn
     */
    public function trouble_list()
    {
        $this->load->model("TroubleModel", "trouble", true);
        $list = $this->trouble->get_trouble_list();
        foreach ($list as $key => $item) {
            $list[$key]['files'] = unserialize($item['files']);
            $list[$key]['desc'] = mb_substr($item['desc'], 0, 15) . "...";
            $list[$key]['create_time'] = date("Y-m-d H:i:s", $item['create_time']);
        }
        $this->vars['list'] = $list;
        $this->page("trouble/list.html");
    }

    /**
     * 问题详情展示
     * @author liuyongming@shopex.cn
     */
    public function show()
    {
        $trouble_id = $this->input->get("id", true);
        $this->load->model("TroubleModel", "trouble", true);
        $info = $this->trouble->get_trouble_info($trouble_id);
        $info['files'] = unserialize($info['files']);
        $info['create_time'] = date("Y-m-d H:i:s", $info['create_time']);
        $this->vars['info'] = $info;
        $this->vars['footer'] = true;
        $this->page("trouble/show.html");
    }

    /**
     * 微信oauth
     * @param bool|false $oauth
     * @author liuyongming@shopex.cn
     */
    public function wx_oauth($oauth = false)
    {
        $openid = $this->session->userdata("openid");
        if (empty($openid)) {
            //通过code获得openid
            if (!isset($_GET['code'])) {
                //触发微信返回code码
                $baseUrl = urlencode($this->config->site_url('trouble/wx_oauth'));
                $url = $this->__CreateOauthUrlForCode($baseUrl);
                redirect($url, 302);
            }
            else {
                //获取code码，以获取openid
                $code = $_GET['code'];
                $data = $this->GetOpenidFromMp($code);
                log_message("info", "['oauth result']" . json_encode($data));
            }
        }
    }

    /**
     * 联系物业
     * @author liuyongming@shopex.cn
     */
    public function contact()
    {
        $this->vars['footer'] = true;
        $this->vars['fix_footer'] = true;
        $this->page("trouble/contact.html");
    }

    /**
     * 联系开发
     * @author liuyongming@shopex.cn
     */
    public function develop()
    {
        $this->page("trouble/develop.html");
    }

    /**
     * 添加问题
     * @author liuyongming@shopex.cn
     */
    public function add()
    {
        if ($this->input->method() == "post") {
            $files = $this->input->post("files");
            $desc = $this->input->post("desc", true);
            if (empty($desc)) {
                $this->result(false, "请描述一下你所发现的问题");
            }
            $this->load->model("TroubleModel", "trouble", true);
            $insert_res = $this->trouble->insert_trouble(serialize($files), $desc);
            if ($insert_res == 1) {
                $this->result(true, "添加成功");
            }
            else {
                $this->result(false, "添加失败");
            }
        }
        else {
            $this->page("trouble/add.html");
        }
    }

    /**
     * 上传图片至阿里云oss
     * @return bool
     * @author liuyongming@shopex.cn
     */
    public function upload()
    {
        $imglist = array(
            "data:image/jpeg" => ".jpg",
            "data:image/jpg"  => ".jpg",
            "data:image/png"  => ".png",
        );
        $file = $this->input->post("base64");
        list($meta_data, $pic_encode) = explode(";", $file);
        list($encode, $pic_base) = explode(",", $pic_encode);
        $pic = base64_decode($pic_base);
        $ossClient = new OssClient(OSS_ACCESS_KEY, OSS_SECRET_KEY, OSS_ENDPOINT);
        $openid = "accdddddd";
        $object = "{$openid}/" . $openid . time() . $imglist[$meta_data];
        $options = array(OssClient::OSS_HEADERS => array());
        try {
            $ossClient->putObject(OSS_BUCKET, $object, $pic, $options);
        } catch (OssException $e) {
            return false;
        }
        header("Content-type:application/json");
        echo json_encode(array("src" => OSS_PIC_URL . $object));
    }
}