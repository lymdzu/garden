<?php
/**
 * 文件名称:Vote.php
 * 摘    要:
 * 修改日期: 2017/5/27
 */
class Vote extends PublicController
{
    public function __construct()
    {
        parent::__construct();
        $this->vars['page'] = "vote";
    }

    public function join()
    {
        if($this->input->method() == "post")
        {
            $post_files = $this->input->post("files");
            $files = array_reverse($post_files);
            $slogan = $this->input->post("slogan", true);
            $name = $this->input->post("name", true);
            $unit = $this->input->post("unit", true);
            if (empty($slogan)) {
                $this->result(false, "请填写您的竞选宣言");
            }
            if (empty($name)) {
                $this->result(false, "请填写您的姓名");
            }
            if (empty($unit)) {
                $this->result(false, "请选择您所居住的单元号");
            }
            if (empty($files)) {
                $this->result(false, "请上传至少一张您的生活照");
            }
            $this->load->model("VoteModel", "vote", true);
            $insert_res = $this->vote->insert_vote(serialize($files), $slogan, $name, $unit);
            if ($insert_res == 1) {
                $this->result(true, "提交信息成功");
            }
            else {
                $this->result(false, "提交失败");
            }
        }
        else
        {
            $this->vars['no'] = "'" . implode("','", range(86, 126)) . "'" ;
            $this->page("vote/join.html");
        }

    }
}