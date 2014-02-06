<?php echo $header; ?>

<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>

    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

        <div class="preview" >
            <p ><?php echo $this->language->get('text_preview'); ?></p>
            <img src="<?php echo $avatar; ?>" /></div>
        <label for="file" ><?php echo $this->language->get('text_upload'); ?></label>
        <input type="file" name="file" />
        <div class="error" ><?php echo $error; ?></div>
        <input type="submit" value="<?php echo $this->language->get('text_submit'); ?>" />

    </form>
    <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>