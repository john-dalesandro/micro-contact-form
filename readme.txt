=== Micro Contact Form ===
Contributors: dalesandro
Tags: contact, form, email, lightweight, simple
Requires at least: 5.9
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 1.0.6
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A simple contact form with no setup required. Add [micro_contact_form] to any page, post, or widget and visitors can send you a message.

== Description ==
Most contact form plugins are built for flexibility, with dozens of field types, conditional logic, integrations, and settings screens. That power comes with complexity, even for the simplest use case.

Micro Contact Form does one thing: it gives visitors a simple way to contact you. The form includes name, email, subject, and message fields. No setup required, no database tables, no clutter.

Add the shortcode **[micro_contact_form]** to any page, post, or widget and visitors can contact you immediately. Submissions are sent to your WordPress admin email address by default, or to a custom address configured in the plugin settings.

Provides a simple way for visitors to contact you without exposing your email address publicly on your site.

**Key Features**

* No setup required; works immediately after activation.
* Sends email via WordPress's wp_mail() function and is compatible with SMTP plugins.
* Messages are delivered by email and are not stored in the WordPress database by this plugin.
* Optional settings to customize field labels, the recipient address, and the email subject line.
* Compatible with the **Analytical Spam Filter** plugin.

Micro Contact Form collects:

* Message subject
* Message content
* From name
* Reply email address

**Configuration Options**

* Change the displayed labels for the name, email, subject, and message fields as well as the submit button.
* Change the required field indicator.
* Change the email address that receives form submissions.
* Include or exclude the blog name in the email subject line.
* Add additional text to the email subject line.
* Enable or disable the default plugin stylesheet.

== Installation ==
= Install =
1. Install Micro Contact Form through the WordPress.org plugin repository or by uploading the .zip file using the Admin → Plugins → Add New function.
2. Activate Micro Contact Form on the Admin → Plugins screen.

= Uninstall =
1. Deactivate the plugin on the Admin → Plugins screen. All plugin files will be retained.
2. Delete the plugin on the Admin → Plugins screen. This deletes both the plugin files and all plugin settings stored in the database.

== Frequently Asked Questions ==
= Messages are not sent =

The plugin uses the WordPress wp_mail() function. Please verify that all WordPress settings, SMTP plugin settings, and the server / host settings are correct and functioning as expected. Also verify that the recipient email address configured in the plugin settings (Settings → Micro Contact Form) is correct.

= Messages are sent but not received =

If you have verified that the message was actually sent, then verify that the recipient email address is correct and able to receive other emails. Verify that the email was not flagged as spam and routed to the spam or junk mail folders for that email address.

= The contact form is not displaying =

Verify that the correct shortcode is used: **[micro_contact_form]**

The shortcode can be added to a page or post using a Shortcode block, or to a sidebar using any widget that supports shortcodes (such as the Text widget or the Shortcode widget).

= I see a "Security check failed" error when submitting the form =

This is most commonly caused by a caching plugin that has cached the page including the security nonce. Nonces are time-limited and cannot be cached. Exclude the page containing the contact form from your caching plugin's cache, or configure your caching plugin to exclude pages with POST data.

== Screenshots ==
1. Micro Contact Form - Generated form.
2. Micro Contact Form - Shortcode block.
3. Micro Contact Form - General Settings.

== Changelog ==
= 1.0.6 =
* Minor code quality improvements

= 1.0.5 =
* Security: Added nonce verification to form submissions
* Improvement: Stylesheet now loads reliably in all contexts including widgets
* Improvement: Plugin settings are now cached for the duration of the request to avoid redundant database reads
* Improvement: Invalid email addresses entered in the plugin settings now display an error and retain the previous value rather than being silently discarded
* Improvement: All settings labels and descriptions reviewed and corrected
* Bug fix: Textdomain was registered too late to load translations
* Bug fix: Stylesheet was registered on two hooks causing potential double-enqueue

= 1.0.4 =
* Minor bug fixes: Corrects a compatibility issue with the Analytical Spam Filter plugin when the shortcode is used in a widget and simplifies the use of the default plugin stylesheet.

= 1.0.3 =
* Minor bug fixes (corrects compatibility issue with the Analytical Spam Filter plugin)

= 1.0.2 =
* Minor bug fixes (corrects function visibility)

= 1.0.1 =
* Bug fixes (resolves warning about null post_content)

= 1.0.0 =
* Initial Release