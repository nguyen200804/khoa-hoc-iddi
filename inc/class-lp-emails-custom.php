<?php
/**
 * Custom LearnPress Emails for New Trainee Registration.
 *
 * @package LearnPress/Classes
 */

defined( 'ABSPATH' ) || exit();

if ( class_exists( 'LP_Email' ) ) {

	/**
	 * Class LP_Email_New_Trainee_Admin
	 * Email notification to administrator for new trainee registration.
	 */
	class LP_Email_New_Trainee_Admin extends LP_Email {
		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id          = 'new-trainee-admin';
			$this->title       = __( 'Thông báo Admin', 'learnpress' );
			$this->description = __( 'Gửi email thông báo cho Admin khi có học viên mới đăng ký.', 'learnpress' );

			$this->default_subject = __( '[{{site_title}}] Học viên mới đăng ký', 'learnpress' );
			$this->default_heading = __( 'Học viên mới đăng ký', 'learnpress' );

			$this->recipient = LP_Settings::instance()->get( 'emails_' . $this->id . '.recipients', $this->_get_admin_email() );
			$this->enable_recipients = true;

			// Define theme-based templates base
			$this->template_base = get_stylesheet_directory() . '/learnpress/';

			parent::__construct();

			$this->support_variables = array_merge(
				$this->support_variables,
				array(
					'{{user_name}}',
					'{{user_email}}',
					'{{user_display_name}}',
					'{{user_registered}}',
				)
			);
		}

		/**
		 * Handle and trigger email.
		 *
		 * @param int $user_id
		 */
		public function handle( $user_id ) {
			if ( ! $this->enable ) {
				return;
			}

			$user = get_userdata( $user_id );
			if ( ! $user ) {
				return;
			}

			$this->object = $user;

			// Construct display name, prioritizing POST details if in registration context
			$display_name = '';
			if ( ! empty( $_POST['first_name'] ) || ! empty( $_POST['last_name'] ) ) {
				$first_name = sanitize_text_field( $_POST['first_name'] );
				$last_name  = sanitize_text_field( $_POST['last_name'] );
				$display_name = trim( $last_name . ' ' . $first_name );
			}
			if ( empty( $display_name ) ) {
				$display_name = $user->display_name ?: $user->user_login;
			}

			$this->variables = array(
				'{{user_name}}'         => $user->user_login,
				'{{user_email}}'        => $user->user_email,
				'{{user_display_name}}' => $display_name,
				'{{user_registered}}'   => $user->user_registered,
			);

			$variables_common = $this->get_common_variables( $this->email_format );
			$this->variables  = array_merge( $this->variables, $variables_common );

			$this->send_email();
		}
	}

	/**
	 * Class LP_Email_New_Trainee_User
	 * Welcome email notification to newly registered trainee.
	 */
	class LP_Email_New_Trainee_User extends LP_Email {
		/**
		 * Constructor.
		 */
		public function __construct() {
			$this->id          = 'new-trainee-user';
			$this->title       = __( 'Lời chào Học viên', 'learnpress' );
			$this->description = __( 'Gửi thư chào mừng khi học viên đăng ký tài khoản thành công.', 'learnpress' );

			$this->default_subject = __( 'Chào mừng bạn đến với {{site_title}}', 'learnpress' );
			$this->default_heading = __( 'Chào mừng bạn tham gia khóa học', 'learnpress' );

			$this->enable_recipients = false;

			// Define theme-based templates base
			$this->template_base = get_stylesheet_directory() . '/learnpress/';

			parent::__construct();

			$this->support_variables = array_merge(
				$this->support_variables,
				array(
					'{{user_name}}',
					'{{user_email}}',
					'{{user_display_name}}',
				)
			);
		}

		/**
		 * Handle and trigger email.
		 *
		 * @param int $user_id
		 */
		public function handle( $user_id ) {
			if ( ! $this->enable ) {
				return;
			}

			$user = get_userdata( $user_id );
			if ( ! $user ) {
				return;
			}

			$this->object = $user;
			$this->recipient = $user->user_email;

			// Construct display name, prioritizing POST details if in registration context
			$display_name = '';
			if ( ! empty( $_POST['first_name'] ) || ! empty( $_POST['last_name'] ) ) {
				$first_name = sanitize_text_field( $_POST['first_name'] );
				$last_name  = sanitize_text_field( $_POST['last_name'] );
				$display_name = trim( $last_name . ' ' . $first_name );
			}
			if ( empty( $display_name ) ) {
				$display_name = $user->display_name ?: $user->user_login;
			}

			$this->variables = array(
				'{{user_name}}'         => $user->user_login,
				'{{user_email}}'        => $user->user_email,
				'{{user_display_name}}' => $display_name,
			);

			$variables_common = $this->get_common_variables( $this->email_format );
			$this->variables  = array_merge( $this->variables, $variables_common );

			$this->send_email();
		}
	}

	// Register custom email classes inside LearnPress manager
	add_action( 'learn-press/emails-init', function( $emails_manager ) {
		$emails_manager->emails['LP_Email_New_Trainee_Admin'] = new LP_Email_New_Trainee_Admin();
		$emails_manager->emails['LP_Email_New_Trainee_User']  = new LP_Email_New_Trainee_User();
	} );

	// Hook into user registration to send emails
	add_action( 'user_register', 'lp_custom_send_new_trainee_emails', 20, 1 );
	function lp_custom_send_new_trainee_emails( $user_id ) {
		$emails = LP_Emails::instance()->emails;

		// Send admin notification email
		if ( isset( $emails['LP_Email_New_Trainee_Admin'] ) ) {
			$emails['LP_Email_New_Trainee_Admin']->handle( $user_id );
		}

		// Send user welcome email
		if ( isset( $emails['LP_Email_New_Trainee_User'] ) ) {
			$emails['LP_Email_New_Trainee_User']->handle( $user_id );
		}
	}

}

// Register settings section
add_filter( 'learn-press/email-section-classes', function( $groups ) {
	if ( class_exists( 'LP_Settings_Emails_Group' ) ) {
		if ( ! class_exists( 'LP_Settings_New_Trainee_Emails' ) ) {
			class LP_Settings_New_Trainee_Emails extends LP_Settings_Emails_Group {
				public function __construct() {
					$this->group_id = 'new-trainee-emails';
					$this->items    = array(
						'new-trainee-admin',
						'new-trainee-user',
					);
					parent::__construct();
				}

				public function __toString() {
					return esc_html__( 'Học viên mới', 'learnpress' );
				}
			}
		}
		$groups[] = new LP_Settings_New_Trainee_Emails();
	}
	return $groups;
} );
