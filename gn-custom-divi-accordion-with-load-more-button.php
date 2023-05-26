<?php
/**
 * GN Custom Divi Accordion With Load More Button
 *
 * @package       GNCUSTOMDI
 * @author        George Nicolaou
 * @license       gplv2
 * @version       1.0.0
 *
 * @wordpress-plugin
 * Plugin Name:   GN Custom Divi Accordion With Load More Button
 * Plugin URI:    https://www.georgenicolaou.me/plugins/gn-custom-divi-accordion-with-load-more-button
 * Description:    Generates a Divi Accordion module with customizable items and load more option
 * Version:       1.0.0
 * Author:        George Nicolaou
 * Author URI:    https://www.georgenicolaou.me/
 * Text Domain:   gn-custom-divi-accordion-with-load-more-button
 * Domain Path:   /languages
 * License:       GPLv2
 * License URI:   https://www.gnu.org/licenses/gpl-2.0.html
 *
 * You should have received a copy of the GNU General Public License
 * along with GN Custom Divi Accordion With Load More Button. If not, see <https://www.gnu.org/licenses/gpl-2.0.html/>.
 */

// Exit if accessed directly.
if (!defined('ABSPATH'))
	exit;
// Plugin name
define('GNCUSTOMDI_NAME', 'GN Custom Divi Accordion With Load More Button');

// Plugin version
define('GNCUSTOMDI_VERSION', '1.0.0');

// Plugin Root File
define('GNCUSTOMDI_PLUGIN_FILE', __FILE__);

// Plugin base
define('GNCUSTOMDI_PLUGIN_BASE', plugin_basename(GNCUSTOMDI_PLUGIN_FILE));

// Plugin Folder Path
define('GNCUSTOMDI_PLUGIN_DIR', plugin_dir_path(GNCUSTOMDI_PLUGIN_FILE));

// Plugin Folder URL
define('GNCUSTOMDI_PLUGIN_URL', plugin_dir_url(GNCUSTOMDI_PLUGIN_FILE));

/**
 * Load the main class for the core functionality
 */
require_once GNCUSTOMDI_PLUGIN_DIR . 'core/class-gn-custom-divi-accordion-with-load-more-button.php';

/**
 * The main function to load the only instance
 * of our master class.
 *
 * @author  George Nicolaou
 * @since   1.0.0
 * @return  object|Gn_Custom_Divi_Accordion_With_Load_More_Button
 */
function GNCUSTOMDI()
{
	return Gn_Custom_Divi_Accordion_With_Load_More_Button::instance();
}

// Register the shortcode
add_shortcode('divi_accordion_cpt_load_more', 'generate_custom_accordion');
function generate_custom_accordion($atts)
{
	$index = 0; // Initialize the index variable
	// Shortcode attributes
	$atts = shortcode_atts(
		array(
			'post_type' => 'post',
			'taxonomy' => 'category',
			'term' => 'news',
			'count' => -1,
		),
		$atts
	);

	// Enqueue Divi stylesheets explicitly
	add_action('wp_enqueue_scripts', 'enqueue_divi_styles');

	function enqueue_divi_styles()
	{
		wp_enqueue_style('divi-parent-theme-style', get_template_directory_uri() . '/style.css');
		wp_enqueue_style('divi-child-theme-style', get_stylesheet_uri());

	}

	// Query arguments
	$args = array(
		'post_type' => $atts['post_type'],
		'tax_query' => array(
			array(
				'taxonomy' => $atts['taxonomy'],
				'field' => 'slug',
				'terms' => $atts['term'],
			),
		),
		'posts_per_page' => $atts['count'],
	);

	// Perform the query
	$query = new WP_Query($args);

	// Loop through the queried posts
	$output = '<div class="et_pb_module et_pb_accordion et_pb_accordion_0">';

	while ($query->have_posts()) {
		$query->the_post();

		// Get the post information
		$post_date = get_the_date('j F Y, l');
		$post_title = get_the_title();
		$post_content = get_the_content();

		$accordion_class = ($index === 0) ? 'et_pb_toggle_open' : 'et_pb_toggle_close';
		$output .= '<div id="custom-accordion-' . $index . '" class="et_pb_toggle et_pb_module et_pb_accordion_item et_pb_accordion_item_' . $index . ' ' . $accordion_class . '">';
		$output .= '<div class="et_pb_toggle_header">';
		$output .= '<div class="et_pb_toggle_title">' . $post_date . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $post_title . '</div>';
		$output .= '</div>';
		$output .= '<div class="et_pb_toggle_content clearfix">' . $post_content . '</div>';
		$output .= '</div>';

		$index++; // Increment the index variable
	}

	// Close the accordion and reset post data
	$output .= '</div>';
	wp_reset_postdata();

	// Load more button if there are more posts
	if ($query->found_posts > $atts['count']) {
		$output .= '<div id="custom-accordion-load-more" class="et_pb_button">Load More</div>';
	}

	// Enqueue the necessary scripts
	wp_enqueue_script('custom-accordion-script', GNCUSTOMDI_PLUGIN_URL . 'core/includes/assets/js/custom-accordion-script.js', array('jquery'), '1.0', true);

	wp_localize_script(
		'custom-accordion-script',
		'customAccordion',
		array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('custom-accordion-nonce'),
			'post_type' => esc_attr($atts['post_type']),
			'taxonomy' => esc_attr($atts['taxonomy']),
			'term' => esc_attr($atts['term']),
			'count' => esc_attr($atts['count']),
		)
	);

	return $output;
}

// AJAX load more action
add_action('wp_ajax_custom_accordion_load_more', 'custom_accordion_load_more');
add_action('wp_ajax_nopriv_custom_accordion_load_more', 'custom_accordion_load_more');

function custom_accordion_load_more()
{
	check_ajax_referer('custom-accordion-nonce', 'security');

	$post_type = $_POST['post_type'];
	$taxonomy = $_POST['taxonomy'];
	$term = $_POST['term'];
	$count = $_POST['count'];
	$offset = $_POST['offset'];

	$args = array(
		'post_type' => $post_type,
		'tax_query' => array(
			array(
				'taxonomy' => $taxonomy,
				'field' => 'slug',
				'terms' => $term,
			),
		),
		'posts_per_page' => $count,
		'offset' => $offset,
	);

	$query = new WP_Query($args);

	$output = '';

	while ($query->have_posts()) {
		$query->the_post();

		$post_date = get_the_date('j F Y, l');
		$post_title = get_the_title();

		$accordion_class = ($index === 0) ? 'et_pb_toggle_open' : 'et_pb_toggle_close';
		$output .= '<div id="custom-accordion-' . $index . '" class="et_pb_toggle et_pb_module et_pb_accordion_item et_pb_accordion_item_' . $index . ' ' . $accordion_class . '">';
		$output .= '<div class="et_pb_toggle_header">';
		$output .= '<div class="et_pb_toggle_title">' . $post_date . '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' . $post_title . '</div>';
		$output .= '</div>';
		$output .= '<div class="et_pb_toggle_content clearfix">' . $post_content . '</div>';
		$output .= '</div>';

		$offset++; // Increment the offset variable
	}

	wp_reset_postdata();

	wp_send_json_success($output);
}