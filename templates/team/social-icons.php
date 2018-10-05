<?php
$fa = get_post_meta( get_the_ID(), '_team_facebook', true );
$tw = get_post_meta( get_the_ID(), '_team_twitter', true );
$li = get_post_meta( get_the_ID(), '_team_linkedin', true );
$in = get_post_meta( get_the_ID(), '_team_instagram', true );				
	
if ( $fa || $tw || $li || $in ) :
	 
	$social = array(); ?>
	<ul class="ico-social color square tiny">
		<?php
		$social = array(
			array( 'facebook', $fa, __('Connect with me on Facebook', 'grill') ),
			array( 'twitter', $tw, __('Follow me on Twitter', 'grill') ),
			array( 'linkedin', $li, __('Connect with me on Linkedin', 'grill') ),
			array( 'instagram', $in, __('Follow me on Instagram', 'grill') ),
		);
		
		foreach ( $social as $ico ) :
			if ( $ico[1] ) : ?>
			
			<li><a href="<?php echo $ico[1]; ?>" target="_blank" title="<?php echo $ico[2]; ?>" class="<?php echo $ico[0]; ?>"><i class="fa fa-<?php echo $ico[0]; ?>"></i></a></li>
		<?php
			endif; ?>
		<?php endforeach; ?>
	</ul>	
<?php endif;