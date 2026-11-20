<?php
/**
 * Plugin Name: TCD Classic Editor
 * Plugin URI:
 * Description: This is a classic editor extension plug-in for TCD users.
 * Version: 1.3.9
 * Author: TCD
 * Author URI: https://tcd-theme.com/
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: tcd-classic-editor
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Plugin is active
if ( ! defined( 'TCDCE_ACTIVE' ) ) {
	define(
		'TCDCE_ACTIVE',
		true
	);
}

// Plugin Path
if ( ! defined( 'TCDCE_PATH' ) ) {
	define(
		'TCDCE_PATH',
		plugin_dir_path( __FILE__ )
	);
}

// Plugin Url
if ( ! defined( 'TCDCE_URL' ) ) {
	define(
		'TCDCE_URL',
		plugins_url( '/', __FILE__ )
	);
}

// Plugin Ver
if ( ! defined( 'TCDCE_VER' ) ) {
	define(
		'TCDCE_VER',
		current( get_file_data( __FILE__ , array( 'Version' ), 'plugin' ) )
	);
}

// Plugin base name
if ( ! defined( 'TCDCE_BASE_NAME' ) ) {
	define(
		'TCDCE_BASE_NAME',
		plugin_basename( __FILE__ )
	);
}

// load textdomain
load_textdomain(
	'tcd-classic-editor',
	TCDCE_PATH . 'languages/tcd-classic-editor-' . determine_locale() . '.mo'
);

// プラグインにその言語の翻訳ファイルが無いときは、WordPress に翻訳を自動で読み込ませない
// （設定の初期値などが after_setup_theme より前に翻訳を使うため、読み込みが早すぎるという Notice が出る。読み込む翻訳は無いので、表示は変わらない）
add_filter( 'lang_dir_for_domain', function( $path, $domain, $locale ) {
	if (
		'tcd-classic-editor' === $domain
		&& $path && ! did_action( 'after_setup_theme' )
		&& str_starts_with( $path, TCDCE_PATH )
		&& ! file_exists( $path . $domain . '-' . $locale . '.mo' ) && ! file_exists( $path . $domain . '-' . $locale . '.l10n.php' )
	) {
		return false;
	}
	return $path;
}, 10, 3 );

__( 'This is a classic editor extension plug-in for TCD users.', 'tcd-classic-editor' );

// add image size
add_image_size( 'tcdce-s', 300, 300, true );

// helper
require_once TCDCE_PATH . 'helper.php';

// 管理画面用アイコン
require_once TCDCE_PATH . 'assets/admin-icons.php';

// classic editor切り替え機能（setup_themeで実行）
if ( ! class_exists( 'Classic_Editor' ) ) {
	require_once TCDCE_PATH . 'classes/support/class-tcdce-support.php';
}

// setting

// Start Guide
require_once TCDCE_PATH . 'setting/start-guide.php';

// basic setting
require_once TCDCE_PATH . 'setting/basic.php';

// Quicltag
require_once TCDCE_PATH . 'setting/quicktag.php';
require_once TCDCE_PATH . 'setting/quicktag/heading.php';
require_once TCDCE_PATH . 'setting/quicktag/ul.php';
require_once TCDCE_PATH . 'setting/quicktag/ol.php';
require_once TCDCE_PATH . 'setting/quicktag/box.php';
require_once TCDCE_PATH . 'setting/quicktag/marker.php';
require_once TCDCE_PATH . 'setting/quicktag/button.php';
require_once TCDCE_PATH . 'setting/quicktag/sb.php';
require_once TCDCE_PATH . 'setting/quicktag/heading_label.php';
require_once TCDCE_PATH . 'setting/quicktag/cardlink.php';
require_once TCDCE_PATH . 'setting/quicktag/gmap.php';
require_once TCDCE_PATH . 'setting/quicktag/custom_tag.php';

// Google Maps
require_once TCDCE_PATH . 'setting/google-maps.php';

// Table of contents
require_once TCDCE_PATH . 'setting/table-of-contents.php';

// tcd theme support
require_once TCDCE_PATH . 'theme-support/tcd.php';

// classes
require_once TCDCE_PATH . 'classes/class-tcdce-qt-fields.php';
require_once TCDCE_PATH . 'classes/class-tcdce-admin-menu.php';
require_once TCDCE_PATH . 'classes/class-tcdce-editor.php';
require_once TCDCE_PATH . 'classes/class-tcdce-block-editor.php';
require_once TCDCE_PATH . 'classes/class-tcdce-toc.php';
require_once TCDCE_PATH . 'classes/class-tcdce-toc-widget.php';