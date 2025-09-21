<?php

/**
 * クイックタグ設定 box
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// ブロックエディタにバリエーション登録
add_filter( 'tcdce_block_register_box', function( $value, $quicktag, $key ) {
  return array(
    'name' => 'core/paragraph',
    'settings' => array(
      'name' => 'box-' . $key,
      'title' => $quicktag['label'],
      'attributes' => array(
        'className' => 'tcdce-box ' . $quicktag['class'],
      ),
      'scope' => ['transform']
    )
  );
}, 10, 3 );


// エディタにクイックタグ登録
add_filter( 'tcdce_qt_register_box', function( $value, $quicktag ) {
  $tag_start = '<div class="tcdce-box ' . esc_attr( $quicktag['class'] ) . '">';
  return array(
    'display' => $quicktag['label'],
    'tagStart' => $tag_start,
    'tagEnd' => '</div>'
  );

}, 10, 2 );


// データセット
add_action( 'tcdce_qt_fields_set_properties', function( $instance ) {

  // ラベルをセット
  $instance->set_label( 'box', __( 'Box', 'tcd-classic-editor' ) );

  // プレビュー情報をセット
  $instance->set_preview(
    'box',
    '<div class="tcdce-preview--box tcdce-box js-tcdce-preview-target">' .
      /* translators: %s: quicktag box label */
      sprintf( __( 'Sample %s', 'tcd-classic-editor' ), $instance->get_label( 'box' ) ) .
    '</div>'
  );

  // 初期値をセット
  $default_preset_style = array(
    /**
     * ベース
     */
    '--tcdce-box-font-size-pc' => 16,
    '--tcdce-box-font-size-sp' => 14,
    '--tcdce-box-font-weight' => 'var(--tcdce-opt-font-weight-normal)',
    '--tcdce-box-font-color' => '#000000',
    /**
     * 背景
     */
    '--tcdce-box-background' => 'var(--tcdce-box-background-color)',
    '--tcdce-box-background-color' => '#f6f6f6',
    // '--tcdce-box-background-gradation1' => 'スタイルシートで定義',
    // '--tcdce-box-background-gradation2' => 'スタイルシートで定義',
    '--tcdce-box-background-gradation-color1' => '#fff1eb',
    '--tcdce-box-background-gradation-color2' => '#ace0f9',
    /**
     * 枠線
     */
    '--tcdce-box-border-style' => 'hidden',
    '--tcdce-box-border-width' => 2,
    '--tcdce-box-border-color' => '#000000',
    /**
     * アイコン
     */
    '--tcdce-box-icon' => 'none', // プリセット依存（ユーザー操作不可）
    '--tcdce-box-icon-offset' => '0em', // プリセット依存（ユーザー操作不可）
    '--tcdce-box-icon-content' => 'var(--tcdce-opt-icon--info)',
    '--tcdce-box-icon-image-url' => '',
    '--tcdce-box-icon-color' => '#000000',
    /**
     * パディング
     */
    '--tcdce-box-padding-pc' => '1.5em', // プリセット依存（ユーザー操作不可）
    '--tcdce-box-padding-sp' => '1em', // プリセット依存（ユーザー操作不可）
    '--tcdce-box-padding-custom-pc' => 30,
    '--tcdce-box-padding-custom-sp' => 15,
  );

  // デフォルトマージン
  $default_margin_style = array(
    /**
     * マージン
     */
    '--tcdce-box-margin-top-pc' => 40,
    '--tcdce-box-margin-top-sp' => 20,
    '--tcdce-box-margin-bottom-pc' => 40,
    '--tcdce-box-margin-bottom-sp' => 20,
  );

  $instance->set_default( 'box', array(
    'item' => 'box',
    'show' => 1,
    'class' => 'custom_box',
    /* translators: %s: quicktag box label */
    'label' => sprintf( __( 'Custom %s', 'tcd-classic-editor' ), $instance->get_label( 'box' ) ),
    'preset' => 'preset01',
    'style' => $default_preset_style + $default_margin_style
  ) );

  // プリセットデータをセット
  $instance->set_preset( 'box', array(

    /**
     * シンプルな囲み枠（初期値）
     */
    'preset01' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 1 ),
      'style' => $default_preset_style
    ),
    /**
     * 枠線のみ囲み枠
     */
    'preset02' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 2 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-box-background' => 'initial',
          '--tcdce-box-border-style' => 'solid'
        ),
        $default_preset_style
      )
    ),
    /**
     * アイコン付き囲み枠（補足）
     */
    'preset03' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 3 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-box-background-color' => '#eaeefa',
          '--tcdce-box-border-color' => '#1163c7',
          '--tcdce-box-icon' => 'var(--tcdce-opt-icon)',
          '--tcdce-box-icon-offset' => '2em',
          '--tcdce-box-icon-content' => 'var(--tcdce-opt-icon--info)',
          '--tcdce-box-icon-color' => '#1163c7',
        ),
        $default_preset_style
      )
    ),
    /**
     * アイコン付き囲み枠（警告）
     */
    'preset04' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 4 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-box-background' => 'initial',
          '--tcdce-box-background-color' => '#feece8',
          '--tcdce-box-border-style' => 'solid',
          '--tcdce-box-border-color' => '#EF3C00',
          '--tcdce-box-border-width' => 1,
          '--tcdce-box-icon' => 'var(--tcdce-opt-icon)',
          '--tcdce-box-icon-offset' => '2em',
          '--tcdce-box-icon-content' => 'var(--tcdce-opt-icon--warn)',
          '--tcdce-box-icon-color' => '#EF3C00',
        ),
        $default_preset_style
      )
    ),
    /**
     * アイコン付き囲み枠（ヒント）
     */
    'preset05' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 5 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-box-background-color' => '#fdf8ea',
          '--tcdce-box-border-style' => 'solid',
          '--tcdce-box-border-color' => '#EDB40A',
          '--tcdce-box-border-width' => 1,
          '--tcdce-box-icon' => 'var(--tcdce-opt-icon)',
          '--tcdce-box-icon-offset' => '2em',
          '--tcdce-box-icon-content' => 'var(--tcdce-opt-icon--bulb)',
          '--tcdce-box-icon-color' => '#EDB40A',
        ),
        $default_preset_style
      )
    ),
    /**
     * アイコン付き囲み枠（チェック）
     */
    'preset06' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 6 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-box-background' => 'initial',
          '--tcdce-box-background-color' => '#ebf6e9',
          '--tcdce-box-border-style' => 'dashed',
          '--tcdce-box-border-color' => '#16AE05',
          '--tcdce-box-border-width' => 2,
          '--tcdce-box-icon' => 'var(--tcdce-opt-icon)',
          '--tcdce-box-icon-offset' => '2em',
          '--tcdce-box-icon-content' => 'var(--tcdce-opt-icon--check)',
          '--tcdce-box-icon-color' => '#16AE05',
        ),
        $default_preset_style
      )
    ),
    /**
     * アイコン付き囲み枠（はてな）
     */
    'preset07' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 7 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-box-font-color' => '#ffffff',
          '--tcdce-box-background' => 'var(--tcdce-box-background-gradation1)',
          '--tcdce-box-background-gradation-color1' => '#7367F0',
          '--tcdce-box-background-gradation-color2' => '#CE9FFC',
          '--tcdce-box-border-style' => 'hidden',
          '--tcdce-box-border-color' => '#000000',
          '--tcdce-box-border-width' => 1,
          '--tcdce-box-icon' => 'var(--tcdce-opt-icon)',
          '--tcdce-box-icon-offset' => '2em',
          '--tcdce-box-icon-content' => 'var(--tcdce-opt-icon--help)',
          '--tcdce-box-icon-color' => '#ffffff',
          // プリセットリストに反映させるために追加
          '--tcdce-box-background-gradation1' => 'linear-gradient(90deg,var(--tcdce-box-background-gradation-color1) 0%,var(--tcdce-box-background-gradation-color2) 100%);'
        ),
        $default_preset_style
      )
    ),
    /**
     * カスタム（アイコン無し）
     */
    'custom01' => array(
      'label' => __( 'Custom preset (without icon)', 'tcd-classic-editor' ),
      'style' => wp_parse_args(
        array(
          '--tcdce-box-padding-pc' => 'var(--tcdce-box-padding-custom-pc)',
          '--tcdce-box-padding-sp' => 'var(--tcdce-box-padding-custom-sp)',
        ),
        $default_preset_style
      )
    ),
    /**
     * カスタム（アイコンあり）
     */
    'custom02' => array(
      'label' => __( 'Custom presets (with icons)', 'tcd-classic-editor' ),
      'style' => wp_parse_args(
        array(
          '--tcdce-box-icon' => 'var(--tcdce-opt-icon)',
          '--tcdce-box-icon-offset' => '2em',
          '--tcdce-box-padding-pc' => 'var(--tcdce-box-padding-custom-pc)',
          '--tcdce-box-padding-sp' => 'var(--tcdce-box-padding-custom-sp)',
        ),
        $default_preset_style
      )
    ),
  ) );

} );


// 専用フィールド
add_action( 'tcdce_qt_fields_repeater_options_box', function( $instance, $base_name, $base_value ){

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
add_action( 'tcdce_qt_fields_repeater_preview_options_box', function( $instance, $name, $value ) {

  $item_type = 'box';
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
        '--tcdce-box-font-size-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-box-font-size-pc'],
        ),
        '--tcdce-box-font-size-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-box-font-size-sp'],
        ),
      ) ),
    ),
    array(
      'title' => __( 'Font weight', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->radio( $style_name, $style_value, '--tcdce-box-font-weight', array(
        'var(--tcdce-opt-font-weight-normal)' => TCDCE_ICONS['thick'],
        'var(--tcdce-opt-font-weight-bold)' => TCDCE_ICONS['bold'],
      ) ),
    ),
    array(
      'title' => __( 'Font color', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->color( $style_name, $style_value, '--tcdce-box-font-color' )
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
        'field' => $instance->select( $style_name, $style_value, '--tcdce-box-background', array(
          'initial' => __( 'None', 'tcd-classic-editor' ),
          'var(--tcdce-box-background-color)' => __( 'A single color', 'tcd-classic-editor' ),
          'var(--tcdce-box-background-gradation1)' => __( 'Gradation (horizontal)', 'tcd-classic-editor' ),
          'var(--tcdce-box-background-gradation2)' => __( 'Gradation (vertical)', 'tcd-classic-editor' ),
        ) ),
        'class' => 'tcdce-box-bg-type'
      ),
      array(
        'title' => __( 'Background color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-box-background-color' ),
        'class' => 'tcdce-box-bg-color'
      ),
      array(
        'title' => __( 'Gradation 1', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-box-background-gradation-color1' ),
        'class' => 'tcdce-box-bg-g'
      ),
      array(
        'title' => __( 'Gradation 2', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-box-background-gradation-color2' ),
        'class' => 'tcdce-box-bg-g'
      )
    )
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
        'field' => $instance->select( $style_name, $style_value, '--tcdce-box-border-style', array(
          'hidden' => __( 'None', 'tcd-classic-editor' ),
          'solid' => __( 'Solid', 'tcd-classic-editor' ),
          'dotted' => __( 'Dotted line', 'tcd-classic-editor' ),
          'dashed' => __( 'Dashed line', 'tcd-classic-editor' ),
        ) ),
        'class' => 'tcdce-box-border-style'
      ),
      // 枠線の色
      array(
        'title' => __( 'Border color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-box-border-color' ),
      ),
      array(
        'title' => __( 'Border width', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->number( $style_name, $style_value, array(
          '--tcdce-box-border-width' => array(
            'icon' => '',
            'default' => 0,
          ),
        ) ),
      ),
    ),
    '',
    ''
  );

  /**
   * アイコン
   */
  $instance->fields(
    __( 'Icon', 'tcd-classic-editor' ),
    array(
      // アイコン有り無し
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-box-icon', array(
          'none' => 'none',
          'var(--tcdce-opt-icon)' => 'var(--tcdce-opt-icon)',
        ) ),
        'class' => 'tcdce-box-icon-type'
      ),
      // アイコンオフセット
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-box-icon-offset', array(
          '0em' => '0em',
          '2em' => '2em'
        ) )
      ),
      // アイコンフォント
      array(
        'title' => __( 'Selectable icons', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->radio_icon( $style_name, $style_value, '--tcdce-box-icon-content', array(
          'var(--tcdce-box-icon-image-url)' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="1em" height="1em" fill="currentColor"><path d="M480-480ZM212.31-140Q182-140 161-161q-21-21-21-51.31v-535.38Q140-778 161-799q21-21 51.31-21h270q12.75 0 21.37 8.63 8.63 8.63 8.63 21.38 0 12.76-8.63 21.37-8.62 8.62-21.37 8.62h-270q-5.39 0-8.85 3.46t-3.46 8.85v535.38q0 5.39 3.46 8.85t8.85 3.46h535.38q5.39 0 8.85-3.46t3.46-8.85v-270q0-12.75 8.63-21.37 8.63-8.63 21.38-8.63 12.76 0 21.37 8.63 8.62 8.62 8.62 21.37v270Q820-182 799-161q-21 21-51.31 21H212.31Zm43.08-152.31h449.22L565-478.46 445-322.69l-85-108.08-104.61 138.46ZM680-680h-50q-12.75 0-21.38-8.63-8.62-8.63-8.62-21.38 0-12.76 8.62-21.37Q617.25-740 630-740h50v-50q0-12.75 8.63-21.37 8.63-8.63 21.38-8.63 12.76 0 21.37 8.63Q740-802.75 740-790v50h50q12.75 0 21.37 8.63 8.63 8.63 8.63 21.38 0 12.76-8.63 21.37Q802.75-680 790-680h-50v50q0 12.75-8.63 21.38-8.63 8.62-21.38 8.62-12.76 0-21.37-8.62Q680-617.25 680-630v-50Z"/></svg>'
        ) ),
        'class' => 'tcdce-box-icon-font'
      ),
      // 画像
      array(
        'title' => __( 'Custom icons (.png only)', 'tcd-classic-editor' ),
        'field' => $instance->image( $style_name, $style_value, '--tcdce-box-icon-image-url' ),
        'class' => 'tcdce-box-icon-image'
      ),
      // アイコンカラー
      array(
        'title' => __( 'Icon color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-box-icon-color' )
      ),
    ),
    'tcdce-box-icon',
    'tcdce-box-icon'
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
        'field' => $instance->select( $style_name, $style_value, '--tcdce-box-padding-pc', array(
          '1.5em' => '1.5em',
          'var(--tcdce-box-padding-custom-pc)' => 'var(--tcdce-box-padding-custom-pc)'
        ) )
      ),
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-box-padding-sp', array(
          '1em' => '1em',
          'var(--tcdce-box-padding-custom-sp)' => 'var(--tcdce-box-padding-custom-sp)'
        ) )
      ),
      array(
        'title' => __( 'Padding (top/bottom/left/right)', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->number( $style_name, $style_value, array(
          '--tcdce-box-padding-custom-pc' => array(
            'icon' => TCDCE_ICONS['pc'],
            'default' => 0,
          ),
          '--tcdce-box-padding-custom-sp' => array(
            'icon' => TCDCE_ICONS['sp'],
            'default' => 0,
          ),
        ) ),
      ),
    ),
    'tcdce-box-padding',
    'tcdce-box-padding',
  );

  /**
   * マージン
   */
  $instance->fields( __( 'Margin', 'tcd-classic-editor' ), array(
    array(
      'title' => __( 'Margin top', 'tcd-classic-editor' ),
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-box-margin-top-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-box-margin-top-pc'],
        ),
        '--tcdce-box-margin-top-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-box-margin-top-sp'],
        ),
      ) ),
    ),
    array(
      'title' => __( 'Margin bottom', 'tcd-classic-editor' ),
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-box-margin-bottom-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-box-margin-bottom-pc'],
        ),
        '--tcdce-box-margin-bottom-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-box-margin-bottom-sp'],
        ),
      ) ),
    ),
  ) );

  // submit
  $instance->submit();

}, 10, 3 );


// バリデーション
add_filter( 'tcdce_qt_validation_box', function( $value ) {

  $new_value = array(
    'item' => 'box',
    'show' => absint( $value['show'] ),
    'class' => sanitize_text_field( $value['class'] ),
    'label' => sanitize_text_field( $value['label'] ),
    'preset' => sanitize_text_field( $value['preset'] ),
    'style' => array(
      /**
       * ベース
       */
      '--tcdce-box-font-size-pc' => absint( $value['style']['--tcdce-box-font-size-pc'] ),
      '--tcdce-box-font-size-sp' => absint( $value['style']['--tcdce-box-font-size-sp'] ),
      '--tcdce-box-font-color' => sanitize_hex_color( $value['style']['--tcdce-box-font-color'] ),
      '--tcdce-box-font-weight' => in_array( $value['style']['--tcdce-box-font-weight'], array( 'var(--tcdce-opt-font-weight-normal)', 'var(--tcdce-opt-font-weight-bold)' ), true ) ? $value['style']['--tcdce-box-font-weight'] : 'var(--tcdce-opt-font-weight-normal)',
      /**
       * 背景
       */
      '--tcdce-box-background' => in_array( $value['style']['--tcdce-box-background'], array( 'initial', 'var(--tcdce-box-background-color)', 'var(--tcdce-box-background-gradation1)', 'var(--tcdce-box-background-gradation2)' ), true ) ? $value['style']['--tcdce-box-background'] : 'initial',
      '--tcdce-box-background-color' => sanitize_hex_color( $value['style']['--tcdce-box-background-color'] ),
      '--tcdce-box-background-gradation-color1' => sanitize_hex_color( $value['style']['--tcdce-box-background-gradation-color1'] ),
      '--tcdce-box-background-gradation-color2' => sanitize_hex_color( $value['style']['--tcdce-box-background-gradation-color2'] ),
      /**
       * 枠線
       */
      '--tcdce-box-border-style' => in_array( $value['style']['--tcdce-box-border-style'], array( 'hidden', 'solid', 'dotted', 'dashed' ), true ) ? $value['style']['--tcdce-box-border-style'] : 'hidden',
      '--tcdce-box-border-width' => absint( $value['style']['--tcdce-box-border-width'] ),
      '--tcdce-box-border-color' => sanitize_hex_color( $value['style']['--tcdce-box-border-color'] ),
      /**
       * アイコン
       */
      '--tcdce-box-icon' => in_array( $value['style']['--tcdce-box-icon'], array( 'none', 'var(--tcdce-opt-icon)' ), true ) ? $value['style']['--tcdce-box-icon'] : 'none',
      '--tcdce-box-icon-offset' => sanitize_text_field( $value['style']['--tcdce-box-icon-offset'] ),
      '--tcdce-box-icon-content' => sanitize_text_field( $value['style']['--tcdce-box-icon-content'] ),
      '--tcdce-box-icon-image-url' => $value['style']['--tcdce-box-icon-image-url'] ? absint( $value['style']['--tcdce-box-icon-image-url'] ) : '',
      '--tcdce-box-icon-color' => sanitize_hex_color( $value['style']['--tcdce-box-icon-color'] ),
      /**
       * パディング
       */
      '--tcdce-box-padding-pc' => sanitize_text_field( $value['style']['--tcdce-box-padding-pc'] ),
      '--tcdce-box-padding-sp' => sanitize_text_field( $value['style']['--tcdce-box-padding-sp'] ),
      '--tcdce-box-padding-custom-pc' => absint( $value['style']['--tcdce-box-padding-custom-pc'] ),
      '--tcdce-box-padding-custom-sp' => absint( $value['style']['--tcdce-box-padding-custom-sp'] ),
      /**
       * マージン
       */
      '--tcdce-box-margin-top-pc' => absint( $value['style']['--tcdce-box-margin-top-pc'] ),
      '--tcdce-box-margin-top-sp' => absint( $value['style']['--tcdce-box-margin-top-sp'] ),
      '--tcdce-box-margin-bottom-pc' => absint( $value['style']['--tcdce-box-margin-bottom-pc'] ),
      '--tcdce-box-margin-bottom-sp' => absint( $value['style']['--tcdce-box-margin-bottom-sp'] ),
    )
  );

  return $new_value;

});