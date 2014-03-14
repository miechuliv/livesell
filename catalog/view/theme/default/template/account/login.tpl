<?php echo $header; ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php // echo $column_left; ?><?php // echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>

  <div class="login-content">
    <div class="left">
      <h1><?php echo $text_register; ?></h1>
      <div class="content">
        <p><?php echo $text_new_customer; ?></p>
        <p><?php echo $text_register_account; ?></p>
        <a href="<?php echo $register; ?>" class="button"><?php echo $text_register; ?>!</a></div>
<?/*
	<a href="javascript:void(0);" id="fbbut"></a>
	
	<div id="fbregi">
	    <fb:registration width="300px" onvalidate="validateRegister" fields="[{'name':'name'},{'name':'email_shop','type':'text','description':'email'},{'name':'password'}]" redirect_uri="<?php echo HTTP_SERVER; ?>index.php?route=account/register/fb" />
        <script type="text/javascript" >
            function validateRegister(form)
            {
                console.log(form);

                err = {};

                $.ajax({
                    url: 'index.php?route=account/register/checkEmail&email='+form.email_shop,
                    type: 'get',
                    dataType: 'json',
                    async: false,
                    success: function(json)
                    {
                        if(json['res'] != 'success')
                        {
                            err.email_shop = json['res'];
                        }
                    }
                });

                return err;

            }
			$('#fbbut').click(function(){
				$("#fbregi").css('height','100%');
			});
        </script>	
	</div>
*/?>
    </div> 
  	<div style="padding:0 40px; border-left:1px solid #ddd; display:inline-block; width:30%;">
      <h1><?php echo $heading_title; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="content logon">
          <p><?php echo $text_i_am_returning_customer; ?></p>
          <b><?php echo $entry_email; ?></b><br />
          <input type="text" name="email" value="<?php echo $email; ?>" />
          <br />
          <br />
          <b><?php echo $entry_password; ?></b><br />
          <input type="password" name="password" value="<?php echo $password; ?>" />
          <br /><br/>
          <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br />
          <br />

          <input type="submit" value="<?php echo $button_login; ?>" class="button action" />
          <a  href="<?php echo $loginUrl; ?>" class="button action" ><?php echo $this->language->get('text_facebook_login'); ?></a>
          <?php if ($redirect) { ?>
          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
          <?php } ?>
        </div>
      </form>
    </div>

  </div>
  <?php /*  <iframe width="500px" height="500px" src="https://www.facebook.com/plugins/registration?
             client_id=495578190494203&
             redirect_uri=
             &fields=name,email,password"
            >
    </iframe> */ ?>

    <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#login input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#login').submit();
	}
});
//--></script> 
<?php echo $footer; ?>