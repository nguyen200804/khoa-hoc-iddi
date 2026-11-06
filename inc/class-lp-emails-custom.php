<?php
/**
 * Custom LearnPress Emails for New Trainee Registration.
 *
 * @package LearnPress/Classes
 */

defined( 'ABSPATH' ) || exit();


// Register custom email classes inside LearnPress manager.
// The classes extend LP_Email, which LearnPress only loads when LP_Emails is created,
// so they must be declared inside this hook (not at theme load time).
add_action( 'learn-press/emails-init', function( $emails_manager ) {
	if ( ! class_exists( 'LP_Email' ) ) {
		return;
	}
	require_once get_template_directory() . '/inc/class-lp-emails-trainee.php';
	$emails_manager->emails['LP_Email_New_Trainee_Admin'] = new LP_Email_New_Trainee_Admin();
	$emails_manager->emails['LP_Email_New_Trainee_User']  = new LP_Email_New_Trainee_User();
}, 10 );

// Hook into user registration to send emails
add_action( 'user_register', 'lp_custom_send_new_trainee_emails', 20, 1 );
function lp_custom_send_new_trainee_emails( $user_id ) {
	if ( ! class_exists( 'LP_Emails' ) ) {
		return;
	}
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

/**
 * Show the "Recipient(s)" field on every admin-targeted LearnPress email
 * (New Order, Enrolled Course, ...) and use the saved value as the recipient.
 */
add_action( 'learn-press/emails-init', function( $emails_manager ) {
	if ( empty( $emails_manager->emails ) || ! class_exists( 'LP_Settings' ) ) {
		return;
	}

	foreach ( $emails_manager->emails as $email ) {
		if ( ! is_object( $email ) || empty( $email->id ) ) {
			continue;
		}

		// Only admin emails (id ends with "-admin" / "_admin", e.g. new-order-admin).
		if ( ! preg_match( '/[-_]admin$/', $email->id ) ) {
			continue;
		}

		$email->enable_recipients = true;

		$saved = LP_Settings::instance()->get( 'emails_' . $email->id . '.recipients', '' );
		if ( ! empty( $saved ) ) {
			$email->recipient = $saved;
		}
	}
}, 99 );

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
