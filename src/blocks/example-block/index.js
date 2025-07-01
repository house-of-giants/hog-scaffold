import { registerBlockType } from "@wordpress/blocks";
import {
	useBlockProps,
	InspectorControls,
	RichText,
} from "@wordpress/block-editor";
import { PanelBody, ToggleControl } from "@wordpress/components";
import { __ } from "@wordpress/i18n";
import "./style.css";
import "./editor.css";

registerBlockType("hog-scaffold/example", {
	edit: ({ attributes, setAttributes }) => {
		const { customTitle, customContent, showAdvanced } = attributes;
		const blockProps = useBlockProps();

		return (
			<>
				<InspectorControls>
					<PanelBody title={__("Settings", "hog-scaffold")}>
						<ToggleControl
							label={__("Show Advanced Features", "hog-scaffold")}
							checked={showAdvanced}
							onChange={(value) => setAttributes({ showAdvanced: value })}
						/>
					</PanelBody>
				</InspectorControls>

				<div {...blockProps}>
					<RichText
						tagName="h3"
						value={customTitle}
						onChange={(value) => setAttributes({ customTitle: value })}
						placeholder={__("Enter title...", "hog-scaffold")}
					/>
					<RichText
						tagName="p"
						value={customContent}
						onChange={(value) => setAttributes({ customContent: value })}
						placeholder={__("Enter content...", "hog-scaffold")}
					/>
					{showAdvanced && (
						<p className="advanced-content">
							{__("Advanced features are enabled!", "hog-scaffold")}
						</p>
					)}
				</div>
			</>
		);
	},

	save: ({ attributes }) => {
		const { customTitle, customContent, showAdvanced } = attributes;
		const blockProps = useBlockProps.save();

		return (
			<div {...blockProps}>
				<h3>{customTitle}</h3>
				<p>{customContent}</p>
				{showAdvanced && (
					<p className="advanced-content">
						{__("Advanced features are enabled!", "hog-scaffold")}
					</p>
				)}
			</div>
		);
	},
});
