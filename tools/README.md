# Development Tools

This directory contains development utilities for the WordPress theme.

## Template Validation

### `validate-templates.php`

Validates FSE (Full Site Editing) template files for proper block formatting and WordPress compatibility.

**Usage:**
```bash
php tools/validate-templates.php
```

**What it checks:**
- ✅ Valid block comment syntax
- ✅ Template part blocks have proper `theme="hog-scaffold"` attributes  
- ✅ Navigation blocks don't have problematic `ref` attributes
- ✅ File existence and accessibility

**When to use:**
- After editing template files
- Before committing template changes
- When debugging "Block contains invalid content" errors
- During WordPress core updates

**Example output:**
```
Validating FSE Template Files...
================================

Validating: index.html
✅ Found 10 valid block comments
✅ Template part has proper theme attribute

Validation complete!
```

**Templates checked:**
- `templates/index.html` - Main blog listing
- `templates/single.html` - Single post template  
- `templates/page.html` - Static page template
- `templates/archive.html` - Archive listings
- `templates/search.html` - Search results
- `templates/404.html` - Not found page
- `parts/header.html` - Site header
- `parts/footer.html` - Site footer 