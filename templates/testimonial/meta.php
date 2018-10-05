<?php
if ( $p = get_post_meta( get_the_ID(), '_testimonial_position', true ) ) : ?>
	<p class="entry-meta"><?php echo $p; ?></p>
<?php 
endif;