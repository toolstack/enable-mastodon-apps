<?php

namespace Enable_Mastodon_Apps\OAuth2;

use OAuth2\Storage\AuthorizationCodeInterface;

class AuthorizationCodeStorage implements AuthorizationCodeInterface {
	const META_KEY_PREFIX = 'mastodon_api_oa2_auth_code';

	private static $authorization_code_data = array(
		'client_id'    => 'string', // client identifier.
		'redirect_uri' => 'url', // redirect URI.
		'expires'      => 'int',    // expires as unix timestamp.
		'scope'        => 'string', // scope as space-separated string.
	);

	public function __construct() {
		add_action( 'mastodon_api_cron_hook', array( $this, 'cleanupOldCodes' ) );
	}

	public static function getAll() {
		$tokens = array();

		foreach ( get_user_meta( get_current_user_id() ) as $key => $value ) {
			if ( 0 !== strpos( $key, self::META_KEY_PREFIX ) ) {
				continue;
			}
			$key_parts = explode( '_', substr( $key, strlen( self::META_KEY_PREFIX ) + 1 ) );
			$token = array_pop( $key_parts );
			$key = implode( '_', $key_parts );
			$value = $value[0];

			if ( 'expires' === $key ) {
				$tokens[$token]['expired'] = time() > $value;
			}

			$tokens[$token][$key] = $value;
		}

		return $tokens;
	}
	private function getUserIdByCode( $code ) {
		if ( empty( $code ) ) {
			return null;
		}

		$users = get_users(
			array(
				'number'       => 1,
				// Specifying blog_id does nothing for non-MultiSite installs. But for MultiSite installs, it allows you
				// to customize users of which site is supposed to be available for whatever sites
				// this plugin is meant to be activated on.
				'blog_id'      => get_current_blog_id(),
				// phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_key
				'meta_key'     => self::META_KEY_PREFIX . '_client_id_' . $code,
				// Using a meta_key EXISTS query is not slow, see https://github.com/WordPress/WordPress-Coding-Standards/issues/1871.
				'meta_compare' => 'EXISTS',
			)
		);

		if ( empty( $users ) ) {
			return null;
		}

		return absint( $users[0]->ID );
	}

	public function getAuthorizationCode( $code ) {
		$user_id = $this->getUserIdByCode( $code );
		if ( empty( $user_id ) ) {
			return null;
		}

		$user = new \WP_User( $user_id );

		$authorization_code = array(
			'user_id' => $user->user_login,
			'code'    => $code,
		);
		foreach ( array_keys( self::$authorization_code_data ) as $key ) {
			$authorization_code[ $key ] = get_user_meta( $user_id, self::META_KEY_PREFIX . '_' . $key . '_' . $code, true );
		}

		return $authorization_code;
	}

	public function setAuthorizationCode( $code, $client_id, $user_id, $redirect_uri, $expires, $scope = null ) {
		if ( empty( $code ) ) {
			return;
		}
		$expires = time() + 86400;

		$user = get_user_by( 'login', $user_id );

		if ( $user ) {
			foreach ( self::$authorization_code_data as $key => $data_type ) {
				if ( 'int' === $data_type ) {
					$value = absint( $$key );
				} elseif ( 'url' === $data_type ) {
					$value = strval( $$key );
				} else {
					$value = sanitize_text_field( $$key );
				}

				update_user_meta( $user->ID, self::META_KEY_PREFIX . '_' . $key . '_' . $code, $value );
			}
		}
	}

	public function expireAuthorizationCode( $code ) {
		$user_id = $this->getUserIdByCode( $code );
		if ( empty( $user_id ) ) {
			return null;
		}

		foreach ( array_keys( self::$authorization_code_data ) as $key ) {
			delete_user_meta( $user_id, self::META_KEY_PREFIX . '_' . $key . '_' . $code );
		}

		return true;
	}

	/**
	 * This function cleans up auth codes that are sitting in the database because of interrupted/abandoned OAuth flows.
	 */
	public static function cleanupOldCodes() {
		global $wpdb;

		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$data = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT user_id, meta_key FROM $wpdb->usermeta WHERE meta_key LIKE %s AND meta_value < %d",
				self::META_KEY_PREFIX . '_expires_%',
				time() - 3600 // wait for an hour past expiry, to offer a chance at debug.
			)
		);
		if ( empty( $data ) ) {
			return;
		}

		foreach ( $data as $row ) {
			$code = substr( $row->meta_key, strlen( self::META_KEY_PREFIX . '_expires_' ) );
			foreach ( array_keys( self::$authorization_code_data ) as $key ) {
				delete_user_meta( $row->user_id, self::META_KEY_PREFIX . '_' . $key . '_' . $code );
			}
		}
	}

	public static function uninstall() {
		global $wpdb;

		// Following query is only possible via a direct query since meta_key is not a fixed string
		// and since it only runs at uninstall, we don't need it cached.
		// phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery,WordPress.DB.DirectDatabaseQuery.NoCaching
		$data = $wpdb->get_results(
			$wpdb->prepare(
				"SELECT user_id, meta_key FROM $wpdb->usermeta WHERE meta_key LIKE %s",
				self::META_KEY_PREFIX . '_expires_%'
			)
		);
		if ( empty( $data ) ) {
			return;
		}

		foreach ( $data as $row ) {
			$code = substr( $row->meta_key, strlen( self::META_KEY_PREFIX . '_expires_' ) );
			foreach ( array_keys( self::$authorization_code_data ) as $key ) {
				delete_user_meta( $row->user_id, self::META_KEY_PREFIX . '_' . $key . '_' . $code );
			}
		}
	}
}
