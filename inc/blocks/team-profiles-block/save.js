/**
 * Save component for Team Profiles Block
 *
 * Renders static HTML for optimal SEO performance.
 * Content is saved directly to the database for faster loading.
 *
 * @return {JSX.Element} The saved block HTML.
 */
import { useBlockProps } from "@wordpress/block-editor";

const TeamProfilesSave = ({ attributes }) => {
	const {
		profiles = [],
		columns = 3,
		layout = "grid",
		showSocial = true,
		profileStyle = "default",
		heading = "",
		subheading = "",
	} = attributes;

	const blockProps = useBlockProps.save({
		className: `layout-${layout} style-${profileStyle} columns-${columns} ${!showSocial ? "no-social" : ""}`,
		style: {
			"--columns": columns,
		},
	});

	return (
		<div {...blockProps}>
			{(heading || subheading) && (
				<div className="wp-block-team-profiles__header">
					{heading && (
						<h2 className="wp-block-team-profiles__heading">{heading}</h2>
					)}
					{subheading && (
						<p className="wp-block-team-profiles__subheading">{subheading}</p>
					)}
				</div>
			)}
			<div className="wp-block-team-profiles__grid">
				{profiles.map((profile) => (
					<div key={profile.id} className="wp-block-team-profiles__profile">
						{profile.image && profile.image.url && (
							<div className="wp-block-team-profiles__image">
								<img
									src={profile.image.url}
									alt={profile.image.alt || profile.name || ""}
									loading="lazy"
								/>
							</div>
						)}
						<div className="wp-block-team-profiles__content">
							{profile.name && (
								<h3 className="wp-block-team-profiles__name">{profile.name}</h3>
							)}
							{profile.role && (
								<p className="wp-block-team-profiles__role">{profile.role}</p>
							)}
							{profile.bio && (
								<p className="wp-block-team-profiles__bio">{profile.bio}</p>
							)}
							{showSocial &&
								profile.social &&
								Object.keys(profile.social).some(
									(key) => profile.social[key]
								) && (
									<div className="wp-block-team-profiles__social">
										{profile.social.twitter && (
											<a
												href={profile.social.twitter}
												className="wp-block-team-profiles__social-link twitter"
												target="_blank"
												rel="noopener noreferrer"
												aria-label={`${profile.name} on Twitter`}
											>
												<span className="screen-reader-text">Twitter</span>
											</a>
										)}
										{profile.social.linkedin && (
											<a
												href={profile.social.linkedin}
												className="wp-block-team-profiles__social-link linkedin"
												target="_blank"
												rel="noopener noreferrer"
												aria-label={`${profile.name} on LinkedIn`}
											>
												<span className="screen-reader-text">LinkedIn</span>
											</a>
										)}
										{profile.social.facebook && (
											<a
												href={profile.social.facebook}
												className="wp-block-team-profiles__social-link facebook"
												target="_blank"
												rel="noopener noreferrer"
												aria-label={`${profile.name} on Facebook`}
											>
												<span className="screen-reader-text">Facebook</span>
											</a>
										)}
										{profile.social.instagram && (
											<a
												href={profile.social.instagram}
												className="wp-block-team-profiles__social-link instagram"
												target="_blank"
												rel="noopener noreferrer"
												aria-label={`${profile.name} on Instagram`}
											>
												<span className="screen-reader-text">Instagram</span>
											</a>
										)}
										{profile.social.email && (
											<a
												href={`mailto:${profile.social.email}`}
												className="wp-block-team-profiles__social-link email"
												aria-label={`Email ${profile.name}`}
											>
												<span className="screen-reader-text">Email</span>
											</a>
										)}
									</div>
								)}
						</div>
					</div>
				))}
			</div>
		</div>
	);
};

export default TeamProfilesSave;
