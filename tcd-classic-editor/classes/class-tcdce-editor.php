<?php
/*
 *
 * TCDクラシックエディター
 *
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCDCE_Editor' ) ) {
  class TCDCE_Editor {

		/**
		 * Quicktag options.
		 *
		 * @var array
		 */
    public $tcdce_quicktag = array();

		/**
		 * Dynamic css ajax action.
		 *
		 * @var string
		 */
    public $ajax_action = '';

		/**
		 * Dynamic css ajax url.
		 *
		 * @var string
		 */
    public $ajax_url = '';

		/**
     * Constructor.
     */
    public function __construct() {

			// 各種変数のセット
			$this->set_vars();

			// フロント初期化
			add_action( 'after_setup_theme', array( $this, 'front_init' ) );

			// 管理画面初期化
			add_action( 'admin_init', array( $this, 'admin_init' ) );

		}

		/**
     * フロント 初期化.
     */
    public function set_vars() {

			// クイックタグ設定
			$this->tcdce_quicktag = get_option( 'tcdce_quicktag', tcdce_quicktag_setting_default() );

			// ajaxで読み込むスタイル関連
			$this->ajax_action = 'tcd_quicktags_dynamic_css';
			$this->ajax_url = admin_url( 'admin-ajax.php?action=' . $this->ajax_action );
		}

		/**
     * フロント 初期化.
     */
    public function front_init() {

			// TCDテーマ組み込みエディターを無効化
			remove_action( 'init', 'tcd_quicktag_front_init' );
			remove_action( 'admin_init', 'tcd_quicktag_admin_init' );

			// editor-stylesの有効化
			if( ! current_theme_supports( 'editor-styles' ) ){
				add_theme_support('editor-styles');
			}

			// the_content フィルター
			add_filter( 'the_content', array( $this, 'the_content' ), 10 );

			// エディターの本文をdivで囲むフィルター
			// NOTE: 誤って動作するのを防ぐため、wpautop(10)よりも遅いタイミングで実行する
			add_filter( 'the_content', function( $content ){
				return '<div class="tcdce-body">' . $content . '</div>';
			}, 11 );

			// ページ分割のマークアップ上書き
			add_filter( 'wp_link_pages_args', array( $this, 'wp_link_pages_args' ) );

			// ショートコード登録
			add_shortcode( 'clink', array( $this, 'shortcode_clink' ) );
			add_shortcode( 'speech_bubble', array( $this, 'shortcode_sb' ) );
			add_shortcode( 'gmap', array( $this, 'shortcode_gmap' ) );
			add_shortcode( 'tcd_tab', array( $this, 'shortcode_tab' ) );

			// エディターのフロントスタイル
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

			// ギャラリーによって出力されるCSSを無効化
			add_filter( 'use_default_gallery_style', '__return_false' );

		}


		/**
     * the_contentフィルターの処理
		 *
		 * エディターの出力内容をdivで囲む.
		 *
     */
    public function the_content( $content ) {

			// ブロックが存在しないエディタの場合(has_blocksだと対象がWP_Postになる)
			if( ! str_contains( (string) $content, '<!-- wp:' ) ){

				// tableをdivで囲む
				$content = str_replace( '<table', '<div class="s_table"><table', $content );
				$content = str_replace( '</table>', '</table></div>', $content );

				// フロントの改行をなくす
				// NOTE: wpautopの後に発火する必要あり 10〜
				$content = str_replace( '<p>&nbsp;</p>', '', $content );

				// ショートコードがpタグで囲まれるのを防ぐ
				// NOTE: wpautopの後、do_shortcodeの前に発火する必要あり
				// 優先度は 10
				$content = strtr( $content, array (
					'<p>[' => '[',
					']</p>' => ']',
					']<br />' => ']'
				) );
			}

			return $content;
		}

		/**
     * ページ分割のマークアップ上書き
     */
		public function wp_link_pages_args( $parsed_args ) {

			// デフォルトのマークアップを利用している場合のみプラグインで上書き
			if( str_contains( $parsed_args['before'], '<p class="post-nav-links">' ) ){
				$parsed_args['before'] = '<div class="tcdce-pager">';
				$parsed_args['after'] = '</div>';
			}
			return $parsed_args;

		}

		/**
     * エディターのcss&jsを読み込む.
     */
    public function enqueue_assets() {

			// エディター基本スタイル
			wp_enqueue_style( 'tcdce-editor', TCDCE_URL . 'assets/css/editor.css', array(), filemtime( TCDCE_PATH . 'assets/css/editor.css' ) );

			// エディター（過去テーマ対応）
			// wp_enqueue_style( 'tcdce-old-style', TCDCE_URL . 'assets/css/old-style.css', array(), filemtime( TCDCE_PATH . 'assets/css/old-style.css' ) );

			// TCDテーマに組み込まれているスタイル
			wp_enqueue_style( 'tcdce-utility', TCDCE_URL . 'assets/css/utility.css', array(), filemtime( TCDCE_PATH . 'assets/css/utility.css' ) );

			// クイックタグ用スタイル
			wp_add_inline_style( 'tcdce-editor', $this->render_quicktag_style() );

			// エディター用基本スクリプト
			wp_enqueue_script( 'tcdce-editor', TCDCE_URL . 'assets/js/editor.js', array(), filemtime( TCDCE_PATH . 'assets/js/editor.js' ), true );

		}


		/**
     * 管理画面の初期化.
     */
    public function admin_init() {
			global $pagenow;

			// コメント一覧、コメント編集ページではクイックタグを読み込まない
			if( in_array( $pagenow, [ 'comment.php', 'edit-comments.php' ], true ) ){
				return;
			}

			// tinymceのスクリプト登録
			add_filter( 'mce_external_plugins', array( $this, 'mce_register_scripts' ), 10, 2 );

			// tinymceのボタン登録
			add_filter( 'mce_buttons', array( $this, 'mce_register_buttons' ), 10, 2 );

			// tinymceのタグ登録
			add_action( 'admin_print_footer_scripts', array( $this, 'mce_register_tags' ) );

			// tinymceのbodyにclass追加
			add_filter( 'tiny_mce_before_init', array( $this, 'mce_add_body_class' ), 10, 2 );

			// ビジュアルエディター用スタイル（ajax）
			add_action( 'wp_ajax_' . $this->ajax_action, array( $this, 'ajax_quicktag_dynamic_css' ) );

			// エディタースタイル
			add_editor_style( TCDCE_URL . 'assets/css/utility.css?d='.gmdate( 'YmdGis', filemtime( TCDCE_PATH . 'assets/css/utility.css' ) ) );
			add_editor_style( TCDCE_URL . 'assets/css/editor.css?d='.gmdate( 'YmdGis', filemtime( TCDCE_PATH . 'assets/css/editor.css' ) ) );
			add_editor_style( TCDCE_URL . 'assets/css/old-style.css?d='.gmdate( 'YmdGis', filemtime( TCDCE_PATH . 'assets/css/old-style.css' ) ) );
			add_editor_style( $this->ajax_url );
		}


		/**
     * tinymceのスクリプト登録
     */
    public function mce_register_scripts( $external_plugins, $editor_id ) {
			$external_plugins['table'] = TCDCE_URL . 'assets/js/tinymce-table.min.js';
			$external_plugins['tcdce_button'] = TCDCE_URL . 'assets/js/tinymce-button.js';
			return $external_plugins;
		}


		/**
     * tinymceのビジュアルエディタボタン登録
     */
    public function mce_register_buttons( $mce_buttons, $editor_id ) {

			// moreボタンの削除
			if( ( $wp_more_key = array_search( 'wp_more', $mce_buttons, true ) ) !== false ){
				unset( $mce_buttons[$wp_more_key] );
			}

			// ボタンの追加
			array_push( $mce_buttons, 'wp_page', 'table', 'tcdce_button' );

			return $mce_buttons;
		}


		/**
     * tinymceのタグ登録
     */
    public function mce_register_tags() {

			$tcdQuicktagsL10n = array(
				'pulldown_title' => array(
					'display' => __( 'Quicktag', 'tcd-classic-editor' ),
				),
			);

			if( !empty( $this->tcdce_quicktag ) ){
				foreach( $this->tcdce_quicktag as $key => $quicktag ){
					if( ! $quicktag['show'] ){
						continue;
					}
					$qt_key = 'qt-' . $key;
					$item = $quicktag['item'];
					$register_quicktag = apply_filters( "tcdce_qt_register_{$item}", array(), $quicktag, $key );
					if( ! empty( $register_quicktag ) ){
						$tcdQuicktagsL10n[$qt_key] = $register_quicktag;
					}
				}
			}

			// その他タグの登録（ビジュアル/テキスト）

			// layout2c
			$tcdQuicktagsL10n['post_col-2'] = array(
				'display' => __( '2 column', 'tcd-classic-editor' ),
				'tag' => '<div class="post_row"><div class="post_col post_col-2">' . __( 'Text and image tags to display in the left column', 'tcd-classic-editor' ) . '</div><div class="post_col post_col-2">' . __( 'Text and image tags to display in the right column', 'tcd-classic-editor' ) . '</div></div>'
			);
			// layout3c
			$tcdQuicktagsL10n['post_col-3'] = array(
				'display' => __( '3 column', 'tcd-classic-editor' ),
				'tag' => '<div class="post_row"><div class="post_col post_col-3">' . __( 'Text and image tags to display in the left column', 'tcd-classic-editor' ) . '</div><div class="post_col post_col-3">' . __( 'Text and image tags to display in the center column', 'tcd-classic-editor' ) . '</div><div class="post_col post_col-3">' . __( 'Text and image tags to display in the right column', 'tcd-classic-editor' ) . '</div></div>'
			);
			// tab2
			$tcdQuicktagsL10n['tab'] = array(
				'display' => __( 'Tab', 'tcd-classic-editor' ),
				'tag' => __( '[tcd_tab tab1="Tab1 headline" img1="Tab1 image url" tab2="Tab2 headline" img2="Tab2 image url"]', 'tcd-classic-editor' )
			);

			// その他固定で追加したいものがあればこのフィルターを利用
			$tcdQuicktagsL10n = apply_filters( 'tcdce_register_quicktags_after', $tcdQuicktagsL10n );

			echo '<script id="tcdce-register-quicktag" type="text/javascript">';

			// check if WYSIWYG is enabled
			if ( 'true' == get_user_option( 'rich_editing' ) ) {
				echo "var tcdQuicktagsL10n = " . wp_json_encode( $tcdQuicktagsL10n ) . ";\n";
			}

			if ( wp_script_is( 'quicktags' ) ) {

				foreach ( $tcdQuicktagsL10n as $key => $value ) {
					if ( is_numeric( $key ) || empty( $value['display'] ) ) {
						continue;
					}
					if ( empty( $value['tag'] ) && empty( $value['tagStart'] ) ) {
						continue;
					}
					if ( isset( $value['tag'] ) && ! isset( $value['tagStart'] ) ) {
						$value['tagStart'] = $value['tag'] . "\n\n";
					}
					if ( ! isset( $value['tagEnd'] ) ) {
						$value['tagEnd'] = '';
					}

					echo 'QTags.addButton(';
					echo wp_json_encode( $key ) . ',';
					echo wp_json_encode( $value['display'] ) . ',';
					echo wp_json_encode( $value['tagStart'] ) . ',';
					echo wp_json_encode( $value['tagEnd'] );
					echo ');';
				}

			}

			echo '</script>';

		}


		/**
     * tinymceのエディターにclass追加
     */
    public function mce_add_body_class( $mce_init, $editor_id ) {
			$mce_init['body_class'] = 'tcdce-body';
			return $mce_init;
		}


		/**
     * クイックタグスタイルをtinymceで読み込む
     */
    public function ajax_quicktag_dynamic_css() {
			header( 'Content-Type: text/css; charset=UTF-8' );
			add_filter( 'attribute_escape', 'tcdce_custom_attribute_escape', 10, 2 );
			echo esc_attr( $this->render_quicktag_style() );
			remove_filter( 'attribute_escape', 'tcdce_custom_attribute_escape', 10, 2 );
			exit;
		}


		/**
     * 追加したクイックタグのスタイルの読み込み
     */
    public function render_quicktag_style() {

			$css = '';
			if( !empty( $this->tcdce_quicktag ) ){
				foreach( $this->tcdce_quicktag as $key => $quicktag ){

					$class = $quicktag['class'] ?? '';

					// button（親に当たるが、要確認）
					if( $quicktag['item'] == 'button' ){
						$class = 'wp-block-button.' . $class . ',:is(.tcdce-button-wrapper, .q_button_wrap):has(.' . $class . ')';
					}

					$qt_style = $quicktag['style'] ?? array();
					if( ! empty( $qt_style ) ){

						$css .= '.' . $class . '{';
						foreach( $qt_style as $property => $value ){

							// 画像の変換
							if( strpos( $property,'image-url') !== false ){
								$value = wp_get_attachment_url( $value ) ? 'url(' . wp_get_attachment_url( $value ) . ')' : '';
							}

							// px追加
							if( is_int( $value ) ){
								$value = $value . 'px';
							}

							// ラベル付き見出し
							if( $quicktag['item'] == 'heading_label' && $property === '--tcdce-heading_label-element-text' ){
								 // バックスラッシュをエスケープ
								$text = str_replace('\\', '\\\\', $value);
								// ダブルクォートをエスケープ
								$text = str_replace('"', '\"', $text);
								// 改行コードを \A に統一、次に続く文字が英字だと文字化けするので\Aの後に空白を入れる
								$text = preg_replace("/\r\n|\r|\n/", '\\A ', $text);
								// CSSのcontent用にダブルクォートで囲む
								$value = '\'' . $text . '\'';
							}

							$css .= $property . ':' . $value . ';';
						}

						$css .= '}';

					}

					// custom tag
					if( $quicktag['item'] == 'custom_tag' && $quicktag['css'] ){
						$css .= $quicktag['css'];
					}

				}
			}

			return apply_filters( 'tcdce_render_quicktag_style', $css );
		}

		/**
     * ショートコード「カードリンク」
		 *
		 * NOTE: 既存テーマのカードリンクを更新
     */
		public function shortcode_clink( $atts ) {

			if( ! class_exists( 'TCDCE_Open_Graph' ) ){
				require_once TCDCE_PATH . 'classes/class-tcdce-open-graph.php';
			}

			$atts = shortcode_atts(
				array(
					'url' => '',
					// 日付を非表示にできるオプション
					'hide-date' => 0,
					// 更新日付を非表示にできるオプション
					'hide-modify-date' => 0,
					// 投稿IDから情報を取得できるオプション
					'id' => '',
				),
				$atts
			);

			if ( $atts['id'] ) {
				// IDが指定されていれば、そのまま利用
				$post_id = $atts['id'];
				$post = get_post( $post_id );
				if( ! $post ){
					return '<div class="tcdce-caution">' . __( 'This post ID does not exist.', 'tcd-classic-editor' ) . '</div>';
				}
				// URL上書き
				$atts['url'] = get_the_permalink( $post_id );

			}elseif ( $atts['url'] ) {
				// URLが指定されていれば、URLからID取得
				$post_id = url_to_postid( $atts['url'] );
				$post = get_post( $post_id );

			}else{
				// それ以外は終了
				return;
			}

			// 内部リンク
			if( $post_id ){

				$image = get_the_post_thumbnail_url( $post_id, 'tcdce-s' );
				$title = get_the_title( $post );
				// 投稿タイプが「post」と「news」以外は日付を表示しない
				// NOTE: 固定ページなど多くの投稿タイプに日付が必要ないため
				$date = in_array( get_post_type( $post ), [ 'post', 'news' ], true ) ? get_the_date( 'Y.m.d', $post ) : '';
				// 更新日付はフィルターを使ってテーマ側の設定と連動させる
				$update_date = apply_filters( 'tcdce_clink_modify_date', get_the_modified_date( 'Y.m.d', $post ), $post );
				$desc = $post->post_excerpt ? $post->post_excerpt : $post->post_content;

			// 外部リンク
			}elseif( $graph = TCDCE_Open_Graph::fetch( $atts['url'] ) ){

				$image = $graph->image;
				$title = $graph->title;
				$date = '';
				$update_date = '';
				$desc = $graph->description;

			// リンクが存在しない場合
			}else{

				return '<div class="tcdce-caution">' . __( 'Please enter the correct URL.', 'tcd-classic-editor' ) . '</div>';
			}

			ob_start();
?>
<div class="tcdce-card">
	<a class="tcdce-card__link" href="<?php echo esc_url( $atts['url'] ); ?>">
		<?php if( $image ){ ?>
		<div class="tcdce-card__image">
			<img class="tcdce-card__image-bg" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $title ); ?>">
		</div>
		<?php } ?>
		<div class="tcdce-card__content">
			<?php if( ! $atts['hide-date'] && $date ){ ?>
			<div class="tcdce-card__meta">
				<span class="tcdce-card__meta-date tcdce-card__meta-date--publish">
					<?php echo esc_html( $date ); ?>
				</span>
				<?php if( ! $atts['hide-modify-date'] && $update_date ){ ?>
					<span class="tcdce-card__meta-date tcdce-card__meta-date--modify">
						<?php echo esc_html( $update_date ); ?>
					</span>
				<?php } ?>
			</div>
			<?php } ?>
			<div class="tcdce-card__title"><?php echo esc_html( wp_strip_all_tags( $title ) ); ?></div>
<?php

	$desc = preg_replace( '/<!--more-->.+/is', '', $desc ); // moreタグ以降削除
	$desc = strip_shortcodes( $desc ); // ショートコード削除
	$desc = wp_strip_all_tags( $desc ); // タグの除去
	$desc = str_replace( '&nbsp;', '', $desc ); // 特殊文字の削除（今回はスペースのみ）
	$desc = mb_strimwidth( $desc, 0, 200, '...' ); // 文字列を指定した長さで切り取る

?>
			<div class="tcdce-card__desc"><?php echo esc_html( $desc ); ?></div>
		</div>
	</a>
</div>
<?php
			return ob_get_clean();

		}


		/**
     * ショートコード「吹き出し」
		 *
		 * NOTE: 旧テーマとは別で登録
		 * NOTE: 旧テーマのショートコードの互換性を保つ処理（/theme-suppot/tcd.php）
     */
		public function shortcode_sb( $atts, $content ) {

			$atts = shortcode_atts( array(
				'id' => null,
				'user_name' => '',
				'style' => '',
			), $atts );

			$sb_id = $atts['id'];
			$style = $atts['style'] ? $atts['style'] : '';

			if( $sb_id == null ){
				return;
			}

			$sb_name = $this->tcdce_quicktag[$sb_id]['user_name'] ?? '';
			$sb_name = $atts['user_name'] ? $atts['user_name'] : $sb_name;

			ob_start();
?>
<div class="tcdce-sb" data-key="<?php echo esc_attr( $sb_id ); ?>" style="<?php echo esc_attr( $style ); ?>">
	<div class="tcdce-sb-user">
		<div class="tcdce-sb-user-image"></div>
		<span class="tcdce-sb-user-name js-tcdce-preview-option--text-target"><?php echo esc_html( $sb_name ); ?></span>
	</div>
	<div class="tcdce-sb-content"><?php echo wp_kses_post( wpautop( $content ) ); ?></div>
</div>
<?php
			return ob_get_clean();
		}

		/**
     * ショートコード「Google Maps」
		 *
		 * NOTE: 旧テーマとは別で登録
		 * NOTE: 旧テーマのショートコードも利用できるが併用ｊは考慮していない
     */
		public function shortcode_gmap( $atts ) {

			$atts = shortcode_atts( array(
				'address' => '',
				'image' => '',
				'text' => '',
			), $atts );

			// address check
			if ( ! $atts['address'] ) {
				return '<div class="tcdce-caution">' . __( 'The address has not been entered.', 'tcd-classic-editor' ) . '</div>';
			}

			// api check
			$tcdce_gmap = get_option( 'tcdce_gmap' );
			$api_key = $tcdce_gmap['api'];
			if( ! $api_key ){
				return '<div class="tcdce-caution">' . __( 'Google Maps Platform API key not entered.', 'tcd-classic-editor' ) . '</div>';
			}

			if ( ! wp_script_is( 'qt_google_map_api', 'enqueued' ) ) {
				// Using Google Maps API to embed maps on the website
				$google_map_api_url = add_query_arg(
					array(
						'key' => esc_attr( $api_key ),
						'callback' => 'tcdceInitMap',
						'loading' => 'async'
					),
					'https://maps.googleapis.com/maps/api/js'
				);
				wp_enqueue_script( 'qt_google_map_api', $google_map_api_url, array(), TCDCE_VER, true );
				wp_enqueue_script( 'tcdce-google-map', TCDCE_URL . 'assets/js/google-map.js', array(), TCDCE_VER, true );
			}

			// saturation
			$saturation = $tcdce_gmap['saturation'];

			// map style
			$style = '';

			// font & bg color
			$style .= '--tcdce-gmap-font-color:' . $tcdce_gmap['--tcdce-gmap-font-color'] . ';';
			$style .= '--tcdce-gmap-bg-color:' . $tcdce_gmap['--tcdce-gmap-bg-color'] . ';';

			// marker type
			switch( $tcdce_gmap['marker'] ){

				case 'text' :
					$use_ovarlay = 1;
					$text = $atts['text'] ? $atts['text'] : $tcdce_gmap['text'];
					$image = '';
					break;

				case 'image' :
					$use_ovarlay = 1;
					$text = '';
					$image = wp_get_attachment_url( $tcdce_gmap['--tcdce-gmap-image-url'] ) ? wp_get_attachment_url( $tcdce_gmap['--tcdce-gmap-image-url'] ) : '';
					if( $atts['image'] ){
						$image = $atts['image'];
					}
					if( $image ){
						$style .= '--tcdce-gmap-image-url:url(' . $image . ');';
					}
					break;

				default :
					$use_ovarlay = 0;
					$text = '';
					$image = '';

			}

			ob_start();
?>
<div class="tcdce-gmap" style="<?php echo esc_attr( $style ); ?>">
  <div class="tcdce-gmap-wrapper">
    <div
      class="tcdce-gmap__embed js-tcdce-gmap"
      data-address="<?php echo esc_attr( $atts['address'] ); ?>"
      data-saturation="<?php echo esc_attr( $saturation ); ?>"
      data-use-overlay="<?php echo esc_attr( $use_ovarlay ); ?>"
      data-marker-text="<?php echo esc_attr( $text ); ?>"
    ></div>
  </div>
</div>
<?php
			return ob_get_clean();
		}


		/**
     * ショートコード「タブ」
		 *
		 * NOTE: 旧テーマとは別で登録
		 * NOTE: 旧テーマのショートコードの互換性を保つ処理（/theme-suppot/tcd.php）
     */
		public function shortcode_tab( $atts ) {

			$atts = shortcode_atts( array(
				'tab1' => '',
				'img1' => '',
				'tab2' => '',
				'img2' => '',
				'tab3' => '',
				'img3' => '',
			), $atts );

			$output = '';
			$tab_label = '';
			$tab_field = '';
			$active_class = 'is-active';

			for ($i = 1; $i <= 3; $i++) {
				if( $atts['tab' . $i] ){
					$tab_label .= '<div class="tcdce-tab__label-item tcdce-tab__label-item' . $i . ' ' . $active_class . '">' . esc_html( $atts['tab' . $i] ) . '</div>';
					$tab_field .= '<div class="tcdce-tab__field tcdce-tab__field' . $i . '">';
					$image_id = attachment_url_to_postid( $atts['img' . $i] );
					if( $image_id ){
						$tab_field .= wp_get_attachment_image( $image_id, 'full' );
						if( wp_get_attachment_caption( $image_id ) ){
							$tab_field .= '<span class="tcdce-tab__field-caption">' . wp_get_attachment_caption( $image_id ) . '</span>';
						}
					}
					$tab_field .= '</div>';
					$active_class = '';
				}
			}

			$output .= '<div class="tcdce-tab">';
			$output .= '<div class="tcdce-tab__label">';
			$output .= $tab_label;
			$output .= '</div>';
			$output .= $tab_field;
			$output .= '</div>';

			return $output;

		}

	}

	/**
	 * インスタンス化
	 */
	$tcdce_editor = new TCDCE_Editor();

}
