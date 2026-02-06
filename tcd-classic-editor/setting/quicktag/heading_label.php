<?php

/**
 * Heading Label
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// ブロックエディタにバリエーション登録
add_filter( 'tcdce_block_register_heading_label', function( $value, $quicktag, $key ) {

  if( in_array( $quicktag['wrapper_tag'], [ 'h2', 'h3', 'h4', 'h5', 'h6' ], true ) ){
    // 見出しの場合
    return [
      'name' => 'core/heading',
      'settings' => [
        'name' => 'heading_label-' . $key,
        'title' => $quicktag['label'],
        'description' => sprintf(
          /* translators: %s: quicktag label */
          __( '%s is registered in the TCD Classic Editor.', 'tcd-classic-editor' ),
          __( 'Labeled heading', 'tcd-classic-editor' )
        ),
        'category' => 'tcdce',
        'keywords'=> [ 'tcdce', 'heading_label' ],
        'icon' => 'tcdce-heading_label',
        'attributes' => [
          'level' => str_replace( 'h', '', $quicktag['wrapper_tag'] ),
          'className' => 'tcdce-heading-label ' . $quicktag['class'],
        ]
      ]
    ];

  }else{
    // その他の場合は、カスタムHTML
    return [
      'name' => 'core/html',
      'settings' => [
        'name' => 'heading_label-' . $key,
        'title' => $quicktag['label'],
        'description' => sprintf(
          /* translators: %s: quicktag label */
          __( '%s is registered in the TCD Classic Editor.', 'tcd-classic-editor' ),
          __( 'Labeled heading', 'tcd-classic-editor' )
        ),
        'category' => 'tcdce',
        'keywords'=> [ 'tcdce', 'heading_label' ],
        'icon' => 'tcdce-heading_label',
        'attributes' => [
          'content' => '<div class="tcdce-heading-label ' . $quicktag['class'] . '">' . __( 'Labeled heading', 'tcd-classic-editor' ) . '</div>',
        ]
      ]
    ];
  }

}, 10, 3 );


// エディタにクイックタグ登録
add_filter( 'tcdce_qt_register_heading_label', function( $value, $quicktag ) {
  return [
    'display' => $quicktag['label'],
    'tagStart' => '<' . $quicktag['wrapper_tag'] . ' class="tcdce-heading-label ' . esc_attr( $quicktag['class'] ) . '">',
    'tagEnd' => '</' . $quicktag['wrapper_tag'] . '>'
  ];
}, 10, 2 );


// データセット
add_action( 'tcdce_qt_fields_set_properties', function( $instance ) {

  $item_type = 'heading_label';

  // ラベルをセット
  $instance->set_label( 'heading_label', __( 'Labeled heading', 'tcd-classic-editor' ) );

  // プレビュー情報をセット
  $instance->set_preview(
    $item_type,
    '<h2 class="tcdce-heading-label tcdce-preview--heading_label js-tcdce-preview-target">' .
    /* translators: %s: quicktag heading label */
    sprintf( __( 'Sample %s', 'tcd-classic-editor' ), __( 'Labeled heading', 'tcd-classic-editor' ) ) .
    '</h2>'
  );

  // 初期値をセット
  $default_preset_style = [
    /**
     * ベース
     */
    '--tcdce-' . $item_type . '-font-size-pc' => 24,
    '--tcdce-' . $item_type . '-font-size-sp' => 18,
    '--tcdce-' . $item_type . '-font-weight'  => 'var(--tcdce-opt-font-weight-bold)',
    '--tcdce-' . $item_type . '-font-color' => '#000000',
    '--tcdce-' . $item_type . '-font-family' => 'var(--tcdce-opt-font-type-sans-serif)',
    /**
     * エレメント
     */
    '--tcdce-' . $item_type . '-element-text' => __( "Label", 'tcd-classic-editor' ),
    '--tcdce-' . $item_type . '-element-color' => '#ffffff',
    '--tcdce-' . $item_type . '-element-background' => '#000000',
    '--tcdce-' . $item_type . '-element-border-radius' => 999,
    /**
     * マージン
     */
    '--tcdce-' . $item_type . '-margin-top-pc' => 100,
    '--tcdce-' . $item_type . '-margin-top-sp' => 50,
    '--tcdce-' . $item_type . '-margin-bottom-pc' => 40,
    '--tcdce-' . $item_type . '-margin-bottom-sp' => 20,
  ];

  $instance->set_default(
    'heading_label',
    [
      'item' => 'heading_label',
      'show' => 1,
      'class' => 'custom_heading_label',
      /* translators: %s: quicktag heading label */
      'label' => sprintf( __( 'Custom %s', 'tcd-classic-editor' ), __( 'Labeled heading', 'tcd-classic-editor' ) ),
      'preset' => 'preset01',
      'wrapper_tag' => 'h2',
      'style' => $default_preset_style
    ]
  );

  // プリセットデータをセット
  $instance->set_preset(
    'heading_label',
    [
      'preset01' => [
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 1 ),
        'style' => [
          '--tcdce-' . $item_type . '-font-size-pc' => 24,
          '--tcdce-' . $item_type . '-font-size-sp' => 18,
          '--tcdce-' . $item_type . '-font-weight'  => 'var(--tcdce-opt-font-weight-bold)',
          '--tcdce-' . $item_type . '-font-color' => '#000000',
          '--tcdce-' . $item_type . '-font-family' => 'var(--tcdce-opt-font-type-sans-serif)',
          '--tcdce-' . $item_type . '-element-text' => '"' . __( "Label", 'tcd-classic-editor' ) . '"',
          '--tcdce-' . $item_type . '-element-color' => '#ffffff',
          '--tcdce-' . $item_type . '-element-background' => '#008c69',
          '--tcdce-' . $item_type . '-element-border-radius' => 999,
        ]
      ],
      'preset02' => [
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 2 ),
        'style' => [
          '--tcdce-' . $item_type . '-font-size-pc' => 24,
          '--tcdce-' . $item_type . '-font-size-sp' => 18,
          '--tcdce-' . $item_type . '-font-weight'  => 'var(--tcdce-opt-font-weight-bold)',
          '--tcdce-' . $item_type . '-font-color' => '#000000',
          '--tcdce-' . $item_type . '-font-family' => 'var(--tcdce-opt-font-type-sans-serif)',
          '--tcdce-' . $item_type . '-element-text' => '"' . __( "Label", 'tcd-classic-editor' ) . '"',
          '--tcdce-' . $item_type . '-element-color' => '#ffffff',
          '--tcdce-' . $item_type . '-element-background' => '#1080ab',
          '--tcdce-' . $item_type . '-element-border-radius' => 10,
        ]
      ],
      'preset03' => [
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 3 ),
        'style' => [
          '--tcdce-' . $item_type . '-font-size-pc' => 24,
          '--tcdce-' . $item_type . '-font-size-sp' => 18,
          '--tcdce-' . $item_type . '-font-weight'  => 'var(--tcdce-opt-font-weight-bold)',
          '--tcdce-' . $item_type . '-font-color' => '#000000',
          '--tcdce-' . $item_type . '-font-family' => 'var(--tcdce-opt-font-type-sans-serif)',
          '--tcdce-' . $item_type . '-element-text' => '"' . __( "Label", 'tcd-classic-editor' ) . '"',
          '--tcdce-' . $item_type . '-element-color' => '#ffffff',
          '--tcdce-' . $item_type . '-element-background' => '#673202',
          '--tcdce-' . $item_type . '-element-border-radius' => 0,
        ]
      ],
    ]
  );
} );


// 専用フィールド
add_action( "tcdce_qt_fields_repeater_options_heading_label", function( $instance, $base_name, $base_value ){
  $instance->fields(
    __( 'Quicktag setting', 'tcd-classic-editor' ),
    [
      [
        'title' => __( 'Registered name', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->text( $base_name, $base_value, 'label', 'js-tcdce-repeater-label js-tcdce-empty-validation' )
      ],
      [
        'title' => __( 'Class name', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->text( $base_name, $base_value, 'class', 'js-tcdce-empty-validation' )
      ],
    ]
  );
}, 10, 3 );


// 専用フィールド（プレビュー用）
add_action( "tcdce_qt_fields_repeater_preview_options_heading_label", function( $instance, $name, $value ) {

  $item_type = 'heading_label';
  $default = $instance->default[$item_type]['style'];
  $style_name = $name . '[style]';
  $style_value = wp_parse_args( $value['style'], $default );

  /**
   * ベースオプション
   */
  $instance->fields(
    __( 'Preview', 'tcd-classic-editor' ),
    [
      [
        'title' => __( 'Design Preset', 'tcd-classic-editor' ),
        'field' => $instance->preset( $name . '[preset]', $value['preset'], $item_type ),
      ],
      [
        'title' => __( 'Enclosing HTML tags', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->select(
          $name,
          $value,
          'wrapper_tag',
          [
            'h2' => 'H2',
            'h3' => 'H3',
            'h4' => 'H4',
            'h5' => 'H5',
            'h6' => 'H6',
            'div' => 'div',
          ]
        ),
      ],
      [
        'title' => __( 'Font size', 'tcd-classic-editor' ),
        'field' => $instance->number(
          $style_name,
          $style_value,
          [
            '--tcdce-' . $item_type . '-font-size-pc' => [
              'icon' => TCDCE_ICONS['pc'],
              'default' => $default['--tcdce-' . $item_type . '-font-size-pc'],
            ],
            '--tcdce-' . $item_type . '-font-size-sp' => [
              'icon' => TCDCE_ICONS['sp'],
              'default' => $default['--tcdce-' . $item_type . '-font-size-sp'],
            ],
          ]
        ),
      ],
      [
        'title' => __( 'Font weight', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->radio(
          $style_name,
          $style_value,
          '--tcdce-' . $item_type . '-font-weight',
          [
            'var(--tcdce-opt-font-weight-normal)' => TCDCE_ICONS['thick'],
            'var(--tcdce-opt-font-weight-bold)' => TCDCE_ICONS['bold'],
          ]
        ),
      ],
      [
        'title' => __( 'Font color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-font-color' )
      ],
      [
        'title' => __( 'Font family', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->radio(
          $style_name,
          $style_value,
          '--tcdce-' . $item_type . '-font-family',
          [
            'var(--tcdce-opt-font-type-sans-serif)' => __( 'Sans serif', 'tcd-classic-editor' ),
            'var(--tcdce-opt-font-type-serif)' => __( 'Serif', 'tcd-classic-editor' ),
          ]
        ),
      ]
    ]
  );
  /**
   * エレメント
   */
  $instance->fields(
    __( 'Label', 'tcd-classic-editor' ),
    [
      [
        'title' => __( 'Text', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->textarea( $style_name, $style_value, '--tcdce-' . $item_type . '-element-text', 2, 'js-tcdce-preview-option js-tcdce-preview-target--pseudo-text' ),
      ],
      [
        'title' => __( 'Font color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-element-color' ),
      ],
      [
        'title' => __( 'Background color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-element-background' ),
      ],
      [
        'title' => __( 'Rounded corners', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->number( $style_name, $style_value, [
          '--tcdce-' . $item_type . '-element-border-radius' => [
            'icon' => '',
            'default' => 0,
          ],
        ] ),
        'class' => 'tcdce-heading_label-border-color'
      ],
    ]
  );

  // margin
  $instance->fields(
    __( 'Margin', 'tcd-classic-editor' ),
    [
      [
        'title' => __( 'Margin top', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->number(
          $style_name,
          $style_value,
          [
            '--tcdce-' . $item_type . '-margin-top-pc' => [
              'icon' => TCDCE_ICONS['pc'],
              'default' => $default['--tcdce-' . $item_type . '-margin-top-pc'],
            ],
            '--tcdce-' . $item_type . '-margin-top-sp' => [
              'icon' => TCDCE_ICONS['sp'],
              'default' => $default['--tcdce-' . $item_type . '-margin-top-sp'],
            ],
          ]
        ),
      ],
      [
        'title' => __( 'Margin bottom', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->number(
          $style_name,
          $style_value,
          [
            '--tcdce-' . $item_type . '-margin-bottom-pc' => [
              'icon' => TCDCE_ICONS['pc'],
              'default' => $default['--tcdce-' . $item_type . '-margin-bottom-pc'],
            ],
            '--tcdce-' . $item_type . '-margin-bottom-sp' => [
              'icon' => TCDCE_ICONS['sp'],
              'default' => $default['--tcdce-' . $item_type . '-margin-bottom-sp'],
            ],
          ]
        ),
      ]
    ]
  );

  // submit
  $instance->submit();

}, 10, 3 );


  // バリデーション
  add_filter( "tcdce_qt_validation_heading_label", function( $value ) {

    $item_type = 'heading_label';
    $new_value = [
      'item' => $item_type,
      'show' => absint( $value['show'] ),
      'class' => sanitize_text_field( $value['class'] ),
      'label' => sanitize_text_field( $value['label'] ),
      'preset' => sanitize_text_field( $value['preset'] ),
      'wrapper_tag' => sanitize_text_field( $value['wrapper_tag'] ),
      'style' => [
        /**
         * ベース
         */
        '--tcdce-' . $item_type . '-font-size-pc' => absint( $value['style']['--tcdce-' . $item_type . '-font-size-pc'] ),
        '--tcdce-' . $item_type . '-font-size-sp' => absint( $value['style']['--tcdce-' . $item_type . '-font-size-sp'] ),
        '--tcdce-' . $item_type . '-font-weight'  => in_array( $value['style']['--tcdce-' . $item_type . '-font-weight'], array( 'var(--tcdce-opt-font-weight-normal)', 'var(--tcdce-opt-font-weight-bold)' ), true ) ? $value['style']['--tcdce-' . $item_type . '-font-weight'] : 'var(--tcdce-opt-font-weight-bold)',
        '--tcdce-' . $item_type . '-font-color' => sanitize_hex_color( $value['style']['--tcdce-' . $item_type . '-font-color'] ),
        '--tcdce-' . $item_type . '-font-family' => in_array( $value['style']['--tcdce-' . $item_type . '-font-family'], array( 'var(--tcdce-opt-font-type-sans-serif)', 'var(--tcdce-opt-font-type-serif)' ), true ) ? $value['style']['--tcdce-' . $item_type . '-font-family'] : 'var(--tcdce-opt-font-type-sans-serif)',
        /**
         * エレメント
         */
        '--tcdce-' . $item_type . '-element-text' => sanitize_textarea_field( $value['style']['--tcdce-' . $item_type . '-element-text'] ),
        '--tcdce-' . $item_type . '-element-color' => sanitize_hex_color( $value['style']['--tcdce-' . $item_type . '-element-color'] ),
        '--tcdce-' . $item_type . '-element-background' => sanitize_hex_color( $value['style']['--tcdce-' . $item_type . '-element-background'] ),
        '--tcdce-' . $item_type . '-element-border-radius' => absint( $value['style']['--tcdce-' . $item_type . '-element-border-radius'] ),
        /**
         * マージン
         */
        '--tcdce-' . $item_type . '-margin-top-pc' => absint( $value['style']['--tcdce-' . $item_type . '-margin-top-pc'] ),
        '--tcdce-' . $item_type . '-margin-top-sp' => absint( $value['style']['--tcdce-' . $item_type . '-margin-top-sp'] ),
        '--tcdce-' . $item_type . '-margin-bottom-pc' => absint( $value['style']['--tcdce-' . $item_type . '-margin-bottom-pc'] ),
        '--tcdce-' . $item_type . '-margin-bottom-sp' => absint( $value['style']['--tcdce-' . $item_type . '-margin-bottom-sp'] ),
      ]
    ];

    return $new_value;
  });
