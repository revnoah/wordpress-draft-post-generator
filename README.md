# Draft Post Generator

**Draft Post Generator** is a WordPress plugin that allows bulk creation of draft posts from a list of titles. The plugin supports hierarchical post structures by interpreting hyphens at the beginning of titles to determine parent-child relationships.

## Features
- Bulk draft post creation from new line-delimited titles.
- Hierarchical post generation using hyphens as indicators, when post type supports parent
- Supports custom post types
- Integrates into the WordPress admin tools menu.

## Installation
1. Download the plugin files or clone the repository.
2. Upload the plugin folder to your WordPress `wp-content/plugins` directory.
3. Go to the **Plugins** menu in WordPress and activate **Draft Post Generator**.

## Usage
1. Navigate to **Tools → Draft Post Generator** in the WordPress admin menu.
2. Enter post titles, one per line. Use hyphens to indicate hierarchical relationships.
   - Example:
     ```
     Post A
     - Post B
     -- Post C
     - Post D
     ```
3. Select the desired post type, post status, and taxonomy terms.
4. Click **Generate Drafts** to create the posts.

## Title Hierarchy Rules
- **Top-level posts**: No hyphens (e.g., `Post A`).
- **Child posts**: Precede with one or more hyphens (e.g., `- Post B`).
- **Grandchild posts**: Use double hyphens (e.g., `-- Post C`).

## Example Hierarchy
```
Home Page
- About Us
-- Team
-- History
- Services
-- Web Development
-- Graphic Design
```

## Contributing
1. Fork the repository.
2. Create a new branch for your feature.
3. Submit a pull request with a clear description of your changes.

## License
This project is licensed under the MIT License.

---

**Author**: Noah Stewart  
**Version**: 1.0.0  
**Website**: https://noahjstewart.com
