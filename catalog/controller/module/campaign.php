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

        $campaign = $this->model_project_campaign->showActualCampaign(2);

        if(!empty($campaign))
        {
            $this->data['campaign_products'] = $this->model_project_campaign->getCampaignProducts($campaign['campaign_id']);

            foreach($this->data['campaign_products'] as $key => $product)
            {
                $this->data['campaign_products'][$key]['options'] = $this->model_catalog_product->getProductOptions($product['product_id']);

                $this->data['campaign_products'][$key]['images'] = $this->model_catalog_product->getProductOptions($product['product_id']);
            }
        }
        else
        {
            $this->data['campaign'] = false;
        }
    }


}