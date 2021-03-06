<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 07.02.14
 * Time: 11:04
 * To change this template use File | Settings | File Templates.
 */

class ModelProjectCampaign extends Model{

    public function delete($campaign_id)
    {
        $this->deleteImages($campaign_id);

        $this->deleteDescription($campaign_id);

        $this->deleteCampaignProducts($campaign_id);

        $this->db->query("DELETE FROM ".DB_PREFIX."campaign WHERE campaign_id = '".(int)$campaign_id."' ");
    }


    public function insert($data)
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');


        $sql = "INSERT INTO ".DB_PREFIX.".campaign SET
        project_id = '".(int)$data['project_id']."',
        date_start = '".$this->db->escape($data['date_start'])."',
        show_archiwe = '".(int)$data['show_archiwe']."',
         show_archiwe_sell = '".(int)$data['show_archiwe_sell']."',
         vote = '0' ";

        $this->db->query($sql);


        $campaign_id = $this->db->getLastId();


        $this->saveDescription($campaign_id,$data['campaign_description']);



        if(isset($data['campaign_image']))
        {
            $this->saveImages($campaign_id,$data['campaign_image']);
        }


        /*if(isset($data['campaign_tags']))
        {
            $this->saveTags($campaign_id,$data['campaign_tags']);
        }*/




        if(isset($data['campaign_products']))
        {
            $this->saveCampaignProducts($campaign_id,$data['campaign_products']);
        }



        return $campaign_id;

        //
    }

    public function update($data,$campaign_id)
    {
        $sql = "UPDATE ".DB_PREFIX.".campaign SET

        show_archiwe = '".(int)$data['show_archiwe']."',
        date_start = '".$this->db->escape($data['date_start'])."',
         show_archiwe_sell = '".(int)$data['show_archiwe_sell']."'
        WHERE campaign_id = '".(int)$campaign_id."' ";

        $this->db->query($sql);



        $this->saveDescription($campaign_id,$data['campaign_description']);

        if(isset($data['campaign_image']))
        {
            $this->saveImages($campaign_id,$data['campaign_image']);
        }



        /*if(isset($data['campaign_tags']))
        {
            $this->saveTags($campaign_id,$data['campaign_tags']);
        }*/



        if(isset($data['campaign_products']))
        {
            $this->saveCampaignProducts($campaign_id,$data['campaign_products']);
        }

        return $campaign_id;
    }

    public function checkDate($date,$campaign_id = false)
    {

        $dateObj = new DateTime($date);
        $dateObj->add(new DateInterval('PT24H'));

        $date_end = $dateObj->format('Y-m-d H:I:s');




        $sql = "SELECT * FROM ".DB_PREFIX."campaign
         WHERE date_start > '".$this->db->escape($date)."' AND
         date_start < '".$this->db->escape($date_end)."'   ";

        if($campaign_id)
        {
            $sql .= " AND campaign_id != '".(int)$campaign_id."' ";
        }

        $res = $this->db->query($sql);



        if($res->num_rows)
        {
             $camp = $this->getCampaign($res->row['campaign_id']);
             return $camp['name'];
        }
        else
        {
            return false;
        }
    }

    public function getCampaign($campaign_id)
    {
        $sql = "SELECT * FROM ".DB_PREFIX.".campaign  c
         LEFT JOIN campaign_description des ON(c.campaign_id = des.campaign_id)
          WHERE c.campaign_id = '".(int)$campaign_id."'
           AND des.language_id = '2' ";

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
            $sql .= " AND cdes.name LIKE '%".$this->db->escape($data['filter_name'])."%' ";
        }

        if(isset($data['filter_author']) AND $data['filter_author'])
        {
            $sql .= " AND cu.firstname LIKE '%".$this->db->escape($data['filter_author'])."%' ";
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
        $sql = "SELECT c.*, cdes.name as name, cu.firstname as author, pr.title as project, cu.customer_id as author_id FROM ".DB_PREFIX.".campaign c ";

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