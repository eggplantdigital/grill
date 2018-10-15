<?php
/**
 * Create a FontAwesome field
 *
 * This class is called within the Grill_Meta_Box class
 *
 * @package		Grill
 * @since		1.0.2
 */
class Grill_Field_FontAwesome extends Grill_Field {

	/**
	 * Sanitize the file value input
	 * 
	 * @link https://developer.wordpress.org/reference/functions/sanitize_key/
	 * @since 1.0.2
	 */	
	function parse_save_value(){
		$this->value = sanitize_key( $this->value );
	}

	/**
	 * Display the field
	 * 
	 * @since 1.0.2
	 */	
	function get_field() {		
		$fa_list = $this->get_fontawesome_icons_list();
		?>
		<div class="grill-fa-holder">
			<?php add_thickbox(); ?>
			<div class="grill-fa-preview">
				<i class="fa fa-<?php echo $this->get_value(); ?> fa-2x"></i>
			</div>
			<a href="#TB_inline&width=600&height=550&inlineId=grill-fontawesome-modal-<?php echo $this->id; ?>" title="<?php _e('Select Icon', 'grill'); ?>" class="button thickbox"><?php _e('Select Icon', 'grill'); ?></a>
			
			<a href="javascript:" class="fa-clear"><?php _e('Clear Selection', 'grill'); ?></a>
		
			<?php 
			$cat_arr = array();
			$cat_select = '<select name="fa_select" id="grill-change-category" class="fa-select">';
			$ico_list	= '';

			if ( $fa_list != NULL && is_array( $fa_list["icons"] ) ) {
				foreach ( $fa_list["icons"] as $ico ) {
					
					foreach ( $ico["categories"] as $cat ) {
						if ( ! in_array($cat, $cat_arr) ) 
							$cat_select .= '<option value="'.$cat.'">'.$cat.'</option>';
							$cat_arr[] = $cat;
					}

					$categories = implode(';', $ico["categories"] );					
		
					$ico_list .= '<div class="grill-fa '.( ( $this->get_value()==$ico['id'] ) ? 'selected' : '' ).'" data-category="'.$categories.'" '.( ( ! in_array( 'Web Application Icons', $ico["categories"] ) ) ? 'style="display:none;"' : '' ).'>';
					$ico_list .= '<input class="radio" type="radio" name="'.$this->id.'" value="'.esc_attr( $ico['id'] ).'" '.( ( $this->get_value()==$ico['id'] ) ? 'checked' : '' ).' />';
					$ico_list .= '<a href="javascript:" data-id="'.$ico['id'].'"><i class="fa fa-'.$ico['id'].' fa-2x"></i><span class="tooltip">'.$ico['id'].'</span></a>';
					$ico_list .= '</div>';
				}		
				$cat_select .= '</select>';
			}
			?>
			<div id="grill-fontawesome-modal-<?php echo $this->id; ?>" style="display:none;">
				<?php echo $cat_select; ?>
				<div class="grill-fa-modal">
					<div class="grill-fa-clear">
						<?php echo $ico_list; ?>
					</div>
				</div>
			</div>
		</div>		
		<?php
	}
	
	/**
	 * Load JS scripts in the admin area for plugin use.
	 *
	 * @uses admin_enqueue_scripts
	 */
	public function enqueue_scripts() {
        wp_enqueue_script( 'grill-meta-fa-js', GRILL_URL . '/assets/js/meta.fa.js', array( 'jquery' ) );
	}

	/**
	 * Load styles in the admin area for plugin use.
	 *
	 * @uses admin_enqueue_styles
	 */
	public function enqueue_styles() {
		wp_enqueue_style( 'grill-meta-fa-css', GRILL_URL . '/assets/css/font-awesome.min.css', false, '4.2.0');	
	}
	
	/**
	 * FontAwesome 4.6.3 json list
	 *
	 * A helpful list FontAwesome fonts for use within the beautiful meta fields of this theme.
	 *
	 * @link https://github.com/FortAwesome/Font-Awesome/blob/master/src/icons.yml
	 * @link converted to json with help from http://yamltojson.com/
	 */
 	public function get_fontawesome_icons_list() {
		$fa_json  = wp_remote_fopen( GRILL_URL . '/assets/fonts/fontawesome.json' );
		return $this->json_decode( $fa_json, true );
	}
}