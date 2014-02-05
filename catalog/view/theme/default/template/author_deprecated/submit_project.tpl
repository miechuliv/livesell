<?php echo $header; ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
    <?php echo $content_top; ?>

        <form >
            Tytuł <input type="text" name="title" />
            Opis  <input type="text" name="description" />
            Inspiracja  <input type="text" name="inspiration" />
            Ile kolorów  <select name="colors" >
                        <option value="1" >1</option>
                        <option value="1" >2</option>
                        <option value="1" >3</option>
                </select>
            Plik z szablonem: <input type="file" name="pattern" />
            Czy wczesniej używano? <input type="text" name="previous_release" />
            Link portfolio <input type="text" name="portfolio" />
            Akceptacja <input type="checkbox" value="1" />
        </form>

    <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>
