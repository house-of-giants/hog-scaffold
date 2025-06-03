[Documentation](../README.md) > [Blocks](README.md) > Layout Blocks Quick Start

# Quick Start Guide: Layout Blocks

Get up and running with the WordPress layout blocks system in minutes.

## Prerequisites

- Node.js 18+ installed
- WordPress development environment
- Basic familiarity with WordPress block development

## Getting Started

### 1. Install Dependencies

```bash
npm install
```

### 2. Start Development

```bash
npm run watch
```

This will:

- Compile all JavaScript and CSS
- Watch for file changes
- Rebuild automatically

### 3. Test the Blocks

1. Go to your WordPress admin
2. Create a new post/page
3. In the block inserter, look for the "HoG Scaffold Blocks" category
4. Try inserting:
   - Container Block
   - Spacer Block
   - Divider Block

## Creating Your First Block

### Step 1: Copy the Template

```bash
cp -r inc/blocks/layout/_template inc/blocks/layout/my-block
```

### Step 2: Update the Files

1. **Update `block.json`:**

   ```json
   {
   	"name": "hog-scaffold/my-block",
   	"title": "My Block",
   	"description": "My custom layout block"
   }
   ```

2. **Update class names in CSS files:**

   - Replace `hog-template-block` with `hog-my-block`

3. **Update JavaScript files:**
   - Replace `hog-template-block` class names
   - Update component names and text

### Step 3: Add to Build System

1. **Add webpack entries in `config/webpack.settings.cjs`:**

   ```javascript
   'my-block-editor': './inc/blocks/layout/my-block/index.js',
   'my-block-style': './inc/blocks/layout/my-block/style.css',
   ```

2. **Update block.json paths:**

   ```json
   {
   	"editorScript": "file:../../../js/my-block-editor.js",
   	"style": "file:../../../css/my-block-style.css"
   }
   ```

3. **Import in layout index:**
   ```javascript
   // inc/blocks/layout/index.js
   import "./my-block/index.js";
   ```

### Step 4: Build and Test

```bash
npm run build
```

Your block should now appear in the WordPress block inserter!

## Using Utility Components

### Spacing Controls

```javascript
import { SpacingControls } from "../../utils/index.js";

// In your edit component
<SpacingControls
	values={spacing}
	onChange={(value) => setAttributes({ spacing: value })}
	title="Custom Spacing"
	showMargin={true}
	showPadding={true}
/>;
```

### Background Controls

```javascript
import { BackgroundControls } from "../../utils/index.js";

// In your edit component
<BackgroundControls
	values={background}
	onChange={(value) => setAttributes({ background: value })}
	title="Background Settings"
/>;
```

### Helper Functions

```javascript
import {
	getSpacingClasses,
	getSpacingStyles,
	getBackgroundStyles,
} from "../../utils/index.js";

// Generate CSS classes and styles
const className = getSpacingClasses(spacing);
const styles = {
	...getSpacingStyles(spacing),
	...getBackgroundStyles(background),
};
```

## Common Patterns

### Basic Block Structure

```javascript
export default function Edit({ attributes, setAttributes }) {
	const { myAttribute } = attributes;

	const blockProps = useBlockProps({
		className: "my-block-class",
	});

	return (
		<>
			<InspectorControls>{/* Your controls here */}</InspectorControls>

			<div {...blockProps}>{/* Your block content */}</div>
		</>
	);
}
```

### Adding Custom Attributes

```json
{
	"attributes": {
		"myCustomAttribute": {
			"type": "string",
			"default": "default-value"
		},
		"myNumber": {
			"type": "number",
			"default": 42
		},
		"myBoolean": {
			"type": "boolean",
			"default": false
		}
	}
}
```

### Block Variations

```json
{
	"variations": [
		{
			"name": "my-variation",
			"title": "My Variation",
			"description": "A variation of my block",
			"attributes": {
				"myAttribute": "variation-value"
			},
			"scope": ["inserter"]
		}
	]
}
```

## Development Tips

### Debugging

1. **Check browser console** for JavaScript errors
2. **Verify webpack build** succeeds without errors
3. **Check file paths** in block.json files
4. **Ensure proper imports** in JavaScript files

### Performance

1. **Import only what you need** from utility functions
2. **Use CSS classes** instead of inline styles when possible
3. **Optimize images** and assets
4. **Test on various devices** and browsers

### Accessibility

1. **Add proper ARIA labels**
2. **Ensure keyboard navigation** works
3. **Use semantic HTML** elements
4. **Test with screen readers**

## Build Commands

- `npm run watch` - Development with file watching
- `npm run build` - Production build
- `npm run dev` - Development build
- `npm run lint` - Check code quality
- `npm run format` - Fix code formatting

## Troubleshooting

### Block doesn't appear

- Check if it's imported in `layout/index.js`
- Verify block.json syntax
- Ensure webpack build completed successfully

### Styles not loading

- Check CSS file paths in block.json
- Verify webpack CSS entries
- Clear browser cache

### JavaScript errors

- Check import paths
- Verify all dependencies are available
- Look for typos in attribute names

### Build errors

- Check webpack configuration
- Verify file extensions (.js, .css)
- Ensure all files exist

## Next Steps

1. **Read the full documentation** in [Layout Blocks System](layout-blocks-system.md)
2. **Explore existing blocks** for more complex examples
3. **Customize the utility components** for your needs
4. **Add your own CSS utility classes**
5. **Create block patterns** for common layouts

## Getting Help

- Check the [Layout Blocks System](layout-blocks-system.md) for detailed documentation
- Look at existing block code for examples
- Review WordPress Block Editor Handbook
- Test thoroughly across different browsers and devices

Happy block building! 🎉

---

## See Also

- [Layout Blocks System](layout-blocks-system.md) - Comprehensive system documentation
- [Interactive Block Patterns](interactive-block-patterns.md) - Advanced interactive patterns
- [Block Development Guide](block-development-guide.md) - General block development
- [Getting Started Guide](../getting-started/README.md) - Theme setup and installation

---

**[⬅️ Back to Blocks](README.md)** | **[➡️ Next: Layout Blocks System](layout-blocks-system.md)**
