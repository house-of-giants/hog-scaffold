/**
 * Save component for CTA Block
 *
 * Renders static HTML for optimal SEO performance.
 * Content is saved directly to the database for faster loading.
 *
 * @return {JSX.Element} The saved block HTML.
 */
import { useBlockProps } from "@wordpress/block-editor";

const CtaSave = ({ attributes }) => {
	const {
		heading = "",
		text = "",
		buttonText = "",
		buttonUrl = "",
		buttonOpenInNewTab = false,
		buttonStyle = "primary",
		buttonSize = "medium",
		textAlignment = "left",
		backgroundColor = "",
		backgroundImage = null,
		backgroundPosition = "center center",
		backgroundSize = "cover",
		backgroundOverlay = false,
		backgroundOverlayColor = "rgba(0,0,0,0.5)",
	} = attributes;

	// Build styles for the CTA container
	const containerStyle = {
		backgroundColor: backgroundColor || undefined,
		backgroundImage: backgroundImage?.url
			? `url(${backgroundImage.url})`
			: undefined,
		backgroundPosition: backgroundImage?.url ? backgroundPosition : undefined,
		backgroundSize: backgroundImage?.url ? backgroundSize : undefined,
		backgroundRepeat: backgroundImage?.url ? "no-repeat" : undefined,
		textAlign: textAlignment,
		position: backgroundOverlay ? "relative" : undefined,
	};

	// Build overlay styles
	const overlayStyle = backgroundOverlay
		? {
				position: "absolute",
				top: "0",
				left: "0",
				right: "0",
				bottom: "0",
				backgroundColor: backgroundOverlayColor,
				zIndex: "1",
			}
		: {};

	const blockProps = useBlockProps.save({
		className: `text-align-${textAlignment} ${backgroundImage?.url ? "has-background-image" : ""} ${backgroundColor ? "has-background-color" : ""} ${backgroundOverlay ? "has-overlay" : ""}`,
		style: containerStyle,
	});

	return (
		<div {...blockProps}>
			{backgroundOverlay && (
				<div
					className="wp-block-cta__overlay"
					style={overlayStyle}
					aria-hidden="true"
				/>
			)}
			<div
				className="wp-block-cta__content"
				style={{
					position: backgroundOverlay ? "relative" : undefined,
					zIndex: backgroundOverlay ? "2" : undefined,
				}}
			>
				<div className="wp-block-cta__text-content">
					{heading && <h2 className="wp-block-cta__heading">{heading}</h2>}
					{text && <p className="wp-block-cta__text">{text}</p>}
				</div>
				{buttonText && (
					<div className="wp-block-cta__button-container">
						{buttonUrl ? (
							<a
								href={buttonUrl}
								className={`wp-block-cta__button is-style-${buttonStyle} is-size-${buttonSize}`}
								target={buttonOpenInNewTab ? "_blank" : "_self"}
								rel={buttonOpenInNewTab ? "noopener noreferrer" : ""}
								aria-label={
									buttonText + (buttonOpenInNewTab ? " (opens in new tab)" : "")
								}
							>
								{buttonText}
							</a>
						) : (
							<span
								className={`wp-block-cta__button is-style-${buttonStyle} is-size-${buttonSize}`}
							>
								{buttonText}
							</span>
						)}
					</div>
				)}
			</div>
		</div>
	);
};

export default CtaSave;
