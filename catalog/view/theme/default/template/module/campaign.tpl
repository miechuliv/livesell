<div class="fff">
    <?php if($campaign){ ?>
    <h1><span><?php echo $campaign['name']; ?></span>
        <small>by: <a href="<?php echo $campaign['author_href']; ?>"><?php echo $campaign['author']; ?></a></small>
    </h1>		<div style="background:#000; text-align:center; width:100%; padding:20px 0;">



    <?php foreach($campaign_images as $image){ ?>
        <img src="<?php echo $image; ?>" style="width:100%;" alt=""/>	</div>
    <?php } ?>
    <div id="oferta">
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
            <h1>&nbsp;</h1>
            <?/*<div id="kabelki"></div>*/?>
        <?php if(!$no_buy){ ?>
        <div>
            <div id="bomba"><div id="pageTimer" >00:00</div></div>
        </div>
        <?php } ?>
        <div  class="selecty marginhor product-info">
            <input type="hidden" name="product_id" size="2" value="" />
            <table id="faken" >
                <tr>
                    <td>
                        <h2>1.Wybierz rodzaj</h2>
                    <?php foreach($campaign_products as $product){ ?>

                    <input onclick="reloadOptions('<?php echo $product["product_id"]; ?>')" type="radio" name="rodzaj" id="<?php echo $product['name']; ?>" value="<?php echo $product['product_id']; ?>"><label for="<?php echo $product['name']; ?>" class="<?php echo $product['name']; ?>"><?php echo $product['price']; ?></label>

                    <?php } ?>
                    </td>


                </tr>

              <?php /*  <tr>
                    <td>
                        <h2>2. Wybierz płeć</h2>
                        <input type="radio" name="plec" id="plec-k" value="s"><label for="plec-k" class="pleck">Kobieta</label>
                        <input type="radio" name="plec" id="plec-m" value="m"><label for="plec-m" class="plecm">Mężczyzna</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2>3. Wybierz rozmiar</h2>
                        <input type="radio" name="rozmiar" id="rozmiar-s" value="s"><label for="rozmiar-s">S</label>
                        <input type="radio" name="rozmiar" id="rozmiar-m" value="m"><label for="rozmiar-m">M</label>
                        <input type="radio" name="rozmiar" id="rozmiar-l" value="l"><label for="rozmiar-s">L</label>
                        <input type="radio" name="rozmiar" id="rozmiar-xl" value="xl"><label for="rozmiar-s">XL</label>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h2>4. Wybierz kolor</h2>
                        <input type="radio" name="kolor" id="kolor-c" value="kolor-c"><label for="kolor-c" class="czerwony">&nbsp;</label>
                        <input type="radio" name="kolor" id="kolor-n" value="kolor-n"><label for="kolor-n" class="niebieski">&nbsp;</label>
                    </td>
                </tr> */ ?>
            </table>
            <?php if(!$no_buy){ ?>
            <a  class="action margintop line25 full" id="button-cart" >Kup teraz!</a>
            <?php } ?>
        </div>
    </div>
</div>
</div>
<?php if(!$no_buy){ ?>
<div id="morehome" class="fff">
    <div class="lewa">
        <table>
            <thead>
            <tr>
                <td colspan="2">
                    <h2>O autorze</h2>
                </td>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <?php echo $campaign['author_about']; ?>
                </td>
                <td>
                    <img src="<?php echo $campaign['author_avatar']; ?>" alt="<?php echo $campaign['author']; ?>"/>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
    <div class="prawa">
        <h2 class="white">Ostatnia szansa</h2>
        <div style="height:244px;">
            <img src="<?php echo $alt_offer_preview; ?>" />
            <a href="<?php echo $alt_link; ?>" class="action margintop">Kup teraz!</a>
        </div>
    </div>

</div>
<?php } ?>
<?php }else{ ?>
<div>
    <?php echo $this->language->get('text_no_campaign'); ?>
</div>
<?php } ?>

<script type="text/javascript" src="catalog/view/javascript/countdown.js"></script>
<?php if($campaign AND !$no_buy){ ?>
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




    function OptionRepo()
    {
        this.options = {};
        this.getOptionValuesImage = function(option_id,option_value_id)
        {

              var im = this.options[option_id].values[option_value_id].option_value_image;


            if(im)
            {
                $('#main-foto').attr('src',im);
            }

        }
        this.getOptionsByProduct = function(product_id)
        {


            var html = '';

            var i = 0;

            var opt = this.options;

            $.each(opt , function(key,elem)
            {



                if(elem.product_id == product_id)
                {
                    i++;

                    html += '<tr><td><h2>'+i+'. Wybierz '+elem.option_name+'</h2>';
                    $.each(elem.values,function(key2,value)
                    {
                        html += '   <input pro_id="'+product_id+'" op_id="'+elem.option_id+'" val_id="'+value.option_value_id+'" type="radio" name="option['+elem.product_option_id+']" id="'+value.option_value_name+'" value="'+value.product_option_value_id+'"><label for="'+elem.option_name+'" class="'+value.option_value_name+' op">'+value.option_value_name+'</label>';
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
                    v.option_value_price = '<?php echo $value['price']; ?>';
                    v.option_value_id = '<?php echo $value['option_value_id']; ?>';
                    v.product_option_value_id = '<?php echo $value['product_option_value_id']; ?>';

                    op.values['<?php echo $value['option_value_id']; ?>'] = v;
                <?php } ?>

                repo.options['<?php echo $option['option_id']; ?>'] = op;
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
        $('label').live('click',function(){
            $(this).parent().find('label').removeClass('activ');
            $(this).parent().find('input').attr('checked',false);
            $(this).addClass('activ');

            $(this).prev('input').attr('checked','checked');


        });

        $('.op').live('click',function(){

            reloadPhoto($(this).prev('input').attr('pro_id'),$(this).prev('input').attr('op_id'),$(this).prev('input').attr('val_id'));

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

        $('#faken tr td input:first').click();

        /* $('#button-cart').bind('click', function() {

            $.ajax({
                url: 'index.php?route=checkout/cart/add',
                type: 'post',
                data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea, input[name="kaucja"]'),
                dataType: 'json',
                success: function(json) {
                    $('.success, .warning, .attention, information, .error').remove();

                    if (json['error']) {
                        if (json['error']['option']) {
                            for (i in json['error']['option']) {
                                $('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
                            }
                        }
                    }

                    if (json['success']) {

                        html = cartNotify(json);

                        $('#notification').html(html);

                        $('.success').fadeIn('slow');

                        $('#cart-total').html(json['total']);

                        $('html, body').animate({ scrollTop: 0 }, 'slow');
                    }
                }
            });
        }); */
    });
</script>