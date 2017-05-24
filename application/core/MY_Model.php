<?php
class MY_Model extends CI_Model {

    function load_by_query($query){
        $query = $this->db->query($query);

        if($row = $query->row_array()){
            foreach($row as $k=>$v){
                $this->$k = $v;
            }
            return true;
        }
        return false;
    }

    public function result_to_object($result)
    {
        if(empty($result))
        {
            return false;
        }
        else
        {
            foreach ($result as $key => $value)
            {
                $this->$key = $value;
            }
            return true;
        }
    }
}
