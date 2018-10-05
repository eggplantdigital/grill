<?php
if( !class_exists('Grill_Grid') ) :
/**
 * Grill Grid
 *
 * This class is used to create a grid and control the number of items in a row
 * It helps to make simple grid quicker and is based on Bootstrap.
 *
 * @package		WordPress
 * @subpack		Grill
 * @since		Grill 1.0.0
 */
class Grill_Grid {
	
	public $row = 'div';
	
	public $row_class = 'row';
	
	public $item = 'div';

	public $count = 1;
	
	public $row_count = 1;
	
	/**
	 * Grill Grid initialize.
	 *
	 * Setup the default args and add them to variables to build the grid.
	 *
	 * @param array $args (Optional)
	 *
	 *     @type string 	$row set the html tag for each row, defaults is 'div'
	 *     @type string 	$row_class set the class for each row, default is 'row'.
	 *     @type string 	$item the html tag for each item within the row, default is 'div'.
	 *     @type string 	$item_class set the class for each item, default is 'item'.
	 *     @type numercial	$items_per_row set how many items will appear in one row, default is 1, use -1 to not have row.
	 *     @type numercial	$total_posts set how many posts are will appear in the output, default is 1.
	 *     @type boolean	$echo If set to true the html will be displayed or false will return as a string.
	 *     
	 * @param array	$query Pass the query that paginatio is being created for, by default uses $wp_query.
	 *
	 * @since Grill 1.0.0
	 */
	public function __construct() {}
		
	function post_count() {
		global $wp_query;
		return $wp_query->post_count;		
	}
	
	function items_per_row() {
		global $sc_atts;
		
		if ( isset( $sc_atts['cols'] ) )
			$cols = $sc_atts['cols'];
		else
			$cols = 1;	
			
		return apply_filters( 'grill_loop_columns', $cols );	
	}
	
	function item_class() {
		
		switch ( $this->items_per_row() ) {
		    case 1:
		        $item_class = 'col-sm-12';
		        break;
		    case 2:
		        $item_class = 'col-sm-6';
		        break;
		    case 3:
		        $item_class = 'col-sm-4';
		        break;
		    case 4:
		    	$item_class = 'col-sm-3';
		    	break;
		    case 5:
				$item_class = 'col-sm-2';
				break;
			default:
				$item_class = 'col-sm-4';
		        break;
		}

		return apply_filters( 'grill_loop_item_class', $item_class );
	}
	
	// Creates the opening wrapper for the grid items
	function begin() {
		
		// define output var
		$output = '';
		
		// create the row class
		$row_class  = ($this->row_class) ? ' class="'.$this->row_class .'"' : '';
		
		// start off the row, unless we're displaying on row
		if ($this->row_count == 1 && $this->items_per_row()!=-1) {
			$output .= '<'.$this->row.$row_class.'>';
		}
		
		// add first and last classes based on the count
		$first = ($this->row_count == 1) ? ' first' : '';
		$last  = ($this->count % $this->items_per_row() == 0) ? ' last' : '';
		
		// add the item node
		$output .= '<'.$this->item.' class="'.$this->item_class().$first.$last.'">';
		
		// echo the output created above
		echo $output;
	}	
	
	// Creates the closing wrapper for the grid items
	function finish() {
		
		// close off the item
		$output = '</'.$this->item.'>';
		
		// if we are not on one row			
		if ($this->items_per_row()!=-1) {
			
			// we've reached the end of the row so close off the div
			if ( $this->count % $this->items_per_row() == 0 ) {
				$output .= '</'.$this->row.'>' . '<!-- /'.$this->row_class.'-->';
				$this->row_count = 0;
				
			// we've reached the end of the posts - close off the div
			} elseif ( $this->count == $this->post_count() ) {
				$output .= '</'.$this->row.'>' . '<!-- /'.$this->row_class.'-->';
				$this->count = 0;
			}
		}
		
		// increase our counts by 1
		$this->count++;
		$this->row_count++;
		
		// echo the output created above
		echo $output;
	}
}
endif;