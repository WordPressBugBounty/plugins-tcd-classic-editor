<?php

/**
 * 店舗情報を表示するウィジェット
 */
if ( ! class_exists( 'TCDCE_Toc_Widget' ) ) {

  // ウィジェット登録
  add_action( 'widgets_init', function(){
    register_widget( 'TCDCE_Toc_Widget' );
  } );

  // ウィジェットクラス
  class TCDCE_Toc_Widget extends WP_Widget {

    /**
     * ウィジェットのセットアップ
     */
    public function __construct() {
      $widget_ops = [
        'description' => __( 'This widget can display a table of contents.', 'tcd-classic-editor' )
      ];
      parent::__construct(
        strtolower( get_class( $this ) ),
        __( 'Table of Contents (tcd ver)', 'tcd-classic-editor' ),
        $widget_ops
      );
    }

    /**
     * ウィジェットの出力
     */
    public function widget( $args, $instance ) {
      global $tcdce_toc;

      // サイドバー非表示なら終了
      if( absint( $tcdce_toc->display ) < 2 ){
        return;
      }

      $output = $tcdce_toc->output;
      if( $tcdce_toc->output ){

        $sticky = ! empty( $instance['sticky'] ) ? 'is-sticky' : '';
        echo wp_kses_post( $args['before_widget'] );
        echo '<div class="p-toc p-toc--sidebar ' . esc_attr( $sticky ) . '">';
        echo  wp_kses_post( $tcdce_toc->output );
        echo '</div>';
        echo wp_kses_post( $args['after_widget'] );
      }

    }

    /**
     * ウィジェットの保存処理
     */
    public function update( $new_instance, $old_instance ) {
      $instance           = $old_instance;
      $instance['sticky'] = ! empty( $new_instance['sticky'] ) ? 1 : 0;
      return $instance;
    }

    /**
     * ウィジェットの設定フォーム
     */
    public function form( $instance ) {
      $sticky   = isset( $instance['sticky'] ) ? (bool) $instance['sticky'] : false;
      $tcdce_toc = get_option( 'tcdce_toc' );

      // 表示設定
      $display = ! empty( $tcdce_toc['display'] ) ? absint( $tcdce_toc['display'] ) : 0;

      // 投稿タイプ
      $toc_post_types = ! empty( $tcdce_toc['post_types'] ) ? array_keys( array_filter( $tcdce_toc['post_types'] ) ) : [];
      $toc_post_type_labels = [];
      if( ! empty( $toc_post_types ) ){
        foreach( $toc_post_types as $post_type_key ){
          $toc_post_type_labels[] = get_post_type_object( $post_type_key )?->label;
        }
      }

      // カウント
      $toc_count = ! empty( $tcdce_toc['count'] ) ? $tcdce_toc['count'] : 2;
      /* translators: %s: target heading count */
      $toc_count_label = sprintf( __( 'Display %s or more headings. Not displayed on mobile.', 'tcd-classic-editor' ), $toc_count );

      // 見出し
      $toc_range = ! empty( $tcdce_toc['range'] ) ? $tcdce_toc['range'] : '2';
      $toc_range_label = match ($toc_range) {
        '2-3' => __( 'h2-h3', 'tcd-classic-editor' ),
        '2-4' => __( 'h2-h4', 'tcd-classic-editor' ),
        '2-5' => __( 'h2-h5', 'tcd-classic-editor' ),
        '2-6' => __( 'h2-h6', 'tcd-classic-editor' ),
        default => __( 'Only h2 tags', 'tcd-classic-editor' )
      };

      ?>
      <?php if( $display > 1 ){ ?>
      <div style="padding:0.1em 1em;background:#f6f6f6;margin-top:1em;">
        <p>
          <?php esc_html_e( 'It will be displayed only if the following conditions are met.', 'tcd-classic-editor' ); ?>
        </p>
        <p>
          <b style="display:block;"><?php esc_html_e( 'Post types', 'tcd-classic-editor' ); ?></b>
          <?php echo esc_html( $toc_post_type_labels ? implode( ' / ', $toc_post_type_labels ) : __( 'No post type to display', 'tcd-classic-editor' ) ); ?>
        </p>
        <p>
          <b style="display:block;"><?php esc_html_e( 'Display conditions', 'tcd-classic-editor' ); ?></b>
          <?php echo esc_html( $toc_count_label ); ?>
        </p>
        <p>
          <b style="display:block;"><?php esc_html_e( 'Target headings', 'tcd-classic-editor' ); ?></b>
          <?php echo esc_html( $toc_range_label ); ?>
        </p>
      </div>
      <p>
        <input type="checkbox" class="checkbox" id="<?php echo esc_attr( $this->get_field_id( 'sticky' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'sticky' ) ); ?>"<?php checked( $sticky ); ?> />
        <label for="<?php echo esc_attr( $this->get_field_id( 'sticky' ) ); ?>"><?php esc_html_e( 'Display the table of contents following the screen.', 'tcd-classic-editor' ); ?></label>
      </p>
      <?php }else{ ?>
        <p>
          <?php esc_html_e( 'Currently, the sidebar is set to not display the table of contents.', 'tcd-classic-editor' ); ?><br>
          <a href="<?php menu_page_url( 'tcd_classic_editor_toc' ); ?>" target="_blank">
            <?php esc_html_e( 'Here is the table of contents setup', 'tcd-classic-editor' ); ?>
          </a>
        </p>
      <?php } ?>
      <?php
    }

  }
}