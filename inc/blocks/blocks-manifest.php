<?php
// This file is generated. Do not modify it manually.
return array(
	'example-block' => array(
		'$schema' => 'https://schemas.wp.org/trunk/block.json',
		'apiVersion' => 3,
		'name' => 'hog-scaffold/example',
		'title' => 'Example Block',
		'category' => 'hog-scaffold-blocks',
		'icon' => 'feedback',
		'description' => 'An example block for demonstrating block development patterns and best practices.',
		'keywords' => array(
			'example',
			'demo',
			'template',
			'sample'
		),
		'textdomain' => 'hog-scaffold',
		'editorScript' => 'file:./index.js',
		'editorStyle' => 'file:./index.css',
		'style' => 'file:./style-index.css',
		'supports' => array(
			'anchor' => true,
			'ariaLabel' => true,
			'customClassName' => true,
			'html' => false,
			'inserter' => true,
			'lock' => false,
			'multiple' => true,
			'reusable' => true,
			'color' => array(
				'gradients' => true,
				'background' => true,
				'text' => true,
				'link' => true
			),
			'spacing' => array(
				'padding' => true,
				'margin' => true,
				'blockGap' => false
			),
			'typography' => array(
				'fontSize' => true,
				'lineHeight' => true,
				'__experimentalFontFamily' => true,
				'__experimentalFontWeight' => true,
				'__experimentalFontStyle' => true,
				'__experimentalTextTransform' => true,
				'__experimentalTextDecoration' => true,
				'__experimentalLetterSpacing' => true,
				'__experimentalDefaultControls' => array(
					'fontSize' => true
				)
			)
		),
		'attributes' => array(
			'customTitle' => array(
				'type' => 'string',
				'default' => 'Example Block Title'
			),
			'customContent' => array(
				'type' => 'string',
				'default' => 'This is example content to demonstrate block functionality.'
			),
			'showAdvanced' => array(
				'type' => 'boolean',
				'default' => false
			)
		),
		'example' => array(
			'attributes' => array(
				'customTitle' => 'Example Block Demo',
				'customContent' => 'This example shows how the block appears in the editor.',
				'showAdvanced' => true
			)
		),
		'variations' => array(
			array(
				'name' => 'example-simple',
				'title' => 'Simple Example',
				'description' => 'A simple example block configuration',
				'attributes' => array(
					'customTitle' => 'Simple Title',
					'showAdvanced' => false
				),
				'isDefault' => true
			),
			array(
				'name' => 'example-advanced',
				'title' => 'Advanced Example',
				'description' => 'An advanced example block with all features',
				'attributes' => array(
					'customTitle' => 'Advanced Configuration',
					'showAdvanced' => true
				),
				'isDefault' => false
			)
		)
	)
);
