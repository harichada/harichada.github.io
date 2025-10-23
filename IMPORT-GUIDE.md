# Hugo to WordPress Content Import Guide

This guide explains how to import your Hugo blog content into WordPress.

## Method 1: Automated Import (Recommended)

I've created an automated conversion script that converts your Hugo markdown posts to WordPress WXR (XML) format.

### Step 1: Run the Conversion Script

```bash
python3 hugo-to-wordpress.py
```

This will:
- Scan all markdown files in `osbBlog/content/`
- Extract frontmatter (title, date, draft status, categories, tags)
- Convert markdown content to HTML
- Generate `wordpress-import.xml` file

### Step 2: Install WordPress Importer Plugin

1. Log in to your WordPress admin panel (http://yoursite.com/wp-admin)
2. Go to **Tools** > **Import**
3. Find "WordPress" in the list and click **Install Now**
4. Click **Activate Plugin & Run Importer**

### Step 3: Import the XML File

1. Click **Choose File** and select `wordpress-import.xml`
2. Click **Upload file and import**
3. Assign authors:
   - Map Hugo authors to WordPress users, or
   - Create new users, or
   - Assign all posts to an existing user
4. Check **Download and import file attachments** (if you have images)
5. Click **Submit**

### Step 4: Verify Import

1. Go to **Posts** > **All Posts**
2. Check that your posts were imported
3. Review post content and formatting
4. Publish any draft posts you want to make public

## Method 2: Manual Import

If you prefer to manually copy content:

### For Each Hugo Post:

1. Open the Hugo markdown file (e.g., `osbBlog/content/posts/first.md`)
2. In WordPress admin, go to **Posts** > **Add New**
3. Copy the frontmatter fields:
   - Title → Post title
   - Date → Post date
   - Categories → Categories (create if needed)
   - Tags → Tags
4. Copy the markdown content
5. Paste into WordPress editor (Gutenberg will convert markdown to blocks)
6. Add featured image if present
7. Set post status (Published/Draft)
8. Click **Publish**

## Understanding the Conversion

### What Gets Converted:

- **Title**: From Hugo frontmatter `title` field
- **Content**: Markdown converted to HTML
- **Date**: From Hugo frontmatter `date` field
- **Status**: Draft posts remain drafts, published posts are published
- **Categories**: From Hugo frontmatter `categories` field
- **Tags**: From Hugo frontmatter `tags` field
- **Slug**: Generated from title (URL-friendly)
- **Author**: Mapped to WordPress user

### Markdown to HTML Conversion:

The script converts:
- Headers (# ## ###)
- Bold (**text** or __text__)
- Italic (*text* or _text_)
- Links ([text](url))
- Images (![alt](url))
- Code blocks (```code```)
- Inline code (`code`)
- Lists (ordered and unordered)
- Paragraphs

### What Needs Manual Attention:

- **Images**: You may need to re-upload images to WordPress media library
- **Shortcodes**: Hugo shortcodes need to be replaced with WordPress equivalents
- **Custom formatting**: Complex HTML or custom CSS may need adjustment
- **Internal links**: Update links from Hugo URL structure to WordPress permalinks
- **Featured images**: Set these manually after import

## Your Current Hugo Content

Based on your repository:

- **Location**: `osbBlog/content/posts/`
- **Posts found**: 1 post (`first.md`)
- **Status**: Draft
- **Content**: Empty (template post)

## Troubleshooting

### Import fails or hangs:
- Check file size (large files may timeout)
- Increase PHP memory limit in wp-config.php: `define('WP_MEMORY_LIMIT', '256M');`
- Break into smaller XML files if you have many posts

### Images don't import:
- Ensure images are in the correct Hugo static folder
- Check image URLs in markdown files
- Manually upload images to WordPress Media Library
- Update image URLs in posts

### Formatting issues:
- Use WordPress editor to fix formatting
- Check for unclosed HTML tags
- Verify special characters are encoded correctly

### Missing categories or tags:
- WordPress will auto-create categories/tags during import
- You can reorganize them later in WordPress admin

## Adding More Content

If you have additional Hugo posts to migrate:

1. Place markdown files in `osbBlog/content/posts/`
2. Run the conversion script again: `python3 hugo-to-wordpress.py`
3. Import the new `wordpress-import.xml` file
4. WordPress will skip duplicate posts (by post slug)

## Post-Import Tasks

After importing:

1. **Review all posts**: Check formatting and layout
2. **Set featured images**: Add featured images to posts
3. **Update internal links**: Change Hugo URLs to WordPress permalinks
4. **Configure permalinks**: Go to Settings > Permalinks and choose your structure
5. **Set up categories**: Organize posts into proper categories
6. **Create menus**: Add posts/categories to navigation menus
7. **Configure widgets**: Add recent posts, categories, tags widgets
8. **Update meta descriptions**: Add SEO descriptions if needed
9. **Test responsive layout**: Check posts on mobile devices
10. **Set up redirects**: If changing domains, set up 301 redirects from Hugo URLs

## Permalink Structure

Common WordPress permalink structures:

- **Post name** (recommended): `http://example.com/sample-post/`
- **Day and name**: `http://example.com/2023/03/15/sample-post/`
- **Month and name**: `http://example.com/2023/03/sample-post/`
- **Numeric**: `http://example.com/123`

Configure in: **Settings** > **Permalinks**

## SEO Considerations

If your Hugo site was indexed by search engines:

1. Keep the same URL structure if possible (use permalinks settings)
2. Set up 301 redirects for changed URLs
3. Update sitemap.xml (use Yoast SEO or similar plugin)
4. Submit new sitemap to Google Search Console
5. Update robots.txt if needed
6. Verify all internal links work

## Using the Conversion Script

The `hugo-to-wordpress.py` script can be customized:

### Configuration (at the bottom of the script):

```python
HUGO_CONTENT_DIR = 'osbBlog/content'  # Hugo content directory
OUTPUT_FILE = 'wordpress-import.xml'   # Output file name
```

### Site Information:

Edit the `site_info` dictionary in the script:

```python
site_info = {
    'title': 'OpenSourceBox',
    'url': 'http://opensourcebox.com',
    'description': 'OpenSourceBox Blog'
}
```

### Running with Custom Settings:

```bash
# Default run
python3 hugo-to-wordpress.py

# Or edit the script to change settings
nano hugo-to-wordpress.py
python3 hugo-to-wordpress.py
```

## Support

For issues with:

- **The conversion script**: Check Python version (requires 3.6+)
- **WordPress import**: See WordPress Importer plugin documentation
- **Theme compatibility**: Ensure OpenSourceBox theme is activated

## Next Steps

1. ✓ Run conversion script
2. ✓ Install WordPress
3. ✓ Activate OpenSourceBox theme
4. ⃝ Import content using WordPress Importer
5. ⃝ Review and format imported posts
6. ⃝ Configure site settings
7. ⃝ Add additional content
8. ⃝ Launch your WordPress site!

---

**Note**: Always backup your WordPress database before importing large amounts of content.
