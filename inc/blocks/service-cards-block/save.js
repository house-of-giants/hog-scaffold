/**
 * Save component for Service Cards Block
 *
 * Renders static HTML for optimal SEO performance.
 * Content is saved directly to the database for faster loading.
 *
 * @return {JSX.Element} The saved block HTML.
 */
import { useBlockProps } from "@wordpress/block-editor";

const ServiceCardsSave = ({ attributes }) => {
	const {
		cards = [],
		columns = 3,
		layout = "grid",
		showIcons = true,
		cardStyle = "default",
	} = attributes;

	const blockProps = useBlockProps.save({
		className: `layout-${layout} style-${cardStyle} ${!showIcons ? "no-icons" : ""}`,
		style: {
			"--columns": columns,
		},
	});

	return (
		<div {...blockProps}>
			<div className="wp-block-service-cards__grid">
				{cards.map((card) => (
					<div key={card.id} className="wp-block-service-cards__card">
						{showIcons && card.icon && (
							<div className="wp-block-service-cards__icon">{card.icon}</div>
						)}
						<h3 className="wp-block-service-cards__title">{card.title}</h3>
						<p className="wp-block-service-cards__description">
							{card.description}
						</p>
						{card.link && card.link.url && (
							<a
								href={card.link.url}
								className="wp-block-service-cards__link"
								target={card.link.opensInNewTab ? "_blank" : "_self"}
								rel={card.link.opensInNewTab ? "noopener noreferrer" : ""}
							>
								Learn More
							</a>
						)}
					</div>
				))}
			</div>
		</div>
	);
};

export default ServiceCardsSave;
