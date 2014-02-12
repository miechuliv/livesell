<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 11.02.14
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */

class ControllerProductGallery extends Controller{

    private $baseUrl;
    private $errors;

    function __construct($register)
    {
        parent::__construct($register);

        $this->baseUrl = $this->remainGet(array(
            'filter_date_start',
            'filter_date_end',
            'filter_name',
            'filter_author',
            'filter_status',

        ));
    }





    public function showList()
    {
        $this->getList();
    }

    public function show()
    {
        $this->load->model('project/campaign');

        if(isset($this->request->get['campaign_id']) AND $this->request->get['campaign_id'])
        {
            $campaign = $this->model_project_campaign->getCampaign($this->request->get['campaign_id']);
        }
        else
        {
            $campaign = array();
        }



        if(isset($this->request->get['project_id']))
        {
            $this->data['project_id'] = $this->request->get['project_id'];
        }
        elseif(isset($this->request->post['project_id']))
        {
            $this->data['project_id'] = $this->request->post['project_id'];
        }
        elseif(isset($campaign['project_id']))
        {
            $this->data['project_id'] =  $campaign['project_id'];
        }
        else
        {
            $this->session->data['error'] = $this->language->get('error_no_project');
            $this->redirect($this->url->link('project/campaign/showList', 'token=' . $this->session->data['token'] . $this->baseUrl, 'SSL'));
        }





        $this->setFields(array(
            'date_start',
            'show_archiwe',
            'vote'

        ),$campaign);


        $this->setFields(array(
            'error_date_start',
            'error_name',
            'error_products'
        ),$this->errors);



        $this->data['token'] = $this->session->data['token'];

        if (isset($this->request->post['campaign_description'])) {
            $this->data['campaign_description'] = $this->request->post['campaign_description'];
        } elseif (isset($this->request->get['campaign_id'])) {
            $this->data['campaign_description'] = $this->model_project_campaign->getCampaignDescriptions($this->request->get['campaign_id']);
        } else {
            $this->data['campaign_description'] = array();
        }

        if (isset($this->request->post['campaign_products'])) {
            $this->data['campaign_products'] = $this->request->post['campaign_products'];
        } elseif (isset($this->request->get['campaign_id'])) {
            $this->data['campaign_products'] = $this->model_project_campaign->getCampaignProducts($this->request->get['campaign_id']);
        } else {
            $this->data['campaign_products'] = array();
        }



        $this->data['release_status'] = false;

        if($this->data['date_start'])
        {
            // status
            $now = new DateTime();
            $date_start = new DateTime($this->data['date_start']);

            $date_last_chance = new DateTime($this->data['date_start']);
            $i = new DateInterval('P1D');
            $date_last_chance->add($i);

            $date_end = new DateTime($this->data['date_start']);
            $i = new DateInterval('P2D');
            $date_end->add($i);

            if($now < $date_start)
            {
                $this->data['release_status'] = $this->language->get('text_future');
            }
            elseif($now < $date_last_chance)
            {
                $this->data['release_status'] = $this->language->get('text_now_normal');
            }
            elseif($now < $date_end)
            {
                $this->data['release_status'] = $this->language->get('text_now_last_chance');
            }
            elseif($now > $date_end)
            {
                $this->data['release_status'] = $this->language->get('text_ended');
            }

        }




        foreach($this->data['campaign_products'] as $key => $value)
        {
            $this->data['campaign_products'][$key]['edit'] = $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . $this->baseUrl . '&product_id='.$value['product_id'] . '&full=1', 'SSL');
            $this->data['campaign_products'][$key]['show'] = str_ireplace('admin','',$this->url->link('product/product', 'token=' . $this->session->data['token'] . '&product_id=' . $value['product_id'] ));
        }

        /* if (isset($this->request->post['campaign_tags'])) {
             $this->data['campaign_tags'] = $this->request->post['campaign_tags'];
         } elseif (isset($this->request->get['campaign_id'])) {
             $this->data['campaign_tags'] = $this->model_project_campaign->getCampaignTags($this->request->get['campaign_id']);
         } else {
             $this->data['campaign_tags'] = array();
         } */

        if (isset($this->request->post['campaign_image'])) {
            $campaign_images = $this->request->post['campaign_image'];
        } elseif (isset($this->request->get['campaign_id'])) {
            $campaign_images = $this->model_project_campaign->getCampaignImages($this->request->get['campaign_id']);
        } else {
            $campaign_images = array();
        }

        $this->data['campaign_images'] = array();

        $this->load->model('tool/image');

        $this->data['product_templates'] = $this->model_project_campaign->getBasicProducts();



        foreach ($campaign_images as $campaign_image) {
            if ($campaign_image['image'] && file_exists(DIR_IMAGE . $campaign_image['image'])) {
                $image = $campaign_image['image'];
            } else {
                $image = 'no_image.jpg';
            }

            $this->data['campaign_images'][] = array(
                'image'      => $image,
                'thumb'      => $this->model_tool_image->resize($image, 100, 100),
                'sort_order' => $campaign_image['sort_order']
            );
        }


        $this->data['no_image'] = $this->model_tool_image->resize('no_image.jpg', 100, 100);

        if (!isset($this->request->get['campaign_id'])) {
            $this->data['action'] = $this->url->link('project/campaign/insert', 'token=' . $this->session->data['token'] . $this->baseUrl, 'SSL');
        } else {
            $this->data['action'] = $this->url->link('project/campaign/update', 'token=' . $this->session->data['token'] . '&campaign_id=' . $this->request->get['campaign_id'] . $this->baseUrl, 'SSL');
        }

        $this->data['cancel'] = $this->url->link('project/campaign/showList', 'token=' . $this->session->data['token'] . $this->baseUrl, 'SSL');

        $this->load->model('localisation/language');

        $this->data['languages'] = $this->model_localisation_language->getLanguages();

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/gallery/show.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/product/gallery/show.tpl';
        } else {
            $this->template = 'default/template/product/gallery/show.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'common/header',


        );

        $this->response->setOutput($this->render());
    }


    private function getList()
    {
        $this->load->model('project/campaign');
        $this->load->model('tool/image');


        // filtracja

        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        $defaultData = array('sort_date' => 'DESC', 'sort_vote' => 'DESC');


        $this->setFields(array(

                'filter_name',
                'filter_author',
                'filter_tag',
                'sort_date',
                'sort_vote',

            ),
            $defaultData,
            'get');

        $this->data['date_sorts'][] = array(
            'text'  => $this->language->get('text_date_desc'),
            'value' => 'DESC',
            'href'  => $this->url->link('product/gallery/showList',  '&sort_date=DESC' . $this->baseUrl)
        );

        $this->data['date_sorts'][] = array(
            'text'  => $this->language->get('text_date_asc'),
            'value' => 'ASC',
            'href'  => $this->url->link('product/gallery/showList',  '&sort_date=ASC' . $this->baseUrl)
        );

        $this->data['vote_sorts'][] = array(
            'text'  => $this->language->get('text_vote_desc'),
            'value' => 'DESC',
            'href'  => $this->url->link('product/gallery/showList',  '&sort_vote=DESC' . $this->baseUrl)
        );

        $this->data['vote_sorts'][] = array(
            'text'  => $this->language->get('text_vote_asc'),
            'value' => 'ASC',
            'href'  => $this->url->link('product/gallery/showList',  '&sort_vote=ASC' . $this->baseUrl)
        );


        if(isset($this->request->get['page']))
        {
            $page = $this->request->get['page'];
        }
        else
        {
            $page = 1;
        }

        $this->data['page'] = $page;

        $data = $this->request->get;

        // tylko kampanie które juz sie całkowicie zakończyły


        if(!isset($data['date_stop']))
        {
            $date = new DateTime();
            $in = new DateInterval('P2D');

            $date->sub($in);

            $data['date_stop'] = $date->format('Y-m-d h:i:s');
        }


        $total_campaigns = $this->model_project_campaign->getTotalCampaigns($data);


        $data['start'] = ($page-1)*1;
        $data['limit'] = $page*1;

        $this->data['campaigns'] =  $this->model_project_campaign->getCampaigns($data);

        $this->load->model('tool/image');

        foreach($this->data['campaigns'] as $key => $campaign)
        {


            //$this->image->resize($campaign[''], $config->get('config_image_thumb_width'), $config->get('config_image_thumb_height'));\
            $date = new DateTime($campaign['date_start']);
            $this->data['campaigns'][$key]['date_start'] = $date->format('Y-m-d');
            $in = new DateInterval('P1D');
            $date->add($in);
            $this->data['campaigns'][$key]['date_end'] = $date->format('Y-m-d');
            $this->data['campaigns'][$key]['show'] = $this->url->link('product/gallery/show','&campaign_id='.$campaign['campaign_id'] . $this->baseUrl);

            $images = $this->model_project_campaign->getCampaignImages($campaign['campaign_id']);


            $image = array_shift($images);


            $this->data['campaigns'][$key]['image'] = $this->model_tool_image->resize($image['image'],200,200);
            $this->data['campaigns'][$key]['author_avatar'] = $this->model_tool_image->resize($campaign['author_avatar'],60,60);
        }




        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');




        /* $pagination = new Pagination();
        $pagination->total = $total_campaigns;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('product/gallery/showList',  $this->baseUrl . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render(); */

        if(isset($this->request->get['ajax']))
        {
            echo json_encode($this->data['campaigns']);
        }
        else
        {
            if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/gallery_list.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/product/gallery_list.tpl';
            } else {
                $this->template = 'default/template/product/gallery_list.tpl';
            }

            $this->children = array(
                'common/column_left',
                'common/column_right',
                'common/content_top',
                'common/content_bottom',
                'common/footer',
                'common/header',

            );

            $this->response->setOutput($this->render());

        }


    }





  public function upvote()
  {

      error_reporting(E_ALL);
      ini_set('display_errors', '1');

      $campaign_id = $this->request->get['campaign_id'];




      if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
          $ip = $_SERVER['HTTP_CLIENT_IP'];
      } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
          $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
      } else {
          $ip = $_SERVER['REMOTE_ADDR'];
      }



      $find = $this->db->query("SELECT * FROM ".DB_PREFIX."vote_ip WHERE ip = '".$ip."' AND campaign_id = '".(int)$campaign_id."'  ");



      if($find->num_rows)
      {
          $res = 'block';
      }
      else
      {
          $res = 'ok';
          $this->db->query("INSERT INTO ".DB_PREFIX."vote_ip SET ip = '".$ip."', campaign_id = '".(int)$campaign_id."' ");
          $this->load->model('project/campaign');

          $this->model_project_campaign->upvote($campaign_id);
      }


      echo $res;
  }





}