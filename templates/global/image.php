<?php if (has_post_thumbnail()): ?>
	<!-- Main Image -->
	<div class="post-prev-img">
		<?php $size = apply_filters( 'grill_thumbnail_size', 'full' ); ?>
		<?php the_post_thumbnail( $size ); ?>
	</div>
	<!-- End Main Image -->
	<?php 
endif;