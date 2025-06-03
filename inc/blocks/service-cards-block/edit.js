/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import {
	useBlockProps,
	InspectorControls,
	RichText,
} from "@wordpress/block-editor";
import {
	PanelBody,
	SelectControl,
	ToggleControl,
	Button,
	TextControl,
	CheckboxControl,
	RangeControl,
} from "@wordpress/components";

/**
 * External dependencies
 */
import { useState } from "@wordpress/element";

/**
 * Service Cards Edit Component
 *
 * @param {Object}   props               Component props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Function to set attributes.
 */
export default function Edit({ attributes, setAttributes }) {
	const { cards, columns, layout, showIcons, cardStyle } = attributes;
	const [selectedCard, setSelectedCard] = useState(0);

	const blockProps = useBlockProps({
		className: `layout-${layout} style-${cardStyle} ${
			!showIcons ? "no-icons" : ""
		}`,
		style: {
			"--columns": columns,
		},
	});

	const addCard = () => {
		const newCard = {
			id: Date.now(),
			title: __("New Service", "hog-scaffold"),
			description: __("Description of your service", "hog-scaffold"),
			icon: "",
			link: {
				url: "#",
				opensInNewTab: false,
			},
		};
		setAttributes({ cards: [...cards, newCard] });
	};

	const removeCard = (index) => {
		const newCards = cards.filter((_, i) => i !== index);
		setAttributes({ cards: newCards });
		if (selectedCard >= newCards.length) {
			setSelectedCard(Math.max(0, newCards.length - 1));
		}
	};

	const updateCard = (index, field, value) => {
		const newCards = cards.map((card, i) => {
			if (i === index) {
				if (field === "link") {
					return { ...card, link: { ...card.link, ...value } };
				}
				return { ...card, [field]: value };
			}
			return card;
		});
		setAttributes({ cards: newCards });
	};

	return (
		<>
			<InspectorControls>
				<PanelBody
					title={__("Layout Settings", "hog-scaffold")}
					initialOpen={true}
				>
					<SelectControl
						label={__("Layout", "hog-scaffold")}
						value={layout}
						options={[
							{ label: __("Grid", "hog-scaffold"), value: "grid" },
							{ label: __("List", "hog-scaffold"), value: "list" },
							{ label: __("Carousel", "hog-scaffold"), value: "carousel" },
						]}
						onChange={(value) => setAttributes({ layout: value })}
					/>

					{layout === "grid" && (
						<RangeControl
							label={__("Columns", "hog-scaffold")}
							value={columns}
							onChange={(value) => setAttributes({ columns: value })}
							min={1}
							max={4}
						/>
					)}

					<SelectControl
						label={__("Card Style", "hog-scaffold")}
						value={cardStyle}
						options={[
							{ label: __("Default", "hog-scaffold"), value: "default" },
							{ label: __("Bordered", "hog-scaffold"), value: "bordered" },
							{ label: __("Shadow", "hog-scaffold"), value: "shadow" },
							{ label: __("Minimal", "hog-scaffold"), value: "minimal" },
						]}
						onChange={(value) => setAttributes({ cardStyle: value })}
					/>

					<ToggleControl
						label={__("Show Icons", "hog-scaffold")}
						checked={showIcons}
						onChange={(value) => setAttributes({ showIcons: value })}
					/>
				</PanelBody>

				<PanelBody
					title={__("Cards Management", "hog-scaffold")}
					initialOpen={false}
				>
					<Button isPrimary onClick={addCard}>
						{__("Add New Card", "hog-scaffold")}
					</Button>

					{cards.map((card, index) => (
						<div
							key={card.id}
							style={{
								marginTop: "20px",
								padding: "15px",
								border: "1px solid #ddd",
							}}
						>
							<h4>
								{__("Card", "hog-scaffold")} {index + 1}
							</h4>

							{cards.length > 1 && (
								<Button
									isDestructive
									onClick={() => removeCard(index)}
									style={{ marginBottom: "10px" }}
								>
									{__("Remove", "hog-scaffold")}
								</Button>
							)}

							<TextControl
								label={__("Title", "hog-scaffold")}
								value={card.title}
								onChange={(value) => updateCard(index, "title", value)}
							/>

							<TextControl
								label={__("Description", "hog-scaffold")}
								value={card.description}
								onChange={(value) => updateCard(index, "description", value)}
							/>

							{showIcons && (
								<TextControl
									label={__("Icon (emoji or text)", "hog-scaffold")}
									value={card.icon}
									onChange={(value) => updateCard(index, "icon", value)}
									help={__("Use emoji or short text for icons", "hog-scaffold")}
								/>
							)}

							<TextControl
								label={__("Link URL", "hog-scaffold")}
								value={card.link.url}
								onChange={(value) => updateCard(index, "link", { url: value })}
							/>

							<CheckboxControl
								label={__("Open in new tab", "hog-scaffold")}
								checked={card.link.opensInNewTab}
								onChange={(value) =>
									updateCard(index, "link", { opensInNewTab: value })
								}
							/>
						</div>
					))}
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="wp-block-service-cards__grid">
					{cards.map((card, index) => (
						<div key={card.id} className="wp-block-service-cards__card">
							{showIcons && card.icon && (
								<div className="wp-block-service-cards__icon">{card.icon}</div>
							)}
							<RichText
								tagName="h3"
								className="wp-block-service-cards__title"
								placeholder={__("Enter card title...", "hog-scaffold")}
								value={card.title}
								onChange={(value) => updateCard(index, "title", value)}
							/>
							<RichText
								tagName="p"
								className="wp-block-service-cards__description"
								placeholder={__("Enter card description...", "hog-scaffold")}
								value={card.description}
								onChange={(value) => updateCard(index, "description", value)}
							/>
							{card.link.url && (
								<a
									href={card.link.url}
									className="wp-block-service-cards__link"
									target={card.link.opensInNewTab ? "_blank" : "_self"}
									rel={card.link.opensInNewTab ? "noopener noreferrer" : ""}
								>
									{__("Learn More", "hog-scaffold")}
								</a>
							)}
						</div>
					))}
				</div>
			</div>
		</>
	);
}
