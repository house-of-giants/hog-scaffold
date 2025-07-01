/**
 * Header Navigation JavaScript
 * Handles mobile menu toggle functionality
 * Uses modern JavaScript without jQuery
 */

class HeaderNavigation {
	constructor() {
		this.init();
	}

	init() {
		this.setupMobileMenu();
		this.setupAccessibility();
	}

	setupMobileMenu() {
		const mobileToggle = document.getElementById("mobile-menu-toggle");
		const navigation = document.querySelector(".primary-navigation");
		const { body } = document;

		if (!mobileToggle || !navigation) return;

		mobileToggle.addEventListener("click", (e) => {
			e.preventDefault();
			this.toggleMobileMenu(mobileToggle, navigation, body);
		});

		// Close menu when clicking outside
		document.addEventListener("click", (e) => {
			if (!navigation.contains(e.target) && !mobileToggle.contains(e.target)) {
				this.closeMobileMenu(mobileToggle, navigation, body);
			}
		});

		// Close menu on escape key
		document.addEventListener("keydown", (e) => {
			if (e.key === "Escape" && navigation.classList.contains("is-open")) {
				this.closeMobileMenu(mobileToggle, navigation, body);
				mobileToggle.focus();
			}
		});
	}

	toggleMobileMenu(toggle, nav, body) {
		const isOpen = nav.classList.contains("is-open");

		if (isOpen) {
			this.closeMobileMenu(toggle, nav, body);
		} else {
			this.openMobileMenu(toggle, nav, body);
		}
	}

	openMobileMenu(toggle, nav, body) {
		nav.classList.add("is-open");
		toggle.classList.add("is-active");
		body.classList.add("nav-open");
		toggle.setAttribute("aria-expanded", "true");

		// Focus first menu item
		const firstMenuItem = nav.querySelector(
			".wp-block-navigation-item__content"
		);
		if (firstMenuItem) {
			firstMenuItem.focus();
		}
	}

	closeMobileMenu(toggle, nav, body) {
		nav.classList.remove("is-open");
		toggle.classList.remove("is-active");
		body.classList.remove("nav-open");
		toggle.setAttribute("aria-expanded", "false");
	}

	setupAccessibility() {
		// Setup keyboard navigation for menu items
		const menuItems = document.querySelectorAll(
			".primary-navigation .wp-block-navigation-item__content"
		);

		menuItems.forEach((item, index) => {
			item.addEventListener("keydown", (e) => {
				switch (e.key) {
					case "ArrowDown":
						e.preventDefault();
						this.focusNext(menuItems, index);
						break;
					case "ArrowUp":
						e.preventDefault();
						this.focusPrevious(menuItems, index);
						break;
					case "Home":
						e.preventDefault();
						menuItems[0].focus();
						break;
					case "End":
						e.preventDefault();
						menuItems[menuItems.length - 1].focus();
						break;
				}
			});
		});
	}

	focusNext(items, currentIndex) {
		const nextIndex = (currentIndex + 1) % items.length;
		items[nextIndex].focus();
	}

	focusPrevious(items, currentIndex) {
		const previousIndex = (currentIndex - 1 + items.length) % items.length;
		items[previousIndex].focus();
	}
}

// Initialize when DOM is ready
document.addEventListener("DOMContentLoaded", () => {
	new HeaderNavigation();
});

// Handle resize events to close mobile menu if viewport becomes wide
window.addEventListener("resize", () => {
	if (window.innerWidth >= 768) {
		const navigation = document.querySelector(".primary-navigation");
		const toggle = document.getElementById("mobile-menu-toggle");
		const { body } = document;

		if (navigation && navigation.classList.contains("is-open")) {
			navigation.classList.remove("is-open");
			toggle.classList.remove("is-active");
			body.classList.remove("nav-open");
			toggle.setAttribute("aria-expanded", "false");
		}
	}
});
