<?php
if ( $phone = get_post_meta( get_the_ID(), '_location_content_phone', true ) ) : ?>
<li class="post-phone"><i class="fa fa-li fa-phone"></i><?php echo $phone; ?></li>
<?php endif;