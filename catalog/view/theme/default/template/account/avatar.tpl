<?php echo $header; ?>

<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
<h1><?php echo $this->language->get('text_preview'); ?></h1>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

        <div class="preview" >
            <img src="<?php echo $avatar; ?>" /></div>
        <label for="file" ><h2><?php echo $this->language->get('text_upload'); ?></h2></label>
        <input type="file" name="file" />
        <div class="error" ><?php echo $error; ?></div><br/>
		<input type="checkbox" value="1" name="default_avatar" ><?php echo $this->language->get('text_default_avatar'); ?>
        <input type="submit" value="<?php echo $this->language->get('text_submit'); ?>" class="action"/>

    </form>
    <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>