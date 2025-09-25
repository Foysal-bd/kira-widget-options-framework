# Kira Widget Options Framework

A comprehensive framework for creating custom widget options with various field types. This framework provides methods to generate HTML form fields for WordPress widgets, including text, textarea, select, radio, checkbox, and color picker fields.

## Features

- **Text Input Fields** - Single-line text inputs
- **Textarea Fields** - Multi-line text areas
- **Select Dropdowns** - With WordPress data integration (pages, posts, menus, users)
- **Radio Button Groups** - Single selection options
- **Checkbox Groups** - Multiple selection options
- **Color Picker Fields** - WordPress color picker integration
- **Built-in Security** - Proper sanitization and wp_kses() filtering
- **WordPress Standards** - Fully compliant with WordPress coding standards

## Installation

There are 2 ways to include **Kira** into your project:

1. **As a Plugin** - Install and activate the plugin
2. **Embedded** - Include the framework files in your project

## Usage

### Method 1: Using as a Plugin

When using the framework as a plugin, use the global variable:

```php
public function form( $instance ) {
	global $kira_widget_options_framework;
	
	// Text Field.
	$kira_widget_options_framework->text(
		array(
			'name'        => esc_attr( $this->get_field_name( 'text' ) ),
			'label'       => __( 'Text Field', 'kira' ),
			'description' => __( 'This is a text field', 'kira' ),
			'value'       => isset( $instance['text'] ) ? $instance['text'] : '',
		)
	);
	
	// Select Field with WordPress data.
	$kira_widget_options_framework->select(
		array(
			'name'        => esc_attr( $this->get_field_name( 'menu' ) ),
			'label'       => __( 'Select Menu', 'kira' ),
			'description' => __( 'Choose a navigation menu', 'kira' ),
			'options'     => 'menu', // Uses WordPress menus.
			'value'       => isset( $instance['menu'] ) ? $instance['menu'] : '',
		)
	);
	
	// Color Field.
	$kira_widget_options_framework->color(
		array(
			'name'        => esc_attr( $this->get_field_name( 'color' ) ),
			'label'       => __( 'Accent Color', 'kira' ),
			'description' => __( 'Choose a color', 'kira' ),
			'value'       => isset( $instance['color'] ) ? $instance['color'] : '#ffffff',
			'default'     => '#ffffff',
		)
	);
}
```

### Method 2: Using as Embedded Framework

When embedding the framework in your project, create an instance of the class:

```php
// Include the framework file.
require_once 'path/to/kira-widget-options-framework/includes/class-kira-widget-options-framework.php';

public function form( $instance ) {
	// Create instance of the framework.
	$kira_framework = new Kira_Widget_Options_Framework();
	
	// Text Field.
	$kira_framework->text(
		array(
			'name'  => esc_attr( $this->get_field_name( 'title' ) ),
			'label' => __( 'Widget Title', 'kira' ),
			'value' => isset( $instance['title'] ) ? $instance['title'] : '',
		)
	);
	
	// Radio Field.
	$kira_framework->radio(
		array(
			'name'    => esc_attr( $this->get_field_name( 'layout' ) ),
			'label'   => __( 'Layout Style', 'kira' ),
			'options' => array(
				'horizontal' => __( 'Horizontal', 'kira' ),
				'vertical'   => __( 'Vertical', 'kira' ),
			),
			'value'   => isset( $instance['layout'] ) ? $instance['layout'] : 'horizontal',
		)
	);
	
	// Checkbox Field.
	$kira_framework->checkbox(
		array(
			'name'    => esc_attr( $this->get_field_name( 'features' ) ),
			'label'   => __( 'Enable Features', 'kira' ),
			'options' => array(
				'feature1' => __( 'Feature 1', 'kira' ),
				'feature2' => __( 'Feature 2', 'kira' ),
				'feature3' => __( 'Feature 3', 'kira' ),
			),
			'value'   => isset( $instance['features'] ) ? $instance['features'] : array(),
		)
	);
}
```

## Field Parameters

All field methods accept the following parameters:

- `name` (string) - Field name attribute
- `label` (string) - Field label text
- `description` (string) - Optional description text
- `value` (mixed) - Current field value
- `html_class` (string) - Additional CSS classes
- `html_id` (string) - HTML ID attribute

### Additional Parameters

- **Color Field**: `default` (string) - Default color value
- **Select/Radio/Checkbox**: `options` (array|string) - Options array or helper type ('page', 'post', 'menu', 'user')

## Helper Options

For select, radio, and checkbox fields, you can use these helper strings instead of arrays:

- `'page'` - Lists all pages
- `'post'` - Lists all posts  
- `'menu'` - Lists all navigation menus
- `'user'` - Lists all users

## Upgrade from Version 1.0 to 1.1

**BREAKING CHANGE**: The framework methods now echo output directly instead of returning HTML strings.

### Before (v1.0):
```php
// Plugin usage.
global $kira_widget_options_framework;
echo $kira_widget_options_framework->text(
	array(
		'name'  => esc_attr( $this->get_field_name( 'text' ) ),
		'value' => @$instance['text'], // Deprecated syntax.
	)
);

// Embedded usage.
$kira_framework = new Kira_Widget_Options_Framework();
echo $kira_framework->text(
	array(
		'name'  => esc_attr( $this->get_field_name( 'text' ) ),
		'value' => @$instance['text'], // Deprecated syntax.
	)
);
```

### After (v1.1):
```php
// Plugin usage.
global $kira_widget_options_framework;
$kira_widget_options_framework->text(
	array(
		'name'  => esc_attr( $this->get_field_name( 'text' ) ),
		'value' => isset( $instance['text'] ) ? $instance['text'] : '', // Modern syntax.
	)
);

// Embedded usage.
$kira_framework = new Kira_Widget_Options_Framework();
$kira_framework->text(
	array(
		'name'  => esc_attr( $this->get_field_name( 'text' ) ),
		'value' => isset( $instance['text'] ) ? $instance['text'] : '', // Modern syntax.
	)
);
```

**Important**: Remove all `echo` statements when calling framework methods. The methods now handle output internally.

## Security Features

- **Input Sanitization** - All inputs are properly escaped using WordPress functions
- **Output Filtering** - HTML output is filtered through wp_kses()
- **XSS Protection** - Prevents malicious script injection
- **WordPress Standards** - Follows WordPress coding standards

## Requirements

- WordPress 4.0 or higher
- PHP 5.6 or higher

## License

GPL-2.0

## Repository

- **GitHub**: https://github.com/sabuz/kira-widget-options-framework
- **Issues**: https://github.com/sabuz/kira-widget-options-framework/issues
- **Documentation**: https://github.com/sabuz/kira-widget-options-framework/blob/master/README.md

## Author

**Nazmul Sabuz**
- WordPress Profile: https://profiles.wordpress.org/nazsabuz/
- GitHub: https://github.com/sabuz
