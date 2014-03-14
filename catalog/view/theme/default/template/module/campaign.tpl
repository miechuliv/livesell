<?php if(!$no_buy){ ?>
	<?/*<div id="bomb">
		<div id="bomba"><div id="pageTimer" >00:00</div></div>
	</div>*/?>
<?php } ?>

 <?php if($campaign){ ?>	
	
<div class="fff">		
		 
<div id="oferta">
	<div>
		<div id="tableoff">
			<div>
				<div id="sldiecontrol">
					<div id="prev"></div>
					<div id="next"></div>
				</div>
				<div class="homeslide">
					<img id="main-foto" src="" class="meska" alt="" />

				</div>
			</div>
			<div>
				<div class="selecty marginhor product-info">

					<input type="hidden" name="product_id" size="2" value="" />
                    <input type="hidden" name="campaign_type" value="<?php echo $campaign_type; ?>" />
					
					<h1 class="camp" style="width:300px;">		
						<span><?php echo $campaign['name']; ?></span> <a href="<?php echo $campaign['author_href']; ?>"><?php echo $this->language->get('text_by'); ?><small><?php echo $campaign['author']; ?></small></a>
					</h1>	
					
					<table id="faken" >
						<tr>
							<td>
								<h2>1. <?php echo $this->language->get('text_pick_product'); ?></h2>
								<?php foreach($campaign_products as $product){ ?>
									<input onclick="reloadOptions('<?php echo $product["product_id"]; ?>')" type="radio" name="rodzaj" id="<?php echo $product['name']; ?>" value="<?php echo $product['product_id']; ?>"><label for="<?php echo $product['name']; ?>" class="<?php echo $product['name']; ?>"><?php if(!$no_buy){ echo'<span></span>'; echo number_format($product['price'],2); /*echo' '; echo $this->config->get('config_currency');*/ }else{ echo '----'; } ?></label>
								<?php } ?>    
							</td>
						</tr>
					</table>
					<?/*
					<div>
						<?php echo $campaign['description']; ?>
					</div>
						*/?>            
						
					<?php if(!$no_buy){ ?>
					<a  class="action margintop line25 full" <?php if(!isset($preview)){ ?> id="button-cart" <?php } ?> ><?php echo $this->language->get('text_buy_now'); ?></a>
				   
					<?php } ?>
					
					<?php /* <div class="fb-like" data-href="<?php echo $like_url; ?>" data-layout="standard" data-action="like" data-show-faces="true" data-share="true"></div> */ ?>
					

					<script type="text/javascript" src="http://w.sharethis.com/button/buttons.js"></script>
					<script type="text/javascript">
						stLight.options({
							publisher: "d86370c7-306a-4ae7-a9c4-fec51c5c11f1", 
							doNotHash: false, 
							doNotCopy: false, 
							hashAddressBar: false
						});
							function myCallbackFunction (event,service)
								{					
									if(service=='facebook'){
										$.ajax({
											url: 'index.php?route=module/campaign/facelike&campaign_id=<?php echo $campaign['campaign_id']; ?>',
											type: 'get'
										})
									} 
								}
							stLight.subscribe("click",myCallbackFunction);
					</script>
					
					<span class='st_facebook_large' displayText='Facebook' st_url='<?php echo $like_url; ?>'></span>
					<span class='st_twitter_large' displayText='Tweet' st_url='<?php echo $like_url; ?>'></span>
					<span class='st_googleplus_large' displayText='Google +' st_url='<?php echo $like_url; ?>'></span>
					<span class='st_linkedin_large' displayText='LinkedIn' st_url='<?php echo $like_url; ?>'></span>
					<span class='st_pinterest_large' displayText='Pinterest' st_url='<?php echo $like_url; ?>'></span>
					
				</div>			
			</div>
		</div>
	</div>
	<div id="newslet-home">
		<div>
			<div>
				<div>
					<img src="./image/data/logo/logo-small.png" alt="" id="logos"/> <h3><?php echo $this->language->get('news_submitnew'); ?></h3>
				</div>
				<div>
					<form>
						<input type="text" placeholder="<?php echo $this->language->get('news_email'); ?>">
						<input type="submit" value="<?php echo $this->language->get('news_submit'); ?>" class="button action">
						<div id="czeki">
							<div><input type="checkbox" id="news-regular" checked="checked"><label for="news-regular"><?php echo $this->language->get('news_regular'); ?></label></div>
							<div><input type="checkbox" id="news-okazj" checked="checked"><label for="news-okazj"><?php echo $this->language->get('news_okazja'); ?></label></div>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>


	
<!-- zamknięcie fff i homepage -->
</div></div>
<!-- / zamknięcie -->
	
<div class="bigfff">
	<div class="poziom">


    <?php if($campaign_image){ ?>		
		<div id="startmoke" class="fff">
			<div>
				<div>
					<img src="<?php echo $campaign_image; ?>" alt="<?php echo $campaign['name']; ?>"/>			
				</div>
				<div class="prawa">
					<table>
						<thead>
						<tr>
							<td colspan="2">
								<a href="<?php echo $campaign['author_href']; ?>" class="ahead">
									<img src="<?php echo $campaign['author_avatar']; ?>" alt="<?php echo $campaign['author']; ?>"/>
									<h1 class="lite nomargin"><strong><?php echo $campaign['author']; ?></strong></h1>
								</a>
							</td>
						</tr>
						</thead>
						<tbody>
						<tr>
							<td class="justify">								
								<?php echo html_entity_decode($campaign['author_about']); ?>
							</td>
						</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
    <?php } ?>	


<?php if(!$no_buy){ ?>
<div id="morehome" class="fff">
	<div id="tablemore">
		<div class="lewa">
			
			<div class="table">
				<div class="col-left">
					<div style="display:table-cell; text-align:center;">
						<img src="<?php echo $alt_offer_preview; ?>" style="max-width:90%;" alt=""/>
					</div>
					<div style="display:table-cell; vertical-align:middle; text-align:left;">
						<h1 style="font-size:25px;"><?php echo ($last_chance_flag?$this->language->get('text_current_offer'):$this->language->get('text_last_chance')); ?></h1>
						<h1 style="border:none; font-size:20px; padding:0; margin:0;"><?php echo $alt_name; ?></h1>
						<a href="<?php echo $alt_link; ?>" class="action margintop"><?php echo $this->language->get('text_buy_now'); ?></a>
					</div>
				</div>
				<div class="col-right">
					<div>
						<h1><?php echo $this->language->get('text_headprom'); ?></h1>
						<ul>
						<?php echo $this->language->get('text_prom'); ?>
						</ul>
					</div>
					<div>
						<h1><?php echo $this->language->get('text_headpay'); ?></h1>
						<p>
							<img alt="" src="./image/data/payment icons/mastercard_curved_32px.png">
							<img alt="" src="./image/data/payment icons/visa_straight_32px.png">
							<img alt="" src="./image/data/payment icons/paypal_curved_32px.png">
						</p>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>
<?php } ?>

    <div id="komhome" class="fff">
		<div>
			<h1 class="lite"><?php echo $this->language->get('text_comment'); ?></h1>
            <div id="disqus_thread"></div>
            <script type="text/javascript">
                /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
                var disqus_shortname = 'd4livesell'; // required: replace example with your forum shortname
                var disqus_identifier =  'campaign_id=<?php echo $campaign["campaign_id"]; ?>';
                var disqus_url = '<?php echo HTTP_SERVER; ?>index.php?route=common/home/#!/<?php echo $campaign["campaign_id"]; ?>';

                var disqus_config = function () {
                    this.language = "<?php echo $this->config->get('config_language'); ?>";

                };


                /* * * DON'T EDIT BELOW THIS LINE * * */
                (function() {
                    var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
                    dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
                    dsq.reload = true;
                    (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);


                })();
            </script>
            <noscript>Please enable JavaScript to view the <a href="http://disqus.com/?ref_noscript">comments powered by Disqus.</a></noscript>
            <a href="http://disqus.com" class="dsq-brlink">comments powered by <span class="logo-disqus">Disqus</span></a>
        </div>
    </div>
	
	
		<?php }else{ ?>
			<div>
				<?php echo $this->language->get('text_no_campaign'); ?>
			</div>
		<?php } ?>
    </div>	
		


<script type="text/javascript">
    function Option()
    {
        this.option_id;
        this.option_name;
        this.values = {};

        this.product_id;


    }

    function OptionValue()
    {
        this.option_value_name;
        this.option_value_image;
        this.option_value_price;
        this.option_value_id;
        this.product_option_id;
        this.product_option_value_id;
    }


    var im = '';

    function OptionRepo()
    {
        this.options = {};
        this.getOptionValuesImage = function(option_id,option_value_id)
        {

            var opt = this.options;



            $.each(opt,function(key,elem){

                ;
                if(option_id == elem.option_id)
                {
                 
                    im = elem.values[option_value_id].option_value_image;
                }
            })





            if(im)
            {
                $('#main-foto').attr('src',im);
            }

        }
        this.getOptionsByProduct = function(product_id)
        {


            var html = '';

            var i = 1;

            var opt = this.options;

            $.each(opt , function(key,elem)
            {



                if(elem.product_id == product_id)
                {
                    i++;

                    html += '<tr><td><h2 id="option-'+elem.option_id+'">'+i+'. <?php echo $this->language->get('text_wybierz'); ?> '+elem.option_name+'</h2><div class="opt-'+elem.product_option_id+' error-popup"><?php echo $this->language->get('text_wybierz'); ?> '+elem.option_name+'!</div>';
                    $.each(elem.values,function(key2,value)
                    {
                        html += '<input pro_id="'+product_id+'" op_id="'+elem.option_id+'" val_id="'+value.option_value_id+'" type="radio" name="option['+elem.product_option_id+']" id="'+value.option_value_name+'" value="'+value.product_option_value_id+'"><label for="'+elem.option_name+'" class="'+value.option_value_name+' op">'+value.option_value_name+'</label>';
                    })
                    html += '</td></tr>';


                }
            });



            $('#faken tr:not(:first)').remove();

            $('#faken tr:last').after(html);


        }

    }



    var set = {};

    var images = {};

    var i = 0;
    <?php if($campaign){ ?>
    <?php foreach($campaign_products as $product){ ?>

           images['<?php echo $product['product_id']; ?>'] = '<?php echo $product['image']; ?>';
           var repo = new OptionRepo();

           <?php foreach($product['options'] as $option){ ?>
                var op = new Option();
                op.option_id = '<?php echo $option['option_id']; ?>';
                op.option_name = '<?php echo $option['name']; ?>';
                op.product_id = '<?php echo $product['product_id']; ?>';
                 op.product_option_id = '<?php echo $option['product_option_id']; ?>';
				 
				

                <?php foreach($option['option_value'] as $value){ ?>
                    var v = new OptionValue();
                    v.option_value_name = '<?php echo $value['name']; ?>';
                    v.option_value_image = '<?php echo $value['image_value']; ?>';
                    v.option_value_price = '<?php echo number_format($value['price'],2); ?>';
                    v.option_value_id = '<?php echo $value['option_value_id']; ?>';
                    v.product_option_value_id = '<?php echo $value['product_option_value_id']; ?>';

                    op.values['<?php echo $value['option_value_id']; ?>'] = v;
                <?php } ?>

                repo.options[i] = op;
                i++;
            <?php } ?>

            set['<?php echo $product['product_id']; ?>'] = repo;
        <?php } ?>
        <?php } ?>



    function reloadOptions(product_id)
    {

        <?php if(!$no_buy){ ?>
        set[product_id].getOptionsByProduct(product_id);
        $('input[name=\'product_id\']').val(product_id);
        <?php } ?>

        var image = images[product_id];

        $('#main-foto').attr('src',image);
    }

    function reloadPhoto(product_id,option_id,option_value_id)
    {
        set[product_id].getOptionValuesImage(option_id,option_value_id);
    }
	
    $(document).ready(function(){	 
	  
			$('#tableoff label').live('click',function(){
            $(this).parent().find('label').removeClass('activ');
            $(this).parent().find('input').attr('checked',false);
            $(this).addClass('activ');

            $(this).prev('input').attr('checked','checked');

        });

        $('.op').live('click',function(){

            reloadPhoto($(this).prev('input').attr('pro_id'),$(this).prev('input').attr('op_id'),$(this).prev('input').attr('val_id'));

        });


        $('#faken tr td input:first').click();

    });
</script>