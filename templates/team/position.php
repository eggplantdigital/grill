<?php
if ( $p = get_post_meta( get_the_ID(), '_team_position', true ) ) : ?>
<p class="post-meta"><?php echo $p; ?></p>
<?php endif;