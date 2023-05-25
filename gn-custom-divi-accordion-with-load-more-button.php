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

GNCUSTOMDI();

// Register the shortcode
add_shortcode('divi_accordion_cpt_load_more', 'generate_custom_accordion');

function generate_custom_accordion($atts)
{
	// Shortcode attributes
	$atts = shortcode_atts(
		array(
			'post_type' => 'post',
			'taxonomy' => 'category',
			'term' => 'news',
			'count' => 8,
		),
		$atts
	);

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

	// Start building the accordion
	$output = '<div id="custom-accordion" class="et_pb_accordion">';

	// Loop through the queried posts
	while ($query->have_posts()) {
		$query->the_post();

		// Get the post information
		$post_date = get_the_date('j F Y, l');
		$post_title = get_the_title();
		$post_content = get_the_content();

		// Build the accordion item
		$output .= '<h3 class="et_pb_toggle">' . $post_date . '</h3>';
		$output .= '<div class="et_pb_toggle_content">';
		$output .= '<div class="et_pb_column et_pb_column_1_4">' . $post_date . '</div>';
		$output .= '<div class="et_pb_column et_pb_column_2_4">' . $post_title . '</div>';
		$output .= '<div class="et_pb_column et_pb_column_1_4"><a href="#" class="et_pb_toggle_title">Toggle</a></div>';
		$output .= '</div>';
	}

	// Close the accordion and reset post data
	$output .= '</div>';
	wp_reset_postdata();

	// Load more button if there are more posts
	if ($query->found_posts > $atts['count']) {
		$output .= '<div id="custom-accordion-load-more" class="et_pb_button">Load More</div>';
	}

	// Enqueue the necessary scripts
	wp_enqueue_script('custom-accordion-script', plugin_dir_url(__FILE__) . 'custom-accordion-script.js', array('jquery'), '1.0', true);
	wp_localize_script(
		'custom-accordion-script',
		'customAccordion',
		array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('custom-accordion-nonce'),
			'post_type' => $atts['post_type'],
			'taxonomy' => $atts['taxonomy'],
			'term' => $atts['term'],
			'count' => $atts['count'],
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

		$output .= '<h3 class="et_pb_toggle">' . $post_date . '</h3>';
		$output .= '<div class="et_pb_toggle_content">';
		$output .= '<div class="et_pb_column et_pb_column_1_4">' . $post_date . '</div>';
		$output .= '<div class="et_pb_column et_pb_column_2_4">' . $post_title . '</div>';
		$output .= '<div class="et_pb_column et_pb_column_1_4"><a href="#" class="et_pb_toggle_title">Toggle</a></div>';
		$output .= '</div>';
	}

	wp_reset_postdata();

	wp_send_json_success($output);
}