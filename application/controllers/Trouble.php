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
        $this->vars['footer'] = true;
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
        $info['create_time'] = date("Y-m-d H:i", $info['create_time']);
        $this->vars['info'] = $info;
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
            $post_files = $this->input->post("files");
            $files = array_reverse($post_files);
            $desc = $this->input->post("desc", true);
            $title = $this->input->post("title", true);
            $claim = $this->input->post("claim", true);
            if (empty($title)) {
                $this->result(false, "请给反馈的问题精简出一个标题");
            }
            if (empty($desc)) {
                $this->result(false, "请描述一下你所发现的问题");
            }
            $this->load->model("TroubleModel", "trouble", true);
            $insert_res = $this->trouble->insert_trouble($title, $claim, serialize($files), $desc);
            if ($insert_res == 1) {
                $this->result(true, "问题提交成功");
            } else {
                $this->result(false, "添加失败");
            }
        } else {
            $this->vars['no'] = "'" . implode("','", range(86, 126)) . "'" ;
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
        $object = "{$openid}/" . $openid . time() . mt_rand(0000, 9999) . $imglist[$meta_data];
        $options = array(OssClient::OSS_HEADERS => array());
        try {
            $ossClient->putObject(OSS_BUCKET, $object, $pic, $options);
        } catch (OssException $e) {
            return false;
        }
        unset($pic);
        header("Content-type:application/json");
        echo json_encode(array("src" => OSS_PIC_URL . $object));
    }
}