<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 07.02.14
 * Time: 11:04
 * To change this template use File | Settings | File Templates.
 */

class ModelProjectCampaign extends Model{


    public function showActualCampaign($language_id)
    {
        $sql = "SELECT c.*, des.*, cu.firstname as author, cu.about as author_about
         , cu.avatar as author_avatar , cu.customer_id as author_id
         FROM ".DB_PREFIX.".campaign  c
         LEFT JOIN campaign_description des ON(c.campaign_id = des.campaign_id)
         LEFT JOIN project pro ON(c.project_id = pro.project_id)
         LEFT JOIN customer cu ON(pro.author_id = cu.customer_id)
          WHERE c.date_start <= SYSDATE()
          AND DATE_ADD(c.date_start,INTERVAL 1 DAY) >= SYSDATE()
           AND des.language_id = '".(int)$language_id."' ";


        $res = $this->db->query($sql);

        return $res->row;
    }


    public function showLastChanceCampaign($language_id)
    {
        $sql = "SELECT c.*, des.*, cu.firstname as author, cu.about as author_about
         , cu.avatar as author_avatar , cu.customer_id as author_id
         FROM ".DB_PREFIX.".campaign  c
         LEFT JOIN campaign_description des ON(c.campaign_id = des.campaign_id)
         LEFT JOIN project pro ON(c.project_id = pro.project_id)
         LEFT JOIN customer cu ON(pro.author_id = cu.customer_id)
          WHERE DATE_ADD(c.date_start,INTERVAL 1 DAY) <= SYSDATE()
          AND DATE_ADD(c.date_start,INTERVAL 2 DAY) >= SYSDATE()
           AND des.language_id = '".(int)$language_id."' ";



        $res = $this->db->query($sql);

        return $res->row;
    }


    public function checkDate($date,$campaign_id = false)
    {

        $dateObj = new DateTime($date);
        $dateObj->add(new DateInterval('P1D'));

        $date_end = $dateObj->format('Y-m-d H:i');

        $sql = "SELECT * FROM ".DB_PREFIX."campaign
         WHERE date_start >= '".$this->db->escape($date)."' AND
         date_start <= '".$this->db->escape($date_end)."'   ";

        if($campaign_id)
        {
            $sql .= " AND campaign_id != '".(int)$campaign_id."' ";
        }

        $res = $this->db->query($sql);

        if($res->num_rows)
        {
             $camp = $this->getCampaign($res->row['campaign_id'],2);
             return $camp['name'];
        }
        else
        {
            return false;
        }
    }

    public function getCampaign($campaign_id,$language_id)
    {
        $sql = "SELECT c.*, cdes.*, cdes.name as name, cu.firstname as author, cu.avatar as author_avatar,  cu.customer_id as author_id,  cu.about as author_about
         FROM ".DB_PREFIX.".campaign  c
         LEFT JOIN campaign_description cdes ON(c.campaign_id = cdes.campaign_id)
          LEFT JOIN project pro ON(c.project_id = pro.project_id)
         LEFT JOIN customer cu ON(pro.author_id = cu.customer_id)
          WHERE c.campaign_id = '".(int)$campaign_id."'
           AND cdes.language_id = '".(int)$language_id."' ";

        $res = $this->db->query($sql);

        return $res->row;
    }

    private function applyConditions(&$sql,$data)
    {
        $sql .= ' LEFT JOIN campaign_description cdes ON(c.campaign_id = cdes.campaign_id) ';

            $sql .= ' LEFT JOIN project pr ON(c.project_id = pr.project_id) ';
            $sql .= ' LEFT JOIN customer cu ON(pr.author_id = cu.customer_id) ';


      //   $sql .= " WHERE cdes.language_id = '".(int)$this->config->get('config_admin_language')."'  ";
        $sql .= " WHERE cdes.language_id = '2'  ";

         if(isset($data['filter_date_start']) AND $data['filter_date_start'])
         {
              $sql .= " AND  c.date_start >= '".$this->db->escape($data['filter_date_start'])."' ";
         }

        if(isset($data['filter_date_stop']) AND $data['filter_date_stop'])
        {
            $sql .= " AND c.date_start <= '".$this->db->escape($data['filter_date_stop'])."' ";
        }

        if(isset($data['filter_name']) AND $data['filter_name'])
        {
            $sql .= " AND (cdes.name LIKE '%".$this->db->escape($data['filter_name'])."%'
            OR cu.firstname LIKE '%".$this->db->escape($data['filter_name'])."%'
            OR cdes.tag LIKE '%".$this->db->escape($data['filter_name'])."%' ) ";
        }

        if(isset($data['filter_author']) AND $data['filter_author'])
        {
            $sql .= " AND cu.firstname LIKE '%".$this->db->escape($data['filter_author'])."%' ";
        }

        if(isset($data['filter_tag']) AND $data['filter_tag'])
        {
            $sql .= " AND cdes.tag LIKE '%".$this->db->escape($data['filter_tag'])."%' ";
        }

        if(isset($data['sort_date']) OR isset($data['sort_vote']))
        {
            $sql .= " ORDER BY ";
        }

        if(isset($data['sort_date']) AND ($data['sort_date'] == 'ASC' OR $data['sort_date'] == 'DESC'))
        {
            $sql .= " c.date_start '".$data['sort_date'];
        }

        if(isset($data['sort_date']) AND isset($data['sort_vote']) AND ($data['sort_vote'] == 'ASC' OR $data['sort_vote'] == 'DESC'))
        {
            $sql .= " , c.vote ".$data['sort_vote'];
        }
        elseif(isset($data['sort_vote']) AND ($data['sort_vote'] == 'ASC' OR $data['sort_vote'] == 'DESC'))
        {
            $sql .= "  c.vote ".$data['sort_vote'];
        }

        if(isset($data['start']) ANd isset($data['limit']) AND is_numeric($data['start']) AND is_numeric($data['limit']))
        {
            $sql .= " LIMIT ".$data['start']." , ".$data['limit']." " ;
        }




        /*
         * statusy
         * - zaplanowana 1
         * - wlasnie trwa 2
         * - zakonczona ( niedostepna archiwum ) 3
         * - zakonczona ( dostepna w archiwum ) 4
         */
        /* if(isset($data['filter_status']) AND $data['filter_status'])
        {
            if($data['filter_status'] == 1)
            {
                $sql .= " cu.firstname LIKE %'".$this->db->escape($data['filter_author'])."' ";
            }
        } */
    }

    public function getCampaigns($data)
    {
        $sql = "SELECT c.*, cdes.name as name, cu.firstname as author, cu.avatar as author_avatar, pr.title as project, cu.customer_id as author_id FROM ".DB_PREFIX.".campaign c ";

        $this->applyConditions($sql,$data);

        $res = $this->db->query($sql);


        return $res->rows;
    }

    public function getTotalCampaigns($data)
    {
        $sql = "SELECT COUNT(DISTINCT(c.campaign_id)) as total FROM ".DB_PREFIX.".campaign c  ";

        $this->applyConditions($sql,$data);

        $res = $this->db->query($sql);

        return $res->row['total'];
    }

    public function upvote($campaign_id)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $this->db->query("UPDATE ".DB_PREFIX."campaign SET vote = vote + 1
         WHERE campaign_id = '".(int)$campaign_id."' ");
    }


    // opis
    public function getCampaignDescriptions($campaign_id , $language_id = false)
    {
        $sql = "SELECT * FROM ".DB_PREFIX."campaign_description
        WHERE campaign_id = '".(int)$campaign_id."' ";

        if($language_id)
        {
            $sql .= " AND language_id = '".(int)$language_id."' ";
        }

        $res = $this->db->query($sql);

        $data = array();

        if($language_id)
        {
            return $res->row;
        }
        else{

            foreach($res->rows as $row)
            {
                $data[$row['language_id']] = $row;
            }

            return $data;
        }


    }

    private function deleteDescription($campaign_id)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');


        $sql = "DELETE FROM ".DB_PREFIX."campaign_description
        WHERE campaign_id = '".(int)$campaign_id."'
         ";

        $this->db->query($sql);
    }

    private function saveDescription($campaign_id , $data)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $this->deleteDescription($campaign_id);



        foreach($data as $language_id => $row)
        {
            $sql = "INSERT INTO ".DB_PREFIX."campaign_description
        SET campaign_id = '".(int)$campaign_id."',
        language_id = '".(int)$language_id."',
        name = '".$this->db->escape($row['name'])."',
        description = '".$this->db->escape($row['description'])."',
        meta_description = '".$this->db->escape($row['meta_description'])."',
        meta_keyword = '".$this->db->escape($row['meta_keyword'])."',
        tag = '".$this->db->escape($row['tag'])."'
         ";

            $this->db->query($sql);
        }




    }

    // obrazy
    public function getCampaignImages($campaign_id)
    {


        $sql = "SELECT * FROM ".DB_PREFIX."campaign_image
        WHERE campaign_id = '".(int)$campaign_id."' ";



        $res = $this->db->query($sql);



        return $res->rows;
    }

    private function deleteImages($campaign_id)
    {
        $sql = "DELETE FROM ".DB_PREFIX."campaign_image
        WHERE campaign_id = '".(int)$campaign_id."'
         ";

        $this->db->query($sql);
    }

    private function saveImages($campaign_id , $data)
    {
        $this->deleteImages($campaign_id);


        foreach($data as $image)
        {
            $sql = "INSERT INTO ".DB_PREFIX."campaign_image
        SET campaign_id = '".(int)$campaign_id."',
        image = '".$this->db->escape($image['image'])."',
        sort_order = '".(int)$image['sort_order']."'

         ";

            $this->db->query($sql);
        }


    }

    // tagi

    public function getCampaignTags($campaign_id)
    {
        $sql = "SELECT * FROM ".DB_PREFIX."campaign_tag
        WHERE campaign_id = '".(int)$campaign_id."' ";



        $res = $this->db->query($sql);



        return $res->rows;
    }

    private function deleteTags($campaign_id)
    {
        $sql = "DELETE FROM ".DB_PREFIX."campaign_tag
        WHERE campaign_id = '".(int)$campaign_id."',
         ";

        $this->db->query($sql);
    }

    private function saveTags($campaign_id , $data)
    {
        $this->deleteTags($campaign_id);

        foreach($data as $tag)
        {
            $sql = "SELECT INSERT INTO ".DB_PREFIX."campaign_tag
        SET campaign_id = '".(int)$campaign_id."',
        tag = '".$this->db->escape($tag)."',

         ";

            $this->db->query($sql);
        }


    }

    // produckty
    public function getCampaignProducts($campaign_id)
    {
        $this->load->model('catalog/product');

        $products = $this->model_catalog_product->getProducts(array(
            'filter_campaign_id' => $campaign_id
        ));

        return $products;
    }

    public function getBasicProducts()
    {
        $this->load->model('catalog/product');

        $products = $this->model_catalog_product->getProducts(array(
            'filter_parent_id' => 0
        ));



        $data = array();

        foreach($products as $product)
        {
            $data[$product['product_id']] = $product;
        }

        return $data;
    }

    public function saveCampaignProducts($campaign_id,$data)
    {

        $this->releaseCampaignProducts($campaign_id);



        if(!empty($data))
        {
            foreach($data as $product_id)
            {
                $this->db->query("UPDATE ".DB_PREFIX."product SET campaign_id = '".(int)$campaign_id."'
                 WHERE product_id = '".(int)$product_id['product_id']."' ");
            }
        }
    }



    public function releaseCampaignProducts($campaign_id)
    {

        if(!$campaign_id)
        {
            return false;
        }

        $products = $this->getCampaignProducts($campaign_id);



        foreach($products as $product)
        {
            $this->db->query("UPDATE ".DB_PREFIX."product SET campaign_id = '0'
                 WHERE product_id = '".(int)$product['product_id']."' ");
        }


    }

    public function deleteCampaignProducts($campaign_id)
    {

        if(!$campaign_id)
        {
            return false;
        }

        $products = $this->getCampaignProducts($campaign_id);



        foreach($products as $product)
        {
            $this->db->query("DELETE FROM ".DB_PREFIX."product
                 WHERE product_id = '".(int)$product['product_id']."' ");
        }


    }




}