<?php echo $header; ?>

<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>

    <table style="display:inline-block">
        <tr>
            <td><?php echo $this->language->get('text_title'); ?></td>

            <td><?php echo $this->language->get('text_design'); ?></td>

            <td><?php echo $this->language->get('text_date_added'); ?></td>

            <td><?php echo $this->language->get('text_accepted'); ?></td>

            <td><?php echo $this->language->get('text_action'); ?></td>
        </tr>
        <?php foreach($projects as $project){ ?>
        <tr>
            <td><?php echo $project->title; ?></td>

            <td>
                <img src="<?php echo $prepare_image($project->design); ?>" />
            </td>

            <td><?php echo $project->date_added; ?></td>

            <td><input type="checkbox" disabled="disabled" <?php if($project->accepted){ echo 'checked="checked"'; } ?> /> </td>

            <td>
                 <a href="<?php echo $edit($project->ID); ?>" ><?php echo $this->language->get('text_edit'); ?></a></td>
        </tr>
        <?php } ?>
    </table>



        <div class="buttons">
            <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
            <div class="right">
              <a class="button action" href="<?php echo $add; ?>" ><?php echo $this->language->get('text_submit'); ?></a>
            </div>
        </div>

    <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>