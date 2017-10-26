<?
function vl_util_css($val, $property) {
  return ($val ? ($property . ': ' . $val . ';') : '');
}

function theme_enqueue_styles() {

    $parent_style = 'parent-style';

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style',
        get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );
    wp_enqueue_style( 'theme-page', get_stylesheet_directory_uri() . '/hackathon/css/themes-page.css',false,'1.1','all');
}

function district_child_footer_social_linkoffs() {
  // echo do_shortcode(file_get_contents('templates/footer_social_linkoffs.php', true));
}

function add_border_bottom_meta_to_section() {
  global $metaboxes;
  global $prefix;
  // $fields = $metaboxes['section_layout_box']['fields'];
  // array_splice($fields, count($fields)-2, 0,
  $metaboxes['section_layout_box']['fields'][$prefix . 'bottom_border'] = array(
    'title' => __('Section Bottom Border', 'framework'),
    'type' => 'radio',
    'description' => 'Do you want a thin gray border at the bottom of this section?',
    'std' => 'No',
    'options' => array('No','Yes'),
  );
}

function add_custom_css_classes_meta_to_section() {
  global $metaboxes;
  global $prefix;
  // $fields = $metaboxes['section_layout_box']['fields'];
  // array_splice($fields, count($fields)-2, 0,
  $metaboxes['section_layout_box']['fields'][$prefix . 'custom_css_classes'] = array(
    'title' => __('Custom CSS Class(es)', 'framework'),
    'type' => 'text',
    'description' => 'Do you want any custom CSS classes for this section?',
    'std' => '',
    'size' => 20
  );
}

function add_heading_background_color_to_page() {
  global $metaboxes;
  global $prefix;

  $metaboxes['page_title_box']['fields'][$prefix . 'page_heading_background_color'] = array(
    'title' => __('Heading background color', 'framework'),
    'type' => 'color',
    'description' => 'Optional. This is the heading\'s background color, NOT the entire header unit\'s background color.',
    'std' => '',
    'size' => 20
  );
}


function vl_box($atts, $content = null) {
  extract(shortcode_atts(array(
    'bgcolor' => '#fff',
    'class' => ''
  ), $atts));
  return "<div class='$class' style='background-color: $bgcolor;'>" . do_shortcode($content) . "</div>";
}

function vl_job_box($atts, $content = null) {
  return "<div class='job-box'>" . do_shortcode($content) . "</div>";
}

function vl_job_blurb($atts, $content = null) {
  return "<div class='job-blurb'>" . do_shortcode($content) . "</div>";
}

function vl_in_focus($atts, $content=null) {
  return '<div class="vl-in-focus">' . do_shortcode($content) . '</div>';
}


add_action('wp_enqueue_scripts', 'theme_enqueue_styles');
add_action('wp_footer', 'district_child_footer_social_linkoffs');

// 0 is the priority, because add_post_format_metabox at
// district/functions/customfields.php L#593 is run with the default priority
// of 10. This needs to run before that, as it modifies the global $metaboxes
// array
add_action('admin_init', 'add_border_bottom_meta_to_section', 0);
add_action('admin_init', 'add_custom_css_classes_meta_to_section', 0);
add_action('admin_init', 'add_heading_background_color_to_page', 0);

add_shortcode('vl_box', 'vl_box');
add_shortcode('vl_job_box', 'vl_job_box');
add_shortcode('vl_job_blurb', 'vl_job_blurb');
add_shortcode('vl_in_focus', 'vl_in_focus');



// register the custom post type
if ( ! function_exists('website_themes') ) {

// Register Custom Post Type
function website_themes() {

  $labels = array(
    'name'                  => _x( 'website themes', 'Post Type General Name', 'text_domain' ),
    'singular_name'         => _x( 'website theme', 'Post Type Singular Name', 'text_domain' ),
    'menu_name'             => __( 'Website Themes', 'text_domain' ),
    'name_admin_bar'        => __( 'Website Themes', 'text_domain' ),
    'archives'              => __( 'Item Archives', 'text_domain' ),
    'attributes'            => __( 'Item Attributes', 'text_domain' ),
    'parent_item_colon'     => __( 'Parent Item:', 'text_domain' ),
    'all_items'             => __( 'All Items', 'text_domain' ),
    'add_new_item'          => __( 'Add New Item', 'text_domain' ),
    'add_new'               => __( 'Add New', 'text_domain' ),
    'new_item'              => __( 'New Item', 'text_domain' ),
    'edit_item'             => __( 'Edit Item', 'text_domain' ),
    'update_item'           => __( 'Update Item', 'text_domain' ),
    'view_item'             => __( 'View Item', 'text_domain' ),
    'view_items'            => __( 'View Items', 'text_domain' ),
    'search_items'          => __( 'Search Item', 'text_domain' ),
    'not_found'             => __( 'Not found', 'text_domain' ),
    'not_found_in_trash'    => __( 'Not found in Trash', 'text_domain' ),
    'featured_image'        => __( 'Featured Image', 'text_domain' ),
    'set_featured_image'    => __( 'Set featured image', 'text_domain' ),
    'remove_featured_image' => __( 'Remove featured image', 'text_domain' ),
    'use_featured_image'    => __( 'Use as featured image', 'text_domain' ),
    'insert_into_item'      => __( 'Insert into item', 'text_domain' ),
    'uploaded_to_this_item' => __( 'Uploaded to this item', 'text_domain' ),
    'items_list'            => __( 'Items list', 'text_domain' ),
    'items_list_navigation' => __( 'Items list navigation', 'text_domain' ),
    'filter_items_list'     => __( 'Filter items list', 'text_domain' ),
  );
  $args = array(
    'label'                 => __( 'website theme', 'text_domain' ),
    'description'           => __( 'website themes', 'text_domain' ),
    'labels'                => $labels,
    'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail','custom-fields', 'post-formats', ),
    'taxonomies'            => array( 'category' ),
    'hierarchical'          => false,
    'public'                => true,
    'show_ui'               => true,
    'show_in_menu'          => true,
    'menu_position'         => 5,
    'show_in_admin_bar'     => true,
    'show_in_nav_menus'     => true,
    'can_export'            => true,
    'has_archive'           => true,
    'exclude_from_search'   => false,
    'publicly_queryable'    => true,
    'capability_type'       => 'post',
  );
  register_post_type( 'website_theme', $args );

}
add_action( 'init', 'website_themes', 0 );

}



function hierarchical_category_tree( $cat ) {

  $next = get_categories('hide_empty=false&orderby=name&order=ASC&parent=' . $cat);

    if( $next ) :
    foreach( $next as $cat ) :

      if( $cat->slug!='uncategorized') :
        if($cat->parent==0)
          echo '<li><span class="head-ul">' . $cat->name . '</span>';
        else
          echo '<ul><li class="child"><strong><a href="' . get_category_link( $cat->term_id ) . '">' . $cat->name . '</a></strong>';
        hierarchical_category_tree( $cat->term_id );
      endif;
    endforeach;
    endif;

  echo '</li></ul>'; echo "\n";
}

include 'template-theme-listing-func.php';