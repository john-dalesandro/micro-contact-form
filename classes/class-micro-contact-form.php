<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Micro_Contact_Form' ) ) {
    class Micro_Contact_Form {
        private static $b_form_rendered = false;

        private static $options_cache = null;

        public static function is_form_rendered() {
            return self::$b_form_rendered;
        }

        private function get_options() {
            if ( null === self::$options_cache ) {
                $options = get_option( 'micro_contact_form_settings_db' );
                self::$options_cache = is_array( $options ) ? $options : array();
            }

            return self::$options_cache;
        }

        public function __construct() {
            add_action( 'plugins_loaded', array( $this, 'load_textdomain_handler' ) );
            add_action( 'update_option_micro_contact_form_settings_db', array( $this, 'flush_options_cache' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'load_external_scripts' ) );
            add_shortcode( 'micro_contact_form', array( $this, 'shortcode' ) );
        }

        public function flush_options_cache() {
            self::$options_cache = null;
        }

        public function load_textdomain_handler() {
            load_plugin_textdomain( 'micro-contact-form', false, trailingslashit( dirname( plugin_basename( MICRO_CONTACT_FORM_FILE ) ) ) . 'languages/' );
        }

        public function load_external_scripts() {
            $options = $this->get_options();
            $b_flag_use_default_styles = ( isset( $options['flag_use_default_styles'] ) ? intval( $options['flag_use_default_styles'] ) : 1 );

            if ( $b_flag_use_default_styles ) {
                wp_enqueue_style( 'micro_contact_form_stylesheet', trailingslashit( plugin_dir_url( MICRO_CONTACT_FORM_FILE ) ) . 'css/micro-contact-form-style.css', array(), '1.0.6' );
            }
        }

        public function shortcode() {
            if ( ! array_key_exists( 'submit', $_POST ) ) {
                return $this->generate_form();
            }

            if ( ! isset( $_POST['mcf_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mcf_nonce'] ) ), 'micro_contact_form_submit' ) ) {
                return $this->generate_form( __( 'Security check failed.', 'micro-contact-form' ) );
            }

            $from_name = ( empty( $_POST['from_name'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['from_name'] ) ) );
            $from_email = ( empty( $_POST['from_email'] ) ? '' : sanitize_email( wp_unslash( $_POST['from_email'] ) ) );
            $subject = ( empty( $_POST['subject'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['subject'] ) ) );
            $message = ( empty( $_POST['message'] ) ? '' : sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) );

            if ( empty( $from_name ) || empty( $from_email ) || empty( $subject ) || empty( $message ) ) {
                return $this->generate_form( __( 'All required fields have not been completed.', 'micro-contact-form' ) );
            }

            if ( ! ( is_email( $from_email ) ) ) {
                return $this->generate_form( __( 'A valid email address is required.', 'micro-contact-form' ) );
            }

            $form_data = array(
                'from_name' => $from_name,
                'from_email' => $from_email,
                'subject' => $subject,
                'message' => $message,
            );

            $approved = apply_filters( 'preprocess_micro_contact_form_data', true, $form_data );

            if ( ! $approved ) {
                return $this->generate_form( __( 'Submission has been rejected.', 'micro-contact-form' ) );
            }

            return $this->generate_email( $form_data );
        }

        private function generate_form( $status_message = '' ) {
            $form = '';
            $allowed_html = array(
                'div' => array(
                    'hidden' => array(),
                ),
                'label' => array(
                    'for' => array(),
                ),
                'textarea' => array(
                    'id' => array(),
                    'name' => array(),
                ),
                'input' => array(
                    'type' => array(),
                    'id' => array(),
                    'name' => array(),
                    'value' => array(),
                ),
            );

            if ( true !== self::$b_form_rendered ) {
                $options = $this->get_options();

                $from_name_label = ( ( isset( $options['label_for_from_name_field'] ) && ! empty( $options['label_for_from_name_field'] ) ) ? $options['label_for_from_name_field'] : __( 'Name', 'micro-contact-form' ) );
                $from_email_label = ( ( isset( $options['label_for_from_email_field'] ) && ! empty( $options['label_for_from_email_field'] ) ) ? $options['label_for_from_email_field'] : __( 'Email', 'micro-contact-form' ) );
                $subject_label = ( ( isset( $options['label_for_subject_field'] ) && ! empty( $options['label_for_subject_field'] ) ) ? $options['label_for_subject_field'] : __( 'Subject', 'micro-contact-form' ) );
                $message_label = ( ( isset( $options['label_for_message_field'] ) && ! empty( $options['label_for_message_field'] ) ) ? $options['label_for_message_field'] : __( 'Message', 'micro-contact-form' ) );
                $send_message_label = ( ( isset( $options['label_for_submit_button'] ) && ! empty( $options['label_for_submit_button'] ) ) ? $options['label_for_submit_button'] : __( 'Send Message', 'micro-contact-form' ) );
                $required_field_indicator = ( ( isset( $options['required_field_indicator'] ) && ! empty( $options['required_field_indicator'] ) ) ? $options['required_field_indicator'] : __( '*', 'micro-contact-form' ) );

                $from_name = ( empty( $_POST['from_name'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['from_name'] ) ) );
                $from_email = ( empty( $_POST['from_email'] ) ? '' : sanitize_email( wp_unslash( $_POST['from_email'] ) ) );
                $subject = ( empty( $_POST['subject'] ) ? '' : sanitize_text_field( wp_unslash( $_POST['subject'] ) ) );
                $message = ( empty( $_POST['message'] ) ? '' : sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) );

                $submitted = array_key_exists( 'submit', $_POST );

                ob_start();
                do_action( 'addtl_micro_contact_form_elements' );
                $addtl_elements = ob_get_clean();

                $form .= "\r\n";
                $form .= '<div id="micro-contact-form-submission">' . "\r\n";

                if ( ! empty( $status_message ) ) {
                    $form .= '<p><span class="micro-contact-form-required">' . esc_html( $status_message ) . '</span></p>' . "\r\n";
                }

                $form .= '<form id="micro-contact-form" action="" method="post">' . "\r\n";
                $form .= wp_nonce_field( 'micro_contact_form_submit', 'mcf_nonce', true, false ) . "\r\n";

                $form .= '<p class="from-name">' . "\r\n";
                $form .= '<label for="from_name" class="screen-reader-text">' . esc_html( $from_name_label ) . '</label>' . "\r\n";

                if ( $submitted && empty( $from_name ) ) {
                    $form .= '<input id="from_name" name="from_name" placeholder="' . esc_attr( $from_name_label ) . ' ' . esc_attr( $required_field_indicator ) . '" type="text" value="' . esc_attr( $from_name ) . '" class="micro-contact-form-error" aria-required="true" required />' . "\r\n";
                } else {
                    $form .= '<input id="from_name" name="from_name" placeholder="' . esc_attr( $from_name_label ) . ' ' . esc_attr( $required_field_indicator ) . '" type="text" value="' . esc_attr( $from_name ) . '" aria-required="true" required />' . "\r\n";
                }

                $form .= '</p>' . "\r\n";

                $form .= '<p class="from-email">' . "\r\n";
                $form .= '<label for="from_email" class="screen-reader-text">' . esc_html( $from_email_label ) . '</label>' . "\r\n";

                if ( $submitted && empty( $from_email ) ) {
                    $form .= '<input id="from_email" name="from_email" placeholder="' . esc_attr( $from_email_label ) . ' ' . esc_attr( $required_field_indicator ) . '" type="text" value="' . esc_attr( $from_email ) . '" class="micro-contact-form-error" aria-required="true" required />' . "\r\n";
                } else {
                    $form .= '<input id="from_email" name="from_email" placeholder="' . esc_attr( $from_email_label ) . ' ' . esc_attr( $required_field_indicator ) . '" type="text" value="' . esc_attr( $from_email ) . '" aria-required="true" required />' . "\r\n";
                }

                $form .= '</p>' . "\r\n";

                $form .= '<p class="subject">' . "\r\n";
                $form .= '<label for="subject" class="screen-reader-text">' . esc_html( $subject_label ) . '</label>' . "\r\n";

                if ( $submitted && empty( $subject ) ) {
                    $form .= '<input id="subject" name="subject" placeholder="' . esc_attr( $subject_label ) . ' ' . esc_attr( $required_field_indicator ) . '" type="text" value="' . esc_attr( $subject ) . '" class="micro-contact-form-error" aria-required="true" required />' . "\r\n";
                } else {
                    $form .= '<input id="subject" name="subject" placeholder="' . esc_attr( $subject_label ) . ' ' . esc_attr( $required_field_indicator ) . '" type="text" value="' . esc_attr( $subject ) . '" aria-required="true" required />' . "\r\n";
                }

                $form .= '</p>' . "\r\n";

                $form .= '<p class="message">' . "\r\n";
                $form .= '<label for="message" class="screen-reader-text">' . esc_html( $message_label ) . '</label>' . "\r\n";

                if ( $submitted && empty( $message ) ) {
                    $form .= '<textarea id="message" name="message" placeholder="' . esc_attr( $message_label ) . ' ' . esc_attr( $required_field_indicator ) . '" cols="45" rows="8" class="micro-contact-form-error" aria-required="true" required>' . esc_textarea( $message ) . '</textarea>' . "\r\n";
                } else {
                    $form .= '<textarea id="message" name="message" placeholder="' . esc_attr( $message_label ) . ' ' . esc_attr( $required_field_indicator ) . '" cols="45" rows="8" aria-required="true" required>' . esc_textarea( $message ) . '</textarea>' . "\r\n";
                }

                $form .= '</p>' . "\r\n";

                $form .= '<p class="form-submit">' . "\r\n";
                $form .= '<input id="submit" name="submit" type="submit" value="' . esc_attr( $send_message_label ) . '" />' . "\r\n";
                $form .= '</p>' . "\r\n";
                $form .= wp_kses( $addtl_elements, $allowed_html ) . "\r\n";
                $form .= '</form>' . "\r\n";
                $form .= '</div>' . "\r\n";
            }

            self::$b_form_rendered = true;

            return $form;
        }

        private function generate_email( $form_data ) {
            $options = $this->get_options();

            $from_name_label = ( ( isset( $options['label_for_from_name_field'] ) && ! empty( $options['label_for_from_name_field'] ) ) ? $options['label_for_from_name_field'] : __( 'Name', 'micro-contact-form' ) );
            $from_email_label = ( ( isset( $options['label_for_from_email_field'] ) && ! empty( $options['label_for_from_email_field'] ) ) ? $options['label_for_from_email_field'] : __( 'Email', 'micro-contact-form' ) );
            $message_label = ( ( isset( $options['label_for_message_field'] ) && ! empty( $options['label_for_message_field'] ) ) ? $options['label_for_message_field'] : __( 'Message', 'micro-contact-form' ) );
            $to_email = ( ( isset( $options['email_address_to_receive_messages'] ) && ! empty( $options['email_address_to_receive_messages'] ) ) ? $options['email_address_to_receive_messages'] : get_option( 'admin_email' ) );
            $b_flag_include_blog_name_in_subject = ( isset( $options['flag_include_blog_name_in_subject'] ) ? intval( $options['flag_include_blog_name_in_subject'] ) : 1 );
            $subject_prefix = ( ( isset( $options['subject_prefix_text'] ) && ! empty( $options['subject_prefix_text'] ) ) ? $options['subject_prefix_text'] : '' );

            $from_name = ( empty( $form_data['from_name'] ) ? __( 'No Name Provided', 'micro-contact-form' ) : $form_data['from_name'] );
            $from_email = ( empty( $form_data['from_email'] ) ? __( 'No Email Address Provided', 'micro-contact-form' ) : $form_data['from_email'] );
            $subject = ( empty( $form_data['subject'] ) ? __( 'No Subject Provided', 'micro-contact-form' ) : $form_data['subject'] );
            $message = ( empty( $form_data['message'] ) ? __( 'No Message Provided', 'micro-contact-form' ) : $form_data['message'] );

            $subject_blog_name = '';
            if ( $b_flag_include_blog_name_in_subject ) {
                $subject_blog_name = sprintf( '[%s]', get_bloginfo( 'name' ) );
            }

            $subject_formatted = preg_replace( '/\s+/', ' ', trim( sprintf( '%1$s %2$s %3$s', $subject_blog_name, $subject_prefix, $subject ) ) );

            $body = '';
            $body .= $from_name_label . ': ' . $from_name . "\r\n";
            $body .= $from_email_label . ': ' . $from_email . "\r\n\r\n";
            $body .= $message_label . ': ' . "\r\n";
            $body .= $message;

            if ( wp_mail( $to_email, $subject_formatted, $body ) ) {
                return '<p><span class="micro-contact-form-success">' . esc_html__( 'Message sent successfully.', 'micro-contact-form' ) . '</span></p>';
            }

            return '<p><span class="micro-contact-form-required">' . esc_html__( 'An unexpected error occurred. The message was not sent.', 'micro-contact-form' ) . '</span></p>';
        }
    }
}

if ( class_exists( 'Micro_Contact_Form' ) ) {
    $micro_contact_form = new Micro_Contact_Form();
}
