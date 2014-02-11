<?php echo $header; ?>

<div id="content">
    <div class="box">
        <div class="heading">
            <h1><img src="view/image/product.png" alt="" /> <?php echo $this->language->get('heading_title'); ?></h1>
            <a class="button" target="_blank" href="<?php echo $campaign($project->ID); ?>" ><?php echo $this->language->get('text_campaign'); ?></a>
        </div>
        <div class="content">

            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

                <table>
                    <tr>
                        <td><label for="author" ><?php echo $this->language->get('text_author'); ?></label></td>
                        <td>
                            <input disabled="disabled" type="text" name="author" value="<?php echo $project->author; ?>" />

                        </td>
                    </tr>
                    <tr>
                        <td><label for="title" ><?php echo $this->language->get('text_title'); ?></label></td>
                        <td>
                            <input type="text" name="title" value="<?php echo $title; ?>" />
                            <div><?php echo $error_title; ?></div>
                        </td>
                    </tr>
                    <tr>
                        <td><label for="description" ><?php echo $this->language->get('text_description'); ?></label></td>
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
                        <td><label for="design" ><?php echo $this->language->get('text_design'); ?></label></td>
                        <td>
                            <img src="<?php echo $prepare_image($project->design); ?>" />

                        </td>
                    </tr>
                    <tr>
                        <td><label for="colors" ><?php echo $this->language->get('text_colors'); ?></label></td>
                        <td>
                            <?php echo generateDropDown(array(1,2,3,4),false,false,false,$colors,'colors'); ?>

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
                    <tr>
                        <td><label for="status" ><?php echo $this->language->get('text_status'); ?></label></td>
                        <td>
                            <?php echo generateDropDown($statuses->rows,'project_status_id','name',false,$status,'status',true); ?>
                            <div><?php echo $error_status; ?></div>
                        </td>
                    </tr>

                </table>

                <div class="buttons">
                    <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
                    <div class="right">
                        <input type="submit" value="<?php echo $button_continue; ?>" class="button" />
                    </div>
                </div>
            </form>

        </div>
    </div>
</div>


<?php echo $footer; ?>