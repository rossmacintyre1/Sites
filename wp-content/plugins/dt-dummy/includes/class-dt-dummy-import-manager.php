<?php
/**
 * Manage all import behaviour.
 */

class DT_Dummy_Import_Manager {

	/**
	 * @var DT_Dummy_Content
	 */
	protected $info_obj;

	/**
	 * @var string
	 */
	protected $content_part_id;

	/**
	 * @var string
	 */
	protected $content_dir;

	/**
	 * @var string
	 */
	protected $requested_dummy;

	/**
	 * @var array
	 */
	protected $errors = array();

	protected $importer_obj;

	/**
	 * DT_Dummy_Import_Manager constructor.
	 */
	public function __construct( DT_Dummy_Content $info_obj, $content_part_id, $requested_dummy, $content_dir ) {
		$this->info_obj = $info_obj;
		$this->content_part_id = $content_part_id;
		$this->requested_dummy = array_map( 'trim', explode( ',', $requested_dummy ) );
		$this->content_dir = trailingslashit( $content_dir );
	}

	/**
	 * Import post types dummy.
	 */
	public function import_post_types() {
		$main_dummy = $this->info_obj->get_main_content( $this->content_part_id )->as_array();
		$wc_dummy = $this->info_obj->get_wc_content( $this->content_part_id )->as_array();
		$dummy_to_import = array_intersect_key( array_merge( $main_dummy, $wc_dummy ), array_fill_keys( $this->requested_dummy, '' ) );

		foreach( $dummy_to_import as $dummy_id => $dummy_info ) {
			if ( empty( $dummy_info['file_name'] ) ) {
				continue;
			}

			$import_options = array();
			if ( isset( $dummy_info['replace_attachments'] ) ) {
				$import_options['replace_attachments'] = $dummy_info['replace_attachments'];
			}

			ob_start();
			$this->import_file( $this->content_dir . $dummy_info['file_name'], $import_options );
			$errors = ob_get_clean();

			$this->errors[] = '<p>' . $errors . sprintf( __( 'Import %s : Done.', 'dt-dummy' ), dt_dummy_get_content_nice_name( $dummy_id, $dummy_info ) ) . '</p>';
		}
	}

	/**
	 * Import full dummy content.
	 */
	private function import_full_content() {
		$main_dummy = $this->info_obj->get_main_content( $this->content_part_id )->as_array();
		if ( ! isset( $main_dummy['full_content'] ) ) {
			return;
		}

		$full_content = $main_dummy['full_content'];
		$import_options = array(
			'replace_attachments' => empty( $main_dummy['attachments']['replace_attachments'] ),
		);

		ob_start();
		$this->import_file( $this->content_dir . $full_content['file_name'], $import_options );
		$errors = ob_get_clean();

		$this->errors[] = '<p>' . $errors . __( 'Import Full content : Done.', 'dt-dummy' ) . '</p>';

		// Import theme options.
		$this->requested_dummy[] = 'import_theme_options';
	}

	/**
	 * Import site meta.
	 */
	public function import_site_meta() {
		$site_meta_path = $this->info_obj->get_site_meta( $this->content_part_id )->get();
		if ( ! $site_meta_path ) {
			return;
		}

		$site_meta = json_decode( file_get_contents( $this->content_dir . $site_meta_path ), true );

		// Import theme options.
		if ( isset( $site_meta['theme_options'] ) && in_array( 'import_theme_options', $this->requested_dummy ) ) {
			$known_options = get_option( 'optionsframework' );
			if ( isset( $known_options['id'] ) ) {
				update_option( $known_options['id'], $site_meta['theme_options'] );

				$this->empty_theme_cache();

				$this->errors[] = '<p>' . __( 'Import Theme Options: Done.', 'dt-dummy' ) . '</p>';
			}
		}

		// Import wp settings.
		if ( isset( $site_meta['wp_settings'] ) && in_array( 'pages', $this->requested_dummy ) ) {
			$wp_settings = wp_parse_args( $site_meta['wp_settings'], array(
				'show_on_front' => false,
				'page_on_front' => false,
				'page_for_posts' => false,
			) );

			if ( 'page' === $wp_settings['show_on_front'] ) {
				$page_on_front = $this->importer_get_processed_post( $wp_settings['page_on_front'] );
				if ( 'page' == get_post_type( $page_on_front ) ) {
					update_option( 'show_on_front', 'page' );
					update_option( 'page_on_front', $page_on_front );
				}

				$page_for_posts = $this->importer_get_processed_post( $wp_settings['page_for_posts'] );
				if ( 'page' == get_post_type( $page_for_posts ) ) {
					update_option( 'page_for_posts', $page_for_posts );
				}
			}
		}

		// Import widgets settings.
		if ( ! empty( $site_meta['widgets_settings'] ) && is_array( $site_meta['widgets_settings'] ) ) {
			foreach( $site_meta['widgets_settings'] as $key => $setting) {
				update_option( $key, $setting );
			}
		}

		// Import Ultimate Addons Selected Fonts.
/*
		if ( ! empty( $site_meta['ultimate_selected_google_fonts'] ) && is_array( $site_meta['ultimate_selected_google_fonts'] ) ) {
			$demo_fonts = $site_meta['ultimate_selected_google_fonts'];
			$site_fonts = get_option( 'ultimate_selected_google_fonts', array() );
			if ( empty( $site_fonts ) ) {
				update_option( 'ultimate_selected_google_fonts', $demo_fonts );
			} else {
				$site_fonts_index = wp_list_pluck( $site_fonts, 'font_family' );
				foreach ( $demo_fonts as $google_font ) {
					$site_font_index = array_search( $google_font['font_family'], $site_fonts_index );
					if ( false === $site_font_index ) {
						$site_fonts[] = $google_font;
					} else {
//						$site_fonts[ $site_font_index ]['variants'] = '';
					}
				}

				update_option( 'ultimate_selected_google_fonts', $site_fonts );
			}
		}
*/
	}

	/**
	 * Returns errors string.
	 *
	 * @return string
	 */
	public function get_errors_string() {
		return implode( '', $this->errors );
	}

	/**
	 * Replace attachments with noimage dummies.
	 *
	 * @param $raw_post
	 *
	 * @return mixed
	 */
	public function replace_attachment_url( $raw_post ) {
		if ( isset( $raw_post['post_type'] ) && 'attachment' == $raw_post['post_type'] ) {
			$raw_post['attachment_url'] = $raw_post['guid'] = $this->get_noimage_url( $raw_post['attachment_url'] );
		}

		return $raw_post;
	}

	/**
	 * Import dummy content from a file.
	 *
	 * @param string $file_name
	 * @param array $options
	 */
	private function import_file( $file_name, $options = array() ) {
		$default_options = array(
			'replace_attachments' => true,
			'fetch_attachments' => true
		);
		$options = wp_parse_args( $options, $default_options );

		if ( ! defined( 'WP_LOAD_IMPORTERS' ) ) {
			define( 'WP_LOAD_IMPORTERS', true );
		}

		$import_filepath = apply_filters( 'dt_dummy_filepath', $file_name );

		// Load Importer API
		require_once ABSPATH . 'wp-admin/includes/import.php';
		$import_error = false;

		//check if wp_importer, the base importer class is available, otherwise include it
		if ( ! class_exists( 'WP_Importer' ) ) {
			$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
			if ( file_exists( $class_wp_importer ) ) {
				require_once $class_wp_importer;
			} else {
				$import_error = true;
			}
		}

		//check if the wp import class is available, this class handles the wordpress XML files. If not include it
		//make sure to exclude the init function at the end of the file in kriesi_importer
		if ( ! class_exists( 'WP_Import' ) ) {
			$class_wp_import = dt_dummy_plugin_dir_path() . 'includes/wordpress-importer/wordpress-importer.php';
			if ( file_exists( $class_wp_import ) ) {
				require_once $class_wp_import;
			} else {
				$import_error = true;
			}
		}

		if ( $import_error !== false ) {
			echo "The Auto importing script could not be loaded. please use the wordpress importer and import the XML file that is located in your themes folder manually.";
		} else {

			if ( ! class_exists( 'DT_Dummy_Import', false ) ) {
				require dt_dummy_plugin_dir_path() . 'includes/class-dt-dummy-import.php';
			}

			if ( ! is_file( $import_filepath ) ) {
				echo "The XML file containing the dummy content is not available or could not be read in <pre>".get_template_directory() ."</pre><br/> You might want to try to set the file permission to chmod 777.<br/>If this doesn't work please use the wordpress importer and import the XML file (should be located in your themes folder: dummy.xml) manually <a href='/wp-admin/import.php'>here.</a>";
			} else {

				if ( $options['replace_attachments'] ) {
					add_filter( 'wp_import_post_data_raw', array( &$this, 'replace_attachment_url' ) );
				}

				// woocommerce compatibility
				$this->post_importer_compatibility( $import_filepath );

				$this->importer_obj = new DT_Dummy_Import();
				$this->importer_obj->fetch_attachments = $options['fetch_attachments'];
				$this->importer_obj->import( $import_filepath );
			}
		}
	}

	/**
	 * Returns imported post new id or false.
	 *
	 * @param $post_id
	 *
	 * @return bool
	 */
	private function importer_get_processed_post( $post_id ) {
		if ( $this->importer_obj && isset( $this->importer_obj->processed_posts[ $post_id ] ) ) {
			return $this->importer_obj->processed_posts[ $post_id ];
		}

		return false;
	}

	/**
	 * @param $file
	 */
	public function post_importer_compatibility( $file ) {
		global $wpdb;

		if ( ! dt_dummy_is_wc_active() || ! class_exists( 'WXR_Parser' ) )
			return;

		$parser = new WXR_Parser();
		$import_data = $parser->parse( $file );

		if ( isset( $import_data['posts'] ) ) {
			$posts = $import_data['posts'];

			if ( $posts && sizeof( $posts ) > 0 ) foreach ( $posts as $post ) {

				if ( $post['post_type'] == 'product' ) {

					if ( $post['terms'] && sizeof( $post['terms'] ) > 0 ) {

						foreach ( $post['terms'] as $term ) {

							$domain = $term['domain'];

							if ( strstr( $domain, 'pa_' ) ) {

								// Make sure it exists!
								if ( ! taxonomy_exists( $domain ) ) {

									$nicename = strtolower( sanitize_title( str_replace( 'pa_', '', $domain ) ) );

									$exists_in_db = $wpdb->get_var( $wpdb->prepare( "SELECT attribute_id FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = %s;", $nicename ) );

									// Create the taxonomy
									if ( ! $exists_in_db )
										$wpdb->insert( $wpdb->prefix . "woocommerce_attribute_taxonomies", array( 'attribute_name' => $nicename, 'attribute_type' => 'select', 'attribute_orderby' => 'menu_order' ), array( '%s', '%s', '%s' ) );

									// Register the taxonomy now so that the import works!
									register_taxonomy( $domain,
										apply_filters( 'woocommerce_taxonomy_objects_' . $domain, array('product') ),
										apply_filters( 'woocommerce_taxonomy_args_' . $domain, array(
											'hierarchical' => true,
											'show_ui' => false,
											'query_var' => true,
											'rewrite' => false,
										) )
									);
								}
							}
						}
					}
				}
			}
		}
	}

	/**
	 * Returns dummy image src.
	 *
	 * @param string $origin_img_url
	 *
	 * @return string
	 */
	private function get_noimage_url( $origin_img_url ) {
		switch ( pathinfo( $origin_img_url, PATHINFO_EXTENSION ) ) {
			case 'jpg':
			case 'jpeg':
				$ext = 'jpg';
				break;

			case 'png':
				$ext = 'png';
				break;

			case 'gif':
			default:
				$ext = 'gif';
				break;
		}
		$noimage_fname = 'noimage.' . $ext;

		return dt_dummy_plugin_dir_url( 'admin/images/' . $noimage_fname );
	}

	/**
	 * Empty theme css cache.
	 */
	private function empty_theme_cache() {
		if ( function_exists( 'presscore_set_force_regenerate_css' ) ) {
			presscore_set_force_regenerate_css( true );
		}

		if ( function_exists( 'presscore_cache_loader_inline_css' ) ) {
			presscore_cache_loader_inline_css( '' );
		}
	}
}
