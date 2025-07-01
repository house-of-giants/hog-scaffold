/**
 * Modern Navigation Component
 * Clean, accessible navigation with essential features
 * Maintains WCAG AA compliance while removing unnecessary complexity
 */

class Navigation {
	constructor() {
		// Core elements
		this.header = document.querySelector(".site-header");
		this.mobileToggle = document.querySelector(".mobile-menu-toggle");
		this.primaryNav = document.querySelector(".primary-navigation");
		this.navContainer = this.primaryNav?.querySelector(
			".wp-block-navigation__responsive-container"
		);
		this.navList = this.primaryNav?.querySelector(
			".wp-block-navigation__container"
		);

		// State management
		this.isMenuOpen = false;
		this.isMobile = window.innerWidth < 768;
		this.scrollPosition = 0;
		this.resizeTimeout = null;

		// Configuration
		this.config = {
			mobileBreakpoint: 768,
			animationDuration: 300,
			reducedMotion: window.matchMedia("(prefers-reduced-motion: reduce)")
				.matches,
		};

		// Initialize if required elements exist
		if (this.header && this.mobileToggle && this.primaryNav) {
			this.init();
		}
	}

	/**
	 * Initialize navigation functionality
	 */
	init() {
		this.hideWordPressToggles();
		this.setupMobileMenu();
		this.setupKeyboardNavigation();
		this.setupDropdowns();
		this.setupSmoothScrolling();
		this.setupResponsiveHandling();
		this.setupAccessibility();

		// Initial state
		this.updateAriaAttributes();
	}

	/**
	 * Hide WordPress built-in toggle buttons
	 */
	hideWordPressToggles() {
		const wordPressToggles = document.querySelectorAll(`
			.wp-block-navigation button:not(.mobile-menu-toggle),
			.wp-block-navigation__responsive-container-open,
			.wp-block-navigation__responsive-container-close
		`);

		wordPressToggles.forEach((toggle) => {
			toggle.style.display = "none";
			toggle.setAttribute("aria-hidden", "true");
			toggle.setAttribute("tabindex", "-1");
		});
	}

	/**
	 * Setup mobile menu functionality
	 */
	setupMobileMenu() {
		this.mobileToggle.addEventListener("click", (e) => {
			e.preventDefault();
			this.toggleMobileMenu();
		});

		// Close menu when clicking outside
		document.addEventListener("click", (e) => {
			if (
				this.isMenuOpen &&
				!this.primaryNav.contains(e.target) &&
				!this.mobileToggle.contains(e.target)
			) {
				this.closeMobileMenu();
			}
		});

		// Close menu on escape key
		document.addEventListener("keydown", (e) => {
			if (e.key === "Escape" && this.isMenuOpen) {
				this.closeMobileMenu();
				this.mobileToggle.focus();
			}
		});
	}

	/**
	 * Toggle mobile menu state
	 */
	toggleMobileMenu() {
		if (this.isMenuOpen) {
			this.closeMobileMenu();
		} else {
			this.openMobileMenu();
		}
	}

	/**
	 * Open mobile menu
	 */
	openMobileMenu() {
		this.isMenuOpen = true;
		this.mobileToggle.classList.add("is-active");
		this.primaryNav.classList.add("is-open");
		document.body.classList.add("nav-open");

		// Lock body scroll
		this.scrollPosition = window.pageYOffset;
		document.body.style.overflow = "hidden";
		document.body.style.position = "fixed";
		document.body.style.top = `-${this.scrollPosition}px`;
		document.body.style.width = "100%";

		this.updateAriaAttributes();
		this.trapFocus();
	}

	/**
	 * Close mobile menu
	 */
	closeMobileMenu() {
		this.isMenuOpen = false;
		this.mobileToggle.classList.remove("is-active");
		this.primaryNav.classList.remove("is-open");
		document.body.classList.remove("nav-open");

		// Restore body scroll
		document.body.style.overflow = "";
		document.body.style.position = "";
		document.body.style.top = "";
		document.body.style.width = "";
		window.scrollTo(0, this.scrollPosition);

		this.updateAriaAttributes();
	}

	/**
	 * Setup keyboard navigation
	 */
	setupKeyboardNavigation() {
		this.primaryNav.addEventListener("keydown", (e) => {
			const focusableElements = this.getFocusableElements();
			const currentIndex = Array.from(focusableElements).indexOf(e.target);

			switch (e.key) {
				case "Tab":
					if (this.isMenuOpen && this.isMobile) {
						this.handleTabNavigation(e, focusableElements, currentIndex);
					}
					break;
				case "ArrowDown":
				case "ArrowUp":
					if (this.isInDropdown(e.target)) {
						e.preventDefault();
						this.navigateDropdown(e.key, e.target);
					}
					break;
				case "Enter":
				case " ":
					if (
						e.target.classList.contains("wp-block-navigation-item__content") &&
						e.target.nextElementSibling?.classList.contains(
							"wp-block-navigation__submenu-container"
						)
					) {
						e.preventDefault();
						this.toggleDropdown(e.target.parentElement);
					}
					break;
			}
		});
	}

	/**
	 * Setup dropdown menu functionality
	 */
	setupDropdowns() {
		const dropdownItems = this.navList?.querySelectorAll(
			".wp-block-navigation-item.has-child"
		);

		dropdownItems?.forEach((item) => {
			const link = item.querySelector(".wp-block-navigation-item__content");
			const submenu = item.querySelector(
				".wp-block-navigation__submenu-container"
			);

			if (!link || !submenu) return;

			// Desktop hover (only if not reduced motion)
			if (!this.config.reducedMotion) {
				item.addEventListener("mouseenter", () => {
					if (!this.isMobile) this.openDropdown(item, submenu);
				});

				item.addEventListener("mouseleave", () => {
					if (!this.isMobile) this.closeDropdown(item, submenu);
				});
			}

			// Click/touch for all devices
			link.addEventListener("click", (e) => {
				if (item.querySelector(".wp-block-navigation__submenu-container")) {
					e.preventDefault();
					this.toggleDropdown(item, submenu);
				}
			});
		});
	}

	/**
	 * Setup smooth scrolling for anchor links
	 */
	setupSmoothScrolling() {
		this.navList?.addEventListener("click", (e) => {
			const link = e.target.closest("a");
			if (!link) return;

			const href = link.getAttribute("href");
			if (href?.startsWith("#")) {
				const target = document.querySelector(href);
				if (target) {
					e.preventDefault();
					this.smoothScrollTo(target);

					// Close mobile menu if open
					if (this.isMenuOpen) {
						this.closeMobileMenu();
					}
				}
			}
		});
	}

	/**
	 * Smooth scroll to target element
	 */
	smoothScrollTo(target) {
		const headerHeight = this.header.offsetHeight;
		const targetPosition = target.offsetTop - headerHeight;

		if (this.config.reducedMotion) {
			window.scrollTo(0, targetPosition);
		} else {
			window.scrollTo({
				top: targetPosition,
				behavior: "smooth",
			});
		}
	}

	/**
	 * Setup responsive handling
	 */
	setupResponsiveHandling() {
		const handleResize = () => {
			const wasMobile = this.isMobile;
			this.isMobile = window.innerWidth < this.config.mobileBreakpoint;

			// Close mobile menu if switching to desktop
			if (wasMobile && !this.isMobile && this.isMenuOpen) {
				this.closeMobileMenu();
			}

			// Close all dropdowns when switching between mobile/desktop
			if (wasMobile !== this.isMobile) {
				this.closeAllDropdowns();
			}
		};

		// Throttle resize events
		window.addEventListener("resize", () => {
			clearTimeout(this.resizeTimeout);
			this.resizeTimeout = setTimeout(handleResize, 100);
		});
	}

	/**
	 * Setup accessibility features
	 */
	setupAccessibility() {
		// Create screen reader announcements
		this.announcement = document.createElement("div");
		this.announcement.className = "sr-only";
		this.announcement.setAttribute("aria-live", "polite");
		this.announcement.setAttribute("aria-atomic", "true");
		document.body.appendChild(this.announcement);

		// Enhanced focus management
		this.setupFocusManagement();
	}

	/**
	 * Setup focus management for better accessibility
	 */
	setupFocusManagement() {
		// Visible focus indicators
		document.addEventListener("keydown", (e) => {
			if (e.key === "Tab") {
				document.body.classList.add("keyboard-navigation");
			}
		});

		document.addEventListener("mousedown", () => {
			document.body.classList.remove("keyboard-navigation");
		});
	}

	/**
	 * Update ARIA attributes based on current state
	 */
	updateAriaAttributes() {
		this.mobileToggle.setAttribute("aria-expanded", this.isMenuOpen.toString());
		this.mobileToggle.setAttribute(
			"aria-label",
			this.isMenuOpen ? "Close navigation menu" : "Open navigation menu"
		);

		if (this.navContainer) {
			this.navContainer.setAttribute(
				"aria-hidden",
				(!this.isMenuOpen).toString()
			);
		}
	}

	/**
	 * Get all focusable elements in navigation
	 */
	getFocusableElements() {
		return this.primaryNav.querySelectorAll(
			'a[href], button:not([disabled]), [tabindex]:not([tabindex="-1"])'
		);
	}

	/**
	 * Handle tab navigation in mobile menu
	 */
	handleTabNavigation(e, focusableElements, currentIndex) {
		if (focusableElements.length === 0) return;

		if (e.shiftKey) {
			// Shift + Tab (backwards)
			if (currentIndex <= 0) {
				e.preventDefault();
				focusableElements[focusableElements.length - 1].focus();
			}
		} else {
			// Tab (forwards)
			if (currentIndex >= focusableElements.length - 1) {
				e.preventDefault();
				focusableElements[0].focus();
			}
		}
	}

	/**
	 * Trap focus within mobile menu
	 */
	trapFocus() {
		if (!this.isMenuOpen || !this.isMobile) return;

		// Focus first focusable element
		const focusableElements = this.getFocusableElements();
		if (focusableElements.length > 0) {
			focusableElements[0].focus();
		}
	}

	/**
	 * Check if element is in a dropdown
	 */
	isInDropdown(element) {
		return element.closest(".wp-block-navigation__submenu-container") !== null;
	}

	/**
	 * Navigate within dropdown using arrow keys
	 */
	navigateDropdown(direction, currentElement) {
		const dropdown = currentElement.closest(
			".wp-block-navigation__submenu-container"
		);
		if (!dropdown) return;

		const links = dropdown.querySelectorAll("a");
		const currentIndex = Array.from(links).indexOf(currentElement);
		let nextIndex;

		if (direction === "ArrowDown") {
			nextIndex = currentIndex + 1 >= links.length ? 0 : currentIndex + 1;
		} else {
			nextIndex = currentIndex <= 0 ? links.length - 1 : currentIndex - 1;
		}

		links[nextIndex].focus();
	}

	/**
	 * Open dropdown menu
	 */
	openDropdown(item, submenu) {
		item.classList.add("is-open");
		submenu.setAttribute("aria-hidden", "false");

		const trigger = item.querySelector(".wp-block-navigation-item__content");
		if (trigger) {
			trigger.setAttribute("aria-expanded", "true");
		}
	}

	/**
	 * Close dropdown menu
	 */
	closeDropdown(item, submenu) {
		item.classList.remove("is-open");
		submenu.setAttribute("aria-hidden", "true");

		const trigger = item.querySelector(".wp-block-navigation-item__content");
		if (trigger) {
			trigger.setAttribute("aria-expanded", "false");
		}
	}

	/**
	 * Toggle dropdown menu
	 */
	toggleDropdown(item, submenu) {
		if (!submenu) {
			submenu = item.querySelector(".wp-block-navigation__submenu-container");
		}

		if (item.classList.contains("is-open")) {
			this.closeDropdown(item, submenu);
		} else {
			// Close other dropdowns first
			this.closeAllDropdowns();
			this.openDropdown(item, submenu);
		}
	}

	/**
	 * Close all dropdown menus
	 */
	closeAllDropdowns() {
		const openDropdowns = this.navList?.querySelectorAll(
			".wp-block-navigation-item.is-open"
		);
		openDropdowns?.forEach((item) => {
			const submenu = item.querySelector(
				".wp-block-navigation__submenu-container"
			);
			if (submenu) {
				this.closeDropdown(item, submenu);
			}
		});
	}

	/**
	 * Announce message to screen readers
	 */
	announceToScreenReader(message) {
		if (this.announcement) {
			this.announcement.textContent = message;
		}
	}

	/**
	 * Cleanup method
	 */
	destroy() {
		// Remove event listeners and cleanup
		this.closeMobileMenu();
		this.closeAllDropdowns();

		if (this.announcement?.parentNode) {
			this.announcement.parentNode.removeChild(this.announcement);
		}

		document.body.classList.remove("nav-open", "keyboard-navigation");
	}
}

/**
 * Initialize navigation when DOM is ready
 */
function initNavigation() {
	if (document.readyState === "loading") {
		document.addEventListener("DOMContentLoaded", () => {
			new Navigation();
		});
	} else {
		new Navigation();
	}
}

// Initialize navigation
initNavigation();

// Export for potential use by other scripts
window.Navigation = Navigation;
