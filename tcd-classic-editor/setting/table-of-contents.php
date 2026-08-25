<?php

/**
 * Table of contents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// 目次 初期化
add_action( 'tcdce_add_admin_menu', function( $instance ){

  $instance->submenus[] = array(

    // add menu
		'title' => __( 'Table of contents', 'tcd-classic-editor' ),
		'capability' => 'manage_options',
		'slug' => 'tcd_classic_editor_toc',
		'callback' => 'tcdce_toc_menu_template',

    // add setting
    'setting' => array(
      'option_group' => 'tcdce_toc_group',
      'option_name' => 'tcdce_toc',
      'sanitize_callback' => 'tcdce_toc_setting_validation',
      'default_callback' => 'tcdce_toc_setting_default'
    )

  );

} );

// 初期値
function tcdce_toc_setting_default(){

  return array(
    'display' => 1,
    'post_types' => array(
      'post' => 1,
      'page' => 0,
    ),
    'count' => 2,
    'range' => '2-3',
    'title' => __( 'Table of contents', 'tcd-classic-editor' ),
  );

}

// バリデーション
function tcdce_toc_setting_validation( $value ){

  // reset
  if( isset( $value['reset'] ) && $value['reset'] == 1 ){

    update_option( 'tcdce_reset', 1 );
    return tcdce_toc_setting_default();

  // save
  }else{

    return array(
      'display' => absint( $value['display'] ),
      'post_types' => $value['post_types'] ? $value['post_types'] : array(),
      'count' => absint( $value['count'] ),
      'range' => in_array( $value['range'], array( '2', '2-3', '2-4', '2-5', '2-6' ), true ) ? $value['range'] : '2-3',
      'title' => sanitize_text_field( $value['title'] ),
    );

  }

}

// テンプレート
function tcdce_toc_menu_template( $submenu ){

?>
  <h1 class="tcdce-page__headline"><?php echo esc_html( $submenu['title'] ); ?></h1>
  <p class="tcdce-page__desc">
    <?php esc_html_e( 'This function allows you to display the table of contents within the body (above the first h2 heading) and in the sidebar (using the table of contents widget).', 'tcd-classic-editor' ); ?><br>
    <?php esc_html_e( 'The number and level of headings will be automatically displayed. (The table of contents does not use quick tags.)', 'tcd-classic-editor' ); ?><br>
    <?php esc_html_e( 'On mobile phones, the table of contents can be displayed by clicking the icon in the lower right corner of the screen.', 'tcd-classic-editor' ); ?>
  </p>
  <div class="tcdce-setting">
    <form id="js-tcdce-form" class="tcdce-setting__form" action="options.php" method="post">
<?php

    settings_fields( $submenu['setting']['option_group'] );

    $base_name = $submenu['setting']['option_name'];
    $tcdce_toc = get_option( $base_name );

    $tcdce_qt_fields = new TCDCE_Qt_Fields();

?>
      <div class="tcdce-base-fields">
        <div class="tcdce-base-fields__item">
          <div class="tcdce-base-fields__item-left">
            <span class="tcdce-base-fields__item-title"><?php esc_html_e( 'Display Settings', 'tcd-classic-editor' ); ?></span>
            <p class="tcdce-base-fields__item-desc"><?php esc_html_e( 'Sets the location where the table of contents is displayed.', 'tcd-classic-editor' ); ?></p>
          </div>
          <div class="tcdce-base-fields__item-right">
<?php

    $display_options = apply_filters(
      'tcdce_toc_setting_display_options',
      array(
        0 => __( 'Hide', 'tcd-classic-editor' ),
        1 => __( 'Show in post content', 'tcd-classic-editor' ),
        2 => __( 'Show in sidebar', 'tcd-classic-editor' ),
        3 => __( 'Show in post content and sidebar', 'tcd-classic-editor' ),
      )
    );

    echo wp_kses( $tcdce_qt_fields->select( $base_name, $tcdce_toc, 'display', $display_options ), wp_kses_allowed_html( 'tcdce' ) );

?>
          </div>
        </div>

        <div class="tcdce-base-fields__item">
          <div class="tcdce-base-fields__item-left">
            <span class="tcdce-base-fields__item-title"><?php esc_html_e( 'Post types', 'tcd-classic-editor' ); ?></span>
            <p class="tcdce-base-fields__item-desc"><?php esc_html_e( 'Check the post type for which you want the table of contents to appear.', 'tcd-classic-editor' ); ?></p>
          </div>
          <div class="tcdce-base-fields__item-right">
<?php

    $post_types = array(
      'post',
      'page'
    );

    $built_in_post_types = array_keys(
      get_post_types(
        array(
          'public'   => true,
          '_builtin' => false,
          // ナビゲーションメニューに表示しない場合も除く
          'show_in_nav_menus'   => true,
        )
      )
    );

    if( ! empty( $built_in_post_types ) ){
      $post_types = array_merge( $post_types, $built_in_post_types );
    }

    $post_types = apply_filters(
      'tcdce_toc_setting_post_types_options',
      $post_types
    );

    foreach( $post_types as $post_type ){
      $post_type_obj = get_post_type_object( $post_type );
      echo wp_kses( $tcdce_qt_fields->toggle( $base_name . '[post_types]', $tcdce_toc['post_types'], $post_type, $post_type_obj->labels->name ), wp_kses_allowed_html( 'tcdce' ) );
    }

?>
          </div>
        </div>

        <div class="tcdce-base-fields__item">
          <div class="tcdce-base-fields__item-left">
            <span class="tcdce-base-fields__item-title"><?php esc_html_e( 'Display conditions', 'tcd-classic-editor' ); ?></span>
            <p class="tcdce-base-fields__item-desc"><?php esc_html_e( 'If there are X or more headings in an article, a table of contents is created.', 'tcd-classic-editor' ); ?></p>
          </div>
          <div class="tcdce-base-fields__item-right">
          <?php
            $count_options = array();
            for ($i = 2; $i <= 5; $i++) {
              /* translators: %s: target heading count */
              $count_options[$i] = sprintf( __( 'Display with %s or more', 'tcd-classic-editor' ), $i );
            }
            echo wp_kses( $tcdce_qt_fields->select( $base_name, $tcdce_toc, 'count', $count_options ), wp_kses_allowed_html( 'tcdce' ) );
          ?>
          </div>
        </div>

        <div class="tcdce-base-fields__item">
          <div class="tcdce-base-fields__item-left">
            <span class="tcdce-base-fields__item-title"><?php esc_html_e( 'Target headings', 'tcd-classic-editor' ); ?></span>
            <p class="tcdce-base-fields__item-desc"><?php esc_html_e( 'The selected heading tags will be included in the table of contents.', 'tcd-classic-editor' ); ?></p>
          </div>
          <div class="tcdce-base-fields__item-right">
          <?php
            $range_options = array(
              '2' => __( 'Only h2 tags', 'tcd-classic-editor' ),
              '2-3' => __( 'h2-h3', 'tcd-classic-editor' ),
              '2-4' => __( 'h2-h4', 'tcd-classic-editor' ),
              '2-5' => __( 'h2-h5', 'tcd-classic-editor' ),
              '2-6' => __( 'h2-h6', 'tcd-classic-editor' ),
            );
            echo wp_kses( $tcdce_qt_fields->select( $base_name, $tcdce_toc, 'range', $range_options ), wp_kses_allowed_html( 'tcdce' ) );
          ?>
          </div>
        </div>

        <div class="tcdce-base-fields__item">
          <div class="tcdce-base-fields__item-left">
            <span class="tcdce-base-fields__item-title"><?php esc_html_e( 'Table of Contents Label', 'tcd-classic-editor' ); ?></span>
            <p class="tcdce-base-fields__item-desc"><?php esc_html_e( 'Fill in the label to be displayed at the top of the table of contents.', 'tcd-classic-editor' ); ?></p>
          </div>
          <div class="tcdce-base-fields__item-right">
            <?php echo wp_kses( $tcdce_qt_fields->text( $base_name, $tcdce_toc, 'title', '', __( 'Table of contents', 'tcd-classic-editor' ) ), wp_kses_allowed_html( 'tcdce' ) ); ?>
          </div>
        </div>

      </div>
      <?php $tcdce_qt_fields->submit(); ?>
      <?php $tcdce_qt_fields->reset( $base_name ); ?>

    </form>
  </div>
<?php

}