<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'HELLO_ELEMENTOR_CHILD_VERSION', '2.0.0' );

// == CUSTOM FUNCTIONS == //

function printr($obj) {
	$js = json_encode($obj);
	print_r('<script>console.log('.$js.')</script>');
}

function filter_plugin_updates( $value ) {
    unset( $value->response['elementor-pro/elementor-pro.php'] );
    unset( $value->response['happy-elementor-addons-pro/happy-elementors-addons-pro.php'] );
    return $value;
}
add_filter( 'site_transient_update_plugins', 'filter_plugin_updates' );

// == ADMIN == //

add_action( 'admin_init', function () {
    //printr( $GLOBALS[ 'menu' ]);
	remove_admin_page( 'toplevel_page_hostinger');
	remove_admin_page( "toplevel_page_menu-image-options");
	remove_admin_page( "toplevel_page_loco");
	//remove_admin_page("toplevel_page_happy-addons");
} );

// == ADMIN LEFT MENU == //

if(!function_exists('remove_admin_page')) {
    function remove_admin_page($needle) {
        if(isset($GLOBALS[ 'menu' ]) && !empty($GLOBALS[ 'menu' ]) && !empty($needle)) {

            $needle = strtolower($needle);
            $needle = trim($needle);

            foreach($GLOBALS[ 'menu' ] as $position => $items) {
                foreach($items as $key => $item) {					
                    if(strtolower($item) == $needle) {
                        remove_menu_page( $items[2] );
                    }
                }
            }
        }
    }
} 

add_action( 'admin_menu', 'control_menu_items_shown' );
function control_menu_items_shown() {
    remove_submenu_page( 'index.php', 'update-core.php' );
	//remove_menu_page('admin.php?page=hostinger&#home');
}


// == ADMIN TOP TOOLBAR == //

add_action( 'admin_bar_menu', 'custom_admin_bar', 1000);
function custom_admin_bar( $wp_admin_bar ){
//function custom_admin_bar(){
	$wp_admin_bar->remove_node('updates');
	$wp_admin_bar->remove_node('happy-addons');
	//$wp_admin_bar->remove_node('edit-profile');

	/*
		$menu_id = 'leo';
		$wp_admin_bar->add_menu(array('id' => $menu_id, 'title' => __('LEO'), 'href' => '/'));
		$wp_admin_bar->add_menu(array(
			'parent' => $menu_id,
			'title' => __('Ajouter un FOCUS'),
			'id' => 'dwb-home',
			'href' => '/wp-admin/admin.php?action=ha_duplicate_thing&post_id=280&_wpnonce='.wp_create_nonce('tarace'),
			'meta' => array('target' => '_top'))
		);
	*/

	/*
		$args = array(
			//Type & Status Parameters
			'post_type'   => 'wpse_cpt',
			'post_status' => 'publish',
		);

		$wpse_cpt = new WP_Query( $args );

		$admin_bar_args = array(
			'id' => 'staff_count',
			//,'title' => 'XYZ Post:'.count($wpse_cpt->posts) // this is the visible portion in the admin bar.
		);

		$wp_admin_bar->add_node($admin_bar_args);
	*/
}

//add_action( 'wp_before_admin_bar_render', 'add_all_node_ids_to_toolbar' );
function add_all_node_ids_to_toolbar() {
	global $wp_admin_bar;
	$all_toolbar_nodes = $wp_admin_bar->get_nodes();

	if ( $all_toolbar_nodes ) {

		// add a top-level Toolbar item called "Node Id's" to the Toolbar
		$args = array(
			'id'    => 'node_ids',
			'title' => 'Node ID\'s'
		);
		$wp_admin_bar->add_node( $args );

		// add all current parent node id's to the top-level node.
		foreach ( $all_toolbar_nodes as $node  ) {
			if ( isset($node->parent) && $node->parent ) {

				$args = array(
					'id'     => 'node_id_'.$node->id, // prefix id with "node_id_" to make it a unique id
					'title'  => $node->id,
					'parent' => 'node_ids'
					// 'href' => $node->href,
				);
				// add parent node to node "node_ids"
				$wp_admin_bar->add_node($args);
			}
		}

		// add all current Toolbar items to their parent node or to the top-level node
		foreach ( $all_toolbar_nodes as $node ) {

			$args = array(
				'id'      => 'node_id_'.$node->id, // prefix id with "node_id_" to make it a unique id
				'title'   => $node->id,
				// 'href' => $node->href,
			);

			if ( isset($node->parent) && $node->parent ) {
				$args['parent'] = 'node_id_'.$node->parent;
			} else {
				$args['parent'] = 'node_ids';
			}

			$wp_admin_bar->add_node($args);
		}
	}
}


// == MAIN NAVIGATION MENU == //

//add_filter( 'wp_nav_menu_objects', 'wpdocs_unset_menu_items', 10, 2 );
function wpdocs_unset_menu_items( $menu_objects, $args ) {
    /*if ( 'primary_menu' !== $args->theme_location ) {
        return $menu_objects;
	}*/

    /*if ( is_user_logged_in() ) {
        return $menu_objects;
    }*/

    $menu_items = array(
        'Contact',
    );

    foreach ( $menu_objects as $key => $menu_object ) {
        if ( ! in_array( $menu_object->title, $menu_items ) ) {
            continue;
        }

        unset( $menu_objects[ $key ] );
    }

    return $menu_objects;
}

// == DO NOT EDIT UNDER THIS LINE == //

/**
 * Theme functions
 *
 * For additional information on potential customization options,
 * read the developers' documentation:
 *
 * https://developers.elementor.com/docs/hello-elementor-theme/
 *
 * @package HelloElementorChild
 */

/**
 * Load child theme scripts & styles.
 *
 * @return void
 */
function hello_elementor_child_scripts_styles() {

	wp_enqueue_style(
		'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[
			'hello-elementor-theme-style',
		],
		HELLO_ELEMENTOR_CHILD_VERSION
	);

}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_scripts_styles', 20 );

