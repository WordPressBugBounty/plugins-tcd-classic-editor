<?php

/**
 * Start Guide
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'tcdce_top_menu', function(){

?>
<h1 class="tcdce-page__headline">
  <?php esc_html_e( 'Start guide', 'tcd-classic-editor' ); ?>
</h1>
<p class="tcdce-page__desc">
  <?php esc_html_e( 'This plugin is the perfect extension for those who want to use the Classic Editor more conveniently.', 'tcd-classic-editor' ); ?><br>
  <?php esc_html_e( 'The added quick tags can also be utilized in the block editor, so you can work comfortably in both the old and new editing screens.', 'tcd-classic-editor' ); ?><br>
</p>
<p class="tcdce-page__desc">
  <?php esc_html_e( 'By setting up the following four steps in order, you will have an easy-to-use editor that you will like. Please give it a try.', 'tcd-classic-editor' ); ?><br>
</p>
<?php

  $navs = array(
    'tcd_classic_editor_basic' => array(
      'title' => __( 'Basic settings', 'tcd-classic-editor' ),
      'image' => '',
      'desc' => __( 'First, set the base style, such as font size.', 'tcd-classic-editor' ),
    ),
    'tcd_classic_editor_quicktag' => array(
      'title' => __( 'Quicktag', 'tcd-classic-editor' ),
      'image' => '',
      'desc' => __( 'Set up various decorative parts to decorate articles, such as headlines, buttons, markers, etc.', 'tcd-classic-editor' ),
    ),
    'tcd_classic_editor_gmap' => array(
      'title' => __( 'Google Maps', 'tcd-classic-editor' ),
      'image' => '',
      'desc' => __( 'Customized maps can be inserted into articles using the Google Maps API. (API key required)', 'tcd-classic-editor' ),
    ),
    'tcd_classic_editor_toc' => array(
      'title' => __( 'Table of contents', 'tcd-classic-editor' ),
      'image' => '',
      'desc' => __( 'The table of contents is automatically generated according to the headings entered.', 'tcd-classic-editor' ),
    )
  );

?>
<ul class="p-guide-nav">
  <?php foreach( $navs as $nav_key => $nav_obj ){ ?>
  <li class="p-guide-nav__item">
    <div>
      <h2 class="p-guide-nav__item-title">
        <?php echo esc_html( $nav_obj['title'] ); ?>
      </h2>
      <p class="p-guide-nav__item-desc">
        <?php echo esc_html( $nav_obj['desc'] ); ?>
      </p>
    </div>
    <a class="p-guide-nav__item-link" href="<?php echo esc_url( add_query_arg( array( 'page' => $nav_key ), admin_url( 'admin.php' ) ) ); ?>">
      <?php esc_html_e( 'Open Settings', 'tcd-classic-editor' ); ?>
    </a>
  </li>
  <?php } ?>
</ul>


<?php

} );