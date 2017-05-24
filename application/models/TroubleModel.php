<?php
/**
 * 文件名称:TroubleModel.php
 * 摘    要:
 * 修改日期: 2017/5/25
 */
class TroubleModel extends MY_Model
{
    public function insert_trouble($files, $desc)
    {
        $info['files'] = $files;
        $info['desc'] = $desc;
        $info['create_time'] = time();
        $info['status'] = 0;
        $this->db->insert("trouble", $info);
        return $this->db->affected_rows();
    }
}