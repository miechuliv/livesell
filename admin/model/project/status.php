<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 06.02.14
 * Time: 15:47
 * To change this template use File | Settings | File Templates.
 */

class ModelProjectStatus extends Model{

    public function getStatuses($language_id)
    {
        return $this->db->query("SELECT * FROM ".DB_PREFIX.".project_status WHERE language_id = '".(int)$language_id."' ");

    }

    public function getStatus($status_id,$language_id)
    {

        return $this->db->query("SELECT * FROM ".DB_PREFIX.".project_status WHERE language_id = '".(int)$language_id."' AND status_id = '".(int)$status_id."' ");


    }


}