<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( ! class_exists( 'Micro_Contact_Form_Settings' ) ) {
    class Micro_Contact_Form_Settings {
        private $options;

        private function get_options() {
            if ( ! is_array( $this->options ) ) {
                $options = get_option( 'micro_contact_form_settings_db' );
                $this->options = is_array( $options ) ? $options : array();
            }

            return $this->options;
        }

        public function __construct() {
            add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
            add_action( 'admin_init', array( $this, 'page_init' ) );
            add_filter( 'plugin_action_links_' . plugin_basename( MICRO_CONTACT_FORM_FILE ), array( $this, 'plugin_action_links_handler' ) );
        }

        public function add_plugin_page() {
            add_options_page( __( 'Micro Contact Form', 'micro-contact-form' ), __( 'Micro Contact Form', 'micro-contact-form' ), 'manage_options', 'micro-contact-form-settings', array( $this, 'create_admin_page' ) );
        }

        public function create_admin_page() {
            $this->options = $this->get_options();

            echo( '<div class="wrap"><h1>' . esc_html__( 'Micro Contact Form', 'micro-contact-form' ) . '</h1><form method="post" action="options.php">' );

            settings_fields( 'micro-contact-form-settings-group' );
            do_settings_sections( 'micro-contact-form-settings' );
            submit_button();

            echo( '</form></div>' );
        }

        public function page_init() {
            register_setting( 'micro-contact-form-settings-group', 'micro_contact_form_settings_db', array( $this, 'sanitize' ) );

            add_settings_section( 'micro-contact-form-settings-section-general', __( 'General Settings', 'micro-contact-form' ), array( $this, 'print_section_info_general' ), 'micro-contact-form-settings' );

            add_settings_field( 'label_for_from_name_field', __( 'Label for Name Field', 'micro-contact-form' ), array( $this, 'label_for_from_name_field_callback' ), 'micro-contact-form-settings', 'micro-contact-form-settings-section-general' );
            add_settings_field( 'label_for_from_email_field', __( 'Label for Email Field', 'micro-contact-form' ), array( $this, 'label_for_from_email_field_callback' ), 'micro-contact-form-settings', 'micro-contact-form-settings-section-general' );
            add_settings_field( 'label_for_subject_field', __( 'Label for Subject Field', 'micro-contact-form' ), array( $this, 'label_for_subject_field_callback' ), 'micro-contact-form-settings', 'micro-contact-form-settings-section-general' );
            add_settings_field( 'label_for_message_field', __( 'Label for Message Field', 'micro-contact-form' ), array( $this, 'label_for_message_field_callback' ), 'micro-contact-form-settings', 'micro-contact-form-settings-section-general' );
            add_settings_field( 'label_for_submit_button', __( 'Label for Send Message Button', 'micro-contact-form' ), array( $this, 'label_for_submit_button_callback' ), 'micro-contact-form-settings', 'micro-contact-form-settings-section-general' );
            add_settings_field( 'required_field_indicator', __( 'Character to Indicate Required Field', 'micro-contact-form' ), array( $this, 'required_field_indicator_callback' ), 'micro-contact-form-settings', 'micro-contact-form-settings-section-general' );
            add_settings_field( 'email_address_to_receive_messages', __( 'Email Address to Receive Submitted Messages', 'micro-contact-form' ), array( $this, 'email_address_to_receive_messages_callback' ), 'micro-contact-form-settings', 'micro-contact-form-settings-section-general' );
            add_settings_field( 'flag_include_blog_name_in_subject', __( 'Include Blog Name in Subject?', 'micro-contact-form' ), array( $this, 'flag_include_blog_name_in_subject_callback' ), 'micro-contact-form-settings', 'micro-contact-form-settings-section-general' );
            add_settings_field( 'subject_prefix_text', __( 'Additional Text to Include in Subject', 'micro-contact-form' ), array( $this, 'subject_prefix_text_callback' ), 'micro-contact-form-settings', 'micro-contact-form-settings-section-general' );
            add_settings_field( 'flag_use_default_styles', __( 'Use Default Styles?', 'micro-contact-form' ), array( $this, 'flag_use_default_styles_callback' ), 'micro-contact-form-settings', 'micro-contact-form-settings-section-general' );
        }

        public function plugin_action_links_handler( $links ) {
            $settings_link = '<a href="' . esc_url( admin_url( 'options-general.php?page=micro-contact-form-settings' ) ) . '">' . esc_html__( 'Settings', 'micro-contact-form' ) . '</a>';
            array_unshift( $links, $settings_link );

            return $links;
        }

        public function print_section_info_general() {
            esc_html_e( 'Configure general settings:', 'micro-contact-form' );
        }

        public function label_for_from_name_field_callback() {
            echo( '<input type="text" id="label_for_from_name_field" name="micro_contact_form_settings_db[label_for_from_name_field]" value="' . ( isset( $this->options['label_for_from_name_field'] ) ? esc_attr( $this->options['label_for_from_name_field'] ) : '' ) . '" />' );
            echo( '<label for="label_for_from_name_field">' . esc_html__( 'Label to display for the Name field. Default value is "Name".', 'micro-contact-form' ) . '</label>' );
        }

        public function label_for_from_email_field_callback() {
            echo( '<input type="text" id="label_for_from_email_field" name="micro_contact_form_settings_db[label_for_from_email_field]" value="' . ( isset( $this->options['label_for_from_email_field'] ) ? esc_attr( $this->options['label_for_from_email_field'] ) : '' ) . '" />' );
            echo( '<label for="label_for_from_email_field">' . esc_html__( 'Label to display for the Email field. Default value is "Email".', 'micro-contact-form' ) . '</label>' );
        }

        public function label_for_subject_field_callback() {
            echo( '<input type="text" id="label_for_subject_field" name="micro_contact_form_settings_db[label_for_subject_field]" value="' . ( isset( $this->options['label_for_subject_field'] ) ? esc_attr( $this->options['label_for_subject_field'] ) : '' ) . '" />' );
            echo( '<label for="label_for_subject_field">' . esc_html__( 'Label to display for the Subject field. Default value is "Subject".', 'micro-contact-form' ) . '</label>' );
        }

        public function label_for_message_field_callback() {
            echo( '<input type="text" id="label_for_message_field" name="micro_contact_form_settings_db[label_for_message_field]" value="' . ( isset( $this->options['label_for_message_field'] ) ? esc_attr( $this->options['label_for_message_field'] ) : '' ) . '" />' );
            echo( '<label for="label_for_message_field">' . esc_html__( 'Label to display for the Message field. Default value is "Message".', 'micro-contact-form' ) . '</label>' );
        }

        public function label_for_submit_button_callback() {
            echo( '<input type="text" id="label_for_submit_button" name="micro_contact_form_settings_db[label_for_submit_button]" value="' . ( isset( $this->options['label_for_submit_button'] ) ? esc_attr( $this->options['label_for_submit_button'] ) : '' ) . '" />' );
            echo( '<label for="label_for_submit_button">' . esc_html__( 'Label to display for the submit button. Default value is "Send Message".', 'micro-contact-form' ) . '</label>' );
        }

        public function required_field_indicator_callback() {
            echo( '<input type="text" id="required_field_indicator" name="micro_contact_form_settings_db[required_field_indicator]" value="' . ( isset( $this->options['required_field_indicator'] ) ? esc_attr( $this->options['required_field_indicator'] ) : '' ) . '" />' );
            echo( '<label for="required_field_indicator">' . esc_html__( 'Character to display next to labels for required fields. If more than one character is entered, only the first will be used. Default value is "*".', 'micro-contact-form' ) . '</label>' );
        }

        public function email_address_to_receive_messages_callback() {
            $to_email = get_option( 'admin_email' );

            echo( '<input type="text" id="email_address_to_receive_messages" name="micro_contact_form_settings_db[email_address_to_receive_messages]" value="' . ( isset( $this->options['email_address_to_receive_messages'] ) ? esc_attr( $this->options['email_address_to_receive_messages'] ) : '' ) . '" />' );
            /* translators: %s: email address to receive submissions */
            echo( '<label for="email_address_to_receive_messages">' . sprintf( esc_html__( 'Email address to receive contact form submissions. Default value is "%s".', 'micro-contact-form' ), esc_html( $to_email ) ) . '</label>' );
        }

        public function flag_include_blog_name_in_subject_callback() {
            echo( '<input type="checkbox" id="flag_include_blog_name_in_subject" name="micro_contact_form_settings_db[flag_include_blog_name_in_subject]" value="1"' . checked( 1, ( isset( $this->options['flag_include_blog_name_in_subject'] ) ? esc_attr( $this->options['flag_include_blog_name_in_subject'] ) : 1 ), false ) . '/>' );
            /* translators: %s: blog name */
            echo( '<label for="flag_include_blog_name_in_subject">' . sprintf( esc_html__( 'When checked, the blog name ("%s") will be included in the subject line of received submissions.', 'micro-contact-form' ), esc_html( get_bloginfo( 'name' ) ) ) . '</label>' );
        }

        public function subject_prefix_text_callback() {
            echo( '<input type="text" id="subject_prefix_text" name="micro_contact_form_settings_db[subject_prefix_text]" value="' . ( isset( $this->options['subject_prefix_text'] ) ? esc_attr( $this->options['subject_prefix_text'] ) : '' ) . '" />' );
            echo( '<label for="subject_prefix_text">' . esc_html__( 'Additional text to include in the subject of received contact form submissions. Default value is "" (empty).', 'micro-contact-form' ) . '</label>' );
        }

        public function flag_use_default_styles_callback() {
            echo( '<input type="checkbox" id="flag_use_default_styles" name="micro_contact_form_settings_db[flag_use_default_styles]" value="1"' . checked( 1, ( isset( $this->options['flag_use_default_styles'] ) ? esc_attr( $this->options['flag_use_default_styles'] ) : 1 ), false ) . '/>' );
            echo( '<label for="flag_use_default_styles">' . esc_html__( 'Include default plugin styles for required field indicators, field validation errors, and success/error messages.', 'micro-contact-form' ) . '</label>' );
        }

        public function sanitize( $input ) {
            $sanitized_input = array();

            $sanitized_input['label_for_from_name_field'] = ( isset( $input['label_for_from_name_field'] ) ? sanitize_text_field( wp_unslash( $input['label_for_from_name_field'] ) ) : '' );

            $sanitized_input['label_for_from_email_field'] = ( isset( $input['label_for_from_email_field'] ) ? sanitize_text_field( wp_unslash( $input['label_for_from_email_field'] ) ) : '' );

            $sanitized_input['label_for_subject_field'] = ( isset( $input['label_for_subject_field'] ) ? sanitize_text_field( wp_unslash( $input['label_for_subject_field'] ) ) : '' );

            $sanitized_input['label_for_message_field'] = ( isset( $input['label_for_message_field'] ) ? sanitize_text_field( wp_unslash( $input['label_for_message_field'] ) ) : '' );

            $sanitized_input['label_for_submit_button'] = ( isset( $input['label_for_submit_button'] ) ? sanitize_text_field( wp_unslash( $input['label_for_submit_button'] ) ) : '' );

            $required_field_indicator = ( isset( $input['required_field_indicator'] ) ? sanitize_text_field( wp_unslash( $input['required_field_indicator'] ) ) : '' );
            if ( mb_strlen( $required_field_indicator ) > 1 ) {
                $required_field_indicator = mb_substr( $required_field_indicator, 0, 1 );
            }
            $sanitized_input['required_field_indicator'] = $required_field_indicator;

            $email_input = isset( $input['email_address_to_receive_messages'] ) ? wp_unslash( $input['email_address_to_receive_messages'] ) : '';
            $email_sanitized = sanitize_email( $email_input );
            if ( ! empty( $email_input ) && ! is_email( $email_sanitized ) ) {
                $saved_options = $this->get_options();
                $email_sanitized = isset( $saved_options['email_address_to_receive_messages'] ) ? $saved_options['email_address_to_receive_messages'] : '';
                add_settings_error( 'micro_contact_form_settings_db', 'invalid_email', __( 'The email address entered is not valid. The previous value has been retained.', 'micro-contact-form' ) );
            }
            $sanitized_input['email_address_to_receive_messages'] = $email_sanitized;

            $sanitized_input['flag_include_blog_name_in_subject'] = ( isset( $input['flag_include_blog_name_in_subject'] ) && 1 === intval( $input['flag_include_blog_name_in_subject'] ) ) ? 1 : 0;

            $sanitized_input['subject_prefix_text'] = ( isset( $input['subject_prefix_text'] ) ? sanitize_text_field( wp_unslash( $input['subject_prefix_text'] ) ) : '' );

            $sanitized_input['flag_use_default_styles'] = ( isset( $input['flag_use_default_styles'] ) && 1 === intval( $input['flag_use_default_styles'] ) ) ? 1 : 0;

            return $sanitized_input;
        }
    }
}

if ( class_exists( 'Micro_Contact_Form_Settings' ) ) {
    if ( is_admin() ) {
        $micro_contact_form_settings = new Micro_Contact_Form_Settings();
    }
}
