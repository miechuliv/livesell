<?php echo $header; ?>
<script>
    function showMassEdit()
    {
        $('#mass-edit').toggle( "slow", function() {
            // Animation complete.
        });
    }

    function showDodatkowe()
    {
        $('#dodatkowe-wyszukiwanie').toggle( "slow", function() {
            // Animation complete.
        });
    }

    function showMassSpecial()
    {
        $('#mass-special').toggle( "slow", function() {
            // Animation complete.
        });
    }
</script>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('#form').attr('action', '<?php echo $copy; ?>'); $('#form').submit();" class="button"><?php echo $button_copy; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list" style="table-layout:fixed;">
          <thead>
            <tr>
              <td  style="text-align: center;width:44px;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="center"><?php echo $column_image; ?></td>
              <td  style="width:125px" class="left filter_name"><?php if ($sort == 'pd.name') { ?>
                <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                <?php } ?></td>
                <td class="left"><?php if ($sort == 'p.price') { ?>
                    <a href="<?php echo $sort_price; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_price; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_price; ?>"><?php echo $column_price; ?></a>
                    <?php } ?></td>
                <td class="left"><?php if ($sort == 'p.model') { ?>
                    <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                    <?php } ?></td>



                <td >Stan magazynowy</td>
              <td style="width:125px" class="right">Kategoria</td>
              <td style="width:125px" class="right">Rodzaj produktu: </td>
                <td class="left"><?php if ($sort == 'p.status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>

                <td class="right">Akcja</td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td></td>
              <td class="filter_name"><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" /></td>
                <td align="left"><input type="text" name="filter_price" value="<?php echo $filter_price; ?>" size="8"/></td>
                <td><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" /></td>

               <td><input type="text" name="filter_quantity" value="<?php echo $filter_quantity; ?>" /></td>
                <td align="right">
                    <select name="filter_category_id" style="width:115px;">
                        <option></option>
                        <?php foreach($categories as $category){ ?>
                                <option value="<?php echo $category['category_id'] ?>"  <?php if($filter_category_id==$category['category_id']){ echo 'selected="selected"'; } ?> ><?php echo $category['name']; ?></option>
                        <?php } ?>
                    </select>
                </td>
                <td align="right">


                    <select style="display: none" name="retailer" style="width:115px;">
                        <option></option>
                        <?php if(!empty($retailers)){ ?>
                        <?php foreach($retailers as $retailerow){ ?>
                        <option value="<?php echo $retailerow['retailer_id'] ?>"  <?php if($filter_retailer==$retailerow['retailer_id']){ echo 'selected="selected"'; } ?> ><?php echo $retailerow['retailer_name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                    </select>

                    <select  name="filter_campaign_status" style="width:115px;">
                        <option></option>
                        <option value="basic" <?php if($filter_campaign_status == 'basic'){ echo 'selected="selected"'; } ?>  ><?php echo $this->language->get('text_basic'); ?></option>
                        <option value="current" <?php if($filter_campaign_status == 'current'){ echo 'selected="selected"'; } ?>  ><?php echo $this->language->get('text_current'); ?></option>
                        <option value="future" <?php if($filter_campaign_status == 'future'){ echo 'selected="selected"'; } ?>  ><?php echo $this->language->get('text_future'); ?></option>
                        <option value="ended" <?php if($filter_campaign_status == 'ended'){ echo 'selected="selected"'; } ?>  ><?php echo $this->language->get('text_ended'); ?></option>
                    </select>
                </td>
                <td><select name="filter_status">
                  <option value="*"></option>
                  <?php if ($filter_status) { ?>
                  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_enabled; ?></option>
                  <?php } ?>
                  <?php if (!is_null($filter_status) && !$filter_status) { ?>
                  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_disabled; ?></option>
                  <?php } ?>
                </select></td>


              <td align="right"><a onclick="filter(false);" class="button filtr"><?php echo $button_filter; ?></a></td>
            </tr>
            <!-- dodatkowe wyszukiwanie np: opcje, atrybuty -->
            <tr>
                <td colspan="10" onclick="showDodatkowe()">
                    <a class="button" >Dodatkowe parametry wyszukiwania</a>
                </td>
            </tr>
            <tr style="display:none;" id="dodatkowe-wyszukiwanie">
                <td colspan="10">
                    <table>
                        <tr class="filter">
                            <!-- nagłówkie -->
                            <td>Wyszukiwanie po opcji:</td>
                            <td>Wartość:</td>
                            <td>Wyszukiwanie po atrybucie:</td>
                            <td>Wartość:</td>
                        </tr>
                        <tr class="filter">
                            <td>
                                <select name="option_name" onchange="getOptionValues()">
                                    <option></option>
                                    <?php foreach($options as $option){ ?>
                                    <option value="<?php echo $option['option_id']; ?>" <?php if($filter_option==$option['option_id']){ echo 'selected="selected"'; };?> ><?php echo $option['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <script type="text/javascript">
                             function getOptionValues(){
                                 $.ajax({
                                     url: 'index.php?route=catalog/product/optionValuesAjax&token=<?php echo $token; ?>',
                                     dataType: 'json',
                                     type: 'post',
                                     data: {
                                         option_id: $('select[name="option_name"] option:selected').val()
                                     },
                                     success: function(json) {
                                         html = '';
                                         $.each(json,function(key,elem){
                                             html += '<option value="'+elem['option_value_id']+'" >'+elem['name']+'</option>';
                                         });

                                         $('select[name="option_value"]').html(html);
                                     }
                                 });
                             }

                            </script>
                            <td>

                                <select name="option_value">
                                    <?php if(isset($filter_option_values)){ ?>
                                    <?php foreach($filter_option_values as $value){ ?>
                                    <option value="<?php echo $value['option_value_id']; ?>" <?php if($filter_option_value==$value['option_value_id']){ echo 'selected="selected"'; };?> ><?php echo $value['name']; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <select name="attribute_name" onchange="getAttributeValues(this)">
                                    <option></option>
                                    <?php foreach($attributes as $attribute){ ?>
                                    <option value="<?php echo $attribute['attribute_id']; ?>" <?php if($filter_attribute==$attribute['attribute_id']){ echo 'selected="selected"'; };?> ><?php echo $attribute['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <script type="text/javascript">
                                function getAttributeValues(){
                                    $.ajax({
                                        url: 'index.php?route=catalog/product/attributeValuesAjax&token=<?php echo $token; ?>',
                                        dataType: 'json',
                                        type: 'post',
                                        data: {
                                            attribute_id: $('select[name="attribute_name"] option:selected').val()
                                        },
                                        success: function(json) {
                                            html = '';
                                            $.each(json,function(key,elem){
                                                html += '<option value="'+elem+'" >'+elem+'</option>';
                                            });

                                            $('select[name="attribute_value"]').html(html);
                                        }
                                    });
                                }

                            </script>
                            <td>
                                <select name="attribute_value">
                                    <?php if(isset($filter_attribute_values)){ ?>
                                    <?php foreach($filter_attribute_values as $value){ ?>
                                    <option value="<?php echo $value; ?>" <?php if($filter_attribute_value==$value){ echo 'selected="selected"'; };?> ><?php echo $value; ?></option>
                                    <?php } ?>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </td>


            </tr>

            <tr>
                <td colspan="10" onclick="showMassEdit()">
                    <a class="button" >Pokaż panel masowej edycji</a>
                </td>
            </tr>
            <!-- masowa edycja -->
            <tr style="display:none;" id="mass-edit">
                <td colspan="10">
                    <table>
                        <tr class="filter">
               
                            <td>
                                Cena
                            </td>
                            <td>
                                Cena procentowo (+/-)
                            </td>

                            <td>
                                Czas dostawy (dni)
                            </td>
                            <td>
                                Koszt dostawy
                            </td>

                            <td>
                                Stan magazynowy
                            </td>
                            <td >Czy ma pojawiać się w porównywarkach np: Nokaut.pl?</td>
                            <td >Dodaj do kategorii</td>
                            <td >Usuń z kategorii</td>
                            <td>
                                Dodaj / usuń z Google Merchant
                            </td>
                            <td>

                            </td>
                        </tr>
                        <tr class="filter">
             
                            <td>
                                <input style="width:100px;" type="text" name="mass_price" value="<?php echo $mass_price; ?>" />

                            </td>
                            <td>
                                <input style="width:100px;" type="text" name="mass_price_percentage" value="<?php echo $mass_price_percentage; ?>" />
                            </td>

                            <td>
                                <input type="text" name="mass_delivery_time" value="<?php echo $mass_delivery_time; ?>" />
                            </td>
                            <td>
                                <input type="text" name="mass_delivery_price" value="<?php echo $mass_delivery_price; ?>" />
                            </td>

                            <td>
                                <input type="text" name="mass_quantity" value="<?php echo $mass_quantity; ?>" />
                            </td>

                            <td>
                                <select name="mass_feed" >
                                    <option></option>
                                    <option value="1" >Tak</option>
                                    <option value="0" >Nie</option>
                                </select>
                            </td>

                            <td>
                                <select name="mass_category"  >
                                    <option></option>
                                    <?php foreach($categories as $category){ ?>
                                    <option value="<?php echo $category['category_id'] ?>"   ><?php echo $category['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>

                            <td>
                                <select name="mass_category_remove"  >
                                    <option></option>
                                    <?php foreach($categories as $category){ ?>
                                    <option value="<?php echo $category['category_id'] ?>"   ><?php echo $category['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                            <td>
                                <select name="mass_google_merchant">
                                    <option></option>
                                    <option value="1">Dodaj</option>
                                    <option value="2">Uaktualnij</option>
                                    <option value="0">Usuń</option>
                                </select>
                            </td>

                            <td align="right">
                                <a onclick="filter(true);" class="button filtr">Zapisz</a>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr>
                <td colspan="10" onclick="showMassSpecial()">
                    <a class="button" >Dodaj masową promocję</a>
                </td>
            </tr>
            <tr style="display:none;" id="mass-special">
                <td colspan="10">
                    <table id="special" class="list">
                        <thead>
                        <tr class="filter">
                            <td class="left"><?php echo $entry_customer_group; ?></td>
                            <td class="left"><?php echo $entry_priority; ?></td>
                            <td class="left"><?php echo $entry_price; ?></td>
                            <td class="left"><?php echo $entry_date_start; ?></td>
                            <td class="left"><?php echo $entry_date_end; ?></td>
                            <td class="left"><a onclick="saveMassSpecial();" class="button filtr2">Zapisz</a>
                                <a onclick="saveDeleteSpecial();" class="button bigger">Usuń wszystkie promocje dla wybranych</a></td>
                        </tr>
                        </thead>
                        <?php $special_row = 0; ?>

                        <tfoot>
                        <tr class="filter">
                            <td colspan="5"></td>
                            <td class="left"><a onclick="addSpecial();" class="button"><?php echo $button_add_special; ?></a></td>
                        </tr>
                        </tfoot>
                    </table>
                    <script type="text/javascript"><!--
                        var special_row = <?php echo $special_row; ?>;

                        function addSpecial() {
                            html  = '<tbody id="special-row' + special_row + '">';
                            html += '  <tr>';
                            html += '    <td class="left"><select name="product_special[' + special_row + '][customer_group_id]">';
                        <?php foreach ($customer_groups as $customer_group) { ?>
                                html += '      <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>';
                            <?php } ?>
                            html += '    </select></td>';
                            html += '    <td class="right"><input type="text" name="product_special[' + special_row + '][priority]" value="" size="2" /></td>';
                            html += '    <td class="right"><input type="text" name="product_special[' + special_row + '][price]" value="" /></td>';
                            html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_start]" value="" class="date" /></td>';
                            html += '    <td class="left"><input type="text" name="product_special[' + special_row + '][date_end]" value="" class="date" /></td>';
                            html += '    <td class="left"><a onclick="$(\'#special-row' + special_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
                            html += '  </tr>';
                            html += '</tbody>';

                            $('#special tfoot').before(html);

                            $('#special-row' + special_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

                            special_row++;
                        }

                        function saveMassSpecial()
                        {
                            $.ajax({
                                url: 'index.php?route=catalog/product/saveMassSpecial&token=<?php echo $token; ?>',
                                dataType: 'text',
                                type: 'post',
                                data: $('#mass-special select, #mass-special input, input[name="selected[]"]:checked '),
                                success: function(text) {
                                    if(text == 'ok')
                                    {
                                        alert('Dodano promocję do wybranych produktów');
                                        location.reload();
                                    }
                                    if(text == 'fail')
                                    {
                                        alert('Nie udało się dodać promocji / nie wybrano żadnych produktów');
                                    }
                                }
                            });
                        }

                        function saveDeleteSpecial()
                        {
                            $.ajax({
                                url: 'index.php?route=catalog/product/saveDeleteSpecial&token=<?php echo $token; ?>',
                                dataType: 'text',
                                type: 'post',
                                data: $('input[name="selected[]"]:checked '),
                                success: function(text) {
                                    if(text == 'ok')
                                    {
                                        alert('Usunieto promocję wybranych produktów');
                                        location.reload();
                                    }
                                    if(text == 'fail')
                                    {
                                        alert('Nie udało się usunąć promocji / nie wybrano żadnych produktów');
                                    }
                                }
                            });
                        }
                        //--></script>
                </td>
            </tr>


            <?php if ($products) { ?>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($product['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $product['product_id']; ?>" />
                <?php } ?></td>
              <td class="center"><img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>" style="padding: 1px; border: 1px solid #DDDDDD;" /></td>
              <td class="left filter_name" ><?php echo $product['name']; ?></td>
                <td class="left"><?php if ($product['special']) { ?>
                    <span style="text-decoration: line-through;"><?php echo $product['price']; ?></span><br/>
                    <span style="color: #b00;"><?php echo $product['special']; ?></span>
                    <?php } else { ?>
                    <?php echo $product['price']; ?>
                    <?php } ?></td>
              <td class="left"><?php echo $product['model']; ?></td>
                <td class="right"><?php if ($product['quantity'] <= 0) { ?>
                    <span style="color: #FF0000;"><?php echo $product['quantity']; ?></span>
                    <?php } elseif ($product['quantity'] <= 5) { ?>
                    <span style="color: #FFA500;"><?php echo $product['quantity']; ?></span>
                    <?php } else { ?>
                    <span style="color: #008000;"><?php echo $product['quantity']; ?></span>
                    <?php } ?></td>


                <td class="left"><?php echo $product['category_name']; ?></td>
                <td><?php echo $product['campaign_status']; ?></td>
                <td class="left"><?php echo $product['status']; ?></td>

              <td id="action-td" class="right"><?php foreach ($product['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <br>[ <a href="index.php?route=ebay/debayproduct&product_id=<?php echo $product['product_id'];?>&token=<?php echo $_GET['token'];?>">Wystaw na Ebay</a> ]
                <br>[ <a href="index.php?route=allegro/product&product_id=<?php echo $product['product_id'];?>&token=<?php echo $_GET['token'];?>">Wystaw na Allegro</a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter(mass_edit) {
	url = 'index.php?route=catalog/product&token=<?php echo $token; ?>';

    if(mass_edit)
    {
        url += '&mass_save=1';

        var mass_price = $('input[name=\'mass_price\']').attr('value');

        if (mass_price) {
            url += '&mass_price=' + encodeURIComponent(mass_price);
        }

        var mass_price_percentage = $('input[name=\'mass_price_percentage\']').attr('value');

        if (mass_price_percentage) {
            url += '&mass_price_percentage=' + encodeURIComponent(mass_price_percentage);
        }

        var selected = $('input[name=\'selected[]\']:checked');


        $(selected).each(function(key,elem){

            var id = $(elem).val();



            if (id) {
                url += '&selected[]=' + encodeURIComponent(id);
            }
        });

        var mass_google_merchant = $('select[name=\'mass_google_merchant\'] option:selected').attr('value');

        if (mass_google_merchant) {
            url += '&mass_google_merchant=' + encodeURIComponent(mass_google_merchant);
        }

        var mass_delivery_time = $('input[name=\'mass_delivery_time\']').attr('value');

        if (mass_delivery_time) {
            url += '&mass_delivery_time=' + encodeURIComponent(mass_delivery_time);
        }

        var mass_delivery_price = $('input[name=\'mass_delivery_price\']').attr('value');

        if (mass_delivery_price) {
            url += '&mass_delivery_price=' + encodeURIComponent(mass_delivery_price);
        }


        var mass_quantity = $('input[name=\'mass_quantity\']').attr('value');

        if (mass_quantity) {
            url += '&mass_quantity=' + encodeURIComponent(mass_quantity);
        }

        var mass_feed = $('select[name=\'mass_feed\'] option:selected').attr('value');

        if (mass_feed) {
            url += '&mass_feed=' + encodeURIComponent(mass_feed);
        }

        var mass_category = $('select[name=\'mass_category\'] option:selected').attr('value');

        if (mass_category) {
            url += '&mass_category=' + encodeURIComponent(mass_category);
        }

        var mass_category_remove = $('select[name=\'mass_category_remove\'] option:selected').attr('value');

        if (mass_category_remove) {
            url += '&mass_category_remove=' + encodeURIComponent(mass_category_remove);
        }
    }

    var filter_retailer = $('select[name=\'retailer\'] option:selected').val();


    if (filter_retailer) {
        url += '&filter_retailer=' + encodeURIComponent(filter_retailer);
    }

    var filter_campaign_status = $('select[name=\'filter_campaign_status\'] option:selected').val();


    if (filter_campaign_status) {
        url += '&filter_campaign_status=' + encodeURIComponent(filter_campaign_status);
    }
	
	var filter_name = $('input[name=\'filter_name\']').attr('value');
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}

    var filter_price = $('input[name=\'filter_price\']').attr('value');

    if (filter_price) {
        url += '&filter_price=' + encodeURIComponent(filter_price);
    }

    var filter_model = $('input[name=\'filter_model\']').attr('value');

    if (filter_model) {
        url += '&filter_model=' + encodeURIComponent(filter_model);
    }

    var filter_quantity = $('input[name=\'filter_quantity\']').attr('value');

    if (filter_quantity) {
        url += '&filter_quantity=' + encodeURIComponent(filter_quantity);
    }

    var filter_status = $('select[name=\'filter_status\'] option:selected').attr('value');

    if (filter_status) {
        url += '&filter_status=' + encodeURIComponent(filter_status);
    }


    var filter_category_id = $('select[name=\'filter_category_id\'] option:selected').attr('value');

    if (filter_category_id) {
        url += '&filter_category_id=' + encodeURIComponent(filter_category_id);
    }

    /// opcje i atrybuty
    var filter_attribute = $('select[name=\'attribute_name\'] option:selected').attr('value');

    if (filter_attribute) {
        url += '&filter_attribute=' + encodeURIComponent(filter_attribute);
    }

    var filter_attribute_value = $('select[name=\'attribute_value\'] option:selected').attr('value');

    if (filter_attribute_value) {
        url += '&filter_attribute_value=' + encodeURIComponent(filter_attribute_value);
    }


    var filter_option = $('select[name=\'option_name\'] option:selected').attr('value');

    if (filter_option) {
        url += '&filter_option=' + encodeURIComponent(filter_option);
    }

    var filter_option_value = $('select[name=\'option_value\'] option:selected').attr('value');

    if (filter_option_value) {
        url += '&filter_option_value=' + encodeURIComponent(filter_option_value);
    }


	location = url;
}
//--></script> 
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter(false);
	}
});
//--></script> 
<script type="text/javascript"><!--
$('input[name=\'filter_name\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_name\']').val(ui.item.label);
						
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});

$('input[name=\'filter_model\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.model,
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('input[name=\'filter_model\']').val(ui.item.label);
						
		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script> 
<?php echo $footer; ?>