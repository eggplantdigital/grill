<?php  
	$terms = get_terms( $this->args['options'][0], $this->args['options'][1] ); ?>
<select name="<?php echo $this->get_the_name_attr(); ?>" id="<?php echo $this->id; ?>">
	<?php
	if ( !empty( $terms ) && !is_wp_error( $terms ) ){ 
		?>
		<option value=""><?php _e('--- Select ---', 'grill'); ?></option>
		
		<?php foreach ( $terms as $term ) { 
			?>
    		<option value="<?php echo $term->term_id; ?>" <?php echo ( $this->get_value()==$term->term_id ) ? 'selected' : ''; ?>><?php echo $term->name; ?></option>
	<?php }
	} else {
		echo '<span>'.__('Oops! Nothing Found', 'grill').'</span>';
	} ?>
</select>