<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 13.02.14
 * Time: 15:41
 * To change this template use File | Settings | File Templates.
 */

class ModelProjectNote extends Model{

    public function insert($data)
    {

        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $sql = "INSERT INTO ".DB_PREFIX."project_note
         SET project_id = '".(int)$data['project_id']."',
         note = '".$this->db->escape($data['note'])."',
         date_added = NOW(),
         user_id = '".(int)$this->user->getId()."'

          ";

        $this->db->query($sql);

        return $this->db->getLastId();
    }

    public function update($data,$note_id)
    {
        $sql = "UPDATE ".DB_PREFIX."project_note
         SET
         note = '".$this->db->escape($data['note'])."',
         date_added = NOW(),
         user_id = '".(int)$this->user->getId()."'

          WHERE project_note_id = '".(int)$note_id."'
          ";

        $this->db->query($sql);
    }

    public function delete($note_id)
    {
        $sql = "DELETE FROM ".DB_PREFIX."project_note


          WHERE project_note_id = '".(int)$note_id."'
          ";

        $this->db->query($sql);
    }

    public function getByProject($project_id)
    {
        $q = $this->db->query("SELECT * FROM ".DB_PREFIX."project_note
         WHERE project_id = '".(int)$project_id."' ");

        return $q->rows;
    }
}