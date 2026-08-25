<?php

/**
 * クイックタグ設定 Google Maps
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// ブロックエディタにバリエーション登録
add_filter( 'tcdce_block_register_gmap', function( $value, $quicktag, $key ) {
  return array(
    'name' => 'core/html',
    'settings' => array(
      'name' => 'gmap-' . $key,
      'title' => $quicktag['label'],
      /* translators: %s: quicktag label */
      'description' => sprintf( __( '%s is registered in the TCD Classic Editor.', 'tcd-classic-editor' ), __( 'Google Maps', 'tcd-classic-editor' ) ),
      'category' => 'tcdce',
      'keywords' => array( 'tcdce', 'googlemap', 'gmap' ),
      'icon' => 'tcdce-gmap',
      'attributes' => array(
        'content' => '[gmap address="' . __( 'Enter your address here', 'tcd-classic-editor' ) . '"]',
      )
    )
  );
}, 10, 3 );

// エディタにクイックタグ登録
add_filter( 'tcdce_qt_register_gmap', function( $value, $quicktag ) {
  return array(
    'display' => $quicktag['label'],
    'tag' => '[gmap address=""]'
  );
}, 10, 2 );


// データセット
add_action( 'tcdce_qt_fields_set_properties', function( $instance ) {

  // ラベルをセット
  $instance->set_label( 'gmap', __( 'Google Maps', 'tcd-classic-editor' ) );

  // プレビュー情報をセット
  $instance->set_preview( 'gmap', null );

  // 初期値をセット
  $instance->set_default( 'gmap', array(
    'item' => 'gmap',
    'show' => 1,
    'label' => $instance->get_label( 'gmap' ),
  ) );

  // プリセットなし

} );


// 専用フィールド
add_action( 'tcdce_qt_fields_repeater_options_gmap', function( $instance, $base_name, $base_value ){

  $instance->fields( __( 'Quicktag setting', 'tcd-classic-editor' ), array(
    array(
      'title' => '',
      'col' => 1,
      'field' => $instance->note(
        sprintf(
          /* translators: 1: opening anchor <a> tag, 2: closing </a> tag. */
          esc_html__( 'To use Google Maps, you must register an API key in %1$sGoogle Maps settings%2$s.', 'tcd-classic-editor' ),
          '<a href="' . esc_url( menu_page_url( 'tcd_classic_editor_gmap', false ) ) . '" target="_blank">',
          '</a>'
        )
      )
    ),
    array(
      'title' => __( 'Registered name', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->text( $base_name, $base_value, 'label', 'js-tcdce-repeater-label js-tcdce-empty-validation' )
    ),
    array(
      'title' => __( 'Shortcode', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->shortcode( '[gmap address=""]' )
    ),
  ) );

  // submit
  $instance->submit();

}, 10, 3 );


// バリデーション
add_filter( 'tcdce_qt_validation_gmap', function( $value ) {

  $new_value = array(
    'item' => 'gmap',
    'show' => absint( $value['show'] ),
    'label' => sanitize_text_field( $value['label'] ),
  );

  return $new_value;

});
