/**
 * Enhanced Lazy Loading Module
 *
 * Provides fallback support for browsers without native lazy loading
 * and enhanced features for iframes, videos, and background images.
 *
 * @package HoGScaffold
 */

/* global HTMLImageElement, IntersectionObserver, MutationObserver, requestAnimationFrame */

class LazyLoader {
	constructor() {
		this.imageSelector = 'img[loading="lazy"]';
		this.iframeSelector = "iframe[data-src]";
		this.videoSelector = "video[data-src]";
		this.backgroundSelector = "[data-bg]";

		this.intersectionObserver = null;
		this.mutationObserver = null;

		this.init();
	}

	/**
	 * Initialize lazy loading
	 */
	init() {
		// Check for native lazy loading support
		if ("loading" in HTMLImageElement.prototype) {
			// Native lazy loading is supported, just handle additional features
			this.handleIframes();
			this.handleVideos();
			this.handleBackgroundImages();
		} else {
			// Fallback for browsers without native support
			this.initIntersectionObserver();
			this.handleImages();
			this.handleIframes();
			this.handleVideos();
			this.handleBackgroundImages();
		}

		// Watch for dynamically added content
		this.initMutationObserver();
	}

	/**
	 * Initialize Intersection Observer
	 */
	initIntersectionObserver() {
		if (!("IntersectionObserver" in window)) {
			// Fallback for browsers without Intersection Observer
			this.fallbackToScrollListener();
			return;
		}

		const options = {
			root: null,
			rootMargin: "50px 0px",
			threshold: 0.01,
		};

		this.intersectionObserver = new IntersectionObserver((entries) => {
			entries.forEach((entry) => {
				if (entry.isIntersecting) {
					this.loadElement(entry.target);
					this.intersectionObserver.unobserve(entry.target);
				}
			});
		}, options);
	}

	/**
	 * Handle image lazy loading
	 */
	handleImages() {
		const images = document.querySelectorAll(this.imageSelector);
		images.forEach((img) => {
			if (!img.dataset.src && img.src) {
				// Move src to data-src for lazy loading
				img.dataset.src = img.src;
				img.src = this.generatePlaceholder(img.width || 300, img.height || 200);
			}

			if (this.intersectionObserver) {
				this.intersectionObserver.observe(img);
			}
		});
	}

	/**
	 * Handle iframe lazy loading
	 */
	handleIframes() {
		const iframes = document.querySelectorAll(this.iframeSelector);
		iframes.forEach((iframe) => {
			if (this.intersectionObserver) {
				this.intersectionObserver.observe(iframe);
			} else if (this.isElementInViewport(iframe)) {
				this.loadElement(iframe);
			}
		});
	}

	/**
	 * Handle video lazy loading
	 */
	handleVideos() {
		const videos = document.querySelectorAll(this.videoSelector);
		videos.forEach((video) => {
			if (this.intersectionObserver) {
				this.intersectionObserver.observe(video);
			} else if (this.isElementInViewport(video)) {
				this.loadElement(video);
			}
		});
	}

	/**
	 * Handle background image lazy loading
	 */
	handleBackgroundImages() {
		const elements = document.querySelectorAll(this.backgroundSelector);
		elements.forEach((element) => {
			if (this.intersectionObserver) {
				this.intersectionObserver.observe(element);
			} else if (this.isElementInViewport(element)) {
				this.loadElement(element);
			}
		});
	}

	/**
	 * Load an element (image, iframe, video, background)
	 */
	loadElement(element) {
		if (element.tagName === "IMG") {
			this.loadImage(element);
		} else if (element.tagName === "IFRAME") {
			this.loadIframe(element);
		} else if (element.tagName === "VIDEO") {
			this.loadVideo(element);
		} else if (element.dataset.bg) {
			this.loadBackgroundImage(element);
		}
	}

	/**
	 * Load image
	 */
	loadImage(img) {
		if (img.dataset.src) {
			img.src = img.dataset.src;
			img.removeAttribute("data-src");
		}

		if (img.dataset.srcset) {
			img.srcset = img.dataset.srcset;
			img.removeAttribute("data-srcset");
		}

		img.classList.add("loaded");
	}

	/**
	 * Load iframe
	 */
	loadIframe(iframe) {
		if (iframe.dataset.src) {
			iframe.src = iframe.dataset.src;
			iframe.removeAttribute("data-src");
			iframe.classList.add("loaded");
		}
	}

	/**
	 * Load video
	 */
	loadVideo(video) {
		if (video.dataset.src) {
			video.src = video.dataset.src;
			video.removeAttribute("data-src");
			video.classList.add("loaded");
		}

		// Load video sources
		const sources = video.querySelectorAll("source[data-src]");
		sources.forEach((source) => {
			if (source.dataset.src) {
				source.src = source.dataset.src;
				source.removeAttribute("data-src");
			}
		});

		if (sources.length > 0) {
			video.load();
		}
	}

	/**
	 * Load background image
	 */
	loadBackgroundImage(element) {
		if (element.dataset.bg) {
			element.style.backgroundImage = `url(${element.dataset.bg})`;
			element.removeAttribute("data-bg");
			element.classList.add("loaded");
		}
	}

	/**
	 * Generate placeholder image
	 */
	generatePlaceholder(width, height) {
		const canvas = document.createElement("canvas");
		canvas.width = width;
		canvas.height = height;

		const ctx = canvas.getContext("2d");
		ctx.fillStyle = "#f0f0f0";
		ctx.fillRect(0, 0, width, height);

		return canvas.toDataURL();
	}

	/**
	 * Check if element is in viewport (fallback)
	 */
	isElementInViewport(element) {
		const rect = element.getBoundingClientRect();
		const windowHeight =
			window.innerHeight || document.documentElement.clientHeight;
		const windowWidth =
			window.innerWidth || document.documentElement.clientWidth;

		return (
			rect.top >= -50 &&
			rect.left >= -50 &&
			rect.bottom <= windowHeight + 50 &&
			rect.right <= windowWidth + 50
		);
	}

	/**
	 * Fallback to scroll listener for older browsers
	 */
	fallbackToScrollListener() {
		let ticking = false;

		const lazyLoad = () => {
			if (!ticking) {
				requestAnimationFrame(() => {
					const elements = document.querySelectorAll(
						`${this.imageSelector}[data-src], ${this.iframeSelector}, ${this.videoSelector}, ${this.backgroundSelector}`
					);

					elements.forEach((element) => {
						if (this.isElementInViewport(element)) {
							this.loadElement(element);
						}
					});

					ticking = false;
				});
				ticking = true;
			}
		};

		// Initial check
		lazyLoad();

		// Add scroll and resize listeners
		window.addEventListener("scroll", lazyLoad, { passive: true });
		window.addEventListener("resize", lazyLoad, { passive: true });
	}

	/**
	 * Initialize mutation observer for dynamic content
	 */
	initMutationObserver() {
		if (!("MutationObserver" in window)) {
			return;
		}

		this.mutationObserver = new MutationObserver((mutations) => {
			mutations.forEach((mutation) => {
				if (mutation.type === "childList" && mutation.addedNodes.length > 0) {
					mutation.addedNodes.forEach((node) => {
						if (node.nodeType === 1) {
							// Element node
							this.observeNewElements(node);
						}
					});
				}
			});
		});

		this.mutationObserver.observe(document.body, {
			childList: true,
			subtree: true,
		});
	}

	/**
	 * Observe new elements added to the DOM
	 */
	observeNewElements(element) {
		// Check if element itself matches selectors
		const selectors = [
			this.imageSelector,
			this.iframeSelector,
			this.videoSelector,
			this.backgroundSelector,
		];

		selectors.forEach((selector) => {
			if (element.matches && element.matches(selector)) {
				if (this.intersectionObserver) {
					this.intersectionObserver.observe(element);
				}
			}
		});

		// Check children
		selectors.forEach((selector) => {
			const children = element.querySelectorAll(selector);
			children.forEach((child) => {
				if (this.intersectionObserver) {
					this.intersectionObserver.observe(child);
				}
			});
		});
	}

	/**
	 * Destroy the lazy loader
	 */
	destroy() {
		if (this.intersectionObserver) {
			this.intersectionObserver.disconnect();
		}

		if (this.mutationObserver) {
			this.mutationObserver.disconnect();
		}
	}
}

// Initialize when DOM is ready
if (document.readyState === "loading") {
	document.addEventListener("DOMContentLoaded", () => {
		window.lazyLoader = new LazyLoader();
	});
} else {
	window.lazyLoader = new LazyLoader();
}

// Add helper function for manual triggering
window.lazyLoadImages = function () {
	if (window.lazyLoader) {
		window.lazyLoader.handleImages();
		window.lazyLoader.handleIframes();
		window.lazyLoader.handleVideos();
		window.lazyLoader.handleBackgroundImages();
	}
};
