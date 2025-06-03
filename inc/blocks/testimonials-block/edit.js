import { __ } from "@wordpress/i18n";
import {
	useBlockProps,
	InspectorControls,
	MediaUpload,
	MediaUploadCheck,
	RichText,
} from "@wordpress/block-editor";
import {
	PanelBody,
	SelectControl,
	ToggleControl,
	RangeControl,
	Button,
	ButtonGroup,
	Card,
	CardBody,
	CardHeader,
	Flex,
	FlexItem,
	__experimentalSpacer as Spacer,
} from "@wordpress/components";
import {
	plus,
	trash,
	arrowUp,
	arrowDown,
	starEmpty,
	starFilled,
} from "@wordpress/icons";

export default function Edit({ attributes, setAttributes }) {
	const {
		testimonials,
		layout,
		columns,
		showRating,
		showImage,
		showPosition,
		showCompany,
		quoteStyle,
		autoRotate,
		rotationSpeed,
	} = attributes;

	const blockProps = useBlockProps({
		className: `is-layout-${layout} columns-${columns} quote-style-${quoteStyle}`,
	});

	const addTestimonial = () => {
		const newTestimonial = {
			id: Date.now(),
			quote: __("Enter your testimonial quote here...", "hog-scaffold"),
			author: __("Customer Name", "hog-scaffold"),
			position: __("Position", "hog-scaffold"),
			company: __("Company Name", "hog-scaffold"),
			image: {
				url: "",
				alt: "",
				id: 0,
			},
			rating: 5,
		};
		setAttributes({ testimonials: [...testimonials, newTestimonial] });
	};

	const updateTestimonial = (index, field, value) => {
		const updatedTestimonials = [...testimonials];
		updatedTestimonials[index] = {
			...updatedTestimonials[index],
			[field]: value,
		};
		setAttributes({ testimonials: updatedTestimonials });
	};

	const removeTestimonial = (index) => {
		const updatedTestimonials = testimonials.filter((_, i) => i !== index);
		setAttributes({ testimonials: updatedTestimonials });
	};

	const moveTestimonial = (index, direction) => {
		const updatedTestimonials = [...testimonials];
		const newIndex = direction === "up" ? index - 1 : index + 1;

		if (newIndex >= 0 && newIndex < testimonials.length) {
			[updatedTestimonials[index], updatedTestimonials[newIndex]] = [
				updatedTestimonials[newIndex],
				updatedTestimonials[index],
			];
			setAttributes({ testimonials: updatedTestimonials });
		}
	};

	const renderStars = (rating, testimonialIndex) => {
		const stars = [];
		for (let i = 1; i <= 5; i++) {
			stars.push(
				<Button
					key={i}
					icon={i <= rating ? starFilled : starEmpty}
					onClick={() => updateTestimonial(testimonialIndex, "rating", i)}
					className={`wp-block-testimonials__star ${i <= rating ? "is-filled" : "is-empty"}`}
					label={__(`${i} star${i !== 1 ? "s" : ""}`, "hog-scaffold")}
				/>
			);
		}
		return stars;
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
							{ label: __("Carousel", "hog-scaffold"), value: "carousel" },
							{ label: __("Single", "hog-scaffold"), value: "single" },
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
						label={__("Quote Style", "hog-scaffold")}
						value={quoteStyle}
						options={[
							{ label: __("Standard", "hog-scaffold"), value: "standard" },
							{
								label: __("Large Quote", "hog-scaffold"),
								value: "large-quote",
							},
							{ label: __("Minimal", "hog-scaffold"), value: "minimal" },
							{ label: __("Card", "hog-scaffold"), value: "card" },
						]}
						onChange={(value) => setAttributes({ quoteStyle: value })}
					/>
				</PanelBody>

				<PanelBody
					title={__("Display Options", "hog-scaffold")}
					initialOpen={false}
				>
					<ToggleControl
						label={__("Show Ratings", "hog-scaffold")}
						checked={showRating}
						onChange={(value) => setAttributes({ showRating: value })}
					/>

					<ToggleControl
						label={__("Show Images", "hog-scaffold")}
						checked={showImage}
						onChange={(value) => setAttributes({ showImage: value })}
					/>

					<ToggleControl
						label={__("Show Position", "hog-scaffold")}
						checked={showPosition}
						onChange={(value) => setAttributes({ showPosition: value })}
					/>

					<ToggleControl
						label={__("Show Company", "hog-scaffold")}
						checked={showCompany}
						onChange={(value) => setAttributes({ showCompany: value })}
					/>
				</PanelBody>

				{layout === "carousel" && (
					<PanelBody
						title={__("Carousel Settings", "hog-scaffold")}
						initialOpen={false}
					>
						<ToggleControl
							label={__("Auto Rotate", "hog-scaffold")}
							checked={autoRotate}
							onChange={(value) => setAttributes({ autoRotate: value })}
						/>

						{autoRotate && (
							<RangeControl
								label={__("Rotation Speed (seconds)", "hog-scaffold")}
								value={rotationSpeed / 1000}
								onChange={(value) =>
									setAttributes({ rotationSpeed: value * 1000 })
								}
								min={2}
								max={10}
								step={0.5}
							/>
						)}
					</PanelBody>
				)}

				<PanelBody
					title={__("Testimonials Management", "hog-scaffold")}
					initialOpen={false}
				>
					<Button
						variant="primary"
						icon={plus}
						onClick={addTestimonial}
						className="wp-block-testimonials__add-testimonial"
					>
						{__("Add Testimonial", "hog-scaffold")}
					</Button>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="wp-block-testimonials__container">
					{testimonials.map((testimonial, index) => (
						<div
							key={testimonial.id}
							className="wp-block-testimonials__testimonial"
						>
							<Card>
								<CardHeader>
									<Flex>
										<FlexItem>
											<strong>
												{testimonial.author ||
													__("Unnamed Testimonial", "hog-scaffold")}
											</strong>
										</FlexItem>
										<FlexItem>
											<ButtonGroup>
												<Button
													icon={arrowUp}
													disabled={index === 0}
													onClick={() => moveTestimonial(index, "up")}
													label={__("Move up", "hog-scaffold")}
													size="small"
												/>
												<Button
													icon={arrowDown}
													disabled={index === testimonials.length - 1}
													onClick={() => moveTestimonial(index, "down")}
													label={__("Move down", "hog-scaffold")}
													size="small"
												/>
												<Button
													icon={trash}
													onClick={() => removeTestimonial(index)}
													label={__("Remove testimonial", "hog-scaffold")}
													isDestructive
													size="small"
												/>
											</ButtonGroup>
										</FlexItem>
									</Flex>
								</CardHeader>
								<CardBody>
									<div className="wp-block-testimonials__testimonial-preview">
										{showImage && (
											<div className="wp-block-testimonials__image">
												<MediaUploadCheck>
													<MediaUpload
														onSelect={(media) =>
															updateTestimonial(index, "image", {
																url: media.url,
																alt: media.alt,
																id: media.id,
															})
														}
														allowedTypes={["image"]}
														value={testimonial.image.id}
														render={({ open }) => (
															<Button
																onClick={open}
																className={`wp-block-testimonials__image-button ${testimonial.image.url ? "has-image" : "no-image"}`}
															>
																{testimonial.image.url ? (
																	<img
																		src={testimonial.image.url}
																		alt={testimonial.image.alt}
																		className="wp-block-testimonials__image-preview"
																	/>
																) : (
																	<div className="wp-block-testimonials__image-placeholder">
																		{__("Add Image", "hog-scaffold")}
																	</div>
																)}
															</Button>
														)}
													/>
												</MediaUploadCheck>
											</div>
										)}

										<div className="wp-block-testimonials__content">
											<RichText
												tagName="blockquote"
												className="wp-block-testimonials__quote"
												value={testimonial.quote}
												onChange={(value) =>
													updateTestimonial(index, "quote", value)
												}
												placeholder={__(
													"Enter testimonial quote...",
													"hog-scaffold"
												)}
											/>

											<div className="wp-block-testimonials__attribution">
												<RichText
													tagName="cite"
													className="wp-block-testimonials__author"
													value={testimonial.author}
													onChange={(value) =>
														updateTestimonial(index, "author", value)
													}
													placeholder={__("Author name", "hog-scaffold")}
												/>

												{showPosition && (
													<RichText
														tagName="span"
														className="wp-block-testimonials__position"
														value={testimonial.position}
														onChange={(value) =>
															updateTestimonial(index, "position", value)
														}
														placeholder={__("Position/Title", "hog-scaffold")}
													/>
												)}

												{showCompany && (
													<RichText
														tagName="span"
														className="wp-block-testimonials__company"
														value={testimonial.company}
														onChange={(value) =>
															updateTestimonial(index, "company", value)
														}
														placeholder={__("Company name", "hog-scaffold")}
													/>
												)}
											</div>

											{showRating && (
												<div className="wp-block-testimonials__rating">
													<label>{__("Rating:", "hog-scaffold")}</label>
													<div className="wp-block-testimonials__stars">
														{renderStars(testimonial.rating, index)}
													</div>
												</div>
											)}
										</div>
									</div>
								</CardBody>
							</Card>
							<Spacer marginBottom={4} />
						</div>
					))}

					{testimonials.length === 0 && (
						<div className="wp-block-testimonials__empty">
							<p>{__("No testimonials added yet.", "hog-scaffold")}</p>
							<Button variant="primary" onClick={addTestimonial}>
								{__("Add Your First Testimonial", "hog-scaffold")}
							</Button>
						</div>
					)}
				</div>
			</div>
		</>
	);
}
