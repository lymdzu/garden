<?php
/**
 * 文件名称:VoteModel.php
 * 摘    要:
 * 修改日期: 2017/5/27
 */
class VoteModel extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }
    public function insert_vote($files, $slogan, $name, $unit)
    {
        $params['img'] = $files;
        $params['slogan'] = $slogan;
        $params['name'] = $name;
        $params['unit'] = $unit;
        $params['openid'] = "";
        $params['create_time'] = time();
        $this->db->insert("vote", $params);
        return $this->db->affected_rows();
    }
    public function get_vote_list()
    {
        $query = $this->db->get("vote");
        return $query->result_array();
    }
    public function vote_request($id, $ticket)
    {
        $this->db->where("id", $id);
        $this->db->update("vote", array("ticket" => $ticket));
        return $this->db->affected_rows();
    }
    public function get_vote_by_id($id)
    {
        $this->db->where("id", $id);
        $query = $this->db->get("vote");
        return $query->row_array();
    }
}