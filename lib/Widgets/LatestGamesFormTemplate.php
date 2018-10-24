<br/>
<fieldset><legend>Widget Title:</legend>   
    <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" />
</fieldset>
<br/>

<fieldset><legend>Game Count:</legend>  
    <input id="<?php echo $this->get_field_id('maxgames'); ?>" type="number" placeholder="6" value="<?php echo $maxgames; ?>" name="<?php echo $this->get_field_name('maxgames'); ?>">
</fieldset>
<br/>

<fieldset><legend>Sort Order:</legend> 
    <select id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>">
        <option value="datenewest"<?php if ($orderby=="datenewest") echo ' selected="true"';  ?>>Date (Newest first)</option>
        <option value="dateoldest"<?php if ($orderby=="dateoldest") echo ' selected="true"';  ?>>Date (Oldest first)</option>
        <option value="titleaz"<?php if ($orderby=="titleaz") echo ' selected="true"';  ?>>Alphabetical Title (A-Z)</option>
        <option value="titleza"<?php if ($orderby=="titleza") echo ' selected="true"';  ?>>Alphabetical Title (Z-A)</option>
        <option value="random"<?php if ($orderby=="random") echo ' selected="true"';  ?>>Random</option>
    </select>   
</fieldset>
<br/>
