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
        $this->vars['footer'] = true;
        $this->vars['fix_footer'] = true;
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
    public function vote_list()
    {
        $this->load->model("VoteModel", "vote", true);
        $votes = $this->vote->get_vote_list();
        foreach ($votes as $key => $item)
        {
            $votes[$key]['img'] = unserialize($item['img']);
            $votes[$key]['slogan'] = mb_substr($item['slogan'], 0, 32) . "...";
            $votes[$key]['create_time'] = date("Y-m-d H:i:s", $item['create_time']);
        }
        shuffle($votes);
        $this->vars['vote'] = $votes;
        $this->page("vote/vote_list.html");
    }
    public function show()
    {
        $vote_id = $this->input->get("id");
        $this->load->model("VoteModel", "vote", true);
        $info = $this->vote->get_vote_by_id($vote_id);
        $info['img'] = unserialize($info['img']);
        $info['slogan'] = mb_substr($info['slogan'], 0, 32) . "...";
        $info['create_time'] = date("Y-m-d H:i:s", $info['create_time']);
        $this->vars['info'] = $info;
        $this->page("vote/show.html");
    }
    public function request()
    {
        $vote_id = $this->input->post("id", true);
        $this->load->model("VoteModel", "vote", true);
        $ticket_arr = $this->vote->get_vote_by_id($vote_id);
        $update_res = $this->vote->vote_request($vote_id, $ticket_arr['ticket'] + 1);
        if($update_res == 1)
        {
            $this->result(true, "投给了" . $ticket_arr['name'] . "一票");
        }
        else
        {
            $this->result(false, "投票失败");
        }
    }
}