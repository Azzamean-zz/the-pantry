<?php

global $sm_text_domain;

$sm_text_domain = (defined('SM_TEXT_DOMAIN')) ? SM_TEXT_DOMAIN : 'smart-manager-for-wp-e-commerce';

function set_all_privilege($post) {
        global $wpdb, $sm_text_domain;
        $_POST = $post;

        //Company logo update code
        $company_logo = $post['smart_manager_company_logo'];
        update_option('smart_manager_company_logo', $company_logo);

        $query = "INSERT INTO {$wpdb->prefix}options ( option_name, option_value, autoload) VALUES ";
        $all_roles = get_editable_roles();
        if( isset( $all_roles['administrator'] ) ){
            unset( $all_roles['administrator']);
        }
        $role = array_keys($all_roles);
        for ( $i = 0; $i < count($role); $i++) {
                if ( $role[$i] != 'administrator' )	{
                        if (isset($_POST['sm_'.$role[$i].'_dashboard'])) {
                                $query .= "( 'sm_$role[$i]_dashboard', '".serialize($_POST['sm_'.$role[$i].'_dashboard'])."', 'no'), ";
                        } else {
                                $query .= "( 'sm_$role[$i]_dashboard', '', 'no'), ";
                        }
                }
        }
        $query = substr( $query, 0, -2);
        $query .= " ON DUPLICATE KEY UPDATE option_value = VALUES ( option_value );";
        $wpdb->query ($query);
        echo "<div id='message' class='updated fade'><p>";
        printf ( __ ( 'Smart Manager Settings <b>Updated</b>',$sm_text_domain ));
        echo "</p></div>";
        get_all_privilege();
}

function get_all_privilege() {
        global $wpdb, $sm_text_domain;
        $query = "SELECT option_name, option_value FROM {$wpdb->prefix}options WHERE option_name LIKE 'sm_%_dashboard';";
        $results = $wpdb->get_results ($query);
        $rows_roles = $wpdb->num_rows;

        $roles = array();
        foreach ($results as $obj) {
                $roles_detail = unserialize($obj->option_value);
                $roles[$obj->option_name] = $roles_detail;
        }

        $all_roles = get_editable_roles();

        if( isset( $all_roles['administrator'] ) ){
            unset( $all_roles['administrator']);
        }

        foreach ( $all_roles as $role => $details) {
                $name = translate_user_role($details['name'] );
                $checked = array();

                $roles_count = ( !empty($roles['sm_'.$role.'_dashboard']) ) ? count($roles['sm_'.$role.'_dashboard']) : 0;

                if($rows_roles > 0 && $roles_count > 0) {
                    for ( $i = 0; $i < $roles_count; $i++ ) {
                            $checked[$roles['sm_'.$role.'_dashboard'][$i]] = 'checked';
                    }    
                }

                $products_checked = (isset($checked['Products']) && $checked['Products'] === 'checked') ? 'checked' : 'unchecked';
                $customers_orders_checked = (isset($checked['Customers_Orders']) && $checked['Customers_Orders'] === 'checked') ? 'checked' : 'unchecked';
                if ( $name != 'Administrator' ) {
                        echo "
                                <tr>
                                        <th scope='row'>$name</th>
                                        <td align='center'><input name='sm_".$role."_dashboard[]' type='checkbox' id='Products' value='Products' ".$products_checked."></td>
                                        <td align='center'><input name='sm_".$role."_dashboard[]' type='checkbox' id='Customers_Orders' value='Customers_Orders' ".$customers_orders_checked."></td>
                                </tr>
                        ";
                }
        }


}		

?>

<div class="wrap">
<div id="icon-smart-manager" class="icon32" style="background: url('<?php echo plugins_url( '', __FILE__ );?>/../images/logo-32x32.png') no-repeat scroll transparent;"><br/></div>
<h2>Smart Manager Settings</h2>
	
<form action="" method="post">

<h3 style="font-size:120%;"><?php _e('Privileges for Smart Manager Old for different System Roles',$sm_text_domain);?></h3><br/>

<table class="form-table">
	<tbody>
		<tr>
			<th><b>Roles</b></th>
			<td align="center"><b><?php _e('Can Manage Products?',$sm_text_domain);?></b></td>
			<td align="center"><b><?php _e('Can Manage Customers & Orders?',$sm_text_domain)?></b></td>
		</tr>
		<?php
			if (isset($_POST['submit']) && $_POST['submit'] === 'Apply') {
				set_all_privilege($_POST);
			} else {
				get_all_privilege();
			}
		?>
		
	</tbody>
</table>

<!-- Code for Display part for entering the Company Logo -->
<br/><br/>

<?php 
    
    if( class_exists( 'Smart_Manager_Pro_Access_Privilege' ) && is_callable( array('Smart_Manager_Pro_Access_Privilege', 'render_company_logo_settings') ) ) {
        Smart_Manager_Pro_Access_Privilege::render_company_logo_settings();
    }
    
?>

<p class="submit"><input type="submit" name="submit" id="submit" class="button-primary" value="Apply">
<a href="<?php echo admin_url('admin.php?page=smart-manager'); ?>"> <?php _e('Back to Smart Manager', 'smart-manager-for-wp-e-commerce') ?> </a>
</p>
</form>
</div>
