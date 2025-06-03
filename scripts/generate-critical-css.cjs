#!/usr/bin/env node

/* eslint-env node */
/* global console, __dirname, process */

/**
 * Generate critical CSS for different page templates
 * This script uses the 'critical' package to extract above-the-fold CSS
 */

const critical = require("critical");
const path = require("path");
const fs = require("fs");

// Configuration
const config = {
	base: "dist/",
	src: "../index.html", // You would need to create template HTML files
	dest: "css/",
	width: 1300,
	height: 900,
	// Inline critical CSS
	inline: false,
	// Extract inlined styles from referenced stylesheets
	extract: true,
	// Minify critical CSS
	minify: true,
	// Ignore CSS rules that contain '@font-face'
	ignore: {
		atrule: ["@font-face"],
		rule: [],
		decl: [],
	},
};

// Templates to generate critical CSS for
const templates = [
	{
		name: "home",
		url: "http://localhost:3000/", // Your local development URL
		output: "critical-home.css",
	},
	{
		name: "single",
		url: "http://localhost:3000/sample-post/",
		output: "critical-single.css",
	},
	{
		name: "page",
		url: "http://localhost:3000/sample-page/",
		output: "critical-page.css",
	},
	{
		name: "archive",
		url: "http://localhost:3000/category/sample-category/",
		output: "critical-archive.css",
	},
];

/**
 * Generate critical CSS for all templates
 */
async function generateCriticalCSS() {
	console.log("🚀 Starting critical CSS generation...");

	// Ensure the CSS directory exists
	const cssDir = path.join(__dirname, "..", "dist", "css");
	if (!fs.existsSync(cssDir)) {
		fs.mkdirSync(cssDir, { recursive: true });
	}

	for (const template of templates) {
		try {
			console.log(`📦 Generating critical CSS for ${template.name}...`);

			const result = await critical.generate({
				...config,
				src: template.url,
				target: {
					css: template.output,
				},
				dimensions: [
					{
						width: 320,
						height: 568,
					},
					{
						width: 768,
						height: 1024,
					},
					{
						width: 1300,
						height: 900,
					},
				],
			});

			// Write the critical CSS to file
			const outputPath = path.join(cssDir, template.output);
			fs.writeFileSync(outputPath, result.css);

			console.log(
				`✅ Critical CSS generated for ${template.name}: ${template.output}`
			);
		} catch (error) {
			console.error(
				`❌ Error generating critical CSS for ${template.name}:`,
				error.message
			);
		}
	}

	console.log("🎉 Critical CSS generation complete!");
}

/**
 * Generate fallback critical CSS files if URLs are not accessible
 */
function generateFallbackCriticalCSS() {
	console.log("📝 Generating fallback critical CSS files...");

	const cssDir = path.join(__dirname, "..", "dist", "css");
	if (!fs.existsSync(cssDir)) {
		fs.mkdirSync(cssDir, { recursive: true });
	}

	const fallbackCSS = `
/* Critical CSS - Generated fallback */
/* Reset and normalize */
*,*::before,*::after{box-sizing:border-box}
html{line-height:1.15;-webkit-text-size-adjust:100%}
body{margin:0;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,sans-serif}

/* Basic layout styles */
.site-header{position:relative;z-index:999}
.site-main{min-height:50vh}
.site-footer{margin-top:2rem}

/* Typography */
h1,h2,h3,h4,h5,h6{margin:0 0 1rem;font-weight:600;line-height:1.2}
p{margin:0 0 1rem}

/* Layout utilities */
.container{max-width:1200px;margin:0 auto;padding:0 1rem}
.screen-reader-text{clip:rect(1px,1px,1px,1px);position:absolute!important;height:1px;width:1px;overflow:hidden}

/* WordPress core styles */
.aligncenter{display:block;margin:0 auto}
.alignleft{float:left;margin:0 1rem 1rem 0}
.alignright{float:right;margin:0 0 1rem 1rem}
	`.trim();

	templates.forEach((template) => {
		const outputPath = path.join(cssDir, template.output);
		if (!fs.existsSync(outputPath)) {
			fs.writeFileSync(outputPath, fallbackCSS);
			console.log(`📄 Created fallback critical CSS: ${template.output}`);
		}
	});

	// Also create a general critical.css file
	const generalCriticalPath = path.join(cssDir, "critical.css");
	if (!fs.existsSync(generalCriticalPath)) {
		fs.writeFileSync(generalCriticalPath, fallbackCSS);
		console.log("📄 Created fallback critical.css");
	}
}

// Run the script
if (require.main === module) {
	// Check if we're in a local development environment
	if (
		process.env.NODE_ENV === "production" ||
		process.argv.includes("--production")
	) {
		generateCriticalCSS().catch((error) => {
			console.error("Failed to generate critical CSS:", error);
			// Fallback to static critical CSS
			generateFallbackCriticalCSS();
		});
	} else {
		// In development, just create fallback files
		generateFallbackCriticalCSS();
	}
}

module.exports = {
	generateCriticalCSS,
	generateFallbackCriticalCSS,
};
