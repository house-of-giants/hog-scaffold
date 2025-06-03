/**
 * WordPress dependencies
 */
import { __ } from "@wordpress/i18n";
import {
	useBlockProps,
	RichText,
	InspectorControls,
} from "@wordpress/block-editor";
import {
	PanelBody,
	SelectControl,
	RangeControl,
	ToggleControl,
	Button,
	TextControl,
	CheckboxControl,
	Card,
	CardBody,
	CardHeader,
} from "@wordpress/components";

/**
 * Edit component for Service Cards Block
 *
 * @param {Object}   props               The block props.
 * @param {Object}   props.attributes    Block attributes.
 * @param {Function} props.setAttributes Sets the value for block attributes.
 * @return {Function} Render the edit screen
 */
const ServiceCardsEdit = ({ attributes, setAttributes }) => {
	const { cards, columns, layout, showIcons, cardStyle } = attributes;

	const blockProps = useBlockProps({
		className: `wp-block-service-cards is-layout-${layout} is-style-${cardStyle} columns-${columns}`,
	});

	const updateCard = (index, key, value) => {
		const newCards = [...cards];
		newCards[index] = {
			...newCards[index],
			[key]: value,
		};
		setAttributes({ cards: newCards });
	};

	const updateCardLink = (index, key, value) => {
		const newCards = [...cards];
		newCards[index] = {
			...newCards[index],
			link: {
				...newCards[index].link,
				[key]: value,
			},
		};
		setAttributes({ cards: newCards });
	};

	const addCard = () => {
		const newCard = {
			id: Date.now(),
			title: "New Service",
			description: "Description of your new service",
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
	};

	const moveCard = (index, direction) => {
		const newCards = [...cards];
		const newIndex = direction === "up" ? index - 1 : index + 1;

		if (newIndex >= 0 && newIndex < cards.length) {
			[newCards[index], newCards[newIndex]] = [
				newCards[newIndex],
				newCards[index],
			];
			setAttributes({ cards: newCards });
		}
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={__("Layout Settings", "hog")} initialOpen={true}>
					<SelectControl
						label={__("Layout", "hog")}
						value={layout}
						options={[
							{ label: __("Grid", "hog"), value: "grid" },
							{ label: __("List", "hog"), value: "list" },
							{ label: __("Carousel", "hog"), value: "carousel" },
						]}
						onChange={(value) => setAttributes({ layout: value })}
					/>

					{layout === "grid" && (
						<RangeControl
							label={__("Columns", "hog")}
							value={columns}
							onChange={(value) => setAttributes({ columns: value })}
							min={1}
							max={6}
						/>
					)}

					<SelectControl
						label={__("Card Style", "hog")}
						value={cardStyle}
						options={[
							{ label: __("Default", "hog"), value: "default" },
							{ label: __("Bordered", "hog"), value: "bordered" },
							{ label: __("Shadow", "hog"), value: "shadow" },
							{ label: __("Minimal", "hog"), value: "minimal" },
						]}
						onChange={(value) => setAttributes({ cardStyle: value })}
					/>

					<ToggleControl
						label={__("Show Icons", "hog")}
						checked={showIcons}
						onChange={(value) => setAttributes({ showIcons: value })}
					/>
				</PanelBody>

				<PanelBody title={__("Cards Management", "hog")} initialOpen={false}>
					<Button isPrimary onClick={addCard} style={{ marginBottom: "16px" }}>
						{__("Add New Card", "hog")}
					</Button>

					{cards.map((card, index) => (
						<Card key={card.id} style={{ marginBottom: "16px" }}>
							<CardHeader>
								<strong>
									{__("Card", "hog")} {index + 1}
								</strong>
								<div style={{ display: "flex", gap: "8px" }}>
									{index > 0 && (
										<Button isSmall onClick={() => moveCard(index, "up")}>
											↑
										</Button>
									)}
									{index < cards.length - 1 && (
										<Button isSmall onClick={() => moveCard(index, "down")}>
											↓
										</Button>
									)}
									<Button
										isSmall
										isDestructive
										onClick={() => removeCard(index)}
									>
										{__("Remove", "hog")}
									</Button>
								</div>
							</CardHeader>
							<CardBody>
								<TextControl
									label={__("Title", "hog")}
									value={card.title}
									onChange={(value) => updateCard(index, "title", value)}
								/>
								<TextControl
									label={__("Description", "hog")}
									value={card.description}
									onChange={(value) => updateCard(index, "description", value)}
								/>
								{showIcons && (
									<TextControl
										label={__("Icon (emoji or text)", "hog")}
										value={card.icon}
										onChange={(value) => updateCard(index, "icon", value)}
										help={__("Use emoji or short text for icons", "hog")}
									/>
								)}
								<TextControl
									label={__("Link URL", "hog")}
									value={card.link.url}
									onChange={(value) => updateCardLink(index, "url", value)}
								/>
								<CheckboxControl
									label={__("Open in new tab", "hog")}
									checked={card.link.opensInNewTab}
									onChange={(value) =>
										updateCardLink(index, "opensInNewTab", value)
									}
								/>
							</CardBody>
						</Card>
					))}
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="wp-block-service-cards__container">
					{cards.map((card, index) => (
						<div key={card.id} className="wp-block-service-cards__card">
							{showIcons && card.icon && (
								<div className="wp-block-service-cards__icon">{card.icon}</div>
							)}
							<div className="wp-block-service-cards__content">
								<RichText
									tagName="h3"
									className="wp-block-service-cards__title"
									placeholder={__("Enter card title...", "hog")}
									value={card.title}
									onChange={(value) => updateCard(index, "title", value)}
								/>
								<RichText
									tagName="p"
									className="wp-block-service-cards__description"
									placeholder={__("Enter card description...", "hog")}
									value={card.description}
									onChange={(value) => updateCard(index, "description", value)}
								/>
								{card.link.url && card.link.url !== "#" && (
									<div className="wp-block-service-cards__link">
										<a
											href={card.link.url}
											target={card.link.opensInNewTab ? "_blank" : "_self"}
											rel={card.link.opensInNewTab ? "noopener noreferrer" : ""}
										>
											{__("Learn More", "hog")}
										</a>
									</div>
								)}
							</div>
						</div>
					))}
				</div>
			</div>
		</>
	);
};

export default ServiceCardsEdit;
