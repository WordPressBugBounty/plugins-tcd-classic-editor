<?php

/**
 * Google Maps
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Google Maps 初期化
add_action( 'tcdce_add_admin_menu', function( $instance ){

  $instance->submenus[] = array(

    // add menu
		'title' => __( 'Google Maps', 'tcd-classic-editor' ),
		'capability' => 'manage_options',
		'slug' => 'tcd_classic_editor_gmap',
		'callback' => 'tcdce_gmap_menu_template',

    // add setting
    'setting' => array(
      'option_group' => 'tcdce_gmap_group',
      'option_name' => 'tcdce_gmap',
      'sanitize_callback' => 'tcdce_gmap_setting_validation',
      'default_callback' => 'tcdce_gmap_setting_default'
    )

  );

} );

// 初期値
function tcdce_gmap_setting_default(){

  return array(
    'api' => '',
    'saturation' => 0,
    'marker' => 'default',
    'text' => 'MAP',
    '--tcdce-gmap-image-url' => '',
    '--tcdce-gmap-font-color' => '#ffffff',
    '--tcdce-gmap-bg-color' => '#000000',
  );

}

// バリデーション
function tcdce_gmap_setting_validation( $value ){

  // reset
  if( isset( $value['reset'] ) && $value['reset'] == 1 ){

    update_option( 'tcdce_reset', 1 );
    return tcdce_gmap_setting_default();

  // save
  }else{

    return array(
      'api' => sanitize_text_field( $value['api'] ),
      'saturation' => in_array( $value['saturation'], array( '0', '-100' ), true ) ? $value['saturation'] : '0',
      'marker' => in_array( $value['marker'], array( 'default', 'text', 'image' ), true ) ? $value['marker'] : 'default',
      'text' => sanitize_text_field( $value['text'] ),
      '--tcdce-gmap-image-url' => $value['--tcdce-gmap-image-url'] ? absint( $value['--tcdce-gmap-image-url'] ) : '',
      '--tcdce-gmap-font-color' => sanitize_hex_color( $value['--tcdce-gmap-font-color'] ),
      '--tcdce-gmap-bg-color' => sanitize_hex_color( $value['--tcdce-gmap-bg-color'] )
    );

  }

}

// メニューテンプレート
function tcdce_gmap_menu_template( $submenu ){

?>
  <h1 class="tcdce-page__headline"><?php echo esc_html( $submenu['title'] ); ?></h1>
  <p class="tcdce-page__desc">
    <?php esc_html_e( 'This function allows you to customize and display Google Maps pins and colors.', 'tcd-classic-editor' ); ?><br>
    <b><?php esc_html_e( 'Maps set up here can be used with the "Google Maps" quick tag.', 'tcd-classic-editor' ); ?></b>
  </p>
  <p class="tcdce-page__desc">
    <?php esc_html_e( 'To use this function, you need to obtain an API key for Google Maps Platform.', 'tcd-classic-editor' ); ?><br>
    <a href="https://tcd-theme.com/2018/08/google-maps-platform-api.html" target="_blank" rel="noopener noreferrer"><?php esc_html_e( 'How to get an API key for Google Maps Platform', 'tcd-classic-editor' ); ?></a><br>
  </p>
  <p class="tcdce-page__desc">
    <?php
      /* translators: %1$s: external page links start %2$s: external page links end */
      printf( esc_html__( 'If you do not need to customize the map, you can also embed the map %1$sthis way%2$s.', 'tcd-classic-editor' ), '<a href="https://tcd-theme.com/2021/08/google-map-embed.html" target="_blank" rel="noopener noreferrer">', '</a>' );
    ?>
  </p>
  <div class="tcdce-setting">
    <form id="js-tcdce-form" class="tcdce-setting__form" action="options.php" method="post">
<?php

    settings_fields( $submenu['setting']['option_group'] );

    $base_name = $submenu['setting']['option_name'];
    $tcdce_gmap = get_option( $base_name );

?>
      <div class="tcdce-edit js-tcdce-preview-closest">
<?php

  // preview

?>
        <div class="tcdce-edit__preview tcdce-preview tcdce-body">
          <div class="tcdce-gmap js-tcdce-preview-target">
            <div class="tcdce-gmap-wrapper">
              <div class="tcdce-gmap__embed tcdce-preview--gmap">

                <div class="tcdce-preview--gmap-marker tcdce-preview--gmap-marker--default">
                  <img src="<?php echo esc_url( TCDCE_URL . 'assets/image/gmap-marker.png' ); ?>" alt="" width="26px" height="37px">
                </div>

                <div class="tcdce-preview--gmap-marker tcdce-preview--gmap-marker--text tcdce-gmap__marker">
                  <div class="tcdce-gmap__marker-icon js-tcdce-preview-option--text-target"></div>
                </div>

                <div class="tcdce-preview--gmap-marker tcdce-preview--gmap-marker--image tcdce-gmap__marker">
                  <div class="tcdce-gmap__marker-icon"></div>
                </div>

                <div class="tcdce-preview--gmap-bg" style="background-image:url(<?php echo esc_url( TCDCE_URL . 'assets/image/gmap-bg.jpg' ); ?>);"></div>

              </div>
            </div>
          </div>
        </div>
<?php

  // setting

?>
        <div class="tcdce-edit__options">
<?php

    $tcdce_qt_fields = new TCDCE_Qt_Fields();

    $tcdce_qt_fields->fields( __( 'Preview', 'tcd-classic-editor' ), array(

      array(
        'title' => __( 'API key', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $tcdce_qt_fields->text( $base_name, $tcdce_gmap, 'api' )
      ),
      array(
        'title' => __( 'Map color', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $tcdce_qt_fields->radio( $base_name, $tcdce_gmap, 'saturation', array(
          '0' => __( 'Default', 'tcd-classic-editor' ),
          '-100' => __( 'Monochrome', 'tcd-classic-editor' ),
        ) ),
      ),
      array(
        'title' => __( 'Marker type', 'tcd-classic-editor' ),
        'col' => 1,
        'field' => $tcdce_qt_fields->radio( $base_name, $tcdce_gmap, 'marker', array(
          'default' => TCDCE_ICONS['map_marker'],
          'text' => TCDCE_ICONS['map_text'],
          'image' => TCDCE_ICONS['map_image'],
        ) ),
      ),
      array(
        'title' => __( 'Marker text', 'tcd-classic-editor' ),
        'class' => 'tcdce-gmap-option--text',
        'field' => $tcdce_qt_fields->text( $base_name, $tcdce_gmap, 'text', 'js-tcdce-preview-option js-tcdce-preview-target--text', __( 'e.g.) Store name, etc.', 'tcd-classic-editor' ) )
      ),
      array(
        'title' => __( 'Marker image', 'tcd-classic-editor' ),
        'class' => 'tcdce-gmap-option--image',
        'field' => $tcdce_qt_fields->image( $base_name, $tcdce_gmap, '--tcdce-gmap-image-url' )
      ),

      array(
        'title' => __( 'Font color', 'tcd-classic-editor' ),
        'class' => 'tcdce-gmap-option--text',
        'col' => 2,
        'field' => $tcdce_qt_fields->color( $base_name, $tcdce_gmap, '--tcdce-gmap-font-color' )
      ),
      array(
        'title' => __( 'Background color', 'tcd-classic-editor' ),
        'class' => 'tcdce-gmap-option--text tcdce-gmap-option--image',
        'col' => 2,
        'field' => $tcdce_qt_fields->color( $base_name, $tcdce_gmap, '--tcdce-gmap-bg-color' )
      )

    ) );

    $tcdce_qt_fields->submit();

    $tcdce_qt_fields->reset( $base_name );

?>
        </div>

      </div>

    </form>
  </div>
<?php

}