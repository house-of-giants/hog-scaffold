/**
 * ESLint configuration for WordPress theme development
 * Using ESLint 9 flat config format
 */

import js from '@eslint/js';
import react from 'eslint-plugin-react';
import prettier from 'eslint-plugin-prettier';
import babelParser from '@babel/eslint-parser';

export default [
	// Base recommended configuration
	js.configs.recommended,

	// Main configuration for JavaScript and JSX files
	{
		files: ['**/*.js', '**/*.jsx'],
		languageOptions: {
			parser: babelParser,
			parserOptions: {
				ecmaFeatures: {
					jsx: true,
				},
				requireConfigFile: false,
				babelOptions: {
					presets: ['@wordpress/default'],
				},
			},
			globals: {
				wp: 'readonly',
				window: 'readonly',
				document: 'readonly',
				console: 'readonly',
				fetch: 'readonly',
				URLSearchParams: 'readonly',
				setTimeout: 'readonly',
				alert: 'readonly',
				localStorage: 'readonly',
			},
		},
		plugins: {
			react,
			prettier,
		},
		settings: {
			react: {
				version: 'detect',
			},
		},
		rules: {
			// Prettier integration
			'prettier/prettier': 'error',

			// React rules
			'react/jsx-uses-react': 'off',
			'react/react-in-jsx-scope': 'off',
			'react/jsx-uses-vars': 'error',
			'react/forbid-prop-types': [
				'error',
				{
					forbid: ['any'],
					checkContextTypes: true,
					checkChildContextTypes: true,
				},
			],

			// General JavaScript rules
			'class-methods-use-this': 'off',
			'prefer-destructuring': ['error', { array: false, object: true }],
			'no-restricted-syntax': [
				'error',
				{
					selector: 'ForInStatement',
					message:
						'for..in loops iterate over the entire prototype chain, which is virtually never what you want. Use Object.{keys,values,entries}, and iterate over the resulting array.',
				},
				{
					selector: 'LabeledStatement',
					message:
						'Labels are a form of GOTO; using them makes code confusing and hard to maintain and understand.',
				},
				{
					selector: 'WithStatement',
					message:
						'`with` is disallowed in strict mode because it makes code impossible to predict and optimize.',
				},
			],
		},
	},

	// Ignore patterns
	{
		ignores: [
			'node_modules/**',
			'vendor/**',
			'dist/**',
			'build/**',
			'.git/**',
			'.taskmaster/**',
			'.cursor/**',
			'*.min.js',
		],
	},
];
