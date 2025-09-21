<?php

/**
 * TCDテーマ専用サポート
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// テーマ専用スタイル
add_action( 'wp_enqueue_scripts', 'tcdce_enqueue_inline_asset', 11 );
function tcdce_enqueue_inline_asset(){

  // inline style
  wp_add_inline_style(
    'tcdce-editor',
    apply_filters( 'tcdce_enqueue_inline_style', '' )
  );

  // inline script
  wp_add_inline_script(
    'tcdce-editor',
    apply_filters( 'tcdce_enqueue_inline_script', '' )
  );

}

// 旧テーマの吹き出し対応
add_action( 'after_setup_theme', function(){

  $old_sbs = array(
    1 => 'speech_balloon_left1',
    2 => 'speech_balloon_left2',
    3 => 'speech_balloon_right1',
    4 => 'speech_balloon_right2'
  );

  foreach( $old_sbs as $sb_key => $sb_name ){
    if( shortcode_exists( $sb_name ) ){
      add_shortcode( $sb_name, function( $atts, $content ) use( $sb_name, $sb_key ) {

        if( ! function_exists( 'get_design_plus_option' ) ){
          return;
        }

        $dp_options = get_design_plus_option();

        $image = $dp_options['qt_speech_balloon' . $sb_key . '_user_image'] ?? '';
        $image_url = wp_get_attachment_url( $image ) ?: '';
        $font_color = $dp_options['qt_speech_balloon' . $sb_key . '_font_color'] ?? '';
        $bg_color = $dp_options['qt_speech_balloon' . $sb_key . '_bg_color'] ?? '';
        $border_color = $dp_options['qt_speech_balloon' . $sb_key . '_border_color'] ?? '';
        $user_name = $dp_options['qt_speech_balloon' . $sb_key . '_user_name'] ?? '';

        /**
         * TENJIKU以前の吹き出しデータ対策
         *
         * NOTE: クイックタグの保存データ名が異なる
         */
        // 画像
        if( isset( $dp_options['qt_speech_balloon_user_image' . $sb_key] ) ){
          $image_url = wp_get_attachment_url( $dp_options['qt_speech_balloon_user_image' . $sb_key] ) ?: '';
        }
        // 文字色
        if( isset( $dp_options['qt_speech_balloon_font_color' . $sb_key] ) ){
          $font_color = $dp_options['qt_speech_balloon_font_color' . $sb_key];
        }
        // 背景色
        if( isset( $dp_options['qt_speech_balloon_bg_color' . $sb_key] ) ){
          $bg_color = $dp_options['qt_speech_balloon_bg_color' . $sb_key];
        }
        // ボーダーカラー
        if( isset( $dp_options['qt_speech_balloon_border_color' . $sb_key] ) ){
          $border_color = $dp_options['qt_speech_balloon_border_color' . $sb_key];
        }
        // 名前
        if( isset( $dp_options['qt_speech_balloon_user_name' . $sb_key] ) ){
          $user_name = $dp_options['qt_speech_balloon_user_name' . $sb_key];
        }

        $style =
        '--tcdce-sb-font-color:' . $font_color . ';' .
        '--tcdce-sb-image-url:url(' . $image_url . ');' .
        '--tcdce-sb-preset-color--bg:' . $bg_color . ';' .
        '--tcdce-sb-preset-color--border:' . $border_color . ';' .
        '--tcdce-sb-background: var(--tcdce-sb-preset-color--bg);' .
        '--tcdce-sb-border-color: var(--tcdce-sb-preset-color--border);' .
        '--tcdce-sb-padding: 1em 1.5em;';

        if( $sb_key == 1 || $sb_key == 2 ){
          $style .=
          '--tcdce-sb-direction: row;' .
          '--tcdce-sb-triangle-before-offset: -10px;' .
          '--tcdce-sb-triangle-after-offset: -7px;' .
          '--tcdce-sb-triangle-path: polygon(100% 0, 0 50%, 100% 100%);';

        }elseif( $sb_key == 3 || $sb_key == 4 ){
          $style .=
          '--tcdce-sb-direction: row-reverse;' .
          '--tcdce-sb-triangle-before-offset: 100%;' .
          '--tcdce-sb-triangle-after-offset: calc(100% - 3px);' .
          '--tcdce-sb-triangle-path: polygon(0 0, 0% 100%, 100% 50%);';

        }

        $atts = array(
          'id' => $sb_name,
          'user_name' => $user_name,
          'style' => $style,
        );

        global $tcdce_editor;
        return $tcdce_editor->shortcode_sb( $atts, $content );

      } );
    }
  }

});


/**
 * 強制的にクイックタグの利用設定をオフに
 * クイックタグの見出し設定などが反映されないように
 */
function tcdce_disable_theme_quicktag( $option ){
  if( ! is_admin() && isset( $option['use_quicktags'] ) ){
    $option['use_quicktags'] = 0;
  }
  return $option;
}

/**
 * 汎用メッセージ: プラグイン有効化時はテーマのクイックタグを使えない
 *
 * NOTE: 110以前のテーマは共通で、スタートガイドに表示
 */
function tcdce_top_menu_common_caution(){
?>
<p class="p-guide-caution">
  <?php esc_html_e( 'The "Quick Tags" feature of the TCD theme is not available while this plugin is activated.', 'tcd-classic-editor' ); ?>
</p>
<?php
}

/**
 * 汎用メッセージ: このテーマでは、基本設定はお使いいただけません。TCDテーマオプションの設定が反映されます。
 *
 * NOTE: 110以前のテーマは共通で、基本設定に表示
 */
function tcdce_submenu_basic_common_caution(){
?>
<p class="p-guide-caution is-setting-disabled">
  <?php esc_html_e( 'Basic settings are not available for this theme.', 'tcd-classic-editor' ); ?><br>
  <?php esc_html_e( 'The settings in the TCD theme options will be applied.', 'tcd-classic-editor' ); ?>
</p>
<?php
}

/**
 * 汎用メッセージ: このテーマにはすでに目次機能が備わっているため、プラグインの目次機能を利用できません。
 *
 * NOTE: 目次実装済みテーマ向け
 * add_action( 'tcdce_submenu_tcd_classic_editor_toc', 'tcdce_submenu_disable_toc_caution' );
 */
function tcdce_submenu_disable_toc_caution(){
?>
<p class="p-guide-caution is-setting-disabled">
  <?php esc_html_e( "Since this theme already has a table of contents feature, the plugin's table of contents feature cannot be used.", 'tcd-classic-editor' ); ?>
</p>
<?php
}

/**
 * 汎用メッセージ: このテーマはサイドバーに目次を表示できません。
 *
 * NOTE: サイドバーが無いテーマ向け
 * add_action( 'tcdce_submenu_tcd_classic_editor_toc', 'tcdce_submenu_disable_sidebar_toc_caution' );
 */
function tcdce_submenu_disable_sidebar_toc_caution(){
?>
<p class="p-guide-caution">
  <?php esc_html_e( 'This theme cannot display a table of contents in the sidebar.', 'tcd-classic-editor' ); ?>
</p>
<?php
}

/**
 * 目次ウィジェットの削除
 * add_action( 'widgets_init', 'tcdce_unregister_toc_widget' );
 */
function tcdce_unregister_toc_widget() {
  unregister_widget( 'TCDCE_Toc_Widget' );
}

/**
 * 目次の表示オプションからサイドバーの選択肢をなくす場合に利用
 */
// add_filter( 'tcdce_toc_setting_display_options', function( $options ){
//   unset($options[2]);
//   return $options;
// });

/**
 * 目次のスマホアイコンを表示するブレイクポイントを指定（サイドバーがある場合は合わせる）
 */
// add_filter( 'tcdce_toc_show_breakpoint', fn() => 991 );
