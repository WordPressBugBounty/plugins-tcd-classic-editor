<?php

/**
 * heading（h2〜h6）
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$headings = array(
  'h2' => __( 'H2', 'tcd-classic-editor' ),
  'h3' => __( 'H3', 'tcd-classic-editor' ),
  'h4' => __( 'H4', 'tcd-classic-editor' ),
  'h5' => __( 'H5', 'tcd-classic-editor' ),
  'h6' => __( 'H6', 'tcd-classic-editor' )
);

foreach( $headings as $tag => $label ){

  // ブロックエディタにバリエーション登録
  add_filter( 'tcdce_block_register_' . $tag, function( $value, $quicktag, $key ) use ( $tag, $label ) {
    return array(
      'name' => 'core/heading',
      'settings' => array(
        'name' => $tag . '-' . $key,
        'title' => $quicktag['label'],
        /* translators: %s: quicktag label */
        'description' => sprintf( __( '%s is registered in the TCD Classic Editor.', 'tcd-classic-editor' ), $label ),
        'category' => 'tcdce',
        'keywords'=> array( 'tcdce', $tag ),
        'icon' => 'tcdce-' . $tag,
        'attributes' => array(
          'level' => str_replace('h', '', $tag),
          'className' => $quicktag['class'],
        )
      )
    );
  }, 10, 3 );

  // エディタにクイックタグ登録
  add_filter( 'tcdce_qt_register_' . $tag, function( $value, $quicktag ) use ( $tag, $label ) {
    return array(
      'display' => $quicktag['label'],
      'tagStart' => '<' . $tag . ' class="' . esc_attr( $quicktag['class'] ) . '">',
      'tagEnd' => '</' . $tag . '>'
    );
  }, 10, 2 );

  // データセット
  add_action( 'tcdce_qt_fields_set_properties', function( $instance ) use ( $tag, $label ) {

    // ラベルをセット
    $instance->set_label( $tag, $label );

    // プレビュー情報をセット
    $instance->set_preview(
      $tag,
      '<' . $tag . ' class="tcdce-preview--' . $tag . ' js-tcdce-preview-target">' .
      /* translators: %s: quicktag heading label */
      sprintf( __( 'Sample %s', 'tcd-classic-editor' ), $label ) .
      '</' . $tag . '>'
    );

    switch( $tag ) {

      case 'h2' :
        $font_size_pc = 28;
        $font_size_sp = 22;
        $margin_top_pc = 100;
        $margin_top_sp = 50;
        $margin_bottom_pc = 40;
        $margin_bottom_sp = 20;
        break;

      case 'h3' :
        $font_size_pc = 24;
        $font_size_sp = 20;
        $margin_top_pc = 80;
        $margin_top_sp = 50;
        $margin_bottom_pc = 40;
        $margin_bottom_sp = 20;
        break;

      case 'h4' :
        $font_size_pc = 22;
        $font_size_sp = 18;
        $margin_top_pc = 60;
        $margin_top_sp = 40;
        $margin_bottom_pc = 40;
        $margin_bottom_sp = 20;
        break;

      case 'h5' :
        $font_size_pc = 20;
        $font_size_sp = 16;
        $margin_top_pc = 50;
        $margin_top_sp = 40;
        $margin_bottom_pc = 40;
        $margin_bottom_sp = 20;
        break;

      case 'h6' :
        $font_size_pc = 18;
        $font_size_sp = 16;
        $margin_top_pc = 50;
        $margin_top_sp = 40;
        $margin_bottom_pc = 40;
        $margin_bottom_sp = 20;
        break;

    }

    // 初期値をセット
    $default_preset_style = array(
      /**
       * ベース
       */
      '--tcdce-' . $tag . '-font-size-pc' => $font_size_pc,
      '--tcdce-' . $tag . '-font-size-sp' => $font_size_sp,
      '--tcdce-' . $tag . '-text-align' => $tag == 'h2' ? 'center' : 'left',
      // '--tcdce-' . $tag . '-line-height => 'スタイルシートで定義'
      '--tcdce-' . $tag . '-font-weight' => 'var(--tcdce-opt-font-weight-bold)',
      '--tcdce-' . $tag . '-font-color' => '#000000',
      '--tcdce-' . $tag . '-font-family' => 'var(--tcdce-opt-font-type-sans-serif)',
      '--tcdce-' . $tag . '-content-width' => '100%', // 一部のプリセットのみ
      /**
       * 背景
       */
      '--tcdce-' . $tag . '-background' => 'initial',
      '--tcdce-' . $tag . '-background-color' => '#3c3c3c',
      // '--tcdce-' . $tag . '-background-gradation1' => 'スタイルシートで定義',
      // '--tcdce-' . $tag . '-background-gradation2' => 'スタイルシートで定義',
      '--tcdce-' . $tag . '-background-gradation-color1' => '#CEE4FD',
      '--tcdce-' . $tag . '-background-gradation-color2' => '#FFB5F9',
      /**
       * 枠線
       */
      '--tcdce-' . $tag . '-border-style' => 'hidden',
      '--tcdce-' . $tag . '-border-position' => 'var(--tcdce-' . $tag . '-border-width)',
      // '--tcdce-' . $tag . '-border-position1' => 'スタイルシートで定義',
      // '--tcdce-' . $tag . '-border-position2' => 'スタイルシートで定義',
      // '--tcdce-' . $tag . '-border-position3' => 'スタイルシートで定義',
      // '--tcdce-' . $tag . '-border-position4' => 'スタイルシートで定義',
      '--tcdce-' . $tag . '-border-width' => 0,
      '--tcdce-' . $tag . '-border-color' => '#000000',
      // '--tcdce-' . $tag . '-border-gradation1' => 'スタイルシートで定義'
      // '--tcdce-' . $tag . '-border-gradation2' => 'スタイルシートで定義'
      '--tcdce-' . $tag . '-border-gradation-color1' => '#FF0000',
      '--tcdce-' . $tag . '-border-gradation-color2' => '#FFD41D',
      /**
       * エレメント
       */
      '--tcdce-' . $tag . '-element-background' => 'initial',
      '--tcdce-' . $tag . '-element-color' => '#000000',
      // '--tcdce-' . $tag . '-element-gradation1' => 'スタイルシートで定義'
      // '--tcdce-' . $tag . '-element-gradation2' => 'スタイルシートで定義'
      '--tcdce-' . $tag . '-element-gradation-color1' => '#9890e3',
      '--tcdce-' . $tag . '-element-gradation-color2' => '#b1f4cf',
      '--tcdce-' . $tag . '-element-image-url' => '',
      '--tcdce-' . $tag . '-element-inset-inline' => 'var(--tcdce-opt-inset-inline-left)',
      '--tcdce-' . $tag . '-element-inset-block' => 'var(--tcdce-opt-inset-block-top)',
      '--tcdce-' . $tag . '-element-size-w-pc' => 50,
      '--tcdce-' . $tag . '-element-size-w-sp' => 0,
      '--tcdce-' . $tag . '-element-size-h-pc' => 50,
      '--tcdce-' . $tag . '-element-size-h-sp' => 0,
      /**
       * パディング
       */
      '--tcdce-' . $tag . '-padding-pc' => 0, // プリセット依存（ユーザー操作不可）
      '--tcdce-' . $tag . '-padding-sp' => 0, // プリセット依存（ユーザー操作不可）
      '--tcdce-' . $tag . '-padding-top-pc' => 0,
      '--tcdce-' . $tag . '-padding-top-sp' => 0,
      '--tcdce-' . $tag . '-padding-right-pc' => 0,
      '--tcdce-' . $tag . '-padding-right-sp' => 0,
      '--tcdce-' . $tag . '-padding-bottom-pc' => 0,
      '--tcdce-' . $tag . '-padding-bottom-sp' => 0,
      '--tcdce-' . $tag . '-padding-left-pc' => 0,
      '--tcdce-' . $tag . '-padding-left-sp' => 0,
    );

    // デフォルトマージン
    $default_margin_style = array(
      /**
       * マージン
       */
      '--tcdce-' . $tag . '-margin-top-pc' => $margin_top_pc,
      '--tcdce-' . $tag . '-margin-top-sp' => $margin_top_sp,
      '--tcdce-' . $tag . '-margin-bottom-pc' => $margin_bottom_pc,
      '--tcdce-' . $tag . '-margin-bottom-sp' => $margin_bottom_sp,
    );

    $instance->set_default( $tag, array(
      'item' => $tag,
      'show' => 1,
      'class' => 'custom_' . $tag,
      /* translators: %s: quicktag heading label */
      'label' => sprintf( __( 'Custom %s', 'tcd-classic-editor' ), $label ),
      'preset' => 'preset01',
      'style' => $default_preset_style + $default_margin_style
    ) );

    // プリセットデータをセット
    $instance->set_preset( $tag, array(

      /**
       * シンプルな見出し（初期値）
       */
      'preset01' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 1 ),
        'style' => $default_preset_style
      ),
      /**
       * 背景見出し
       */
      'preset02' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 2 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'left',
            '--tcdce-' . $tag . '-font-color' => '#ffffff',
            '--tcdce-' . $tag . '-background' => 'var(--tcdce-' . $tag . '-background-color)',
            '--tcdce-' . $tag . '-background-color' => '#000000',
            '--tcdce-' . $tag . '-padding-pc' => '0.6em 0.9em',
            '--tcdce-' . $tag . '-padding-sp' => '0.5em 0.75em',
          ),
          $default_preset_style
        )
      ),
      /**
       * グラデーション背景見出し
       */
      'preset03' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 3 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'left',
            '--tcdce-' . $tag . '-font-color' => '#222222',
            '--tcdce-' . $tag . '-background' => 'var(--tcdce-' . $tag . '-background-gradation1)',
            '--tcdce-' . $tag . '-padding-pc' => '0.6em 0.9em',
            '--tcdce-' . $tag . '-padding-sp' => '0.5em 0.75em',
            // プリセットリストに反映させるために追加
            '--tcdce-' . $tag . '-background-gradation1' => 'linear-gradient(90deg, var(--tcdce-' . $tag . '-background-gradation-color1) 0%, var(--tcdce-' . $tag . '-background-gradation-color2) 100%)',
          ),
          $default_preset_style
        )
      ),
      /**
       * 左ボーダー見出し
       */
      'preset04' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 4 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'left',
            '--tcdce-' . $tag . '-font-color' => '#000000',
            '--tcdce-' . $tag . '-border-style' => 'solid',
            '--tcdce-' . $tag . '-border-position' => 'var(--tcdce-' . $tag . '-border-position2)',
            '--tcdce-' . $tag . '-border-width' => 3,
            '--tcdce-' . $tag . '-padding-pc' => '0.4em 0 0.4em 0.8em',
            '--tcdce-' . $tag . '-padding-sp' => '0.3em 0 0.3em 0.8em',
            // プリセットリストに反映させるために追加
            '--tcdce-' . $tag . '-border-position2' => '0 0 0 var(--tcdce-' . $tag . '-border-width)'
          ),
          $default_preset_style
        )
      ),
      /**
       * 左グラデーションボーダー見出し
       */
      'preset05' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 5 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'left',
            '--tcdce-' . $tag . '-font-color' => '#000000',
            '--tcdce-' . $tag . '-border-style' => 'var(--tcdce-' . $tag . '-border-gradation2)',
            '--tcdce-' . $tag . '-border-position' => 'var(--tcdce-' . $tag . '-border-position2)',
            '--tcdce-' . $tag . '-border-width' => 5,
            '--tcdce-' . $tag . '-padding-pc' => '0.4em 0 0.4em calc(0.8em + var(--tcdce-' . $tag . '-border-width))',
            '--tcdce-' . $tag . '-padding-sp' => '0.3em 0 0.3em calc(0.8em + var(--tcdce-' . $tag . '-border-width))',
            // プリセットリストに反映させるために追加
            '--tcdce-' . $tag . '-border-gradation2' => 'linear-gradient(180deg, var(--tcdce-' . $tag . '-border-gradation-color1) 0%, var(--tcdce-' . $tag . '-border-gradation-color2) 100%)',
            '--tcdce-' . $tag . '-border-position2' => '0 0 0 var(--tcdce-' . $tag . '-border-width)'
          ),
          $default_preset_style
        )
      ),
      /**
       * 左ボーダー+背景色
       */
      'preset06' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 6 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'left',
            '--tcdce-' . $tag . '-font-color' => '#000000',
            '--tcdce-' . $tag . '-background' => 'var(--tcdce-' . $tag . '-background-color)',
            '--tcdce-' . $tag . '-background-color' => '#fafafa',
            '--tcdce-' . $tag . '-border-style' => 'solid',
            '--tcdce-' . $tag . '-border-position' => 'var(--tcdce-' . $tag . '-border-position2)',
            '--tcdce-' . $tag . '-border-width' => 5,
            '--tcdce-' . $tag . '-border-color' => '#1a335b',
            '--tcdce-' . $tag . '-padding-pc' => '0.5em 0.5em 0.5em 0.7em',
            '--tcdce-' . $tag . '-padding-sp' => '0.5em 0.5em 0.5em 0.75em',
            // プリセットリストに反映させるために追加
            '--tcdce-' . $tag . '-border-position2' => '0 0 0 var(--tcdce-' . $tag . '-border-width)'
          ),
          $default_preset_style
        )
      ),
      /**
       * 下ボーダー
       */
      'preset07' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 7 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'left',
            '--tcdce-' . $tag . '-font-color' => '#000000',
            '--tcdce-' . $tag . '-border-style' => 'solid',
            '--tcdce-' . $tag . '-border-position' => 'var(--tcdce-' . $tag . '-border-position3)',
            '--tcdce-' . $tag . '-border-width' => 3,
            '--tcdce-' . $tag . '-padding-pc' => '0 0 0.4em 0',
            '--tcdce-' . $tag . '-padding-sp' => '0 0 0.3em 0',
            // プリセットリストに反映させるために追加
            '--tcdce-' . $tag . '-border-position3' => '0 0 var(--tcdce-' . $tag . '-border-width) 0'
          ),
          $default_preset_style
        )
      ),
      /**
       * 下グラデーションボーダー
       */
      'preset08' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 8 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'left',
            '--tcdce-' . $tag . '-font-color' => '#000000',
            '--tcdce-' . $tag . '-border-style' => 'var(--tcdce-' . $tag . '-border-gradation1)',
            '--tcdce-' . $tag . '-border-position' => 'var(--tcdce-' . $tag . '-border-position3)',
            '--tcdce-' . $tag . '-border-width' => 5,
            '--tcdce-' . $tag . '-border-gradation-color1' => '#FF5ACD',
            '--tcdce-' . $tag . '-border-gradation-color2' => '#FBDA61',
            '--tcdce-' . $tag . '-padding-pc' => '0 0 calc(0.4em + var(--tcdce-' . $tag . '-border-width)) 0',
            '--tcdce-' . $tag . '-padding-sp' => '0 0 calc(0.3em + var(--tcdce-' . $tag . '-border-width)) 0',
            // プリセットリストに反映させるために追加
            '--tcdce-' . $tag . '-border-gradation1' => 'linear-gradient(90deg, var(--tcdce-' . $tag . '-border-gradation-color1) 0%, var(--tcdce-' . $tag . '-border-gradation-color2) 100%)',
            '--tcdce-' . $tag . '-border-position3' => '0 0 var(--tcdce-' . $tag . '-border-width) 0'
          ),
          $default_preset_style
        )
      ),
      /**
       * 下ボーダー点線見出し
       */
      'preset09' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 9 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'left',
            '--tcdce-' . $tag . '-font-color' => '#222222',
            '--tcdce-' . $tag . '-border-style' => 'dashed',
            '--tcdce-' . $tag . '-border-color' => '#7291B8',
            '--tcdce-' . $tag . '-border-position' => 'var(--tcdce-' . $tag . '-border-position3)',
            '--tcdce-' . $tag . '-border-width' => 3,
            '--tcdce-' . $tag . '-padding-pc' => '0 0 0.4em 0',
            '--tcdce-' . $tag . '-padding-sp' => '0 0 0.3em 0',
            // プリセットリストに反映させるために追加
            '--tcdce-' . $tag . '-border-position3' => '0 0 var(--tcdce-' . $tag . '-border-width) 0'
          ),
          $default_preset_style
        )
      ),
      /**
       * 上ボーダー
       */
      'preset10' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 10 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'center',
            '--tcdce-' . $tag . '-font-color' => '#000000',
            '--tcdce-' . $tag . '-border-style' => 'solid',
            '--tcdce-' . $tag . '-border-color' => '#000000',
            '--tcdce-' . $tag . '-border-position' => 'var(--tcdce-' . $tag . '-border-position1)',
            '--tcdce-' . $tag . '-border-width' => 3,
            '--tcdce-' . $tag . '-padding-pc' => '0.8em 0 0 0',
            '--tcdce-' . $tag . '-padding-sp' => '0.6em 0 0 0',
            // プリセットリストに反映させるために追加
            '--tcdce-' . $tag . '-border-position1' => 'var(--tcdce-' . $tag . '-border-width) 0 0 0'
          ),
          $default_preset_style
        )
      ),
      /**
       * 上ボーダー テキスト幅
       */
      'preset11' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 11 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'center',
            '--tcdce-' . $tag . '-font-color' => '#000000',
            '--tcdce-' . $tag . '-content-width' => 'fit-content',
            '--tcdce-' . $tag . '-border-style' => 'solid',
            '--tcdce-' . $tag . '-border-color' => '#004000',
            '--tcdce-' . $tag . '-border-position' => 'var(--tcdce-' . $tag . '-border-position1)',
            '--tcdce-' . $tag . '-border-width' => 5,
            '--tcdce-' . $tag . '-padding-pc' => '0.8em 0 0 0',
            '--tcdce-' . $tag . '-padding-sp' => '0.6em 0 0 0',
            // プリセットリストに反映させるために追加
            '--tcdce-' . $tag . '-border-position1' => 'var(--tcdce-' . $tag . '-border-width) 0 0 0'
          ),
          $default_preset_style
        )
      ),
      /**
       * エレメント見出し
       */
      'preset12' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 12 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'left',
            '--tcdce-' . $tag . '-element-background' => 'var(--tcdce-' . $tag . '-element-color)',
            '--tcdce-' . $tag . '-element-color' => '#f3b80a',
            '--tcdce-' . $tag . '-element-inset-block' => 'var(--tcdce-opt-inset-block-center)',
            '--tcdce-' . $tag . '-element-size-w-pc' => 40,
            '--tcdce-' . $tag . '-element-size-w-sp' => 30,
            '--tcdce-' . $tag . '-element-size-h-pc' => 5,
            '--tcdce-' . $tag . '-element-size-h-sp' => 4,
            '--tcdce-' . $tag . '-padding-pc' => '0 0 0 calc(0.7em + var(--tcdce-' . $tag . '-element-size-w-pc))',
            '--tcdce-' . $tag . '-padding-sp' => '0 0 0 calc(0.6em + var(--tcdce-' . $tag . '-element-size-w-sp))',
          ),
          $default_preset_style
        )
      ),
      /**
       * エレメント見出し（上）
       */
      'preset13' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 13 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'center',
            '--tcdce-' . $tag . '-element-background' => 'var(--tcdce-' . $tag . '-element-gradation1)',
            '--tcdce-' . $tag . '-element-inset-block' => 'var(--tcdce-opt-inset-block-top)',
            '--tcdce-' . $tag . '-element-inset-inline' => 'var(--tcdce-opt-inset-inline-center)',
            '--tcdce-' . $tag . '-element-size-w-pc' => 120,
            '--tcdce-' . $tag . '-element-size-w-sp' => 70,
            '--tcdce-' . $tag . '-element-size-h-pc' => 3,
            '--tcdce-' . $tag . '-element-size-h-sp' => 2,
            '--tcdce-' . $tag . '-padding-pc' => 'calc(0.6em + var(--tcdce-' . $tag . '-element-size-h-pc)) 0 0 0',
            '--tcdce-' . $tag . '-padding-sp' => 'calc(0.5em + var(--tcdce-' . $tag . '-element-size-h-sp)) 0 0 0',
            // プリセットリストに反映させるために追加
            '--tcdce-' . $tag . '-element-gradation1' => 'linear-gradient(90deg, var(--tcdce-' . $tag . '-element-gradation-color1) 0%, var(--tcdce-' . $tag . '-element-gradation-color2) 100%)',
          ),
          $default_preset_style
        )
      ),
      /**
       * エレメント見出し（下）
       */
      'preset14' => array(
        /* translators: %s: preset number */
        'label' => sprintf( __('Preset %s', 'tcd-classic-editor' ), 14 ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-text-align' => 'center',
            '--tcdce-' . $tag . '-element-background' => 'var(--tcdce-' . $tag . '-element-color)',
            '--tcdce-' . $tag . '-element-color' => '#1556AF',
            '--tcdce-' . $tag . '-element-inset-block' => 'var(--tcdce-opt-inset-block-bottom)',
            '--tcdce-' . $tag . '-element-inset-inline' => 'var(--tcdce-opt-inset-inline-center)',
            '--tcdce-' . $tag . '-element-size-w-pc' => 100,
            '--tcdce-' . $tag . '-element-size-w-sp' => 70,
            '--tcdce-' . $tag . '-element-size-h-pc' => 6,
            '--tcdce-' . $tag . '-element-size-h-sp' => 4,
            '--tcdce-' . $tag . '-padding-pc' => '0 0 calc(0.6em + var(--tcdce-' . $tag . '-element-size-h-pc)) 0',
            '--tcdce-' . $tag . '-padding-sp' => '0 0 calc(0.5em + var(--tcdce-' . $tag . '-element-size-h-sp)) 0',
          ),
          $default_preset_style
        )
      ),
      /**
       * カスタムプリセット
       */
      'preset_custom' => array(
        'label' => __( 'Custom presets (for advanced users)', 'tcd-classic-editor' ),
        'style' => wp_parse_args(
          array(
            '--tcdce-' . $tag . '-border-width' => 3,
            '--tcdce-' . $tag . '-element-size-w-pc' => 30,
            '--tcdce-' . $tag . '-element-size-w-sp' => 20,
            '--tcdce-' . $tag . '-element-size-h-pc' => 30,
            '--tcdce-' . $tag . '-element-size-h-sp' => 20,
            '--tcdce-' . $tag . '-padding-pc' => 'var(--tcdce-' . $tag . '-padding-top-pc) var(--tcdce-' . $tag . '-padding-right-pc) var(--tcdce-' . $tag . '-padding-bottom-pc) var(--tcdce-' . $tag . '-padding-left-pc)',
            '--tcdce-' . $tag . '-padding-sp' => 'var(--tcdce-' . $tag . '-padding-top-sp) var(--tcdce-' . $tag . '-padding-right-sp) var(--tcdce-' . $tag . '-padding-bottom-sp) var(--tcdce-' . $tag . '-padding-left-sp)',
            '--tcdce-' . $tag . '-padding-top-pc' => 20,
            '--tcdce-' . $tag . '-padding-top-sp' => 10,
            '--tcdce-' . $tag . '-padding-bottom-pc' => 20,
            '--tcdce-' . $tag . '-padding-bottom-sp' => 10,
          ),
          $default_preset_style
        )
      )
    ) );

  } );


  // 専用フィールド
  add_action( "tcdce_qt_fields_repeater_options_{$tag}", function( $instance, $base_name, $base_value ){

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
  add_action( "tcdce_qt_fields_repeater_preview_options_{$tag}", function( $instance, $name, $value ) use( $tag ) {

    $item_type = $tag;
    $default = $instance->default[$item_type]['style'];
    $style_name = $name . '[style]';
    $style_value = wp_parse_args( $value['style'], $default );

    /**
     * ベースオプション
     */
    $instance->fields( __( 'Preview', 'tcd-classic-editor' ), array(
      array(
        'title' => __( 'Design Preset', 'tcd-classic-editor' ),
        'field' => $instance->preset( $name . '[preset]', $value['preset'], $item_type ),
      ),
      array(
        'title' => __( 'Font size', 'tcd-classic-editor' ),
        'field' => $instance->number( $style_name, $style_value, array(
          '--tcdce-' . $item_type . '-font-size-pc' => array(
            'icon' => TCDCE_ICONS['pc'],
            'default' => $default['--tcdce-' . $item_type . '-font-size-pc'],
          ),
          '--tcdce-' . $item_type . '-font-size-sp' => array(
            'icon' => TCDCE_ICONS['sp'],
            'default' => $default['--tcdce-' . $item_type . '-font-size-sp'],
          ),
        ) ),
      ),
      array(
        'title' => __( 'Text align', 'tcd-classic-editor' ),
        'field' => $instance->radio( $style_name, $style_value, '--tcdce-' . $item_type . '-text-align', array(
          'left' => TCDCE_ICONS['left'],
          'center' => TCDCE_ICONS['center'],
          'right' => TCDCE_ICONS['right'],
        ) ),
      ),
      array(
        'title' => __( 'Font weight', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->radio( $style_name, $style_value, '--tcdce-' . $item_type . '-font-weight', array(
          'var(--tcdce-opt-font-weight-normal)' => TCDCE_ICONS['thick'],
          'var(--tcdce-opt-font-weight-bold)' => TCDCE_ICONS['bold'],
        ) ),
      ),
      array(
        'title' => __( 'Font color', 'tcd-classic-editor' ),
        'col' => 2,
        'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-font-color' )
      ),
      array(
        'title' => __( 'Font family', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->radio( $style_name, $style_value, '--tcdce-' . $item_type . '-font-family', array(
          'var(--tcdce-opt-font-type-sans-serif)' => __( 'Sans serif', 'tcd-classic-editor' ),
          'var(--tcdce-opt-font-type-serif)' => __( 'Serif', 'tcd-classic-editor' ),
        ) ),
      ),
      array(
        'title' => __( 'Width', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->select( $style_name, $style_value, '--tcdce-' . $item_type . '-content-width', array(
          '100%' => __( 'Full width', 'tcd-classic-editor' ),
          'fit-content' => __( 'Text width', 'tcd-classic-editor' ),
        ) ),
        'class' => 'tcdce-heading-base-width'
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
          'field' => $instance->select( $style_name, $style_value, '--tcdce-' . $item_type . '-background', array(
            'initial' => __( 'None', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-background-color)' => __( 'A single color', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-background-gradation1)' => __( 'Gradation (horizontal)', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-background-gradation2)' => __( 'Gradation (vertical)', 'tcd-classic-editor' ),
          ) ),
          'class' => 'tcdce-heading-bg-type'
        ),
        array(
          'title' => __( 'Background color', 'tcd-classic-editor' ),
          'col' => 2,
          'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-background-color' ),
          'class' => 'tcdce-heading-bg-color'
        ),
        array(
          'title' => __( 'Gradation 1', 'tcd-classic-editor' ),
          'col' => 2,
          'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-background-gradation-color1' ),
          'class' => 'tcdce-heading-bg-g'
        ),
        array(
          'title' => __( 'Gradation 2', 'tcd-classic-editor' ),
          'col' => 2,
          'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-background-gradation-color2' ),
          'class' => 'tcdce-heading-bg-g'
        )
      ),
      'tcdce-heading-bg',
      'tcdce-heading-bg',
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
          'field' => $instance->select( $style_name, $style_value, '--tcdce-' . $item_type . '-border-style', array(
            'hidden' => __( 'None', 'tcd-classic-editor' ),
            'solid' => __( 'Solid', 'tcd-classic-editor' ),
            'dotted' => __( 'Dotted line', 'tcd-classic-editor' ),
            'dashed' => __( 'Dashed line', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-border-gradation1)' => __( 'Gradation (horizontal)', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-border-gradation2)' => __( 'Gradation (vertical)', 'tcd-classic-editor' ),
          ) ),
          'class' => 'tcdce-heading-border-style'
        ),
        // 枠線の色
        array(
          'title' => __( 'Border color', 'tcd-classic-editor' ),
          'col' => 2,
          'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-border-color' ),
          'class' => 'tcdce-heading-border-color'
        ),
        // グラデーション1
        array(
          'title' => __( 'Gradation 1', 'tcd-classic-editor' ),
          'col' => 2,
          'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-border-gradation-color1' ),
          'class' => 'tcdce-heading-border-g'
        ),
        // グラデーション2
        array(
          'title' => __( 'Gradation 2', 'tcd-classic-editor' ),
          'col' => 2,
          'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-border-gradation-color2' ),
          'class' => 'tcdce-heading-border-g'
        ),
        // 枠線の表示位置
        array(
          'title' => __( 'Border position', 'tcd-classic-editor' ),
          'col' => 2,
          'field' => $instance->select( $style_name, $style_value, '--tcdce-' . $item_type . '-border-position', array(
            'var(--tcdce-' . $item_type . '-border-width)' => __( 'All', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-border-position1)' => __( 'Top', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-border-position2)' => __( 'Left', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-border-position3)' => __( 'Bottom', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-border-position4)' => __( 'Top & Bottom', 'tcd-classic-editor' ),
          ) ),
          'class' => 'tcdce-heading-border-position'
        ),
        array(
          'title' => __( 'Border width', 'tcd-classic-editor' ),
          'col' => 2,
          'field' => $instance->number( $style_name, $style_value, array(
            '--tcdce-' . $item_type . '-border-width' => array(
              'icon' => '',
              'default' => 0,
            ),
          ) ),
          'class' => 'tcdce-heading-border-width'
        ),
      ),
      'tcdce-heading-border',
      'tcdce-heading-border'
    );
    /**
     * エレメント
     */
    $instance->fields(
      __( 'Element', 'tcd-classic-editor' ),
      array(
        // エレメントタイプ
        array(
          'title' => __( 'Element type', 'tcd-classic-editor' ),
          'col' => 1,
          'field' => $instance->select( $style_name, $style_value, '--tcdce-' . $item_type . '-element-background', array(
            'initial' => __( 'None', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-element-color)' => __( 'A single color', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-element-gradation1)' => __( 'Gradation (horizontal)', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-element-gradation2)' => __( 'Gradation (vertical)', 'tcd-classic-editor' ),
            'var(--tcdce-' . $item_type . '-element-image-url)' => __( 'Image', 'tcd-classic-editor' ),
          ) ),
          'class' => 'tcdce-heading-el-type'
        ),
        // エレメントカラー
        array(
          'title' => __( 'Background color', 'tcd-classic-editor' ),
          'col' => 2,
          'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-element-color' ),
          'class' => 'tcdce-heading-el-color'
        ),
        // エレメントグラデーションカラー1
        array(
          'title' => __( 'Gradation 1', 'tcd-classic-editor' ),
          'col' => 2,
          'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-element-gradation-color1' ),
          'class' => 'tcdce-heading-el-g'
        ),
        // エレメントグラデーションカラー2
        array(
          'title' => __( 'Gradation 2', 'tcd-classic-editor' ),
          'col' => 2,
          'field' => $instance->color( $style_name, $style_value, '--tcdce-' . $item_type . '-element-gradation-color2' ),
          'class' => 'tcdce-heading-el-g'
        ),
        // 画像
        array(
          'title' => __( 'Image', 'tcd-classic-editor' ),
          'field' => $instance->image( $style_name, $style_value, '--tcdce-' . $item_type . '-element-image-url' ),
          'class' => 'tcdce-heading-el-image'
        ),
        // 横幅
        array(
          'title' => __( 'Width', 'tcd-classic-editor' ),
          'col' => 1,
          'field' => $instance->number( $style_name, $style_value, array(
            '--tcdce-' . $item_type . '-element-size-w-pc' => array(
              'icon' => TCDCE_ICONS['pc'],
              'default' => 0,
            ),
            '--tcdce-' . $item_type . '-element-size-w-sp' => array(
              'icon' => TCDCE_ICONS['sp'],
              'default' => 0,
            ),
          ) ),
        ),
        // 高さ
        array(
          'title' => __( 'Height', 'tcd-classic-editor' ),
          'col' => 1,
          'field' => $instance->number( $style_name, $style_value, array(
            '--tcdce-' . $item_type . '-element-size-h-pc' => array(
              'icon' => TCDCE_ICONS['pc'],
              'default' => 0,
            ),
            '--tcdce-' . $item_type . '-element-size-h-sp' => array(
              'icon' => TCDCE_ICONS['sp'],
              'default' => 0,
            ),
          ) ),
        ),
        // ポジション（左右）
        array(
          'title' => __( 'Align horizontal', 'tcd-classic-editor' ),
          'col' => 1,
          'field' => $instance->radio( $style_name, $style_value, '--tcdce-' . $item_type . '-element-inset-inline', array(
            'var(--tcdce-opt-inset-inline-left)' => TCDCE_ICONS['h_left'],
            'var(--tcdce-opt-inset-inline-center)' => TCDCE_ICONS['h_center'],
            'var(--tcdce-opt-inset-inline-right)' => TCDCE_ICONS['h_right'],
          ) ),
          'class' => 'tcdce-heading-el-pos-inline'
        ),
        // ポジション（上下）
        array(
          'title' => __( 'Align vertical', 'tcd-classic-editor' ),
          'col' => 1,
          'field' => $instance->radio( $style_name, $style_value, '--tcdce-' . $item_type . '-element-inset-block', array(
            'var(--tcdce-opt-inset-block-top)' => TCDCE_ICONS['v_top'],
            'var(--tcdce-opt-inset-block-center)' => TCDCE_ICONS['v_center'],
            'var(--tcdce-opt-inset-block-bottom)' => TCDCE_ICONS['v_bottom'],
          ) ),
          'class' => 'tcdce-heading-el-pos-block'
        ),
      ),
      'tcdce-heading-el',
      'tcdce-heading-el'
    );

    // padding
    $instance->fields(
      __( 'Padding', 'tcd-classic-editor' ),
      array(
        array(
          'title' => '',
          'col' => 0,
          'field' => $instance->text( $style_name, $style_value, '--tcdce-' . $item_type . '-padding-pc', 'js-tcdce-preview-option' )
        ),
        array(
          'title' => '',
          'col' => 0,
          'field' => $instance->text( $style_name, $style_value, '--tcdce-' . $item_type . '-padding-sp', 'js-tcdce-preview-option' )
        ),
        array(
          'title' => __( 'Padding top', 'tcd-classic-editor' ),
          'col' => 1,
          'field' => $instance->number( $style_name, $style_value, array(
            '--tcdce-' . $item_type . '-padding-top-pc' => array(
              'icon' => TCDCE_ICONS['pc'],
              'default' => 0,
            ),
            '--tcdce-' . $item_type . '-padding-top-sp' => array(
              'icon' => TCDCE_ICONS['sp'],
              'default' => 0,
            ),
          ) ),
        ),
        array(
          'title' => __( 'Padding right', 'tcd-classic-editor' ),
          'col' => 1,
          'field' => $instance->number( $style_name, $style_value, array(
            '--tcdce-' . $item_type . '-padding-right-pc' => array(
              'icon' => TCDCE_ICONS['pc'],
              'default' => 0,
            ),
            '--tcdce-' . $item_type . '-padding-right-sp' => array(
              'icon' => TCDCE_ICONS['sp'],
              'default' => 0,
            ),
          ) ),
        ),
        array(
          'title' => __( 'Padding bottom', 'tcd-classic-editor' ),
          'col' => 1,
          'field' => $instance->number( $style_name, $style_value, array(
            '--tcdce-' . $item_type . '-padding-bottom-pc' => array(
              'icon' => TCDCE_ICONS['pc'],
              'default' => 0,
            ),
            '--tcdce-' . $item_type . '-padding-bottom-sp' => array(
              'icon' => TCDCE_ICONS['sp'],
              'default' => 0,
            ),
          ) ),
        ),
        array(
          'title' => __( 'Padding left', 'tcd-classic-editor' ),
          'col' => 1,
          'field' => $instance->number( $style_name, $style_value, array(
            '--tcdce-' . $item_type . '-padding-left-pc' => array(
              'icon' => TCDCE_ICONS['pc'],
              'default' => 0,
            ),
            '--tcdce-' . $item_type . '-padding-left-sp' => array(
              'icon' => TCDCE_ICONS['sp'],
              'default' => 0,
            ),
          ) ),
        ),
      ),
      'tcdce-heading-padding',
      'tcdce-heading-padding',
    );

    // margin
    $instance->fields( __( 'Margin', 'tcd-classic-editor' ), array(
      array(
        'title' => __( 'Margin top', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->number( $style_name, $style_value, array(
          '--tcdce-' . $item_type . '-margin-top-pc' => array(
            'icon' => TCDCE_ICONS['pc'],
            'default' => $default['--tcdce-' . $item_type . '-margin-top-pc'],
          ),
          '--tcdce-' . $item_type . '-margin-top-sp' => array(
            'icon' => TCDCE_ICONS['sp'],
            'default' => $default['--tcdce-' . $item_type . '-margin-top-sp'],
          ),
        ) ),
      ),
      array(
        'title' => __( 'Margin bottom', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $instance->number( $style_name, $style_value, array(
          '--tcdce-' . $item_type . '-margin-bottom-pc' => array(
            'icon' => TCDCE_ICONS['pc'],
            'default' => $default['--tcdce-' . $item_type . '-margin-bottom-pc'],
          ),
          '--tcdce-' . $item_type . '-margin-bottom-sp' => array(
            'icon' => TCDCE_ICONS['sp'],
            'default' => $default['--tcdce-' . $item_type . '-margin-bottom-sp'],
          ),
        ) ),
      ),
    ) );

    // submit
    $instance->submit();

  }, 10, 3 );


  // バリデーション
  add_filter( "tcdce_qt_validation_{$tag}", function( $value ) use( $tag ) {

    $new_value = array(
      'item' => $tag,
      'show' => absint( $value['show'] ),
      'class' => sanitize_text_field( $value['class'] ),
      'label' => sanitize_text_field( $value['label'] ),
      'preset' => sanitize_text_field( $value['preset'] ),
      'style' => array(
        /**
         * ベース
         */
        '--tcdce-' . $tag . '-font-size-pc' => absint( $value['style']['--tcdce-' . $tag . '-font-size-pc'] ),
        '--tcdce-' . $tag . '-font-size-sp' => absint( $value['style']['--tcdce-' . $tag . '-font-size-sp'] ),
        '--tcdce-' . $tag . '-text-align' => in_array( $value['style']['--tcdce-' . $tag . '-text-align'], array( 'left', 'center', 'right' ), true ) ? $value['style']['--tcdce-' . $tag . '-text-align'] : 'left',
        '--tcdce-' . $tag . '-font-weight' => in_array( $value['style']['--tcdce-' . $tag . '-font-weight'], array( 'var(--tcdce-opt-font-weight-normal)', 'var(--tcdce-opt-font-weight-bold)' ), true ) ? $value['style']['--tcdce-' . $tag . '-font-weight'] : 'var(--tcdce-opt-font-weight-bold)',
        '--tcdce-' . $tag . '-font-color' => sanitize_hex_color( $value['style']['--tcdce-' . $tag . '-font-color'] ),
        '--tcdce-' . $tag . '-font-family' => in_array( $value['style']['--tcdce-' . $tag . '-font-family'], array( 'var(--tcdce-opt-font-type-sans-serif)', 'var(--tcdce-opt-font-type-serif)' ), true ) ? $value['style']['--tcdce-' . $tag . '-font-family'] : 'var(--tcdce-opt-font-type-sans-serif)',
        '--tcdce-' . $tag . '-content-width' => in_array( $value['style']['--tcdce-' . $tag . '-content-width'], array( '100%', 'fit-content' ), true ) ? $value['style']['--tcdce-' . $tag . '-content-width'] : '100%',
        /**
         * 背景
         */
        '--tcdce-' . $tag . '-background' => in_array( $value['style']['--tcdce-' . $tag . '-background'], array( 'initial', 'var(--tcdce-' . $tag . '-background-color)', 'var(--tcdce-' . $tag . '-background-gradation1)', 'var(--tcdce-' . $tag . '-background-gradation2)' ), true ) ? $value['style']['--tcdce-' . $tag . '-background'] : 'initial',
        '--tcdce-' . $tag . '-background-color' => sanitize_hex_color( $value['style']['--tcdce-' . $tag . '-background-color'] ),
        '--tcdce-' . $tag . '-background-gradation-color1' => sanitize_hex_color( $value['style']['--tcdce-' . $tag . '-background-gradation-color1'] ),
        '--tcdce-' . $tag . '-background-gradation-color2' => sanitize_hex_color( $value['style']['--tcdce-' . $tag . '-background-gradation-color2'] ),
        /**
         * 枠線
         */
        '--tcdce-' . $tag . '-border-style' => in_array( $value['style']['--tcdce-' . $tag . '-border-style'], array( 'hidden', 'solid', 'dotted', 'dashed', 'var(--tcdce-' . $tag . '-border-gradation1)', 'var(--tcdce-' . $tag . '-border-gradation2)' ), true ) ? $value['style']['--tcdce-' . $tag . '-border-style'] : 'hidden',
        '--tcdce-' . $tag . '-border-position' => in_array( $value['style']['--tcdce-' . $tag . '-border-position'], array( 'var(--tcdce-' . $tag . '-border-width)', 'var(--tcdce-' . $tag . '-border-position1)', 'var(--tcdce-' . $tag . '-border-position2)', 'var(--tcdce-' . $tag . '-border-position3)', 'var(--tcdce-' . $tag . '-border-position4)' ), true ) ? $value['style']['--tcdce-' . $tag . '-border-position'] : 'var(--tcdce-' . $tag . '-border-width)',
        '--tcdce-' . $tag . '-border-width' => absint( $value['style']['--tcdce-' . $tag . '-border-width'] ),
        '--tcdce-' . $tag . '-border-color' => sanitize_hex_color( $value['style']['--tcdce-' . $tag . '-border-color'] ),
        '--tcdce-' . $tag . '-border-gradation-color1' => sanitize_hex_color( $value['style']['--tcdce-' . $tag . '-border-gradation-color1'] ),
        '--tcdce-' . $tag . '-border-gradation-color2' => sanitize_hex_color( $value['style']['--tcdce-' . $tag . '-border-gradation-color2'] ),
        /**
         * エレメント
         */
        '--tcdce-' . $tag . '-element-background' => in_array( $value['style']['--tcdce-' . $tag . '-element-background'], array( 'initial', 'var(--tcdce-' . $tag . '-element-color)', 'var(--tcdce-' . $tag . '-element-gradation1)', 'var(--tcdce-' . $tag . '-element-gradation2)', 'var(--tcdce-' . $tag . '-element-image-url)' ), true ) ? $value['style']['--tcdce-' . $tag . '-element-background'] : 'initial',
        '--tcdce-' . $tag . '-element-color' => sanitize_hex_color( $value['style']['--tcdce-' . $tag . '-element-color'] ),
        '--tcdce-' . $tag . '-element-gradation-color1' => sanitize_hex_color( $value['style']['--tcdce-' . $tag . '-element-gradation-color1'] ),
        '--tcdce-' . $tag . '-element-gradation-color2' => sanitize_hex_color( $value['style']['--tcdce-' . $tag . '-element-gradation-color2'] ),
        '--tcdce-' . $tag . '-element-image-url' => $value['style']['--tcdce-' . $tag . '-element-image-url'] ? absint( $value['style']['--tcdce-' . $tag . '-element-image-url'] ) : '',
        '--tcdce-' . $tag . '-element-inset-inline' => in_array( $value['style']['--tcdce-' . $tag . '-element-inset-inline'], array( 'var(--tcdce-opt-inset-inline-left)', 'var(--tcdce-opt-inset-inline-center)', 'var(--tcdce-opt-inset-inline-right)' ), true ) ? $value['style']['--tcdce-' . $tag . '-element-inset-inline'] : 'var(--tcdce-opt-inset-inline-left)',
        '--tcdce-' . $tag . '-element-inset-block' => in_array( $value['style']['--tcdce-' . $tag . '-element-inset-block'], array( 'var(--tcdce-opt-inset-block-top)', 'var(--tcdce-opt-inset-block-center)', 'var(--tcdce-opt-inset-block-bottom)' ), true ) ? $value['style']['--tcdce-' . $tag . '-element-inset-block'] : 'var(--tcdce-opt-inset-block-top)',
        '--tcdce-' . $tag . '-element-size-w-pc' => absint( $value['style']['--tcdce-' . $tag . '-element-size-w-pc'] ),
        '--tcdce-' . $tag . '-element-size-w-sp' => absint( $value['style']['--tcdce-' . $tag . '-element-size-w-sp'] ),
        '--tcdce-' . $tag . '-element-size-h-pc' => absint( $value['style']['--tcdce-' . $tag . '-element-size-h-pc'] ),
        '--tcdce-' . $tag . '-element-size-h-sp' => absint( $value['style']['--tcdce-' . $tag . '-element-size-h-sp'] ),
        /**
         * パディング
         */
        '--tcdce-' . $tag . '-padding-pc' => sanitize_text_field( $value['style']['--tcdce-' . $tag . '-padding-pc'] ),
        '--tcdce-' . $tag . '-padding-sp' => sanitize_text_field( $value['style']['--tcdce-' . $tag . '-padding-sp'] ),
        '--tcdce-' . $tag . '-padding-top-pc' => absint( $value['style']['--tcdce-' . $tag . '-padding-top-pc'] ),
        '--tcdce-' . $tag . '-padding-top-sp' => absint( $value['style']['--tcdce-' . $tag . '-padding-top-sp'] ),
        '--tcdce-' . $tag . '-padding-right-pc' => absint( $value['style']['--tcdce-' . $tag . '-padding-right-pc'] ),
        '--tcdce-' . $tag . '-padding-right-sp' => absint( $value['style']['--tcdce-' . $tag . '-padding-right-sp'] ),
        '--tcdce-' . $tag . '-padding-bottom-pc' => absint( $value['style']['--tcdce-' . $tag . '-padding-bottom-pc'] ),
        '--tcdce-' . $tag . '-padding-bottom-sp' => absint( $value['style']['--tcdce-' . $tag . '-padding-bottom-sp'] ),
        '--tcdce-' . $tag . '-padding-left-pc' => absint( $value['style']['--tcdce-' . $tag . '-padding-left-pc'] ),
        '--tcdce-' . $tag . '-padding-left-sp' => absint( $value['style']['--tcdce-' . $tag . '-padding-left-sp'] ),
        /**
         * マージン
         */
        '--tcdce-' . $tag . '-margin-top-pc' => absint( $value['style']['--tcdce-' . $tag . '-margin-top-pc'] ),
        '--tcdce-' . $tag . '-margin-top-sp' => absint( $value['style']['--tcdce-' . $tag . '-margin-top-sp'] ),
        '--tcdce-' . $tag . '-margin-bottom-pc' => absint( $value['style']['--tcdce-' . $tag . '-margin-bottom-pc'] ),
        '--tcdce-' . $tag . '-margin-bottom-sp' => absint( $value['style']['--tcdce-' . $tag . '-margin-bottom-sp'] ),
      )
    );

    return $new_value;

  });

}