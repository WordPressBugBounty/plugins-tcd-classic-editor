<?php

/**
 *
 * 管理画面の設定&メニュー登録
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCDCE_Admin_Menu' ) ) {
	class TCDCE_Admin_Menu {

		/**
		 * Admin top menu slug.
		 *
		 * @var string
		 */
    public $menu_slug = 'tcd_classic_editor';


    /**
		 * Admin submenus.
		 *
		 * @var array
		 */
    public $submenus = array();

    /**
     * Constructor.
     */
    public function __construct() {

			// menu
			do_action( 'tcdce_add_admin_menu', $this );

			// admin menu init
			add_action( 'admin_menu', array( $this, 'init_menu' ) );

			// init setting
			add_action( 'init', array( $this, 'init_setting' ) );

		}

		/**
     * admin_menuで実行
     */
    public function init_menu() {

			// top menu 追加
			add_menu_page(
				__( 'TCD Classic Editor', 'tcd-classic-editor' ),
				__( 'TCD Classic Editor', 'tcd-classic-editor' ),
				'manage_options',
				$this->menu_slug,
				function(){
					echo '<div id="js-tcdce-page" class="tcdce-page">';
					$this->load_header();
					$this->load_notice();
					echo '<div class="tcdce-page__inner">';
					do_action( 'tcdce_top_menu' );
					echo '</div>';
					echo '</div>';
				},
				'dashicons-editor-kitchensink',
				3
			);

			// submenu 登録
			if( ! empty( $this->submenus ) ){
				foreach( $this->submenus as $submenu ){
					add_submenu_page(
						$this->menu_slug,
						$submenu['title'],
						$submenu['title'],
						$submenu['capability'],
						$submenu['slug'],
						function() use( $submenu ) {
							// reset
							$reset_class = get_option( 'tcdce_reset', 0 ) ? 'is-reset' : '';
							update_option( 'tcdce_reset', 0 );

							// page
							echo '<div id="js-tcdce-page" class="tcdce-page ' . esc_attr( $reset_class ) . '">';
							$this->load_header();
							$this->load_notice();
							echo '<div class="tcdce-page__inner">';
							do_action( "tcdce_submenu_{$submenu['slug']}" );
							call_user_func( $submenu['callback'], $submenu );
							echo '</div>';
							echo '</div>';
						},
						null
					);
				}
			}

			// top menu の上書き
			global $submenu;
			if( isset( $submenu['tcd_classic_editor'] ) ){
				$submenu['tcd_classic_editor'][0][0] = __( 'Start guide', 'tcd-classic-editor' );
				$submenu['tcd_classic_editor'][0][3] = __( 'Start guide', 'tcd-classic-editor' );
			}

			// プラグイン一覧の設定リンク追加
			add_filter( 'plugin_action_links_' . TCDCE_BASE_NAME, array( $this, 'add_action_link' ) );

		}


		/**
     * initで実行
     */
    public function init_setting() {

			// css&jsの読み込み
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ) );

			// サブメニューの設定登録
			if( ! empty( $this->submenus ) ){
				foreach( $this->submenus as $submenu ){
					register_setting(
						$submenu['setting']['option_group'],
						$submenu['setting']['option_name'],
						array(
							'type' => 'array',
							'description' => '',
							'sanitize_callback' => $submenu['setting']['sanitize_callback'],
							'show_in_rest' => false,
							'default' => call_user_func( $submenu['setting']['default_callback'] )
						)
					);
				}
			}

		}

		/**
     * 管理画面で使うcss&jsの読み込み
     */
    public function admin_enqueue_assets( $hook_suffix ) {

			// editor style
			wp_enqueue_style( 'tcdce-editor', TCDCE_URL . 'assets/css/editor.css', array(), filemtime( TCDCE_PATH . 'assets/css/editor.css' ) );

			// jquery ui sortable
			wp_enqueue_script( 'jquery-ui-sortable' );

			// vanilla picker
			wp_enqueue_script( 'vanilla-picker', TCDCE_URL . 'assets/js/vanilla-picker.min.js', array(), TCDCE_VER, true );

			// ui
			wp_enqueue_style( 'tcdce-admin-ui', TCDCE_URL . 'assets/css/admin-ui.css', array(), filemtime( TCDCE_PATH . 'assets/css/admin-ui.css' ) );
			wp_enqueue_script( 'tcdce-admin-ui', TCDCE_URL . 'assets/js/admin-ui.js', array(), filemtime( TCDCE_PATH . 'assets/js/admin-ui.js' ), true );
			wp_localize_script( 'tcdce-admin-ui', 'TCDCE_OBJECT', array(
				'media_text' => __( 'Select Image', 'tcd-classic-editor' ),
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'tcdce_ajax_action' ),
				'ajax_error_message' => __( 'Error was occurred. Please retry again.', 'tcd-classic-editor' ),
			));

		}


		/**
     * 共通ヘッダーの読み込み
     */
    public function load_header() {
			global $plugin_page;
?>
<header class="tcdce-header">
	<ul class="tcdce-header__nav">
		<li class="tcdce-header__nav-item">
			<a class="tcdce-header__nav-link <?php echo $plugin_page == $this->menu_slug ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => $this->menu_slug ), admin_url( 'admin.php' ) ) ); ?>">
				<?php esc_html_e( 'Start guide', 'tcd-classic-editor' ); ?>
			</a>
		</li>
		<?php foreach( $this->submenus as $submenu ){ ?>
		<li class="tcdce-header__nav-item">
			<a class="tcdce-header__nav-link <?php echo $plugin_page == $submenu['slug'] ? 'is-active' : ''; ?>" href="<?php echo esc_url( add_query_arg( array( 'page' => $submenu['slug'] ), admin_url( 'admin.php' ) ) ); ?>">
				<?php echo esc_html( $submenu['title'] ); ?>
			</a>
		</li>
		<?php } ?>
	</ul>
	<div class="tcdce-header__info">
		ver <?php echo esc_html( TCDCE_VER ); ?>
		<a class="tcdce-header__info-link" href="https://tcd-theme.com/2025/02/tcd-classic-editor.html" target="_blank">
			<?php esc_html_e( 'Manual', 'tcd-classic-editor' ); ?>
		</a>
	</div>
</header>
<?php
		}

		/**
     * 共通noticeの読み込み
     */
    public function load_notice() {

?>
<div class="tcdce-message">
	<div class="tcdce-message--success">
		<?php esc_html_e( 'Settings saved.', 'tcd-classic-editor' ); ?>
	</div>
	<div class="tcdce-message--failed">
		<?php esc_html_e( 'Could not save settings.', 'tcd-classic-editor' ); ?>
	</div>
	<div class="tcdce-message--reset">
		<?php esc_html_e( 'Settings reset.', 'tcd-classic-editor' ); ?>
	</div>
	<div class="tcdce-message--imported">
		<?php esc_html_e( 'Import is complete.', 'tcd-classic-editor' ); ?>
	</div>
</div>
<?php
		}

		/**
     * メインメニューテンプレートの読み込み
     */
    public function load_topmenu() {

			// load header
			$this->load_header();

			// load notice
			$this->load_notice();

		}

		/**
     * プラグイン一覧に設定リンク追加
     */
    public function add_action_link( $actions ) {
			array_unshift(
				$actions,
				'<a href="' . esc_url( add_query_arg( array( 'page' => $this->menu_slug ), admin_url( 'admin.php' ) ) ) . '">' . __( 'Settings', 'tcd-classic-editor' ) . '</a>'
			);
			return $actions;
		}

	}

	/**
	 * インスタンス化
	 */
	$tcdce_admin_menu = new TCDCE_Admin_Menu();

}
