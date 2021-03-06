<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="view/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<link type="text/css" href="view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" rel="stylesheet" />
<script type="text/javascript" src="view/javascript/jquery/tabs.js"></script>
<script type="text/javascript" src="view/javascript/jquery/superfish/js/superfish.js"></script>
<script type="text/javascript" src="view/javascript/common.js"></script>

<script type="text/javascript" src="view/javascript/project_specyfic/marki.js"></script>


<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<script type="text/javascript">
//-----------------------------------------
// Confirm Actions (delete, uninstall)
//-----------------------------------------
var i;
$(document).ready(function(){
    // Confirm Delete

    i = $('#licznik').html();



    $('.delete_car').live('click',function(){
        $(this).parent().remove();
    })

    $('#add_car').click(function(){



        var make_id = $('#make option:selected').val();
        var model_id = $('#model option:selected').val();
        var type_id = $('#type option:selected').val();

        var make_name = $('#make option:selected').html();
        var model_name = $('#model option:selected').html();
        var type_name = $('#type option:selected').html();

        if(make_name=='' || make_name=='Marka')
        {
             return false
        }

        if(model_name=='Model')
        {
            model_id = NULL;
            model_name = '';
        }

        if(type_name=='Typ')
        {
            type_id = NULL;
            type_name = '';
        }

        html='<tr>';
        html+='<td>';
        html+='<input type="hidden" name="cars['+i+']" value="'+make_id+'_'+model_id+'_'+type_id+'"  />  ';
        html+='</td>';
        html+='<td>';
        html+='Marka: '+make_name+' Model: '+model_name+' Typ: '+type_name;
        html+='</td>'
        html+='<td class="delete_car" >Usuń</td>';
        html+='</tr>';

        $('#added_cars').append(html);

        i++;

    });

    $('#make').change(function(){

                var make_id = $('#make option:selected').val();

                if(make_id=='' || make_id=='Marka')
                {
                     return false;
                }

                $.ajax({
                    type     : "POST",
                    url      : "index.php?route=tool/cars/getModelsAjax&token=<?php echo $this->session->data['token']; ?>",
                    dataType: 'json',
                    data     : {
                        make_id : make_id

                    },
                    success : function(data) {

                        var html='';

                        // var obj = jQuery.parseJSON(data);


                        html+='<option value="" >Model</option>';

                        jQuery.each( data['output'] , function(index, value) {

                            html+='<option value="'+value['model_id']+'" >'+value['model_name']+'</option>';
                        });

                        $('#model').html(html);

                    },
                    complete : function(r) {
                        //ten fragment wykona się po ZAKONCZENIU połączenia
                        //"r" to przykładowa nazwa zmiennej, która zawiera dane zwrócone z serwera

                    },
                    error:    function(error) {
                        //ten fragment wykona się w przypadku BŁĘDU

                    }
                });

            }






    )

    $('#model').change(function(){

                var model_id = $('#model option:selected').val();

                if(model_id=='' || model_id=='Model')
                {
                    return false;
                }

                $.ajax({
                    type     : "POST",
                    url      : "index.php?route=tool/cars/getTypesAjax&token=<?php echo $this->session->data['token']; ?>",
                    dataType: 'json',
                    data     : {
                        model_id : model_id

                    },
                    success : function(data) {

                        var html='';

                        html+='<option value="" >Typ</option>';

                        // var obj = jQuery.parseJSON(data);

                        jQuery.each( data['output'] , function(index, value) {

                            html+='<option value="'+value['type_id']+'" >'+value['type_name']+'</option>';
                        });

                        $('#type').html(html);

                    },
                    complete : function(r) {
                        //ten fragment wykona się po ZAKONCZENIU połączenia
                        //"r" to przykładowa nazwa zmiennej, która zawiera dane zwrócone z serwera

                    },
                    error:    function(error) {
                        //ten fragment wykona się w przypadku BŁĘDU

                    }
                });

            }






    )


    $('#form').submit(function(){
        if ($(this).attr('action').indexOf('delete',1) != -1) {
            if (!confirm('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });

    // Confirm Uninstall
    $('a').click(function(){
        if ($(this).attr('href') != null && $(this).attr('href').indexOf('uninstall',1) != -1) {
            if (!confirm ('<?php echo $text_confirm; ?>')) {
                return false;
            }
        }
    });
    $('#8floatactive').click(function(){
        if( $('#8floatactive').is(':checked') ) {
            $('#8float').removeAttr('disabled');
            $('#6-float').attr('disabled', true);
            $('#7-float').attr('disabled', true);
            $('#5-int').val('<?php echo $_SESSION['product_quantity']; ?>') ;
            $('#8float').val('<?php echo $_SESSION['product_specials']; ?>') ;
        } else {
            $('#6-float').removeAttr('disabled');
            $('#7-float').removeAttr('disabled');
            $('#5-int').val('1') ;
        }
    });
    $('#allegrotemplate').change(function(){
        $('#showtemplate').attr('href','<?php echo HTTP_SERVER;?>index.php?route=allegro/template&token=<?php echo $this->session->data['token'];?>&name='+$('select[name=allegrotemplate] option:selected').attr('title')+'&product_id=<?php echo $_GET['product_id'] ; ?>') ;
    });

function floatactive(productid) {
    if( $('#'+productid+'-8floatactive').is(':checked') ) {
        $('#'+productid+'-8float').removeAttr('disabled');
        $('#'+productid+'-6-float').attr('disabled', true);
        $('#'+productid+'-7-float').attr('disabled', true);
        $('#'+productid+'-5-int').val('<?php echo $_SESSION['product_quantity']; ?>') ;
        $('#'+productid+'-8float').val('<?php echo $_SESSION['product_specials']; ?>') ;
    } else {
        $('#'+productid+'-6-float').removeAttr('disabled');
        $('#'+productid+'-7-float').removeAttr('disabled');
        $('#'+productid+'-5-int').val('1') ;
    }
}
function allegrotemplate(productid) {
    nameselected = '#'+productid+'-allegrotemplate option:selected' ;
    $('#'+productid+'-showtemplate').attr('href','<?php echo HTTP_SERVER;?>index.php?route=allegro/template&token=<?php echo $this->session->data['token'];?>&name='+$(nameselected).attr('title')+'&product_id='+productid) ;
}
function showdesc(divid) {
    $('#'+divid).css('display','block');
}
function hidedesc(divid) {
    $('#'+divid).css('display','none');
}
});
</script>
<script type="text/javascript" src="view/javascript/stringToSlug/jquery.stringToSlug.min.js"></script>
	<script type="text/javascript">		
	$(document).ready( function() {
		$("#title-slug").stringToSlug({
			setEvents: 'keyup keydown blur',
			getPut: '#slug-result',
		});
	});
	</script>
</head>
<body>
<div id="container">
<div id="header">

  <div class="div1">
    <div class="div2"><span style="float:left;line-height:30px;font-size:10px;">ADMINISTRACJA</span></div>
    <?php if ($logged) { ?>
    <div class="div3"><img src="view/image/lock.png" alt="" style="position: relative; top: 3px;" />&nbsp;<?php echo $logged; ?></div>
    <?php } ?>
  </div>
  <?php if ($logged) { ?>
  <div id="menu">
    <ul class="left" style="display: none;">
      <li id="dashboard"><a href="<?php echo $home; ?>" class="top"><?php echo $text_dashboard; ?></a></li>
      <li id="catalog"><a class="top"><?php echo $text_catalog; ?></a>
        <ul>
          <li><a href="<?php echo $category; ?>"><?php echo $text_category; ?></a></li>
          <li><a href="<?php echo $product; ?>"><?php echo $text_product; ?></a></li>
        <!--  <li><a href="<?php echo $filter; ?>"><?php echo $text_filter; ?></a></li> -->
       <!--   <li><a class="parent"><?php echo $text_attribute; ?></a>
            <ul>
              <li><a href="<?php echo $attribute; ?>"><?php echo $text_attribute; ?></a></li>
              <li><a href="<?php echo $attribute_group; ?>"><?php echo $text_attribute_group; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $option; ?>"><?php echo $text_option; ?></a></li> -->
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
       <!--   <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li> -->
          <li><a href="<?php echo $review; ?>"><?php echo $text_review; ?></a></li>
          <li><a href="<?php echo $information; ?>"><?php echo $text_information; ?></a></li>
        </ul>
      </li>
      <li id="extension"><a class="top"><?php echo $text_extension; ?></a>
        <ul>
        <!--  <li><a href="<?php echo $module; ?>"><?php echo $text_module; ?></a></li> -->
          <li><a href="<?php echo $shipping; ?>"><?php echo $text_shipping; ?></a></li>
        <!--  <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li> -->
        <!--  <li><a href="<?php echo $total; ?>"><?php echo $text_total; ?></a></li>
          <li><a href="<?php echo $feed; ?>"><?php echo $text_feed; ?></a></li> -->
          <li><a href="<?php echo $compare_prices; ?>"><?php echo $text_compare_prices; ?></a></li>
        </ul>
      </li>
      <li id="sale"><a class="top"><?php echo $text_sale; ?></a>
        <ul>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li><a class="parent"><?php echo $text_customer; ?></a>
            <ul>
              <li><a href="<?php echo $customer; ?>"><?php echo $text_customer; ?></a></li>
              <li><a href="<?php echo $customer_group; ?>"><?php echo $text_customer_group; ?></a></li>
              <li><a href="<?php echo $customer_ban_ip; ?>"><?php echo $text_customer_ban_ip; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $coupon; ?>"><?php echo $text_coupon; ?></a></li>
          <li><a class="parent"><?php echo $text_voucher; ?></a>
            <ul>
              <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
              <li><a href="<?php echo $voucher_theme; ?>"><?php echo $text_voucher_theme; ?></a></li>
            </ul>
          </li>
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
        </ul>
      </li>
      <li id="system"><a class="top"><?php echo $text_system; ?></a>
        <ul>
          <li><a href="<?php echo $setting; ?>"><?php echo $text_setting; ?></a></li>
          <li><a class="parent"><?php echo $text_design; ?></a>
            <ul>
            <!--  <li><a href="<?php echo $layout; ?>"><?php echo $text_layout; ?></a></li> -->
              <li><a href="<?php echo $banner; ?>"><?php echo $text_banner; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_users; ?></a>
            <ul>
              <li><a href="<?php echo $user; ?>"><?php echo $text_user; ?></a></li>
              <li><a href="<?php echo $user_group; ?>"><?php echo $text_user_group; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_localisation; ?></a>
            <ul>
              <li><a href="<?php echo $language; ?>"><?php echo $text_language; ?></a></li>
              <li><a href="<?php echo $currency; ?>"><?php echo $text_currency; ?></a></li>
              <li><a href="<?php echo $stock_status; ?>"><?php echo $text_stock_status; ?></a></li>
              <li><a href="<?php echo $order_status; ?>"><?php echo $text_order_status; ?></a></li>
              <li><a class="parent"><?php echo $text_return; ?></a>
                <ul>
                  <li><a href="<?php echo $return_status; ?>"><?php echo $text_return_status; ?></a></li>
                  <li><a href="<?php echo $return_action; ?>"><?php echo $text_return_action; ?></a></li>
                  <li><a href="<?php echo $return_reason; ?>"><?php echo $text_return_reason; ?></a></li>
                </ul>
              </li>
              <li><a href="<?php echo $country; ?>"><?php echo $text_country; ?></a></li>
              <li><a href="<?php echo $zone; ?>"><?php echo $text_zone; ?></a></li>
              <li><a href="<?php echo $geo_zone; ?>"><?php echo $text_geo_zone; ?></a></li>
              <li><a class="parent"><?php echo $text_tax; ?></a>
                <ul>
                  <li><a href="<?php echo $tax_class; ?>"><?php echo $text_tax_class; ?></a></li>
                  <li><a href="<?php echo $tax_rate; ?>"><?php echo $text_tax_rate; ?></a></li>
                </ul>
              </li>
              <li><a href="<?php echo $length_class; ?>"><?php echo $text_length_class; ?></a></li>
              <li><a href="<?php echo $weight_class; ?>"><?php echo $text_weight_class; ?></a></li>
            </ul>
          </li>
      <!--    <li><a href="<?php echo $error_log; ?>"><?php echo $text_error_log; ?></a></li>
          <li><a href="<?php echo $backup; ?>"><?php echo $text_backup; ?></a></li> -->
          <li><a href="<?php echo $generate_sitemap; ?>"><?php echo $text_generate_sitemap; ?></a></li>
        </ul>
      </li>
      <li id="reports"><a class="top"><?php echo $text_reports; ?></a>
        <ul>
          <li><a class="parent"><?php echo $text_sale; ?></a>
            <ul>
              <li><a href="<?php echo $report_sale_order; ?>"><?php echo $text_report_sale_order; ?></a></li>
              <li><a href="<?php echo $report_sale_tax; ?>"><?php echo $text_report_sale_tax; ?></a></li>
              <li><a href="<?php echo $report_sale_shipping; ?>"><?php echo $text_report_sale_shipping; ?></a></li>
              <li><a href="<?php echo $report_sale_return; ?>"><?php echo $text_report_sale_return; ?></a></li>
              <li><a href="<?php echo $report_sale_coupon; ?>"><?php echo $text_report_sale_coupon; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_product; ?></a>
            <ul>
              <li><a href="<?php echo $report_product_viewed; ?>"><?php echo $text_report_product_viewed; ?></a></li>
              <li><a href="<?php echo $report_product_purchased; ?>"><?php echo $text_report_product_purchased; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_customer; ?></a>
            <ul>
              <li><a href="<?php echo $report_customer_online; ?>"><?php echo $text_report_customer_online; ?></a></li>
              <li><a href="<?php echo $report_customer_order; ?>"><?php echo $text_report_customer_order; ?></a></li>
              <li><a href="<?php echo $report_customer_reward; ?>"><?php echo $text_report_customer_reward; ?></a></li>
              <li><a href="<?php echo $report_customer_credit; ?>"><?php echo $text_report_customer_credit; ?></a></li>
            </ul>
          </li>
          <li><a class="parent"><?php echo $text_affiliate; ?></a>
            <ul>
              <li><a href="<?php echo $report_affiliate_commission; ?>"><?php echo $text_report_affiliate_commission; ?></a></li>
            </ul>
          </li>
        </ul>
      </li>

        <li id="ebay"><a class="top">Ebay</a>
            <ul>
                <li><a href="index.php?route=ebay/debayconfig&token=<?=$_GET['token']?>">Ebay konfiguracja</a></li>
				<li><a href="index.php?route=ebay/debaytemplates&token=<?=$_GET['token']?>">Szablony</a></li>
                <li><a href="index.php?route=ebay/debayseller/active&token=<?=$_GET['token']?>">Panel sprzedawcy</a></li>
            </ul>
        </li>

        <li id="allegro"><a class="top">Allegro</a>
            <ul>
                <li><a href="index.php?route=allegro/szablony&token=<?=$_GET['token']?>">Allegro Szablony</a></li>
                <li><a href="index.php?route=allegro/seller&token=<?=$_GET['token']?>">Panel Sprzedawcy</a></li>
            </ul>
        </li>
    </ul>
    <ul class="right" style="display: none;">
      <li id="store"><a href="<?php echo $store; ?>" target="_blank" class="top"><?php echo $text_front; ?></a>
        <ul>
          <?php foreach ($stores as $stores) { ?>
          <li><a href="<?php echo $stores['href']; ?>" target="_blank"><?php echo $stores['name']; ?></a></li>
          <?php } ?>
        </ul>
      </li>
      <li><a class="top" href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
    </ul>
  </div>
  <?php } ?>
</div>


