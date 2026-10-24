=== Scope Nine Alert Bar ===
Contributors: abdullahsajjad
Tags: alert bar, notification bar, announcement bar, header bar, notice
Requires at least: 5.0
Tested up to: 7.1
Requires PHP: 7.1
Stable tag: 1.0.1
License: GPL-2.0+
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A lightweight, fast alert bar for WordPress. Show customizable notification banners with live preview and responsive design.

== Description ==

Scope Nine Alert Bar adds a clean, dismissible announcement bar to the top of your site. Use it for sales, launches, shipping notices, maintenance windows, or any message visitors shouldn't miss.

You design the bar on one settings screen and see a live preview as you go. Nothing to code, no page builder, and no extra requests on the frontend beyond one small script.

= Features =

* **Live preview** – every change shows up instantly while you edit
* **Rich text message** – bold, italic, underline, links, and text color
* **Your colors** – background and text color with a color picker or hex value
* **Font size and padding** – fine-tune top, right, bottom, and left spacing
* **Dismissible** – visitors can close the bar; upload your own close icon if you like
* **Placement** – show the bar before or after your theme header
* **Targeting** – show it site-wide, on the homepage only, or on specific pages and posts
* **Feature a post** – link the bar to any post, with an optional custom title and expiry date
* **On/off switch** – turn the bar off without losing your settings
* **Responsive** – looks right on phones, tablets, and desktops
* **Lightweight** – no jQuery on the frontend, no tracking, no external requests

= How it works =

1. Go to **Alert Bar → Design** in your WordPress admin.
2. Write your message in the editor.
3. Pick colors, spacing, placement, and where it should appear.
4. Save. The bar appears on your site right away.

= Feature a post (optional) =

1. Edit the post you want to promote.
2. In the **Alert Bar** box, tick **Feature in Alert Bar**.
3. Optionally set a custom link title and an expiry date.

The bar then links to that post until it expires. Only administrators can choose the featured post.

== Installation ==

1. Upload the `scope-nine-alert-bar` folder to `/wp-content/plugins/`
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to **Alert Bar > Design** to configure your alert bar

== Frequently Asked Questions ==

= Can I show the bar only on certain pages? =

Yes! In the Display On setting, choose "Specific Pages Only" and select which pages and posts should display the alert bar.

= Can I use HTML in the alert message? =

Yes, the WYSIWYG editor supports bold, italic, underline, links, and text color formatting.

= How do I style the bar beyond the built-in options? =

Use WordPress's own Additional CSS (Appearance → Customize → Additional CSS on classic themes, or the Site Editor on block themes). Target `.scopenine-alert-bar` for the bar and `.scopenine-alert-bar-close` for the close button.

= Who can feature a post in the bar? =

Only administrators (users with the `manage_options` capability). Authors and editors don't see the Alert Bar box, and only published posts are linked.

= Is the alert bar responsive? =

Yes, the alert bar is fully responsive and adapts to all screen sizes.

== Screenshots ==

1. The alert bar on the frontend
2. Design settings with live preview
3. Display options and page targeting
4. The Alert Bar box for featuring a post

== Changelog ==

= 1.0.1 =
* Security: only administrators can set or clear the featured alert bar post
* Fix: saving an unrelated post no longer removes the featured post from the bar
* Fix: drafts and private posts are never linked in the bar
* Updated plugin description

= 1.0.0 =
* Initial release
* Live preview in admin
* WYSIWYG content editor
* Custom colors with hex input
* Individual padding controls
* Responsive design
* Display mode options (site-wide, homepage, specific pages)
* Position control (before/after header)
* Custom close icon upload
* Post linking with expiry dates

== Upgrade Notice ==

= 1.0.1 =
Tightens who can change the featured alert bar post. Recommended for all users.

= 1.0.0 =
Initial release of Scope Nine Alert Bar.
