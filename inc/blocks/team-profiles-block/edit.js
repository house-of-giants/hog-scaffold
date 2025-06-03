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
	TextControl,
	Card,
	CardBody,
	CardHeader,
	Flex,
	FlexItem,
	__experimentalSpacer as Spacer,
} from "@wordpress/components";
import { plus, trash, arrowUp, arrowDown } from "@wordpress/icons";

export default function Edit({ attributes, setAttributes }) {
	const {
		members,
		columns,
		layout,
		showBio,
		showSocialLinks,
		imageShape,
		textAlignment,
	} = attributes;

	const blockProps = useBlockProps({
		className: `is-layout-${layout} columns-${columns} text-align-${textAlignment} image-shape-${imageShape}`,
	});

	const addMember = () => {
		const newMember = {
			id: Date.now(),
			name: __("New Team Member", "hog-scaffold"),
			position: __("Position", "hog-scaffold"),
			bio: __("Brief bio about this team member...", "hog-scaffold"),
			image: {
				url: "",
				alt: "",
				id: 0,
			},
			socialLinks: [
				{
					platform: "linkedin",
					url: "",
					label: __("LinkedIn Profile", "hog-scaffold"),
				},
				{
					platform: "twitter",
					url: "",
					label: __("Twitter Profile", "hog-scaffold"),
				},
			],
		};
		setAttributes({ members: [...members, newMember] });
	};

	const updateMember = (index, field, value) => {
		const updatedMembers = [...members];
		updatedMembers[index] = { ...updatedMembers[index], [field]: value };
		setAttributes({ members: updatedMembers });
	};

	const removeMember = (index) => {
		const updatedMembers = members.filter((_, i) => i !== index);
		setAttributes({ members: updatedMembers });
	};

	const moveMember = (index, direction) => {
		const updatedMembers = [...members];
		const newIndex = direction === "up" ? index - 1 : index + 1;

		if (newIndex >= 0 && newIndex < members.length) {
			[updatedMembers[index], updatedMembers[newIndex]] = [
				updatedMembers[newIndex],
				updatedMembers[index],
			];
			setAttributes({ members: updatedMembers });
		}
	};

	const updateSocialLink = (memberIndex, linkIndex, field, value) => {
		const updatedMembers = [...members];
		updatedMembers[memberIndex].socialLinks[linkIndex] = {
			...updatedMembers[memberIndex].socialLinks[linkIndex],
			[field]: value,
		};
		setAttributes({ members: updatedMembers });
	};

	const addSocialLink = (memberIndex) => {
		const updatedMembers = [...members];
		updatedMembers[memberIndex].socialLinks.push({
			platform: "website",
			url: "",
			label: __("Website", "hog-scaffold"),
		});
		setAttributes({ members: updatedMembers });
	};

	const removeSocialLink = (memberIndex, linkIndex) => {
		const updatedMembers = [...members];
		updatedMembers[memberIndex].socialLinks = updatedMembers[
			memberIndex
		].socialLinks.filter((_, i) => i !== linkIndex);
		setAttributes({ members: updatedMembers });
	};

	const socialPlatformOptions = [
		{ label: __("LinkedIn", "hog-scaffold"), value: "linkedin" },
		{ label: __("Twitter", "hog-scaffold"), value: "twitter" },
		{ label: __("Facebook", "hog-scaffold"), value: "facebook" },
		{ label: __("Instagram", "hog-scaffold"), value: "instagram" },
		{ label: __("GitHub", "hog-scaffold"), value: "github" },
		{ label: __("Website", "hog-scaffold"), value: "website" },
		{ label: __("Email", "hog-scaffold"), value: "email" },
	];

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
							max={6}
						/>
					)}

					<SelectControl
						label={__("Text Alignment", "hog-scaffold")}
						value={textAlignment}
						options={[
							{ label: __("Left", "hog-scaffold"), value: "left" },
							{ label: __("Center", "hog-scaffold"), value: "center" },
							{ label: __("Right", "hog-scaffold"), value: "right" },
						]}
						onChange={(value) => setAttributes({ textAlignment: value })}
					/>

					<SelectControl
						label={__("Image Shape", "hog-scaffold")}
						value={imageShape}
						options={[
							{ label: __("Circle", "hog-scaffold"), value: "circle" },
							{ label: __("Square", "hog-scaffold"), value: "square" },
							{ label: __("Rounded", "hog-scaffold"), value: "rounded" },
						]}
						onChange={(value) => setAttributes({ imageShape: value })}
					/>
				</PanelBody>

				<PanelBody
					title={__("Display Options", "hog-scaffold")}
					initialOpen={false}
				>
					<ToggleControl
						label={__("Show Bio", "hog-scaffold")}
						checked={showBio}
						onChange={(value) => setAttributes({ showBio: value })}
					/>

					<ToggleControl
						label={__("Show Social Links", "hog-scaffold")}
						checked={showSocialLinks}
						onChange={(value) => setAttributes({ showSocialLinks: value })}
					/>
				</PanelBody>

				<PanelBody
					title={__("Team Members", "hog-scaffold")}
					initialOpen={false}
				>
					<Button
						variant="primary"
						icon={plus}
						onClick={addMember}
						className="wp-block-team-profiles__add-member"
					>
						{__("Add Team Member", "hog-scaffold")}
					</Button>
				</PanelBody>
			</InspectorControls>

			<div {...blockProps}>
				<div className="wp-block-team-profiles__container">
					{members.map((member, index) => (
						<div key={member.id} className="wp-block-team-profiles__member">
							<Card>
								<CardHeader>
									<Flex>
										<FlexItem>
											<strong>
												{member.name || __("Unnamed Member", "hog-scaffold")}
											</strong>
										</FlexItem>
										<FlexItem>
											<ButtonGroup>
												<Button
													icon={arrowUp}
													disabled={index === 0}
													onClick={() => moveMember(index, "up")}
													label={__("Move up", "hog-scaffold")}
													size="small"
												/>
												<Button
													icon={arrowDown}
													disabled={index === members.length - 1}
													onClick={() => moveMember(index, "down")}
													label={__("Move down", "hog-scaffold")}
													size="small"
												/>
												<Button
													icon={trash}
													onClick={() => removeMember(index)}
													label={__("Remove member", "hog-scaffold")}
													isDestructive
													size="small"
												/>
											</ButtonGroup>
										</FlexItem>
									</Flex>
								</CardHeader>
								<CardBody>
									<div className="wp-block-team-profiles__member-preview">
										<div className="wp-block-team-profiles__image">
											<MediaUploadCheck>
												<MediaUpload
													onSelect={(media) =>
														updateMember(index, "image", {
															url: media.url,
															alt: media.alt,
															id: media.id,
														})
													}
													allowedTypes={["image"]}
													value={member.image.id}
													render={({ open }) => (
														<Button
															onClick={open}
															className={`wp-block-team-profiles__image-button ${member.image.url ? "has-image" : "no-image"}`}
														>
															{member.image.url ? (
																<img
																	src={member.image.url}
																	alt={member.image.alt}
																	className={`wp-block-team-profiles__image-preview is-shape-${imageShape}`}
																/>
															) : (
																<div
																	className={`wp-block-team-profiles__image-placeholder is-shape-${imageShape}`}
																>
																	{__("Add Image", "hog-scaffold")}
																</div>
															)}
														</Button>
													)}
												/>
											</MediaUploadCheck>
										</div>

										<div className="wp-block-team-profiles__content">
											<RichText
												tagName="h3"
												className="wp-block-team-profiles__name"
												value={member.name}
												onChange={(value) => updateMember(index, "name", value)}
												placeholder={__("Team member name", "hog-scaffold")}
											/>

											<RichText
												tagName="p"
												className="wp-block-team-profiles__position"
												value={member.position}
												onChange={(value) =>
													updateMember(index, "position", value)
												}
												placeholder={__("Position/Title", "hog-scaffold")}
											/>

											{showBio && (
												<RichText
													tagName="p"
													className="wp-block-team-profiles__bio"
													value={member.bio}
													onChange={(value) =>
														updateMember(index, "bio", value)
													}
													placeholder={__("Brief bio...", "hog-scaffold")}
												/>
											)}

											{showSocialLinks && (
												<div className="wp-block-team-profiles__social">
													<h4>{__("Social Links", "hog-scaffold")}</h4>
													{member.socialLinks.map((link, linkIndex) => (
														<div
															key={linkIndex}
															className="wp-block-team-profiles__social-link"
														>
															<Flex>
																<FlexItem>
																	<SelectControl
																		value={link.platform}
																		options={socialPlatformOptions}
																		onChange={(value) =>
																			updateSocialLink(
																				index,
																				linkIndex,
																				"platform",
																				value
																			)
																		}
																	/>
																</FlexItem>
																<FlexItem>
																	<TextControl
																		value={link.url}
																		onChange={(value) =>
																			updateSocialLink(
																				index,
																				linkIndex,
																				"url",
																				value
																			)
																		}
																		placeholder={__("URL", "hog-scaffold")}
																	/>
																</FlexItem>
																<FlexItem>
																	<Button
																		icon={trash}
																		onClick={() =>
																			removeSocialLink(index, linkIndex)
																		}
																		isDestructive
																		size="small"
																	/>
																</FlexItem>
															</Flex>
														</div>
													))}
													<Button
														variant="secondary"
														size="small"
														onClick={() => addSocialLink(index)}
													>
														{__("Add Social Link", "hog-scaffold")}
													</Button>
												</div>
											)}
										</div>
									</div>
								</CardBody>
							</Card>
							<Spacer marginBottom={4} />
						</div>
					))}

					{members.length === 0 && (
						<div className="wp-block-team-profiles__empty">
							<p>{__("No team members added yet.", "hog-scaffold")}</p>
							<Button variant="primary" onClick={addMember}>
								{__("Add Your First Team Member", "hog-scaffold")}
							</Button>
						</div>
					)}
				</div>
			</div>
		</>
	);
}
