<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
    <?php echo $content_top; ?>

    <form method="post" action="<?php echo $action; ?>" >
        <div class="error" ><?php echo $error_login; ?></div>
        <div class="error" ><?php echo $error_confirmed; ?></div>

        <label for="email" ><?php echo $this->language->get('text_email'); ?></label>
        <input type="text" name="email" value="<?php echo $email; ?>" />
        <div class="error" ><?php echo $error_email; ?></div>

        <label for="password" ><?php echo $this->language->get('text_password'); ?></label>
        <input type="text" name="password" value="<?php echo $password; ?>" />
        <div class="error" ><?php echo $error_password; ?></div>


        <input type="submit" value="<?php echo $this->language->get('text_submit'); ?>" />

    </form>

    <a href="<?php echo $register; ?>" ><?php echo $this->language->get('text_register'); ?></a>

    <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>