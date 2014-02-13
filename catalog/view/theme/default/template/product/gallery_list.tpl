<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
    <?php /* <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div> */ ?>

<div>
    <div class="campaign-filter" >
        <label for="filter_author" ><?php echo $this->language->get('text_filter_author'); ?></label>
        <input name="filter_author" value="<?php echo $filter_author; ?>">

        <label for="filter_name" ><?php echo $this->language->get('text_filter_name'); ?></label>
        <input name="filter_name" value="<?php echo $filter_name; ?>">

        <label for="filter_tag" ><?php echo $this->language->get('text_filter_tag'); ?></label>
        <input name="filter_tag" value="<?php echo $filter_tag; ?>">

        <input onclick="filter();" type="submit" value="<?php echo $this->language->get('text_filter'); ?>" >

    </div>



    <div class="product-filter" style="margin-bottom:10px; border:none; float:right; margin:5px 0; width:520px;">

        <div class="sort"><b><?php echo $this->language->get('text_sort'); ?></b>
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

    <?php if ($campaigns) { ?>


<div class="product-list">
    <?php foreach ($campaigns as $campaign) { ?>
    <div>



        <?php if ($campaign['image']) { ?>


        <div class="image"><a href="<?php echo $campaign['show']; ?>"><img  src="<?php echo $campaign['image']; ?>" title="<?php echo $campaign['name']; ?>" alt="<?php echo $campaign['name']; ?>" /></a></div>

        <?php } ?>

        <div class="name"><a href="<?php echo $campaign['show']; ?>"><?php echo $campaign['name']; ?></a></div>
        <div class="description">
            <?php echo $this->language->get('text_author'); ?>: <?php echo $campaign['author']; ?>
            <div class="image"><img  src="<?php echo $campaign['author_avatar']; ?>" title="<?php echo $campaign['author']; ?>" alt="<?php echo $campaign['author']; ?>" /></div>
            <?php echo $this->language->get('text_date'); ?>:  <?php echo $campaign['date_start']; ?> - <?php echo $campaign['date_end']; ?>
        </div>

        <?php if ($campaign['vote']) { ?>
        <div class="rating"><?php echo $campaign['vote']; ?></div>
        <?php } ?>
        <?php if($this->customer->isLogged()){ ?>
        <div class="vote" onclick="upvote(this,'<?php echo $campaign["campaign_id"]; ?>');">+ <?php echo $this->language->get('text_upvote'); ?></div>
        <?php } ?>

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

                        html += '<div class="name"><a href="'+elem["show"]+'">'+elem["name"]+'</a></div>';
                        html += '<div class="description">';
                        html += '        <?php echo $this->language->get("text_author"); ?>: '+ elem["author"];
                        html += '<div class="image"><img  src="'+elem["author_avatar"]+'" title="'+elem["author"]+'" alt="'+elem["author"]+'" /></div>';
                        html += '       <?php echo $this->language->get("text_date"); ?>:  '+elem["date_start"]+' - '+elem["date_end"];
                        html += '</div>';

                        html += '<div class="rating">'+elem["vote"]+'</div>';

                        html += '<div class="vote" onclick="upvote(this,\''+elem["campaign_id"]+'\');">+ <?php echo $this->language->get("text_upvote"); ?></div>';
                        html += '</div>';

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
<?php echo $footer; ?>