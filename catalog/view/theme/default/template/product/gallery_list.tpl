<?php echo $header; ?><?php //echo $column_left; ?><?php //echo $column_right; ?>
<div id="content" class="galeria"><?php echo $content_top; ?>
<div class="breadcrumb">        
	<a href="./index.php?route=common/home">Strona główna</a>         
	» <a href="./index.php?route=product/gallery/showList"><?php echo $this->language->get('text_gallery'); ?></a>    
</div>	  

<div class="fff podstr">
<div>	  

<h1><?php echo $this->language->get('text_gallery'); ?></h1>


    <div class="campaign-filter" >
		<div>
			<div>
				<strong><?php // echo $this->language->get('text_search'); ?><?php echo $this->language->get('text_filter'); ?>:</strong>
			</div>
			<div>
				<?php /* <label for="filter_author" ><?php echo $this->language->get('text_filter_author'); ?></label>
				<input name="filter_author" value="<?php echo $filter_author; ?>"> */ ?>
<div style="float:left">
				 <label for="filter_name" ><?php echo $this->language->get('text_filter_name'); ?></label>
				<input name="filter_name" value="<?php echo $filter_name; ?>">

                <label for="filter_sell" ><?php echo $this->language->get('text_filter_sell'); ?></label>
                <select name="filter_sell" onchange="filter();">
                    <?php if($filter_sell) { ?>

                    <option value="1" selected="selected" ><?php echo $this->language->get('text_yes'); ?></option>
                    <option value="0"  ><?php echo $this->language->get('text_no'); ?></option>
                    <?php } else { ?>
                    <option value="1"  ><?php echo $this->language->get('text_yes'); ?></option>
                    <option value="0" selected="selected" ><?php echo $this->language->get('text_no'); ?></option>
                    <?php } ?>

                </select>

				<?php /* <label for="filter_tag" ><?php echo $this->language->get('text_filter_tag'); ?></label>
				<input name="filter_tag" value="<?php echo $filter_tag; ?>"> */ ?>
</div>
			<input onclick="filter();" type="submit" class="button" value="<?php echo $this->language->get('text_filter'); ?>" style="float:left; line-height:17px; margin:5px 0 0 10px;">
			</div>
			<div>
				<div class="sort">
					<b><?php echo $this->language->get('text_sort'); ?>:</b>&nbsp;
					<select name="date_sort" onchange="filter();">
						<?php foreach ($date_sorts as $sorts) { ?>
						<?php if ($sorts['value'] == $sort_date) { ?>
						<option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
					<select name="vote_sort" onchange="filter();">
						<?php foreach ($vote_sorts as $sorts) { ?>
						<?php if ($sorts['value'] == $sort_vote) { ?>
						<option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
						<?php } else { ?>
						<option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
						<?php } ?>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
    </div>

    <?php if ($campaigns) { ?>


<div class="product-list">
    <?php foreach ($campaigns as $campaign) { ?>
    <div>



        <?php if ($campaign['image']) { ?>


        <div class="image"><a href="<?php echo $campaign['show']; ?>"><img  src="<?php echo $campaign['image']; ?>" title="<?php echo $campaign['name']; ?>" alt="<?php echo $campaign['name']; ?>" /></a></div>

        <?php } ?>

       <div class="name"><a href="<?php echo $campaign['show']; ?>"><?php echo $campaign['name']; ?></a></div>
        <div class="description">
            <?php echo $this->language->get('text_author'); ?>: <?php echo $campaign['author']; ?><br/>
            <?/*<div class="image"><img  src="<?php echo $campaign['author_avatar']; ?>" title="<?php echo $campaign['author']; ?>" alt="<?php echo $campaign['author']; ?>" /></div>*/?>
            <?php echo $this->language->get('text_date'); ?>:  <?php echo $campaign['date_start']; ?> - <?php echo $campaign['date_end']; ?>

        <?php if ($campaign['vote']) { ?>
        <div class="rating"><?php echo $this->language->get('text_vote').': '.$campaign['vote']; ?> 
		        <?php if($this->customer->isLogged()){ ?> 
					(<a href="javascript:void(0);" class="vote" onclick="upvote(this,'<?php echo $campaign["campaign_id"]; ?>');">+ <?php echo $this->language->get('text_upvote'); ?></a>)
				   <?php }else{ ?>
					(<a href="javascript:void(0);" class="vote" onclick="$('#vl').css('display','block');">+ <?php echo $this->language->get('text_upvote'); ?></a>)
				 <?php } ?>
		</div>
        <?php } ?>

 
		   <?php if($campaign['show_archiwe_sell']=='1') { ?> <a href="<?php echo $campaign['show']; ?>" class="button action" style="width:100%; text-align:center; padding:10px 0; margin:5px 0;"><?php echo $this->language->get('text_buy'); ?></a> <? } ?>
        </div>

</div>
<?php } ?>


</div>
<?php /* <div class="pagination"><?php echo $pagination; ?></div> */ ?>
<?php }else{ ?>
<div id="search-error" style="text-align:left;">
    <?php echo $this->language->get('text_no_results'); ?> <br/>
</div>
<?php } ?>

<?php echo $content_bottom; ?></div>
</div>
</div>
</div>
<script type="text/javascript"><!--

    var page = 2;

    $(document).ready(function(){
        $(window).scroll(function() {
            if($(window).scrollTop() + $(window).height() == $(document).height()) {

                loadMore();
				
            } 
        });
    });

    function loadMore()
    {
        $.ajax({
            url: 'index.php?route=product/gallery/showList&ajax=true&page='+page,
            type: 'get',
            dataType: 'json',
            success: function(json){

                if(json)
                {
                    $.each(json,function(key,elem){
                        var html = '<div>';
                        html += '<div class="image"><a href="'+elem["show"]+'"><img  src="'+elem["image"]+'" title="'+elem["name"]+'" alt="'+elem["name"]+'" /></a></div>';

                        html += '<div><div class="name"><a href="'+elem["show"]+'">'+elem["name"]+'</a></div>';
                        html += '<div class="description">';
                        html += '        <?php echo $this->language->get("text_author"); ?>: '+ elem["author"];
                        html += '<div class="image"><img  src="'+elem["author_avatar"]+'" title="'+elem["author"]+'" alt="'+elem["author"]+'" /></div>';
                        html += '       <?php echo $this->language->get("text_date"); ?>:  '+elem["date_start"]+' - '+elem["date_end"];
                        html += '</div>';

                        html += '<div class="rating">'+elem["vote"]+'</div>';

                        html += '<div class="vote" onclick="upvote(this,\''+elem["campaign_id"]+'\');">+ <?php echo $this->language->get("text_upvote"); ?></div>';
                        html += '</div></div>';

                        $('.product-list').append(html);

                    })

                    page++;

                }
            }
        });
    }

    <?php if($this->customer->isLogged()){ ?>
    function upvote(elem,campaign_id)
    {
      /*  var upvote = $.cookie('upvote');
        console.log(upvote);
        if(upvote == campaign_id)
        {
            alert('oddałeś już głos');
        } */
        $.ajax({
        url: 'index.php?route=product/gallery/upvote&campaign_id='+campaign_id,
        type: 'get',
        dataType: 'text',
        success: function(text){
            if(text == 'block' )
            {
                alert('<?php echo $this->language->get("text_voted_already"); ?>');
            }
            if(text == 'ok' )
            {
               // $.cookie('upvote',campaign_id);
                alert('<?php echo $this->language->get("text_voted"); ?>');
                var t = $(elem).prev('.rating').text();
                t = parseInt(t) + 1;
                $(elem).prev('.rating').text(t);


            }

        }

        })
    }
    <?php } ?>

    function filter(mass_edit) {
        url = 'index.php?route=product/gallery/showList&page=<?php echo $page; ?>';





        var filter_name = $('input[name=\'filter_name\']').attr('value');

        if (filter_name) {
            url += '&filter_name=' + encodeURIComponent(filter_name);
        }

        var filter_sell = $('select[name=\'filter_sell\']').find(':selected').attr('value');

        if (filter_sell) {
            url += '&filter_sell=' + encodeURIComponent(filter_sell);
        }

        var filter_author = $('input[name=\'filter_author\']').attr('value');

        if (filter_author) {
            url += '&filter_author=' + encodeURIComponent(filter_author);
        }

        var filter_tag = $('input[name=\'filter_tag\']').attr('value');


        if (filter_tag) {
            url += '&filter_tag=' + encodeURIComponent(filter_tag);
        }

        var sort_date = $('select[name=\'sort_date\'] option:selected').attr('value');

        if (sort_date) {
            url += '&sort_date=' + encodeURIComponent(sort_date);
        }

        var sort_vote = $('select[name=\'sort_vote\'] option:selected').attr('value');

        if (sort_vote) {
            url += '&sort_vote=' + encodeURIComponent(sort_vote);
        }






        location = url;
    }
    //--></script>
<div id="vl">
	<a href="javascript:void(0);" onclick="$('#vl').css('display','none');" id="close">X</a>
    <form  action="<?php echo $login_action; ?>" method="post" > 
		<div>
			<h1 style="font-size:17px; margin:10px 0;"><?php echo $this->language->get('text_login_to_vote'); ?></h1>
		</div>
		<div>
			<input type="hidden" name="redirect" value="<?php echo $login_redirect; ?>" />
			<label for="email" ><?php echo $this->language->get('entry_email'); ?></label>		
			<input type="text" name="email" />
		</div><div>
			<label for="password" ><?php echo $this->language->get('entry_password'); ?></label>
			<input type="password" name="password"/>
		</div><div>
			<input type="submit" class="button action" value="<?php echo $this->language->get('text_login'); ?>" />
		</div>
    </form>
</div>
<?php echo $footer; ?>