# WordPress Theme Setup Guide for OpenSourceBox

This repository now contains a custom WordPress theme for OpenSourceBox blog. This guide will help you set up WordPress and activate the theme.

## What's Included

This repository includes:
- Custom WordPress theme in `wp-content/themes/opensourcebox/`
- Sample WordPress configuration file: `wp-config-sample.php`

## Theme Structure

The OpenSourceBox theme includes:
- `style.css` - Main stylesheet with theme information
- `functions.php` - Theme functionality and features
- `header.php` - Site header template
- `footer.php` - Site footer template
- `index.php` - Main blog listing template
- `single.php` - Individual post template
- `page.php` - Static page template
- `archive.php` - Archive pages template
- `search.php` - Search results template
- `404.php` - Error page template
- `sidebar.php` - Sidebar widget area
- `comments.php` - Comments template
- `searchform.php` - Search form template

## Installation Options

### Option 1: Using an Existing WordPress Installation

If you already have WordPress installed:

1. Copy the `wp-content/themes/opensourcebox/` folder to your WordPress installation's `wp-content/themes/` directory
2. Log in to your WordPress admin panel
3. Navigate to Appearance > Themes
4. Find "OpenSourceBox" and click "Activate"

### Option 2: Fresh WordPress Installation

If you're starting fresh:

#### Step 1: Download WordPress Core Files

You need to download and install WordPress core files. You have several options:

**Using WP-CLI (Recommended):**
```bash
# Install WP-CLI if you haven't already
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp

# Download WordPress
wp core download
```

**Manual Download:**
1. Visit https://wordpress.org/download/
2. Download the latest WordPress version
3. Extract the files to your repository root
4. The theme folder is already in place at `wp-content/themes/opensourcebox/`

#### Step 2: Set Up Database

1. Create a MySQL database for WordPress:
```sql
CREATE DATABASE opensourcebox_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'wp_user'@'localhost' IDENTIFIED BY 'your_strong_password';
GRANT ALL PRIVILEGES ON opensourcebox_db.* TO 'wp_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Step 3: Configure WordPress

1. Copy `wp-config-sample.php` to `wp-config.php`:
```bash
cp wp-config-sample.php wp-config.php
```

2. Edit `wp-config.php` and update:
   - `DB_NAME` - Your database name (e.g., 'opensourcebox_db')
   - `DB_USER` - Your database username
   - `DB_PASSWORD` - Your database password
   - `DB_HOST` - Usually 'localhost'

3. Generate security keys:
   - Visit: https://api.wordpress.org/secret-key/1.1/salt/
   - Copy the generated keys
   - Replace the placeholder keys in `wp-config.php`

#### Step 4: Run WordPress Installation

1. If using a local server, start it:
```bash
# Using PHP's built-in server
php -S localhost:8000

# Or using XAMPP, WAMP, MAMP, etc.
```

2. Visit your site in a browser (e.g., http://localhost:8000)
3. Follow the WordPress installation wizard:
   - Enter site title: "OpenSourceBox"
   - Create admin username and password
   - Enter your email address
   - Click "Install WordPress"

#### Step 5: Activate the Theme

1. Log in to WordPress admin panel (http://localhost:8000/wp-admin)
2. Navigate to Appearance > Themes
3. Find "OpenSourceBox" theme
4. Click "Activate"

## Theme Features

The OpenSourceBox theme includes:

- Responsive design
- Custom navigation menus (Primary and Footer)
- Widget-ready areas (Sidebar + 3 Footer areas)
- Featured images support
- Custom logo support
- HTML5 markup
- Comments system
- Search functionality
- Archive and category pages
- Post pagination
- Clean, modern design

## Customization

### Menus

1. Go to Appearance > Menus
2. Create a new menu
3. Assign it to "Primary Menu" or "Footer Menu" location
4. Add pages, posts, or custom links

### Widgets

1. Go to Appearance > Widgets
2. Add widgets to:
   - Sidebar
   - Footer 1, 2, or 3

### Custom Logo

1. Go to Appearance > Customize
2. Navigate to Site Identity
3. Upload your logo

### Colors and Styling

Edit `wp-content/themes/opensourcebox/style.css` to customize:
- Colors
- Fonts
- Spacing
- Layout

## Importing Content from Hugo

Your Hugo blog content is in `osbBlog/content/posts/`.

### Automated Import (Recommended):

I've created a Python script to automatically convert and import your Hugo content:

```bash
# Run the conversion script
python3 hugo-to-wordpress.py
```

This generates `wordpress-import.xml` that you can import into WordPress.

**Detailed instructions**: See [IMPORT-GUIDE.md](IMPORT-GUIDE.md) for complete step-by-step instructions.

### Quick Import Steps:

1. Run `python3 hugo-to-wordpress.py` to generate the import file
2. In WordPress admin, go to **Tools > Import**
3. Install and activate "WordPress Importer" plugin
4. Upload `wordpress-import.xml`
5. Map authors and click Submit

### Manual Migration:

If you prefer manual migration:

1. Copy content from Hugo markdown files (`osbBlog/content/posts/*.md`)
2. Create new posts in WordPress admin (Posts > Add New)
3. Paste and format content
4. Update metadata (date, categories, tags)

## Development

### Local Development Server

```bash
# PHP built-in server
php -S localhost:8000

# Access site at: http://localhost:8000
```

### Theme Development

The theme files are located in:
```
wp-content/themes/opensourcebox/
```

After making changes:
1. Refresh your browser to see updates
2. Use WordPress debugging for development:
   - Edit `wp-config.php`
   - Set `define('WP_DEBUG', true);`

## Deployment

### To Deploy on Web Hosting:

1. Upload all WordPress files via FTP/SFTP
2. Import your database
3. Update `wp-config.php` with production database credentials
4. Update site URL in WordPress settings or database
5. Activate the OpenSourceBox theme

### Common Hosting Platforms:

- **SiteGround**: Use their WordPress installer, then upload theme
- **Bluehost**: One-click WordPress install, then activate theme
- **WordPress.com**: Upload theme via Appearance > Themes (Business plan required)
- **DigitalOcean**: Use WordPress one-click app, then upload theme

## Troubleshooting

### Theme not appearing:
- Ensure `style.css` has proper theme headers
- Check file permissions (755 for directories, 644 for files)

### White screen:
- Enable WP_DEBUG in wp-config.php
- Check PHP error logs
- Verify all theme files are uploaded

### Database connection error:
- Verify database credentials in wp-config.php
- Ensure database server is running
- Check user privileges

## File Structure

```
harichada.github.io/
├── wp-config-sample.php          # WordPress configuration sample
├── wp-content/
│   └── themes/
│       └── opensourcebox/        # Your custom theme
│           ├── style.css
│           ├── functions.php
│           ├── header.php
│           ├── footer.php
│           ├── index.php
│           ├── single.php
│           ├── page.php
│           ├── archive.php
│           ├── search.php
│           ├── 404.php
│           ├── sidebar.php
│           ├── comments.php
│           └── searchform.php
└── osbBlog/                      # Original Hugo site (for reference)
```

## Next Steps

1. Download WordPress core files (if not already done)
2. Set up your database
3. Configure wp-config.php
4. Install WordPress
5. Activate the OpenSourceBox theme
6. Import/migrate your Hugo content
7. Customize your site (menus, widgets, logo)
8. Add new content
9. Deploy to production

## Resources

- WordPress Codex: https://codex.wordpress.org/
- Theme Development: https://developer.wordpress.org/themes/
- WordPress Support: https://wordpress.org/support/
- WP-CLI Documentation: https://wp-cli.org/

## Support

For theme-specific issues or customization help, please refer to the WordPress Theme Development Handbook or consult with a WordPress developer.

## License

This theme is licensed under the GNU General Public License v2 or later.
