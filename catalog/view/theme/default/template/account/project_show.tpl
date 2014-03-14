<?php echo $header; ?>

<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>

    <table>

       <?php if($project){ ?>
        <tr>
           <td><?php echo $this->language->get('text_title'); ?></td>
           <td><?php echo $project->title; ?></td>
        </tr>
        <tr>
            <td><?php echo $this->language->get('text_description'); ?></td>
            <td><?php echo $project->description; ?></td>
        </tr>
        <tr>
            <td><?php echo $this->language->get('text_inspiration'); ?></td>
            <td><?php echo $project->inspiration; ?></td>
        </tr>
        <tr>
            <td><?php echo $this->language->get('text_colors'); ?></td>
            <td><?php echo $project->colors; ?></td>
        </tr>
        <tr>
            <td><?php echo $this->language->get('text_design'); ?></td>
            <td><img src="<?php echo $prepare_image($project->design); ?>" /></td>
        </tr>
        <tr>
            <td><?php echo $this->language->get('text_prev_release'); ?></td>
            <td><?php echo $project->prev_release; ?></td>
        </tr>
        <tr>
            <td><?php echo $this->language->get('text_portfolio'); ?></td>
            <td><?php echo $project->portfolio; ?></td>
        </tr>
        <tr>
            <td><?php echo $this->language->get('text_date_added'); ?></td>
            <td><?php echo $project->date_added; ?></td>
        </tr>
        <tr>
            <td><?php echo $this->language->get('text_accepted'); ?></td>
            <td>
			<?php foreach($statuses->rows as $status){ ?>
				<?php if($project->accepted == $status['project_status_id']){ ?>
					<?php echo $status['name']; ?>
				<?php } ?>
			<?php } ?>
			</td>
        </tr>

        <?php } ?>

    </table>



    <div class="buttons">
        <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
        <div class="right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
        </div>
    </div>

    <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>