<?php
/**
 * Created by JetBrains PhpStorm.
 * User: USER
 * Date: 11.02.14
 * Time: 12:36
 * To change this template use File | Settings | File Templates.
 */

class ControllerModuleCampaign extends Controller{

    protected function index($setting)
    {
        $this->load->model('project/campaign');
        $this->load->model('catalog/product');
        $this->load->model('tool/image');

        if(isset($this->request->get['last_chance']))
        {
            $campaign = $this->model_project_campaign->showLastChanceCampaign(2);
        }
        else
        {
            $campaign = $this->model_project_campaign->showActualCampaign(2);
        }

        if(isset($this->request->get['no_buy']))
        {
            $this->data['no_buy'] = true;
        }
        else
        {
            $this->data['no_buy'] = false;
        }


        if(!empty($campaign))
        {


            $this->data['campaign_products'] = $this->model_project_campaign->getCampaignProducts($campaign['campaign_id']);




            $campaign['author_href'] = $this->url->link('project/author','&author_id='.$campaign['author_id']);
            $campaign['author_avatar'] = $this->model_tool_image->resize($campaign['author_avatar'],300,300);

            $date = new DateTime($campaign['date_start']);


            if(isset($this->request->get['last_chance']))
            {
                $i = new DateInterval('P2D');
            }
            else
            {
                $i = new DateInterval('P1D');
            }

            $date->add($i);

            $campaign['date_end'] = $date->format('Y-m-d H:i:s');


            $campaign['split_date'] = array(
                'year' => $date->format('Y'),
                'month' => (int)$date->format('m')-1,
                'day' => $date->format('d'),
                'hour' => $date->format('H'),
                'minute' => $date->format('i'),
                'second' => $date->format('s'),
            );




            $this->data['campaign'] = $campaign;


            $campaign_images = $this->model_project_campaign->getCampaignImages($campaign['campaign_id']);



            $this->data['campaign_images'] = array();

            foreach($campaign_images as $image)
            {
                $this->data['campaign_images'][] =  $this->model_tool_image->resize($image['image'],200,200);
            }

            $image = array_shift($campaign_images);

            $this->data['campaign_image'] =  $this->model_tool_image->resize($image['image'],800,800);





            if(isset($this->request->get['last_chance']))
            {
                $this->data['alt_link'] = $this->url->link('common/home');

                $alt_offer = $this->model_project_campaign->showActualCampaign(2);
            }
            else
            {
                $this->data['alt_link'] = $this->url->link('common/home&last_chance=1');

                $alt_offer = $this->model_project_campaign->showLastChanceCampaign(2);


            }



            if(isset($alt_offer['campaign_id']))
            {
                $images = $this->model_project_campaign->getCampaignImages($alt_offer['campaign_id']);


                if(!empty($images))
                {
                    $image = array_shift($images);

                    $this->data['alt_offer_preview'] = $this->model_tool_image->resize($image['image'],300,300);
                }
            }
            else
            {
                $this->data['alt_offer_preview'] = false;
            }






            foreach($this->data['campaign_products'] as $key => $product)
            {
                $im = $this->model_tool_image;
                $config = $this->config;
                $f = function($image) use ($im,$config) {
                     return $im->resize($image,$config->get('config_image_thumb_width'),$config->get('config_image_thumb_height'));
                };
                $this->data['campaign_products'][$key]['options'] = $this->model_catalog_product->getProductOptions($product['product_id'],$f);
                $this->data['campaign_products'][$key]['price'] = $this->model_catalog_product->getProductsPrice($product['product_id'],$this->currency->getId(),(isset($this->request->get['last_chance'])?true:false));
                $this->data['campaign_products'][$key]['image'] = $this->model_tool_image->resize($product['image'],$config->get('config_image_thumb_width'),$config->get('config_image_thumb_height'));
            }


        }
        else
        {
            $this->data['campaign'] = false;
        }



        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/module/campaign.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/module/campaign.tpl';
        } else {
            $this->template = 'default/template/module/campaign.tpl';
        }

        $this->render();
    }


}