<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 07.02.14
 * Time: 12:48
 * To change this template use File | Settings | File Templates.
 */

class ControllerProjectCampaign extends Controller{

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

        $this->language->load('project/campaign');
    }

    public function insert()
      {

          $this->load->model('project/campaign');



          if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->validate($this->request->post)) {

              $campaign_id = $this->model_project_campaign->insert($this->request->post);


              $this->session->data['success'] = $this->language->get('text_success');


              $this->redirect($this->url->link('project/campaign/showList', 'token=' . $this->session->data['token'] . $this->baseUrl, 'SSL'));
          }

          $this->getForm();
      }

    public function update(){
        $this->load->model('project/campaign');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && !$this->validate($this->request->post,$this->request->get['campaign_id'])) {

            $campaign_id = $this->model_project_campaign->update($this->request->post,$this->request->get['campaign_id']);


            $this->session->data['success'] = $this->language->get('text_success');


            $this->redirect($this->url->link('project/campaign/showList', 'token=' . $this->session->data['token'] . $this->baseUrl, 'SSL'));
        }

        $this->getForm();
    }

    public function showList()
    {
        $this->getList();
    }

	public function shortReport()	{			$campaign_id = $this->request->get['campaign_id'];				$this->load->model('report/campaign');				$this->load->model('project/campaign');				$this->load->model('sale/order');				$sales = $this->model_report_campaign->getCampaignSale($campaign_id);								$product_data = array();				foreach ($sales as $product) {					$option_data = array();										$key = $product['product_id'];															$options = $this->model_sale_order->getOrderOptions($product['order_id'], $product['order_product_id']);															foreach ($options as $option) {						if ($option['type'] != 'file') {							$value = $option['value'];						} else {							$value = utf8_substr($option['value'], 0, utf8_strrpos($option['value'], '.'));						}												$key .= '_'.$option['product_option_value_id'];												$option_data[] = array(							'name'  => $option['name'],							'value' => $value						);													}																				if(isset($product_data[$key]))					{						$product_data[$key]['sold'] += $product['sold'];					}					else					{					    $product_data[$key] = array(						'name'     => $product['name'],												'option'   => $option_data,						'sold' => $product['sold'],											);					}									}																				$this->data['products'] = $product_data;				$this->data['campaign'] = $this->model_project_campaign->getCampaign($campaign_id);				$this->template = 'project/short_report.tpl';        $this->children = array(            'common/footer',            'common/header'        );		        $this->response->setOutput($this->render());						}

    private function getList()
    {
        $this->load->model('project/campaign');

        if(isset($this->session->data['error']))
        {
            $this->data['error'] = $this->session->data['error'];
        }
        else
        {
            $this->data['error'] = false;
        }
        // filtracja

        error_reporting(E_ALL);
ini_set('display_errors', '1');


        $this->setFields(array(
                'filter_date_start',
                'filter_date_stop',
                'filter_name',
                'filter_author',
                'filter_status'
            ),
            false,
            'get');




        if(isset($this->request->get['page']))
        {
            $page = $this->request->get['page'];
        }
        else
        {
            $page = 1;
        }

        if(isset($this->session->data['success']))
        {
            $this->data['success'] = $this->session->data['success'];
        }
        else
        {
            $this->data['success'] = false;
        }






        $total_campaigns = $this->model_project_campaign->getTotalCampaigns($this->request->get);


        $this->request->get['start'] = ($page-1)*20;
        $this->request->get['limit'] = $page*20;

        $this->data['campaigns'] =  $this->model_project_campaign->getCampaigns($this->request->get);

        $this->load->model('tool/image');		$this->load->model('report/campaign');

        foreach($this->data['campaigns'] as $key => $campaign)
        {
            $this->data['campaigns'][$key]['edit'] = $this->url->link('project/campaign/update', $this->baseUrl.'&token='.$this->session->data['token'] . '&campaign_id='.$campaign['campaign_id'], 'SSL');
            $this->data['campaigns'][$key]['selected'] = isset($this->request->post['selected']) && in_array($campaign['campaign_id'], $this->request->post['selected']);
            $this->data['campaigns'][$key]['edit_author'] = $this->url->link('sale/customer/update', $this->baseUrl.'&token='.$this->session->data['token'] . '&customer_id='.$campaign['author_id'], 'SSL');
            //$this->image->resize($campaign[''], $config->get('config_image_thumb_width'), $config->get('config_image_thumb_height'));\
            $this->data['campaigns'][$key]['preview_link'] = str_ireplace('admin','',$this->url->link('common/home','&campaign_id='.$campaign['campaign_id']));						$this->data['campaigns'][$key]['sold'] = $this->model_report_campaign->getCampaigntTotalSale($campaign['campaign_id']);						$this->data['campaigns'][$key]['short_report'] = $this->url->link('project/campaign/shortReport', $this->baseUrl.'&token='.$this->session->data['token'] . '&campaign_id='.$campaign['campaign_id'], 'SSL');

            $date = new DateTime($campaign['date_start']);
            $in = new DateInterval('P1D');
            $date->add($in);
            $this->data['campaigns'][$key]['date_end'] = $date->format('Y-m-d H:i:s');



            $now = new DateTime();
            $date_start = new DateTime($campaign['date_start']);

            $date_last_chance = new DateTime($campaign['date_start']);
            $i = new DateInterval('P1D');
            $date_last_chance->add($i);

            $date_end = new DateTime($campaign['date_start']);
            $i = new DateInterval('P2D');
            $date_end->add($i);

            if($now < $date_start)
            {
                $this->data['campaigns'][$key]['status'] = false;
            }
            elseif($now < $date_last_chance)
            {
                $this->data['campaigns'][$key]['status'] = $this->model_tool_image->resize('green_ball.png',20,20);
            }
            elseif($now < $date_end)
            {
                $this->data['campaigns'][$key]['status'] = $this->model_tool_image->resize('yellow_ball.png',20,20);
            }
            elseif($now > $date_end)
            {
                $this->data['campaigns'][$key]['status'] = $this->model_tool_image->resize('red_ball.png',20,20);
            }
        }


        $this->data['token'] = $this->session->data['token'];


        $this->data['button_continue'] = $this->language->get('button_continue');
        $this->data['button_back'] = $this->language->get('button_back');

        $this->data['insert'] = $this->url->link('project/campaign/insert', 'token=' . $this->session->data['token'] . $this->baseUrl , 'SSL');
        $this->data['delete'] = $this->url->link('project/campaign/delete', 'token=' . $this->session->data['token'] . $this->baseUrl , 'SSL');


        $pagination = new Pagination();
        $pagination->total = $total_campaigns;
        $pagination->page = $page;
        $pagination->limit = $this->config->get('config_admin_limit');
        $pagination->text = $this->language->get('text_pagination');
        $pagination->url = $this->url->link('project/campaign/showList', 'token=' . $this->session->data['token'] . $this->baseUrl . '&page={page}', 'SSL');

        $this->data['pagination'] = $pagination->render();

        $this->template = 'project/campaign_list.tpl';

        $this->children = array(

            'common/footer',
            'common/header'
        );


        $this->response->setOutput($this->render());
    }

    public function delete()
    {


        $this->load->model('project/campaign');

        if (isset($this->request->post['selected'])) {
            foreach ($this->request->post['selected'] as $campaign_id) {
                $this->model_project_campaign->delete($campaign_id);
            }

            $this->session->data['success'] = $this->language->get('text_success');




            $this->redirect($this->url->link('project/campaign/showList', 'token=' . $this->session->data['token'] . $this->baseUrl, 'SSL'));
        }

        $this->getList();
    }


    private function getForm()
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

        $this->template = 'project/campaign_form.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $this->response->setOutput($this->render());
    }

    private function validate($data,$campaign_id = false)
    {
        $problem = false;

        // data
        if(!preg_match('/^[0-9]{4}+-+[0-9]{2}+-+[0-9]{2}+(\s+[0-9]{0,2}+:+[0-9]{0,2}+(:[0-9]{0,2})?)?$/',trim($data['date_start'])))
        {
            $this->errors['error_date_start'] = $this->language->get('error_date_start');
            $problem = true;
        }

        // sprawdzamy czy data jest zajęta
        if(($name = $this->model_project_campaign->checkDate($data['date_start'],$campaign_id)) !== false )
        {
            $this->errors['error_date_start'] = $this->language->get('error_date_used').' '.$name;
            $problem = true;
        }

        // opis
        foreach ($this->request->post['campaign_description'] as $language_id => $value) {
            if ((utf8_strlen($value['name']) < 1) || (utf8_strlen($value['name']) > 255)) {
                $this->errors['error_name'][$language_id] = $this->language->get('error_name');
                        $problem = true;
            }
        }


        // produkty
        if(!isset($data['campaign_products']) OR empty($data['campaign_products']) OR !$data['campaign_products'])
        {
            $this->errors['error_products'] = $this->language->get('error_products');
            $problem = true;
        }

        return $problem;
    }

    public function addProduct()
    {


        $parent_id = $this->request->post['parent_id'];

        $this->load->model('catalog/product');

        $this->load->model('project/campaign');

        $product_id = $this->model_catalog_product->copyProduct($parent_id);

        // ustawiamy mu parenta
        $this->model_catalog_product->setProductsParent($product_id,$parent_id);


        $templates = $this->model_project_campaign->getBasicProducts();

        $parent_name = $templates[$parent_id]['name'];



        $json = array(
            'product_id' => $product_id,
                'parent' => $parent_name,
                'product_edit' => $this->url->link('catalog/product/update', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id . '&full=1', 'SSL'),

                'product_show' =>  str_ireplace('admin','',$this->url->link('product/product', 'token=' . $this->session->data['token'] . '&product_id=' . $product_id )),
        );

        echo json_encode($json);
    }

    public function deleteProduct()
    {


        $product_id = $this->request->post['product_id'];

        $this->load->model('catalog/product');

        $this->model_catalog_product->deleteProduct($product_id);


        echo true;
    }


}