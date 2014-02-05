<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
    <?php echo $content_top; ?>

    <form method="post" action="<?php echo $action; ?>" >
       <label for="name" ><?php echo $this->language->get('text_name'); ?></label>
       <input type="text" name="name" value="<?php echo $name; ?>" />
        <div class="error" ><?php echo $error_name; ?></div>

        <label for="email" ><?php echo $this->language->get('text_email'); ?></label>
        <input type="text" name="email" value="<?php echo $email; ?>" />
        <div class="error" ><?php echo $error_email; ?></div>

        <label for="confirm_email" ><?php echo $this->language->get('text_confirm_email'); ?></label>
        <input type="text" name="confirm_email" value="<?php echo $confirm_email; ?>" />
        <div class="error" ><?php echo $error_confirm_email; ?></div>

        <label for="password" ><?php echo $this->language->get('text_password'); ?></label>
        <input type="password" name="password" value="<?php echo $password; ?>" />
        <div class="error" ><?php echo $error_password; ?></div>

        <label for="confirm_password" ><?php echo $this->language->get('text_confirm_password'); ?></label>
        <input type="password" name="confirm_password" value="<?php echo $confirm_password; ?>" />
        <div class="error" ><?php echo $error_confirm_password; ?></div>

        <input type="submit" value="<?php echo $this->language->get('text_submit'); ?>" />

    </form>

    <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>
