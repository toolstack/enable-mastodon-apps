<?php
/**
 * Mastodon Apps
 *
 * This contains the Mastodon Apps handlers.
 *
 * @package Enable_Mastodon_Apps
 */

namespace Enable_Mastodon_Apps;

/**
 * This is the class that implements the Mastodon Apps storage.
 *
 * @since 0.1
 *
 * @package Enable_Mastodon_Apps
 * @author Alex Kirk
 */
class Mastodon_App {
	/**
	 * Contains a reference to the term that represents the app.
	 *
	 * @var \WP_Term $term
	 */
	private $term;

	private static $current_app = null;

	const DEBUG_CLIENT_ID = 'enable-mastodon-apps';
	const TAXONOMY        = 'mastodon-app';
	const VALID_SCOPES    = array(
		'read',
		'write',
		'follow',
		'push',
	);


	/**
	 * Constructor
	 *
	 * @param \WP_Term $term The term that represents the app.
	 */
	public function __construct( \WP_Term $term ) {
		$this->term = $term;
	}

	public function get_client_id() {
		return $this->term->slug;
	}

	public function get_client_secret() {
		return get_term_meta( $this->term->term_id, 'client_secret', true );
	}

	public function get_redirect_uris() {
		return get_term_meta( $this->term->term_id, 'redirect_uris', true );
	}

	public function get_client_name() {
		return get_term_meta( $this->term->term_id, 'client_name', true );
	}

	public function get_scopes() {
		return get_term_meta( $this->term->term_id, 'scopes', true );
	}

	public function get_website() {
		return get_term_meta( $this->term->term_id, 'website', true );
	}

	public function get_last_used() {
		return get_term_meta( $this->term->term_id, 'last_used', true );
	}

	public function get_creation_date() {
		return get_term_meta( $this->term->term_id, 'creation_date', true );
	}

	public function get_query_args() {
		return get_term_meta( $this->term->term_id, 'query_args', true );
	}

	public function get_post_formats() {
		$query_args = $this->get_query_args();
		if ( ! isset( $query_args['post_formats'] ) || ! is_array( $query_args['post_formats'] ) ) {
			return array();
		}

		if ( array_keys( get_post_format_slugs() ) === $query_args['post_formats'] ) {
			return array();
		}

		return $query_args['post_formats'];
	}

	public function get_admin_page() {
		return add_query_arg( 'app', $this->term->slug, admin_url( 'options-general.php?page=enable-mastodon-apps' ) );
	}

	public function get_create_post_type() {
		$create_post_type = get_term_meta( $this->term->term_id, 'create_post_type', true );
		if ( ! $create_post_type ) {
			$create_post_type = 'post';
		}
		return $create_post_type;
	}

	public function get_create_post_format( $raw = false ) {
		$create_post_format = get_term_meta( $this->term->term_id, 'create_post_format', true );
		if ( ! $create_post_format && ! $raw ) {
			$post_formats = $this->get_post_formats();
			$create_post_format = reset( $post_formats );
		}
		return $create_post_format;
	}

	public function get_view_post_types() {
		$view_post_types = get_term_meta( $this->term->term_id, 'view_post_types', true );
		if ( ! $view_post_types ) {
			$view_post_types = 'post';
		}

		if ( ! is_array( $view_post_types ) ) {
			$view_post_types = array( $view_post_types );
		}

		if ( ! in_array( Mastodon_API::ANNOUNCE_CPT, $view_post_types, true ) ) {
			$view_post_types[] = Mastodon_API::ANNOUNCE_CPT;
		}

		if ( ! in_array( Mastodon_API::POST_CPT, $view_post_types, true ) ) {
			$view_post_types[] = Mastodon_API::POST_CPT;
		}

		return $view_post_types;
	}

	public function get_disable_blocks() {
		$options = get_term_meta( $this->term->term_id, 'options', true );
		if ( ! is_array( $options ) ) {
			return false;
		}
		return boolval( $options['blocks'] );
	}

	public function set_client_secret( $client_secret ) {
		return update_term_meta( $this->term->term_id, 'client_secret', $client_secret );
	}

	public function set_create_post_type( $create_post_type ) {
		return update_term_meta( $this->term->term_id, 'create_post_type', $create_post_type );
	}

	public function set_create_post_format( $create_post_format ) {
		return update_term_meta( $this->term->term_id, 'create_post_format', $create_post_format );
	}

	public function set_view_post_types( $view_post_types ) {
		return update_term_meta( $this->term->term_id, 'view_post_types', $view_post_types );
	}

	public function set_post_formats( $post_formats ) {
		$query_args = $this->get_query_args();
		if ( ! is_array( $query_args ) ) {
			$query_args = array();
		}
		$query_args['post_formats'] = $post_formats;
		update_term_meta( $this->term->term_id, 'query_args', $query_args );
		return $this->get_post_formats();
	}

	public function set_disable_blocks( $disable_blocks ) {
		$options = get_term_meta( $this->term->term_id, 'options', true );
		if ( ! is_array( $options ) ) {
			$options = array();
		}
		$options['blocks'] = $disable_blocks;
		return update_term_meta( $this->term->term_id, 'options', $options );
	}

	public function check_redirect_uri( $redirect_uri ) {
		$redirect_uris = $this->get_redirect_uris();
		if ( ! is_array( $redirect_uris ) ) {
			$redirect_uris = array( $redirect_uris );
		}

		foreach ( $redirect_uris as $uri ) {
			if ( $uri === $redirect_uri ) {
				return true;
			}
		}

		return false;
	}

	public function delete_last_requests() {
		return delete_metadata( 'term', $this->term->term_id, 'request' );
	}

	public function get_last_requests() {
		$requests = array();
		foreach ( get_term_meta( $this->term->term_id, 'request' ) as $request ) {
			if ( empty( $request ) || empty( $request['path'] ) ) {
				delete_metadata( 'term', $this->term->term_id, 'request', $request );
				continue;
			}
			$requests[ intval( $request['timestamp'] * 10000 ) ] = $request;
		}

		ksort( $requests );
		return $requests;
	}

	public static function log( $request, $additional_debug_data = array() ) {
		$app = self::get_current_app();
		if ( ! $app ) {
			$app = self::get_debug_app();
		}
		return $app->was_used( $request, $additional_debug_data );
	}

	public function was_used( $request, $additional_debug_data = array() ) {
		static $logged = array();

		$calls = array();
		$line = false;
		foreach ( debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 4 ) as $backtrace ) { // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_debug_backtrace
			if ( 'Enable_Mastodon_Apps\\Mastodon_App' === $backtrace['class'] ) {
				$line = basename( $backtrace['file'] ) . ':' . $backtrace['line'];
				continue;
			}
			$calls = array( $line => $backtrace['function'] . '()' );
			break;
		}
		if ( $line && empty( $calls ) ) {
			$calls[ $line ] = '?';
		}

		$data = array_merge(
			array(
				'timestamp'    => microtime( true ),
				'path'         => $_SERVER['REQUEST_URI'], // phpcs:ignore
				'method'       => $_SERVER['REQUEST_METHOD'], // phpcs:ignore
				'params'       => $request->get_params(),
				'json'         => $request->get_json_params(),
				'files'        => $request->get_file_params(),
				'current_user' => get_current_user_id(),
				'calls'        => $calls,
			),
			$additional_debug_data
		);

		if ( isset( $logged[ $this->get_client_id() ] ) && $logged[ $this->get_client_id() ] ) {
			$previous_data = get_metadata_by_mid( 'term', $logged[ $this->get_client_id() ] );
			if ( is_array( $previous_data->meta_value ) ) {
				foreach ( $previous_data->meta_value as $key => $value ) {
					if ( ! isset( $data[ $key ] ) ) {
						$data[ $key ] = $value;
						continue;
					}
					if ( 'errors' === $key || 'calls' === $key ) {
						$data[ $key ] = array_merge( $data[ $key ], $value );
					}
				}
			}
			update_metadata_by_mid( 'term', $logged[ $this->get_client_id() ], $data );
			return true;
		}

		if ( get_option( 'mastodon_api_debug_mode' ) > time() ) {
			$logged[ $this->get_client_id() ] = add_metadata( 'term', $this->term->term_id, 'request', $data );
		} else {
			$logged[ $this->get_client_id() ] = false;
		}

		if ( $this->get_last_used() < time() - MINUTE_IN_SECONDS ) {
			update_term_meta( $this->term->term_id, 'last_used', time() );
		}

		return $logged[ $this->get_client_id() ];
	}

	/**
	 * Get the text to display to the user about the current settings.
	 *
	 * @param      string $content  The content.
	 *
	 * @return     string  The current settings.
	 */
	public function get_current_settings_text( string $content = '' ) {
		$post_formats = $this->get_post_formats();
		$post_format_strings = array_filter(
			get_post_format_strings(),
			function ( $slug ) use ( $post_formats ) {
				return in_array( $slug, $post_formats, true );
			},
			ARRAY_FILTER_USE_KEY
		);

		if ( empty( $post_format_strings ) ) {
			// translators: %s is a list of post formats.
			$content .= PHP_EOL . sprintf( _n( 'Post Format: %s', 'Post Formats: %s', count( get_post_format_strings() ), 'enable-mastodon-apps' ), __( 'All' ) ); // phpcs:ignore WordPress.WP.I18n.MissingArgDomain
		} else {
			// translators: %s is a list of post formats.
			$content .= PHP_EOL . sprintf( _n( 'Post Format: %s', 'Post Formats: %s', count( $post_format_strings ), 'enable-mastodon-apps' ), implode( ', ', $post_format_strings ) );
		}

		if ( empty( $post_format_strings ) ) {
			$post_format_strings = get_post_format_strings();
		}

		$content .= PHP_EOL . _x( 'Create new posts as', 'select post type', 'enable-mastodon-apps' ) . ': ';
		$content .= get_post_type_object( $this->get_create_post_type() )->labels->singular_name;
		$content .= ' ' . _x( 'in the post format', 'select post type', 'enable-mastodon-apps' ) . ': ';
		if ( $this->get_create_post_format() && isset( $post_format_strings[ $this->get_create_post_format() ] ) ) {
			$content .= $post_format_strings[ $this->get_create_post_format() ];
		} else {
			$content .= reset( $post_format_strings );
		}

		$t = PHP_EOL . __( 'Show these post types', 'enable-mastodon-apps' ) . ': ';
		foreach ( $this->get_view_post_types() as $post_type ) {
			$content .= $t . get_post_type_object( $post_type )->labels->name;
			$t = ', ';
		}

		if ( $this->get_disable_blocks() ) {
			$content .= PHP_EOL . __( 'Automatic conversion to blocks is disabled', 'enable-mastodon-apps' );
		}

		return trim( $content );
	}

	/**
	 * Posts current settings as an announcement just for this app.
	 *
	 * @param      string $title  The title.
	 * @param      string $intro  The intro text.
	 *
	 * @return     int|null  The post ID.
	 */
	public function post_current_settings( string $title = '', string $intro = '' ) {
		if ( get_option( 'mastodon_api_disable_ema_app_settings_changes' ) ) {
			return null;
		}

		if ( ! $title ) {
			$title = sprintf(
				// translators: %s: app name.
				__( 'Settings for %s changed', 'enable-mastodon-apps' ),
				$this->get_client_name()
			);
		}

		if ( ! $intro ) {
			$intro = __( 'The current settings for this app are:', 'enable-mastodon-apps' );
		}
		$content = $this->get_current_settings_text( $intro );

		$previous_posts = get_posts(
			array(
				'post_type'   => Mastodon_API::ANNOUNCE_CPT,
				'post_status' => 'publish',
				'meta_query'  => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					array(
						'key'   => 'ema_app_id',
						'value' => $this->get_client_id(),
					),
				),
			)
		);

		foreach ( $previous_posts as $previous_post ) {
			wp_delete_post( $previous_post->ID );
		}

		$post_id = wp_insert_post(
			array(
				'post_type'    => Mastodon_API::ANNOUNCE_CPT,
				'post_title'   => $title,
				'post_content' => nl2br( trim( $content ) ),
				'post_status'  => 'publish',
				'meta_input'   => array(
					'ema_app_id' => $this->get_client_id(),
				),
			)
		);
		if ( $post_id ) {
			// Assign all post formats so that it will be shown regardless of the app's (potentially later changed) post format settings.
			wp_set_object_terms(
				$post_id,
				array_map(
					function ( $slug ) {
						return 'post-format-' . $slug;
					},
					get_post_format_slugs()
				),
				'post_format'
			);
		}
		return $post_id;
	}

	public static function set_current_app( $client_id, $request ) {
		self::$current_app = self::get_by_client_id( $client_id );
		self::$current_app->was_used( $request );
		return self::$current_app;
	}

	public static function get_current_app() {
		return self::$current_app;
	}

	public function is_outdated() {
		$now = time();
		foreach ( OAuth2\Access_Token_Storage::getAll() as $token ) {
			if ( $token['client_id'] === $this->get_client_id() && $token['expires'] > $now ) {
				return false;
			}
		}
		foreach ( OAuth2\Authorization_Code_Storage::getAll() as $code ) {
			if ( $code['client_id'] === $this->get_client_id() && $code['expires'] > $now ) {
				return false;
			}
		}
		return true;
	}

	public function delete() {
		return wp_delete_term( $this->term->term_id, self::TAXONOMY );
	}

	public static function get_all() {
		$apps = array();
		foreach ( get_terms(
			array(
				'taxonomy'   => self::TAXONOMY,
				'hide_empty' => false,
			)
		) as $term ) {
			if ( $term instanceof \WP_Term ) {
				$app                           = new Mastodon_App( $term );
				$apps[ $app->get_client_id() ] = $app;
			}
		}

		return $apps;
	}

	public static function register_taxonomy() {
		$args = array(
			'labels'       => array(
				'name'          => __( 'Mastodon Apps', 'enable-mastodon-apps' ),
				'singular_name' => __( 'Mastodon App', 'enable-mastodon-apps' ),
				'menu_name'     => __( 'Mastodon Apps', 'enable-mastodon-apps' ),
			),
			'public'       => false,
			'show_ui'      => false,
			'show_in_menu' => false,
			'show_in_rest' => false,
			'rewrite'      => false,
		);

		register_taxonomy( self::TAXONOMY, null, $args );

		register_term_meta(
			self::TAXONOMY,
			'client_secret',
			array(
				'show_in_rest'      => false,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => function ( $value ) {
					if ( ! is_string( $value ) || strlen( $value ) < 16 || strlen( $value ) > 200 ) {
						throw new \Exception( 'invalid-client_secret,Client secret must be a string with a length between 16 and 200 chars.' );
					}
					return $value;
				},
			)
		);

		register_term_meta(
			self::TAXONOMY,
			'redirect_uris',
			array(
				'show_in_rest'      => false,
				'single'            => true,
				'type'              => 'array',
				'sanitize_callback' => function ( $value ) {
					if ( ! is_array( $value ) ) {
						$value = array( $value );
					}
					$urls = array();
					foreach ( $value as $url ) {
						if ( Mastodon_OAuth::OOB_REDIRECT_URI === $url ) {
							$urls[] = $url;
						} elseif ( preg_match( '#^[a-z0-9.-]+://?[a-z0-9.%-]*#i', $url ) ) {
							// custom protocols are ok.
							$urls[] = $url;
						}
					}

					if ( empty( $urls ) ) {
						throw new \Exception( 'invalid-redirect_uris,No valid redirect URIs given' );
					}

					return $urls;
				},
			)
		);

		register_term_meta(
			self::TAXONOMY,
			'client_name',
			array(
				'show_in_rest'      => false,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => function ( $value ) {
					if ( ! is_string( $value ) || strlen( $value ) < 3 || strlen( $value ) > 200 ) {
						throw new \Exception( 'invalid-client_name,Client name must be a string with a length between 3 and 200 chars.' );
					}
					return $value;
				},
			)
		);

		register_term_meta(
			self::TAXONOMY,
			'scopes',
			array(
				'show_in_rest'      => false,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => function ( $value ) {
					if ( ! is_string( $value ) ) {
						$value = '';
					}
					$scopes = array();
					foreach ( explode( ' ', $value ) as $s ) {
						if ( ! trim( $s ) ) {
							continue;
						}
						$scope_parts = explode( ':', $s, 2 );
						$scope = array_shift( $scope_parts );
						if ( ! in_array( $scope, self::VALID_SCOPES, true ) ) {
							throw new \Exception( 'invalid-scopes,Invalid scope given: ' . esc_html( $s ) );
						}
						$scopes[] = $s;
					}

					if ( empty( $scopes ) ) {
						throw new \Exception( 'invalid-scopes,No scopes given.' );
					}
					return implode( ' ', $scopes );
				},
			)
		);

		register_term_meta(
			self::TAXONOMY,
			'website',
			array(
				'show_in_rest'      => false,
				'single'            => true,
				'type'              => 'string',
				'sanitize_callback' => function ( $url ) {
					if ( ! $url ) {
						return '';
					}
					$host = wp_parse_url( $url, PHP_URL_HOST );
					$protocol = wp_parse_url( $url, PHP_URL_SCHEME );

					if ( ! $host || 0 !== strpos( 'https', $protocol ) ) {
						$url = '';
					}

					return $url;
				},
			)
		);

		register_term_meta(
			self::TAXONOMY,
			'creation_date',
			array(
				'show_in_rest'      => false,
				'single'            => true,
				'type'              => 'int',
				'sanitize_callback' => function ( $value ) {
					if ( ! is_int( $value ) ) {
						$value = time();
					}
					return $value;
				},
			)
		);

		register_term_meta(
			self::TAXONOMY,
			'last_used',
			array(
				'show_in_rest'      => false,
				'single'            => true,
				'type'              => 'int',
				'sanitize_callback' => function ( $value ) {
					if ( ! is_int( $value ) ) {
						$value = time();
					}
					return $value;
				},
			)
		);

		register_term_meta(
			self::TAXONOMY,
			'query_args',
			array(
				'show_in_rest'      => false,
				'single'            => true,
				'type'              => 'array',
				'sanitize_callback' => function ( $value ) {
					if ( ! is_array( $value ) ) {
						return array();
					}
					$value = array_diff_key( $value, array( 'post_formats' ) );
					if ( isset( $value['post_formats'] ) ) {
						if ( ! is_array( $value['post_formats'] ) ) {
							$value['post_formats'] = array( $value['post_formats'] );
						}
						$value['post_formats'] = array_filter(
							$value['post_formats'],
							function ( $post_format ) {
								if ( ! in_array( $post_format, get_post_format_slugs(), true ) ) {
									return false;
								}
								return true;
							}
						);
						if ( empty( $value['post_formats'] ) ) {
							unset( $value['post_formats'] );
						}
					}
					return $value;
				},
			)
		);

		register_term_meta(
			self::TAXONOMY,
			'options',
			array(
				'show_in_rest'      => false,
				'single'            => true,
				'type'              => 'array',
				'sanitize_callback' => function ( $value ) {
					if ( ! is_array( $value ) ) {
						return array();
					}

					foreach ( array_keys( $value ) as $key ) {
						if ( 'blocks' === $key ) {
							$value[ $key ] = boolval( $value[ $key ] );
							continue;
						}
						unset( $value[ $key ] );
					}

					return $value;
				},
			)
		);

		if ( get_option( 'mastodon_api_debug_mode' ) > time() ) {
			register_term_meta(
				self::TAXONOMY,
				'request',
				array(
					'show_in_rest'      => false,
					'single'            => false,
					'type'              => 'array',
					'sanitize_callback' => function ( $value ) {
						if ( ! is_array( $value ) ) {
							return array();
						}

						foreach ( array_keys( $value ) as $key ) {
							if ( 'path' === $key || 'user_agent' === $key ) {
								$value[ $key ] = preg_replace( '#[^A-Za-z0-9?&%=[\]+.:@_/()-]#', ' ', $value[ $key ] );
								continue;
							}
							if ( 'status' === $key || 'current_user' === $key ) {
								$value[ $key ] = intval( $value[ $key ] );
								continue;
							}
							if ( 'timestamp' === $key ) {
								$value[ $key ] = floatval( $value[ $key ] );
								continue;
							}
							if ( 'method' === $key && preg_match( '/^[A-Z]{3,15}$/', $value[ $key ] ) ) {
								continue;
							}
							if (
								(
									'files' === $key ||
									'params' === $key ||
									'json' === $key ||
									'errors' === $key ||
									'calls' === $key
								) &&
								! empty( $value[ $key ] )
							) {
								continue;
							}
							unset( $value[ $key ] );
						}

						return $value;
					},
				)
			);
		}
	}

	public function modify_wp_query_args( $args ) {
		$tax_query = array();
		if ( isset( $args['tax_query'] ) ) {
			$tax_query = $args['tax_query'];
		}

		$filter_by_post_format = $this->get_post_formats();
		$post_formats          = get_post_format_slugs();
		$filter_by_post_format = array_filter(
			$filter_by_post_format,
			function ( $post_format ) use ( $post_formats ) {
				return in_array( $post_format, $post_formats, true );
			}
		);
		if ( ! empty( $filter_by_post_format ) ) {
			if ( ! empty( $tax_query ) ) {
				$tax_query['relation'] = 'AND';
			}
			$post_format_query = array(
				'taxonomy' => 'post_format',
				'field'    => 'slug',
			);

			if ( in_array( 'standard', $filter_by_post_format, true ) ) {
				$post_format_query['operator'] = 'NOT IN';
				$post_format_query['terms']    = array_values(
					array_map(
						function ( $post_format ) {
							return 'post-format-' . $post_format;
						},
						array_diff( $post_formats, $filter_by_post_format )
					)
				);
			} else {
				$post_format_query['operator'] = 'IN';
				$post_format_query['terms']    = array_map(
					function ( $post_format ) {
						return 'post-format-' . $post_format;
					},
					$filter_by_post_format
				);
			}
			$tax_query[] = $post_format_query;
		}
		$args['tax_query'] = $tax_query; // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query

		return $args;
	}

	public function has_scope( $requested_scope ) {
		return OAuth2\Scope_Util::checkSingleScope( $requested_scope, $this->get_scopes() );
	}

	/**
	 * Get an app via client_id.
	 *
	 * @param      string $client_id     The client id.
	 *
	 * @return     object|\WP_Error   A Mastodon_App object.
	 */
	public static function get_by_client_id( $client_id ) {
		$term_query = new \WP_Term_Query(
			array(
				'taxonomy'   => self::TAXONOMY,
				'slug'       => $client_id,
				'hide_empty' => false,
			)
		);
		foreach ( $term_query->get_terms() as $term ) {
			return new self( $term );
		}

		return new \WP_Error( 'term_not_found', $client_id );
	}

	public static function delete_outdated() {
		$count = 0;
		foreach ( self::get_all() as $app ) {
			if ( $app->is_outdated() ) {
				if ( $app->delete() ) {
					++$count;
				}
			}
		}
		return $count;
	}

	public static function get_debug_app() {
		$term = term_exists( self::DEBUG_CLIENT_ID, self::TAXONOMY );
		if ( ! $term ) {
			$term = wp_insert_term( self::DEBUG_CLIENT_ID, self::TAXONOMY );
			add_metadata( 'term', $term['term_id'], 'client_name', 'Debugger', true );
		}
		$term = get_term( $term['term_id'] );
		if ( is_wp_error( $term ) ) {
			return $term;
		}

		return new self( $term );
	}

	public static function save( $client_name, array $redirect_uris, $scopes, $website ) { // phpcs:ignore Generic.CodeAnalysis.UnusedFunctionParameter.FoundAfterLastUsed
		$client_id     = strtolower( wp_generate_password( 32, false ) );
		$client_secret = wp_generate_password( 128, false );

		$term = wp_insert_term( $client_id, self::TAXONOMY );

		if ( is_wp_error( $term ) ) {
			return $term;
		}

		$app_metadata = compact(
			'client_name',
			'redirect_uris',
			'scopes',
			'website'
		);

		$post_formats = array();
		if ( get_option( 'mastodon_api_default_create_post_format' ) ) {
			$post_formats[] = get_option( 'mastodon_api_default_create_post_format' );
		}
		/**
		 * Post formats to be enabled for new apps.
		 *
		 * @param array $post_formats    The post formats.
		 *
		 * @return array The post formats.
		 *
		 * Example:
		 * ```php
		 * add_filter( 'mastodon_api_new_app_post_formats', function( $post_formats ) {
		 *    // This will enable standard and aside post formats for new apps.
		 *    return array( 'standard', 'aside' );
		 * } );
		 * ```
		 */
		$post_formats = apply_filters( 'mastodon_api_new_app_post_formats', $post_formats, $app_metadata );

		$app_metadata['query_args'] = array( 'post_formats' => $post_formats );

		$app_metadata['create_post_type'] = get_option( 'mastodon_api_posting_cpt', apply_filters( 'mastodon_api_default_post_type', \Enable_Mastodon_Apps\Mastodon_API::POST_CPT ) );
		$view_post_types = array( 'post', 'comment' );
		if ( ! in_array( $app_metadata['create_post_type'], $view_post_types ) ) {
			$view_post_types[] = $app_metadata['create_post_type'];
		}

		if ( get_option( 'mastodon_api_default_create_post_format' ) && in_array( get_option( 'mastodon_api_default_create_post_format' ), $post_formats ) ) {
			$app_metadata['create_post_format'] = get_option( 'mastodon_api_default_create_post_format' );
		}

		/**
		 * Standard post types that the app can view.
		 *
		 * @param array $view_post_types    The post types.
		 *
		 * @return array The post types.
		 *
		 * Example:
		 * ```php
		 * add_filter( 'mastodon_api_view_post_types', function( $view_post_types ) {
		 *   // This will allow the app to view pages.
		 *   return array_merge( $view_post_types, array( 'page' ) );
		 * } );
		 * ```
		 */
		$app_metadata['view_post_types'] = apply_filters( 'mastodon_api_view_post_types', $view_post_types );

		$term_id = $term['term_id'];
		foreach ( $app_metadata as $key => $value ) {
			add_metadata( 'term', $term_id, $key, $value, true );
		}
		add_metadata( 'term', $term_id, 'client_secret', $client_secret, true );
		add_metadata( 'term', $term_id, 'creation_date', time(), true );

		$term = get_term( $term['term_id'] );
		if ( is_wp_error( $term ) ) {
			return $term;
		}

		$app = new self( $term );
		$app->post_current_settings(
			sprintf(
				// translators: %s: app name.
				__( 'App %s created', 'enable-mastodon-apps' ),
				$client_name
			),
			__( 'This app was created with the following settings:', 'enable-mastodon-apps' )
		);
		return $app;
	}
}
