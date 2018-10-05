<select name="<?php echo $this->get_the_name_attr(); ?>" id="<?php echo $this->id; ?>">
	<?php if ( is_array( $this->args['options'] ) ) foreach ( $this->args['options'] as $key => $value ) : ?>
    	<option value="<?php echo $key; ?>" <?php echo ( $this->get_value()==$key ) ? 'selected' : ''; ?>><?php echo $value; ?></option>
	<?php endforeach; ?>
</select>