# Documentation Style Guide

This guide defines the standards for all documentation in the House of Giants WordPress Theme Scaffold project to ensure consistency, clarity, and professional presentation.

## Table of Contents

- [Header Hierarchy](#header-hierarchy)
- [Code Block Formatting](#code-block-formatting)
- [List Styles](#list-styles)
- [Link Formatting](#link-formatting)
- [Image Guidelines](#image-guidelines)
- [Terminology Standards](#terminology-standards)
- [Writing Tone](#writing-tone)
- [File Naming Conventions](#file-naming-conventions)
- [GitHub Markdown Features](#github-markdown-features)

## Header Hierarchy

Use the following header structure consistently across all documents:

- **H1 (`#`)**: Document title (use once per document)
- **H2 (`##`)**: Major sections
- **H3 (`###`)**: Subsections
- **H4 (`####`)**: Sub-subsections (use sparingly)

### ✅ Correct Usage

```markdown
# Getting Started Guide

## Installation

### Prerequisites

#### System Requirements
```

### ❌ Incorrect Usage

```markdown
## Getting Started Guide <!-- Should be H1 -->

### Installation <!-- Should be H2 under main H1 -->
```

## Code Block Formatting

Always use fenced code blocks with language identifiers for syntax highlighting:

### ✅ Correct Usage

````markdown
```php
function theme_setup() {
    add_theme_support('post-thumbnails');
}
```
````

```javascript
const element = document.querySelector(".my-class");
```

```css
.button {
	background-color: var(--wp--preset--color--primary);
}
```

```bash
npm run build
```

````

### ❌ Incorrect Usage

```markdown
````

// No language specified
function example() {}

```

Use `code` for single items like function names or short code snippets.
```

## List Styles

### Unordered Lists

Use hyphens (`-`) for unordered lists for consistency:

```markdown
- First item
- Second item
  - Nested item
  - Another nested item
- Third item
```

### Ordered Lists

Use numbers for ordered lists:

```markdown
1. First step
2. Second step
3. Third step
```

### Task Lists

Use GitHub-flavored task list syntax:

```markdown
- [x] Completed task
- [ ] Pending task
- [ ] Another pending task
```

## Link Formatting

### Internal Links

Use relative paths for internal documentation links:

```markdown
[Getting Started Guide](getting-started/setup.md)
[API Reference](../api/functions.md)
```

### External Links

Use descriptive link text (avoid "click here"):

### ✅ Correct Usage

```markdown
Refer to the [WordPress Coding Standards](https://developer.wordpress.org/coding-standards/) for best practices.
```

### ❌ Incorrect Usage

```markdown
Click [here](https://developer.wordpress.org/coding-standards/) for coding standards.
```

### Reference Links

For frequently referenced links, use reference style at the bottom of the document:

```markdown
Check the [WordPress Codex][wp-codex] for more information.

[wp-codex]: https://codex.wordpress.org/
```

## Image Guidelines

### File Naming

Use descriptive, kebab-case names:

```
theme-setup-screenshot.png
block-editor-interface.jpg
deployment-workflow-diagram.svg
```

### Alt Text

Always include descriptive alt text:

```markdown
![WordPress admin dashboard showing theme customization options](images/theme-customizer-screenshot.png)
```

### Image Sizing

Optimize images for web display and keep file sizes reasonable:

- Screenshots: Max 1200px wide
- Diagrams: SVG preferred for scalability
- Photos: WebP format when possible

## Terminology Standards

Use these standardized terms consistently:

| ✅ Preferred                | ❌ Avoid                                     |
| --------------------------- | -------------------------------------------- |
| website                     | web site, web-site                           |
| WordPress                   | Wordpress, wordpress                         |
| JavaScript                  | Javascript, javascript                       |
| email                       | e-mail                                       |
| setup (noun), set up (verb) | setup (verb)                                 |
| login (noun), log in (verb) | login (verb)                                 |
| theme                       | template                                     |
| block                       | element (when referring to WordPress blocks) |

### Code-Related Terms

- Use `function`, `class`, `method` appropriately
- Capitalize proper names: `React`, `Node.js`, `Webpack`
- Use backticks for code elements: `wp_enqueue_script()`

## Writing Tone

Maintain a professional but approachable tone:

### ✅ Good Examples

- "Follow these steps to configure your development environment."
- "This approach provides better performance and maintainability."
- "Consider using this method when you need more flexibility."

### ❌ Avoid

- "Just do this..." (too casual)
- "Obviously, you should..." (condescending)
- "It's super easy!" (oversimplification)

### Voice Guidelines

- Use active voice when possible
- Write in second person ("you") for instructions
- Be concise but thorough
- Include context for why something matters

## File Naming Conventions

Use kebab-case for all documentation files:

### ✅ Correct

```
getting-started.md
block-development-guide.md
api-reference.md
troubleshooting-common-issues.md
```

### ❌ Incorrect

```
GettingStarted.md
block_development_guide.md
API Reference.md
troubleshootingCommonIssues.md
```

## GitHub Markdown Features

Leverage GitHub-specific markdown features when appropriate:

### Tables

```markdown
| Feature       | Supported | Notes                          |
| ------------- | --------- | ------------------------------ |
| Dark mode     | ✅        | Auto-detects system preference |
| RTL languages | ❌        | Planned for future release     |
```

### Collapsible Sections

````markdown
<details>
<summary>Advanced Configuration Options</summary>

This section contains advanced configuration that most users won't need.

```php
// Advanced configuration code here
```
````

</details>
```

### Alerts and Callouts

Use blockquotes for important information:

```markdown
> **Note:** This feature requires WordPress 6.0 or higher.

> **Warning:** Modifying this file directly may cause issues during theme updates.
```

### Syntax Highlighting

Always specify language for proper highlighting:

- `php` for PHP code
- `javascript` or `js` for JavaScript
- `css` for CSS
- `scss` for Sass
- `bash` or `shell` for terminal commands
- `json` for JSON files
- `yaml` for YAML files

## Navigation Elements

### Breadcrumbs

Include breadcrumb navigation at the top of deep pages:

```markdown
[Documentation](../README.md) > [Customization](README.md) > Block Development
```

### Cross-References

Include "See also" sections when relevant:

```markdown
## See Also

- [Theme Setup Guide](../getting-started/setup.md)
- [API Reference](../api/functions.md)
- [Troubleshooting](../troubleshooting/common-issues.md)
```

## Accessibility Considerations

- Use meaningful heading structures
- Include alt text for all images
- Ensure sufficient color contrast in any custom styling
- Use descriptive link text
- Structure content logically

## Examples

### Document Template

````markdown
# Document Title

Brief description of what this document covers.

## Prerequisites

List any requirements or prior knowledge needed.

## Overview

High-level explanation of the topic.

## Step-by-Step Instructions

1. First step with clear action
2. Second step with example
3. Third step with verification

## Code Examples

```php
// Well-commented code example
function example_function() {
    // Implementation details
}
```
````

## Troubleshooting

Common issues and solutions.

## See Also

- [Related Document 1](../category/document.md)
- [Related Document 2](../category/other-document.md)

---

**[⬅️ Back to Documentation Index](README.md)**

```

This style guide should be followed for all new documentation and applied when updating existing content. Consistency in documentation presentation improves the developer experience and makes the project more professional and accessible.
```
