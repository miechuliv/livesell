<?php 
class ControllerProductCategory extends Controller {  
	public function index() {

		$this->language->load('product/category');


		
		$this->load->model('catalog/category');


		
		$this->load->model('catalog/product');
		
		$this->load->model('tool/image');

		if (isset($this->request->get['filter'])) {
			$filter = $this->request->get['filter'];
		} else {
			$filter = '';
		}
				
		if (isset($this->request->get['sort'])) {
			$sort = $this->request->get['sort'];
		} else {
			$sort = 'p.sort_order';
		}

		if (isset($this->request->get['order'])) {
			$order = $this->request->get['order'];
		} else {
			$order = 'ASC';
		}
		
		if (isset($this->request->get['page'])) {
			$page = $this->request->get['page'];
		} else { 
			$page = 1;
		}	
							
		if (isset($this->request->get['limit'])) {
			$limit = $this->request->get['limit'];
		} else {
			$limit = $this->config->get('config_catalog_limit');
		}

        // additional search
        // get search data

        $min = 0;

        $query = $this->db->query("SELECT MAX(price) as max, MIN(price) as min FROM product");


        if(isset($query->row['max']) AND (int)$query->row['max'] >= 100)
        {
            $max = $query->row['max'];

        }
        else
        {
            $max = 2000;

        }

        $max = (int)$this->currency->convert($max,$this->config->get('config_currency'),$this->currency->getCode());
        $max = round($max);

        $step = 20;
        $zeros = 0;

        $f = function($max)use(&$f,&$zeros)
        {
              // reszta jest liczbą całkowitą

              if(round( ($max/10), 0, PHP_ROUND_HALF_DOWN) > 0)
              {
                  $zeros ++;
                  return $f(round($max/10));
              }
              else
              {
                  $tail = '';
                  for($i=0;$i<$zeros;$i++)
                  {
                      $tail .= '0';
                  }

                  return $max.$tail;
              }
        };

        $max = $f($max);



        $this->data['all_cats'] = false;

        if(isset($this->request->post['clear']) AND $this->request->post['clear'])
        {
          /*  unset($this->request->post['year']);
            unset($this->request->post['make']);
            unset($this->request->post['model']);
            unset($this->request->post['type']); */
            unset($this->request->post['all-cats']);
            unset($this->request->post['manufacturer_id']);
        }

        if(isset($this->request->post['all-cats']) AND $this->request->post['all-cats'])
        {
            unset($this->request->get['path']);

            $this->data['all_cats'] = true;

        }

        $this->updateGlobalData('all_cats',$this->data['all_cats']);

        $step = $this->currency->convert($step,$this->config->get('config_currency'),$this->currency->getCode());
       // $step = round($step);



        $this->data['contact_link_text'] = $this->language->get('text_contact');
        $this->data['contact_link'] = $this->url->link('information/contact');




        if(isset($this->request->get['path']) AND $this->request->get['path']!='' )
        {
            $car_action =  $this->url->link('product/category','&path='.$this->request->get['path']);
        }
        else
        {
            $car_action =  $this->url->link('product/category');
        }



        $filtering = false;
        $price_min_values = array();

        $price_max_values = array();

        for($i=0; $i < $max ; $i+=$step) {
            array_push($price_min_values,(string)$i);
            array_push($price_max_values,(string)($i+$step));
        }
        $filters = array();

        $filters['price_min_value'] = $price_min_values;
        $filters['price_max_value'] = $price_max_values;

        $filters['default_current_price_min']=0;


        $filters['default_current_price_max']=$max;

        $filters['number_price_sections'] = count($price_max_values)-1;

        $this->updateGlobalData('filters',$filters);

        $all = false;








		$this->data['breadcrumbs'] = array();

   		$this->data['breadcrumbs'][] = array(
       		'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),
       		'separator' => false
   		);	
			
		if (isset($this->request->get['path']) AND $this->request->get['path']) {
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
									
			$path = '';
		
			$parts = explode('_', (string)$this->request->get['path']);
		
			$category_id = (int)array_pop($parts);
		
			foreach ($parts as $path_id) {
				if (!$path) {
					$path = (int)$path_id;
				} else {
					$path .= '_' . (int)$path_id;
				}
									
				$category_info = $this->model_catalog_category->getCategory($path_id);
				
				if ($category_info) {
	       			$this->data['breadcrumbs'][] = array(
   	    				'text'      => $category_info['name'],
						'href'      => $this->url->link('product/category', 'path=' . $path . $url),
        				'separator' => $this->language->get('text_separator')
        			);
				}
			}
		} else {
			$category_id = 0;
            $all = true;
		}



				
		$category_info = $this->model_catalog_category->getCategory($category_id,$all);


	
		if ($category_info) {

            if($all)
            {
                $this->document->setTitle($this->config->get('config_name'));
                $this->document->setDescription($this->config->get('config_name'));
                $this->document->setKeywords($this->config->get('config_name'));
                $this->data['heading_title'] = $this->config->get('config_name');
            }
            else
            {
                $this->document->setTitle($category_info['name']);
                $this->document->setDescription($category_info['meta_description']);
                $this->document->setKeywords($category_info['meta_keyword']);
                $this->data['heading_title'] = $category_info['name'];
            }

			$this->document->addScript('catalog/view/javascript/jquery/jquery.total-storage.min.js');
			

			
			$this->data['text_refine'] = $this->language->get('text_refine');
			$this->data['text_empty'] = $this->language->get('text_empty');			
			$this->data['text_quantity'] = $this->language->get('text_quantity');
			$this->data['text_manufacturer'] = $this->language->get('text_manufacturer');
			$this->data['text_model'] = $this->language->get('text_model');
			$this->data['text_price'] = $this->language->get('text_price');
			$this->data['text_tax'] = $this->language->get('text_tax');
			$this->data['text_points'] = $this->language->get('text_points');
			$this->data['text_compare'] = sprintf($this->language->get('text_compare'), (isset($this->session->data['compare']) ? count($this->session->data['compare']) : 0));
			$this->data['text_display'] = $this->language->get('text_display');
			$this->data['text_list'] = $this->language->get('text_list');
			$this->data['text_grid'] = $this->language->get('text_grid');
			$this->data['text_sort'] = $this->language->get('text_sort');
			$this->data['text_limit'] = $this->language->get('text_limit');
					
			$this->data['button_cart'] = $this->language->get('button_cart');
			$this->data['button_wishlist'] = $this->language->get('button_wishlist');
			$this->data['button_compare'] = $this->language->get('button_compare');
			$this->data['button_continue'] = $this->language->get('button_continue');
			
			// Set the last category breadcrumb		
			$url = '';
			
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	
			
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}

            if($all)
            {
                $this->data['breadcrumbs'][] = array(
                    'text'      => 'Alle Kategorien',
                    'href'      => $this->url->link('product/category'),
                    'separator' => $this->language->get('text_separator')
                );
            }
            else
            {
                $this->data['breadcrumbs'][] = array(
                    'text'      => $category_info['name'],
                    'href'      => $this->url->link('product/category', 'path=' . $this->request->get['path']),
                    'separator' => $this->language->get('text_separator')
                );
            }

								
			if (!$all AND $category_info['image']) {
				$this->data['thumb'] = $this->model_tool_image->resize($category_info['image'], $this->config->get('config_image_category_width'), $this->config->get('config_image_category_height'));
			} else {
				$this->data['thumb'] = '';
			}

            if($all)
            {
                $this->data['description'] = 'Alle Kategorien';
            }
            else
            {
                $this->data['description'] = html_entity_decode($category_info['description'], ENT_QUOTES, 'UTF-8');
            }

			$this->data['compare'] = $this->url->link('product/compare');
			
			$url = '';
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}	
						
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}	
			
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
								
			$this->data['categories'] = array();

            if($all)
            {


                // wybiera Alle Kategorien
                $results = $this->model_catalog_category->getCategories($category_id,$all);

                $this->data['products'] = array();

                foreach($results as $result)
                {
                    $this->data['categories'][] = array(
                        'name'  => $result['name'],
                        'href'  => $this->url->link('product/category', 'path=' . $result['category_id'] . $url)
                    );
                }


                $product_results = array();


                $data = array(

                    'filter_filter'      => $filter,
                    'sort'               => $sort,
                    'order'              => $order,
                    'start'              => ($page - 1) * ($limit),
                    'limit'              => $limit,
                    'filter_show_on_store' => 1,
                    'filter_campaign_status' => 'ended',
                    'quantity' => 1,
                );



                if($filtering){
                    $product_total = $this->model_catalog_product->getTotalProducts($data);
                }
                else
                {
                    $product_total = $this->model_catalog_product->getTotalProducts($data);
                }

                    if(isset($filtering))
                    {
                        $product_results = array_merge($product_results,$this->model_catalog_product->getProducts($data,$this->data['filters']));
                    }
                    else
                    {
                        $product_results = array_merge($product_results,$this->model_catalog_product->getProducts($data));
                    }





            }
			else
            {
                $results = $this->model_catalog_category->getCategories($category_id);

                foreach ($results as $result) {
                    $data = array(
                        'filter_category_id'  => $result['category_id'],
                        'filter_sub_category' => true
                    );

                    $product_total = $this->model_catalog_product->getTotalProducts($data);

                    $this->data['categories'][] = array(
                        'name'  => $result['name'] . ($this->config->get('config_product_count') ? ' (' . $product_total . ')' : ''),
                        'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '_' . $result['category_id'] . $url)
                    );
                }

                $this->data['products'] = array();

                $data = array(
                    'filter_category_id' => $category_id,
                    'filter_filter'      => $filter,
                    'sort'               => $sort,
                    'order'              => $order,
                    'start'              => ($page - 1) * $limit,
                    'limit'              => $limit,
                    'filter_show_on_store' => 1,
                    'filter_campaign_status' => 'ended',
                    'quantity' => 1,
                );



                if($filtering){
                    $product_total = $this->model_catalog_product->getTotalProducts($data);
                }
                else
                {
                    $product_total = $this->model_catalog_product->getTotalProducts($data);
                }

                if($filtering)
                {
                    $results = $this->model_catalog_product->getProducts($data);
                }
                else
                {
                    $results = $this->model_catalog_product->getProducts($data);
                }

            }



			
            if(isset($product_results))
            {
                $results = $product_results;
            }

			
			foreach ($results as $result) {
				if ($result['image']) {
					$image = $this->model_tool_image->resize($result['image'], $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height'));


                  //  $image = $this->model_tool_image->image_watermark(str_replace(HTTP_IMAGE,'',$image));
				} else {
					$image = false;
				}



				/*if (($this->config->get('config_customer_price') && $this->customer->isLogged()) || !$this->config->get('config_customer_price')) {
					$price = $this->currency->format($this->tax->calculate($result['price'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$price = false;
				}*/

                $price = $this->model_catalog_product->getProductsPrice($result['product_id'],$this->currency->getId(),false);

				
				if ((float)$result['special']) {
					$special = $this->currency->format($this->tax->calculate($result['special'], $result['tax_class_id'], $this->config->get('config_tax')));
				} else {
					$special = false;
				}	
				
				if ($this->config->get('config_tax')) {
					$tax = $this->currency->format((float)$result['special'] ? $result['special'] : $result['price']);
				} else {
					$tax = false;
				}				
				
				if ($this->config->get('config_review_status')) {
					$rating = (int)$result['rating'];
				} else {
					$rating = false;
				}

                // CodeHouse: get additional image
                $results = $this->model_catalog_product->getProductImages($result['product_id']);




                if ( isset($results[0]['image']) ) {
                    $additional_image = $this->model_tool_image->resize($results[0]['image'] , $this->config->get('config_image_product_width'), $this->config->get('config_image_product_height')) ;
                } else { $additional_image = false ; }
                // CodeHouse: END

                if($all)
                {
                   $href = $this->url->link('product/product',  '&product_id=' . $result['product_id'] . $url);
                }
                else
                {
                   $href =  $this->url->link('product/product', 'path=' . $this->request->get['path'] . '&product_id=' . $result['product_id'] . $url);
                }
								
				$this->data['products'][] = array(
					'product_id'  => $result['product_id'],
					'thumb'       => $image,
                    // CodeHouse: get additional image
                    'additional_image' => $additional_image,
                    // CodeHouse: END
					'name'        => $result['name'],
					'description' => utf8_substr(strip_tags(html_entity_decode($result['description'], ENT_QUOTES, 'UTF-8')), 0, 100) . '..',
					'price'       => $this->currency->format($price,'',1,true),
					'special'     => $special,
					'tax'         => $tax,
					'rating'      => $result['rating'],
					'reviews'     => sprintf($this->language->get('text_reviews'), (int)$result['reviews']),
					'href'        => $href,
				);



			}

			
			$url = '';
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}
				
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
										
			$this->data['sorts'] = array();

            if($all)
            {
                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_default'),
                    'value' => 'p.sort_order-ASC',
                    'href'  => $this->url->link('product/category',  'sort=p.sort_order&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_name_asc'),
                    'value' => 'pd.name-ASC',
                    'href'  => $this->url->link('product/category',  'sort=pd.name&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_name_desc'),
                    'value' => 'pd.name-DESC',
                    'href'  => $this->url->link('product/category', 'sort=pd.name&order=DESC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_price_asc'),
                    'value' => 'p.price-ASC',
                    'href'  => $this->url->link('product/category',  'sort=p.price&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_price_desc'),
                    'value' => 'p.price-DESC',
                    'href'  => $this->url->link('product/category', 'sort=p.price&order=DESC' . $url)
                );

                if ($this->config->get('config_review_status')) {
                    $this->data['sorts'][] = array(
                        'text'  => $this->language->get('text_rating_desc'),
                        'value' => 'rating-DESC',
                        'href'  => $this->url->link('product/category',  'sort=rating&order=DESC' . $url)
                    );

                    $this->data['sorts'][] = array(
                        'text'  => $this->language->get('text_rating_asc'),
                        'value' => 'rating-ASC',
                        'href'  => $this->url->link('product/category', 'sort=rating&order=ASC' . $url)
                    );
                }

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_model_asc'),
                    'value' => 'p.model-ASC',
                    'href'  => $this->url->link('product/category', 'sort=p.model&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_model_desc'),
                    'value' => 'p.model-DESC',
                    'href'  => $this->url->link('product/category', 'sort=p.model&order=DESC' . $url)
                );
            }
            else
            {
                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_default'),
                    'value' => 'p.sort_order-ASC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.sort_order&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_name_asc'),
                    'value' => 'pd.name-ASC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_name_desc'),
                    'value' => 'pd.name-DESC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=pd.name&order=DESC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_price_asc'),
                    'value' => 'p.price-ASC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_price_desc'),
                    'value' => 'p.price-DESC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.price&order=DESC' . $url)
                );

                if ($this->config->get('config_review_status')) {
                    $this->data['sorts'][] = array(
                        'text'  => $this->language->get('text_rating_desc'),
                        'value' => 'rating-DESC',
                        'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=DESC' . $url)
                    );

                    $this->data['sorts'][] = array(
                        'text'  => $this->language->get('text_rating_asc'),
                        'value' => 'rating-ASC',
                        'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=rating&order=ASC' . $url)
                    );
                }

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_model_asc'),
                    'value' => 'p.model-ASC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=ASC' . $url)
                );

                $this->data['sorts'][] = array(
                    'text'  => $this->language->get('text_model_desc'),
                    'value' => 'p.model-DESC',
                    'href'  => $this->url->link('product/category', 'path=' . $this->request->get['path'] . '&sort=p.model&order=DESC' . $url)
                );
            }
			

			
			$url = '';
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}
				
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
			
			$this->data['limits'] = array();
	
			$limits = array_unique(array($this->config->get('config_catalog_limit'), 25, 50, 75, 100));
			
			sort($limits);

            if($all)
            {
              $href =  $this->url->link('product/category',  $url . '&limit=' . $limits);
            }
            else
            {
                $href =   $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&limit=' . $limits);
            }
	
			foreach($limits as $limits){
				$this->data['limits'][] = array(
					'text'  => $limits,
					'value' => $limits,
					'href'  => $href
				);
			}
			
			$url = '';
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}
				
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
	
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}


					
			$pagination = new Pagination();
			$pagination->total = $product_total;
			$pagination->page = $page;
			$pagination->limit = $limit;
			$pagination->text = $this->language->get('text_pagination');

            if($all)
            {
                $pagination->url = $this->url->link('product/category', $url . '&page={page}');
            }
            else
            {
                $pagination->url = $this->url->link('product/category', 'path=' . $this->request->get['path'] . $url . '&page={page}');
            }

		
			$this->data['pagination'] = $pagination->render();
		
			$this->data['sort'] = $sort;
			$this->data['order'] = $order;
			$this->data['limit'] = $limit;
		
			$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/product/category.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/product/category.tpl';
			} else {
				$this->template = 'default/template/product/category.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
				
			$this->response->setOutput($this->render());										
    	} else {
			$url = '';
			
			if (isset($this->request->get['path'])) {
				$url .= '&path=' . $this->request->get['path'];
			}
			
			if (isset($this->request->get['filter'])) {
				$url .= '&filter=' . $this->request->get['filter'];
			}
												
			if (isset($this->request->get['sort'])) {
				$url .= '&sort=' . $this->request->get['sort'];
			}	

			if (isset($this->request->get['order'])) {
				$url .= '&order=' . $this->request->get['order'];
			}
				
			if (isset($this->request->get['page'])) {
				$url .= '&page=' . $this->request->get['page'];
			}
						
			if (isset($this->request->get['limit'])) {
				$url .= '&limit=' . $this->request->get['limit'];
			}
						
			$this->data['breadcrumbs'][] = array(
				'text'      => $this->language->get('text_error'),
				'href'      => $this->url->link('product/category', $url),
				'separator' => $this->language->get('text_separator')
			);
				
			$this->document->setTitle($this->language->get('text_error'));

      		$this->data['heading_title'] = $this->language->get('text_error');

      		$this->data['text_error'] = $this->language->get('text_error');

      		$this->data['button_continue'] = $this->language->get('button_continue');

      		$this->data['continue'] = $this->url->link('common/home');

			if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/error/not_found.tpl')) {
				$this->template = $this->config->get('config_template') . '/template/error/not_found.tpl';
			} else {
				$this->template = 'default/template/error/not_found.tpl';
			}
			
			$this->children = array(
				'common/column_left',
				'common/column_right',
				'common/content_top',
				'common/content_bottom',
				'common/footer',
				'common/header'
			);
					
			$this->response->setOutput($this->render());
		}
  	}
}
?>