<?php

/**
 * Table of contents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// 基本 初期化
add_action( 'tcdce_add_admin_menu', function( $instance ){

  $instance->submenus[] = array(

    // add menu
		'title' => __( 'Basic settings', 'tcd-classic-editor' ),
		'capability' => 'manage_options',
		'slug' => 'tcd_classic_editor_basic',
		'callback' => 'tcdce_basic_menu_template',

    // add setting
    'setting' => array(
      'option_group' => 'tcdce_basic_group',
      'option_name' => 'tcdce_basic',
      'sanitize_callback' => 'tcdce_basic_setting_validation',
      'default_callback' => 'tcdce_basic_setting_default'
    )

  );

} );

// 初期値
function tcdce_basic_setting_default(){

  return array(
    '--tcdce-base-font-size-pc' => 16,
    '--tcdce-base-font-size-sp' => 14,
    '--tcdce-base-link-color' => '#0b57d0',
  );
}

// バリデーション
function tcdce_basic_setting_validation( $value ){

  // reset
  if( isset( $value['reset'] ) && $value['reset'] == 1 ){

    update_option( 'tcdce_reset', 1 );
    return tcdce_basic_setting_default();

  // save
  }else{

    return array(
      '--tcdce-base-font-size-pc' => absint( $value['--tcdce-base-font-size-pc'] ),
      '--tcdce-base-font-size-sp' => absint( $value['--tcdce-base-font-size-sp'] ),
      '--tcdce-base-link-color' => sanitize_hex_color( $value['--tcdce-base-link-color'] ),
    );

  }

}

// テンプレート
function tcdce_basic_menu_template( $submenu ){

?>
  <h1 class="tcdce-page__headline"><?php echo esc_html( $submenu['title'] ); ?></h1>
  <p class="tcdce-page__desc">
    <?php esc_html_e( 'Sets the basic style on which the body text is based.', 'tcd-classic-editor' ); ?><br>
    <?php
      printf(
        /* translators: %1$s: external page links start %2$s: external page links end */
        esc_html__( 'Please register and use %1$s"Quick Tags"%2$s for parts such as headings and buttons.', 'tcd-classic-editor' ),
        '<a href="' . esc_url( menu_page_url( 'tcd_classic_editor_quicktag', false ) ) . '" target="_blank" rel="noopener noreferrer">',
        '</a>'
      );
    ?>
  </p>
  <div class="tcdce-setting">
    <form id="js-tcdce-form" class="tcdce-setting__form" action="options.php" method="post">
<?php

    settings_fields( $submenu['setting']['option_group'] );

    $base_name = $submenu['setting']['option_name'];
    $tcdce_basic = get_option( $base_name );

    $tcdce_qt_fields = new TCDCE_Qt_Fields();

?>
      <div class="tcdce-base-fields">

        <?php // 文字サイズ ?>
        <div class="tcdce-base-fields__item">
          <div class="tcdce-base-fields__item-left">
            <span class="tcdce-base-fields__item-title"><?php esc_html_e( 'Font size of main text', 'tcd-classic-editor' ); ?></span>
            <p class="tcdce-base-fields__item-desc"><?php esc_html_e( 'Sets the base font size for the body text.', 'tcd-classic-editor' ); ?></p>
          </div>
          <div class="tcdce-base-fields__item-right">
<?php

        // 文字サイズ
        echo wp_kses(
          $tcdce_qt_fields->number( $base_name, $tcdce_basic, array(
            '--tcdce-base-font-size-pc' => array(
              'icon' => TCDCE_ICONS['pc'],
              'default' => 16,
            ),
            '--tcdce-base-font-size-sp' => array(
              'icon' => TCDCE_ICONS['sp'],
              'default' => 16,
            ),
          ) ),
          wp_kses_allowed_html( 'tcdce' )
        );

?>
          </div>
        </div>
        <?php // リンクカラー ?>
        <div class="tcdce-base-fields__item">
          <div class="tcdce-base-fields__item-left">
            <span class="tcdce-base-fields__item-title"><?php esc_html_e( 'Link color of text', 'tcd-classic-editor' ); ?></span>
            <p class="tcdce-base-fields__item-desc"><?php esc_html_e( 'Sets the text color of text links in the body text.', 'tcd-classic-editor' ); ?></p>
          </div>
          <div class="tcdce-base-fields__item-right">
<?php

        // リンクカラー
        echo wp_kses(
          $tcdce_qt_fields->color( $base_name, $tcdce_basic, '--tcdce-base-link-color' ),
          wp_kses_allowed_html( 'tcdce' )
        );

?>
          </div>
        </div>

      </div>
      <?php $tcdce_qt_fields->submit(); ?>
      <?php $tcdce_qt_fields->reset( $base_name ); ?>
    </form>
  </div>
<?php

}

// エディタにスタイル反映
add_filter( 'tcdce_render_quicktag_style', 'tcdce_render_quicktag_basic_style' );
function tcdce_render_quicktag_basic_style( $css ) {

  $tcdce_basic = wp_parse_args(
    get_option( 'tcdce_basic' ),
    tcdce_basic_setting_default()
  );

  // テーマ側のフロントで使えるようにルートで定義
  if( ! is_admin() ){
    $css .= ':root {';
    $css .= '--tcdce-base-link-color:' . $tcdce_basic['--tcdce-base-link-color'];
    $css .= '}';
  }

  // 管理画面・フロントで利用するための設定
  $css .= '.tcdce-body, .editor-styles-wrapper {';
  $css .= '--tcdce-base-font-size-pc:' . $tcdce_basic['--tcdce-base-font-size-pc'] . 'px;';
  $css .= '--tcdce-base-font-size-sp:' . $tcdce_basic['--tcdce-base-font-size-sp'] . 'px;';
  $css .= '--tcdce-base-link-color:' . $tcdce_basic['--tcdce-base-link-color'];
  $css .= '}';
  return $css;
}