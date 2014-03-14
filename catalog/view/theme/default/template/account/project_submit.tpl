<?php echo $header; ?>

<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
<div class="breadcrumb">	
	<a href="./>">Strona główna</a> » <a href="./index.php?route=account">Konto</a> »	<a href="http://<?php echo $_SERVER['HTTP_HOST']; echo $_SERVER['REQUEST_URI']; ?>"><?php echo $this->language->get('text_submit_project'); ?></a>	  
</div>
<h1><?php echo $this->language->get('text_submit_project'); ?></h1>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

        <table id="proj-submit">
            <tr>
                <td><label for="title" class="required" ><?php echo $this->language->get('text_title'); ?> * </label></td>
                <td>
                    <input type="text" name="title" value="<?php echo $title; ?>" />
                    <div><?php echo $error_title; ?></div>
                </td>
            </tr>
            <tr>
                <td><label class="required"  for="description" ><?php echo $this->language->get('text_description'); ?> * </label></td>
                <td>
                    <textarea  name="description" ><?php echo $description; ?></textarea>
                    <div><?php echo $error_description; ?></div>
                </td>
            </tr>
            <tr>
                <td><label for="inspiration" ><?php echo $this->language->get('text_inspiration'); ?></label></td>
                <td>
                    <input type="text" name="inspiration" value="<?php echo $inspiration; ?>" />
                    <div><?php echo $error_inspiration; ?></div>
                </td>
            </tr>
            <tr>
                <td><label for="colors" ><?php echo $this->language->get('text_colors'); ?></label></td>
                <td>
                    <?php echo generateDropDown(array(1,2,3,4,5),false,false,false,$colors,'colors'); ?>

                </td>
            </tr>
            <tr>
                <td><label class="required"  for="file" ><?php echo $this->language->get('text_design'); ?> * </label></td>
                <td>
                    <input type="file" name="file"  />
                    <div><?php echo $error; ?></div>
                    <a href="<?php echo $this->config->get('config_submission_kit'); ?>" ><?php echo $this->language->get('text_submission_kit'); ?></a>
                </td>
            </tr>
            <tr>
                <td><label for="prev_release" ><?php echo $this->language->get('text_prev_release'); ?></label></td>
                <td>
                    <input type="text" name="prev_release" value="<?php echo $prev_release; ?>" />
                    <div><?php echo $error_prev_release; ?></div>
                </td>
            </tr>
            <tr>
                <td><label for="portfolio" ><?php echo $this->language->get('text_portfolio'); ?></label></td>
                <td>
                    <input type="text" name="portfolio" value="<?php echo $portfolio; ?>" />
                    <div><?php echo $error_portfolio; ?></div>
                </td>
            </tr>

        </table>
		
		<table id="proj-submit-right">
            <tr>
                <td>
                    <?php echo html_entity_decode($this->config->get('config_author_rules')); ?>
                </td>
            </tr>
			<tr>
                <td>
				
				<input type="checkbox" name="confirm" id="confirm" value="1" /><label for="confirm" ><?php echo $this->language->get('text_confirm'); ?>:  <a href="<?php echo $author_regulamin; ?>" ><strong><?php echo $regulamin; ?></strong></a></label>
                    
                    <div><?php echo $error_confirm; ?></div>                
				</td>
            </tr>
			<tr>
				<td>								
					<div class="buttons">
						<div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
						<div class="right">
							<input type="submit" value="<?php echo $button_continue; ?>" class="button action" />
						</div>
					</div>
				</td>
			</tr>
		</table>

    </form>
    <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>