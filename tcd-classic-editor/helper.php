<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * custom esc_attr filter
 */
function tcdce_custom_attribute_escape( $safe_text, $text ){
  $safe_text = str_replace( "&#039;", "'" , $safe_text );
  return $safe_text;
}

/**
 * custom allowed html
 */
function tcdce_kses_allowed_html( $allowedtags, $context ) {
  if( $context == 'tcdce' ){
    global $allowedposttags;
    $allowedtags = $allowedposttags;
    $allowedtags['textarea']['placeholder'] = true;
    $allowedtags['select'] = array(
      'class' => true,
      'name' => true,
      'data-property' => true
    );
    $allowedtags['option'] = array(
      'value' => true,
      'selected' => true
    );
    $allowedtags['input'] = array(
      'type' => true,
      'name' => true,
      'id' => true,
      'value' => true,
      'class' => true,
      'style' => true,
      'placeholder' => true,
      'checked' => true,
      'disabled' => true,
      'readonly' => true,
      'required' => true,
      'autocomplete' => true,
      'autofocus' => true,
      'min' => true,
      'max' => true,
      'data-property' => true
    );
    $allowedtags['svg'] = array(
      'xmlns' => true,
      'width' => true,
      'height' => true,
      'viewbox' => true,
      'fill' => true
    );
    $allowedtags['path'] = array(
      'd' => true,
      'fill' => true,
      'stroke' => true,
      'stroke-width' => true
    );
    $allowedtags['circle'] = array(
      'cx' => true,
      'cy' => true,
      'r' => true,
      'fill' => true
    );
    $allowedtags['rect'] = array(
      'x' => true,
      'y' => true,
      'width' => true,
      'height' => true,
      'fill' => true
    );
    $allowedtags['line'] = array(
      'x1' => true,
      'x2' => true,
      'y1' => true,
      'y2' => true,
      'stroke' => true,
      'stroke-width' => true
    );
    $allowedtags['g'] = array(
      'transform' => true
    );

  }
  return $allowedtags;
}
add_filter( 'wp_kses_allowed_html', 'tcdce_kses_allowed_html', 999, 2 );


/**
 * Enabling the Classic Editor in Patterns
 */
function tcdce_redirect_site_editor_patterns_to_classic() {
  global $pagenow;

  // リダイレクト先
  $redirect_url = admin_url( 'edit.php?post_type=wp_block' );

  // サイトエディターのページじゃなければ終了
  if( $pagenow !== 'site-editor.php' ){
    return;
  }

  // WP6.7対応 "?path=/patterns" ならリダイレクト
  $path = isset( $_GET['path'] ) ? sanitize_key( wp_unslash( $_GET['path'] ) ) : '';
  if ( $path === 'patterns' ) {
    wp_safe_redirect( $redirect_url );
    exit;
  }

  // WP6.8対応 "?p=/pattern" ならリダイレクト
  $p = isset( $_GET['p'] ) ? sanitize_key( wp_unslash( $_GET['p'] ) ) : '';
  if ( $p === 'pattern' ) {
    wp_safe_redirect( $redirect_url );
    exit;
  }

}
add_action( 'admin_init', 'tcdce_redirect_site_editor_patterns_to_classic' );


/**
 * インポート / エクスポート ボタン
 */
function tcdce_import_export_view( $option = 'tcdce_quicktag' ){
  global $plugin_page;
?>
<div class="tcdce-setting-data">
  <label class="tcdce-setting-data-action">
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M460-249.23h40V-437l84 84 28.31-28.54L480-513.85 347.69-381.54l28.54 28.31L460-437v187.77ZM264.62-120q-27.62 0-46.12-18.5Q200-157 200-184.62v-590.76q0-27.62 18.5-46.12Q237-840 264.62-840H580l180 180v475.38q0 27.62-18.5 46.12Q723-120 695.38-120H264.62ZM560-640v-160H264.62q-9.24 0-16.93 7.69-7.69 7.69-7.69 16.93v590.76q0 9.24 7.69 16.93 7.69 7.69 16.93 7.69h430.76q9.24 0 16.93-7.69 7.69-7.69 7.69-16.93V-640H560ZM240-800v160-160 640-640Z"/></svg>
    <input class="js-tcdce-setting-data-form-input" type="checkbox" style="display:none;">
    <?php esc_html_e( 'Import', 'tcd-classic-editor' ); ?>
  </label>
  <div class="js-tcdce-setting-data-form tcdce-setting-data-form">
    <?php esc_html_e( 'Import exported data to restore settings.', 'tcd-classic-editor' ); ?>
    <form method="post" enctype="multipart/form-data" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
      <?php wp_nonce_field( 'tcdce_import' ); ?>
      <input type="hidden" name="action" value="tcdce_import_action" />
      <input type="hidden" name="setting" value="<?php echo esc_attr( $option ); ?>" />
      <input type="hidden" name="base_url" value="<?php echo esc_url( menu_page_url( $plugin_page, false ) ); ?>" />
      <input type="file" name="tcdce_import_file" accept=".json" onchange="this.setAttribute('data-file',this.files.length)" />
      <input type="submit" class="tcdce-setting-data-form-button" value="<?php esc_html_e( 'Import', 'tcd-classic-editor' ); ?>" />
    </form>
  </div>
  <label class="tcdce-setting-data-action">
    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="currentColor"><path d="M720-148.46 851.54-280 824-307.54l-84 84V-419h-40v195.46l-84-84L588.46-280 720-148.46ZM580-20v-40h280v40H580ZM244.62-180q-26.08 0-45.35-19.27Q180-218.54 180-244.62v-550.76q0-26.08 19.27-45.35Q218.54-860 244.62-860H520l220 220v125.62h-40V-620H500v-200H244.62q-9.24 0-16.93 7.69-7.69 7.69-7.69 16.93v550.76q0 9.24 7.69 16.93 7.69 7.69 16.93 7.69h240v40h-240ZM220-220v-600 600Z"/></svg>
    <input class="js-tcdce-setting-data-form-input" type="checkbox" style="display:none;">
    <?php esc_html_e( 'Export', 'tcd-classic-editor' ); ?>
  </label>
  <div class="js-tcdce-setting-data-form tcdce-setting-data-form">
    <?php esc_html_e( 'Download the current configuration as a JSON file.', 'tcd-classic-editor' ); ?>
    <form method="get" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>">
      <?php wp_nonce_field( 'tcdce_export' ); ?>
      <input type="hidden" name="action" value="tcdce_export_action" />
      <input type="hidden" name="setting" value="<?php echo esc_attr( $option ); ?>" />
      <input type="submit" class="tcdce-setting-data-form-button" value="<?php esc_html_e( 'Export', 'tcd-classic-editor' ); ?>" />
    </form>
  </div>
</div>
<?php
}


/**
 * インポート処理
 */
add_action( 'admin_post_tcdce_import_action', 'tcdce_import_action' );
function tcdce_import_action() {

  // nonce認証
  check_admin_referer( 'tcdce_import' );

  // 設定ページのリンクがなければ終了
  $setting = sanitize_text_field( wp_unslash( $_POST['setting'] ?? '' ) );
  $setting_page_url =sanitize_text_field( wp_unslash( $_POST['base_url'] ?? '' ) );
  if( ! $setting || ! $setting_page_url ){
    wp_die( esc_html__( 'Incorrect setting item.', 'tcd-classic-editor' ) );
  }

  // ファイルがアップロードされていなければ終了
  if ( empty( $_FILES['tcdce_import_file']['tmp_name'] ) ) {
    wp_die(
      esc_html__( 'File has not been uploaded.', 'tcd-classic-editor' ),
      'Import error',
      [ 'back_link' => true ]
    );
  }

  // ファイルの中身を読み取り
  $uploaded_file_path = $_FILES['tcdce_import_file']['tmp_name'];
  $json_string = file_get_contents( $uploaded_file_path );
  if (! $json_string) {
    wp_die(
      esc_html__( 'Failed to load uploaded file. The file may be corrupted.', 'tcd-classic-editor' ),
      'Import error',
      ['back_link' => true]
    );
  }

  // JSON のパース
  try {
    $decoded = json_decode( $json_string, true, 512, JSON_THROW_ON_ERROR );
  } catch (\JsonException $e) {
    // JSONのパースに失敗した場合
    wp_die(
      esc_html__( 'JSON parsing failed. Please upload a file in the correct format.', 'tcd-classic-editor' ),
      'Import error',
      ['back_link' => true]
    );
  }

  // パース結果が配列かどうかチェック
  if ( ! is_array( $decoded ) ) {
    wp_die(
      esc_html__( 'This is not expected data. Please upload the corresponding JSON format file.', 'tcd-classic-editor' ),
      'Import error',
      ['back_link' => true]
    );
  }

  // オプションを上書き
  update_option( $setting, $decoded );

  // 終了後にリダイレクト
  wp_safe_redirect( add_query_arg( [ 'imported' => 1 ], $setting_page_url ) );
  exit;
}


/**
 * エクスポート処理
 */
add_action( 'admin_post_tcdce_export_action', 'tcdce_export_action' );
function tcdce_export_action() {

  // nonce認証
  check_admin_referer( 'tcdce_export' );

  // optionの設定キー
  $setting = sanitize_text_field( wp_unslash( $_GET['setting'] ?? '' ) );
  if( ! $setting ){
    wp_die( esc_html__( 'Incorrect setting item.', 'tcd-classic-editor' ) );
  }

  // 現在のオプションを取得
  $option_data = get_option( $setting );

  // 日付文字列 (例: 20250218)
  $date_str = wp_date( 'Ymd' );

  // ダウンロードファイル名を組み立て
  $filename = "{$setting}-{$date_str}.json";

  // ダウンロード用のヘッダーを送信
  header( 'Content-Type: application/json; charset=utf-8' );
  header( "Content-Disposition: attachment; filename=\"{$filename}\"" );
  header( 'Cache-Control: no-cache, must-revalidate' );
  header( 'Expires: 0' );

  // JSON化して出力
  echo wp_json_encode(
    $option_data,
    JSON_THROW_ON_ERROR | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
  );
  exit;
}
