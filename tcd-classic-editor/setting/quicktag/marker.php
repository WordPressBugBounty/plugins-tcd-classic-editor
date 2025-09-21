<?php

/**
 * クイックタグ設定 marker
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// ブロックエディタにバリエーション登録
add_filter( 'tcdce_block_register_marker', function( $value, $quicktag, $key ) {
  return array(
    'name' => 'toolbar/marker',
    'settings' => array(
      'name' => 'marker-' . $key,
      'title' => $quicktag['label'],
      'className' => $quicktag['class'],
    )
  );
}, 10, 3 );


// エディタにクイックタグ登録
add_filter( 'tcdce_qt_register_marker', function( $value, $quicktag ) {
  return array(
    'display' => $quicktag['label'],
    'tagStart' => '<span class="tcdce-marker ' . esc_attr( $quicktag['class'] ) . '">',
    'tagEnd' => '</span>'
  );

}, 10, 2 );


// データセット
add_action( 'tcdce_qt_fields_set_properties', function( $instance ) {

  // ラベルをセット
  $instance->set_label( 'marker', __( 'Marker', 'tcd-classic-editor' ) );

  // プレビュー情報をセット
  $instance->set_preview(
    'marker',
    '<div style="text-align:center;">
      <span class="tcdce-preview--marker tcdce-marker js-tcdce-preview-target">' .
        /* translators: %s: quicktag marker label */
        sprintf( __( 'Sample %s', 'tcd-classic-editor' ), $instance->get_label( 'marker' ) ) .
      '</span>
    </div>'
  );

  // 初期値をセット
  $instance->set_default( 'marker', array(
    'item' => 'marker',
    'show' => 1,
    'class' => 'custom_marker',
    /* translators: %s: quicktag marker label */
    'label' => sprintf( __( 'Custom %s', 'tcd-classic-editor' ), $instance->get_label( 'marker' ) ),
    'preset' => 'preset01',
    'style' => array(
      '--tcdce-marker-font-weight' => '400',
      '--tcdce-marker-color' => '#fff799',
      '--tcdce-marker-weight' => '0.8em',
      '--tcdce-marker-animation' => 'none',
    )
  ) );

  // プリセットデータをセット
  $instance->set_preset( 'marker', array(

    'preset01' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 1 ),
      'style' => array(
        '--tcdce-marker-font-weight' => '400',
        '--tcdce-marker-color' => '#0E7199',
        '--tcdce-marker-weight' => '1em',
        '--tcdce-marker-animation' => 'none',
      )
    ),
    'preset02' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 2 ),
      'style' => array(
        '--tcdce-marker-font-weight' => '400',
        '--tcdce-marker-color' => '#83e3aa',
        '--tcdce-marker-weight' => '1em',
        '--tcdce-marker-animation' => 'none',
      )
    ),
    'preset03' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 3 ),
      'style' => array(
        '--tcdce-marker-font-weight' => '400',
        '--tcdce-marker-color' => '#FF4000',
        '--tcdce-marker-weight' => '1em',
        '--tcdce-marker-animation' => 'none',
      )
    ),
    'preset04' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 4 ),
      'style' => array(
        '--tcdce-marker-font-weight' => '400',
        '--tcdce-marker-color' => '#fff799',
        '--tcdce-marker-weight' => '0.8em',
        '--tcdce-marker-animation' => 'none',
      )
    ),
    'preset05' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 5 ),
      'style' => array(
        '--tcdce-marker-font-weight' => '400',
        '--tcdce-marker-color' => '#97c0f3',
        '--tcdce-marker-weight' => '0.8em',
        '--tcdce-marker-animation' => 'none',
      )
    ),
    'preset06' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 6 ),
      'style' => array(
        '--tcdce-marker-font-weight' => '400',
        '--tcdce-marker-color' => '#ffbe8f',
        '--tcdce-marker-weight' => '0.8em',
        '--tcdce-marker-animation' => 'none',
      )
    ),
    'preset07' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 7 ),
      'style' => array(
        '--tcdce-marker-font-weight' => '400',
        '--tcdce-marker-color' => '#ffd9fd',
        '--tcdce-marker-weight' => '0.1em',
        '--tcdce-marker-animation' => 'none',
      )
    ),
    'preset08' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 8 ),
      'style' => array(
        '--tcdce-marker-font-weight' => '400',
        '--tcdce-marker-color' => '#fffad9',
        '--tcdce-marker-weight' => '0.1em',
        '--tcdce-marker-animation' => 'none',
      )
    ),
    'preset09' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 9 ),
      'style' => array(
        '--tcdce-marker-font-weight' => '400',
        '--tcdce-marker-color' => '#d9fffa',
        '--tcdce-marker-weight' => '0.1em',
        '--tcdce-marker-animation' => 'none',
      )
    ),

  ) );

} );


// 専用フィールド
add_action( 'tcdce_qt_fields_repeater_options_marker', function( $instance, $base_name, $base_value ){

  $instance->fields( __( 'Quicktag setting', 'tcd-classic-editor' ), array(
    array(
      'title' => __( 'Registered name', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->text( $base_name, $base_value, 'label', 'js-tcdce-repeater-label js-tcdce-empty-validation' )
    ),
    array(
      'title' => __( 'Class name', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->text( $base_name, $base_value, 'class', 'js-tcdce-empty-validation' )
    ),
  ) );

}, 10, 3 );


// 専用フィールド（プレビュー用）
add_action( 'tcdce_qt_fields_repeater_preview_options_marker', function( $instance, $name, $value ) {

  $item_type = 'marker';
  $style_name = $name . '[style]';
  $style_value = $value['style'];

  // text
  $instance->fields( __( 'Preview', 'tcd-classic-editor' ), array(
    array(
      'title' => __( 'Design Preset', 'tcd-classic-editor' ),
      'field' => $instance->preset( $name . '[preset]', $value['preset'], $item_type ),
    ),
    array(
      'title' => __( 'Font weight', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->radio( $style_name, $style_value, '--tcdce-marker-font-weight', array(
        '400' => TCDCE_ICONS['thick'],
        '600' => TCDCE_ICONS['bold'],
      ) )
    ),
    array(
      'title' => __( 'Marker color', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->color( $style_name, $style_value, '--tcdce-marker-color' )
    ),
    array(
      'title' => __( 'Marker weight', 'tcd-classic-editor' ),
      'field' => $instance->radio( $style_name, $style_value, '--tcdce-marker-weight', array(
        '1em' => TCDCE_ICONS['marker_thin'],
        '0.8em' => TCDCE_ICONS['marker_normal'],
        '0.1em' => TCDCE_ICONS['marker_bold'],
      ) )
    ),
    array(
      'title' => __( 'Animation', 'tcd-classic-editor' ),
      'field' => $instance->radio( $style_name, $style_value, '--tcdce-marker-animation', array(
        'var(--tcdce-marker-animation-keyframes)' => 'ON',
        'none' => 'OFF'
      ) )
    ),
  ) );

  // submit
  $instance->submit();

}, 10, 3 );


// バリデーション
add_filter( 'tcdce_qt_validation_marker', function( $value ) {

  $new_value = array(
    'item' => 'marker',
    'show' => absint( $value['show'] ),
    'class' => sanitize_text_field( $value['class'] ),
    'label' => sanitize_text_field( $value['label'] ),
    'preset' => sanitize_text_field( $value['preset'] ),
    'style' => array(
      '--tcdce-marker-font-weight' => in_array( $value['style']['--tcdce-marker-font-weight'], array( '600', '400' ), true ) ? $value['style']['--tcdce-marker-font-weight'] : '400',
      '--tcdce-marker-color' => sanitize_hex_color( $value['style']['--tcdce-marker-color'] ),
      '--tcdce-marker-weight' => sanitize_text_field( $value['style']['--tcdce-marker-weight'] ),
      '--tcdce-marker-animation' => sanitize_text_field( $value['style']['--tcdce-marker-animation'] ),
    )
  );

  return $new_value;

});