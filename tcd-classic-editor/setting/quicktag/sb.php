<?php

/**
 * クイックタグ設定 Speech Bubble
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// ブロックエディタにバリエーション登録
add_filter( 'tcdce_block_register_sb', function( $value, $quicktag, $key ) {
  return array(
    'name' => 'core/html',
    'settings' => array(
      'name' => 'sb-' . $key,
      'title' => $quicktag['label'],
      /* translators: %s: quicktag label */
      'description' => sprintf( __( '%s is registered in the TCD Classic Editor.', 'tcd-classic-editor' ), __( 'Speech bubble', 'tcd-classic-editor' ) ),
      'category' => 'tcdce',
      'keywords' => array( 'tcdce', 'sb', 'hukidashi' ),
      'icon' => 'tcdce-sb',
      'attributes' => array(
        'content' => '[speech_bubble id="' . esc_attr( $key ) . '"]' . __( 'Enter text here', 'tcd-classic-editor' ) . '[/speech_bubble]',
      )
    )
  );
}, 10, 3 );

// エディタにクイックタグ登録
add_filter( 'tcdce_qt_register_sb', function( $value, $quicktag, $key ) {
  return array(
    'display' => $quicktag['label'],
    'tagStart' => '[speech_bubble id="' . esc_attr( $key ) . '"]',
    'tagEnd' => '[/speech_bubble]'
  );
}, 10, 3 );


// データセット
add_action( 'tcdce_qt_fields_set_properties', function( $instance ) {

  // ラベルをセット
  $instance->set_label( 'sb', __( 'Speech bubble', 'tcd-classic-editor' ) );

  // プレビュー情報をセット
  $instance->set_preview(
    'sb',
    '<div class="tcdce-preview--sb tcdce-sb js-tcdce-preview-target">
      <div class="tcdce-sb-user">
        <div class="tcdce-sb-user-image"></div>
        <span class="tcdce-sb-user-name js-tcdce-preview-option--text-target">' .
        __( 'User name', 'tcd-classic-editor' ) .
        '</span>
      </div>
      <div class="tcdce-sb-content">' .
        /* translators: %s: quicktag speech bubble label */
        sprintf( __( 'Sample %s', 'tcd-classic-editor' ), $instance->get_label( 'sb' ) ) .
      '</div>
    </div>'
  );

  // 初期値をセット
  $instance->set_default( 'sb', array(
    'item' => 'sb',
    'show' => 1,
    'class' => '',
    /* translators: %s: quicktag speech bubble label */
    'label' => sprintf( __( 'Custom %s', 'tcd-classic-editor' ), $instance->get_label( 'sb' ) ),
    'preset' => 'preset01',
    'user_name' => __( 'User name', 'tcd-classic-editor' ),
    'style' => array(
      '--tcdce-sb-font-size-pc' => 16,
      '--tcdce-sb-font-size-sp' => 14,
      '--tcdce-sb-font-weight' => '400',
      '--tcdce-sb-font-color' => '#000000',
      '--tcdce-sb-image-url' => '',
      '--tcdce-sb-preset-color--bg' => '',
      '--tcdce-sb-preset-color--border' => '',
      '--tcdce-sb-background' => 'transparent',
      '--tcdce-sb-border-color' => 'transparent',
      '--tcdce-sb-padding' => '0',
      '--tcdce-sb-direction' => 'row',
      '--tcdce-sb-triangle-before-offset' => '-10px',
      '--tcdce-sb-triangle-after-offset' => '-7px',
      '--tcdce-sb-triangle-path' => 'polygon(100% 0, 0 50%, 100% 100%)',
      '--tcdce-sb-margin-top-pc' => 40,
      '--tcdce-sb-margin-top-sp' => 20,
      '--tcdce-sb-margin-bottom-pc' => 40,
      '--tcdce-sb-margin-bottom-sp' => 20,
    )
  ) );

  // プリセットデータをセット
  $instance->set_preset( 'sb', array(

    'preset01' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 1 ),
      'style' => array(
        '--tcdce-sb-font-size-pc' => 16,
        '--tcdce-sb-font-size-sp' => 14,
        '--tcdce-sb-font-weight' => '400',
        '--tcdce-sb-font-color' => '#000000',
        '--tcdce-sb-image-url' => '',
        '--tcdce-sb-preset-color--bg' => '',
        '--tcdce-sb-preset-color--border' => '',
        '--tcdce-sb-background' => 'transparent',
        '--tcdce-sb-border-color' => 'transparent',
        '--tcdce-sb-padding' => '0',
        '--tcdce-sb-direction' => 'row',
        '--tcdce-sb-triangle-before-offset' => '-10px',
        '--tcdce-sb-triangle-after-offset' => '-7px',
        '--tcdce-sb-triangle-path' => 'polygon(100% 0, 0 50%, 100% 100%)',
      )
    ),
    'preset02' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 2 ),
      'style' => array(
        '--tcdce-sb-font-size-pc' => 16,
        '--tcdce-sb-font-size-sp' => 14,
        '--tcdce-sb-font-weight' => '400',
        '--tcdce-sb-font-color' => '#000000',
        '--tcdce-sb-image-url' => '',
        '--tcdce-sb-preset-color--bg' => '',
        '--tcdce-sb-preset-color--border' => '',
        '--tcdce-sb-background' => 'transparent',
        '--tcdce-sb-border-color' => 'transparent',
        '--tcdce-sb-padding' => '0',
        '--tcdce-sb-direction' => 'row-reverse',
        '--tcdce-sb-triangle-before-offset' => '100%',
        '--tcdce-sb-triangle-after-offset' => 'calc(100% - 3px)',
        '--tcdce-sb-triangle-path' => 'polygon(0 0, 0% 100%, 100% 50%)',
      )
    ),

    'preset03' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 3 ),
      'style' => array(
        '--tcdce-sb-font-size-pc' => 16,
        '--tcdce-sb-font-size-sp' => 14,
        '--tcdce-sb-font-weight' => '400',
        '--tcdce-sb-font-color' => '#ffffff',
        '--tcdce-sb-image-url' => '',
        '--tcdce-sb-preset-color--bg' => '#06C755',
        '--tcdce-sb-preset-color--border' => '',
        '--tcdce-sb-background' => 'var(--tcdce-sb-preset-color--bg)',
        '--tcdce-sb-border-color' => 'var(--tcdce-sb-preset-color--bg)',
        '--tcdce-sb-padding' => '1em 1.5em',
        '--tcdce-sb-direction' => 'row',
        '--tcdce-sb-triangle-before-offset' => '-10px',
        '--tcdce-sb-triangle-after-offset' => '-7px',
        '--tcdce-sb-triangle-path' => 'polygon(100% 0, 0 50%, 100% 100%)',
      )
    ),
    'preset04' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 4 ),
      'style' => array(
        '--tcdce-sb-font-size-pc' => 16,
        '--tcdce-sb-font-size-sp' => 14,
        '--tcdce-sb-font-weight' => '400',
        '--tcdce-sb-font-color' => '#ffffff',
        '--tcdce-sb-image-url' => '',
        '--tcdce-sb-preset-color--bg' => '#06C755',
        '--tcdce-sb-preset-color--border' => '',
        '--tcdce-sb-background' => 'var(--tcdce-sb-preset-color--bg)',
        '--tcdce-sb-border-color' => 'var(--tcdce-sb-preset-color--bg)',
        '--tcdce-sb-padding' => '1em 1.5em',
        '--tcdce-sb-direction' => 'row-reverse',
        '--tcdce-sb-triangle-before-offset' => '100%',
        '--tcdce-sb-triangle-after-offset' => 'calc(100% - 3px)',
        '--tcdce-sb-triangle-path' => 'polygon(0 0, 0% 100%, 100% 50%)',
      )
    ),

    'preset05' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 5 ),
      'style' => array(
        '--tcdce-sb-font-size-pc' => 16,
        '--tcdce-sb-font-size-sp' => 14,
        '--tcdce-sb-font-weight' => '400',
        '--tcdce-sb-font-color' => '#000000',
        '--tcdce-sb-image-url' => '',
        '--tcdce-sb-preset-color--bg' => '#ffffff',
        '--tcdce-sb-preset-color--border' => '#000000',
        '--tcdce-sb-background' => 'var(--tcdce-sb-preset-color--bg)',
        '--tcdce-sb-border-color' => 'var(--tcdce-sb-preset-color--border)',
        '--tcdce-sb-padding' => '1em 1.5em',
        '--tcdce-sb-direction' => 'row',
        '--tcdce-sb-triangle-before-offset' => '-10px',
        '--tcdce-sb-triangle-after-offset' => '-7px',
        '--tcdce-sb-triangle-path' => 'polygon(100% 0, 0 50%, 100% 100%)',
      )
    ),
    'preset06' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 6 ),
      'style' => array(
        '--tcdce-sb-font-size-pc' => 16,
        '--tcdce-sb-font-size-sp' => 14,
        '--tcdce-sb-font-weight' => '400',
        '--tcdce-sb-font-color' => '#000000',
        '--tcdce-sb-image-url' => '',
        '--tcdce-sb-preset-color--bg' => '#ffffff',
        '--tcdce-sb-preset-color--border' => '#000000',
        '--tcdce-sb-background' => 'var(--tcdce-sb-preset-color--bg)',
        '--tcdce-sb-border-color' => 'var(--tcdce-sb-preset-color--border)',
        '--tcdce-sb-padding' => '1em 1.5em',
        '--tcdce-sb-direction' => 'row-reverse',
        '--tcdce-sb-triangle-before-offset' => '100%',
        '--tcdce-sb-triangle-after-offset' => 'calc(100% - 3px)',
        '--tcdce-sb-triangle-path' => 'polygon(0 0, 0% 100%, 100% 50%)',
      )
    ),

  ) );

} );


// 専用フィールド
add_action( 'tcdce_qt_fields_repeater_options_sb', function( $instance, $base_name, $base_value, $key ){

  // class
  echo '<input type="hidden" name="' . esc_attr( $base_name ) . '[class]" value="' . esc_attr( 'tcdce-sb[data-key="' . $key . '"]' ) . '">';

  $instance->fields( __( 'Quicktag setting', 'tcd-classic-editor' ), array(
    array(
      'title' => __( 'Registered name', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->text( $base_name, $base_value, 'label', 'js-tcdce-repeater-label js-tcdce-empty-validation' )
    ),
    array(
      'title' => __( 'Shortcode', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->shortcode( '[speech_bubble id="' . $key . '"][/speech_bubble]' )
    ),
  ) );

}, 10, 4 );


// 専用フィールド（プレビュー用）
add_action( 'tcdce_qt_fields_repeater_preview_options_sb', function( $instance, $name, $value ) {

  $item_type = 'sb';
  $default = $instance->default[$item_type]['style'];
  $style_name = $name . '[style]';
  $style_value = $value['style'];

  // text
  $instance->fields( __( 'Preview', 'tcd-classic-editor' ), array(

    array(
      'title' => __( 'Design Preset', 'tcd-classic-editor' ),
      'field' => $instance->preset( $name . '[preset]', $value['preset'], $item_type ),
    ),
    array(
      'title' => __( 'Font size', 'tcd-classic-editor' ),
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-sb-font-size-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-sb-font-size-pc'],
        ),
        '--tcdce-sb-font-size-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-sb-font-size-sp'],
        ),
      ) ),
    ),
    array(
      'title' => __( 'Font weight', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->radio( $style_name, $style_value, '--tcdce-sb-font-weight', array(
        '400' => TCDCE_ICONS['thick'],
        '600' => TCDCE_ICONS['bold'],
      ) ),
    ),
    array(
      'title' => __( 'Font color', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->color( $style_name, $style_value, '--tcdce-sb-font-color' )
    ),
    array(
      'title' => __( 'User name', 'tcd-classic-editor' ),
      'field' => $instance->text( $name, $value, 'user_name', 'js-tcdce-preview-option js-tcdce-preview-target--text' )
    ),
    array(
      'title' => __( 'User image', 'tcd-classic-editor' ),
      'field' => $instance->image( $style_name, $style_value, '--tcdce-sb-image-url' )
    ),
    array(
      'title' => __( 'Background color', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->color( $style_name, $style_value, '--tcdce-sb-preset-color--bg' ),
      'class' => 'tcdce-sb-bg-color'
    ),
    array(
      'title' => __( 'Border color', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->color( $style_name, $style_value, '--tcdce-sb-preset-color--border' ),
      'class' => 'tcdce-sb-border-color'
    ),

  ) );


  // margin
  $instance->fields( __( 'Margin', 'tcd-classic-editor' ), array(
    array(
      'title' => __( 'Margin top', 'tcd-classic-editor' ),
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-sb-margin-top-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-sb-margin-top-pc'],
        ),
        '--tcdce-sb-margin-top-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-sb-margin-top-sp'],
        ),
      ) ),
    ),
    array(
      'title' => __( 'Margin bottom', 'tcd-classic-editor' ),
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-sb-margin-bottom-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-sb-margin-bottom-pc'],
        ),
        '--tcdce-sb-margin-bottom-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-sb-margin-bottom-sp'],
        ),
      ) ),
    ),
  ) );

  // その他プレビューで使用するもの
  $instance->hiddens( $style_name, $style_value, array(
    '--tcdce-sb-background',
    '--tcdce-sb-border-color',
    '--tcdce-sb-padding',
    '--tcdce-sb-direction',
    '--tcdce-sb-triangle-before-offset',
    '--tcdce-sb-triangle-after-offset',
    '--tcdce-sb-triangle-path',
  ) );

  // submit
  $instance->submit();

}, 10, 3 );


// バリデーション
add_filter( 'tcdce_qt_validation_sb', function( $value ) {

  $new_value = array(
    'item' => 'sb',
    'show' => absint( $value['show'] ),
    'class' => sanitize_text_field( $value['class'] ),
    'label' => sanitize_text_field( $value['label'] ),
    'preset' => sanitize_text_field( $value['preset'] ),
    'user_name' => sanitize_text_field( $value['user_name'] ),
    'style' => array(
      '--tcdce-sb-font-size-pc' => absint( $value['style']['--tcdce-sb-font-size-pc'] ),
      '--tcdce-sb-font-size-sp' => absint( $value['style']['--tcdce-sb-font-size-sp'] ),
      '--tcdce-sb-font-weight' => in_array( $value['style']['--tcdce-sb-font-weight'], array( '600', '400' ), true ) ? $value['style']['--tcdce-sb-font-weight'] : '400',
      '--tcdce-sb-font-color' => sanitize_hex_color( $value['style']['--tcdce-sb-font-color'] ),
      '--tcdce-sb-image-url' => $value['style']['--tcdce-sb-image-url'] ? absint( $value['style']['--tcdce-sb-image-url'] ) : '',
      '--tcdce-sb-preset-color--bg' => sanitize_hex_color( $value['style']['--tcdce-sb-preset-color--bg'] ),
      '--tcdce-sb-preset-color--border' => sanitize_hex_color( $value['style']['--tcdce-sb-preset-color--border'] ),
      '--tcdce-sb-background' => sanitize_text_field( $value['style']['--tcdce-sb-background'] ),
      '--tcdce-sb-border-color' => sanitize_text_field( $value['style']['--tcdce-sb-border-color'] ),
      '--tcdce-sb-padding' => sanitize_text_field( $value['style']['--tcdce-sb-padding'] ),
      '--tcdce-sb-direction' => sanitize_text_field( $value['style']['--tcdce-sb-direction'] ),
      '--tcdce-sb-triangle-before-offset' => sanitize_text_field( $value['style']['--tcdce-sb-triangle-before-offset'] ),
      '--tcdce-sb-triangle-after-offset' => sanitize_text_field( $value['style']['--tcdce-sb-triangle-after-offset'] ),
      '--tcdce-sb-triangle-path' => sanitize_text_field( $value['style']['--tcdce-sb-triangle-path'] ),
      '--tcdce-sb-margin-top-pc' => absint( $value['style']['--tcdce-sb-margin-top-pc'] ),
      '--tcdce-sb-margin-top-sp' => absint( $value['style']['--tcdce-sb-margin-top-sp'] ),
      '--tcdce-sb-margin-bottom-pc' => absint( $value['style']['--tcdce-sb-margin-bottom-pc'] ),
      '--tcdce-sb-margin-bottom-sp' => absint( $value['style']['--tcdce-sb-margin-bottom-sp'] ),
    )
  );

  return $new_value;

});