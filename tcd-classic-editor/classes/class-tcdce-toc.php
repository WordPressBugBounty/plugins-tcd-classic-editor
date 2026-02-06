<?php

/**
 *
 * TCD Table of content class
 *
 * 目次機能
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCDCE_Toc' ) ) {
  class TCDCE_Toc {

    /**
		 * Table of Contents Display Type.
		 *
		 * @var string
		 */
    public $display = 1;

    /**
		 * Post types with table of contents enabled.
		 *
		 * @var array
		 */
    public $post_types = array();

    /**
		 * Anchor links in headings.
		 *
		 * @var string
		 */
    public $target_id = '';

    /**
		 * Scope of headings covered.
		 *
		 * @var string
		 */
    public $target_range = '';

    /**
		 * Number of headings to be covered.
		 *
		 * @var int
		 */
    public $target_counts = 0;

    /**
		 * Class for skipping headings.
		 *
		 * @var string
		 */
    public $skip_class = '';

    /**
		 * Table of Contents Title.
		 *
		 * @var int
		 */
    public $title = '';

    /**
		 * Table of Contents Headings.
		 *
		 * @var array
		 */
    public $headings = array();

    /**
		 * Table of contents for output.
		 *
		 * @var string
		 */
    public $output = '';

    /**
     * Constructor.
     */
    public function __construct() {

      // wpで初期化
      add_action( 'wp', array( $this, 'init' ) );

      // 管理画面の初期化
      add_action( 'admin_init', array( $this, 'admin_init' ) );

      // wpで初期化
      add_action( 'wp_head', array( $this, 'inline_style' ) );
    }

    /**
     * Initialize Table of Contents.
     */
    public function init() {

      // set properties
      $this->set_properties();

      // 非表示ならスルー
      if( ! $this->display ){
        return;
      }

      // トッページは除く
      if( is_front_page() ){
        return;
      }

      // 特定の詳細ページのみ実行
      if( empty( $this->post_types ) || ! is_singular( $this->post_types ) ){
        return;
      }

      // カスタムフィールドで非表示になっていたらスルー
      if( get_post_meta( get_the_ID(), 'hide_toc', true ) == 1 ){
        return;
      }

      // 見出し抽出
      $this->set_headings( get_the_content( null, false, get_queried_object() ) );

      // X個以上存在していたらスルー
      if( empty( $this->headings ) || count( $this->headings ) < $this->target_counts ){
        return;
      }

      // 目次生成
      $this->set_output();

      // ショートコード登録
      add_shortcode( 'tcdce_toc', array( $this, 'register_shortcode' ) );

      // 本文内の見出しを書き換え & 目次設置
      add_filter( 'the_content', array( $this, 'convert_content' ), 9 );

      // スマホ用目次をセット
      add_action( 'wp_footer', array( $this, 'output_footer' ) );
    }

    /**
     * Each set of properties.
     */
    public function set_properties() {

      $tcdce_toc = get_option( 'tcdce_toc' );

      /**
       * Filters TCD Toc class default option filter.
       */
      $args = apply_filters(
        'tcdce_toc_default_options',
        array(
          'display'       => $tcdce_toc['display'],
          'post_types'    => array_keys( $tcdce_toc['post_types'], 1 ),
          'target_id'     => 'toc-',
          'target_range'  => $tcdce_toc['range'],
          'target_counts' => $tcdce_toc['count'],
          'skip_class'    => 'no-toc',
          'title'         => $tcdce_toc['title'],
        )
      );

      $this->display       = $args['display'];
      $this->post_types    = $args['post_types'];
      $this->target_id     = $args['target_id'];
      $this->target_range  = $args['target_range'];
      $this->target_counts = (int) $args['target_counts'];
      $this->skip_class    = $args['skip_class'];
      $this->title         = $args['title'] ? $args['title'] : __( 'Table of contents', 'tcd-classic-editor' );
    }

    /**
     * Set extracted headings.
     */
    public function set_headings( $content ) {

      // 投稿タイプやカスタムフィールド別に有効化されているか
      preg_match_all(
        '/<h[' . $this->target_range . '](.*?)>(.*?)<\/h([' . $this->target_range . '])>/s',
        $content,
        $matches,
        PREG_SET_ORDER
      );

      // マッチしなければ終了
      if( empty( $matches ) ){
        return;
      }

      // 配列作成
      foreach( $matches as $index => $match ) {

        $index++;

        // no_tocが無い && hタグに文字が存在していれば
        if( strpos( $match[1], $this->skip_class ) === false && strlen( $match[2] ) ){

          // id抽出
          $match[4] = $this->target_id . $index;
          if( preg_match( '/id=["\'](.*?)["\']/i', $match[1], $id )) {
            $match[4] = $id[1];
          }

          // 配列作成
          $this->headings[$index] = array_combine( array(
            'el',   // hタグ全部
            'attr', // hタグのセレクタのみ
            'txt',  // hタグに囲まれた文字列
            'lvl',  // hタグの数字
            'id'    // hタグのid
          ), $match );

        }
      }

    }

    /**
     * Register Table of contents shortcode.
     */
    public function register_shortcode() {
      if( $this->display == 1 || $this->display == 3 ){
        return '<div class="p-toc">' . $this->output . '</div>';
      }else{
        return '';
      }
    }

    /**
     * Convert headings in the post content.
     */
    public function convert_content( $content ) {

      foreach ( $this->headings as $key => $heading ){

        // 最初のH2に目次を設置
        if( $key === array_key_first( $this->headings ) && ! has_shortcode( $content, 'tcdce_toc' ) ) {
          $content = preg_replace(
            '/(' . preg_quote( $heading['el'], '/' ) . ')/su',
            '[tcdce_toc]$1',
            $content,
            1
          );
        }

        // idがなければ置換え
        if( strpos( $heading['attr'], 'id=' ) === false ){
          $content = preg_replace(
            '/' . preg_quote( $heading['el'], '/' ) . '/su',
            preg_replace( '/<(h[2-6])/s', '<$1 id="' . esc_attr( $heading['id'] ) . '"', $heading['el'], 1 ),
            $content,
            1
          );
        }
      }

      return $content;
    }

    /**
     * Set table of contents data to be output.
     */
    public function set_output() {

      $toc = '<span class="p-toc-headline">' . $this->title . '</span>';
      $current_level = 1;

      foreach ( $this->headings as $heading ) {

        $level = (int) $heading['lvl'];

        // 見出しのレベルが指定したレベルよりも低い間
        while ( $current_level < $level ) {
          $class = $current_level === 1 ? '' : 'child';
          $toc .= '<ul class="' . $class . '">';
          $current_level++;
        }

        // 見出しのレベルが指定したレベルよりも大きい間
        while ( $current_level > $level ) {
          $toc .= '</li></ul>';
          $current_level--;
        }

        $toc .= '<li>';
        $toc .= '<a href="#' . esc_attr( $heading['id'] ) . '">' . wp_strip_all_tags( $heading['txt'] ) . '</a>';
      }

      // 目次の最後の項目をどの要素から作成したかによりタグの閉じ方を変更
      while ( $current_level > 1 ) {
        $toc .= '</li></ul>';
        $current_level--;
      }

      $this->output = $toc;
    }

    /**
     * Output table of contents in footer.
     */
    public function output_footer() {
      echo '<div id="js-tcdce-toc-open" class="p-toc-open"></div>';
      echo '<div id="js-tcdce-toc-modal" class="p-toc-modal">';
      echo '<div class="p-toc">' . wp_kses_post( $this->output ) . '</div>';
      echo '<button class="js-tcdce-toc-close p-toc-modal-close"></button>';
      echo '<div class="js-tcdce-toc-close p-toc-modal-overlay"></div>';
      echo '</div>';
    }

    /**
     * Output inline style.
     */
    public function inline_style() {
      $media_query = apply_filters( 'tcdce_toc_show_breakpoint', 991 );
      printf(
        '<style>
          @media not all and (max-width: %1$spx) {
            .p-toc-open, .p-toc-modal { display: none; }
          }
          @media (max-width: %1$spx) {
            .widget_tcdce_toc_widget { display: none; }
          }
        </style>' . "\n", // 改行を追加してCSS圧縮時のバグを防止
        esc_attr( $media_query )
      );
    }

    /**
     * Initialization of management screen.
     */
    public function admin_init() {
      add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ), 10, 2 );
      add_action( 'save_post', array( $this, 'save_meta_box' ) );
    }

    /**
     * Add meta boxes.
     */
    public function add_meta_boxes( $post_type, $post ) {

      // ホームページ・投稿ページの固定ページは除外
      if (
        'page' === get_option( 'show_on_front' ) &&
        'page' === $post_type &&
        $post->ID &&
        ( get_option( 'page_on_front' ) == $post->ID || get_option( 'page_for_posts' ) == $post->ID )
      ) {
        return;
      }

      add_meta_box(
        'tcdce_toc',
        __( 'Table of contents', 'tcd-classic-editor' ),
        array( $this, 'add_meta_box' ),
        array( 'post', 'page', 'news', 'review' ),
        'side',
        'low'
      );
    }

    /**
     * Add meta box.
     */
    public function add_meta_box( $post ) {
      if( in_array( get_post_type( $post ), $this->post_types, true ) || $this->display ){
        wp_nonce_field( basename( __FILE__ ), 'tcdce_toc_meta_box_nonce' );

        echo '<p><a href="' . esc_url( add_query_arg( array( 'page' => 'tcd_classic_editor_toc' ), admin_url( 'admin.php' ) ) ) . '">';
        esc_html_e( 'Click here to set the table of contents.', 'tcd-classic-editor' );
        echo '</a></p>';
        echo '<input type="hidden" name="hide_toc" value="0"/>';
        echo '<label>';
        echo '<input type="checkbox" name="hide_toc" value="1"' . checked( 1, get_post_meta( $post->ID, 'hide_toc', true ), false ) . '/>';
        esc_html_e( 'Hide the table of contents', 'tcd-classic-editor' );
        echo '</label>';
      }
    }

    /**
     * Save meta box.
     */
    public function save_meta_box( $post_id ) {

      // verify nonce
      if ( ! isset( $_POST['tcdce_toc_meta_box_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash ( $_POST['tcdce_toc_meta_box_nonce'] ) ) , basename(__FILE__) ) ) {
        return $post_id;
      }

      // check autosave
      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return $post_id;
      }

      // ユーザー権限を調べ、権限がなければ処理をしない
      $post_type = isset( $_POST['post_type'] ) ? sanitize_key( wp_unslash( $_POST['post_type'] ) ) : '';
      if ( 'page' == $post_type ) {
        if ( ! current_user_can( 'edit_page', $post_id ) ) {
          return $post_id;
        }
      } elseif ( ! current_user_can( 'edit_post', $post_id ) ) {
        return $post_id;
      }

      // 保存処理
      if ( isset( $_POST[ 'hide_toc' ] ) ) {
        update_post_meta( $post_id, 'hide_toc', sanitize_key( $_POST[ 'hide_toc' ] ) );
      }

    }

  }

  /**
   * インスタンス化
   */
  $tcdce_toc = new TCDCE_Toc();

}