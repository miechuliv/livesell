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


        if(!empty($campaign))
        {
            $this->data['campaign_products'] = $this->model_project_campaign->getCampaignProducts($campaign['campaign_id']);





            $campaign_images = $this->model_project_campaign->getCampaignImages($campaign['campaign_id']);

            $this->data['campaign_images'] = array();

            foreach($campaign_images as $image)
            {
                $this->data['campaign_images'][] =  $this->model_tool_image->resize($image,200,200);
            }

            $this->data['author_about'] = $campaign['author_about'];

            $this->data['author_avatar'] = $this->model_tool_image->resize($campaign['author_avatar'],200,200);



            if(isset($this->request->get['last_chance']))
            {
                $this->data['alt_link'] = $this->url->link('common/home');

                $alt_offer = $this->model_project_campaign->showActualCampaign(2);
            }
            else
            {
                $this->data['alt_link'] = $this->url->link('common/home&type=last_chance');

                $alt_offer = $this->model_project_campaign->showLastChanceCampaign(2);
            }



            if(isset($alt_offer['campaign_id']))
            {
                $images = $this->model_project_campaign->getCampaignImages($alt_offer['campaign_id']);

                if(!empty($images))
                {
                    $image = array_shift($images);

                    $this->data['alt_offer_preview'] = $this->model_tool_image->resize($image,200,200);
                }
            }
            else
            {
                $this->data['alt_offer_preview'] = false;
            }




            foreach($this->data['campaign_products'] as $key => $product)
            {
                $this->data['campaign_products'][$key]['options'] = $this->model_catalog_product->getProductOptions($product['product_id']);
                $this->data['campaign_products'][$key]['price'] = $this->model_catalog_product->getProductsPrice($product['product_id'],$this->currency->getId(),(isset($this->request->get['last_chance'])?true:false));

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