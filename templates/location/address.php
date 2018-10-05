<?php
if ( $add = get_post_meta( get_the_ID(), '_location_address', true ) ) : ?>
	<li class="post-address"><i class="fa-li fa fa-map-marker"></i><?php echo $add; ?></li>
<?php endif;