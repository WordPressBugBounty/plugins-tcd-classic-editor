<?php

/**
 * Quicktag
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Quicktag 初期化
add_action( 'tcdce_add_admin_menu', function( $instance ){

  $instance->submenus[] = array(

    // add menu
		'title' => __( 'Quicktag', 'tcd-classic-editor' ),
		'capability' => 'manage_options',
		'slug' => 'tcd_classic_editor_quicktag',
		'callback' => 'tcdce_quicktag_menu_template',

    // add setting
    'setting' => array(
      'option_group' => 'tcdce_quicktag_group',
      'option_name' => 'tcdce_quicktag',
      'sanitize_callback' => 'tcdce_quicktag_setting_validation',
      'default_callback' => 'tcdce_quicktag_setting_default'
    )

  );

} );

// 初期値
function tcdce_quicktag_setting_default(){

  // filter
  $tcdce_qt_fields = new TCDCE_Qt_Fields();

	$default = array(

    // h2
    100 => $tcdce_qt_fields->get_default( 'h2' ),

    // h3
    101 => $tcdce_qt_fields->get_default( 'h3' ),

    // h4
    102 => $tcdce_qt_fields->get_default( 'h4' ),

    // h5
    103 => $tcdce_qt_fields->get_default( 'h5' ),

    // h6
    104 => $tcdce_qt_fields->get_default( 'h6' ),

    // ul
    105 => $tcdce_qt_fields->get_default( 'ul' ),

    // ol
    106 => $tcdce_qt_fields->get_default( 'ol' ),

    // box
    107 => $tcdce_qt_fields->get_default( 'box' ),

    // marker
    108 => $tcdce_qt_fields->get_default( 'marker' ),

    // button
    109 => $tcdce_qt_fields->get_default( 'button' ),

    // speech bubble
    110 => $tcdce_qt_fields->get_default( 'sb' ),

    // cardlink
    111 => $tcdce_qt_fields->get_default( 'cardlink' ),

    // custom tag
    112 => $tcdce_qt_fields->get_default( 'custom_tag' ),

  );

  return apply_filters( "tcdce_quicktag_setting_default", $default );
}


// バリデーション
function tcdce_quicktag_setting_validation( $value ){

  // reset
  if( isset( $value['reset'] ) && $value['reset'] == 1 ){

    update_option( 'tcdce_reset', 1 );
    return tcdce_quicktag_setting_default();

  // save
  }else{

    $new_values = array();
    if( ! empty( $value ) ){
      foreach( $value as $key => $new_value ){
        $item = $new_value['item'];
        $new_values[$key] = apply_filters( "tcdce_qt_validation_{$item}", $new_value );
      }
      $value = $new_values;
    }

    return $value;

  }

}


// メニューテンプレート
function tcdce_quicktag_menu_template( $submenu ){

?>
  <h1 class="tcdce-page__headline"><?php echo esc_html( $submenu['title'] ); ?></h1>
  <p class="tcdce-page__desc">
    <?php esc_html_e( 'The ability to add quick tags to the classic editor.', 'tcd-classic-editor' ); ?><br>
    <?php esc_html_e( 'Add headings, lists, buttons, and other items you need to use your own quick tags.', 'tcd-classic-editor' ); ?><br>
    <?php esc_html_e( 'We have already designed presets for you to set up each quick tag. Please select the preset of your choice and then make your own adjustments.', 'tcd-classic-editor' ); ?>
  </p>
<?php

  // main content

?>
  <div class="tcdce-setting">
    <?php // インポート / エクスポート ?>
    <?php tcdce_import_export_view(); ?>
    <form id="js-tcdce-form" class="tcdce-setting__form" action="options.php" method="post">
<?php

  $tcdce_qt_fields = new TCDCE_Qt_Fields();

  settings_fields( $submenu['setting']['option_group'] );

?>
      <h2 class="tcdce-setting__headline">
        <?php esc_html_e( 'Registered Quicktags', 'tcd-classic-editor' ); ?>
      </h2>
<?php

  // repeater
  $tcdce_quicktag = get_option( $submenu['setting']['option_name'] );

?>
      <div class="tcdce-repeater">
        <div class="tcdce-repeater__list js-tcdce-repeater-list js-tcdce-repeater-sortable">
<?php

  if( ! empty( $tcdce_quicktag ) ){
    foreach( $tcdce_quicktag as $key => $tcdce_qt ) {
      $tcdce_qt_fields->repeater(
        $tcdce_qt['item'],
        $key,
        'tcdce_quicktag[' . $key . ']',
        $tcdce_qt
      );
    }
  }

?>
        </div>
      </div>
      <div class="tcdce-repeater-unregistered">
        <?php esc_html_e( 'Add an item.', 'tcd-classic-editor' ); ?>
      </div>
<?php

  // repeater END

  // add field
  $tcdce_qt_fields_labels = $tcdce_qt_fields->get_label();

?>
      <h2 class="tcdce-setting__headline">
        <?php esc_html_e( 'Quicktags you can add', 'tcd-classic-editor' ); ?>
      </h2>
      <div class="tcdce-repeater-add">
        <?php foreach( $tcdce_qt_fields_labels as $item => $label ){ ?>
        <div class="tcdce-repeater-add__item js-tcdce-repeater-add" data-item="<?php echo esc_attr( $item ); ?>">
          <span class="tcdce-repeater-add__item-icon"><?php echo wp_kses( TCDCE_ICONS[$item], wp_kses_allowed_html( 'tcdce' ) ); ?></span>
          <?php echo esc_html( $label ); ?>
        </div>
        <?php } ?>
      </div>
<?php

  $tcdce_qt_fields->submit();

  $tcdce_qt_fields->reset( 'tcdce_quicktag' );

?>
      </form>
    </div>
<?php


  // モーダル
  $tcdce_qt_fields_previews = $tcdce_qt_fields->get_preview();
  foreach( $tcdce_qt_fields_previews as $item => $preview ){
    if( $preview == null ){
      return;
    }
    $label = $tcdce_qt_fields->get_label( $item );
    $preset_list = $tcdce_qt_fields->get_preset( $item );
?>
  <div class="tcdce-modal js-tcdce-preset-modal" data-preset-type="<?php echo esc_attr( $item ); ?>">
    <div class="tcdce-modal__scroll js-tcdce-preset-modal-scroll">
      <div class="tcdce-modal__content">

        <div class="tcdce-modal__header">
          <h2 class="tcdce-modal__header-title">
            <?php
              /* translators: %s: quicktag label */
              printf( esc_html__( '%s Presets', 'tcd-classic-editor' ), esc_html( $label ) );
            ?>
          </h2>
          <span class="tcdce-modal__header-close js-tcdce-preset-close"><?php esc_html_e( 'Close', 'tcd-classic-editor' ); ?></span>
        </div>

        <?php if( $item == 'button' ){ ?>
        <div class="tcdce-modal__desc">
          <?php esc_html_e( 'Hover the mouse cursor over the button to see the hover animation.', 'tcd-classic-editor' ); ?>
        </div>
        <?php } ?>

        <ul class="tcdce-modal__preset">
          <?php
            foreach( $preset_list as $preset_key => $preset_info ){

              // style
              $style = '';
              foreach( $preset_info['style'] as $property => $value ){
                // 数値ならpxつける
                if( is_int( $value ) ){
                  $value = $value . 'px';
                }
                $style .= $property . ':' . $value . ';';
              }
          ?>
          <li class="tcdce-modal__preset-item js-tcdce-preset-item" data-preset="<?php echo esc_attr( $preset_key ); ?>" data-preset-label="<?php echo esc_attr( $preset_info['label'] ); ?>" style="<?php echo esc_attr( $style ); ?>">
            <span class="tcdce-modal__preset-item__label"><?php echo esc_html( $preset_info['label'] ); ?></span>
            <div class="tcdce-modal__preset-item__preview tcdce-body">
              <?php echo wp_kses( $preview, wp_kses_allowed_html( 'tcdce' ) ); ?>
            </div>
          </li>
          <?php } ?>
        </ul>
        <div class="tcdce-modal__select">
          <span class="tcdce-modal__select-button js-tcdce-preset-load"><?php esc_html_e( 'Load Preset', 'tcd-classic-editor' ); ?></span>
        </div>
      </div>
    </div>
    <div class="tcdce-modal__overlay js-tcdce-preset-close"></div>
  </div>
<?php

  }

}