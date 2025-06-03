#!/usr/bin/env node

/**
 * CSS Optimization Script
 * Analyzes CSS usage and identifies optimization opportunities
 */

/* global require, process */

const fs = require("fs");
const path = require("path");

class CSSOptimizer {
	constructor() {
		this.projectRoot = process.cwd();
		this.cssFiles = [];
		this.htmlFiles = [];
		this.phpFiles = [];
		this.jsFiles = [];
		this.usedClasses = new Set();
		this.definedClasses = new Set();
		this.unusedClasses = new Set();
	}

	/**
	 * Find all relevant files
	 */
	findFiles() {
		console.log("🔍 Finding project files...");

		// Find CSS files
		this.cssFiles = this.findFilesByExtension("assets/css", ".css");

		// Find template files
		this.htmlFiles = this.findFilesByExtension("parts", ".html");
		this.htmlFiles.push(...this.findFilesByExtension("patterns", ".php"));
		this.htmlFiles.push(...this.findFilesByExtension("templates", ".html"));

		// Find PHP files
		this.phpFiles = this.findFilesByExtension(".", ".php", [
			"vendor",
			"node_modules",
		]);

		// Find JS files
		this.jsFiles = this.findFilesByExtension("assets/js", ".js");
		this.jsFiles.push(...this.findFilesByExtension("inc/blocks", ".js"));

		console.log(`Found ${this.cssFiles.length} CSS files`);
		console.log(`Found ${this.htmlFiles.length} HTML/template files`);
		console.log(`Found ${this.phpFiles.length} PHP files`);
		console.log(`Found ${this.jsFiles.length} JS files`);
	}

	/**
	 * Find files by extension in directory
	 */
	findFilesByExtension(dir, ext, excludeDirs = []) {
		const files = [];
		const fullDir = path.join(this.projectRoot, dir);

		if (!fs.existsSync(fullDir)) {
			return files;
		}

		const walk = (currentDir) => {
			const items = fs.readdirSync(currentDir);

			for (const item of items) {
				const fullPath = path.join(currentDir, item);
				const stat = fs.statSync(fullPath);

				if (stat.isDirectory()) {
					if (!excludeDirs.includes(item) && !item.startsWith(".")) {
						walk(fullPath);
					}
				} else if (item.endsWith(ext)) {
					files.push(fullPath);
				}
			}
		};

		walk(fullDir);
		return files;
	}

	/**
	 * Extract CSS class definitions
	 */
	extractCSSClasses() {
		console.log("📝 Extracting CSS class definitions...");

		for (const file of this.cssFiles) {
			const content = fs.readFileSync(file, "utf8");

			// Match CSS class selectors
			const classMatches = content.match(
				/\.[a-zA-Z][a-zA-Z0-9_-]*(?=[\s.#:[\{,>~+])/g
			);

			if (classMatches) {
				classMatches.forEach((match) => {
					// Remove the leading dot
					const className = match.substring(1);
					this.definedClasses.add(className);
				});
			}
		}

		console.log(`Found ${this.definedClasses.size} defined CSS classes`);
	}

	/**
	 * Extract used classes from templates and PHP files
	 */
	extractUsedClasses() {
		console.log("🔎 Extracting used CSS classes...");

		const allFiles = [...this.htmlFiles, ...this.phpFiles, ...this.jsFiles];

		for (const file of allFiles) {
			const content = fs.readFileSync(file, "utf8");

			// Match class attributes
			const classMatches = content.match(/class\s*=\s*["']([^"']+)["']/g);

			if (classMatches) {
				classMatches.forEach((match) => {
					// Extract class names from the attribute
					const classAttr = match.match(/class\s*=\s*["']([^"']+)["']/);
					if (classAttr && classAttr[1]) {
						const classes = classAttr[1].split(/\s+/);
						classes.forEach((cls) => {
							if (cls.trim()) {
								this.usedClasses.add(cls.trim());
							}
						});
					}
				});
			}

			// Match classList.add() calls in JavaScript
			const jsClassMatches = content.match(
				/classList\.add\s*\(\s*["']([^"']+)["']\s*\)/g
			);
			if (jsClassMatches) {
				jsClassMatches.forEach((match) => {
					const className = match.match(/["']([^"']+)["']/);
					if (className && className[1]) {
						this.usedClasses.add(className[1]);
					}
				});
			}

			// Match className assignments in JavaScript
			const classNameMatches = content.match(
				/className\s*=\s*["']([^"']+)["']/g
			);
			if (classNameMatches) {
				classNameMatches.forEach((match) => {
					const classAttr = match.match(/className\s*=\s*["']([^"']+)["']/);
					if (classAttr && classAttr[1]) {
						const classes = classAttr[1].split(/\s+/);
						classes.forEach((cls) => {
							if (cls.trim()) {
								this.usedClasses.add(cls.trim());
							}
						});
					}
				});
			}
		}

		console.log(`Found ${this.usedClasses.size} used CSS classes`);
	}

	/**
	 * Find unused CSS classes
	 */
	findUnusedClasses() {
		console.log("🧹 Finding unused CSS classes...");

		for (const definedClass of this.definedClasses) {
			if (!this.usedClasses.has(definedClass)) {
				// Skip WordPress core classes and utility classes that might be used dynamically
				if (
					!this.isWordPressClass(definedClass) &&
					!this.isUtilityClass(definedClass)
				) {
					this.unusedClasses.add(definedClass);
				}
			}
		}

		console.log(
			`Found ${this.unusedClasses.size} potentially unused CSS classes`
		);
	}

	/**
	 * Check if class is a WordPress core class
	 */
	isWordPressClass(className) {
		const wpPrefixes = [
			"wp-",
			"has-",
			"is-",
			"alignfull",
			"alignwide",
			"aligncenter",
			"alignleft",
			"alignright",
			"screen-reader-text",
			"sr-only",
		];

		return wpPrefixes.some((prefix) => className.startsWith(prefix));
	}

	/**
	 * Check if class is a utility class that might be used dynamically
	 */
	isUtilityClass(className) {
		const utilityPrefixes = ["u-", "utility-", "helper-"];
		return utilityPrefixes.some((prefix) => className.startsWith(prefix));
	}

	/**
	 * Generate optimization report
	 */
	generateReport() {
		console.log("\n📊 CSS Optimization Report");
		console.log("=".repeat(50));

		console.log(`\n📈 Statistics:`);
		console.log(`  • Defined classes: ${this.definedClasses.size}`);
		console.log(`  • Used classes: ${this.usedClasses.size}`);
		console.log(`  • Potentially unused: ${this.unusedClasses.size}`);

		if (this.unusedClasses.size > 0) {
			console.log(`\n🗑️  Potentially Unused Classes:`);
			const sortedUnused = Array.from(this.unusedClasses).sort();
			sortedUnused.forEach((className) => {
				console.log(`  • .${className}`);
			});
		}

		// Calculate potential savings
		const totalClasses = this.definedClasses.size;
		const unusedPercentage = (
			(this.unusedClasses.size / totalClasses) *
			100
		).toFixed(1);

		console.log(`\n💾 Potential Savings:`);
		console.log(
			`  • ${unusedPercentage}% of CSS classes could potentially be removed`
		);

		// Recommendations
		console.log(`\n💡 Recommendations:`);
		console.log(
			`  • Review unused classes before removing (some may be used dynamically)`
		);
		console.log(`  • Consider implementing CSS purging in build process`);
		console.log(
			`  • Use CSS-in-JS or component-scoped styles for better tree-shaking`
		);
		console.log(
			`  • Implement critical CSS extraction for above-the-fold content`
		);

		// Save detailed report
		const report = {
			timestamp: new Date().toISOString(),
			statistics: {
				definedClasses: this.definedClasses.size,
				usedClasses: this.usedClasses.size,
				unusedClasses: this.unusedClasses.size,
				unusedPercentage: parseFloat(unusedPercentage),
			},
			unusedClasses: Array.from(this.unusedClasses).sort(),
			definedClasses: Array.from(this.definedClasses).sort(),
			usedClasses: Array.from(this.usedClasses).sort(),
		};

		const reportPath = path.join(
			this.projectRoot,
			"css-optimization-report.json"
		);
		fs.writeFileSync(reportPath, JSON.stringify(report, null, 2));
		console.log(`\n📄 Detailed report saved to: ${reportPath}`);
	}

	/**
	 * Run the optimization analysis
	 */
	run() {
		console.log("🚀 Starting CSS optimization analysis...\n");

		this.findFiles();
		this.extractCSSClasses();
		this.extractUsedClasses();
		this.findUnusedClasses();
		this.generateReport();

		console.log("\n✅ CSS optimization analysis complete!");
	}
}

// Run the optimizer
const optimizer = new CSSOptimizer();
optimizer.run();
