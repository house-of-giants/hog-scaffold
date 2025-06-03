[Documentation](../README.md) > [Refactoring Process](README.md) > Git History Preservation

# Preserving Git History During Theme Refactoring

## Overview

Maintaining a clean and useful Git history during a major architectural refactoring is crucial for future development and troubleshooting. This guide outlines our approach to preserving meaningful history while transforming from a classic theme to a block theme.

## Table of Contents

- [Key Strategies](#key-strategies)
- [File Management Techniques](#file-management-techniques)
- [Commit Best Practices](#commit-best-practices)
- [Branch Management](#branch-management)
- [Viewing Component History](#viewing-component-history)
- [Comparing Pre/Post Refactoring](#comparing-prepost-refactoring)
- [Lessons Learned](#lessons-learned)

## Key Strategies

### 1. Incremental Commits

Rather than one massive refactoring commit, we broke down the transformation into logical, incremental changes:

- Initial setup of block theme structure
- Migration of template files to block templates
- Conversion of CSS to theme.json settings
- Refactoring of JavaScript components

**Example commit sequence:**

```bash
git commit -m "feat: initialize block theme directory structure

- Create parts/, templates/, patterns/ directories
- Add basic theme.json with minimal configuration
- Set up modern build system with @wordpress/scripts

Ref: #123"

git commit -m "refactor: convert header.php to block template part

- Transform header.php to parts/header.html
- Migrate navigation menu to Navigation block
- Preserve existing functionality and styling
- Update template references

Ref: #124"
```

### 2. File Renaming vs. Creating New Files

When possible, we used Git's ability to track renamed files:

```bash
# Preserve history when moving files
git mv old-file.php new-file.php

# For complex transformations, use git mv then modify
git mv header.php parts/header.html
# Edit the file to convert PHP to HTML blocks
git add parts/header.html
git commit -m "refactor: convert header.php to block template part"
```

**When to use git mv:**

- Simple file relocations
- Renaming with minimal content changes
- Preserving file history is critical

**When to create new files:**

- Complete rewrites with different structure
- Converting between fundamentally different formats
- When the relationship isn't clear

### 3. Detailed Commit Messages

Each commit includes:

- A clear summary of changes
- References to related issues/tickets
- Before/after context when appropriate
- Rationale for architectural decisions

**Commit message template:**

```
type(scope): brief description

- Detailed explanation of what changed
- Why the change was necessary
- Any breaking changes or migration notes
- Links to related documentation

Ref: #issue-number
```

**Example:**

```
refactor(templates): migrate single.php to block template

- Convert single.php to templates/single.html
- Replace PHP template tags with equivalent blocks
- Maintain post meta display functionality
- Update post navigation to use Query Loop block

Breaking change: Custom post meta hooks need updating
See migration guide: docs/refactoring-process/migration-strategy.md

Ref: #156
```

### 4. Feature Branches and Pull Requests

Major components were refactored in dedicated feature branches and merged via pull requests with thorough code reviews.

**Branch naming convention:**

```bash
# Feature branches for major refactoring areas
refactor/template-conversion
refactor/javascript-modernization
refactor/css-to-theme-json
refactor/block-development

# Specific component branches
refactor/header-template-part
refactor/navigation-block
refactor/hero-pattern
```

## File Management Techniques

### Tracking File Transformations

For files that undergo significant transformation:

```bash
# Method 1: Preserve history with git mv
git mv style.css assets/css/legacy-style.css
# Create new theme.json and modern CSS structure
# Commit both changes together

# Method 2: Document transformation in commit
git add theme.json assets/css/
git rm style.css
git commit -m "refactor: migrate CSS to theme.json design system

- Extract color palette from style.css to theme.json
- Move component styles to assets/css/components/
- Implement CSS custom properties for design tokens
- Remove legacy CSS patterns

Migrated from: style.css (see previous commits for history)
Ref: #178"
```

### Handling Complex Migrations

For files that split into multiple new files:

```bash
# Document the relationship clearly
git commit -m "refactor: split functions.php into modular components

- Move theme setup to inc/theme-setup.php
- Extract block registration to inc/blocks.php
- Move customizer code to inc/customizer.php
- Keep core functions in functions.php

Original file: functions.php (1,200 lines)
Split into: 4 focused modules for better maintainability

Ref: #189"
```

## Commit Best Practices

### 1. Atomic Commits

Each commit should represent a single logical change:

```bash
# Good: Single responsibility
git commit -m "refactor: convert footer.php to block template part"

# Bad: Multiple unrelated changes
git commit -m "convert footer, update styles, fix navigation bug"
```

### 2. Meaningful Commit Messages

Follow conventional commit format with detailed descriptions:

```bash
# Structure: type(scope): description
feat(blocks): add custom hero block
fix(navigation): resolve mobile menu toggle issue
refactor(css): migrate to CSS custom properties
docs(readme): update installation instructions
```

### 3. Reference Related Work

Link commits to issues, pull requests, and documentation:

```bash
git commit -m "refactor: implement responsive navigation pattern

- Convert jQuery mobile menu to vanilla JavaScript
- Add CSS custom properties for breakpoints
- Implement accessible keyboard navigation
- Update documentation for new navigation API

Closes #145
See: docs/blocks/navigation-block.md
Related: PR #156 (header template conversion)"
```

## Branch Management

### Long-Running Feature Branches

For major refactoring work, maintain feature branches with regular rebasing:

```bash
# Create feature branch
git checkout -b refactor/block-theme-conversion

# Regular rebasing to stay current
git fetch origin
git rebase origin/main

# Squash related commits before merging
git rebase -i HEAD~5
```

### Milestone Tags

Create tags at significant milestones:

```bash
# Tag major milestones
git tag -a v1.0.0-classic-theme -m "Last version before block theme refactoring"
git tag -a v1.1.0-templates-converted -m "All templates converted to block templates"
git tag -a v2.0.0-block-theme -m "Complete block theme refactoring"
```

## Viewing Component History

To trace the evolution of a component through the refactoring:

```bash
# Follow file history across renames
git log --follow -- path/to/component

# See detailed changes with diffs
git log --follow -p -- path/to/component

# View file history in a specific date range
git log --follow --since="2023-01-01" --until="2023-12-31" -- path/to/component

# Find when a file was renamed
git log --follow --name-status -- path/to/component
```

**Example: Tracking header evolution**

```bash
# See complete history of header component
git log --follow --oneline -- parts/header.html

# Output:
# a1b2c3d refactor: add site logo block to header template
# d4e5f6g refactor: convert header.php to block template part
# g7h8i9j feat: add responsive navigation to header
# j1k2l3m initial: create header.php template
```

## Comparing Pre/Post Refactoring

To compare the theme before and after the refactoring:

```bash
# Compare specific versions
git diff v1.0.0-classic-theme..v2.0.0-block-theme -- specific/file/or/directory

# Compare directory structures
git diff --name-status v1.0.0-classic-theme..v2.0.0-block-theme

# See what files were added/removed/renamed
git diff --summary v1.0.0-classic-theme..v2.0.0-block-theme
```

**Example comparisons:**

```bash
# Compare template structures
git diff v1.0.0-classic-theme..v2.0.0-block-theme -- templates/

# Compare styling approaches
git diff v1.0.0-classic-theme..v2.0.0-block-theme -- style.css theme.json

# See JavaScript modernization
git diff v1.0.0-classic-theme..v2.0.0-block-theme -- assets/js/
```

### Generating Migration Reports

Create comprehensive reports of changes:

```bash
# Generate detailed change summary
git diff --stat v1.0.0-classic-theme..v2.0.0-block-theme > refactoring-summary.txt

# List all modified files with change types
git diff --name-status v1.0.0-classic-theme..v2.0.0-block-theme > file-changes.txt

# Create commit log for the refactoring period
git log --oneline v1.0.0-classic-theme..v2.0.0-block-theme > refactoring-commits.txt
```

## Lessons Learned

### What Worked Well

- **Incremental commits** made it easy to track specific changes and revert if needed
- **Detailed commit messages** provided valuable context for future developers
- **Feature branches** allowed for focused work without disrupting main development
- **Regular rebasing** kept the history clean and linear
- **Milestone tags** created clear reference points for major changes

### Areas for Improvement

- **Earlier planning** of file transformation strategies would have prevented some history loss
- **More consistent commit message format** would improve searchability
- **Better documentation** of breaking changes in commit messages
- **Automated tools** for generating migration reports would save time

### Recommendations for Future Refactoring

1. **Establish commit conventions** before beginning refactoring
2. **Use meaningful branch names** that reflect architectural changes
3. **Consider creating tag points** at key milestones
4. **Document major structural changes** in commit messages for future reference
5. **Plan file transformation strategy** to preserve maximum history
6. **Use pull request templates** to ensure consistent documentation
7. **Create automated reports** to track refactoring progress

### Tools and Scripts

**Useful Git aliases for refactoring work:**

```bash
# Add to ~/.gitconfig
[alias]
    # View file history across renames
    follow = log --follow --name-status

    # Detailed history with diffs
    history = log --follow -p

    # Compare branches with summary
    compare = diff --stat

    # Show renamed files
    renames = diff --name-status --diff-filter=R
```

**Script for generating refactoring reports:**

```bash
#!/bin/bash
# generate-refactoring-report.sh

FROM_TAG=$1
TO_TAG=$2
OUTPUT_DIR="refactoring-reports"

mkdir -p $OUTPUT_DIR

echo "Generating refactoring report from $FROM_TAG to $TO_TAG..."

# File changes summary
git diff --stat $FROM_TAG..$TO_TAG > $OUTPUT_DIR/file-changes-summary.txt

# Detailed file changes
git diff --name-status $FROM_TAG..$TO_TAG > $OUTPUT_DIR/file-changes-detailed.txt

# Commit history
git log --oneline $FROM_TAG..$TO_TAG > $OUTPUT_DIR/commit-history.txt

# Contributors
git shortlog -sn $FROM_TAG..$TO_TAG > $OUTPUT_DIR/contributors.txt

echo "Report generated in $OUTPUT_DIR/"
```

---

## See Also

- [Methodology](methodology.md) - Overall refactoring approach
- [Architectural Changes](architectural-changes.md) - Technical details of changes made
- [Migration Strategy](migration-strategy.md) - Step-by-step migration process

---

**[⬅️ Back to Refactoring Process](README.md)** | **[➡️ Next: Architectural Changes](architectural-changes.md)**
