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
           // 'filter_author',
            'filter_status',

        ));				$this->language->load('product/gallery');				$this->document->setTitle($this->language->get('text_title'));
    }





    public function showList()
    {
        $this->getList();
    }

    public function show()
    {


        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/gallery_show.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/product/gallery_show.tpl';
        } else {
            $this->template = 'default/template/product/gallery_show.tpl';
        }

        $this->children = array(
            'common/column_left',
            'common/column_right',
            'common/content_top',
            'common/content_bottom',
            'common/footer',
            'module/campaign',
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

        $this->document->setTitle($this->language->get('text_title'));
        $this->document->setDescription($this->language->get('text_description'));
        $this->document->setKeywords($this->language->get('text_keyword'));


        $this->setFields(array(

                'filter_name',
                //'filter_author',
               // 'filter_tag',
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


        if(!isset($data['filter_date_stop']))
        {
            $date = new DateTime();
            $in = new DateInterval('P2D');

            $date->sub($in);

            $data['filter_date_stop'] = $date->format('Y-m-d H:i:s');
        }




        $total_campaigns = $this->model_project_campaign->getTotalCampaigns($data);


        $data['start'] = ($page-1)*20;
        $data['limit'] = $page*20;

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
            $this->data['campaigns'][$key]['show'] = $this->url->link('product/gallery/show','&campaign_id='.$campaign['campaign_id'] . '&no_buy=1'. $this->baseUrl);

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




      $find = $this->db->query("SELECT * FROM ".DB_PREFIX."vote_ip WHERE customer_id = '".(int)$this->customer->getId()."' AND campaign_id = '".(int)$campaign_id."'  ");



      if($find->num_rows)
      {
          $res = 'block';
      }
      else
      {
          $res = 'ok';
          $this->db->query("INSERT INTO ".DB_PREFIX."vote_ip SET customer_id = '".(int)$this->customer->getId()."', campaign_id = '".(int)$campaign_id."' ");
          $this->load->model('project/campaign');

          $this->model_project_campaign->upvote($campaign_id);
      }


      echo $res;
  }





}