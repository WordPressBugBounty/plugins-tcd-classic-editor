<?php

/**
 * クイックタグ設定 button
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// ブロックエディタにバリエーション登録
add_filter( 'tcdce_block_register_button', function( $value, $quicktag, $key ) {
  return array(
    'name' => 'core/button',
    'settings' => array(
      'name' => 'button-' . $key,
      'title' => $quicktag['label'],
      'attributes' => array(
        'className' => $quicktag['class'],
      ),
      'scope' => ['transform']
    )
  );
}, 10, 3 );


// エディタにクイックタグ登録
add_filter( 'tcdce_qt_register_button', function( $value, $quicktag ) {
  return array(
    'display' => $quicktag['label'],
    'tag' => '<p class="tcdce-button-wrapper">' . "\n" . "\t" .
                '<a href="#" class="tcdce-button ' . esc_attr( $quicktag['class'] ) . '">' .
                  __( 'Button', 'tcd-classic-editor' ) .
                '</a>'. "\n" .
              '</p>'
  );
}, 10, 2 );


// データセット
add_action( 'tcdce_qt_fields_set_properties', function( $instance ) {

  // ラベルをセット
  $instance->set_label( 'button', __( 'Button', 'tcd-classic-editor' ) );

  // プレビュー情報をセット
  $instance->set_preview(
    'button',
    '<p style="text-align:center;">
      <span class="tcdce-preview--button tcdce-button js-tcdce-preview-target">' .
        /* translators: %s: quicktag button label */
        sprintf( __( 'Sample %s', 'tcd-classic-editor' ), $instance->get_label( 'button' ) ) .
      '</span>
    </p>'
  );

  // 初期値をセット
  $instance->set_default( 'button', array(
    'item' => 'button',
    'show' => 1,
    'class' => 'custom_button',
    /* translators: %s: quicktag button label */
    'label' => sprintf( __( 'Custom %s', 'tcd-classic-editor' ), $instance->get_label( 'button' ) ),
    'preset' => 'preset01',
    'style' => array(
      '--tcdce-button-font-size-pc' => 16,
      '--tcdce-button-font-size-sp' => 14,
      '--tcdce-button-font-weight' => '400',
      '--tcdce-button-shape' => 'var(--tcdce-button-shape--round)',
      '--tcdce-button-size-width-pc' => 270,
      '--tcdce-button-size-width-sp' => 220,
      '--tcdce-button-size-height-pc' => 60,
      '--tcdce-button-size-height-sp' => 50,
      '--tcdce-button-preset-color--a' => '#66D1F0',
      '--tcdce-button-preset-color--b' => '',
      '--tcdce-button-preset-color--gradation--a' => '',
      '--tcdce-button-preset-color--gradation--b' => '',
      '--tcdce-button-font-color' => '#ffffff',
      '--tcdce-button-font-color-hover' => '#ffffff',
      '--tcdce-button-background' => 'var(--tcdce-button-preset-color--a)',
      '--tcdce-button-background-hover' => 'var(--tcdce-button-preset-color--a)',
      '--tcdce-button-border' => 'none',
      '--tcdce-button-border-hover' => 'none',
      '--tcdce-button-transform' => 'none',
      '--tcdce-button-transform-hover' => 'none',
      '--tcdce-button-overlay' => '\'\'',
      '--tcdce-button-margin-top-pc' => 40,
      '--tcdce-button-margin-top-sp' => 20,
      '--tcdce-button-margin-bottom-pc' => 40,
      '--tcdce-button-margin-bottom-sp' => 20,
    )
  ) );

  // プリセットデータをセット
  $instance->set_preset( 'button', array(

    'preset01' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 1 ),
      'style' => array(
        '--tcdce-button-font-size-pc' => 16,
        '--tcdce-button-font-size-sp' => 14,
        '--tcdce-button-font-weight' => '400',
        '--tcdce-button-preset-color--a' => '#66D1F0',
        '--tcdce-button-preset-color--b' => '',
        '--tcdce-button-preset-color--gradation--a' => '',
        '--tcdce-button-preset-color--gradation--b' => '',
        '--tcdce-button-font-color' => '#ffffff',
        '--tcdce-button-font-color-hover' => '#ffffff',
        '--tcdce-button-background' => 'var(--tcdce-button-preset-color--a)',
        '--tcdce-button-background-hover' => 'var(--tcdce-button-preset-color--a)',
        '--tcdce-button-border' => 'none',
        '--tcdce-button-border-hover' => 'none',
        '--tcdce-button-overlay' => '\'\'',
        '--tcdce-button-transform' => 'none',
        '--tcdce-button-transform-hover' => 'none'
      )
    ),
    'preset02' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 2 ),
      'style' => array(
        '--tcdce-button-font-size-pc' => 16,
        '--tcdce-button-font-size-sp' => 14,
        '--tcdce-button-font-weight' => '400',
        '--tcdce-button-preset-color--a' => '#000000',
        '--tcdce-button-preset-color--b' => '#66D1F0',
        '--tcdce-button-preset-color--gradation--a' => '',
        '--tcdce-button-preset-color--gradation--b' => '',
        '--tcdce-button-font-color' => '#ffffff',
        '--tcdce-button-font-color-hover' => '#ffffff',
        '--tcdce-button-background' => 'var(--tcdce-button-preset-color--a)',
        '--tcdce-button-background-hover' => 'var(--tcdce-button-preset-color--b)',
        '--tcdce-button-border' => 'none',
        '--tcdce-button-border-hover' => 'none',
        '--tcdce-button-transform' => 'none',
        '--tcdce-button-transform-hover' => 'none',
        '--tcdce-button-overlay' => 'none',
      )
    ),
    'preset03' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 3 ),
      'style' => array(
        '--tcdce-button-font-size-pc' => 16,
        '--tcdce-button-font-size-sp' => 14,
        '--tcdce-button-font-weight' => '400',
        '--tcdce-button-preset-color--a' => '#E6E5E5',
        '--tcdce-button-preset-color--b' => '#000000',
        '--tcdce-button-preset-color--gradation--a' => '',
        '--tcdce-button-preset-color--gradation--b' => '',
        '--tcdce-button-font-color' => '#000000',
        '--tcdce-button-font-color-hover' => '#ffffff',
        '--tcdce-button-background' => 'var(--tcdce-button-preset-color--a)',
        '--tcdce-button-background-hover' => 'var(--tcdce-button-preset-color--b)',
        '--tcdce-button-border' => 'none',
        '--tcdce-button-border-hover' => 'none',
        '--tcdce-button-transform' => 'none',
        '--tcdce-button-transform-hover' => 'none',
        '--tcdce-button-overlay' => 'none',
      )
    ),
    'preset04' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 4 ),
      'style' => array(
        '--tcdce-button-font-size-pc' => 16,
        '--tcdce-button-font-size-sp' => 14,
        '--tcdce-button-font-weight' => '400',
        '--tcdce-button-preset-color--a' => '',
        '--tcdce-button-preset-color--b' => '',
        '--tcdce-button-preset-color--gradation--a' => '#F066F0',
        '--tcdce-button-preset-color--gradation--b' => '#2EDFFF',
        '--tcdce-button-font-color' => '#ffffff',
        '--tcdce-button-font-color-hover' => '#ffffff',
        '--tcdce-button-background' => 'linear-gradient(90deg, var(--tcdce-button-preset-color--gradation--a) 0%, var(--tcdce-button-preset-color--gradation--b) 100%)',
        '--tcdce-button-background-hover' => 'linear-gradient(90deg, var(--tcdce-button-preset-color--gradation--a) 0%, var(--tcdce-button-preset-color--gradation--b) 100%)',
        '--tcdce-button-border' => 'none',
        '--tcdce-button-border-hover' => 'none',
        '--tcdce-button-transform' => 'none',
        '--tcdce-button-transform-hover' => 'none',
        '--tcdce-button-overlay' => '\'\'',
      )
    ),
    'preset05' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 5 ),
      'style' => array(
        '--tcdce-button-font-size-pc' => 16,
        '--tcdce-button-font-size-sp' => 14,
        '--tcdce-button-font-weight' => '400',
        '--tcdce-button-preset-color--a' => '#000000',
        '--tcdce-button-preset-color--b' => '#AD8E47',
        '--tcdce-button-preset-color--gradation--a' => '',
        '--tcdce-button-preset-color--gradation--b' => '',
        '--tcdce-button-font-color' => 'var(--tcdce-button-preset-color--a)',
        '--tcdce-button-font-color-hover' => '#ffffff',
        '--tcdce-button-background' => 'transparent',
        '--tcdce-button-background-hover' => 'var(--tcdce-button-preset-color--b)',
        '--tcdce-button-border' => '1px solid var(--tcdce-button-preset-color--a)',
        '--tcdce-button-border-hover' => '1px solid var(--tcdce-button-preset-color--b)',
        '--tcdce-button-transform' => 'none',
        '--tcdce-button-transform-hover' => 'none',
        '--tcdce-button-overlay' => 'none',
      )
    ),
    'preset06' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 6 ),
      'style' => array(
        '--tcdce-button-font-size-pc' => 16,
        '--tcdce-button-font-size-sp' => 14,
        '--tcdce-button-font-weight' => '400',
        '--tcdce-button-preset-color--a' => '#000000',
        '--tcdce-button-preset-color--b' => '#AD8E47',
        '--tcdce-button-preset-color--gradation--a' => '',
        '--tcdce-button-preset-color--gradation--b' => '',
        '--tcdce-button-font-color' => 'var(--tcdce-button-preset-color--a)',
        '--tcdce-button-font-color-hover' => '#ffffff',
        '--tcdce-button-background' => 'var(--tcdce-button-preset-color--b)',
        '--tcdce-button-background-hover' => 'var(--tcdce-button-preset-color--b)',
        '--tcdce-button-border' => '1px solid var(--tcdce-button-preset-color--a)',
        '--tcdce-button-border-hover' => '1px solid var(--tcdce-button-preset-color--b)',
        '--tcdce-button-transform' => 'translateX(-100%)',
        '--tcdce-button-transform-hover' => 'translateX(0%)',
        '--tcdce-button-overlay' => 'none',
      )
    ),
    'preset07' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 7 ),
      'style' => array(
        '--tcdce-button-font-size-pc' => 16,
        '--tcdce-button-font-size-sp' => 14,
        '--tcdce-button-font-weight' => '400',
        '--tcdce-button-preset-color--a' => '#000000',
        '--tcdce-button-preset-color--b' => '#AD8E47',
        '--tcdce-button-preset-color--gradation--a' => '',
        '--tcdce-button-preset-color--gradation--b' => '',
        '--tcdce-button-font-color' => 'var(--tcdce-button-preset-color--a)',
        '--tcdce-button-font-color-hover' => '#ffffff',
        '--tcdce-button-background' => 'var(--tcdce-button-preset-color--b)',
        '--tcdce-button-background-hover' => 'var(--tcdce-button-preset-color--b)',
        '--tcdce-button-border' => '1px solid var(--tcdce-button-preset-color--a)',
        '--tcdce-button-border-hover' => '1px solid var(--tcdce-button-preset-color--b)',
        '--tcdce-button-transform' => 'skewX(45deg) scale(1.2) translateX(-100%)',
        '--tcdce-button-transform-hover' => 'skewX(45deg) scale(1.2) translateX(0%)',
        '--tcdce-button-overlay' => 'none',
      )
    ),

  ) );

} );


// 専用フィールド
add_action( 'tcdce_qt_fields_repeater_options_button', function( $instance, $base_name, $base_value ){

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
add_action( 'tcdce_qt_fields_repeater_preview_options_button', function( $instance, $name, $value ) {

  $item_type = 'button';
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
        '--tcdce-button-font-size-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-button-font-size-pc'],
        ),
        '--tcdce-button-font-size-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-button-font-size-sp'],
        ),
      ) ),
    ),
    array(
      'title' => __( 'Font weight', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->radio( $style_name, $style_value, '--tcdce-button-font-weight', array(
        '400' => TCDCE_ICONS['thick'],
        '600' => TCDCE_ICONS['bold'],
      ) ),
    ),
    array(
      'title' => __( 'Shape', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->radio( $style_name, $style_value, '--tcdce-button-shape', array(
        'var(--tcdce-button-shape--round)' => '<span style="display: block;border: 1px solid;border-radius:12px;width: 20px;height: 12px;"></span>',
        'var(--tcdce-button-shape--square)' => '<span style="display: block;border: 1px solid;width: 20px;height: 12px;"></span>',
      ) )
    ),
    array(
      'title' => __( 'Button size (pc)', 'tcd-classic-editor' ),
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-button-size-width-pc' => array(
          'icon' => 'w',
          'default' => $default['--tcdce-button-size-width-pc'],
        ),
        '--tcdce-button-size-height-pc' => array(
          'icon' => 'h',
          'default' => $default['--tcdce-button-size-height-pc'],
        ),
      ) )
    ),
    array(
      'title' => __( 'Button size (mobile)', 'tcd-classic-editor' ),
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-button-size-width-sp' => array(
          'icon' => 'w',
          'default' => $default['--tcdce-button-size-width-sp'],
        ),
        '--tcdce-button-size-height-sp' => array(
          'icon' => 'h',
          'default' => $default['--tcdce-button-size-height-sp'],
        ),
      ) )
    ),
    array(
      'title' => __( 'Button color', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->color( $style_name, $style_value, '--tcdce-button-preset-color--a' ),
      'class' => 'tcdce-button-color'
    ),
    array(
      'title' => __( 'Button hover color', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->color( $style_name, $style_value, '--tcdce-button-preset-color--b' ),
      'class' => 'tcdce-button-hover-color'
    ),
    array(
      'title' => __( 'Gradation 1', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->color( $style_name, $style_value, '--tcdce-button-preset-color--gradation--a' ),
      'class' => 'tcdce-button-gradation1'
    ),
    array(
      'title' => __( 'Gradation 2', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->color( $style_name, $style_value, '--tcdce-button-preset-color--gradation--b' ),
      'class' => 'tcdce-button-gradation2'
    ),

  ) );

  // margin
  $instance->fields( __( 'Margin', 'tcd-classic-editor' ), array(
    array(
      'title' => __( 'Margin top', 'tcd-classic-editor' ),
      'col' => 1,
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-button-margin-top-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-button-margin-top-pc'],
        ),
        '--tcdce-button-margin-top-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-button-margin-top-sp'],
        ),
      ) ),
    ),
    array(
      'title' => __( 'Margin bottom', 'tcd-classic-editor' ),
      'col' => 1,
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-button-margin-bottom-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-button-margin-bottom-pc'],
        ),
        '--tcdce-button-margin-bottom-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-button-margin-bottom-sp'],
        ),
      ) ),
    ),
  ) );

  // その他
  $instance->hiddens( $style_name, $style_value, array(
    '--tcdce-button-font-color',
    '--tcdce-button-font-color-hover',
    '--tcdce-button-background',
    '--tcdce-button-background-hover',
    '--tcdce-button-border',
    '--tcdce-button-border-hover',
    '--tcdce-button-overlay',
    '--tcdce-button-transform',
    '--tcdce-button-transform-hover'
  ) );

  // submit
  $instance->submit();

}, 10, 3 );


// バリデーション
add_filter( 'tcdce_qt_validation_button', function( $value ) {

  $new_value = array(
    'item' => 'button',
    'show' => absint( $value['show'] ),
    'class' => sanitize_text_field( $value['class'] ),
    'label' => sanitize_text_field( $value['label'] ),
    'preset' => sanitize_text_field( $value['preset'] ),
    'style' => array(
      '--tcdce-button-font-size-pc' => absint( $value['style']['--tcdce-button-font-size-pc'] ),
      '--tcdce-button-font-size-sp' => absint( $value['style']['--tcdce-button-font-size-sp'] ),
      '--tcdce-button-font-weight' => in_array( $value['style']['--tcdce-button-font-weight'], array( '600', '400' ), true ) ? $value['style']['--tcdce-button-font-weight'] : '400',
      '--tcdce-button-shape' => in_array( $value['style']['--tcdce-button-shape'], array( 'var(--tcdce-button-shape--round)', 'var(--tcdce-button-shape--square)' ), true ) ? $value['style']['--tcdce-button-shape'] : 'var(--tcdce-button-shape--round)',
      '--tcdce-button-size-width-pc' => absint( $value['style']['--tcdce-button-size-width-pc'] ),
      '--tcdce-button-size-width-sp' => absint( $value['style']['--tcdce-button-size-width-sp'] ),
      '--tcdce-button-size-height-pc' => absint( $value['style']['--tcdce-button-size-height-pc'] ),
      '--tcdce-button-size-height-sp' => absint( $value['style']['--tcdce-button-size-height-sp'] ),
      '--tcdce-button-preset-color--a' => sanitize_hex_color( $value['style']['--tcdce-button-preset-color--a'] ),
      '--tcdce-button-preset-color--b' => sanitize_hex_color( $value['style']['--tcdce-button-preset-color--b'] ),
      '--tcdce-button-preset-color--gradation--a' => sanitize_hex_color( $value['style']['--tcdce-button-preset-color--gradation--a'] ),
      '--tcdce-button-preset-color--gradation--b' => sanitize_hex_color( $value['style']['--tcdce-button-preset-color--gradation--b'] ),
      '--tcdce-button-font-color' => sanitize_text_field( $value['style']['--tcdce-button-font-color'] ),
      '--tcdce-button-font-color-hover' => sanitize_hex_color( $value['style']['--tcdce-button-font-color-hover'] ),
      '--tcdce-button-background' => sanitize_text_field( $value['style']['--tcdce-button-background'] ),
      '--tcdce-button-background-hover' => sanitize_text_field( $value['style']['--tcdce-button-background-hover'] ),
      '--tcdce-button-border' => sanitize_text_field( $value['style']['--tcdce-button-border'] ),
      '--tcdce-button-border-hover' => sanitize_text_field( $value['style']['--tcdce-button-border-hover'] ),
      '--tcdce-button-transform' => sanitize_text_field( $value['style']['--tcdce-button-transform'] ),
      '--tcdce-button-transform-hover' => sanitize_text_field( $value['style']['--tcdce-button-transform-hover'] ),
      '--tcdce-button-overlay' => sanitize_text_field( $value['style']['--tcdce-button-overlay'] ),
      '--tcdce-button-margin-top-pc' => absint( $value['style']['--tcdce-button-margin-top-pc'] ),
      '--tcdce-button-margin-top-sp' => absint( $value['style']['--tcdce-button-margin-top-sp'] ),
      '--tcdce-button-margin-bottom-pc' => absint( $value['style']['--tcdce-button-margin-bottom-pc'] ),
      '--tcdce-button-margin-bottom-sp' => absint( $value['style']['--tcdce-button-margin-bottom-sp'] ),
    )
  );

  return $new_value;

});