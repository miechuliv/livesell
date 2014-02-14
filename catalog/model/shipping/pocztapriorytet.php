<?php
class ModelShippingpocztapriorytet extends Model {
	function getQuote($address) {
		$this->load->language('shipping/pocztapriorytet');

		
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

        $c = $this->config->get('pocztapriorytet_allowed_zones');
		if (!$c OR empty($c)) {
			$status = false;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}



        $rate = false;

        if($status)
        {
            foreach($c as $zone)
            {
                if($zone['zone_id'] == $query->row['geo_zone_id'])
                {
                    $rate = $zone['weight'];
                }
            }
        }


		$method_data = array();
	
		if ($status AND $rate) {
			$cost = 0;
			$weight = $this->cart->getWeight();
			
			$rates = explode(',', $rate);
			
			foreach ($rates as $rate) {
  				$data = explode(':', $rate);
  					
				if ($data[0] >= $weight) {
					if (isset($data[1])) {
    					$cost = $data[1];
					}
					
   					break;
  				}
			}
			
			$quote_data = array();
			
			if ((float)$cost) {
				$quote_data['pocztapriorytet'] = array(
        			'code'         => 'pocztapriorytet.pocztapriorytet',
        			'title'        => $this->language->get('text_title') . '  (' . $this->language->get('text_weight') . ' ' . $this->weight->format($weight, $this->config->get('config_weight_class_id')) . ')',
        			'cost'         => $cost,
        			'tax_class_id' => $this->config->get('pocztapriorytet_tax_class_id'),
					'text'         => $this->currency->format($this->tax->calculate($cost, $this->config->get('pocztapriorytet_tax_class_id'), $this->config->get('config_tax')))
      			);
				
      			$method_data = array(
        			'code'       => 'pocztapriorytet',
        			'title'      => $this->language->get('text_title'),
        			'quote'      => $quote_data,
					'sort_order' => $this->config->get('pocztapriorytet_sort_order'),
        			'error'      => false
      			);
			}
		}
	
		return $method_data;
	}
}
?>