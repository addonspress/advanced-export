# AGENTS

## Project Structure

```
advanced-export/
├── advanced-export.php          # Main plugin file, constants, activation/deactivation hooks
├── admin/
│   ├── class-advanced-export-admin.php   # Admin screen, nonce handling, form POST processing
│   ├── function-create-zip.php           # ZIP creation, data file generation, media copy
│   └── function-form-load.php            # Export form HTML, date/author dropdowns
├── includes/
│   ├── class-advanced-export.php         # Core singleton, hook registration
│   ├── class-advanced-export-activator.php      # Activation hook (empty)
│   ├── class-advanced-export-deactivator.php    # Deactivation hook (empty)
│   ├── class-advanced-export-i18n.php          # Textdomain loading
│   └── class-advanced-export-loader.php        # Hook orchestrator
├── assets/
│   ├── css/advanced-export-admin.css     # Admin styles
│   └── js/advanced-export-admin.js       # AJAX form load, filter toggles
├── languages/                          # .pot translation template
├── uninstall.php                       # Cleans temp directory on uninstall
└── readme.txt                          # WordPress.org readme
```

## Security Model

### Two Nonce Flows

1. **AJAX form load** (`wp_ajax_advanced_export_ajax_form_load`):
   - JS sends nonce from `advanced_export_js_object.nonce` (via `wp_localize_script`)
   - Server verifies with `check_ajax_referer('advanced-export', '_wpnonce')`
   - Capability check: `current_user_can($this->export_capability)`

2. **Form POST submission** (`export_content()`):
   - Form contains `wp_nonce_field('advanced-export')`
   - Server verifies with `check_admin_referer('advanced-export')`
   - Capability check: `current_user_can($this->export_capability)`

### Superglobal Handling

All `$_POST` and `$_GET` values are processed with `wp_unslash()` before any sanitizer:
- Strings: `sanitize_text_field(wp_unslash($_POST['field']))`
- Integers: `absint(wp_unslash($_POST['field']))`
- Keys: `sanitize_key(wp_unslash($_GET['field']))`
- Always guarded with `isset()` or `! empty()` before access

### File Operations

- ZIP filename sanitized with `sanitize_file_name()` (never `esc_attr()`)
- ZIP written to `ADVANCED_EXPORT_TEMP` (inside uploads dir), never CWD
- File copy uses `$wp_filesystem->copy()`, not native `copy()`
- Temp directory cleaned recursively after download and on uninstall

## PHP Version

Minimum: PHP 7.4 (declared in plugin header and readme.txt)

## WordPress Version

Tested up to: WordPress 7.0

## Text Domain

`advanced-export` — all translatable strings use this domain consistently.
