</div></div>

<?/*
<div id="mychat"><a href="http://www.phpfreechat.net">Creating chat rooms everywhere - phpFreeChat</a></div>
*/?>

<div id="footer">
<div class="poziom" style="width:950px;">
  <?php if ($informations) { ?>
  <div class="column">
    <h3><?php echo $text_information; ?></h3>
    <ul>
      <?php foreach ($informations as $information) { ?>
      <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
      <?php } ?>
    </ul>
  </div>
  <?php } ?>
  <div class="column">
    <h3><?php echo $text_service; ?></h3>
    <ul>
      <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
      <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
      <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
    </ul>
  </div>
  <div class="column">
    <h3><?php echo $text_account; ?></h3>
    <ul>
      <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
      <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
      <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
    </ul>
  </div>
  <div class="column">
  <h3>TeeGlobe @</h3>
	<div style="margin:0 0 10px;" class="fb-like" data-href="https://www.facebook.com/TeeGlobeDotCom" data-layout="button_count" data-action="like" data-show-faces="false" data-share="false"></div>
		<a href="https://twitter.com/teeglobe" class="twitter-follow-button" data-show-count="false">Follow @teeglobe</a>
		<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
  </div>
  <div class="column">
	<h3><?php echo $text_contact; ?></h3>
	<strong><?php echo $this->config->get('config_name'); ?></strong><br/>
	<?php echo $this->config->get('config_address'); ?><br/><br/>
	<?php echo $this->config->get('config_email'); ?>
  </div>
</div>

</div>

<? if(Utilities::isController('product/category')) { ?>
<script type="text/javascript">$(document).ready(function() { display('grid'); });</script>
<? } ?>

<script type="text/javascript"><!--
    $('#button-cart').bind('click', function() {

        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea, input[name="kaucja"]'),
            dataType: 'json',
            success: function(json) {
                $('.success, .warning, .attention, information, .error').remove();

                $('#campaign_errors').empty();

                if (json['error']) {
                    if (json['error']['option']) {
					$('.error-popup').hide();
                        for (i in json['error']['option']) {
							console.log('.opt-' + i);						
							
                            $('.opt-' + i).show();
							$('.opt-' + i).parent().addClass('red');
							$('.red > label').bind('click',function(){
								$(this).parent().removeClass('red');
								$(this).parent().find('.error-popup').hide();
							});

                            // kanpania
                            //$('#campaign_errors').append('<span class="error">' + json['error']['option'][i] + '</span>');

                        }
                    }
                }

                if (json['success']) {
				
					/*

                    html = cartNotify(json);

                    $('#notification').html(html);

                    $('.success').fadeIn('slow');

                    $('#cart-total').html(json['total']);

                    $('html, body').animate({ scrollTop: 0 }, 'slow');
					
					*/
					
					window.location = './index.php?route=checkout/cart';
                }
            }
        });
    });



    //--></script>

<script type="text/javascript" src="catalog/view/javascript/countdown.js"></script>
<?php $campaign = $this->document->getCampaign(); ?>
<?php if($campaign AND !$campaign['no_buy']){ ?>
<script type="text/javascript">

    var end = new Date('<?php echo $campaign["split_date"]["year"]; ?>',
            '<?php echo $campaign["split_date"]["month"]; ?>',
            '<?php echo $campaign["split_date"]["day"]; ?>',
            '<?php echo $campaign["split_date"]["hour"]; ?>',
            '<?php echo $campaign["split_date"]["minute"]; ?>',
            '<?php echo $campaign["split_date"]["day"]; ?>');


    var timerId =
            countdown(

                    end,
                    function(ts) {
                        document.getElementById('pageTimer').innerHTML = ts.toString("strong");
                    },
                    countdown.HOURS|countdown.MINUTES|countdown.SECONDS);
</script>
<?php } ?>
<?/*
<script type="text/javascript">
    $('#mychat').phpfreechat({ serverUrl: '/phpfreechat-2.1.0/server' });
</script>
*/?>
</body></html>