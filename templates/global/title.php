<?php
// 1. The post is set to have a single post
if ( grill_get_setting('type') == 1 && ! is_single() ) : ?>
<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3>

<?php 
// 2. The post is set to have a pop up
elseif ( grill_get_setting('type') == 2 && ! is_single() ) : ?>
<h3 class="entry-title"><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" class="popup"><?php the_title(); ?></a></h3>

<?php
// default
else : ?>
<h3 class="entry-title"><?php the_title(); ?></h3>
<?php endif; ?>