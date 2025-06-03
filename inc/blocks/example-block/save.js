/**
 * Save component for Example Block
 *
 * Renders static HTML for optimal SEO performance.
 * Content is saved directly to the database for faster loading.
 *
 * @return {JSX.Element} The saved block HTML.
 */
import { useBlockProps } from "@wordpress/block-editor";

const ExampleBlockSave = ({ attributes }) => {
	const { customTitle = "" } = attributes;

	const blockProps = useBlockProps.save({
		className: "wp-block-example-block",
	});

	return (
		<div {...blockProps}>
			{customTitle && (
				<h2 className="wp-block-example-block__title">{customTitle}</h2>
			)}
		</div>
	);
};

export default ExampleBlockSave;
