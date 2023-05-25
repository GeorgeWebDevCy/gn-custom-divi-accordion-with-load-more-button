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
if ( ! defined( 'ABSPATH' ) ) exit;
// Plugin name
define( 'GNCUSTOMDI_NAME',			'GN Custom Divi Accordion With Load More Button' );

// Plugin version
define( 'GNCUSTOMDI_VERSION',		'1.0.0' );

// Plugin Root File
define( 'GNCUSTOMDI_PLUGIN_FILE',	__FILE__ );

// Plugin base
define( 'GNCUSTOMDI_PLUGIN_BASE',	plugin_basename( GNCUSTOMDI_PLUGIN_FILE ) );

// Plugin Folder Path
define( 'GNCUSTOMDI_PLUGIN_DIR',	plugin_dir_path( GNCUSTOMDI_PLUGIN_FILE ) );

// Plugin Folder URL
define( 'GNCUSTOMDI_PLUGIN_URL',	plugin_dir_url( GNCUSTOMDI_PLUGIN_FILE ) );

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
function GNCUSTOMDI() {
	return Gn_Custom_Divi_Accordion_With_Load_More_Button::instance();
}

GNCUSTOMDI();
