<?php echo $header; ?>

<div id="content">
<div class="box">
    <div class="heading">
        <h1><img src="view/image/product.png" alt="" /> <?php echo $this->language->get('heading_title'); ?></h1>
    </div>
<div class="content">

    <table class="list" >
        <thead>
            <tr>
                <td><?php echo $this->language->get('text_title'); ?></td>

                <td><?php echo $this->language->get('text_design'); ?></td>

                <td><?php echo $this->language->get('text_date_added'); ?></td>

                <td><?php echo $this->language->get('text_status'); ?></td>

                <td><?php echo $this->language->get('text_author'); ?></td>

                <td><?php echo $this->language->get('text_action'); ?></td>
            </tr>
        <tr>
            <td>

            </td>
            <td> </td>

            <td>
                <input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $this->language->get('text_date_start'); ?>" />
                <input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="<?php echo $this->language->get('text_date_end'); ?>" /></td>



            <td><?php echo generateDropDown($statuses->rows,'project_status_id','name',false,$status,'status',true); ?> </td>

            <td><input type="text" name="author" value="<?php echo $author; ?>" placeholder="<?php echo $this->language->get('text_author'); ?>" /></td>

            <td><a class="button" onclick="filter();return false" ><?php echo $this->language->get('text_filter'); ?></a></td>
        </tr>
        </thead>
        <tbody>

        <?php if(!empty($projects)){ ?>
        <?php foreach($projects as $project){ ?>
        <tr>
            <td><?php echo $project->title; ?></td>

            <td>
                <img src="<?php echo $prepare_image($project->design); ?>" />
            </td>

            <td><?php echo $project->date_added; ?></td>



            <td><?php echo $project->status; ?></td>

            <td><?php echo $project->author; ?></td>

            <td>
                <a href="<?php echo $edit($project->ID); ?>" ><?php echo $this->language->get('text_edit'); ?></a><br/>
                <a href="<?php echo $delete($project->ID); ?>" ><?php echo $this->language->get('text_delete'); ?></a><br/>
                <a target="_blank" href="<?php echo $campaign($project->ID); ?>" ><?php echo $this->language->get('text_create_campaign'); ?></a>
            </td>
        </tr>
        <?php } ?>
        <?php } ?>
        </tbody>

    </table>

    <div class="pagination"><?php echo $pagination; ?></div>

    </div>
</div>
</div>


<script type="text/javascript"><!--
    function filter() {
        url = 'index.php?route=project/project/showList&token=<?php echo $this->session->data["token"]; ?>';


        var date_start = $('input[name=\'date_start\']').attr('value');

        if (date_start) {
            url += '&date_start=' + encodeURIComponent(date_start);
        }

        var date_end = $('input[name=\'date_end\']').attr('value');

        if (date_end) {
            url += '&date_end=' + encodeURIComponent(date_end);
        }

        var status = $('select[name=\'status\'] option:selected').attr('value');

        if (status) {
            url += '&status=' + encodeURIComponent(status);
        }

        var author = $('input[name=\'author\']').attr('value');

        if (author) {
            url += '&author=' + encodeURIComponent(author);
        }


        location = url;
    }

    $('input[name=\'date_start\']').datepicker({dateFormat: 'yy-mm-dd'});
    $('input[name=\'date_end\']').datepicker({dateFormat: 'yy-mm-dd'});
    //--></script>
<?php echo $footer; ?>