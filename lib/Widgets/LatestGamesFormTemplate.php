<br/>
<fieldset><legend><?php echo wp_strip_all_tags( __( 'Widget Title', 'vegashero' ) ); ?>:</legend>   
	<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
</fieldset>
<br/>

<fieldset><legend><?php echo wp_strip_all_tags( __( 'Game Count', 'vegashero' ) ); ?>:</legend>  
	<input id="<?php echo $this->get_field_id( 'maxgames' ); ?>" type="number" placeholder="6" value="<?php echo $maxgames; ?>" name="<?php echo $this->get_field_name( 'maxgames' ); ?>">
</fieldset>
<br/>

<fieldset><legend><?php echo wp_strip_all_tags( __( 'Sort Order', 'vegashero' ) ); ?>:</legend> 
	<select id="<?php echo $this->get_field_id( 'orderby' ); ?>" name="<?php echo $this->get_field_name( 'orderby' ); ?>">
		<option value="datenewest"
		<?php
		if ( $orderby == 'datenewest' ) {
			echo ' selected="true"';}
		?>
		><?php echo wp_strip_all_tags( __( 'Date (Newest first)', 'vegashero' ) ); ?></option>
		<option value="dateoldest"
		<?php
		if ( $orderby == 'dateoldest' ) {
			echo ' selected="true"';}
		?>
		><?php echo wp_strip_all_tags( __( 'Date (Oldest first)', 'vegashero' ) ); ?></option>
		<option value="titleaz"
		<?php
		if ( $orderby == 'titleaz' ) {
			echo ' selected="true"';}
		?>
		><?php echo wp_strip_all_tags( __( 'Alphabetical Title (A-Z)', 'vegashero' ) ); ?></option>
		<option value="titleza"
		<?php
		if ( $orderby == 'titleza' ) {
			echo ' selected="true"';}
		?>
		><?php echo wp_strip_all_tags( __( 'Alphabetical Title (Z-A)', 'vegashero' ) ); ?></option>
		<option value="random"
		<?php
		if ( $orderby == 'random' ) {
			echo ' selected="true"';}
		?>
		><?php echo wp_strip_all_tags( __( 'Random', 'vegashero' ) ); ?></option>
	</select>   
</fieldset>
<br/>
