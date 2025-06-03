# Contributing to Documentation

We welcome contributions to improve the House of Giants WordPress Theme Scaffold documentation. This guide outlines how to contribute effectively to our documentation system.

## Table of Contents

- [Getting Started](#getting-started)
- [Documentation Standards](#documentation-standards)
- [Contribution Process](#contribution-process)
- [Types of Contributions](#types-of-contributions)
- [Review Process](#review-process)
- [Style Guidelines](#style-guidelines)

## Getting Started

### Prerequisites

Before contributing to documentation:

1. **Familiarize yourself** with the [Style Guide](style-guide.md)
2. **Review existing documentation** to understand our structure and tone
3. **Set up the development environment** following the [Build System Guide](getting-started/build-system.md)

### Documentation Structure

Our documentation is organized in the following structure:

```
docs/
├── README.md                    # Main documentation index
├── style-guide.md              # Documentation standards
├── theme-features-overview.md  # Theme overview
├── getting-started/            # Setup and onboarding
├── customization/              # Theme customization guides
├── blocks/                     # Block development
├── deployment/                 # Production deployment
├── security/                   # Security and accessibility
├── api/                        # Technical references
└── troubleshooting/            # Common issues and solutions
```

## Documentation Standards

### Writing Standards

- **Follow the [Style Guide](style-guide.md)** for formatting and tone
- **Use clear, concise language** appropriate for developers
- **Include practical examples** and code snippets
- **Maintain professional but approachable tone**
- **Write in second person** ("you") for instructions

### Technical Standards

- **Use kebab-case** for file names (e.g., `block-development-guide.md`)
- **Include navigation breadcrumbs** at the top of each document
- **Add "See also" sections** with related links
- **Use proper markdown syntax** with language identifiers for code blocks
- **Include table of contents** for longer documents

### Content Standards

- **Ensure accuracy** - all code examples should be tested and functional
- **Keep content current** - update documentation when code changes
- **Include context** - explain why something matters, not just how to do it
- **Add screenshots** when helpful for understanding UI elements

## Contribution Process

### 1. Planning Your Contribution

Before starting work:

- **Check existing issues** for documentation improvements
- **Create an issue** if one doesn't exist for your planned contribution
- **Discuss significant changes** with maintainers before starting

### 2. Making Changes

1. **Fork the repository** and create a feature branch
2. **Make your changes** following our documentation standards
3. **Test all links** and code examples
4. **Review your changes** for spelling, grammar, and formatting

### 3. Submitting Changes

1. **Create a pull request** with a clear description of changes
2. **Reference related issues** in your PR description
3. **Request review** from documentation maintainers
4. **Address feedback** promptly and professionally

## Types of Contributions

### Content Updates

- **Correcting errors** in existing documentation
- **Updating outdated information** to reflect current code
- **Improving clarity** of existing explanations
- **Adding missing information** to incomplete sections

### New Documentation

- **Creating guides** for undocumented features
- **Adding tutorials** for common workflows
- **Documenting new features** as they're developed
- **Creating troubleshooting guides** for common issues

### Structural Improvements

- **Reorganizing content** for better navigation
- **Improving cross-references** between documents
- **Adding navigation elements** to improve usability
- **Creating new documentation categories** when needed

### Visual Enhancements

- **Adding screenshots** to clarify UI instructions
- **Creating diagrams** to explain complex concepts
- **Improving code examples** with better formatting
- **Adding visual aids** where helpful

## Review Process

### Review Criteria

Documentation reviews focus on:

- **Accuracy**: All information is correct and up-to-date
- **Clarity**: Content is easy to understand and follow
- **Completeness**: All necessary information is included
- **Consistency**: Follows our style guide and standards
- **Usefulness**: Provides value to developers using the theme

### Review Timeline

- **Initial review**: Within 2-3 business days
- **Follow-up reviews**: Within 1-2 business days after updates
- **Final approval**: When all feedback is addressed

### Reviewer Responsibilities

Reviewers will:

- **Check technical accuracy** of all code examples
- **Verify links** work correctly
- **Ensure style guide compliance**
- **Test instructions** when possible
- **Provide constructive feedback**

## Style Guidelines

### Markdown Formatting

```markdown
# Document Title (H1 - use once per document)

## Major Section (H2)

### Subsection (H3)

#### Sub-subsection (H4 - use sparingly)

- Use hyphens for unordered lists
- Include code examples with language identifiers

```php
function example_function() {
    // Well-commented code
}
```

Use `inline code` for function names and short snippets.
```

### Navigation Elements

**Breadcrumbs** (at top of document):
```markdown
[Documentation](../README.md) > [Section](README.md) > Document Title
```

**Cross-references** (at bottom of document):
```markdown
## See Also

- [Related Document 1](../category/document.md)
- [Related Document 2](../category/other-document.md)

---

**[⬅️ Back to Documentation Index](../README.md)** | **[➡️ Next: Related Document](next-document.md)**
```

### Code Examples

- **Always specify language** for syntax highlighting
- **Include comments** to explain complex code
- **Test all examples** to ensure they work
- **Use realistic examples** rather than placeholder text

### Images and Screenshots

- **Use descriptive file names**: `theme-customizer-screenshot.png`
- **Include alt text**: `![WordPress customizer interface](image.png)`
- **Optimize file sizes** for web display
- **Store in appropriate subdirectories**

## Common Mistakes to Avoid

### Content Issues

- **Don't assume prior knowledge** - explain concepts clearly
- **Don't use jargon** without explanation
- **Don't skip error handling** in code examples
- **Don't forget to update related documents** when making changes

### Formatting Issues

- **Don't use inconsistent heading levels**
- **Don't forget language identifiers** in code blocks
- **Don't use absolute paths** for internal links
- **Don't skip navigation elements**

### Process Issues

- **Don't make large changes** without discussion
- **Don't ignore review feedback**
- **Don't submit untested code examples**
- **Don't forget to update the main index** when adding new documents

## Getting Help

### Resources

- **Style Guide**: [style-guide.md](style-guide.md) - Complete formatting standards
- **Existing Documentation**: Review similar documents for examples
- **GitHub Issues**: Check for related discussions or questions

### Contact

- **Create an issue** for questions about documentation
- **Tag maintainers** in pull requests for urgent questions
- **Follow up** if you don't receive a response within a few days

## Recognition

We appreciate all contributions to our documentation! Contributors will be:

- **Credited** in pull request acknowledgments
- **Mentioned** in release notes for significant contributions
- **Added to contributors list** for ongoing participation

## Examples

### Good Contribution Example

```markdown
[Documentation](../README.md) > [Blocks](README.md) > Custom Block Tutorial

# Creating a Custom Block

This tutorial walks you through creating a custom WordPress block for the HoG Scaffold theme.

## Prerequisites

- Basic knowledge of JavaScript and React
- Familiarity with WordPress block development
- Development environment set up per [Build System Guide](../getting-started/build-system.md)

## Step 1: Create Block Structure

Create a new directory for your block:

```bash
mkdir inc/blocks/my-custom-block
cd inc/blocks/my-custom-block
```

## Step 2: Add Block Metadata

Create `block.json` with the following content:

```json
{
  "$schema": "https://schemas.wp.org/trunk/block.json",
  "apiVersion": 3,
  "name": "hog-scaffold/my-custom-block",
  "title": "My Custom Block",
  "category": "hog-scaffold",
  "description": "A custom block example",
  "textdomain": "hog-scaffold"
}
```

## See Also

- [Block Development Guide](block-development-guide.md) - Complete block development reference
- [Build System Guide](../getting-started/build-system.md) - Development workflow

---

**[⬅️ Back to Documentation Index](../README.md)** | **[➡️ Next: Block Patterns](block-patterns.md)**
```

Thank you for contributing to the House of Giants WordPress Theme Scaffold documentation! Your contributions help make this project better for everyone.

---

**[⬅️ Back to Documentation Index](README.md)** 