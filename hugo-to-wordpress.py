#!/usr/bin/env python3
"""
Hugo to WordPress Content Converter
Converts Hugo markdown posts to WordPress WXR (XML) format for import
"""

import os
import re
from datetime import datetime
from pathlib import Path
import xml.etree.ElementTree as ET


def parse_hugo_frontmatter(content):
    """Extract frontmatter and content from Hugo markdown file"""
    # Match YAML frontmatter between --- markers
    pattern = r'^---\s*\n(.*?)\n---\s*\n(.*)$'
    match = re.match(pattern, content, re.DOTALL)

    if not match:
        return {}, content

    frontmatter_text = match.group(1)
    post_content = match.group(2).strip()

    # Parse frontmatter
    frontmatter = {}
    for line in frontmatter_text.split('\n'):
        if ':' in line:
            key, value = line.split(':', 1)
            key = key.strip()
            value = value.strip().strip('"').strip("'")
            frontmatter[key] = value

    return frontmatter, post_content


def convert_markdown_to_html(markdown_text):
    """Basic markdown to HTML conversion"""
    html = markdown_text

    # Headers
    html = re.sub(r'^### (.*?)$', r'<h3>\1</h3>', html, flags=re.MULTILINE)
    html = re.sub(r'^## (.*?)$', r'<h2>\1</h2>', html, flags=re.MULTILINE)
    html = re.sub(r'^# (.*?)$', r'<h1>\1</h1>', html, flags=re.MULTILINE)

    # Bold and italic
    html = re.sub(r'\*\*\*(.*?)\*\*\*', r'<strong><em>\1</em></strong>', html)
    html = re.sub(r'\*\*(.*?)\*\*', r'<strong>\1</strong>', html)
    html = re.sub(r'\*(.*?)\*', r'<em>\1</em>', html)
    html = re.sub(r'\_\_(.*?)\_\_', r'<strong>\1</strong>', html)
    html = re.sub(r'\_(.*?)\_', r'<em>\1</em>', html)

    # Links
    html = re.sub(r'\[(.*?)\]\((.*?)\)', r'<a href="\2">\1</a>', html)

    # Images
    html = re.sub(r'!\[(.*?)\]\((.*?)\)', r'<img src="\2" alt="\1" />', html)

    # Code blocks
    html = re.sub(r'```(.*?)\n(.*?)```', r'<pre><code class="language-\1">\2</code></pre>', html, flags=re.DOTALL)
    html = re.sub(r'`(.*?)`', r'<code>\1</code>', html)

    # Lists
    lines = html.split('\n')
    processed_lines = []
    in_ul = False
    in_ol = False

    for line in lines:
        # Unordered lists
        if re.match(r'^[\*\-\+]\s+', line):
            if not in_ul:
                processed_lines.append('<ul>')
                in_ul = True
            item = re.sub(r'^[\*\-\+]\s+', '', line)
            processed_lines.append(f'<li>{item}</li>')
        # Ordered lists
        elif re.match(r'^\d+\.\s+', line):
            if not in_ol:
                if in_ul:
                    processed_lines.append('</ul>')
                    in_ul = False
                processed_lines.append('<ol>')
                in_ol = True
            item = re.sub(r'^\d+\.\s+', '', line)
            processed_lines.append(f'<li>{item}</li>')
        else:
            if in_ul:
                processed_lines.append('</ul>')
                in_ul = False
            if in_ol:
                processed_lines.append('</ol>')
                in_ol = False
            processed_lines.append(line)

    if in_ul:
        processed_lines.append('</ul>')
    if in_ol:
        processed_lines.append('</ol>')

    html = '\n'.join(processed_lines)

    # Paragraphs (basic - wrap non-empty lines that aren't already in tags)
    lines = html.split('\n')
    processed_lines = []
    for line in lines:
        line = line.strip()
        if line and not re.match(r'^<[^>]+>', line):
            processed_lines.append(f'<p>{line}</p>')
        elif line:
            processed_lines.append(line)

    html = '\n'.join(processed_lines)

    return html


def create_wxr_xml(posts, site_info):
    """Create WordPress WXR (XML) format for import"""

    # Create root element
    rss = ET.Element('rss', {
        'version': '2.0',
        'xmlns:excerpt': 'http://wordpress.org/export/1.2/excerpt/',
        'xmlns:content': 'http://purl.org/rss/1.0/modules/content/',
        'xmlns:wfw': 'http://wellformedweb.org/CommentAPI/',
        'xmlns:dc': 'http://purl.org/dc/elements/1.1/',
        'xmlns:wp': 'http://wordpress.org/export/1.2/'
    })

    channel = ET.SubElement(rss, 'channel')

    # Site information
    ET.SubElement(channel, 'title').text = site_info.get('title', 'OpenSourceBox')
    ET.SubElement(channel, 'link').text = site_info.get('url', 'http://opensourcebox.com')
    ET.SubElement(channel, 'description').text = site_info.get('description', 'OpenSourceBox Blog')
    ET.SubElement(channel, 'language').text = 'en-US'
    ET.SubElement(channel, '{http://wordpress.org/export/1.2/}wxr_version').text = '1.2'
    ET.SubElement(channel, '{http://wordpress.org/export/1.2/}base_site_url').text = site_info.get('url', 'http://opensourcebox.com')
    ET.SubElement(channel, '{http://wordpress.org/export/1.2/}base_blog_url').text = site_info.get('url', 'http://opensourcebox.com')

    # Add posts
    post_id = 1
    for post in posts:
        item = ET.SubElement(channel, 'item')

        ET.SubElement(item, 'title').text = post.get('title', 'Untitled')
        ET.SubElement(item, 'link').text = post.get('link', '')
        ET.SubElement(item, 'pubDate').text = post.get('pubDate', datetime.now().strftime('%a, %d %b %Y %H:%M:%S +0000'))
        ET.SubElement(item, '{http://purl.org/dc/elements/1.1/}creator').text = post.get('author', 'admin')
        ET.SubElement(item, 'guid', {'isPermaLink': 'false'}).text = post.get('guid', f'http://opensourcebox.com/?p={post_id}')
        ET.SubElement(item, 'description')
        ET.SubElement(item, '{http://purl.org/rss/1.0/modules/content/}encoded').text = post.get('content', '')
        ET.SubElement(item, '{http://wordpress.org/export/1.2/excerpt/}encoded').text = post.get('excerpt', '')

        ET.SubElement(item, '{http://wordpress.org/export/1.2/}post_id').text = str(post_id)
        ET.SubElement(item, '{http://wordpress.org/export/1.2/}post_date').text = post.get('post_date', datetime.now().strftime('%Y-%m-%d %H:%M:%S'))
        ET.SubElement(item, '{http://wordpress.org/export/1.2/}post_date_gmt').text = post.get('post_date_gmt', datetime.now().strftime('%Y-%m-%d %H:%M:%S'))
        ET.SubElement(item, '{http://wordpress.org/export/1.2/}comment_status').text = 'open'
        ET.SubElement(item, '{http://wordpress.org/export/1.2/}ping_status').text = 'open'
        ET.SubElement(item, '{http://wordpress.org/export/1.2/}post_name').text = post.get('slug', '')
        ET.SubElement(item, '{http://wordpress.org/export/1.2/}status').text = post.get('status', 'publish')
        ET.SubElement(item, '{http://wordpress.org/export/1.2/}post_parent').text = '0'
        ET.SubElement(item, '{http://wordpress.org/export/1.2/}menu_order').text = '0'
        ET.SubElement(item, '{http://wordpress.org/export/1.2/}post_type').text = 'post'
        ET.SubElement(item, '{http://wordpress.org/export/1.2/}post_password')
        ET.SubElement(item, '{http://wordpress.org/export/1.2/}is_sticky').text = '0'

        # Add categories if present
        if 'categories' in post:
            for category in post['categories']:
                cat_elem = ET.SubElement(item, 'category', {
                    'domain': 'category',
                    'nicename': category.lower().replace(' ', '-')
                })
                cat_elem.text = category

        # Add tags if present
        if 'tags' in post:
            for tag in post['tags']:
                tag_elem = ET.SubElement(item, 'category', {
                    'domain': 'post_tag',
                    'nicename': tag.lower().replace(' ', '-')
                })
                tag_elem.text = tag

        post_id += 1

    return rss


def prettify_xml(elem):
    """Return a pretty-printed XML string"""
    # Register namespaces to avoid ns0, ns1 prefixes
    ET.register_namespace('excerpt', 'http://wordpress.org/export/1.2/excerpt/')
    ET.register_namespace('content', 'http://purl.org/rss/1.0/modules/content/')
    ET.register_namespace('wfw', 'http://wellformedweb.org/CommentAPI/')
    ET.register_namespace('dc', 'http://purl.org/dc/elements/1.1/')
    ET.register_namespace('wp', 'http://wordpress.org/export/1.2/')

    rough_string = ET.tostring(elem, encoding='unicode', method='xml')
    return '<?xml version="1.0" encoding="UTF-8"?>\n' + rough_string


def convert_hugo_to_wordpress(hugo_content_dir, output_file):
    """Main conversion function"""

    posts = []
    content_path = Path(hugo_content_dir)

    # Find all markdown files
    md_files = list(content_path.rglob('*.md'))

    print(f"Found {len(md_files)} markdown files")

    for md_file in md_files:
        # Skip archetype files
        if 'archetypes' in str(md_file):
            continue

        print(f"Processing: {md_file}")

        try:
            with open(md_file, 'r', encoding='utf-8') as f:
                content = f.read()

            frontmatter, post_content = parse_hugo_frontmatter(content)

            # Convert markdown to HTML
            html_content = convert_markdown_to_html(post_content)

            # Parse date
            post_date = frontmatter.get('date', datetime.now().isoformat())
            try:
                # Try to parse Hugo date format
                dt = datetime.fromisoformat(post_date.replace('Z', '+00:00'))
            except:
                dt = datetime.now()

            # Determine post status
            is_draft = frontmatter.get('draft', 'false').lower() == 'true'
            status = 'draft' if is_draft else 'publish'

            # Create slug from title or filename
            title = frontmatter.get('title', md_file.stem)
            slug = re.sub(r'[^a-z0-9]+', '-', title.lower()).strip('-')

            # Extract excerpt (first paragraph or first 150 chars)
            excerpt_match = re.search(r'<p>(.*?)</p>', html_content)
            excerpt = excerpt_match.group(1) if excerpt_match else html_content[:150]

            post = {
                'title': title,
                'content': html_content,
                'excerpt': excerpt,
                'post_date': dt.strftime('%Y-%m-%d %H:%M:%S'),
                'post_date_gmt': dt.strftime('%Y-%m-%d %H:%M:%S'),
                'pubDate': dt.strftime('%a, %d %b %Y %H:%M:%S +0000'),
                'status': status,
                'slug': slug,
                'link': f'http://opensourcebox.com/{slug}/',
                'guid': f'http://opensourcebox.com/?p={len(posts) + 1}',
                'author': frontmatter.get('author', 'admin'),
            }

            # Add categories if present
            if 'categories' in frontmatter:
                categories = frontmatter['categories']
                if isinstance(categories, str):
                    categories = [c.strip() for c in categories.split(',')]
                post['categories'] = categories

            # Add tags if present
            if 'tags' in frontmatter:
                tags = frontmatter['tags']
                if isinstance(tags, str):
                    tags = [t.strip() for t in tags.split(',')]
                post['tags'] = tags

            posts.append(post)
            print(f"  ✓ Converted: {title} (status: {status})")

        except Exception as e:
            print(f"  ✗ Error processing {md_file}: {e}")

    # Create WXR XML
    site_info = {
        'title': 'OpenSourceBox',
        'url': 'http://opensourcebox.com',
        'description': 'OpenSourceBox Blog'
    }

    wxr = create_wxr_xml(posts, site_info)
    xml_string = prettify_xml(wxr)

    # Write to file
    with open(output_file, 'w', encoding='utf-8') as f:
        f.write(xml_string)

    print(f"\n✓ Successfully converted {len(posts)} posts to {output_file}")
    print(f"\nNext steps:")
    print(f"1. Install WordPress")
    print(f"2. Log in to WordPress admin")
    print(f"3. Go to Tools > Import")
    print(f"4. Install 'WordPress Importer' if needed")
    print(f"5. Upload {output_file}")
    print(f"6. Map authors and import attachments")
    print(f"7. Click 'Submit' to import")


if __name__ == '__main__':
    # Configuration
    HUGO_CONTENT_DIR = 'osbBlog/content'
    OUTPUT_FILE = 'wordpress-import.xml'

    print("=" * 60)
    print("Hugo to WordPress Converter")
    print("=" * 60)
    print()

    if not os.path.exists(HUGO_CONTENT_DIR):
        print(f"Error: Hugo content directory not found: {HUGO_CONTENT_DIR}")
        exit(1)

    convert_hugo_to_wordpress(HUGO_CONTENT_DIR, OUTPUT_FILE)
    print("\nDone!")
