<?php

/**
 * クイックタグ設定 ul
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


// ブロックエディタにバリエーション登録
add_filter( 'tcdce_block_register_ul', function( $value, $quicktag, $key ) {
  return array(
    'name' => 'core/list',
    'settings' => array(
      'name' => 'ul-' . $key,
      'title' => $quicktag['label'],
      /* translators: %s: quicktag label */
      'description' => sprintf( __( '%s is registered in the TCD Classic Editor.', 'tcd-classic-editor' ), __( 'Unordered list', 'tcd-classic-editor' ) ),
      'category' => 'tcdce',
      'keywords'=> array( 'tcdce', 'ul', 'list' ),
      'icon' => 'tcdce-ul',
      'attributes' => array(
        'ordered' => false,
        'className' => $quicktag['class'],
      )
    )
  );
}, 10, 3 );


// エディタにクイックタグ登録
add_filter( 'tcdce_qt_register_ul', function( $value, $quicktag ) {
  return array(
    'display' => $quicktag['label'],
    'tag' => '<ul class="' . esc_attr( $quicktag['class'] ) . '">' . "\n" .
              str_repeat( "\t" . '<li>' . __( 'Unordered list', 'tcd-classic-editor' ) . '</li>' . "\n", 3 ) .
              '</ul>'
  );
}, 10, 2 );


// データセット
add_action( 'tcdce_qt_fields_set_properties', function( $instance ) {

  // ラベルをセット
  $instance->set_label( 'ul', __( 'Unordered list', 'tcd-classic-editor' ) );

  // プレビュー情報をセット
  $instance->set_preview(
    'ul',
    '<ul class="tcdce-preview--ul js-tcdce-preview-target">' .
    /* translators: %s: quicktag ul label */
    str_repeat( '<li>' . sprintf( __( 'Sample %s', 'tcd-classic-editor' ), $instance->get_label( 'ul' ) ) . '</li>', 3 ) .
    '</ul>',
  );

  // 初期値をセット
  $default_preset_style = array(
    /**
     * ベース
     */
    '--tcdce-ul-font-size-pc' => 16,
    '--tcdce-ul-font-size-sp' => 14,
    '--tcdce-ul-font-weight' => 'var(--tcdce-opt-font-weight-normal)',
    '--tcdce-ul-font-color' => '#000000',
    /**
     * 背景
     */
    '--tcdce-ul-background' => 'initial',
    '--tcdce-ul-background-color' => '#f6f6f6',
    // '--tcdce-ul-background-gradation1' => 'スタイルシートで定義',
    // '--tcdce-ul-background-gradation2' => 'スタイルシートで定義',
    '--tcdce-ul-background-gradation-color1' => '#fff1eb',
    '--tcdce-ul-background-gradation-color2' => '#ace0f9',
    /**
     * 枠線
     */
    '--tcdce-ul-border-style' => 'hidden',
    '--tcdce-ul-border-width' => 2,
    '--tcdce-ul-border-color' => '#000000',
    /**
     * アイコン
    */
    '--tcdce-ul-list-style' => 'disc', // プリセット依存（ユーザー操作不可）
    '--tcdce-ul-icon' => 'none', // プリセット依存（ユーザー操作不可）
    '--tcdce-ul-icon-offset' => '1em', // プリセット依存（ユーザー操作不可）
    '--tcdce-ul-icon-content' => 'var(--tcdce-opt-icon--check)',
    '--tcdce-ul-icon-image-url' => '',
    '--tcdce-ul-icon-color' => '#000000',
    /**
     * パディング
     */
    '--tcdce-ul-padding-pc' => 'var(--tcdce-ul-padding-custom-pc)', // プリセット依存（ユーザー操作不可）
    '--tcdce-ul-padding-sp' => 'var(--tcdce-ul-padding-custom-sp)', // プリセット依存（ユーザー操作不可）
    '--tcdce-ul-padding-custom-pc' => 0,
    '--tcdce-ul-padding-custom-sp' => 0,
  );

  // デフォルトマージン
  $default_margin_style = array(
    /**
     * マージン
     */
    '--tcdce-ul-margin-top-pc' => 40,
    '--tcdce-ul-margin-top-sp' => 20,
    '--tcdce-ul-margin-bottom-pc' => 40,
    '--tcdce-ul-margin-bottom-sp' => 20,
  );

  $instance->set_default( 'ul', array(
    'item' => 'ul',
    'show' => 1,
    'class' => 'custom_ul',
    /* translators: %s: quicktag ul label */
    'label' => sprintf( __( 'Custom %s', 'tcd-classic-editor' ), $instance->get_label( 'ul' ) ),
    'preset' => 'preset01',
    'style' => $default_preset_style + $default_margin_style
  ) );

  // プリセットデータをセット
  $instance->set_preset( 'ul', array(

    /**
     * シンプルなリスト（初期値）
     */
    'preset01' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 1 ),
      'style' => $default_preset_style
    ),
    /**
     * 背景色付きリスト
     */
    'preset02' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 2 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-ul-background' => 'var(--tcdce-ul-background-color)',
          '--tcdce-ul-padding-pc' => '1.5em',
          '--tcdce-ul-padding-sp' => '1em',
        ),
        $default_preset_style
      )
    ),
    /**
     * 枠線付きリスト
     */
    'preset03' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 3 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-ul-border-style' => 'solid',
          '--tcdce-ul-padding-pc' => '1.5em',
          '--tcdce-ul-padding-sp' => '1em',
        ),
        $default_preset_style
      )
    ),
    /**
     * シンプルアイコンリスト
     */
    'preset04' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 4 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-ul-list-style' => 'none',
          '--tcdce-ul-icon' => 'var(--tcdce-opt-icon)',
          '--tcdce-ul-icon-offset' => '1.5em',
          '--tcdce-ul-icon-color' => '#16ae05',
        ),
        $default_preset_style
      )
    ),
    /**
     * 背景色アイコンリスト
     */
    'preset05' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 5 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-ul-background' => 'var(--tcdce-ul-background-color)',
          '--tcdce-ul-background-color' => '#e9f2fd',
          '--tcdce-ul-list-style' => 'none',
          '--tcdce-ul-icon' => 'var(--tcdce-opt-icon)',
          '--tcdce-ul-icon-offset' => '1.5em',
          '--tcdce-ul-icon-content' => 'var(--tcdce-opt-icon--good)',
          '--tcdce-ul-icon-color' => '#0085E6',
          '--tcdce-ul-padding-pc' => '1.5em',
          '--tcdce-ul-padding-sp' => '1em',
        ),
        $default_preset_style
      )
    ),
    /**
     * 枠線アイコンリスト
     */
    'preset06' => array(
      /* translators: %s: preset number */
      'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 6 ),
      'style' => wp_parse_args(
        array(
          '--tcdce-ul-border-style' => 'dashed',
          '--tcdce-ul-border-color' => '#FF0000',
          '--tcdce-ul-border-width' => 1,
          '--tcdce-ul-list-style' => 'none',
          '--tcdce-ul-icon' => 'var(--tcdce-opt-icon)',
          '--tcdce-ul-icon-offset' => '1.5em',
          '--tcdce-ul-icon-content' => 'var(--tcdce-opt-icon--cross)',
          '--tcdce-ul-icon-color' => '#FF0000',
          '--tcdce-ul-padding-pc' => '1.5em',
          '--tcdce-ul-padding-sp' => '1em',
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
          '--tcdce-ul-background' => 'var(--tcdce-ul-background-color)',
          '--tcdce-ul-padding-custom-pc' => 30,
          '--tcdce-ul-padding-custom-sp' => 15,
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
          '--tcdce-ul-background' => 'var(--tcdce-ul-background-color)',
          '--tcdce-ul-list-style' => 'none',
          '--tcdce-ul-icon' => 'var(--tcdce-opt-icon)',
          '--tcdce-ul-icon-offset' => '1.5em',
          '--tcdce-ul-padding-custom-pc' => 30,
          '--tcdce-ul-padding-custom-sp' => 15,
        ),
        $default_preset_style
      )
    )
  ) );

} );


// 専用フィールド
add_action( 'tcdce_qt_fields_repeater_options_ul', function( $instance, $base_name, $base_value ){

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
add_action( 'tcdce_qt_fields_repeater_preview_options_ul', function( $instance, $name, $value ) {

  $item_type = 'ul';
  $default = $instance->default['ul']['style'];
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
        '--tcdce-ul-font-size-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-ul-font-size-pc'],
        ),
        '--tcdce-ul-font-size-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-ul-font-size-sp'],
        ),
      ) ),
    ),
    array(
      'title' => __( 'Font weight', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->radio( $style_name, $style_value, '--tcdce-ul-font-weight', array(
        'var(--tcdce-opt-font-weight-normal)' => TCDCE_ICONS['thick'],
        'var(--tcdce-opt-font-weight-bold)' => TCDCE_ICONS['bold'],
      ) ),
    ),
    array(
      'title' => __( 'Font color', 'tcd-classic-editor' ),
      'col' => 2,
      'field' => $instance->color( $style_name, $style_value, '--tcdce-ul-font-color' )
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
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ul-background', array(
          'initial' => __( 'None', 'tcd-classic-editor' ),
          'var(--tcdce-ul-background-color)' => __( 'A single color', 'tcd-classic-editor' ),
          'var(--tcdce-ul-background-gradation1)' => __( 'Gradation (horizontal)', 'tcd-classic-editor' ),
          'var(--tcdce-ul-background-gradation2)' => __( 'Gradation (vertical)', 'tcd-classic-editor' ),
        ) ),
        'class' => 'tcdce-ul-bg-type'
      ),
      array(
        'title' => __( 'Background color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-ul-background-color' ),
        'class' => 'tcdce-ul-bg-color'
      ),
      array(
        'title' => __( 'Gradation 1', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-ul-background-gradation-color1' ),
        'class' => 'tcdce-ul-bg-g'
      ),
      array(
        'title' => __( 'Gradation 2', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-ul-background-gradation-color2' ),
        'class' => 'tcdce-ul-bg-g'
      )
    ),
    'tcdce-ul-bg',
    'tcdce-ul-bg'
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
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ul-border-style', array(
          'hidden' => __( 'None', 'tcd-classic-editor' ),
          'solid' => __( 'Solid', 'tcd-classic-editor' ),
          'dotted' => __( 'Dotted line', 'tcd-classic-editor' ),
          'dashed' => __( 'Dashed line', 'tcd-classic-editor' ),
        ) ),
        'class' => 'tcdce-ul-border-style'
      ),
      // 枠線の色
      array(
        'title' => __( 'Border color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-ul-border-color' ),
      ),
      array(
        'title' => __( 'Border width', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->number( $style_name, $style_value, array(
          '--tcdce-ul-border-width' => array(
            'icon' => '',
            'default' => 0,
          ),
        ) ),
      ),
    ),
    'tcdce-ul-border',
    'tcdce-ul-border'
  );

  /**
   * アイコン
   */
  $instance->fields(
    __( 'Icon', 'tcd-classic-editor' ),
    array(
      // リストスタイル
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ul-list-style', array(
          'none' => 'none',
          'disc' => 'disc',
        ) ),
      ),
      // アイコン有り無し
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ul-icon', array(
          'none' => 'none',
          'var(--tcdce-opt-icon)' => 'var(--tcdce-opt-icon)',
        ) ),
        'class' => 'tcdce-ul-icon-type'
      ),
      // アイコンオフセット
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ul-icon-offset', array(
          '1em' => '1em',
          '1.5em' => '1.5em'
        ) )
      ),
      // アイコンフォント
      array(
        'title' => __( 'Selectable icons', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->radio_icon( $style_name, $style_value, '--tcdce-ul-icon-content', array(
          'var(--tcdce-ul-icon-image-url)' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="1em" height="1em" fill="currentColor"><path d="M480-480ZM212.31-140Q182-140 161-161q-21-21-21-51.31v-535.38Q140-778 161-799q21-21 51.31-21h270q12.75 0 21.37 8.63 8.63 8.63 8.63 21.38 0 12.76-8.63 21.37-8.62 8.62-21.37 8.62h-270q-5.39 0-8.85 3.46t-3.46 8.85v535.38q0 5.39 3.46 8.85t8.85 3.46h535.38q5.39 0 8.85-3.46t3.46-8.85v-270q0-12.75 8.63-21.37 8.63-8.63 21.38-8.63 12.76 0 21.37 8.63 8.62 8.62 8.62 21.37v270Q820-182 799-161q-21 21-51.31 21H212.31Zm43.08-152.31h449.22L565-478.46 445-322.69l-85-108.08-104.61 138.46ZM680-680h-50q-12.75 0-21.38-8.63-8.62-8.63-8.62-21.38 0-12.76 8.62-21.37Q617.25-740 630-740h50v-50q0-12.75 8.63-21.37 8.63-8.63 21.38-8.63 12.76 0 21.37 8.63Q740-802.75 740-790v50h50q12.75 0 21.37 8.63 8.63 8.63 8.63 21.38 0 12.76-8.63 21.37Q802.75-680 790-680h-50v50q0 12.75-8.63 21.38-8.63 8.62-21.38 8.62-12.76 0-21.37-8.62Q680-617.25 680-630v-50Z"/></svg>'
        ) ),
        'class' => 'tcdce-ul-icon-font'
      ),
      // 画像
      array(
        'title' => __( 'Custom icons (.png only)', 'tcd-classic-editor' ),
        'field' => $instance->image( $style_name, $style_value, '--tcdce-ul-icon-image-url' ),
        'class' => 'tcdce-ul-icon-image'
      ),
      // アイコンカラー
      array(
        'title' => __( 'Icon color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-ul-icon-color' )
      ),
    ),
    'tcdce-ul-icon',
    'tcdce-ul-icon'
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
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ul-padding-pc', array(
          '1.5em' => '1.5em',
          'var(--tcdce-ul-padding-custom-pc)' => 'var(--tcdce-ul-padding-custom-pc)'
        ) )
      ),
      array(
        'title' => '',
        'col' => 0,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-ul-padding-sp', array(
          '1em' => '1em',
          'var(--tcdce-ul-padding-custom-sp)' => 'var(--tcdce-ul-padding-custom-sp)'
        ) )
      ),
      array(
        'title' => __( 'Padding (top/bottom/left/right)', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->number( $style_name, $style_value, array(
          '--tcdce-ul-padding-custom-pc' => array(
            'icon' => TCDCE_ICONS['pc'],
            'default' => 0,
          ),
          '--tcdce-ul-padding-custom-sp' => array(
            'icon' => TCDCE_ICONS['sp'],
            'default' => 0,
          ),
        ) ),
      ),
    ),
    'tcdce-ul-padding',
    'tcdce-ul-padding',
  );

  /**
   * マージン
   */
  $instance->fields( __( 'Margin', 'tcd-classic-editor' ), array(
    array(
      'title' => __( 'Margin top', 'tcd-classic-editor' ),
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-ul-margin-top-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-ul-margin-top-pc'],
        ),
        '--tcdce-ul-margin-top-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-ul-margin-top-sp'],
        ),
      ) ),
    ),
    array(
      'title' => __( 'Margin bottom', 'tcd-classic-editor' ),
      'field' => $instance->number( $style_name, $style_value, array(
        '--tcdce-ul-margin-bottom-pc' => array(
          'icon' => TCDCE_ICONS['pc'],
          'default' => $default['--tcdce-ul-margin-bottom-pc'],
        ),
        '--tcdce-ul-margin-bottom-sp' => array(
          'icon' => TCDCE_ICONS['sp'],
          'default' => $default['--tcdce-ul-margin-bottom-sp'],
        ),
      ) ),
    ),
  ) );

  // submit
  $instance->submit();

}, 10, 3 );



// バリデーション
add_filter( 'tcdce_qt_validation_ul', function( $value ) {

  $new_value = array(
    'item' => 'ul',
    'show' => absint( $value['show'] ),
    'class' => sanitize_text_field( $value['class'] ),
    'label' => sanitize_text_field( $value['label'] ),
    'preset' => sanitize_text_field( $value['preset'] ),
    'style' => array(
      /**
       * ベース
       */
      '--tcdce-ul-font-size-pc' => absint( $value['style']['--tcdce-ul-font-size-pc'] ),
      '--tcdce-ul-font-size-sp' => absint( $value['style']['--tcdce-ul-font-size-sp'] ),
      '--tcdce-ul-font-weight' => in_array( $value['style']['--tcdce-ul-font-weight'], array( 'var(--tcdce-opt-font-weight-normal)', 'var(--tcdce-opt-font-weight-bold)' ), true ) ? $value['style']['--tcdce-ul-font-weight'] : 'var(--tcdce-opt-font-weight-normal)',
      '--tcdce-ul-font-color' => sanitize_hex_color( $value['style']['--tcdce-ul-font-color'] ),
      /**
       * 背景
       */
      '--tcdce-ul-background' => in_array( $value['style']['--tcdce-ul-background'], array( 'initial', 'var(--tcdce-ul-background-color)', 'var(--tcdce-ul-background-gradation1)', 'var(--tcdce-ul-background-gradation2)' ), true ) ? $value['style']['--tcdce-ul-background'] : 'initial',
      '--tcdce-ul-background-color' => sanitize_hex_color( $value['style']['--tcdce-ul-background-color'] ),
      '--tcdce-ul-background-gradation-color1' => sanitize_hex_color( $value['style']['--tcdce-ul-background-gradation-color1'] ),
      '--tcdce-ul-background-gradation-color2' => sanitize_hex_color( $value['style']['--tcdce-ul-background-gradation-color2'] ),
      /**
       * 枠線
       */
      '--tcdce-ul-border-style' => in_array( $value['style']['--tcdce-ul-border-style'], array( 'hidden', 'solid', 'dotted', 'dashed' ), true ) ? $value['style']['--tcdce-ul-border-style'] : 'hidden',
      '--tcdce-ul-border-width' => absint( $value['style']['--tcdce-ul-border-width'] ),
      '--tcdce-ul-border-color' => sanitize_hex_color( $value['style']['--tcdce-ul-border-color'] ),
      /**
       * アイコン
       */
      '--tcdce-ul-list-style' => in_array( $value['style']['--tcdce-ul-list-style'], array( 'none', 'disc' ), true ) ? $value['style']['--tcdce-ul-list-style'] : 'none',
      '--tcdce-ul-icon' => in_array( $value['style']['--tcdce-ul-icon'], array( 'none', 'var(--tcdce-opt-icon)' ), true ) ? $value['style']['--tcdce-ul-icon'] : 'none',
      '--tcdce-ul-icon-offset' => sanitize_text_field( $value['style']['--tcdce-ul-icon-offset'] ),
      '--tcdce-ul-icon-content' => sanitize_text_field( $value['style']['--tcdce-ul-icon-content'] ),
      '--tcdce-ul-icon-image-url' => $value['style']['--tcdce-ul-icon-image-url'] ? absint( $value['style']['--tcdce-ul-icon-image-url'] ) : '',
      '--tcdce-ul-icon-color' => sanitize_hex_color( $value['style']['--tcdce-ul-icon-color'] ),
      /**
       * パディング
       */
      '--tcdce-ul-padding-pc' => sanitize_text_field( $value['style']['--tcdce-ul-padding-pc'] ),
      '--tcdce-ul-padding-sp' => sanitize_text_field( $value['style']['--tcdce-ul-padding-sp'] ),
      '--tcdce-ul-padding-custom-pc' => absint( $value['style']['--tcdce-ul-padding-custom-pc'] ),
      '--tcdce-ul-padding-custom-sp' => absint( $value['style']['--tcdce-ul-padding-custom-sp'] ),
      /**
       * マージン
       */
      '--tcdce-ul-margin-top-pc' => absint( $value['style']['--tcdce-ul-margin-top-pc'] ),
      '--tcdce-ul-margin-top-sp' => absint( $value['style']['--tcdce-ul-margin-top-sp'] ),
      '--tcdce-ul-margin-bottom-pc' => absint( $value['style']['--tcdce-ul-margin-bottom-pc'] ),
      '--tcdce-ul-margin-bottom-sp' => absint( $value['style']['--tcdce-ul-margin-bottom-sp'] ),
    )
  );

  return $new_value;

});