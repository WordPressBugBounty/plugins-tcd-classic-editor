<?php
/*
 *
 * TCDブロックエディター用クラス
 *
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCDCE_Block_Editor' ) ) {
  class TCDCE_Block_Editor extends TCDCE_Editor {

    /**
		 * Quick tags to register in the block editor.
		 *
		 * @var array
		 */
    public $tcdce_blocks = array();

    /**
     * Constructor.
     */
    public function __construct() {

      // 親クラスの変数をセット
      $this->set_vars();

      // テーマ読み込み後に実行
      add_action( 'after_setup_theme', array( $this, 'init' ) );

    }

    /**
     * init.
     */
    public function init() {

      // 埋め込みのレスポンシブ対応の有効化（ブロック）
			if( ! current_theme_supports( 'responsive-embeds' ) ){
				add_theme_support( 'responsive-embeds' );
			}

      // ブロックスタイル（画像-sshot）の追加
			register_block_style(
				'core/image', array(
						'name' => 'sshot',
						'label' => __( 'Border', 'tcd-classic-editor' ),
				)
			);

			// 専用ブロックカテゴリーの追加
			add_filter( 'block_categories_all', array( $this, 'add_block_categories' ) );

      // クイックタグデータをブロック用に変換
      $this->set_blocks();

			// javascript読み込み
      add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );

			// エディター本文用スタイル
			add_action( 'enqueue_block_assets', array( $this, 'enqueue_block_editor_styles' ) );

    }


		/**
     * ブロックカテゴリー（tcdce）の追加
     */
		public function add_block_categories( $categories ) {
			array_unshift( $categories, array(
				'slug'  => 'tcdce',
				'title' => __( 'TCD Classic Editor', 'tcd-classic-editor' ),
				'icon'  => null,
			) );
			return $categories;
		}

    /**
     * ブロックエディタ用CSS、JSの読み込み
     */
		public function set_blocks() {

      if( empty( $this->tcdce_quicktag ) ){
        return;
      }

      foreach( $this->tcdce_quicktag as $key => $quicktag ){
        if( ! $quicktag['show'] ){
          continue;
        }

        $register_block = apply_filters( "tcdce_block_register_{$quicktag['item']}", array(), $quicktag, $key );
        if( ! empty( $register_block ) ){
          $this->tcdce_blocks[] = $register_block;
        }
      }

      // その他ブロックを登録する場合は、このフィルターを利用
      $this->tcdce_blocks = apply_filters( "tcdce_block_register_after", $this->tcdce_blocks );

      // 囲み枠、ボタンチェック
      if( ! empty( $this->tcdce_blocks ) ){

        $register_block_names = array_column( $this->tcdce_blocks, 'name' );

        // ボタンのデフォルトバリエーション登録
        if ( in_array( 'core/button', $register_block_names, true ) ) {
          array_unshift( $this->tcdce_blocks, array(
            'name' => 'core/button',
            'settings' => array(
              'name' => 'button-default',
              'title' => __( 'Default', 'tcd-classic-editor' ),
              'attributes' => array(
                'className' => '',
              ),
              'scope' => ['transform']
            )
          ) );
        }

        // 囲み枠のデフォルトバリエーション登録
        if ( in_array( 'core/paragraph', $register_block_names, true ) ) {
          array_unshift( $this->tcdce_blocks, array(
            'name' => 'core/paragraph',
            'settings' => array(
              'name' => 'box-default',
              'title' => __( 'Default', 'tcd-classic-editor' ),
              'attributes' => array(
                'className' => '',
              ),
              'scope' => ['transform']
            )
          ) );
        }

      }

		}

		/**
     * ブロックエディタ用JSの読み込み
     */
		public function enqueue_block_editor_assets() {
			wp_enqueue_script(
				'tcdce-block-editor',
				TCDCE_URL . 'assets/js/block-editor.js',
				array( 'wp-blocks', 'wp-element', 'wp-components', 'wp-editor', 'wp-dom-ready', 'wp-edit-post' ),
        filemtime( TCDCE_PATH . 'assets/js/block-editor.js' ),
				true
			);

      if( ! empty( $this->tcdce_blocks ) ){
				wp_localize_script( 'tcdce-block-editor', 'tcdceBlockEditorObj', $this->tcdce_blocks );
			}

		}


		/**
		 * ブロックエディターに本文用スタイルを追加する
		 *
		 * enqueue_block_assetsから読み込むことで、add_editor_style()による
		 * 内部HTTPリクエストを発生させずにエディターのiframeへ渡す。
		 */
		public function enqueue_block_editor_styles() {
			if ( ! is_admin() ) {
				return;
			}

			wp_enqueue_style(
				'tcdce-block-editor-utility',
				TCDCE_URL . 'assets/css/utility.css',
				array(),
				filemtime( TCDCE_PATH . 'assets/css/utility.css' )
			);

			wp_enqueue_style(
				'tcdce-block-editor-content',
				TCDCE_URL . 'assets/css/editor.css',
				array( 'tcdce-block-editor-utility' ),
				filemtime( TCDCE_PATH . 'assets/css/editor.css' )
			);

			// iframeの高さ計算へ影響させず、本文幅に応じたコンテナクエリを維持する。
			wp_add_inline_style(
				'tcdce-block-editor-content',
				'.editor-styles-wrapper { container: normal; } .editor-styles-wrapper .is-root-container { container: tcdce / inline-size; }'
			);

			wp_enqueue_style(
				'tcdce-block-editor-old-style',
				TCDCE_URL . 'assets/css/old-style.css',
				array( 'tcdce-block-editor-content' ),
				filemtime( TCDCE_PATH . 'assets/css/old-style.css' )
			);

			wp_add_inline_style( 'tcdce-block-editor-old-style', $this->render_quicktag_style() );
		}

  }

  /**
   * インスタンス化
   */
  $tcdce_block_editor = new TCDCE_Block_Editor();

}
