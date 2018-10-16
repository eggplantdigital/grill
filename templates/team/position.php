<?php
if ( $p = get_post_meta( get_the_ID(), '_team_position', true ) ) : ?>
<p class="post-meta"><?php echo esc_attr($p); ?></p>
<?php endif;