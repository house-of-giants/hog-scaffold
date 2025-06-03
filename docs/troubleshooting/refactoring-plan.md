# Task 1.2: Comprehensive Refactoring Plan

## Overview

This plan outlines the systematic refactoring of the existing WordPress boilerplate from a classic theme to a modern block theme while preserving Git history and maintaining all existing functionality.

## Phase 1: Dependency Updates and Core Configuration

### 1.1 Update package.json

- **Action**: Update Node.js requirement from `>=12.0.0` to `>=18.0.0`
- **Files**: `package.json`
- **Git commit**: "feat: update Node.js requirement to 18.x+ for PRD compliance"

### 1.2 Update composer.json

- **Action**: Update PHP requirement from `>=7.0` to `>=8.0`
- **Files**: `composer.json`
- **Git commit**: "feat: update PHP requirement to 8.0+ for PRD compliance"

### 1.3 Create theme.json

- **Action**: Create basic `theme.json` with version 2 schema
- **Location**: Root directory
- **Content**: Basic settings, styles, and templateParts configuration
- **Git commit**: "feat: add theme.json for block theme support"

## Phase 2: Directory Restructuring (Preserving Git History)

### 2.1 Rename directories using Git

```bash
# Preserve history while renaming directories
git mv includes inc
git mv partials parts
```

- **Git commit**: "refactor: rename directories for block theme standards (includes→inc, partials→parts)"

### 2.2 Create new required directories

```bash
mkdir patterns
mkdir parts/template-parts
mkdir templates/parts
```

- **Git commit**: "feat: add block theme directory structure (patterns, template-parts)"

### 2.3 Update file references

- **Files to update**:
  - `functions.php` (update include paths)
  - `composer.json` (update autoload paths)
  - Any files referencing old directory names
- **Git commit**: "fix: update file paths after directory restructuring"

## Phase 3: Template System Conversion

### 3.1 Analyze existing PHP templates

- **Files**: `header.php`, `footer.php`, `index.php`, `search.php`, `searchform.php`
- **Action**: Document functionality to preserve in block templates
- **Output**: Template conversion matrix

### 3.2 Create template parts

- **Create**: `parts/header.html`
- **Create**: `parts/footer.html`
- **Action**: Convert PHP template parts to HTML with block markup
- **Git commit**: "feat: add block theme template parts (header, footer)"

### 3.3 Create main templates

- **Create**: `templates/index.html`
- **Create**: `templates/single.html`
- **Create**: `templates/page.html`
- **Create**: `templates/archive.html`
- **Create**: `templates/404.html`
- **Git commit**: "feat: add core block theme templates"

### 3.4 Remove classic theme files

- **Remove**: `header.php`, `footer.php`, `search.php`, `searchform.php`
- **Keep**: `index.php` (required fallback), `functions.php`
- **Git commit**: "cleanup: remove classic theme template files"

## Phase 4: Block Theme Integration

### 4.1 Update functions.php

- **Action**: Add block theme support
- **Add**: `add_theme_support('block-templates')`
- **Add**: `add_theme_support('block-template-parts')`
- **Update**: Asset enqueueing for block editor
- **Git commit**: "feat: add block theme support to functions.php"

### 4.2 Update style.css theme header

- **Action**: Update theme metadata for block theme
- **Add**: Block theme tags
- **Git commit**: "feat: update theme header for block theme"

### 4.3 Create basic block patterns

- **Create**: `patterns/example-pattern.php`
- **Action**: Add pattern registration
- **Git commit**: "feat: add initial block patterns structure"

## Phase 5: Build System Updates

### 5.1 Review and update Webpack configuration

- **Files**: `config/webpack.*.js`
- **Action**: Ensure compatibility with block theme assets
- **Add**: Block editor asset handling if needed
- **Git commit**: "feat: update build system for block theme compatibility"

### 5.2 Update npm scripts if necessary

- **File**: `package.json`
- **Action**: Ensure all scripts work with new structure
- **Git commit**: "fix: update npm scripts for new directory structure"

## Phase 6: CSS Integration

### 6.1 Create CSS custom properties mapping

- **Action**: Map existing CSS variables to theme.json
- **File**: Update main CSS files to reference theme.json values
- **Git commit**: "feat: integrate CSS custom properties with theme.json"

### 6.2 Update PostCSS configuration

- **Action**: Ensure PostCSS works with block theme structure
- **Git commit**: "fix: update PostCSS for block theme compatibility"

## Phase 7: Testing and Validation

### 7.1 Test local development environment

- **Action**: Verify `npm run watch` and `npm run build` work
- **Action**: Ensure WordPress recognizes as block theme
- **Validation**: Full site editing capabilities functional

### 7.2 Verify Git history preservation

- **Action**: Check that `git log --follow` works for renamed files
- **Action**: Ensure no commits lost during restructuring

### 7.3 Function verification

- **Action**: Test all existing functionality still works
- **Action**: Verify block editor integration
- **Action**: Check responsive design maintained

## Implementation Order

1. **Dependencies** (Phase 1) - Low risk, foundational
2. **Directory Structure** (Phase 2) - Medium risk, requires careful Git handling
3. **Template Conversion** (Phase 3) - High impact, visible changes
4. **Block Integration** (Phase 4) - Core functionality
5. **Build System** (Phase 5) - Technical requirements
6. **CSS Integration** (Phase 6) - Visual consistency
7. **Testing** (Phase 7) - Quality assurance

## Risk Mitigation

### High Risk Areas

- Directory renaming with Git history preservation
- Template conversion maintaining functionality
- Build system compatibility

### Backup Strategy

- Create branch before starting: `git checkout -b refactor/block-theme-conversion`
- Tag current state: `git tag v1.2.0-classic-theme`
- Test each phase before proceeding to next

### Rollback Plan

- Each phase is a separate commit
- Can rollback to any phase if issues arise
- Keep classic theme backup branch

## Success Criteria

1. ✅ WordPress recognizes theme as block theme
2. ✅ Full site editing works correctly
3. ✅ All existing functionality preserved
4. ✅ Build system works without errors
5. ✅ Git history preserved for all files
6. ✅ Local development environment functional
7. ✅ CSS and JavaScript assets load correctly
8. ✅ Block editor integration complete

## Estimated Timeline

- Phase 1: 30 minutes
- Phase 2: 1 hour
- Phase 3: 2-3 hours
- Phase 4: 1 hour
- Phase 5: 30 minutes
- Phase 6: 1 hour
- Phase 7: 1-2 hours

**Total Estimated Time: 7-9 hours**
