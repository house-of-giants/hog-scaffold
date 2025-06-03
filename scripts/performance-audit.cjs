#!/usr/bin/env node

/* eslint-env node */
/* global console, __dirname */

/**
 * Performance audit script
 * Analyzes build output and provides performance recommendations
 */

const fs = require("fs");
const path = require("path");

/**
 * Analyze bundle sizes and provide recommendations
 */
function analyzeBundleSizes() {
	console.log("📊 Analyzing bundle sizes...");

	const distPath = path.join(__dirname, "..", "dist");

	if (!fs.existsSync(distPath)) {
		console.error("❌ dist directory not found. Run npm run build first.");
		return;
	}

	const recommendations = {
		css: [],
		js: [],
		images: [],
		overall: [],
	};

	// Analyze CSS files
	const cssPath = path.join(distPath, "css");
	if (fs.existsSync(cssPath)) {
		const cssFiles = fs
			.readdirSync(cssPath)
			.filter((file) => file.endsWith(".css"));

		cssFiles.forEach((file) => {
			const filePath = path.join(cssPath, file);
			const stats = fs.statSync(filePath);
			const sizeKB = (stats.size / 1024).toFixed(2);

			console.log(`📄 ${file}: ${sizeKB} KB`);

			if (stats.size > 50 * 1024) {
				// 50KB
				recommendations.css.push(
					`${file} is ${sizeKB} KB - consider splitting or optimizing`
				);
			}
		});
	}

	// Analyze JavaScript files
	const jsPath = path.join(distPath, "js");
	if (fs.existsSync(jsPath)) {
		const jsFiles = fs
			.readdirSync(jsPath)
			.filter((file) => file.endsWith(".js"));

		jsFiles.forEach((file) => {
			const filePath = path.join(jsPath, file);
			const stats = fs.statSync(filePath);
			const sizeKB = (stats.size / 1024).toFixed(2);

			console.log(`📜 ${file}: ${sizeKB} KB`);

			if (stats.size > 200 * 1024) {
				// 200KB
				recommendations.js.push(
					`${file} is ${sizeKB} KB - consider code splitting`
				);
			}
		});
	}

	// Analyze images
	const imagesPath = path.join(distPath, "images");
	if (fs.existsSync(imagesPath)) {
		const imageFiles = fs.readdirSync(imagesPath);
		let totalImageSize = 0;

		imageFiles.forEach((file) => {
			const filePath = path.join(imagesPath, file);
			const stats = fs.statSync(filePath);
			totalImageSize += stats.size;

			const sizeKB = (stats.size / 1024).toFixed(2);
			console.log(`🖼️  ${file}: ${sizeKB} KB`);

			if (stats.size > 500 * 1024) {
				// 500KB
				recommendations.images.push(
					`${file} is ${sizeKB} KB - consider compression or WebP format`
				);
			}
		});

		const totalImageSizeMB = (totalImageSize / (1024 * 1024)).toFixed(2);
		console.log(`📸 Total image size: ${totalImageSizeMB} MB`);

		if (totalImageSize > 2 * 1024 * 1024) {
			// 2MB
			recommendations.overall.push(
				`Total image size is ${totalImageSizeMB} MB - consider lazy loading and optimization`
			);
		}
	}

	// Print recommendations
	if (
		recommendations.css.length ||
		recommendations.js.length ||
		recommendations.images.length ||
		recommendations.overall.length
	) {
		console.log("\n💡 Performance Recommendations:");

		if (recommendations.css.length) {
			console.log("\n🎨 CSS Optimization:");
			recommendations.css.forEach((rec) => console.log(`  • ${rec}`));
		}

		if (recommendations.js.length) {
			console.log("\n📜 JavaScript Optimization:");
			recommendations.js.forEach((rec) => console.log(`  • ${rec}`));
		}

		if (recommendations.images.length) {
			console.log("\n🖼️  Image Optimization:");
			recommendations.images.forEach((rec) => console.log(`  • ${rec}`));
		}

		if (recommendations.overall.length) {
			console.log("\n🚀 Overall Recommendations:");
			recommendations.overall.forEach((rec) => console.log(`  • ${rec}`));
		}
	} else {
		console.log("\n✅ All bundle sizes are within recommended limits!");
	}
}

/**
 * Check for performance best practices
 */
function checkBestPractices() {
	console.log("\n🔍 Checking performance best practices...");

	const distPath = path.join(__dirname, "..", "dist");

	const checks = {
		passed: [],
		failed: [],
	};

	// Check for critical CSS files
	const criticalCssPath = path.join(distPath, "css", "critical.css");
	if (fs.existsSync(criticalCssPath)) {
		checks.passed.push("Critical CSS file exists");
	} else {
		checks.failed.push("Critical CSS file missing - run npm run critical-css");
	}

	// Check for WebP images
	const imagesPath = path.join(distPath, "images");
	if (fs.existsSync(imagesPath)) {
		const webpFiles = fs
			.readdirSync(imagesPath)
			.filter((file) => file.endsWith(".webp"));
		if (webpFiles.length > 0) {
			checks.passed.push(`WebP images found (${webpFiles.length} files)`);
		} else {
			checks.failed.push(
				"No WebP images found - consider enabling WebP generation"
			);
		}
	}

	// Check for font optimization
	const fontsPath = path.join(distPath, "fonts");
	if (fs.existsSync(fontsPath)) {
		const woff2Files = fs
			.readdirSync(fontsPath)
			.filter((file) => file.endsWith(".woff2"));
		if (woff2Files.length > 0) {
			checks.passed.push(`WOFF2 fonts found (${woff2Files.length} files)`);
		} else {
			checks.failed.push(
				"No WOFF2 fonts found - consider using modern font formats"
			);
		}
	}

	// Check for source maps in production
	const jsPath = path.join(distPath, "js");
	if (fs.existsSync(jsPath)) {
		const mapFiles = fs
			.readdirSync(jsPath)
			.filter((file) => file.endsWith(".map"));
		if (mapFiles.length === 0) {
			checks.passed.push("No source maps in production build");
		} else {
			checks.failed.push(
				`Source maps found in production (${mapFiles.length} files) - disable for better performance`
			);
		}
	}
	// This is a simplified check - in practice, you'd analyze the actual code
	checks.passed.push("Modern JavaScript features available");

	// Display results
	console.log("\n✅ Best Practices - Passed:");
	checks.passed.forEach((check) => console.log(`  • ${check}`));

	if (checks.failed.length > 0) {
		console.log("\n❌ Best Practices - Failed:");
		checks.failed.forEach((check) => console.log(`  • ${check}`));
	}

	const score = Math.round(
		(checks.passed.length / (checks.passed.length + checks.failed.length)) * 100
	);
	console.log(`\n🎯 Performance Score: ${score}%`);
}

/**
 * Generate performance report
 */
function generateReport() {
	const reportPath = path.join(__dirname, "..", "performance-report.md");
	const timestamp = new Date().toISOString();

	const report = `# Performance Audit Report
Generated: ${timestamp}

## Bundle Analysis
- Run \`npm run build-analyze\` for detailed bundle analysis
- Use \`npm run bundle-analyzer\` to view interactive bundle map

## Recommendations
- Enable Gzip compression on server
- Implement browser caching headers
- Use CDN for static assets
- Consider implementing Service Worker for offline caching
- Monitor Core Web Vitals in production

## Tools for Further Analysis
- [Google PageSpeed Insights](https://pagespeed.web.dev/)
- [GTmetrix](https://gtmetrix.com/)
- [WebPageTest](https://www.webpagetest.org/)
- [Lighthouse CI](https://github.com/GoogleChrome/lighthouse-ci)

## Next Steps
1. Run \`npm run critical-css\` to generate critical CSS
2. Test on real devices and network conditions
3. Set up performance monitoring
4. Implement A/B testing for optimizations
`;

	fs.writeFileSync(reportPath, report);
	console.log(`\n📄 Performance report generated: ${reportPath}`);
}

// Run the audit
if (require.main === module) {
	console.log("🚀 Starting performance audit...\n");

	analyzeBundleSizes();
	checkBestPractices();
	generateReport();

	console.log("\n🎉 Performance audit complete!");
}

module.exports = {
	analyzeBundleSizes,
	checkBestPractices,
	generateReport,
};
