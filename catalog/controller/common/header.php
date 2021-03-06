<?php
class ControllerCommonHeader extends Controller {
	protected function index() {
		$this->data['title'] = $this->document->getTitle();
		
		if (isset($this->request->server['HTTPS']) && (($this->request->server['HTTPS'] == 'on') || ($this->request->server['HTTPS'] == '1'))) {
			$server = $this->config->get('config_ssl');
		} else {
			$server = $this->config->get('config_url');
		}

        $this->document->addScript('catalog/view/javascript/cloud-zoom.1.0.2.min.js');
        $this->document->addScript('catalog/view/javascript/whcookies.js');
        $this->document->addScript('catalog/view/javascript/project_specyfic/jquery.validate.js');

        $this->data['is_ie'] = false;

        if( $this->browser->getBrowser() == Browser::BROWSER_IE  ) {
            $this->data['is_ie'] =  true;
        }


        // jeśli kampania nie została załadowana to ładujemy ją teraz do licznika
        $campaign = $this->document->getCampaign();

        $this->load->model('project/campaign');
        $this->data['last_chance'] = $this->model_project_campaign->showLastChanceCampaign($this->config->get('config_language_id'));

       // kampanie - galeria
        $this->load->model('project/campaign');
        $t =array();

            $date = new DateTime();
            $in = new DateInterval('P2D');

            $date->sub($in);

            $t['filter_date_stop'] = $date->format('Y-m-d H:i:s');

        $this->data['total_campaigns'] = $this->model_project_campaign->getTotalCampaigns($t);




        if(!$campaign)
        {


            $campaign = $this->model_project_campaign->showActualCampaign($this->config->get('config_language_id'));

            if($campaign){

            $campaign['no_buy'] = false;
            $campaign['campaign_type'] = 'current';

            $date = new DateTime($campaign['date_start']);

            $i = new DateInterval('P1D');


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

            $this->document->setCampaign($campaign);

            }
        }





        // facebook like box

        $this->data['facebook_check'] = $this->config->get('config_facebook_check');
        $this -> data['facebook_link'] = $this -> config -> get('config_facebook_link');
        $this -> data['facebook_link'] = str_replace('/', '%2F', $this -> data['facebook_link']);

		$this->data['base'] = $server;
		$this->data['description'] = $this->document->getDescription();
		$this->data['keywords'] = $this->document->getKeywords();
		$this->data['links'] = $this->document->getLinks();	 
		$this->data['styles'] = $this->document->getStyles();
		$this->data['scripts'] = $this->document->getScripts();
		$this->data['lang'] = $this->language->get('code');
		$this->data['direction'] = $this->language->get('direction');
		$this->data['google_analytics'] = html_entity_decode($this->config->get('config_google_analytics'), ENT_QUOTES, 'UTF-8');
		$this->data['name'] = $this->config->get('config_name');
		
		if ($this->config->get('config_icon') && file_exists(DIR_IMAGE . $this->config->get('config_icon'))) {
			$this->data['icon'] = $server . 'image/' . $this->config->get('config_icon');
		} else {
			$this->data['icon'] = '';
		}
		
		if ($this->config->get('config_logo') && file_exists(DIR_IMAGE . $this->config->get('config_logo'))) {
			$this->data['logo'] = $server . 'image/' . $this->config->get('config_logo');
		} else {
			$this->data['logo'] = '';
		}		
		
		$this->language->load('common/header');
		
		$this->data['text_home'] = $this->language->get('text_home');
		$this->data['text_wishlist'] = sprintf($this->language->get('text_wishlist'), (isset($this->session->data['wishlist']) ? count($this->session->data['wishlist']) : 0));
		$this->data['text_shopping_cart'] = $this->language->get('text_shopping_cart');
    	$this->data['text_search'] = $this->language->get('text_search');
		$this->data['text_welcome'] = sprintf($this->language->get('text_welcome'), $this->url->link('account/login', '', 'SSL'), $this->url->link('account/register', '', 'SSL'));
		$this->data['text_logged'] = sprintf($this->language->get('text_logged'), $this->url->link('account/account', '', 'SSL'), $this->customer->getFirstName(), $this->url->link('account/logout', '', 'SSL'));
		$this->data['text_account'] = $this->language->get('text_account');
    	$this->data['text_checkout'] = $this->language->get('text_checkout');

        $l = $this->config->get("config_language");
        $this->data['rss_link'] = HTTP_SERVER.'rss'.$l.'.xml';
				
		$this->data['home'] = $this->url->link('common/home');
		$this->data['wishlist'] = $this->url->link('account/wishlist', '', 'SSL');
		$this->data['logged'] = $this->customer->isLogged();
		$this->data['account'] = $this->url->link('account/account', '', 'SSL');
		$this->data['shopping_cart'] = $this->url->link('checkout/cart');
		$this->data['checkout'] = $this->url->link('checkout/checkout', '', 'SSL');

        $this->data['active'] = $this->url->link('common/home');
        $this->data['last_chance'] = $this->url->link('common/home','&last_chance=1');
        $this->data['gallery'] = $this->url->link('product/gallery/showList');
        $this->data['register'] = $this->url->link('account/project/submit');
        $this->data['contact'] = $this->url->link('information/contact');
        $this->data['cart_link'] = $this->url->link('checkout/cart');
        $this->data['shop'] = $this->url->link('product/category');
        $this->data['blog'] = $this->url->link('module/blog');

        $this->data['show_shop'] = $this->config->get('config_show_store');



        if(isset($this->request->get['route']))
        {
            if(strpos($this->request->get['route'],'common/home')!==false AND isset($this->request->get['last_chance']))
            {
                $this->data['selected'] = 'last_chance';
            }
            elseif(strpos($this->request->get['route'],'common/home')!==false)
            {
                $this->data['selected'] = 'active';
            }
            elseif(strpos($this->request->get['route'],'product/gallery')!==false)
            {
                $this->data['selected'] = 'gallery';
            }
            elseif(strpos($this->request->get['route'],'account/register')!==false)
            {
                $this->data['selected'] = 'register';
            }
            elseif(strpos($this->request->get['route'],'information/contact')!==false)
            {
                $this->data['selected'] = 'contact';
            }
            elseif(strpos($this->request->get['route'],'checkout/cart')!==false)
            {
                $this->data['selected'] = 'cart';
            }
            elseif(strpos($this->request->get['route'],'module/blog')!==false)
            {
                $this->data['selected'] = 'blog';
            }
            elseif(strpos($this->request->get['route'],'category')!==false OR strpos($this->request->get['route'],'product')!==false)
            {
                $this->data['selected'] = 'shop';
            }
            else
            {
                $this->data['selected'] = false;
            }
        }
        else
        {
            $this->data['selected'] = 'active';
        }
		
		// Daniel's robot detector
		$status = true;
		
		if (isset($this->request->server['HTTP_USER_AGENT'])) {
			$robots = explode("\n", trim($this->config->get('config_robots')));

			foreach ($robots as $robot) {
				if ($robot && strpos($this->request->server['HTTP_USER_AGENT'], trim($robot)) !== false) {
					$status = false;

					break;
				}
			}
		}
		
		// A dirty hack to try to set a cookie for the multi-store feature
		$this->load->model('setting/store');
		
		$this->data['stores'] = array();
		
		if ($this->config->get('config_shared') && $status) {
			$this->data['stores'][] = $server . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();
			
			$stores = $this->model_setting_store->getStores();
					
			foreach ($stores as $store) {
				$this->data['stores'][] = $store['url'] . 'catalog/view/javascript/crossdomain.php?session_id=' . $this->session->getId();
			}
		}
				
		// Search		
		if (isset($this->request->get['search'])) {
			$this->data['search'] = $this->request->get['search'];
		} else {
			$this->data['search'] = '';
		}
		
		// Menu
		$this->load->model('catalog/category');
		
		$this->load->model('catalog/product');
		
		$this->data['categories'] = array();
					
		$categories = $this->model_catalog_category->getCategories(0);
		
		foreach ($categories as $category) {
			if ($category['top'] AND !$category['virtual']) {
				// Level 2
				$children_data = array();
				
				$children = $this->model_catalog_category->getCategories($category['category_id']);
				
				foreach ($children as $child) {
					$data = array(
						'filter_category_id'  => $child['category_id'],
						'filter_sub_category' => true
					);
					
					//$product_total = $this->model_catalog_product->getTotalProducts($data);
									
					$children_data[] = array(
						//'name'  => $child['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
                        'name'  => $child['name'],
						'href'  => $this->url->link('product/category', 'path=' . $category['category_id'] . '_' . $child['category_id'])
					);						
				}
				
				// Level 1
				$this->data['categories'][] = array(
					'name'     => $category['name'],
					'children' => $children_data,
					'column'   => $category['column'] ? $category['column'] : 1,
					'href'     => $this->url->link('product/category', 'path=' . $category['category_id'])
				);
			}
		}

        $this->load->model('catalog/information');

        $informations = $this->model_catalog_information->getInformations();

        $this->data['informations'] = array();

        foreach($informations as $info)
        {
             if($info['top'])
             {
                 $this->data['informations'][] = array(
                     'href' => $this->url->link('information/information','information_id='.$info['information_id'] ),
                     'name' => $info['title'],
                 );
             }
        }

		
		$this->children = array(
			'module/language',
			'module/currency',
			'module/cart',

		);
				
		if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/common/header.tpl')) {
			$this->template = $this->config->get('config_template') . '/template/common/header.tpl';
		} else {
			$this->template = 'default/template/common/header.tpl';
		}
		
    	$this->render();
	} 	
}
?>
