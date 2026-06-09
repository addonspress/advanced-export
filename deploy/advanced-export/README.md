# Advanced Export

A WordPress plugin that exports site data (posts, pages, media, widgets, customizer settings) into a downloadable ZIP file. Exported data can be imported using the [Advanced Import](https://wordpress.org/plugins/advanced-import/) plugin.

## Requirements

- WordPress 5.5+
- PHP 7.4+
- ZipArchive PHP extension

## Features

- Export all content or filter by post type (posts, pages, media, custom post types)
- Filter by author, category, date range, and post status
- Export widget data
- Export customizer/options data
- Include actual media files in export
- Exported ZIP importable via Advanced Import plugin

## Installation

1. Upload the plugin files to `/wp-content/plugins/advanced-export/`
2. Activate the plugin through the "Plugins" menu in WordPress
3. Navigate to **Tools → Advanced Export**

## Security Hardening (v2.0.0)

Version 2.0.0 includes comprehensive security hardening for WordPress.org submission:

- Nonce verification on all AJAX handlers
- Capability checks on all admin actions
- `wp_unslash()` on all superglobal accesses
- Input sanitization with `sanitize_text_field()`, `sanitize_key()`, `absint()`
- `$wpdb->prepare()` for all database queries
- `sanitize_file_name()` for filesystem paths
- WP_Filesystem API for file operations
- Temp directory cleanup on uninstall

## Hooks

### Filters

- `advanced_export_page_slug` — Change the admin page slug
- `advanced_export_capability` — Change the required capability (default: `export`)
- `advanced_export_ignore_post_types` — Post types to exclude from export
- `advanced_export_include_options` — Additional options to include in export
- `advanced_export_all_options` — Return `true` to export all options

### Actions

- `advanced_export_before_create_data_files` — Fires before data files are generated
- `advanced_export_form` — Fires inside the export form to add custom fields

## License

GPL-2.0+
