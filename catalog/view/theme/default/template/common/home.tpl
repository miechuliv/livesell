<?php echo $header; ?><?php // echo $column_left; ?><?php // echo $content_top; ?>

<div id="homepage">
<?/*
	<div>
		<div id="timer">
			Time left<br/>
			<span>00:00</span>
		</div>
	</div>
	*/?>		
	


    <?php echo $campaign; ?>
	
</div>

<?/*
<div id="content" style="margin:0;">
	<div class="poziom">
	</div>
</div>
*/?>

<script>
	$(document).ready(function(){
		$('label').bind('click',function(){
			$(this).parent().find('label').removeClass('activ');
			$(this).addClass('activ');
		});
		$('.inpczerwony').click(function(){
			$('#startmoke').css('background','#e74c3c');
		});
		$('.inpniebieski').click(function(){
			$('#startmoke').css('background','#3498db');
		});
		$('.pleck').click(function(){
			$('.meska').hide();
			$('.damska').show();
		});
		$('.plecm').click(function(){
			$('.meska').show();
			$('.damska').hide();
		});
	});
</script>

<?php echo $footer; ?>