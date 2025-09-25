<?php
/**
 * Kira Widget Options Framework
 *
 * A comprehensive framework for creating custom widget options with various field types.
 * This class provides methods to generate HTML form fields for WordPress widgets,
 * including text, textarea, select, radio, checkbox, and color picker fields.
 *
 * @package Kira_Widget_Options_Framework
 * @since 1.0
 * @author Nazmul Sabuz
 */

/**
 * Main framework class for widget options
 *
 * This class handles the initialization and provides methods for creating
 * various types of form fields for WordPress widgets. It includes CSS and
 * JavaScript for enhanced functionality like color pickers.
 *
 * @package Kira_Widget_Options_Framework
 * @since 1.0
 */
class Kira_Widget_Options_Framework {

	/**
	 * Constructor
	 *
	 * Initializes the framework by hooking into WordPress admin actions
	 * to add necessary CSS and JavaScript for widget functionality.
	 *
	 * @since 1.0
	 */
	public function __construct() {
		add_action( 'admin_head-widgets.php', [ $this, 'header_scripts' ], 99 );
		add_action( 'admin_footer-widgets.php', [ $this, 'footer_scripts' ], 99 );
	}

	/**
	 * Output CSS styles for widget controls
	 *
	 * Adds custom CSS styles to the admin head specifically for the widgets page.
	 * These styles ensure proper layout and appearance of widget control groups.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function header_scripts() {
		echo '<style>
            .kira-widget-control-group-wrap {
                display: block;
                width: 100%;
                clear: both;
                margin-bottom: 5px;
            }
            .kira-widget-control-group-wrap label {
                display: block;
                clear: both;
            }
        </style>';
	}

	/**
	 * Output JavaScript for enhanced widget functionality
	 *
	 * Adds JavaScript to initialize color picker functionality for widget controls.
	 * The script handles both existing widgets and dynamically added/updated widgets.
	 *
	 * @since 1.0
	 * @return void
	 */
	public function footer_scripts() {
		echo '<script>
            (function($) {
                function initColorPicker(widget) {
                    $(".color-picker", widget).wpColorPicker({
                        change: function(e, ui) {
                            $(e.target).val(ui.color.toString());
                            $(e.target).trigger("change");
                        },
                        clear: function(e, ui) {
                            $(e.target).trigger("change");
                        }
                    });
                }

                $(document).ready(function() {
                    $("#widgets-right .widget:has(.color-picker)").each(function() {
                        initColorPicker($(this));
                    });
                });

                $(document).on("widget-added widget-updated", function(event, widget) {
                    initColorPicker(widget);
                });
            })(jQuery);
		</script>';
	}

	/**
	 * Helper method to generate option arrays for select fields
	 *
	 * Creates associative arrays of options for select, radio, and checkbox fields
	 * based on WordPress data sources like pages, posts, menus, and users.
	 *
	 * @since 1.0
	 * @param string $args The type of data to retrieve ('page', 'post', 'menu', 'user').
	 * @return array Associative array of options (ID => Name/Title)
	 */
	protected function _helper( $args ) {
		$arr = [];

		switch ( $args ) {
			case 'page':
				$pages = get_posts(
					[
						'post_type'      => 'page',
						'orderby'        => 'date',
						'order'          => 'DESC',
						'posts_per_page' => -1,
					]
				);
				if ( $pages ) {
					foreach ( $pages as $page ) {
						$arr[ $page->ID ] = $page->post_title;
					}
				}

				break;

			case 'post':
				$posts = get_posts(
					[
						'post_type'      => 'post',
						'orderby'        => 'date',
						'order'          => 'DESC',
						'posts_per_page' => -1,
					]
				);
				if ( $posts ) {
					foreach ( $posts as $post ) {
						$arr[ $post->ID ] = $post->post_title;
					}
				}

				break;

			case 'menu':
				$menus = wp_get_nav_menus();
				if ( $menus ) {
					foreach ( $menus as $menu ) {
						$arr[ $menu->term_id ] = $menu->name;
					}
				}

				break;

			case 'user':
				$users = get_users();
				if ( $users ) {
					foreach ( $users as $user ) {
						$arr[ $user->ID ] = $user->display_name;
					}
				}

				break;
		}

		return $arr;
	}

	/**
	 * Generate a text input field
	 *
	 * Creates a single-line text input field with label and optional description.
	 * Perfect for simple text inputs like titles, names, or short values.
	 *
	 * @since 1.0
	 * @param array $args {
	 *     Field configuration arguments.
	 *
	 *     @type string $name        Field name attribute.
	 *     @type string $label       Field label text.
	 *     @type string $description Optional description text.
	 *     @type string $value       Current field value.
	 *     @type string $html_class  Additional CSS classes.
	 *     @type string $html_id     HTML ID attribute.
	 * }
	 * @return string HTML markup for the text field
	 */
	public function text( $args ) {
		$defaults = [
			'name'        => '',
			'label'       => '',
			'description' => '',
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
		];

		$args = wp_parse_args( $args, $defaults );

		$html = '<p>
			<label for="' . $args['name'] . '" class="widefat">' . $args['label'] . '</label>
            <input type="text" name="' . $args['name'] . '" class="widefat" value="' . $args['value'] . '">';

		if ( ! empty( $args['description'] ) ) {
			$html .= '<span class="description">' . $args['description'] . '</span>';
		}

		$html .= '</p>';

		return $html;
	}

	/**
	 * Generate a textarea field
	 *
	 * Creates a multi-line textarea field with label and optional description.
	 * Ideal for longer text content like descriptions, content blocks, or notes.
	 *
	 * @since 1.0
	 * @param array $args {
	 *     Field configuration arguments.
	 *
	 *     @type string $name        Field name attribute.
	 *     @type string $label       Field label text.
	 *     @type string $description Optional description text.
	 *     @type string $value       Current field value.
	 *     @type string $html_class  Additional CSS classes.
	 *     @type string $html_id     HTML ID attribute.
	 * }
	 * @return string HTML markup for the textarea field
	 */
	public function textarea( $args ) {
		$defaults = [
			'name'        => '',
			'label'       => '',
			'description' => '',
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
		];

		$args = wp_parse_args( $args, $defaults );

		$html = '<p>
			<label for="' . $args['name'] . '" class="widefat">' . $args['label'] . '</label>
            <textarea name="' . $args['name'] . '" class="widefat">' . $args['value'] . '</textarea>';

		if ( ! empty( $args['description'] ) ) {
			$html .= '<span class="description">' . $args['description'] . '</span>';
		}

		$html .= '</p>';

		return $html;
	}

	/**
	 * Generate a select dropdown field
	 *
	 * Creates a dropdown select field with options. Options can be provided as
	 * an array or as a string to use helper data (page, post, menu, user).
	 *
	 * @since 1.0
	 * @param array $args {
	 *     Field configuration arguments.
	 *
	 *     @type string $name        Field name attribute
	 *     @type string $label       Field label text
	 *     @type string $description Optional description text
	 *     @type array|string $options Array of options or helper type ('page', 'post', 'menu', 'user').
	 *     @type string $value       Current field value
	 *     @type string $html_class  Additional CSS classes
	 *     @type string $html_id     HTML ID attribute
	 * }
	 * @return string HTML markup for the select field
	 */
	public function select( $args ) {
		$defaults = [
			'name'        => '',
			'label'       => '',
			'description' => '',
			'options'     => [],
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
		];

		$args = wp_parse_args( $args, $defaults );

		if ( is_string( $args['options'] ) ) {
			$args['options'] = $this->_helper( $args['options'] );
		}

		$html = '<p>
			<label for="' . $args['name'] . '" class="widefat">' . $args['label'] . '</label>
			<select name="' . $args['name'] . '" class="widefat">';

		if ( ! empty( $args['options'] ) ) {
			foreach ( $args['options'] as $key => $value ) {
				$html .= '<option value="' . esc_html( $key ) . '" ' . ( esc_html( $args['value'] ) === esc_html( $key ) ? 'selected' : '' ) . '>' . esc_html( $value ) . '</option>';
			}
		}

		$html .= '</select>';

		if ( ! empty( $args['description'] ) ) {
			$html .= '<span class="description">' . $args['description'] . '</span>';
		}

		$html .= '</p>';

		return $html;
	}

	/**
	 * Generate radio button fields
	 *
	 * Creates a group of radio buttons with options. Options can be provided as
	 * an array or as a string to use helper data (page, post, menu, user).
	 * Only one option can be selected at a time.
	 *
	 * @since 1.0
	 * @param array $args {
	 *     Field configuration arguments.
	 *
	 *     @type string $name        Field name attribute
	 *     @type string $label       Field label text
	 *     @type string $description Optional description text
	 *     @type array|string $options Array of options or helper type ('page', 'post', 'menu', 'user').
	 *     @type string $value       Current field value
	 *     @type string $html_class  Additional CSS classes
	 *     @type string $html_id     HTML ID attribute
	 * }
	 * @return string HTML markup for the radio button group
	 */
	public function radio( $args ) {
		$defaults = [
			'name'        => '',
			'label'       => '',
			'description' => '',
			'options'     => [],
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
		];

		$args = wp_parse_args( $args, $defaults );

		if ( is_string( $args['options'] ) ) {
			$args['options'] = $this->_helper( $args['options'] );
		}

		$html = '<p>
			<label for="' . $args['name'] . '" class="widefat">' . $args['label'] . '</label>
			<span class="kira-widget-control-group-wrap">';

		if ( ! empty( $args['options'] ) ) {
			foreach ( $args['options'] as $key => $value ) {
				$uid   = uniqid( null, $args['name'] );
				$html .= '<label for="' . $uid . '"><input type="radio" name="' . $args['name'] . '" id="' . $uid . '" value="' . esc_html( $key ) . '" ' . ( esc_html( $args['value'] ) === esc_html( $key ) ? 'checked' : '' ) . '>' . esc_html( $value ) . '</label>';
			}
		}

		$html .= '</span>';

		if ( ! empty( $args['description'] ) ) {
			$html .= '<span class="description">' . $args['description'] . '</span>';
		}

		$html .= '</p>';

		return $html;
	}

	/**
	 * Generate checkbox fields
	 *
	 * Creates a group of checkbox fields with options. Options can be provided as
	 * an array or as a string to use helper data (page, post, menu, user).
	 * Multiple options can be selected at the same time.
	 *
	 * @since 1.0
	 * @param array $args {
	 *     Field configuration arguments.
	 *
	 *     @type string $name        Field name attribute
	 *     @type string $label       Field label text
	 *     @type string $description Optional description text
	 *     @type array|string $options Array of options or helper type ('page', 'post', 'menu', 'user').
	 *     @type array $value       Current field values (array of selected values).
	 *     @type string $html_class  Additional CSS classes
	 *     @type string $html_id     HTML ID attribute
	 * }
	 * @return string HTML markup for the checkbox group
	 */
	public function checkbox( $args ) {
		$defaults = [
			'name'        => '',
			'label'       => '',
			'description' => '',
			'options'     => [],
			'value'       => '',
			'html_class'  => '',
			'html_id'     => '',
		];

		$args = wp_parse_args( $args, $defaults );

		if ( is_string( $args['options'] ) ) {
			$args['options'] = $this->_helper( $args['options'] );
		}

		$html = '<p>
			<label for="' . $args['name'] . '" class="widefat">' . $args['label'] . '</label>
			<span class="kira-widget-control-group-wrap">';

		if ( ! empty( $args['options'] ) ) {
			foreach ( $args['options'] as $key => $value ) {
				$uid   = uniqid( null, $args['name'] );
				$html .= '<label for="' . $uid . '"><input type="checkbox" name="' . $args['name'] . '[]" id="' . $uid . '" value="' . esc_html( $key ) . '" ' . ( in_array( esc_html( $key ), $args['value'], true ) ? 'checked' : '' ) . '>' . esc_html( $value ) . '</label>';
			}
		}

		$html .= '</span>';

		if ( ! empty( $args['description'] ) ) {
			$html .= '<span class="description">' . $args['description'] . '</span>';
		}

		$html .= '</p>';

		return $html;
	}

	/**
	 * Generate a color picker field
	 *
	 * Creates a color picker input field using WordPress's built-in color picker.
	 * Automatically enqueues the required WordPress color picker scripts and styles.
	 * Includes a default color option and proper initialization.
	 *
	 * @since 1.0
	 * @param array $args {
	 *     Field configuration arguments.
	 *
	 *     @type string $name        Field name attribute
	 *     @type string $label       Field label text
	 *     @type string $description Optional description text
	 *     @type string $value       Current field value (hex color code).
	 *     @type string $default     Default color value (hex color code).
	 * }
	 * @return string HTML markup for the color picker field
	 */
	public function color( $args ) {
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker' );

		$defaults = [
			'name'        => '',
			'label'       => '',
			'description' => '',
			'value'       => '#ffffff',
			'default'     => '#ffffff',
		];

		$args = wp_parse_args( $args, $defaults );

		$html = '<p>
			<label for="' . $args['name'] . '" class="widefat">' . $args['label'] . '</label>
            <input type="text" name="' . $args['name'] . '" class="color-picker" value="' . $args['value'] . '" data-default-color="' . $args['default'] . '">';

		if ( ! empty( $args['description'] ) ) {
			$html .= '<span class="description">' . $args['description'] . '</span>';
		}

		$html .= '</p>';

		return $html;
	}
}
