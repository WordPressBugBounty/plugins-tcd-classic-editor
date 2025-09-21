<?php

/**
 * クイックタグ設定のUI
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'TCDCE_Qt_Fields' ) ) {
  class TCDCE_Qt_Fields {

    /**
		 * Quicktag name.
		 *
		 * @var string
		 */
    public $name = '';

    /**
		 * Quicktag label.
		 *
		 * @var array
		 */
    public $label = array();

    /**
		 * Preview html.
		 *
		 * @var array
		 */
    public $preview = array();

    /**
		 * Default settings.
		 *
		 * @var array
		 */
    public $default = array();

    /**
		 * Preset.
		 *
		 * @var array
		 */
    public $preset = array();

    /**
     * Constructor.
     */
    public function __construct() {

      // 各種値をセット
      do_action( 'tcdce_qt_fields_set_properties', $this );

      // ajax登録
      add_action( 'wp_ajax_tcdce_repeater_add', array( $this, 'repeater_add' ) );

    }

    /**
     * Get label.
     */
    public function get_label( $key = null ) {
      if( $key !== null && array_key_exists( $key, $this->label ) ){
        return $this->label[$key];
      }else{
        return $this->label;
      }
    }

    /**
     * Set label.
     */
    public function set_label( $key, $value ) {
      $this->label[$key] = $value;
    }

    /**
     * Get preview.
     */
    public function get_preview( $key = null ) {
      if( $key !== null && array_key_exists( $key, $this->preview ) ){
        return $this->preview[$key];
      }else{
        return $this->preview;
      }
    }

    /**
     * Set preview.
     */
    public function set_preview( $key, $value ) {
      $this->preview[$key] = $value;
    }

    /**
     * Get default.
     */
    public function get_default( $key = null ) {
      if( $key !== null && array_key_exists( $key, $this->default ) ){
        return $this->default[$key];
      }else{
        return $this->default;
      }
    }

    /**
     * Set default.
     */
    public function set_default( $key, $value ) {
      $this->default[$key] = $value;
    }

    /**
     * Get preset.
     */
    public function get_preset( $key = null ) {
      if( $key !== null && array_key_exists( $key, $this->preset ) ){
        return $this->preset[$key];
      }else{
        return $this->preset;
      }
    }

    /**
     * Set preset.
     */
    public function set_preset( $key, $value ) {
      $this->preset[$key] = $value;
    }

    /**
     * Main fields.
     */
    public function fields( $title, $fields = array(), $title_class = '', $fields_class = "" ) {
      echo '<h3 class="tcdce-edit__options-title ' . esc_attr( $title_class ) . '">' . esc_html( $title ) . '</h3>';
      echo '<div class="tcdce-edit__options-field ' . esc_attr( $fields_class ) . '">';
      foreach( $fields as $field ){
        $field = wp_parse_args( $field, array(
          'title' => '',
          'class' => '',
          'col' => 1,
          'field' => '',
        ) );
        echo '<div class="tcdce-edit__options-field__item ' . esc_attr( $field['class'] ) . '" data-col="' . esc_attr( $field['col'] ) . '">';
        if( $field['title'] ){
          echo '<span class="tcdce-edit__options-name">' . esc_html( $field['title'] ) . '</span>';
        }
        echo wp_kses( $field['field'], wp_kses_allowed_html( 'tcdce' ) );
        echo '</div>';
      }
      echo '</div>';
    }

    /**
     * Submit button.
     */
    public function submit( $class = 'tcdce-submit-wrapper' ) {
      echo '<div class="' . esc_attr( $class ) . '">';
      echo '<button class="tcdce-submit js-tcdce-form-submit" type="button">';
      esc_html_e( 'Save Changes', 'tcd-classic-editor' );
      echo '</button>';
      echo '</div>';
    }

    /**
     * Reset button.
     */
    public function reset( $base_name, $class = '' ) {
      echo '<div class="tcdce-base-fields__reset">';
      echo '<span class="tcdce-base-fields__reset-action js-tcdce-reset">' . esc_html__( 'Reset settings', 'tcd-classic-editor' ) . '</span>';
      echo '<div class="tcdce-base-fields__reset-content">';
      echo '<div class="tcdce-base-fields__reset-inner">';
      echo '<p class="tcdce-base-fields__reset-desc">';
      echo esc_html__( 'Initialize settings.', 'tcd-classic-editor' ) . '<br>' . esc_html__( 'Please note that all current settings will be deleted.', 'tcd-classic-editor' );
      echo '</p>';
      echo '<button id="js-tcdce-reset-button" class="tcdce-base-fields__reset-button" type="button" name="' . esc_attr( $base_name ) .'[reset]" value="1">' . esc_html__( 'Reset settings', 'tcd-classic-editor' ) . '</button>';
      echo '</div>';
      echo '</div>';
      echo '</div>';
    }

    /**
     * Note.
     */
    public function note( $text ) {
      if ( is_array( $text ) ) {
        $text = implode( "\n", $text );
      }
      $output = '<div class="tcdce-note">';
      $output .= nl2br( wp_kses( $text, wp_kses_allowed_html( 'tcdce' ) ) );
      $output .= '</div>';
      return $output;
    }

    /**
     * Text field.
     */
    public function text( $base_name, $base_value, $property, $class = '', $placeholder = '' ) {
      $output = '<div class="tcdce-text">';
      $output .= '<input
                  class="tcdce-text__input ' . esc_attr( $class ) . '"
                  type="text"
                  name="' . esc_attr( $base_name . '[' . $property . ']' ) . '"
                  value="' . esc_attr( $base_value[$property] ?? '' ) . '"
                  placeholder="' . esc_attr( $placeholder ) . '"
                  data-property="' . esc_attr( $property ) . '"
                >';
      if( str_contains( $class, 'js-tcdce-empty-validation' ) ){
        $output .= '<div class="tcdce-text__input-error">' . __( 'This field is required.', 'tcd-classic-editor' ) . '</div>';
      }
      $output .= '</div>';
      return $output;
    }

    /**
     * Shortcode.
     */
    public function shortcode( $value ) {
      return
      '<div class="tcdce-text">
        <input
          class="tcdce-text__input"
          type="text"
          value="' . esc_attr( $value ) . '"
          readonly
        >
      </div>';
    }

    /**
     * textarea field.
     */
    public function textarea( $base_name, $base_value, $property, $rows = 5, $class = '', $placeholder = '' ) {
      return
      '<div class="tcdce-textarea">
        <textarea
          class="tcdce-textarea__value ' . esc_attr( $class ) . '"
          name="' . esc_attr( $base_name . '[' . $property . ']' ) . '"
          rows="' . esc_attr( $rows ) . '"
          placeholder="' . esc_attr( $placeholder ) . '"
        >' .
        $base_value[$property] .
        '</textarea>
      </div>';
    }

    /**
     * hidden fields.
     */
    public function hiddens( $base_name, $base_value, $properties ) {
      foreach( $properties as $property ){
        echo
        '<input
          class="js-tcdce-preview-option"
          type="hidden"
          name="' . esc_attr( $base_name ) . '[' . esc_attr( $property ) . ']"
          value="' . esc_attr( $base_value[$property] ) . '"
          data-property="' . esc_attr( $property ) . '"
        >';
      }
    }

    /**
     * Number field.
     */
    public function number( $base_name, $base_value, $properties ) {
      $output = '<div class="tcdce-number">';
      foreach( $properties as $property => $property_info ){
        $output .= '<label class="tcdce-number__label">';
        if( isset( $property_info['icon'] ) && $property_info['icon'] ){
          $output .= '<span class="tcdce-number__icon">' . $property_info['icon'] . '</span>';
        }
        $output .= '<input
          class="tcdce-number__input js-tcdce-preview-option"
          type="number"
          name="' . esc_attr( $base_name . '[' . $property . ']' ) . '"
          value="' . esc_attr( $base_value[$property] ?? 0 ) . '"
          min="0"
          max="999"
          data-property="' . esc_attr( $property ) . '"
          placeholder="' . $property_info['default'] . '"
        />';
        $output .= '<span class="tcdce-number__unit">px</span>';
        $output .= '</label>';
      }
      $output .= '</div>';
      return $output;
    }

    /**
     * Color picker.
     */
    public function color( $base_name, $base_value, $property ) {
      return
      '<div class="js-tcdce-color-picker tcdce-color">
        <input
          class="js-tcdce-color-picker--value tcdce-color__input js-tcdce-preview-option"
          type="text"
          name="' . esc_attr( $base_name . '[' . $property . ']' ) . '"
          value="' . esc_attr( $base_value[$property] ?? '' ) . '"
          data-property="' . esc_attr( $property ) . '"
        >
      </div>';
    }

    /**
     * toggle button.
     */
    public function toggle( $name, $value, $property, $label ) {
      $output = '<div class="tcdce-toggle-wrapper">';
      $output .= '<label class="tcdce-toggle">';
      $output .= '<input type="hidden" name="' . esc_attr( $name . '[' . $property . ']' ) . '" value="0">';
      $output .= '<input class="tcdce-toggle__input" type="checkbox" name="' . esc_attr( $name . '[' . $property . ']' ) . '"' . checked( 1, esc_attr( $value[$property] ?? 0 ), false ) . ' value="1">';
      $output .= '<span class="tcdce-toggle__button"></span>';
      $output .= '<span class="tcdce-toggle__label">' . esc_html( $label ) . '</span>';
      $output .= '</label>';
      $output .= '</div>' . "\n";
      return $output;
    }

    /**
     * Radio button.
     */
    public function radio( $name, $value, $property, $options ) {
      $output = '<div class="tcdce-radio">';
      foreach( $options as $key => $icon ){
        $output .= '<label class="tcdce-radio__label">';
        $output .=
          '<input ' .
            'class="tcdce-radio__input js-tcdce-preview-option--radio" ' .
            'type="radio" ' .
            'name="' . esc_attr( $name . '[' . $property . ']' ) . '" ' .
            'value="' . esc_attr( $key ) . '" ' .
            checked( $key, esc_attr( $value[$property] ?? array_key_first( $options ) ), false ) .
            ' data-property="' . esc_attr( $property ) . '"
          >';
        $output .= '<span class="tcdce-radio__icon">' . $icon . '</span>';
        $output .= '</label>';
      }
      $output .= '</div>';
      return $output;
    }

    /**
     * Radio icon button.
     */
    public function radio_icon( $name, $value, $property, $options = array() ) {

      $options = wp_parse_args(
        $options,
        array(
          'var(--tcdce-opt-icon--info)' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="1em" height="1em" fill="currentColor"><path d="M480.01-290q12.76 0 21.37-8.63Q510-307.25 510-320v-170q0-12.75-8.63-21.38-8.63-8.62-21.38-8.62-12.76 0-21.37 8.62Q450-502.75 450-490v170q0 12.75 8.63 21.37 8.63 8.63 21.38 8.63ZM480-588.46q13.73 0 23.02-9.29t9.29-23.02q0-13.73-9.29-23.02-9.29-9.28-23.02-9.28t-23.02 9.28q-9.29 9.29-9.29 23.02t9.29 23.02q9.29 9.29 23.02 9.29Zm.07 488.46q-78.84 0-148.21-29.92t-120.68-81.21q-51.31-51.29-81.25-120.63Q100-401.1 100-479.93q0-78.84 29.92-148.21t81.21-120.68q51.29-51.31 120.63-81.25Q401.1-860 479.93-860q78.84 0 148.21 29.92t120.68 81.21q51.31 51.29 81.25 120.63Q860-558.9 860-480.07q0 78.84-29.92 148.21t-81.21 120.68q-51.29 51.31-120.63 81.25Q558.9-100 480.07-100Zm-.07-60q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>',
          'var(--tcdce-opt-icon--help)' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="1em" height="1em" fill="currentColor"><path d="M479.56-255.39q17.13 0 28.94-11.82 11.81-11.83 11.81-28.97 0-17.13-11.83-28.94-11.83-11.8-28.96-11.8-17.13 0-28.94 11.83-11.81 11.83-11.81 28.96 0 17.13 11.83 28.94 11.83 11.8 28.96 11.8Zm.51 155.39q-78.84 0-148.21-29.92t-120.68-81.21q-51.31-51.29-81.25-120.63Q100-401.1 100-479.93q0-78.84 29.92-148.21t81.21-120.68q51.29-51.31 120.63-81.25Q401.1-860 479.93-860q78.84 0 148.21 29.92t120.68 81.21q51.31 51.29 81.25 120.63Q860-558.9 860-480.07q0 78.84-29.92 148.21t-81.21 120.68q-51.29 51.31-120.63 81.25Q558.9-100 480.07-100Zm-.07-60q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Zm3.24-171.23q27.68 0 47.91 17.43 20.24 17.43 20.24 43.57 0 22-12.93 39.38-12.92 17.39-29.54 32.39-21.78 19.32-38.35 42.51-16.57 23.18-17.72 51.64-.39 10.93 7.69 18.31 8.08 7.38 18.84 7.38 11.54 0 19.54-7.69t10.23-18.84q4-20.62 17.04-36.73 13.04-16.12 28.25-30.65 21.87-21.32 38.17-46.48 16.31-25.17 16.31-56.14 0-47.54-37.46-78.12Q534-703.84 484-703.84q-35.69 0-67.31 15.8-31.61 15.81-49.23 46.12-5.46 9.31-3.5 19.59 1.95 10.29 10.55 15.62 10.95 6.09 22.49 3.48 11.54-2.62 19.61-13.15 12.16-15.77 29.43-25.31t37.2-9.54Z"/></svg>',
          'var(--tcdce-opt-icon--pen)' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="1em" height="1em" fill="currentColor"><path d="M200-200h50.46l409.46-409.46-50.46-50.46L200-250.46V-200Zm-23.84 60q-15.37 0-25.76-10.4-10.4-10.39-10.4-25.76v-69.3q0-14.63 5.62-27.89 5.61-13.26 15.46-23.11l506.54-506.31q9.07-8.24 20.03-12.73 10.97-4.5 23-4.5t23.3 4.27q11.28 4.27 19.97 13.58l48.85 49.46q9.31 8.69 13.27 20 3.96 11.31 3.96 22.62 0 12.07-4.12 23.03-4.12 10.97-13.11 20.04L296.46-161.08q-9.85 9.85-23.11 15.46-13.26 5.62-27.89 5.62h-69.3Zm584.22-570.15-50.23-50.23 50.23 50.23Zm-126.13 75.9-24.79-25.67 50.46 50.46-25.67-24.79Z"/></svg>',
          'var(--tcdce-opt-icon--bulb)' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="1em" height="1em" fill="currentColor"><path d="M480-96.92q-30.31 0-52.27-21-21.96-21-23.88-51.31h152.3q-1.92 30.31-23.88 51.31-21.96 21-52.27 21Zm-120-127.7q-12.77 0-21.38-8.61-8.62-8.62-8.62-21.39 0-12.77 8.62-21.38 8.61-8.62 21.38-8.62h240q12.77 0 21.38 8.62 8.62 8.61 8.62 21.38t-8.62 21.39q-8.61 8.61-21.38 8.61H360ZM336.15-340q-62.84-39.08-99.49-102.12Q200-505.15 200-580q0-116.92 81.54-198.46T480-860q116.92 0 198.46 81.54T760-580q0 74.85-36.66 137.88-36.65 63.04-99.49 102.12h-287.7ZM354-400h252q45-32 69.5-79T700-580q0-92-64-156t-156-64q-92 0-156 64t-64 156q0 54 24.5 101t69.5 79Zm126 0Z"/></svg>',
          'var(--tcdce-opt-icon--warn)' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="1em" height="1em" fill="currentColor"><path d="M480-290.77q13.73 0 23.02-9.29t9.29-23.02q0-13.73-9.29-23.02-9.29-9.28-23.02-9.28t-23.02 9.28q-9.29 9.29-9.29 23.02t9.29 23.02q9.29 9.29 23.02 9.29Zm.01-146.15q12.76 0 21.37-8.63 8.62-8.62 8.62-21.37v-180q0-12.75-8.63-21.38-8.63-8.62-21.38-8.62-12.76 0-21.37 8.62-8.62 8.63-8.62 21.38v180q0 12.75 8.63 21.37 8.63 8.63 21.38 8.63Zm.06 336.92q-78.84 0-148.21-29.92t-120.68-81.21q-51.31-51.29-81.25-120.63Q100-401.1 100-479.93q0-78.84 29.92-148.21t81.21-120.68q51.29-51.31 120.63-81.25Q401.1-860 479.93-860q78.84 0 148.21 29.92t120.68 81.21q51.31 51.29 81.25 120.63Q860-558.9 860-480.07q0 78.84-29.92 148.21t-81.21 120.68q-51.29 51.31-120.63 81.25Q558.9-100 480.07-100Zm-.07-60q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>',
          'var(--tcdce-opt-icon--good)' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="1em" height="1em" fill="currentColor"><path d="M827.69-620q28.54 0 50.42 21.89Q900-576.23 900-547.69v64.61q0 6.3-1.62 13.5-1.61 7.19-3.61 13.43l-114.64 270.5q-8.59 19.19-28.82 32.42T709.09-140H288.46v-480l232.69-230.69q11.93-11.92 27.62-14.23 15.69-2.31 30.07 5.38 14.39 7.7 21.08 21.85 6.69 14.15 2.85 29.31L559.69-620h268Zm-479.23 25.54V-200h360.77q4.23 0 8.65-2.31 4.43-2.31 6.74-7.69L840-480v-67.69q0-5.39-3.46-8.85t-8.85-3.46H483.85L534-779.23 348.46-594.46ZM172.31-140q-29.83 0-51.07-21.24Q100-182.48 100-212.31v-335.38q0-29.83 21.24-51.07Q142.48-620 172.31-620h116.15v60H172.31q-5.39 0-8.85 3.46t-3.46 8.85v335.38q0 5.39 3.46 8.85t8.85 3.46h116.15v60H172.31Zm176.15-60v-394.46V-200Z"/></svg>',
          'var(--tcdce-opt-icon--bad)' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="1em" height="1em" fill="currentColor"><path d="M132.31-340q-28.54 0-50.42-21.89Q60-383.77 60-412.31v-64.61q0-6.3 1.62-13.5 1.61-7.19 3.61-13.43l114.64-270.5q8.59-19.19 28.82-32.42T250.91-820h420.63v480L438.85-109.31q-11.93 11.92-27.62 14.23-15.69 2.31-30.07-5.38-14.39-7.7-21.08-21.85-6.69-14.15-2.85-29.31L400.31-340h-268Zm479.23-25.54V-760H250.77q-4.23 0-8.65 2.31-4.43 2.31-6.74 7.69L120-480v67.69q0 5.39 3.46 8.85t8.85 3.46h343.84L426-180.77l185.54-184.77ZM787.69-820q29.83 0 51.07 21.24Q860-777.52 860-747.69v335.38q0 29.83-21.24 51.07Q817.52-340 787.69-340H671.54v-60h116.15q5.39 0 8.85-3.46t3.46-8.85v-335.38q0-5.39-3.46-8.85t-8.85-3.46H671.54v-60h116.15Zm-176.15 60v394.46V-760Z"/></svg>',
          'var(--tcdce-opt-icon--check)' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="1em" height="1em" fill="currentColor"><path d="m382-339.38 345.54-345.54q8.92-8.93 20.88-9.12 11.96-.19 21.27 9.12 9.31 9.31 9.31 21.38 0 12.08-9.31 21.39l-362.38 363q-10.85 10.84-25.31 10.84-14.46 0-25.31-10.84l-167-167q-8.92-8.93-8.8-21.2.11-12.26 9.42-21.57t21.38-9.31q12.08 0 21.39 9.31L382-339.38Z"/></svg>',
          'var(--tcdce-opt-icon--circle)' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="1em" height="1em" fill="currentColor"><path d="M480.07-100q-78.84 0-148.21-29.92t-120.68-81.21q-51.31-51.29-81.25-120.63Q100-401.1 100-479.93q0-78.84 29.92-148.21t81.21-120.68q51.29-51.31 120.63-81.25Q401.1-860 479.93-860q78.84 0 148.21 29.92t120.68 81.21q51.31 51.29 81.25 120.63Q860-558.9 860-480.07q0 78.84-29.92 148.21t-81.21 120.68q-51.29 51.31-120.63 81.25Q558.9-100 480.07-100Zm-.07-60q134 0 227-93t93-227q0-134-93-227t-227-93q-134 0-227 93t-93 227q0 134 93 227t227 93Zm0-320Z"/></svg>',
          'var(--tcdce-opt-icon--cross)' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960" width="1em" height="1em" fill="currentColor"><path d="M480-437.85 277.08-234.92q-8.31 8.3-20.89 8.5-12.57.19-21.27-8.5-8.69-8.7-8.69-21.08 0-12.38 8.69-21.08L437.85-480 234.92-682.92q-8.3-8.31-8.5-20.89-.19-12.57 8.5-21.27 8.7-8.69 21.08-8.69 12.38 0 21.08 8.69L480-522.15l202.92-202.93q8.31-8.3 20.89-8.5 12.57-.19 21.27 8.5 8.69 8.7 8.69 21.08 0 12.38-8.69 21.08L522.15-480l202.93 202.92q8.3 8.31 8.5 20.89.19 12.57-8.5 21.27-8.7 8.69-21.08 8.69-12.38 0-21.08-8.69L480-437.85Z"/></svg>',
        )
      );

      $output = '<div class="tcdce-radio-icon">';
      foreach( $options as $key => $icon ){
        $output .= '<label class="tcdce-radio-icon__label">';
        $output .=
          '<input ' .
            'class="tcdce-radio__input js-tcdce-preview-option--radio" ' .
            'type="radio" ' .
            'name="' . esc_attr( $name . '[' . $property . ']' ) . '" ' .
            'value="' . esc_attr( $key ) . '" ' .
            checked( $key, esc_attr( $value[$property] ?? array_key_first( $options ) ), false ) .
            ' data-property="' . esc_attr( $property ) . '"
          >';
        $output .= $icon;
        $output .= '</label>';
      }
      $output .= '</div>';
      return $output;
    }

    /**
     * Select box.
     */
    public function select( $name, $value, $property, $options ) {
      $output = '<div class="tcdce-select">';
      $output .= '<select class="tcdce-select-box js-tcdce-preview-option" name="' . esc_attr( $name . '[' . $property . ']' ) . '" data-property="' . esc_attr( $property ) . '">';
      foreach( $options as $key => $label ){
        $output .= '<option
          value="' . esc_attr( $key ) . '"' .
          selected( $key, esc_attr( $value[$property] ?? array_key_first( $options ) ), false ) .
          '>' . esc_html( $label ) . '</option>';
      }
      $output .= '</select>';
      $output .= '</div>';
      return $output;
    }

    /**
     * Image uploader.
     */
    public function image( $base_name, $base_value, $property ) {
      $image_url = wp_get_attachment_url( $base_value[$property] ?? '' ) ? 'url(' . wp_get_attachment_url( $base_value[$property] ?? '' ) . ')' : '';
      return
      '<div class="tcdce-image js-tcdce-image-closest">
        <input
          class="tcdce-image__value js-tcdce-image-id"
          type="text"
          name="' . esc_attr( $base_name . '[' . $property . ']' ) . '"
          value="' . esc_attr( $base_value[$property] ?? '' ) . '"
          placeholder=" "
        >
        <input class="js-tcdce-preview-option"
          type="hidden"
          value="' . esc_attr( $image_url ) . '"
          data-property="' . esc_attr( $property ) . '"
        >
        <div class="tcdce-image__input js-tcdce-image">' .
        '<span class="tcdce-image__input-icon">' . TCDCE_ICONS['add_image'] . '</span>' . __( 'Add image', 'tcd-classic-editor' ) .
        '</div>
        <div class="tcdce-image__uploaded">
          <span class="tcdce-image__delete js-tcdce-image-delete">' . TCDCE_ICONS['close'] . '</span>
          <div class="tcdce-image__uploaded-image js-tcdce-image-preview" style="background-image:' . $image_url . ';"></div>
          <span class="tcdce-image__change js-tcdce-image">' . __( 'Change image', 'tcd-classic-editor' ) . '</span>
        </div>
      </div>';
    }

    /**
     * Preset field.
     */
    public function preset( $name, $value, $item_type, $preset = '' ) {
      return
      '<div class="tcdce-text js-tcdce-preset-open" data-preset-type="' . esc_attr( $item_type ) . '">
        <input
          class="js-tcdce-preset-target-value"
          type="hidden" name="' . esc_attr( $name ) . '"
          value="' . esc_attr( $value ) . '"
        >
        <input
          class="tcdce-text__input tcdce-preset js-tcdce-preset-target-label"
          type="text"
          value="' . esc_attr( $this->preset[$item_type][$value]['label'] ) . '"
          placeholder="' . __( 'Select Preset', 'tcd-classic-editor' ) . '"
          readonly
        >
      </div>';
    }

    /**
     * Repeater.
     */
    public function repeater( $item_type, $key, $base_name, $base_value ) {
?>
<div class="tcdce-repeater__item js-tcdce-repeater-item" data-key="<?php echo esc_attr( $key ); ?>">
  <?php $this->repeater_title( $item_type, $base_name, $base_value ); ?>
  <div class="tcdce-repeater__item-content">
    <input type="hidden" name="<?php echo esc_attr( $base_name . '[item]' ); ?>" value="<?php echo esc_attr( $item_type ); ?>">
<?php

    // setting
    do_action( "tcdce_qt_fields_repeater_options_{$item_type}", $this, $base_name, $base_value, $key );

    // preview
    if( $this->preview[$item_type] ){

?>
    <div class="tcdce-edit js-tcdce-preview-closest">
      <div class="tcdce-edit__preview">
        <?php // pc/sp switch button ?>
        <div class="tcdce-edit__preview-switch" data-device="pc" data-guide="off">
          <span class="tcdce-edit__preview-switch-item js-tcdce-preview-switch" data-type="pc">
            <?php echo wp_kses( TCDCE_ICONS['pc'], wp_kses_allowed_html( 'tcdce' ) ); ?>
          </span>
          <span class="tcdce-edit__preview-switch-item js-tcdce-preview-switch" data-type="sp">
            <?php echo wp_kses( TCDCE_ICONS['sp'], wp_kses_allowed_html( 'tcdce' ) ); ?>
          </span>
          <span class="tcdce-edit__preview-guide js-tcdce-preview-guide"></span>
        </div>
        <?php // preview body ?>
        <div class="tcdce-edit__preview-content tcdce-body">
          <?php echo wp_kses( $this->preview[$item_type], wp_kses_allowed_html( 'tcdce' ) ); ?>
        </div>
      </div>
      <div class="tcdce-edit__options">
        <?php do_action( "tcdce_qt_fields_repeater_preview_options_{$item_type}", $this, $base_name, $base_value ); ?>
      </div>
    </div>
<?php

    }

?>
  </div>
</div>
<?php
    }


    /**
     * Repeater title.
     */
    public function repeater_title( $item_type, $base_name, $base_value ) {
?>
  <div class="tcdce-repeater__item-title js-tcdce-repeater-title">
    <span class="tcdce-repeater__item-title__handle js-tcdce-repeater-sortable-handle">
      <?php echo wp_kses( TCDCE_ICONS['handle'], wp_kses_allowed_html( 'tcdce' ) ); ?>
    </span>
    <label class="tcdce-repeater__item-title__toggle tcdce-toggle">
      <input type="hidden" name="<?php echo esc_attr( $base_name . '[show]' ); ?>" value="0"/>
      <input class="tcdce-toggle__input" type="checkbox" name="<?php echo esc_attr( $base_name . '[show]' ); ?>" value="1" <?php checked( 1, esc_attr( $base_value['show'] ) ); ?>/>
      <span class="tcdce-toggle__button"></span>
    </label>
    <span class="tcdce-repeater__item-title__icon">
      <?php echo wp_kses( TCDCE_ICONS[$item_type], wp_kses_allowed_html( 'tcdce' ) ); ?>
    </span>
    <div class="tcdce-repeater__item-title__register">
      <span class="tcdce-repeater__item-title__register-label"><?php esc_html_e( 'Registered name', 'tcd-classic-editor' ); ?></span>
      <span class="tcdce-repeater__item-title__register-name js-tcdce-repeater-title-label"><?php echo esc_html( $base_value['label'] ); ?></span>
    </div>
    <div class="tcdce-repeater__item-title__delete js-tcdce-repeater-delete" data-msg="<?php esc_attr_e( 'Can I delete this Quicktag?', 'tcd-classic-editor' ); ?>">
      <?php echo wp_kses( TCDCE_ICONS['delete'], wp_kses_allowed_html( 'tcdce' ) ); ?>
    </div>
    <div class="tcdce-repeater__item-title__expand">
      <?php echo wp_kses( TCDCE_ICONS['expand'], wp_kses_allowed_html( 'tcdce' ) ); ?>
    </div>
  </div>
<?php
    }

    /**
     * Ajax repeater add.
     */
    public function repeater_add() {

      // nonce認証
      $nonce = check_ajax_referer( 'tcdce_ajax_action', 'nonce', false );
      if( ! $nonce ){
        echo 'nonce_error';
        wp_die();
      }

      // repeater item
      $item = isset( $_POST['item'] ) ? sanitize_key( wp_unslash( $_POST['item'] ) ) : '';

      // repeater keys
      $register_keys = array();
      if( isset( $_POST['register_keys'] ) && is_array( $_POST['register_keys'] ) && ! empty( $_POST['register_keys'] ) ){
        $register_keys = array_map( 'absint', $_POST['register_keys'] );
      }

      // 固有IDの発酵
      $new_key = wp_rand( 100, 999 );
      if( ! empty( $register_keys ) ){
        $register_keys = array_map( 'intval', $register_keys );
        while( true ){
          $tmp_key = wp_rand( 100, 999 );
          if( ! in_array( $tmp_key, $register_keys, true ) ){
            $new_key = $tmp_key;
            break;
          }
        }
      }

      $default = $this->get_default( $item );
      if( isset( $default['class'] ) ) {
        $default['class'] = $item . '-' . $new_key;
      }

      $this->repeater(
        $item,
        $new_key,
        'tcdce_quicktag[' . $new_key . ']',
        $default
      );

      wp_die();

    }

  }
}