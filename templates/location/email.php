<?php
if ( $email = get_post_meta( get_the_ID(), '_location_contact_email', true ) ) : ?>
<li class="post-email "><a href="mailto: <?php echo $email; ?>"><i class="fa fa-li fa-paper-plane"></i><?php echo $email; ?></a></li>
<?php endif;