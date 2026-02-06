<?php

/**
 * クイックタグ設定 ol
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// ブロックエディタにバリエーション登録
add_filter( 'tcdce_block_register_ol', function( $value, $quicktag, $key ) {
  return array(
    'name' => 'core/list',
    'settings' => array(
      'name' => 'ol-' . $key,
      'title' => $quicktag['label'],
      /* translators: %s: quicktag label */
      'description' => sprintf( __( '%s is registered in the TCD Classic Editor.', 'tcd-classic-editor' ), __( 'Ordered list', 'tcd-classic-editor' ) ),
      'category' => 'tcdce',
      'keywords'=> array( 'tcdce', 'ol', 'list' ),
      'icon' => 'tcdce-ol',
      'attributes' => array(
        'ordered' => true,
        'className' => $quicktag['class'],
      )
    )
  );
}, 10, 3 );


// エディタにクイックタグ登録
add_filter( 'tcdce_qt_register_ol', function( $value, $quicktag ) {
  return array(
    'display' => $quicktag['label'],
    'tag' => '<ol class="' . esc_attr( $quicktag['class'] ) . '">' . "\n" .
              str_repeat( "\t" . '<li>' . __( 'Ordered list', 'tcd-classic-editor' ) . '</li>' . "\n", 3 ) .
              '</ol>',
  );
}, 10, 2 );


// データセット
add_action( 'tcdce_qt_fields_set_properties', function( $instance ) {

  // ラベルをセット
  $instance->set_label( 'ol', __( 'Ordered list', 'tcd-classic-editor' ) );

  // プレビュー情報をセット
  $instance->set_preview(
    'ol',
    '<ol class="tcdce-preview--ol js-tcdce-preview-target">' .
    /* translators: %s: quicktag ol label */
    str_repeat( '<li>' . sprintf( __( 'Sample %s', 'tcd-classic-editor' ), $instance->get_label( 'ol' ) ) . '</li>', 3 ) .
    '</ol>',
  );

  // 初期値をセット
  $default_preset_style = array(
    /**
     * ベース
     */
    '--tcdce-ol-font-size-pc' => 16,
    '--tcdce-ol-font-size-sp' => 16,
    '--tcdce-ol-font-weight' => 'var(--tcdce-opt-font-weight-normal)',
    '--tcdce-ol-font-color' => '#000000',
    /**
     * 背景色
     */
    '--tcdce-ol-background' => 'initial',
    '--tcdce-ol-background-color' => '#f6f6f6',
    // '--tcdce-ol-background-gradation1' => 'スタイルシートで定義',
    // '--tcdce-ol-background-gradation2' => 'スタイルシートで定義',
    '--tcdce-ol-background-gradation-color1' => '#fff1eb',
    '--tcdce-ol-background-gradation-color2' => '#ace0f9',
    /**
     * 枠線
     */
    '--tcdce-ol-border-style' => 'hidden',
    '--tcdce-ol-border-width' => 2,
    '--tcdce-ol-border-color' => '#000000',
    /**
     * カウンター
     */
    '--tcdce-ol-list-style' => 'decimal',
    '--tcdce-ol-counter-type' => 'none',
    '--tcdce-ol-counter-offset' => '1em',
    '--tcdce-ol-counter-weight' => 'var(--tcdce-opt-font-weight-normal)',
    '--tcdce-ol-counter-size' => 'scale(1)',
    '--tcdce-ol-counter-color' => '#000000',
    '--tcdce-ol-counter-background' => 'transparent',
    '--tcdce-ol-counter-background-color' => '#000000',
    /**
     * パディング
     */
    '--tcdce-ol-padding-pc' => 'var(--tcdce-ol-padding-custom-pc)', // プリセット依存（ユーザー操作不可）
    '--tcdce-ol-padding-sp' => 'var(--tcdce-ol-padding-custom-sp)', // プリセット依存（ユーザー操作不可）
    '--tcdce-ol-padding-custom-pc' => 0,
    '--tcdce-ol-padding-custom-sp' => 0
  );

  // デフォルトマージン
  $default_margin_style = array(
    /**
     * マージン
     */
    '--tcdce-ol-margin-top-pc' => 40,
    '--tcdce-ol-margin-top-sp' => 20,
    '--tcdce-ol-margin-bottom-pc' => 40,
    '--tcdce-ol-margin-bottom-sp' => 20,
  );

  $instance->set_default( 'ol', array(
    'item' => 'ol',
    'show' => 1,
    'class' => 'custom_ol',
    /* translators: %s: quicktag ol label */
    'label' => sprintf( __( 'Custom %s', 'tcd-classic-editor' ), $instance->get_label( 'ol' ) ),
    'preset' => 'preset01',
    'style' => $default_preset_style + $default_margin_style
  ) );

  // プリセットデータをセット
  $instance->set_preset( 'ol', array(

    /**
     * シンプルな番号リスト
     */
    'preset01' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 1 ),
      'style' => $default_preset_style
    ),
    /**
     * 背景色付き番号リスト
     */
    'preset02' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 2 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-ol-background' => 'var(--tcdce-ol-background-color)',
          '--tcdce-ol-padding-pc' => '1.5em',
          '--tcdce-ol-padding-sp' => '1em',
        ),
        $default_preset_style
      )
    ),
    /**
     * 0埋め番号リスト
     */
    'preset03' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 3 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-ol-list-style' => 'none',
          '--tcdce-ol-counter-type' => 'decimal-leading-zero',
          '--tcdce-ol-counter-offset' => '2em',
          '--tcdce-ol-counter-weight' => 'var(--tcdce-opt-font-weight-bold)',
          '--tcdce-ol-counter-size' => 'scale(1.2)',
          '--tcdce-ol-counter-color' => '#0091d7',
        ),
        $default_preset_style
      )
    ),
    /**
     * 0埋め背景あり番号リスト
     */
    'preset04' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 4 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-ol-border-style' => 'dashed',
          '--tcdce-ol-border-color' => '#008c69',
          '--tcdce-ol-list-style' => 'none',
          '--tcdce-ol-counter-type' => 'decimal-leading-zero',
          '--tcdce-ol-counter-offset' => '2em',
          '--tcdce-ol-counter-weight' => 'var(--tcdce-opt-font-weight-bold)',
          '--tcdce-ol-counter-size' => 'scale(1.2)',
          '--tcdce-ol-counter-color' => '#008c69',
          '--tcdce-ol-padding-pc' => '1.5em',
          '--tcdce-ol-padding-sp' => '1em',
        ),
        $default_preset_style
      )
    ),
    /**
     * 丸番号リスト
     */
    'preset05' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 5 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-ol-list-style' => 'none',
          '--tcdce-ol-counter-type' => 'decimal',
          '--tcdce-ol-counter-offset' => '2em',
          '--tcdce-ol-counter-color' => '#ffffff',
          '--tcdce-ol-counter-background' => 'var(--tcdce-ol-counter-background-color)',
          '--tcdce-ol-counter-background-color' => '#FF4000',
        ),
        $default_preset_style
      )
    ),
    /**
     * 丸番号リスト
     */
    'preset06' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 6 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-ol-border-style' => 'solid',
          '--tcdce-ol-border-color' => '#673202',
          '--tcdce-ol-border-width' => 3,
          '--tcdce-ol-list-style' => 'none',
          '--tcdce-ol-counter-type' => 'decimal',
          '--tcdce-ol-counter-offset' => '2em',
          '--tcdce-ol-counter-color' => '#ffffff',
          '--tcdce-ol-counter-background' => 'var(--tcdce-ol-counter-background-color)',
          '--tcdce-ol-counter-background-color' => '#673202',
          '--tcdce-ol-padding-pc' => '1.5em',
          '--tcdce-ol-padding-sp' => '1em',
        ),
        $default_preset_style
      )
    ),
    /**
     * カスタム（デフォルト番号）
     */
    'custom01' => array(
      'label' => __( 'Custom (default number)', 'tcd-classic-editor' ),
      'style' => $default_preset_style
    ),
    /**
     * カスタム（0埋め番号）
     */
    'custom02' => array(
      'label' => __( 'Custom (leading zero)', 'tcd-classic-editor' ),
      'style' => wp_parse_args(
        array(
          '--tcdce-ol-list-style' => 'none',
          '--tcdce-ol-counter-type' => 'decimal-leading-zero',
          '--tcdce-ol-counter-offset' => '2em',
          '--tcdce-ol-counter-weight' => 'var(--tcdce-opt-font-weight-bold)',
          '--tcdce-ol-counter-size' => 'scale(1.2)',
          '--tcdce-ol-counter-color' => '#444444',
        ),
        $default_preset_style
      )
    ),
    /**
     * カスタム（背景付き番号）
     */
    'custom03' => array(
      'label' => __( 'Custom (number with background)', 'tcd-classic-editor' ),
      'style' => wp_parse_args(
        array(
          '--tcdce-ol-list-style' => 'none',
          '--tcdce-ol-counter-type' => 'decimal',
          '--tcdce-ol-counter-offset' => '2em',
          '--tcdce-ol-counter-color' => '#ffffff',
          '--tcdce-ol-counter-background' => 'var(--tcdce-ol-counter-background-color)',
          '--tcdce-ol-counter-background-color' => '#000000',
        ),
        $default_preset_style
      )
    ),


  ) );

} );


// 専用フィールド
add_action( 'tcdce_qt_fields_repeater_options_ol', function( $instance, $base_name, $base_value ){

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
add_action( 'tcdce_qt_fields_repeater_preview_options_ol', function( $instance, $name, $value ) {

  $item_type = 'ol';
  $default = $instance->default[$item_type]['style'];
  $style_name = $name . '[style]';
  $style_value = $value['style'];

  /**
   * ベース
   */
  $instance->fields( __( 'Preview', 'tcd-classic-editor' ), array(
    array(
      'title' => __( 'Design Preset', 'tcd-classic-editor' ),
      'field' => $instance->preset( $name . '[preset]', $value['preset'], $item_type ),
    ),
    array(
      'title' => __( 'Font size', 'tcd-classic-editor' ),
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-ol-font-size-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-ol-font-size-pc'],
        ),
        '--tcdce-ol-font-size-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-ol-font-size-sp'],
        ),
      ) ),
    ),
    array(
      'title' => __( 'Font weight', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->radio( $style_name, $style_value, '--tcdce-ol-font-weight', array(
        'var(--tcdce-opt-font-weight-normal)' => TCDCE_ICONS['thick'],
        'var(--tcdce-opt-font-weight-bold)' => TCDCE_ICONS['bold'],
      ) ),
    ),
    array(
      'title' => __( 'Font color', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->color( $style_name, $style_value, '--tcdce-ol-font-color' )
    )
  ) );


  /**
   * 背景オプション
   */
  $instance->fields(
    __( 'Background', 'tcd-classic-editor' ),
    array(
      array(
        'title' => __( 'Background type', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ol-background', array(
          'initial' => __( 'None', 'tcd-classic-editor' ),
          'var(--tcdce-ol-background-color)' => __( 'A single color', 'tcd-classic-editor' ),
          'var(--tcdce-ol-background-gradation1)' => __( 'Gradation (horizontal)', 'tcd-classic-editor' ),
          'var(--tcdce-ol-background-gradation2)' => __( 'Gradation (vertical)', 'tcd-classic-editor' ),
        ) ),
        'class' => 'tcdce-ol-bg-type'
      ),
      array(
        'title' => __( 'Background color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-ol-background-color' ),
        'class' => 'tcdce-ol-bg-color'
      ),
      array(
        'title' => __( 'Gradation 1', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-ol-background-gradation-color1' ),
        'class' => 'tcdce-ol-bg-g'
      ),
      array(
        'title' => __( 'Gradation 2', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-ol-background-gradation-color2' ),
        'class' => 'tcdce-ol-bg-g'
      )
    ),
    'tcdce-ol-bg',
    'tcdce-ol-bg'
  );


  /**
   * 枠線
   */
  $instance->fields(
    __( 'Border', 'tcd-classic-editor' ),
    array(
      // 枠線のスタイル
      array(
        'title' => __( 'Border style', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ol-border-style', array(
          'hidden' => __( 'None', 'tcd-classic-editor' ),
          'solid' => __( 'Solid', 'tcd-classic-editor' ),
          'dotted' => __( 'Dotted line', 'tcd-classic-editor' ),
          'dashed' => __( 'Dashed line', 'tcd-classic-editor' ),
        ) ),
        'class' => 'tcdce-ol-border-style'
      ),
      // 枠線の色
      array(
        'title' => __( 'Border color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-ol-border-color' ),
      ),
      array(
        'title' => __( 'Border width', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->number( $style_name, $style_value, array(
          '--tcdce-ol-border-width' => array(
            'icon' => '',
            'default' => 0,
          ),
        ) ),
      ),
    ),
    'tcdce-ol-border',
    'tcdce-ol-border'
  );

  /**
   * カウンター
   */
  $instance->fields(
    __( 'Marker', 'tcd-classic-editor' ),
    array(
      // リストスタイル
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ol-list-style', array(
          'none' => 'list-style:none;',
          'decimal' => 'list-style:decimal;'
        ) ),
        'class' => ''
      ),
      // カウンタータイプ
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ol-counter-type', array(
          'none' => 'content:none;',
          'decimal-leading-zero' => 'content:decimal-leading-zero;',
          'decimal' => 'content:decimal;',
        ) ),
        'class' => 'tcdce-ol-counter-type'
      ),
      // カウンターオフセット
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ol-counter-offset', array(
          '1em' => 'offset:1em;',
          '1.8em' => 'offset:1.8em;',
          '2em' => 'offset:2em;',
        ) ),
        'class' => ''
      ),
      // カウンター太さ
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ol-counter-weight', array(
          'var(--tcdce-opt-font-weight-normal)' => 'weight:normal;',
          'var(--tcdce-opt-font-weight-bold)' => 'weight:bold;',
        ) ),
      ),
      // カウンターサイズ
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ol-counter-size', array(
          'scale(1)' => 'scale(1)',
          'scale(1.2)' => 'scale(1.2)',
        ) ),
      ),
      // カウンター背景
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ol-counter-background', array(
          'transparent' => 'transparent',
          'var(--tcdce-ol-counter-background-color)' => 'var(--tcdce-ol-counter-background-color)',
        ) ),
      ),
      // カウンター文字色
      array(
        'title' => __( 'Font color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-ol-counter-color' ),
        'class' => 'tcdce-ol-counter-color'
      ),
      // カウンター背景色
      array(
        'title' => __( 'Background color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-ol-counter-background-color' ),
        'class' => 'tcdce-ol-counter-bg-color'
      ),

    ),
    'tcdce-ol-counter',
    'tcdce-ol-counter'
  );


  /**
   * パディング
   */
  $instance->fields(
    __( 'Padding', 'tcd-classic-editor' ),
    array(
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ol-padding-pc', array(
          '1.5em' => '1.5em',
          'var(--tcdce-ol-padding-custom-pc)' => 'var(--tcdce-ol-padding-custom-pc)'
        ) )
      ),
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ol-padding-sp', array(
          '1em' => '1em',
          'var(--tcdce-ol-padding-custom-sp)' => 'var(--tcdce-ol-padding-custom-sp)'
        ) )
      ),
      array(
        'title' => __( 'Padding (top/bottom/left/right)', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->number( $style_name, $style_value, array(
          '--tcdce-ol-padding-custom-pc' => array(
            'icon' => TCDCE_ICONS['pc'],
            'default' => 0,
          ),
          '--tcdce-ol-padding-custom-sp' => array(
            'icon' => TCDCE_ICONS['sp'],
            'default' => 0,
          ),
        ) ),
      ),
    ),
    'tcdce-ol-padding',
    'tcdce-ol-padding',
  );


  /**
   * マージン
   */
  $instance->fields( __( 'Margin', 'tcd-classic-editor' ), array(
    array(
      'title' => __( 'Margin top', 'tcd-classic-editor' ),
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-ol-margin-top-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-ol-margin-top-pc'],
        ),
        '--tcdce-ol-margin-top-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-ol-margin-top-sp'],
        ),
      ) ),
    ),
    array(
      'title' => __( 'Margin bottom', 'tcd-classic-editor' ),
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-ol-margin-bottom-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-ol-margin-bottom-pc'],
        ),
        '--tcdce-ol-margin-bottom-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-ol-margin-bottom-sp'],
        ),
      ) ),
    ),
  ) );

  // submit
  $instance->submit();

}, 10, 3 );



// バリデーション
add_filter( 'tcdce_qt_validation_ol', function( $value ) {

  $new_value = array(
    'item' => 'ol',
    'show' => absint( $value['show'] ),
    'class' => sanitize_text_field( $value['class'] ),
    'label' => sanitize_text_field( $value['label'] ),
    'preset' => sanitize_text_field( $value['preset'] ),
    'style' => array(
      /**
       * ベース
       */
      '--tcdce-ol-font-size-pc' => absint( $value['style']['--tcdce-ol-font-size-pc'] ),
      '--tcdce-ol-font-size-sp' => absint( $value['style']['--tcdce-ol-font-size-sp'] ),
      '--tcdce-ol-font-color' => sanitize_hex_color( $value['style']['--tcdce-ol-font-color'] ),
      '--tcdce-ol-font-weight' => in_array( $value['style']['--tcdce-ol-font-weight'], array( 'var(--tcdce-opt-font-weight-normal)', 'var(--tcdce-opt-font-weight-bold)' ), true ) ? $value['style']['--tcdce-ol-font-weight'] : 'var(--tcdce-opt-font-weight-normal)',
      /**
       * 背景
       */
      '--tcdce-ol-background' => in_array( $value['style']['--tcdce-ol-background'], array( 'initial', 'var(--tcdce-ol-background-color)', 'var(--tcdce-ol-background-gradation1)', 'var(--tcdce-ol-background-gradation2)' ), true ) ? $value['style']['--tcdce-ol-background'] : 'initial',
      '--tcdce-ol-background-color' => sanitize_hex_color( $value['style']['--tcdce-ol-background-color'] ),
      '--tcdce-ol-background-gradation-color1' => sanitize_hex_color( $value['style']['--tcdce-ol-background-gradation-color1'] ),
      '--tcdce-ol-background-gradation-color2' => sanitize_hex_color( $value['style']['--tcdce-ol-background-gradation-color2'] ),
      /**
       * 枠線
       */
      '--tcdce-ol-border-style' => in_array( $value['style']['--tcdce-ol-border-style'], array( 'hidden', 'solid', 'dotted', 'dashed' ), true ) ? $value['style']['--tcdce-ol-border-style'] : 'hidden',
      '--tcdce-ol-border-width' => absint( $value['style']['--tcdce-ol-border-width'] ),
      '--tcdce-ol-border-color' => sanitize_hex_color( $value['style']['--tcdce-ol-border-color'] ),
      /**
       * カウンター
       */
      '--tcdce-ol-list-style' => in_array( $value['style']['--tcdce-ol-list-style'], array( 'decimal', 'none' ), true ) ? $value['style']['--tcdce-ol-list-style'] : 'decimal',
      '--tcdce-ol-counter-type' => in_array( $value['style']['--tcdce-ol-counter-type'], array( 'none', 'decimal', 'decimal-leading-zero' ), true ) ? $value['style']['--tcdce-ol-counter-type'] : 'none',
      '--tcdce-ol-counter-offset' => sanitize_text_field( $value['style']['--tcdce-ol-counter-offset'] ),
      '--tcdce-ol-counter-weight' => in_array( $value['style']['--tcdce-ol-counter-weight'], array( 'var(--tcdce-opt-font-weight-normal)', 'var(--tcdce-opt-font-weight-bold)' ), true ) ? $value['style']['--tcdce-ol-counter-weight'] : 'var(--tcdce-opt-font-weight-normal)',
      '--tcdce-ol-counter-size' => in_array( $value['style']['--tcdce-ol-counter-size'], array( 'scale(1)', 'scale(1.2)' ), true ) ? $value['style']['--tcdce-ol-counter-size'] : 'scale(1)',
      '--tcdce-ol-counter-color' => sanitize_hex_color( $value['style']['--tcdce-ol-counter-color'] ),
      '--tcdce-ol-counter-background' => in_array( $value['style']['--tcdce-ol-counter-background'], array( 'transparent', 'var(--tcdce-ol-counter-background-color)' ), true ) ? $value['style']['--tcdce-ol-counter-background'] : 'transparent',
      '--tcdce-ol-counter-background-color' => sanitize_hex_color( $value['style']['--tcdce-ol-counter-background-color'] ),
      /**
       * パディング
       */
      '--tcdce-ol-padding-pc' => sanitize_text_field( $value['style']['--tcdce-ol-padding-pc'] ),
      '--tcdce-ol-padding-sp' => sanitize_text_field( $value['style']['--tcdce-ol-padding-sp'] ),
      '--tcdce-ol-padding-custom-pc' => absint( $value['style']['--tcdce-ol-padding-custom-pc'] ),
      '--tcdce-ol-padding-custom-sp' => absint( $value['style']['--tcdce-ol-padding-custom-sp'] ),
      /**
       * マージン
       */
      '--tcdce-ol-margin-top-pc' => absint( $value['style']['--tcdce-ol-margin-top-pc'] ),
      '--tcdce-ol-margin-top-sp' => absint( $value['style']['--tcdce-ol-margin-top-sp'] ),
      '--tcdce-ol-margin-bottom-pc' => absint( $value['style']['--tcdce-ol-margin-bottom-pc'] ),
      '--tcdce-ol-margin-bottom-sp' => absint( $value['style']['--tcdce-ol-margin-bottom-sp'] ),
    )
  );

  return $new_value;

});