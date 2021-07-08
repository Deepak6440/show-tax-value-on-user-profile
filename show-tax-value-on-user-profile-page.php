add_action( 'show_user_profile', 'Add_user_fields' );
add_action( 'edit_user_profile', 'Add_user_fields' );

function Add_user_fields( $user ) { 

	/* echo "<pre>";
	print_r($all_tax_rates);
	echo "</pre>"; */
	
		/* echo "<pre>";
	print_r($rates);
	echo "</pre>"; */
	//echo($rates->tax_rate_name);

?>

<h3>Extra profile tax information</h3>

<table class="form-table">

<tbody>
	<?php 
		$all_tax_rates = [];
		$tax_classes = WC_Tax::get_tax_classes(); // Retrieve all tax classes.
		if ( !in_array( '', $tax_classes ) ) { // Make sure "Standard rate" (empty class name) is present.
			array_unshift( $tax_classes, '' );
		}
		?>   
   <tr>
   <th><label for="taxclass">AcuCommerce Tax Class</label></th>
        <td>
            <?php 
            //get dropdown saved value
           $selected = get_the_author_meta( 'user_select', $user->ID ); //there was an extra ) here that was not needed 
            ?>
            <select name="user_select" id="user_select">
				<?php 
			foreach ( $tax_classes as $tax_class ) { // For each tax class, get all rates.
			$taxes = WC_Tax::get_rates_for_tax_class( $tax_class );
			$all_tax_rates = array_merge( $all_tax_rates, $taxes );
					foreach($all_tax_rates as $rates){?>
						<option value="<?php echo $rates->tax_rate_name;?>" <?php echo ($selected == $rates->tax_rate_name)?  'selected="selected"' : '' ?>><?php echo $rates->tax_rate_name;?></option>
				<?php	}
					}//end foreach second
				?>
			</select>
        </td>
		
    </tr>
	</tbody>
</table>
<?php

}//end function
add_action( 'personal_options_update', 'save_user_fields' );
add_action( 'edit_user_profile_update', 'save_user_fields' );

function save_user_fields( $user_id ) {

if ( !current_user_can( 'edit_user', $user_id ) )
    return false;

//save dropdown
update_usermeta( $user_id, 'user_select', $_POST['user_select'] );
}

add_filter( 'woocommerce_find_rates', 'acucommerce_get_tax_class', 1, 2 );

function acucommerce_get_tax_class( $matched_tax_rates, $args) {
	
	global $wpdb;
	
	$current_user = wp_get_current_user();
	$current_user_id = $current_user->ID;
	$tax_class = get_the_author_meta( 'user_select' ,$current_user_id);
	//echo $tax_class;
  
}
