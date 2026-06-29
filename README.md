# Micro Contact Form

A lightweight, no-configuration WordPress contact form plugin.

Many WordPress contact form plugins are built for flexibility, with dozens of field types, conditional logic, and settings screens. That power comes with complexity, even for the simplest use case. Micro Contact Form does one thing: it gives visitors a straightforward way to contact you. Add the shortcode to any page, post, or widget and it works immediately.

## Requirements

- WordPress 5.9 or higher
- PHP 7.4 or higher
- Tested up to WordPress 7.0

## Installation

1. Install Micro Contact Form through the WordPress.org plugin repository, or upload the `.zip` file via **Admin → Plugins → Add New**.
2. Activate the plugin on the **Admin → Plugins** screen.
3. Add `[micro_contact_form]` to any post, page, or widget.

## Usage

```
[micro_contact_form]
```

Submissions are sent to your WordPress admin e-mail address by default. No database tables are created. Sending uses WordPress's built-in `wp_mail()` function and is compatible with SMTP plugins.

## Configuration Options

All settings are optional. Defaults work without any configuration.

- Change field labels and the submit button text
- Change the required field indicator
- Specify a custom recipient e-mail address
- Include or exclude the blog name in the e-mail subject line
- Add custom text to the e-mail subject line
- Enable or disable the default plugin stylesheet

## Notes

Compatible with the [Analytical Spam Filter](https://wordpress.org/plugins/analytical-spam-filter/) plugin.
