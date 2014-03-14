<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
</div>
<h1><?php echo $heading_title; ?></h1>

<?php $type='payment'; ?>


<?php if ($addresses) { ?>
<input type="radio" name="payment_address" value="existing" id="payment-address-existing" checked="checked" />
<label for="payment-address-existing"><?php echo $text_address_existing; ?></label>
<div id="payment-existing">
    <select name="payment_address_id" style="width: 100%; margin-bottom: 15px;" size="5">
        <?php foreach ($addresses as $address) { ?>
        <?php if ($address['address_id'] == $payment_address_id) { ?>
        <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
        <?php } ?>
        <?php } ?>
    </select>
</div>
<p>
    <input type="radio" name="payment_address" value="new" id="payment-address-new" />
    <label for="payment-address-new"><?php echo $text_address_new; ?></label>
</p>
<?php } ?>
<div id="payment-new" style="display: <?php echo ($addresses ? 'none' : 'block'); ?>;">
<?php if(!$addresses){ ?> <input type="hidden" name="payment_address" value="new"  /> <?php } ?>
    <table class="form">
        <tr>
            <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
            <td><input type="text" name="payment_firstname" value="" class="large-field" /></td>
        </tr>
        <tr>
            <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
            <td><input type="text" name="payment_lastname" value="" class="large-field" /></td>
        </tr>
        <tr>
            <td><?php echo $entry_company; ?></td>
            <td><input type="text" name="payment_company" value="" class="large-field" /></td>
        </tr>
        <?php if ($company_id_display) { ?>
        <tr>
            <td><?php if ($company_id_required) { ?>
                <span class="required">*</span>
                <?php } ?>
                <?php echo $entry_company_id; ?></td>
            <td><input type="text" name="payment_company_id" value="" class="large-field" /></td>
        </tr>
        <?php } ?>
        <?php if ($tax_id_display) { ?>
        <tr>
            <td><?php if ($tax_id_required) { ?>
                <span class="required">*</span>
                <?php } ?>
                <?php echo $entry_tax_id; ?></td>
            <td><input type="text" name="payment_tax_id" value="" class="large-field" /></td>
        </tr>
        <?php } ?>
        <tr>
            <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
            <td><input type="text" name="payment_address_1" value="" class="large-field" /></td>
        </tr>
        <tr>
            <td><?php echo $entry_address_2; ?></td>
            <td><input type="text" name="payment_address_2" value="" class="large-field" /></td>
        </tr>
        <tr>
            <td><span class="required">*</span> <?php echo $entry_city; ?></td>
            <td><input type="text" name="payment_city" value="" class="large-field" /></td>
        </tr>
        <tr>
            <td><span id="payment-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
            <td><input type="text" name="payment_postcode" value="" class="large-field" /></td>
        </tr>
        <tr>
            <td><span class="required">*</span> <?php echo $entry_country; ?></td>
            <td><select id="payment_country_id" name="payment_country_id" class="large-field">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($countries as $country) { ?>
                    <?php if ($country['country_id'] == $payment_country_id) { ?>
                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select></td>
        </tr>
        <tr style="display:none">
            <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
            <td><select id="payment_zone_id" name="payment_zone_id" class="large-field">
					<option value="1" selected="selected" ></option>
                </select></td>
        </tr>
    </table>
</div>
<br />

<script type="text/javascript"><!--
    $('#payment-address-new').live('change', function() {
        if (this.value == 'new') {
            $('#payment-existing').hide();
            $('#payment-new').show();
        } else {
            $('#payment-existing').show();
            $('#payment-new').hide();
        }
    });

    $('#payment-address-existing').live('change', function() {
        if (this.value == 'new') {
            $('#payment-existing').hide();
            $('#payment-new').show();
        } else {
            $('#payment-existing').show();
            $('#payment-new').hide();
        }
    });


    //--></script>
<script type="text/javascript"><!--
    $('#payment_country_id').bind('change', function() {
        if (this.value == '') return;
        $.ajax({
            url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('#payment_country_id').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('.wait').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#payment-postcode-required').show();
                } else {
                    $('#payment-postcode-required').hide();
                }

                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        <?php /* if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        } */ ?>

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                //$('#payment_zone_id').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('#payment_country_id').trigger('change');
    //--></script>


<?php $type='shipping'; ?>


<?php if ($addresses) { ?>
<input type="radio" name="shipping_address" value="existing" id="shipping-address-existing" checked="checked" />
<label for="shipping-address-existing"><?php echo $text_address_existing; ?></label>
<div id="shipping-existing">
    <select name="shipping_address_id" style="width: 100%; margin-bottom: 15px;" size="5">
        <?php foreach ($addresses as $address) { ?>
        <?php if ($address['address_id'] == $shipping_address_id) { ?>
        <option value="<?php echo $address['address_id']; ?>" selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
        <?php } ?>
        <?php } ?>
    </select>
</div>
<p>
    <input type="radio" name="shipping_address" value="new" id="shipping-address-new" />
    <label for="shipping-address-new"><?php echo $text_address_new; ?></label>
</p>
<?php } ?>
<div id="shipping-new" style="display: <?php echo ($addresses ? 'none' : 'block'); ?>;">
<?php if(!$addresses){ ?> <input type="hidden" name="shipping_address" value="new"   /> <?php } ?>
    <table class="form">
        <tr>
            <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
            <td><input type="text" name="shipping_firstname" value="" class="large-field" /></td>
        </tr>
        <tr>
            <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
            <td><input type="text" name="shipping_lastname" value="" class="large-field" /></td>
        </tr>
        <tr>
            <td><?php echo $entry_company; ?></td>
            <td><input type="text" name="shipping_company" value="" class="large-field" /></td>
        </tr>
        <tr>
            <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
            <td><input type="text" name="shipping_address_1" value="" class="large-field" /></td>
        </tr>
        <tr>
            <td><?php echo $entry_address_2; ?></td>
            <td><input type="text" name="shipping_address_2" value="" class="large-field" /></td>
        </tr>
        <tr>
            <td><span class="required">*</span> <?php echo $entry_city; ?></td>
            <td><input type="text" name="shipping_city" value="" class="large-field" /></td>
        </tr>
        <tr>
            <td><span id="shipping-postcode-required" class="required">*</span> <?php echo $entry_postcode; ?></td>
            <td><input type="text" name="shipping_postcode" value="<?php echo $shipping_postcode; ?>" class="large-field" /></td>
        </tr>
        <tr>
            <td><span class="required">*</span> <?php echo $entry_country; ?></td>
            <td><select id="shipping_country_id" name="shipping_country_id" class="large-field">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($countries as $country) { ?>
                    <?php if ($country['country_id'] == $shipping_country_id) { ?>
                    <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                </select></td>
        </tr>
        <tr style="display:none">
            <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
            <td><select id="shipping_zone_id" name="shipping_zone_id" class="large-field">
			<option value="1" selected="selected" ></option>
                </select></td>
        </tr>
    </table>
</div>
<br />

<script type="text/javascript"><!--
    $('#shipping-address-new').live('change', function() {
        if (this.value == 'new') {
            $('#shipping-existing').hide();
            $('#shipping-new').show();
        } else {
            $('#shipping-existing').show();
            $('#shipping-new').hide();
        }
    });

    $('#shipping-address-existing').live('change', function() {
        if (this.value == 'new') {
            $('#shipping-existing').hide();
            $('#shipping-new').show();
        } else {
            $('#shipping-existing').show();
            $('#shipping-new').hide();
        }
    });
    //--></script>
<script type="text/javascript"><!--
    $('#shipping_country_id').bind('change', function() {
        if (this.value == '') return;
        $.ajax({
            url: 'index.php?route=checkout/checkout/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('#shipping_country_id').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
            },
            complete: function() {
                $('.wait').remove();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('#shipping-postcode-required').show();
                } else {
                    $('#shipping-postcode-required').hide();
                }

                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        <?php /* if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        } */ ?>

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

               // $('#shipping_zone_id').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('shipping_country_id').trigger('change');
    //--></script>


<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
<div id="shipping-methods">
<div id="shipping-calculator" style="display:none">
             <p><?php echo $this->language->get('text_shipping_calculator'); ?></p>
        <select name="country_id" class="large-field">
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($countries as $country) { ?>
            <?php if ($country['country_id'] == 170) { ?>
            <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
            <?php } ?>
            <?php } ?>
        </select>


        </div>
    <script type="text/javascript"><!--

        function reloadShipping()
        {

            $.ajax({
                url: 'index.php?route=checkout/checkout/reloadShipping&country_id=' + $('#shipping-calculator select[name="country_id"] option:selected').val() +'&zone_id=0',
                dataType: 'json',
                beforeSend: function() {
                    // $('#payment-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
                },
                complete: function() {
                    //   $('.wait').remove();
                },
                success: function(json) {

                    html = '';
                    $.each(json,function(index,item){

                        var error = '';
                        if(item['error'])
                        {
                            error = item['error'];
                        }


                        html+=  '<tr>';
                        //     html+=  '<td colspan="3"><b>'+item['title']+'</b></td>';
                        html+=  '</tr>';

                        $.each(item['quote'],function(index2,quote){

                            html+=  '<tr class="highlight">';

                            html+=  '<td><input type="radio" checked="checked" onchange="reloadTotals()" name="shipping_method" value="'+quote['code']+'" id="'+quote['code']+'" /></td>';

                            html+=  '<td><label  for="'+quote['code']+'">'+quote['title']+'</label></td>';
                            html+=  '<td style="text-align: right;"><label for="'+quote['code']+'">'+quote['text']+'</label></td>';
                            html+=  '</tr>';

                        });


                        html+=  '<tr>';
                        html+=  '<td colspan="3"><div class="error">'+error+'</div></td>';
                        html+=  '</tr>';


                    });



                    target = $('#shipping-methods table');

                    $(target).html(html);

                    reloadTotals();

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });

        }


        

        $('#shipping-new select[name=\'shipping_country_id\']').bind('change', function() {
            if (this.value == '') return;

            var v = this.value;

            $('#shipping-calculator select[name=\'country_id\']').val(v);
            reloadShipping();
        });

        $('#shipping-calculator select[name=\'country_id\']').bind('change', function() {
            if (this.value == '') return;

            var v = this.value;

          
            $('#shipping-new select[name=\'shipping_country_id\']').val(v);

           

            reloadShipping();
        });


        $('#shipping-calculator select[name=\'country_id\']').trigger('change');
		
		<?php if($addresses){ ?>
		$('select[name=\'shipping_address_id\']').bind('change',function(){
				
				$.ajax({
                url: 'index.php?route=checkout/checkout/reloadShippingByAddress&address_id=' + $('select[name=\'shipping_address_id\'] option:selected').val() +'&zone_id=0',
                dataType: 'json',
                beforeSend: function() {
                    // $('#payment-address select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
                },
                complete: function() {
                    //   $('.wait').remove();
                },
                success: function(json) {

							var country_id = json['country_id'];
							
							$('#shipping-calculator select[name=\'country_id\']').val(country_id);
							$('#shipping-calculator select[name=\'country_id\']').trigger('change');
							
							

                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
			
		})
		
		$('select[name=\'shipping_address_id\']').trigger('change');
		<?php } ?>
        //--></script>
    <p><?php echo $text_shipping_method; ?></p>
    <table class="radio">
        <?php foreach ($shipping_methods as $shipping_method) { ?>
        <tr>
            <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
        </tr>
        <?php if (!$shipping_method['error']) { ?>
        <?php foreach ($shipping_method['quote'] as $quote) { ?>
        <tr class="highlight">
            <td><?php if ($quote['code'] == $code || !$code) { ?>
                <?php $code = $quote['code']; ?>
                <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
                <?php } ?></td>
            <td><label for="<?php echo $quote['code']; ?>"><?php echo $quote['title']; ?></label></td>
            <td style="text-align: right;"><label for="<?php echo $quote['code']; ?>"><?php echo $quote['text']; ?></label></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
            <td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
        </tr>
        <?php } ?>
        <?php } ?>
    </table>
    <br />
</div>
<?php } ?>


<br />


<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<div id="payment-methods">
    <p><?php echo $text_payment_method; ?></p>
    <table class="radio">
        <?php foreach ($payment_methods as $payment_method) { ?>
        <tr class="highlight">
            <td><?php if ($payment_method['code'] == $code || !$code) { ?>
                <?php $code = $payment_method['code']; ?>
                <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" id="<?php echo $payment_method['code']; ?>" />
                <?php } ?></td>
            <td><label for="<?php echo $payment_method['code']; ?>"><?php echo $payment_method['title']; ?></label></td>
        </tr>
        <?php } ?>
    </table>
    <br />
</div>
<?php } ?>
<b><?php echo $text_comments; ?></b>
<textarea name="comment" rows="8" style="width: 98%;"><?php echo $comment; ?></textarea>
<br />
<br />
<?php if ($text_agree) { ?>
<div class="buttons" id="agree">
    <div class="right"><?php echo $text_agree; ?>
        <?php if ($agree) { ?>
        <input type="checkbox" name="agree" value="1" checked="checked" />
        <?php } else { ?>
        <input type="checkbox" name="agree" value="1" />
        <?php } ?>

    </div>
</div>
<?php } else { ?>

<?php } ?>
<script type="text/javascript"><!--
    $('input[name=\'shipping_method\']').change(function(){

        reloadTotals();

    });

    function reloadTotals()
    {
        $.ajax({
            url: 'index.php?route=checkout/checkout/reloadTotals',
            type: 'post',
            data: $('#shipping-methods input[type=\'radio\']:checked'),

            dataType: 'json',
            success: function(json) {

                html='';

                $.each( json['totals'], function( key, value ) {
                    html+=   '<tr>';
                    html+=    '<td colspan="4" class="price"><b>'+value['title']+':</b></td>';
                    html+=    '<td class="total">'+value['text']+'</td>';
                    html+=   '</tr>';
                });



                $('.checkout-product tfoot').html(html);

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    $('.colorbox').colorbox({
        width: 640,
        height: 480
    });
    //--></script>

<?php if (!isset($redirect)) { ?>
<div class="checkout-product">
    <table>
        <thead>
        <tr>
            <td class="name"><?php echo $column_name; ?></td>
            <td class="model"><?php echo $column_model; ?></td>
            <td class="quantity"><?php echo $column_quantity; ?></td>
            <td class="price"><?php echo $column_price; ?></td>
            <td class="total"><?php echo $column_total; ?></td>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
            <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <?php foreach ($product['option'] as $option) { ?>
                <br />
                &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                <?php } ?>
                
            </td>
            <td class="model"><?php echo $product['model']; ?></td>
            <td class="quantity"><?php echo $product['quantity']; ?></td>
            <td class="price"><?php echo $product['price']; ?></td>
            <td class="total"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
            <td class="name"><?php echo $voucher['description']; ?></td>
            <td class="model"></td>
            <td class="quantity">1</td>
            <td class="price"><?php echo $voucher['amount']; ?></td>
            <td class="total"><?php echo $voucher['amount']; ?></td>
        </tr>
        <?php } ?>
        </tbody>
        <tfoot>
        <?php foreach ($totals as $total) { ?>
        <tr>
            <td colspan="4" class="price"><b><?php echo $total['title']; ?>:</b></td>
            <td class="total"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
        </tfoot>
    </table>
    <div class="buttons">
        <div class="right">
            <input type="button" onclick="finalize()"  class="button" value="<?php echo $text_order_confirm ?>" />
        </div>
    </div>
</div>
<div class="payment" style="display:none;"></div>
<?php } else { ?>
<script type="text/javascript"><!--
    location = '<?php echo $redirect; ?>';
    //--></script>
<?php } ?>

<script>
    function finalize()
    {

        $.ajax({
            url: 'index.php?route=checkout/checkout/loggedValidate',
            type: 'post',
            data: $('input[type=\'text\'], textarea, select, input[type=\'checkbox\']:checked, input[type=\'radio\']:checked, input[type=\'password\'], input[type=\'hidden\']'),
            dataType: 'json',
            success: function(json) {

                $('.warning, .error').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                } else if (json['error']) {
                    // warningi
                    // guest
                    if (json['error']['warning']) {
                        $('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');
                    }

                    if (json['error']['firstname']) {
                        $('#payment-address input[name=\'firstname\'] + br').after('<span class="error">' + json['error']['firstname'] + '</span>');
                    }

                    if (json['error']['lastname']) {
                        $('#payment-address input[name=\'lastname\'] + br').after('<span class="error">' + json['error']['lastname'] + '</span>');
                    }

                    if (json['error']['email']) {
                        $('#payment-address input[name=\'email\'] + br').after('<span class="error">' + json['error']['email'] + '</span>');
                    }

                    if (json['error']['telephone']) {
                        $('#payment-address input[name=\'telephone\'] + br').after('<span class="error">' + json['error']['telephone'] + '</span>');
                    }

                    if (json['error']['company_id']) {
                        $('#payment-address input[name=\'company_id\'] + br').after('<span class="error">' + json['error']['company_id'] + '</span>');
                    }

                    if (json['error']['tax_id']) {
                        $('#payment-address input[name=\'tax_id\'] + br').after('<span class="error">' + json['error']['tax_id'] + '</span>');
                    }

                    if (json['error']['address_1']) {
                        $('#payment-address input[name=\'address_1\'] + br').after('<span class="error">' + json['error']['address_1'] + '</span>');
                    }

                    if (json['error']['city']) {
                        $('#payment-address input[name=\'city\'] + br').after('<span class="error">' + json['error']['city'] + '</span>');
                    }

                    if (json['error']['postcode']) {
                        $('#payment-address input[name=\'postcode\'] + br').after('<span class="error">' + json['error']['postcode'] + '</span>');
                    }

                    if (json['error']['country']) {
                        $('#payment-address select[name=\'country_id\'] + br').after('<span class="error">' + json['error']['country'] + '</span>');
                    }

                    if (json['error']['zone']) {
                        $('#payment-address select[name=\'zone_id\'] + br').after('<span class="error">' + json['error']['zone'] + '</span>');
                    }

                    // guest shipping
                    if (json['error']['warning']) {
                        $('#shipping-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');

                        $('.warning').fadeIn('slow');
                    }

                    if (json['error']['shipping_firstname']) {
                        $('#shipping-address input[name=\'shipping_firstname\']').after('<span class="error">' + json['error']['shipping_firstname'] + '</span>');
                    }

                    if (json['error']['shipping_lastname']) {
                        $('#shipping-address input[name=\'shipping_lastname\']').after('<span class="error">' + json['error']['shipping_lastname'] + '</span>');
                    }

                    if (json['error']['shipping_email']) {
                        $('#shipping-address input[name=\'shipping_email\']').after('<span class="error">' + json['error']['shipping_email'] + '</span>');
                    }

                    if (json['error']['shipping_telephone']) {
                        $('#shipping-address input[name=\'shipping_telephone\']').after('<span class="error">' + json['error']['shipping_telephone'] + '</span>');
                    }

                    if (json['error']['shipping_address_1']) {
                        $('#shipping-address input[name=\'shipping_address_1\']').after('<span class="error">' + json['error']['shipping_address_1'] + '</span>');
                    }

                    if (json['error']['city']) {
                        $('#shipping-address input[name=\'shipping_city\']').after('<span class="error">' + json['error']['shipping_city'] + '</span>');
                    }

                    if (json['error']['postcode']) {
                        $('#shipping-address input[name=\'shipping_postcode\']').after('<span class="error">' + json['error']['shipping_postcode'] + '</span>');
                    }

                    if (json['error']['country']) {
                        $('#shipping-address select[name=\'shipping_country_id\']').after('<span class="error">' + json['error']['shipping_country'] + '</span>');
                    }

                    if (json['error']['zone']) {
                        $('#shipping-address select[name=\'shipping_zone_id\']').after('<span class="error">' + json['error']['shipping_zone'] + '</span>');
                    }
                    // end shipping

                    // shipping method
                    if (json['error']['shipping']) {
                        $('#shipping-methods').after('<span class="error">' + json['error']['shipping'] + '</span>');


                    }
                    // payment method
                    if (json['error']['payment']) {
                        $('#payment-methods').after('<span class="error">' + json['error']['payment'] + '</span>');


                    }
                    // end warnings
                    if (json['error']['agree']) {
                        $('#agree').after('<span class="error">' + json['error']['agree'] + '</span>');

                    }


                } else {

                    $.ajax({
                        url: 'index.php?route=checkout/checkout/getPayment',
                        dataType: 'json',
                        success: function(json) {
                            if(json['error'])
                            {

                            }
                            else
                            {
                                $('.payment').append(json['payment']);

                                $("#button-confirm").trigger("click");


                            }

                        },
                        error: function(xhr, ajaxOptions, thrownError) {
                            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                        }
                    });

                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        })

    }
</script>
<script>
    $(document).ready(function(){
        reloadTotals();
    })
</script>

</div>

<?php echo $footer; ?>