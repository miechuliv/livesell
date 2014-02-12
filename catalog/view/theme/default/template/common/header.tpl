<!DOCTYPE html>
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<head>
<meta charset="UTF-8" />
    <meta name="google-site-verification" content="l9N6oDorNSlFCx2E8tWfMUJdjAN_yZ5aPOCz62X0COw" />
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css" />
<script type="text/javascript" src="catalog/view/javascript/jquery/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<script type="text/javascript" src="http://www.geoplugin.net/javascript.gp"></script>

<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>

<!--[if IE 7]> 
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie7.css" />
<![endif]-->
<!--[if lt IE 7]>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/default/stylesheet/ie6.css" />
<script type="text/javascript" src="catalog/view/javascript/DD_belatedPNG_0.0.8a-min.js"></script>
<script type="text/javascript">
DD_belatedPNG.fix('#logo img');
</script>
<![endif]-->
<?/*
    <link rel="stylesheet" type="text/css" href="/phpfreechat-2.1.0/client/themes/default/jquery.phpfreechat.min.css" />
    <script src="/phpfreechat-2.1.0/client/jquery.phpfreechat.min.js" type="text/javascript"></script>
*/?>
<?/*
<script type="text/javascript">
	$(document).ready(function() {

	// OBCIĘCIE NAZW PRODUKTÓW 

		$('.name a').each(function(index, element) {

			$(element).text($(element).text().substr(0,50));
			$(element).append('...');
		})

	if ($stores) { ?>
	<?php foreach ($stores as $store) { ?>
	$('body').prepend('<iframe src="<?php echo $store; ?>" style="display: none;"></iframe>');
	<?php } ?>
	<?php } 

		if(isset($filters['price_min_value'])){ ?>
			<!-- slider cena -->
			var price_min_values = [<?php foreach($filters['price_min_value'] as $value){ echo $value.','; }; ?>];
			var price_max_values = [<?php foreach($filters['price_max_value'] as $value){ echo $value.','; }; ?>];
			var numPos=0;
			var step=250/<?php echo $filters['number_price_sections']; ?>;
			var max=250;
			var position_min=<?php if(isset($filters['current_price_min'])){echo $filters['current_price_min'].'*';}else{echo $filters['default_current_price_min'].'*';}?>(step);
			var position_max=<?php if(isset($filters['current_price_max'])){ echo $filters['current_price_max'].'*';}else{echo $filters['default_current_price_max'].'*';}?>(step);

			$("#slider_price").slider({
				min:0,
				max:250,
				step:250/<?php echo $filters['number_price_sections']; ?>,
			values:[position_min, position_max],
					slide: function(event, ui) {

				if(ui.values[0]>ui.values[1]){
					var newvalues=[ui.values[1]-step,ui.values[1]];
					$("#slider_price").slider('values',0,newvalues[0]); // sets first handle

					return false;
				}
				
				numPos1 = (parseInt(ui.values[0])/max)*<?php echo $filters['number_price_sections']; ?>;
				numPos2 = (parseInt(ui.values[1])/max)*<?php echo $filters['number_price_sections']; ?>;
				numPos1=numPos1.toFixed(0);
				numPos2=numPos2.toFixed(0);

				$("#cena_min").val(price_min_values[numPos1]);
				$("#cena_max").val(price_max_values[numPos2]);
				$("#price_search").val(ui.values);
			}
		});

		$("#slider_price").mouseup(function(){
			$("#miechu_form").submit();
		});
		<?php } ?>
});
</script>
*/?>
<?/*
    <script>
        $(document).ready(function(){
            $('.kill-filter span').click(function(){
                var elem = $(this).parents('.kill-filter');


                killFilter(elem);
            });
        });




        function killFilter(elem)
        {
            var input_name = $(elem).find('input[name="input_name"]').val();


            var def_value = $(elem).find('.filter-name').text();


            var target = $('select[name="'+input_name+'"] option:eq(0)');

            if(target.length)
            {
                $(target).prop('selected', true);
                $(target).attr('selected', 'selected');

                $('select[name="'+input_name+'"]').next('.select').html(def_value);
            }
            else
            {
                target = $('input[name="'+input_name+'"]');

                $.each(target,function(key,elem){

                            $(elem).attr('checked',false);

                            $(elem).parent().removeClass('active');

                }
                )



            }


            $(elem).remove();
        }
    </script>
*/?>
<?php echo $google_analytics; ?>

</head>
<body>

<div id="black"></div>

<div id="header">
	<div class="poziom">
	
		<div>	
			<?php if ($logo) { ?>
				<div id="logo"><a href="./"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
			<?php } ?>  
		</div>
		
<div id="navi">

	<ul>
		<li class="active"><a href="/livesell/">Today's Collection</a></li>
		<li><a href="<?php echo $last_chance; ?>">Last Chance</a></li>
		<li><a href="<?php echo $gallery; ?>">Gallery</a></li>
		<li><a href="<?php echo $register; ?>">Submit</a></li>
		<li><a href="<?php echo $contact; ?>">Contact</a></li>
		<li class="kasa"><a href="<?php echo $cart_link; ?>" >Koszyk</a></li>
	</ul>
</div>
<?/*
		<div>
			<img src="./image/bomba.png" style="height:150px">
		</div>
		<div>
			<div id="pseudokoszyk">KOSZYK</div>
		</div>
*/?>
	<?/*
	<?php echo $cart; ?>    

	<div id="tel"><span><?php echo $this->config->get('config_telephone'); ?></span><br/><small>E-mail: <a href="mailto:<?php echo $this->config->get('config_email'); ?>"><?php echo $this->config->get('config_email'); ?></a></small></div>

	  <?php if ($logo) { ?>
	  <div id="logo"><a href="./"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
	  <?php } ?>  

	   <div id="search">
		<input type="text" class="borderb" name="search" placeholder="<?php echo $text_search; ?>" value="<?php echo $search; ?>" />
		<div class="button-search"></div>
	  </div>

	<?php if ($categories) { ?>
	<div id="menu">
	  <ul>
		<?php foreach ($categories as $category) { ?>
		<li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
		  <?php if ($category['children']) { ?>
		  <div>
			<?php for ($i = 0; $i < count($category['children']);) { ?>
			<ul>
			  <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
			  <?php for (; $i < $j; $i++) { ?>
			  <?php if (isset($category['children'][$i])) { ?>
			  <li><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></li>
			  <?php } ?>
			  <?php } ?>
			</ul>
			<?php } ?>
		  </div>
		  <?php } ?>
		</li>
		<?php } ?>
		<?php foreach($informations as $information){ ?>

		  <li><a href="<?php echo $information['href']; ?>"><?php echo $information['name']; ?></a></li>
		<?php } ?>
	  </ul>
	</div>
	<?php } ?>
	*/?>

	</div>
</div>


<div id="notification"></div>

<div id="container">

    <div id="translateBox"></div>

<?php /* foreach(Utilities::getInstance()->getControllerList() as $controller){ ?>
      <input type="hidden" name="active_controller[]" value="<?php echo $controller; ?>.php" />
<?php } */ ?>


<div <?php if(!in_array('common/home',Utilities::getControllerList() )) { ?> class="poziom" <?php } else { ?> id="poziomhome" <?php } ?> >