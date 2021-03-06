<?php
class ControllerIntegrationCompareSklepy24 extends Controller {
	public function index() {
		if ($this->config->get('sklepy24_status')) {
			if ($this->request->get['password'] == $this->config->get('sklepy24_private_key')) {
				$output  = '<?xml version="1.0" encoding="UTF-8" ?>';
				$output .= '<products xmlns="http://www.sklepy24.pl" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" date="' . date("Y-m-d") . '" xsi:schemaLocation=" http://www.sklepy24.pl http://www.sklepy24.pl/formats/products.xsd">';

				$this->load->model('catalog/category');

				$this->load->model('catalog/product');

				$this->load->model('tool/image');

				$products = $this->model_catalog_product->getProducts();

				foreach ($products as $product) {
					$product = $this->clear($product);

                    if ((float)$product['special'])
                        $price = $this->currency->format($this->tax->calculate($product['special'], $product['tax_class_id']), 'PLN', false, false);
                    else
                        $price = $this->currency->format($this->tax->calculate($product['price'], $product['tax_class_id']), 'PLN', false, false);

					$output .= '<product id="' . $product['product_id'] . '">';
					$output .= '<name><![CDATA[' . $product['name'] . ']]></name>';
					$output .= '<url><![CDATA[' . $this->url->link('product/product', 'product_id=' . $product['product_id']) . ']]></url>';
					$output .= '<brand><![CDATA[' . $product['manufacturer'] . ']]></brand>';

					$categories = $this->model_catalog_product->getCategories($product['product_id']);

					foreach ($categories as $category) {
						$path = $this->getPath($category['category_id']);
						
						if ($path) {
							$string = '';
							
							foreach (explode('_', $path) as $path_id) {
								$category_info = $this->model_catalog_category->getCategory($path_id);
								
								if ($category_info) {
									if (!$string) {
										$string = $category_info['name'];
									} else {
										$string .= ' / ' . $category_info['name'];
									}
								}
							}
						}
					}

					$output .= '<categories><category><![CDATA[' . $string . ']]></category></categories>';

					if ($product['image'])
						$output .= '<photo><![CDATA[' . $this->model_tool_image->resize($product['image'], 500, 500) . ']]></photo>';

					$output .= '<description><![CDATA[' . $product['description'] . ']]></description>';
					$output .= '<price>' . $price . '</price>';
					$output .= '</product>';
				}

				$output .= '</products>';

				$this->load->model('integration_compare/compareprices');
				$this->model_integration_compare_compareprices->saveLastDate('sklepy24');

				$this->response->addHeader('Content-Type: application/rss+xml');
				$this->response->setOutput($output);
			}
		}
	}

	protected function getPath($parent_id, $current_path = '') {
		$category_info = $this->model_catalog_category->getCategory($parent_id);
	
		if ($category_info) {
			if (!$current_path) {
				$new_path = $category_info['category_id'];
			} else {
				$new_path = $category_info['category_id'] . '_' . $current_path;
			}	
		
			$path = $this->getPath($category_info['parent_id'], $new_path);
					
			if ($path) {
				return $path;
			} else {
				return $new_path;
			}
		}
	}

	protected function clear($data) {
		foreach ($data as $key => $value) {
			$data[$key] = html_entity_decode($value, ENT_QUOTES, 'UTF-8');
			$data[$key] = strip_tags($data[$key]);
			$data[$key] = preg_replace(array('/(\s){1,100}/us', '/&nbsp;/', '/\r\n/', '/\n\r/', '/\n/'), array(' ', ' ', '', '', ''), $data[$key]);
			$data[$key] = trim($data[$key]);
		}

		return $data;
	}
}
?>