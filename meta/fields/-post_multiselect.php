<?php 
	
$val = (array) $this->get_value();

$name = $this->get_the_name_attr();
$name .= ! empty( $this->args['multiple'] ) ? '[]' : null;

$args = $this->args['query'];

$args['post__in'] = $val;

$the_query = new WP_Query( $args );
?>
<select
	<?php $this->id; ?>
	<?php printf( 'name="%s"', esc_attr( $name ) ); ?>
	<?php echo ! empty( $this->args['multiple'] ) ? 'multiple' : '' ?>
	<?php $this->class_attr( 'grill-multiselect-ajax' ); ?>
>

	<?php 
	if ( $the_query->have_posts() ){ 
		?>
		<option value=""><?php _e('--- Select ---', 'grill'); ?></option>
		
		<?php while ( $the_query->have_posts() ) { 
				$the_query->the_post(); ?>
				
    		<option value="<?php echo get_the_ID(); ?>" <?php echo ( in_array( get_the_ID(), $val ) ) ? 'selected' : ''; ?>><?php echo get_the_title(); ?></option>
			
		<?php }
		wp_reset_postdata();
	}
	?>

</select>

<script type="text/javascript">
	(function($) {

		var ajaxData = {
			action  : 'grill_post_select',
			post_id : '<?php echo intval( get_the_id() ); ?>', // Used for user capabilty check.
			nonce   : <?php echo json_encode( wp_create_nonce( 'grill_select_field' ) ); ?>,
			query   : <?php echo json_encode( $this->args['query'] ); ?>
		};

		$('.grill-multiselect-ajax').select2({
	  		ajax: {
	    			url: <?php echo json_encode( esc_url( admin_url( 'admin-ajax.php' ) ) ); ?>,
	    			type: 'POST',
	    			dataType: 'json',
	    			delay: 250,
	    			data: function ( params ) {
						ajaxData.query.q = params.term;	
						return ajaxData;
	    			},
	    			processResults: function( data ) {
					var options = [];
					if ( data ) {
						console.log( data );
						// data is the array of arrays, and each of them contains ID and the Label of the option
						$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
							options.push( { id: text[0], text: text[1]  } );
						});
	 
					}
					return {
						results: options
					};
				},
				cache: true
			},
			minimumInputLength: 2
		});
	})( jQuery );
</script>	