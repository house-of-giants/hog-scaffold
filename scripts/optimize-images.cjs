#!/usr/bin/env node

/* eslint-env node */
/* global console, __dirname, process */

/**
 * Image optimization script
 * Optimizes images and generates WebP versions
 */

const fs = require("fs");
const path = require("path");
const sharp = require("sharp");

/**
 * Optimize a single image file
 */
async function optimizeImage(inputPath, outputPath, options = {}) {
	try {
		const image = sharp(inputPath);
		const metadata = await image.metadata();

		// Default optimization options
		const defaultOptions = {
			jpeg: { quality: 80, progressive: true },
			png: { quality: 80, progressive: true },
			webp: { quality: 80 },
		};

		const opts = { ...defaultOptions, ...options };

		// Optimize based on format
		switch (metadata.format) {
			case "jpeg":
			case "jpg":
				await image.jpeg(opts.jpeg).toFile(outputPath);
				break;

			case "png":
				await image.png(opts.png).toFile(outputPath);
				break;

			case "webp":
				await image.webp(opts.webp).toFile(outputPath);
				break;

			default:
				// Copy other formats as-is
				fs.copyFileSync(inputPath, outputPath);
		}

		// Generate WebP version for web formats
		if (["jpeg", "jpg", "png"].includes(metadata.format)) {
			const webpPath = outputPath.replace(/\.(jpe?g|png)$/i, ".webp");
			await image.webp(opts.webp).toFile(webpPath);
		}

		return true;
	} catch (error) {
		console.error(`Error optimizing ${inputPath}:`, error.message);
		return false;
	}
}

/**
 * Process all images in a directory
 */
async function processDirectory(inputDir, outputDir) {
	if (!fs.existsSync(inputDir)) {
		console.log(`📁 Input directory not found: ${inputDir}`);
		return;
	}

	// Ensure output directory exists
	if (!fs.existsSync(outputDir)) {
		fs.mkdirSync(outputDir, { recursive: true });
	}

	const files = fs.readdirSync(inputDir);
	const imageExtensions = [".jpg", ".jpeg", ".png", ".webp", ".gif"];

	let processedCount = 0;
	let totalSavings = 0;

	for (const file of files) {
		const inputPath = path.join(inputDir, file);
		const stat = fs.statSync(inputPath);

		if (stat.isDirectory()) {
			// Recursively process subdirectories
			await processDirectory(inputPath, path.join(outputDir, file));
		} else if (imageExtensions.includes(path.extname(file).toLowerCase())) {
			const outputPath = path.join(outputDir, file);

			console.log(`🖼️  Processing: ${file}`);

			const originalSize = stat.size;
			const success = await optimizeImage(inputPath, outputPath);

			if (success && fs.existsSync(outputPath)) {
				const optimizedSize = fs.statSync(outputPath).size;
				const savings = originalSize - optimizedSize;
				const savingsPercent = ((savings / originalSize) * 100).toFixed(1);

				console.log(
					`   ✅ Optimized: ${(originalSize / 1024).toFixed(1)}KB → ${(optimizedSize / 1024).toFixed(1)}KB (${savingsPercent}% reduction)`
				);

				totalSavings += savings;
				processedCount++;
			}
		}
	}

	if (processedCount > 0) {
		console.log(`\n📊 Summary for ${inputDir}:`);
		console.log(`   Images processed: ${processedCount}`);
		console.log(`   Total savings: ${(totalSavings / 1024).toFixed(1)}KB`);
	}
}

/**
 * Generate responsive image sizes
 */
async function generateResponsiveSizes(
	inputPath,
	outputDir,
	sizes = [300, 600, 1200, 1920]
) {
	try {
		const image = sharp(inputPath);
		const metadata = await image.metadata();
		const basename = path.basename(inputPath, path.extname(inputPath));
		const ext = path.extname(inputPath);

		console.log(`📐 Generating responsive sizes for: ${basename}${ext}`);

		for (const size of sizes) {
			// Only generate if image is larger than target size
			if (metadata.width > size) {
				const outputPath = path.join(outputDir, `${basename}-${size}w${ext}`);
				const webpPath = path.join(outputDir, `${basename}-${size}w.webp`);

				// Generate resized version
				await image
					.resize(size, null, { withoutEnlargement: true })
					.jpeg({ quality: 80, progressive: true })
					.toFile(outputPath);

				// Generate WebP version
				await image
					.resize(size, null, { withoutEnlargement: true })
					.webp({ quality: 80 })
					.toFile(webpPath);

				console.log(`   ✅ Generated: ${size}w versions`);
			}
		}

		return true;
	} catch (error) {
		console.error(`Error generating responsive sizes:`, error.message);
		return false;
	}
}

/**
 * Main optimization function
 */
async function optimizeImages() {
	console.log("🚀 Starting image optimization...\n");

	const assetsDir = path.join(__dirname, "..", "assets", "images");
	const distDir = path.join(__dirname, "..", "dist", "images");

	// Process all images
	await processDirectory(assetsDir, distDir);

	// Generate responsive sizes for hero images (optional)
	const heroImagesDir = path.join(assetsDir, "hero");
	if (fs.existsSync(heroImagesDir)) {
		console.log("\n📐 Generating responsive hero images...");
		const heroFiles = fs
			.readdirSync(heroImagesDir)
			.filter((file) =>
				[".jpg", ".jpeg", ".png"].includes(path.extname(file).toLowerCase())
			);

		for (const file of heroFiles) {
			await generateResponsiveSizes(
				path.join(heroImagesDir, file),
				path.join(distDir, "hero"),
				[400, 800, 1200, 1920]
			);
		}
	}

	console.log("\n🎉 Image optimization complete!");
	console.log("\n💡 Next steps:");
	console.log(
		"   • Update your templates to use responsive images with srcset"
	);
	console.log("   • Implement lazy loading for below-the-fold images");
	console.log(
		"   • Configure your server to serve WebP to supporting browsers"
	);
}

// Run the optimization
if (require.main === module) {
	optimizeImages().catch((error) => {
		console.error("❌ Image optimization failed:", error);
		process.exit(1);
	});
}

module.exports = {
	optimizeImage,
	processDirectory,
	generateResponsiveSizes,
	optimizeImages,
};
