/* eslint-env node */

/* global console, process, Buffer, fetch */

/**
 * Clear WPEngine Cache Script
 *
 * This script provides utilities for clearing various types of cache
 * on WPEngine hosting environments.
 */

const https = require("https");
const { execSync } = require("child_process");

class WPEngineCacheClearer {
	constructor(options = {}) {
		this.environment =
			options.environment || process.env.WPENGINE_ENV || "staging";
		this.installName = options.installName || process.env.WPENGINE_INSTALL_NAME;
		this.apiUser = options.apiUser || process.env.WPENGINE_API_USER;
		this.apiPassword = options.apiPassword || process.env.WPENGINE_API_PASSWORD;
		this.siteUrl = options.siteUrl || process.env.SITE_URL;
	}

	/**
	 * Clear WPEngine object cache via API
	 */
	async clearObjectCache() {
		if (!this.installName || !this.apiUser || !this.apiPassword) {
			console.log(
				"⚠️  WPEngine API credentials not configured. Skipping object cache clear."
			);
			return;
		}

		console.log(
			`🧹 Clearing object cache for ${this.installName} (${this.environment})...`
		);

		const postData = JSON.stringify({
			environment: this.environment,
		});

		const options = {
			hostname: "api.wpengine.com",
			port: 443,
			path: `/v1/installs/${this.installName}/object_cache`,
			method: "DELETE",
			headers: {
				"Content-Type": "application/json",
				"Content-Length": Buffer.byteLength(postData),
				Authorization:
					"Basic " +
					Buffer.from(`${this.apiUser}:${this.apiPassword}`).toString("base64"),
			},
		};

		return new Promise((resolve, reject) => {
			const req = https.request(options, (res) => {
				let data = "";
				res.on("data", (chunk) => {
					data += chunk;
				});
				res.on("end", () => {
					if (res.statusCode === 200 || res.statusCode === 204) {
						console.log("✅ Object cache cleared successfully");
						resolve(data);
					} else {
						console.error(`❌ Error clearing cache: ${res.statusCode}`);
						reject(new Error(`HTTP ${res.statusCode}: ${data}`));
					}
				});
			});

			req.on("error", (error) => {
				console.error("❌ Error clearing cache:", error.message);
				reject(error);
			});

			req.write(postData);
			req.end();
		});
	}

	/**
	 * Clear page cache using WP-CLI (if available)
	 */
	async clearPageCache() {
		console.log("🧹 Clearing page cache...");

		try {
			// Try WP-CLI cache flush
			execSync("wp cache flush", { stdio: "inherit" });
			console.log("✅ Page cache cleared via WP-CLI");
		} catch (err) {
			console.log(err);
			console.log("⚠️  WP-CLI not available or cache flush failed");
		}

		// Alternative: Clear cache via HTTP requests if WP-CLI not available
		if (this.siteUrl) {
			await this.warmupCache();
		}
	}

	/**
	 * Clear CDN cache if configured
	 */
	async clearCdnCache() {
		console.log("🧹 Clearing CDN cache...");

		// This is a placeholder for CDN-specific cache clearing
		// Examples:
		// - CloudFlare API calls
		// - MaxCDN API calls
		// - WPEngine CDN API calls

		console.log(
			"⚠️  CDN cache clearing not configured. Please implement for your CDN provider."
		);
	}

	/**
	 * Warm up cache by making requests to key pages
	 */
	async warmupCache() {
		if (!this.siteUrl) {
			console.log("⚠️  Site URL not configured. Skipping cache warmup.");
			return;
		}

		console.log("🔥 Warming up cache...");

		const urlsToWarmup = [
			"/",
			"/about/",
			"/contact/",
			"/blog/",
			// Add more URLs as needed
		];

		const promises = urlsToWarmup.map(async (path) => {
			try {
				const url = `${this.siteUrl}${path}`;
				console.log(`🔄 Warming up: ${url}`);

				const response = await fetch(url);
				if (response.ok) {
					console.log(`✅ Warmed up: ${path}`);
				} else {
					console.log(`⚠️  Failed to warm up: ${path} (${response.status})`);
				}
			} catch (error) {
				console.log(`❌ Error warming up ${path}:`, error.message);
			}
		});

		await Promise.all(promises);
		console.log("✅ Cache warmup complete");
	}

	/**
	 * Clear all cache types
	 */
	async clearAllCache() {
		console.log("🧹 Starting comprehensive cache clear...\n");

		try {
			await this.clearObjectCache();
			await this.clearPageCache();
			await this.clearCdnCache();
			console.log("\n✅ All cache clearing operations complete!");
		} catch (error) {
			console.error("\n❌ Cache clearing failed:", error.message);
			process.exit(1);
		}
	}
}

// CLI usage
if (require.main === module) {
	const args = process.argv.slice(2);
	const command = args[0] || "all";

	const cacheClearer = new WPEngineCacheClearer();

	switch (command) {
		case "object":
			cacheClearer.clearObjectCache().catch(console.error);
			break;
		case "page":
			cacheClearer.clearPageCache().catch(console.error);
			break;
		case "cdn":
			cacheClearer.clearCdnCache().catch(console.error);
			break;
		case "warmup":
			cacheClearer.warmupCache().catch(console.error);
			break;
		case "all":
		default:
			cacheClearer.clearAllCache().catch(console.error);
			break;
	}
}

module.exports = WPEngineCacheClearer;
