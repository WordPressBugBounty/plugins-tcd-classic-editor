<?php

/**
 * クイックタグ設定 cardlink
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// ブロックエディタにバリエーション登録
add_filter( 'tcdce_block_register_cardlink', function( $value, $quicktag, $key ) {
  return array(
    'name' => 'core/html',
    'settings' => array(
      'name' => 'cardlink-' . $key,
      'title' => $quicktag['label'],
      /* translators: %s: quicktag label */
      'description' => sprintf( __( '%s is registered in the TCD Classic Editor.', 'tcd-classic-editor' ), __( 'Cardlink', 'tcd-classic-editor' ) ),
      'category' => 'tcdce',
      'keywords' => array( 'tcdce', 'cardlink', 'clink' ),
      'icon' => 'tcdce-cardlink',
      'attributes' => array(
        'content' => '[clink url="' . __( 'Enter the article URL you want to display here', 'tcd-classic-editor' ) . '"]',
      )
    )
  );
}, 10, 3 );

// エディタにクイックタグ登録
add_filter( 'tcdce_qt_register_cardlink', function( $value, $quicktag ) {
  return array(
    'display' => $quicktag['label'],
    'tag' => '[clink url="' . __( 'Enter the article URL you want to display here', 'tcd-classic-editor' ) . '"]'
  );
}, 10, 2 );


// データセット
add_action( 'tcdce_qt_fields_set_properties', function( $instance ) {

  // ラベルをセット
  $instance->set_label( 'cardlink', __( 'Cardlink', 'tcd-classic-editor' ) );

  // プレビュー情報をセット
  $instance->set_preview( 'cardlink', null );

  // 初期値をセット
  $instance->set_default( 'cardlink', array(
    'item' => 'cardlink',
    'show' => 1,
    'label' => $instance->get_label( 'cardlink' ),
  ) );

  // プリセットなし

} );

// 専用フィールド
add_action( 'tcdce_qt_fields_repeater_options_cardlink', function( $instance, $base_name, $base_value ){

  $instance->fields( __( 'Quicktag setting', 'tcd-classic-editor' ), array(
    array(
      'title' => __( 'Registered name', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->text( $base_name, $base_value, 'label', 'js-tcdce-repeater-label js-tcdce-empty-validation' )
    ),
  ) );

  $instance->fields( __( 'List of available shortcodes', 'tcd-classic-editor' ), array(
    array(
      'title' => '',
      'col' => 2,
      'field' => $instance->shortcode( '[clink url=""]' )
    ),
    array(
      'title' => '',
      'col' => 2,
      'field' => '<div class="tcdce_sc_info">' . __( 'Basic shortcode to display card links by entering a URL', 'tcd-classic-editor' ) . '</div>'
    ),
    array(
      'title' => '',
      'col' => 2,
      'field' => $instance->shortcode( '[clink url="" hide-date="1"]' )
    ),
    array(
      'title' => '',
      'col' => 2,
      'field' => '<div class="tcdce_sc_info">' . __( 'Option to hide card link dates', 'tcd-classic-editor' ) . '</div>'
    ),
    array(
      'title' => '',
      'col' => 2,
      'field' => $instance->shortcode( '[clink url="" hide-modify-date="1"]' )
    ),
    array(
      'title' => '',
      'col' => 2,
      'field' => '<div class="tcdce_sc_info">' . __( 'Option to hide only the update date of card links', 'tcd-classic-editor' ) . '</div>'
    ),
  ) );

	// submit
	$instance->submit();

}, 10, 3 );


// バリデーション
add_filter( 'tcdce_qt_validation_cardlink', function( $value ) {

  $new_value = array(
    'item' => 'cardlink',
    'show' => absint( $value['show'] ),
    'label' => sanitize_text_field( $value['label'] ),
  );

  return $new_value;

});