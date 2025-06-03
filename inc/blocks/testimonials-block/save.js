/**
 * Save component for Testimonials Block
 *
 * Renders static HTML for optimal SEO performance.
 * Content is saved directly to the database for faster loading.
 *
 * @return {JSX.Element} The saved block HTML.
 */
import { useBlockProps } from "@wordpress/block-editor";

const TestimonialsSave = ({ attributes }) => {
	const {
		testimonials = [],
		columns = 2,
		layout = "grid",
		showImages = true,
		testimonialStyle = "default",
		heading = "",
		subheading = "",
	} = attributes;

	const blockProps = useBlockProps.save({
		className: `layout-${layout} style-${testimonialStyle} columns-${columns} ${!showImages ? "no-images" : ""}`,
		style: {
			"--columns": columns,
			"--testimonials-gap-base": "1.5rem",
		},
	});

	return (
		<div {...blockProps}>
			{(heading || subheading) && (
				<div className="wp-block-testimonials__header">
					{heading && (
						<h2 className="wp-block-testimonials__heading">{heading}</h2>
					)}
					{subheading && (
						<p className="wp-block-testimonials__subheading">{subheading}</p>
					)}
				</div>
			)}
			<div className="wp-block-testimonials__grid">
				{testimonials.map((testimonial) => (
					<div
						key={testimonial.id}
						className="wp-block-testimonials__testimonial"
					>
						<blockquote className="wp-block-testimonials__quote">
							{testimonial.quote && (
								<p className="wp-block-testimonials__text">
									{testimonial.quote}
								</p>
							)}
							{(testimonial.name ||
								testimonial.role ||
								testimonial.company) && (
								<footer className="wp-block-testimonials__footer">
									{showImages && testimonial.image && testimonial.image.url && (
										<div className="wp-block-testimonials__image">
											<img
												src={testimonial.image.url}
												alt={testimonial.image.alt || testimonial.name || ""}
												loading="lazy"
											/>
										</div>
									)}
									<div className="wp-block-testimonials__cite">
										{testimonial.name && (
											<cite className="wp-block-testimonials__name">
												{testimonial.name}
											</cite>
										)}
										{testimonial.role && (
											<span className="wp-block-testimonials__role">
												{testimonial.role}
											</span>
										)}
										{testimonial.company && (
											<span className="wp-block-testimonials__company">
												{testimonial.company}
											</span>
										)}
									</div>
								</footer>
							)}
						</blockquote>
					</div>
				))}
			</div>
		</div>
	);
};

export default TestimonialsSave;
