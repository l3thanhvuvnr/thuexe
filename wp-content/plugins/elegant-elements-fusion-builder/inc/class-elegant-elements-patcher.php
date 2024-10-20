<?php
class Elegant_Elements_Patcher {

	/**
	 * What is this patcher used for - plugin or theme?
	 *
	 * @access public
	 * @since 3.4.0
	 * @var string
	 */
	private $patches_for = 'plugin';

	/**
	 * Plugin or theme directory name.
	 *
	 * @access public
	 * @since 3.4.0
	 * @var string
	 */
	private $patch_file_slug = 'elegant-elements-fusion-builder';

	/**
	 * API endpoint.
	 *
	 * @access public
	 * @since 3.4.0
	 * @var string
	 */
	private $patches_root_api = 'https://patcher.infiwebs.com/wp-json/wp/v2/';

	/**
	 * Generates the API url to request patches.
	 *
	 * @access public
	 * @since 3.4.0
	 * @var string
	 */
	private $available_patches_api;

	/**
	 * List of patches.
	 *
	 * @access public
	 * @since 3.4.0
	 * @var array
	 */
	private $patches = array();

	/**
	 * Constructor
	 *
	 * @access public
	 * @since 3.4.0
	 * @return void
	 */
	public function __construct() {
		$this->available_patches_api = $this->patches_root_api . 'patches/slug/' . $this->patch_file_slug;
		add_action( 'init', array( $this, 'check_to_apply_patch' ) );
	}

	/**
	 * Prints patches table.
	 *
	 * @access public
	 * @since 3.4.0
	 * @param array  $attrs   Attributes.
	 * @param string $content Patch content.
	 * @return string
	 */
	public function get_patches( $attrs = array(), $content = '' ) {
		return $this->patches_table_list();
	}

	/**
	 * Check to see if the patch is ready to be applied.
	 *
	 * @access public
	 * @since 3.4.0
	 * @return void
	 */
	public function check_to_apply_patch() {

		if ( isset( $_GET[ 'wp_patcher_apply_' . $this->patch_file_slug ] ) && ! empty( $_GET[ 'wp_patcher_apply_' . $this->patch_file_slug ] ) ) {

			if ( isset( $_GET['apply_patch'] ) && ! empty( $_GET['apply_patch'] ) ) {

				if ( isset( $_GET['_wpnonce'] ) && ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wp-patcher-' . $this->patch_file_slug . '-nonce' ) ) {

					$this->apply_patch( $_GET['apply_patch'], base64_decode( $_GET[ 'wp_patcher_apply_' . $this->patch_file_slug ] ) ); // @codingStandardsIgnoreLine

				}
			}
		}

		if ( isset( $_GET[ 'wp_patcher_download_' . $this->patch_file_slug ] ) && ! empty( $_GET[ 'wp_patcher_download_' . $this->patch_file_slug ] ) ) {

			if ( isset( $_GET['download_patch'] ) && ! empty( $_GET['download_patch'] ) ) {

				if ( isset( $_GET['_wpnonce'] ) && ! empty( $_GET['_wpnonce'] ) && wp_verify_nonce( $_GET['_wpnonce'], 'wp-patcher-' . $this->patch_file_slug . '-nonce' ) ) {

					$this->download_patch( $_GET['download_patch'], base64_decode( $_GET[ 'wp_patcher_download_' . $this->patch_file_slug ] ) ); // @codingStandardsIgnoreLine

				}
			}
		}
	}

	/**
	 * Initialize the patches.
	 *
	 * @access public
	 * @since 3.4.0
	 * @return void
	 */
	private function init_patches() {
		$this->patches = $this->get_available_patches();
	}

	/**
	 * Request and store the available patches.
	 *
	 * @access public
	 * @since 3.4.0
	 * @return string
	 */
	private function get_available_patches() {

		$patches_transient = get_transient( 'wppatcher_patches_' . sanitize_title_with_dashes( $this->patch_file_slug ) );

		if ( false !== $patches_transient ) {
			return $patches_transient;
		}

		$return = array();

		$version = '';

		if ( 'theme' === $this->patches_for ) {
			$theme   = wp_get_theme( $this->patch_file_slug );
			$version = $theme->get( 'Version' );
		} else {
			$all_plugins = get_plugins();
			foreach ( $all_plugins as $plugin_file => $plugin_data ) {
				if ( false !== strpos( $plugin_file, $this->patch_file_slug ) ) {
					$version = $plugin_data['Version'];
					continue;
				}
			}
		}

		$args = array(
			'site'    => home_url(),
			'version' => $version,
		);

		$response = wp_remote_get( $this->available_patches_api . '?' . http_build_query( $args ), array( 'sslverify' => false ) );

		if ( is_array( $response ) && isset( $response['response'] ) && isset( $response['response']['code'] ) && 404 !== $response['response']['code'] ? true : false ) {

			$body = json_decode( $response['body'] );

			if ( ! isset( $body->error ) || 1 !== (int) $body->error ) {
				$return = $body;
			}
		}

		set_transient( 'wppatcher_patches_' . sanitize_title_with_dashes( $this->patch_file_slug ), $return, HOUR_IN_SECONDS * 12 );

		return $return;
	}

	/**
	 * Apply the patch.
	 *
	 * @access public
	 * @since 3.4.0
	 * @param int    $number_id Patch ID.
	 * @param string $file_url  Patch file url.
	 * @return void
	 */
	private function apply_patch( $number_id = false, $file_url = false ) {

		if ( $number_id && $file_url ) {

			$file_url = esc_url( $file_url );

			$file_data = wp_remote_get( $file_url, array( 'sslverify' => false ) );
			$zip       = $file_data['body'];

			if ( $file_data && is_array( $file_data ) && isset( $file_data['response'] ) && isset( $file_data['response']['code'] ) && 200 === $file_data['response']['code'] ) {

				if ( $this->is_zip_file( $file_data ) ) {

					$patch_changes_location = false;

					switch ( $this->patches_for ) {
						case 'plugin':
							if ( $this->plugin_is_installed( $this->patch_file_slug ) ) {
								$patch_changes_location = WP_PLUGIN_DIR . '/' . $this->patch_file_slug;
							}
							break;
						case 'theme':
							if ( $this->theme_is_installed( $this->patch_file_slug ) ) {
								$patch_changes_location = WP_CONTENT_DIR . '/themes/' . $this->patch_file_slug;
							}
							break;
					}

					if ( $patch_changes_location && ! file_exists( $patch_changes_location ) ) {
						$patch_changes_location = false;
					}

					if ( $patch_changes_location ) {

						$uploads_dir = wp_upload_dir( 'basedir' );
						$uploads_dir = $uploads_dir['basedir'];

						if ( ! function_exists( 'WP_Filesystem' ) ) {
							require_once ABSPATH . 'wp-admin/includes/file.php';
						}

						if ( $zip && is_string( $zip ) ) {

							// The path in WP uploads direction that patch zip file will temporary saved.
							$wp_temp_folder = $uploads_dir . '/wp-patcher-tmp/';

							// Create the temporary folder if not exists.
							if ( ! file_exists( $wp_temp_folder ) ) {
								mkdir( $wp_temp_folder, 0777, true );
							}

							// Save temporary patch zip file.
							$wp_temp_file = $wp_temp_folder . sanitize_title_with_dashes( $this->patch_file_slug ) . '.zip';

							// Use the standard PHP file functions. @codingStandardsIgnoreStart
							$fp = fopen( $wp_temp_file, 'w' );
							fwrite( $fp, $zip );
							fclose( $fp );
							// @codingStandardsIgnoreEnd

							WP_Filesystem();

							// Unzip patch zip contents into final destination folder.
							$unzip_file = unzip_file( $wp_temp_file, $patch_changes_location );

							// Delete temporary files.
							unlink( $system_temp_file );
							unlink( $wp_temp_file );

							// Delete the temporary folder in WP uploads direction.
							rmdir( $wp_temp_folder );

							$applied_patches = get_option( 'wp_patcher_applied_patches_' . $this->patch_file_slug );

							// Save applied patch.

							if ( false === $applied_patches ) {
								update_option( 'wp_patcher_applied_patches_' . $this->patch_file_slug, array( $number_id ) );
							} else {
								if ( ! in_array( $number_id, $applied_patches ) ) {
									$applied_patches[] = $number_id;
									update_option( 'wp_patcher_applied_patches_' . $this->patch_file_slug, $applied_patches );
								}
							}
						}
					}
				}
			}
		}

		// Redirect to previous page, that includes patches table list.
		$redirect_to = get_transient( 'redirection-after-' . $this->patch_file_slug . '-apply' );

		if ( $redirect_to ) {
			delete_transient( 'redirection-after-' . $this->patch_file_slug . '-apply' );
			wp_safe_redirect( $redirect_to );
			exit;
		}

		esc_attr_e( 'Something went wrong.', 'elegant-elements' );

		die();
	}

	/**
	 * Download the requested patch file.
	 *
	 * @access public
	 * @since 3.4.0
	 * @param int    $number_id Patch ID.
	 * @param string $file_url  Patch file url.
	 * @return void
	 */
	private function download_patch( $number_id = false, $file_url = false ) {
		if ( $number_id && $file_url ) {
			$file_url = esc_url( $file_url );
			wp_redirect( $file_url ); // @codingStandardsIgnoreLine
			exit;
		}
	}

	/**
	 * Print styles for the patches table.
	 *
	 * @access public
	 * @since 3.4.0
	 * @return void
	 */
	private function stylesheets() {
		?><style type="text/css">
			.wp-patcher-wrap { margin: 0; }
			.patches-table{ width: 100%; margin: 1em 0; text-align: left; background: #fff; border: 1px solid #e7e7e7; border-bottom-color:rgba(0,0,0,.07); border-collapse: separate; border-spacing: 0; }
			.patches-table th, .patches-table td{ padding: 10px 15px; border-bottom: 1px solid #e7e7e7; }
			.patches-table th{ padding: 12px 15px; font-weight: bold; background:#f8f8f8; }
			.patches-table th.ac, .patches-table td.ac{ text-align:center; }
			.patches-table td.ac .button { min-width: 160px; margin: 0 !important;}
			.patches-table td.ac p { margin-top: 10px !important; margin-bottom: 0 !important;}
			.patches-table .dashicons-yes{ width:auto; height:auto; line-height:1em; padding:0; margin:0; font-size: 30px; color: #14ab59; }
			.patches-table th.patch-id { width: 100px; }
			.patches-table tbody { line-height: 1.5em; }
			.wp-patcher-refresh { display: flex; align-items: flex-end; justify-content: flex-end; margin-top: 20px; }
			.wp-patcher-refresh a span { padding-right: 10px; }
			.wp-patcher-refresh a { display: flex !important; align-items: center; padding: 3px 10px !important; }
			.wp-patcher-refresh a .dashicons { font-size: 18px; }
			span.patch-time { font-size: 11px; line-height: 1.5em !important; display: block; }
		</style>
		<?php
	}

	/**
	 * Patches list table.
	 *
	 * @access public
	 * @since 3.4.0
	 * @return string
	 */
	private function patches_table_list() {

		if ( isset( $_GET['refresh-patches'] ) ) {
			delete_transient( 'wppatcher_patches_' . sanitize_title_with_dashes( $this->patch_file_slug ) );
			?>
			<!-- Remove the refresh patches parameter from url to prevent http api calls to server. -->
			<script type="text/javascript">
			jQuery( document ).ready( function() {
				var adminPage = window.location.href.replace( '&refresh-patches=true', '' );
				window.history.replaceState( null, null, adminPage );
			})
			</script>
			<?php
		}

		$this->init_patches();

		// Get array of applied patches.
		$applied_patches = get_option( 'wp_patcher_applied_patches_' . $this->patch_file_slug );

		// Calculate the difference between UTC and local time.
		$time_offset = get_option( 'gmt_offset' ) * 60 * 60;

		// Current page URL.
		$current_page_url = $this->current_page_url();

		set_transient( 'redirection-after-' . $this->patch_file_slug . '-apply', $current_page_url );

		$apply_patch_url = $current_page_url . ( empty( $_SERVER['QUERY_STRING'] ) ? '?' : '&' );

		$table_header = array(
			'patch'       => __( 'PATCH #', 'elegant-elements' ),
			'title'       => __( 'TITLE', 'elegant-elements' ),
			'description' => __( 'DESCRIPTION', 'elegant-elements' ),
			'status'      => __( 'STATUS', 'elegant-elements' ),
			'actions'     => '',
		);

		$table_body = array();

		foreach ( $this->patches as $key => $patch ) {
			if ( ! is_object( $patch ) ) {
				continue;
			}

			$already_applied  = ( ! $applied_patches || ! in_array( $patch->number_id, $applied_patches ) ) ? false : true;
			$previous_applied = false;

			if ( isset( $this->patches[ $key - 1 ] ) && ( ! $applied_patches || ! in_array( $this->patches[ $key - 1 ]->number_id, $applied_patches ) ) ) {
				$previous_applied = false;
			} else {
				$previous_applied = true;
			}

			$patch_formated_local_date = date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $patch->date_gmt ) + $time_offset );

			$table_body[ $key ] = array(
				'patch'       => '#' . $patch->number_id,
				'date'        => $patch_formated_local_date,
				'title'       => $patch->title,
				'description' => $patch->description,
			);

			$patch_file_api = $this->patches_root_api . 'patch_file/' . $patch->number_id . '/?site=' . home_url();
			$nonce          = wp_create_nonce( 'wp-patcher-' . $this->patch_file_slug . '-nonce' );

			$download_patch_url  = $apply_patch_url . 'download_patch=' . $patch->number_id;
			$download_patch_url .= '&wp_patcher_download_' . $this->patch_file_slug . '=' . base64_encode( $patch_file_api ); // @codingStandardsIgnoreLine
			$download_patch_url .= '&_wpnonce=' . $nonce;

			if ( ! $previous_applied ) {
				$previous_patch_id              = isset( $this->patches[ $key - 1 ] ) ? $this->patches[ $key - 1 ]->number_id : '';
				$table_body[ $key ]['status']   = '';
				$table_body[ $key ]['actions']  = '<button class="button button-primary" disabled>' . sprintf( __( 'Apply Patch #%s First', 'elegant-elements' ), $previous_patch_id ) . '</button>';
				$table_body[ $key ]['actions'] .= '<p><a href="' . esc_attr( $download_patch_url ) . '" class="button button-secondary" title="' . __( 'Manually apply patch', 'elegant-elements' ) . '" style="margin-left: 10px;">' . __( 'Download', 'elegant-elements' ) . '</a></p>';

			} elseif ( $already_applied ) {
				$table_body[ $key ]['status']   = '<span class="dashicons dashicons-yes"></span>';
				$table_body[ $key ]['actions']  = '<button class="button button-primary" disabled>' . __( 'Patch Applied' ) . '</button>';
				$table_body[ $key ]['actions'] .= '<p><a href="' . esc_attr( $download_patch_url ) . '" class="button button-secondary" title="' . __( 'Manually apply patch', 'elegant-elements' ) . '" style="margin-left: 10px;">' . __( 'Download', 'elegant-elements' ) . '</a></p>';
			} else {

				$apply_this_patch_url  = $apply_patch_url . 'apply_patch=' . $patch->number_id;
				$apply_this_patch_url .= '&wp_patcher_apply_' . $this->patch_file_slug . '=' . base64_encode( $patch_file_api ); // @codingStandardsIgnoreLine
				$apply_this_patch_url .= '&_wpnonce=' . $nonce;

				$table_body[ $key ]['status']   = '';
				$table_body[ $key ]['actions']  = '<a href="' . esc_attr( $apply_this_patch_url ) . '" class="button button-primary">' . __( 'Apply Patch', 'elegant-elements' ) . '</a>';
				$table_body[ $key ]['actions'] .= '<p><a href="' . esc_attr( $download_patch_url ) . '" class="button button-secondary" title="' . __( 'Manually apply patch', 'elegant-elements' ) . '" style="margin-left: 10px;">' . __( 'Download', 'elegant-elements' ) . '</a></p>';
			}
		}

		ob_start();

		$this->stylesheets();

		$current_page_url = $this->current_page_url();
		$apply_patch_url  = $current_page_url . ( empty( $_SERVER['QUERY_STRING'] ) ? '?' : '&' );

		?>
		<div class="wrap wp-patcher-wrap">
			<div class="wp-patcher-refresh">
				<a href="<?php echo $apply_patch_url; ?>refresh-patches=true" class="button button-primary"><span class="dashicons dashicons-image-rotate"></span><span><?php echo __( 'Refresh Patches', 'elegant-elements' ); ?></span></a>
			</div>
			<table class="patches-table">
				<?php
				if ( ! empty( $table_body ) ) {
					?>
					<thead>
						<tr>
							<th class="patch-id"><?php echo $table_header['patch']; ?></th>
							<th><?php echo $table_header['title']; ?></th>
							<th><?php echo $table_header['description']; ?></th>
							<th class="ac"><?php echo $table_header['status']; ?></th>
							<th class="ac"><?php echo $table_header['actions']; ?></th>
						</tr>
					</thead>
					<?php
				}
				?>
				<tbody>
				<?php
				if ( empty( $table_body ) ) {
					?>
						<tr>
							<td colspan="6" class="ac"><?php echo __( 'No patches available for this version. Click refresh patches button to see if there are any.', 'elegant-elements' ); ?></td>
						</tr>
						<?php
				} else {
					foreach ( $table_body as $k => $v ) {
						?>
						<tr>
							<td><?php echo $v['patch']; ?><br/><span class="patch-time"><?php echo $v['date']; ?></span></td>
							<td><?php echo $v['title']; ?></td>
							<td><?php echo $v['description']; ?></td>
							<td class="ac"><?php echo $v['status']; ?></td>
							<td class="ac"><?php echo $v['actions']; ?></td>
						</tr>
						<?php
					}
				}
				?>
				</tbody>
			</table>
		</div>
		<?php

		$output = ob_get_contents();

		ob_end_clean();

		return $output;
	}

	/**
	 * Get the current page url where the patches are displayed.
	 *
	 * @access public
	 * @since 3.4.0
	 * @return string
	 */
	private function current_page_url() {
		if ( isset( $_SERVER['HTTPS'] ) &&
			( 'on' === $_SERVER['HTTPS'] || 1 === $_SERVER['HTTPS'] ) ||
			isset( $_SERVER['HTTP_X_FORWARDED_PROTO'] ) &&
			'https' === $_SERVER['HTTP_X_FORWARDED_PROTO'] ) {
			$protocol = 'https://';
		} else {
			$protocol = 'http://';
		}

		$host = ( isset( $_SERVER['HTTP_HOST'] ) ) ? $_SERVER['HTTP_HOST'] : '';
		$uri  = ( isset( $_SERVER['REQUEST_URI'] ) ) ? $_SERVER['REQUEST_URI'] : '';

		return $protocol . $host . $uri;
	}

	/**
	 * Check to see if the patch file is zip file.
	 *
	 * @access public
	 * @since 3.4.0
	 * @param string $file Patch file.
	 * @return bool
	 */
	private function is_zip_file( $file = false ) {
		return $file && isset( $file['headers'] ) && 'application/zip' === $file['headers']['content-type'] ? true : false;
	}

	/**
	 * Check to see if the plugin, the patch is being applied for, is installed.
	 *
	 * @access public
	 * @since 3.4.0
	 * @param string $root_folder_name Plugin directory name.
	 * @return bool
	 */
	private function plugin_is_installed( $root_folder_name = '' ) {

		$return = false;

		if ( $root_folder_name && ! empty( $root_folder_name ) ) {

			if ( ! function_exists( 'get_plugins' ) ) {
				require_once ABSPATH . 'wp-admin/includes/plugin.php'; }

			$all_plugins = get_plugins();

			if ( $all_plugins && ! empty( $all_plugins ) ) {
				foreach ( array_keys( $all_plugins ) as $k => $v ) {
					if ( 0 === strpos( $v, $root_folder_name ) ) {
						$return = true;
						break;
					}
				}
			}
		}

		return $return;
	}

	/**
	 * Check to see if the theme, the patch is being applied for, is installed.
	 *
	 * @access public
	 * @since 3.4.0
	 * @param string $root_folder_name Plugin directory name.
	 * @return bool
	 */
	private function theme_is_installed( $root_folder_name = '' ) {

		$return = false;

		if ( $root_folder_name && ! empty( $root_folder_name ) ) {

			if ( ! function_exists( 'wp_get_themes' ) ) {
				require_once ABSPATH . 'wp-admin/includes/theme.php'; }

			$all_themes = wp_get_themes();

			if ( $all_themes && ! empty( $all_themes ) ) {
				foreach ( array_keys( $all_themes ) as $k => $v ) {
					if ( 0 === strpos( $v, $root_folder_name ) ) {
						$return = true;
						break;
					}
				}
			}
		}

		return $return;
	}
}
