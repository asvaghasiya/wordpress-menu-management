<?php
if(!class_exists('WP_Menu_Management'))
{
	class WP_Menu_Management
	{
	/*
	 * Construct the plugin object
	 * @since 1.0.0
	 * actions/filters for the plugin to initialize.
	 */
		public function __construct()
		{
		    add_action('admin_menu', array(&$this, 'func_menu_mngt_admin_menu'));
		    add_action( 'admin_enqueue_scripts', array(&$this, 'funct_menu_mngt_admin_styles'));
		    add_action( 'admin_enqueue_scripts', array(&$this, 'funct_menu_mngt_admin_scripts'));
            add_action('init', array($this, 'func_menu_mngt_main'));
		} 
		public function func_menu_mngt_admin_menu()
		{
			add_theme_page( 'WP Menu Meanagement','WP Menu Meanagement','manage_options','wp-menu-mngt',array(&$this,'func_wp_menu_mngt_submenu'));	
	    }
		public function func_wp_menu_mngt_submenu()
	    { ?>
        <div class="wp-menu-management-main">
			<div class="container">
			    <h2>WP Menu Management Settings</h2>
				<div class="outer_main_menu">
					<div class="outer_tab">
		                <div class="tab-main">
		                    <ul class="tab-list" id="tabs">
				                <li class="active" data-tab="one"><?php _e( 'Import Menu', 'wp-menu-management' );?></li>
					            <li data-tab="two"><?php _e( 'Export Menu', 'wp-menu-management' );?></li>
					            <li data-tab="three"><?php _e( 'Delete Menu', 'wp-menu-management' ); ?></li>
				            </ul>
				        </div>
				        <div class="contents">
		                    <div class="tab-content active one">
			                    <form id="import" action="#nav-home" method="post" name="upload_excel" enctype="multipart/form-data">
						            <input type="file" name="upload_file" id="upload_file" class="input-large" required>
						            <input type="submit" data-num="0" name ="Import" class="button button-primary button-large " value="Import">
						        </form>
			                </div>
			                <div class="tab-content two">
			                   <form id="export" action="#nav-profile" method="post" name="upload_excel" enctype="multipart/form-data">
			                        <?php $menus = get_terms('nav_menu');  
									    $tmenus = count( $menus ); 
									    if( !empty( $tmenus ) ) { ?>
					                        <select name="select-exp-menu" class="select-export-menu" id="select-export-menu">
					                            <option id="Select" value="select-menu"><?php _e( 'Select Menu', 'wp-menu-management' ); ?></option>
											    <?php
												foreach( $menus as $menu ){ ?>
											        <option value="<?php echo $menu->term_id; ?>"><?php echo $menu->name; ?></option>
											    <?php } ?>
											    <option value="exp-all-menu">Export All</option>
								            </select>
								            <input type="submit" data-num="0" name ="Export" class="button button-primary button-large btn-export-menus" value="<?php _e( 'Export Menu', 'wp-menu-management' ); ?> ">
								            <?php  $select_menu_err = "Please Select Menu"; ?>
								    <?php } else {
								        echo "<h4>No menu items were found!</h4>";
								    } ?>
							    </form>
			                </div>
			                <div class="tab-content three">
			                    <form id="delete" action="#nav-delete" method="post" name="upload_excel" enctype="multipart/form-data">
		                            <?php $get_menus = get_terms('nav_menu');    
		                                $tmenus = count( $get_menus ); 
										if( !empty( $tmenus ) ) { ?>
										  <select name="select-delete-menu" class="select-delete-menu" id="select-delete-menu">
								                <option  name="select-menu-empt" value="select-menu-empt" ><?php _e("Select Menu"); ?></option>
								                <?php  foreach( $get_menus as $get_menu) { ?>
								                <option value="<?php echo $get_menu->term_id; ?>" id="<?php echo $get_menu->term_id; ?>">
								                    <?php echo $get_menu->name; ?></option>
								                <?php } ?>
								                <option name="all-delete" value="all-delete" id="all-delete"><?php _e("Delete All"); ?></option>
										   </select>
										   <input type="submit" name="Delete" class="button button-primary button-large btn-delete-menus" value="Delete Menu">
										<?php } else {
								            echo "<h4>No menu items were found!</h4>";
								        } ?>
								</form>
							</div>
		                </div>
		            </div>  
		            <div class="menu_description">
		            	<div class="inner_menu_desc">
		            	<?php _e( '<p><strong>Important Notes:</strong></p><p><strong>-></strong> Menu name will only appear in the dropdown when  page, post, or custom link under the menu, Check Menu in <strong>Appearance->menus</strong></p>
		            		<p><strong>-></strong>You can import only .csv file that you are export from our plugin</p>
		            		<p><strong>-></strong>If you can exported file import into other site then menu structure display same but menu page title shows like <strong>#0 (no title)</strong></p>', 'wp-menu-management' ); ?>
		            	</div>
		            </div>  
		        </div>    
            </div>
        </div>    
        <?php }
	/*
	 * Plugin main function for performing all operation
	 * Converting Menu data to CSV
	 * Import csv data
	 * Delete Menu
	 */
	public function func_menu_mngt_main() {
		ob_start();
		global $wpdb;
		$err = '';
	    /* 
	     * Import csv data
	     */
	    if( isset( $_POST['Import'] ) ) {
            
	    	$table_wp_postmeta = $wpdb->prefix."postmeta";
	    	$table_wp_posts = $wpdb->prefix."posts";
	    	$table_wp_terms = $wpdb->prefix."terms";
	    	$table_wp_tr = $wpdb->prefix."term_relationships";
	    	$table_tt = $wpdb->prefix."term_taxonomy";
	        $file = fopen($_FILES['upload_file']['tmp_name'],"r");
     
			while(! feof ( $file ) )  {
			    $data =fgetcsv($file);   
			    $success = $wpdb->insert( $table_wp_postmeta, array( 'meta_id' => $data[0], 'post_id' => $data[1], 'meta_key' => $data[2], 'meta_value' => $data[3] ) );
			   
			    $success = $wpdb->insert( $table_wp_posts, array( 'ID' => $data[4], 'post_author' => $data[5], 'post_date' => $data[6], 'post_date_gmt' => $data[7], 'post_content' => $data[8], 'post_title' => $data[9], 'post_excerpt' => $data[10], 'post_status' => $data[11], 'comment_status' => $data[12],'ping_status' => $data[13], 'post_password' => $data[14], 'post_name' => $data[15], 'to_ping' => $data[16], 'pinged' => $data[17], 'post_modified' => $data[18], 'post_modified_gmt' => $data[19], 'post_content_filtered' => $data[20], 'post_parent' => $data[21], 'guid' => $data[22], 'menu_order' => $data[23], 'post_type' => $data[24], 'post_mime_type' => $data[25],'comment_count' => $data[26] ) );
	             
				$success = $wpdb->insert( $table_wp_terms, array( 'term_id' => $data[27], 'name' => $data[28], 'slug' => $data[29], 'term_group' => $data[30] ) );

				$success = $wpdb->insert( $table_wp_tr, array( 'object_id' => $data[31], 'term_taxonomy_id' => $data[32], 'term_order' => $data[33] ) );

				$success = $wpdb->insert( $table_tt, array( 'term_taxonomy_id' => $data[32], 'term_id' => $data[27], 'taxonomy' => $data[34], 'description' => $data[35], 'parent' => $data[36], 'count' => $data[37] ) );
		    }
			fclose($file);
	    }

        /* 
	     * Export Menu data and store into csv file
	     */
	    $cdate = date('Y-m-d H-i-s');
		if( isset( $_POST["Export"] ) ) {  
			$selected_menu_id = $_POST['select-exp-menu'];
			if( $selected_menu_id != "select-menu" ){
			    if($selected_menu_id == 'exp-all-menu') {
		        $csv_output = $wpdb->get_results( "SELECT pm.*,p.*,t.*,tr.*,tt.* FROM wp_posts AS p LEFT JOIN wp_postmeta AS pm ON pm.post_id = p.ID LEFT JOIN wp_term_relationships AS tr ON tr.object_id = p.ID LEFT JOIN wp_term_taxonomy AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id LEFT JOIN wp_terms AS t ON t.term_id = tt.term_id WHERE p.post_type = 'nav_menu_item'",ARRAY_A );
			    } else {
			    $csv_output = $wpdb->get_results( "SELECT pm.*,p.*,t.*,tr.*,tt.* FROM wp_posts AS p LEFT JOIN wp_postmeta AS pm ON pm.post_id = p.ID LEFT JOIN wp_term_relationships AS tr ON tr.object_id = p.ID LEFT JOIN wp_term_taxonomy AS tt ON tt.term_taxonomy_id = tr.term_taxonomy_id LEFT JOIN wp_terms AS t ON t.term_id = tt.term_id WHERE p.post_type = 'nav_menu_item' AND tt.term_id =".$selected_menu_id."",ARRAY_A );
			    }	
		        header('Content-type: text/csv');
				header('Content-Disposition: attachment; filename="menu-bkp-'.$cdate.'.csv"');
				header('Pragma: no-cache');
				header('Expires: 0'); 
				$file = fopen('php://output', 'w');
				foreach ( $csv_output as $row )
				{
				   fputcsv( $file, $row );
				}
				fclose($file);
				exit();
		    }
	    }

        /* 
	     * Delete Menu
	     */
        if( isset( $_POST['Delete'] ) ) {  
			$value = $_POST['select-delete-menu'];
			if( $value == "all-delete" ) {
				$menus = wp_get_nav_menus();
				foreach( $menus as $menu ){
					wp_delete_nav_menu($menu->term_id);			 
				} 
			} else {
		      wp_delete_nav_menu( $value ); 
			}
	    }
	    ob_end_clean();
	}

	/*
	 * Load styles for plugin admin screens.
	 * @since 1.0.0
	 */
	public function funct_menu_mngt_admin_styles() {
	    wp_register_style('admin-custom',WP_MENU_MNGT_PLUGIN_URL.'assets/css/custom-admin.css');
	    wp_enqueue_style('admin-custom');
	}
	/*
	 * load scripts for all admin side.
	 * @since 1.0.0
	 */
	public function funct_menu_mngt_admin_scripts() {
		wp_register_script('plugin-admin-js',WP_MENU_MNGT_PLUGIN_URL . 'assets/js/admin-plugin.js',array( 'jquery' ),'4.0.0',true);
		wp_enqueue_script('plugin-admin-js');
	}

	}
} 
// instantiates class
$multilingual = new WP_Menu_Management();