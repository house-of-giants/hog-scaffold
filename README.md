# House of Giants Scaffold

[![Support Level](https://img.shields.io/badge/support-active-green.svg)](#support-level)

## Dependencies

1. [Node & NPM](https://www.npmjs.com/get-npm) - Build packages and 3rd party dependencies are managed through NPM, so you will need that installed globally.
2. [Webpack](https://webpack.js.org/) - Webpack is used to process the JavaScript, CSS, and other assets.

## Getting Started

### Direct Install

- Clone the repository
- Rename folder theme-scaffold -> your project's name
- If copying files manually to an existing theme directory instead of cloning directly from the repository, make sure to include the following files which may be hidden:

```
.babelrc
.browserslistrc
.editorconfig
.eslintignore
.eslintrc
.gitignore
```

The NPM commands will fail without these files present.

- Do case-sensitive search/replace for the following:

      	- HoGScaffold
      	- HoG_SCAFFOLD
      	- HoG-scaffold
      	- HoG_scaffold
      	- HoG Scaffold

- `cd` into the theme folder
- run `npm run start`

## Webpack config

Webpack config files can be found in `config` folder:

- `webpack.dev.js`
- `webpack.shared.js`
- `webpack.prod.js`
- `webpack.settings.js`

In most cases `webpack.settings.js` is the main file which would change from project to project. For example adding or removing entry points for JS and CSS.

## NPM Commands

- `npm run start` (install dependencies)
- `npm run watch` (watch)
- `npm run build` (build all files)
- `npm run build-release` (build all files for release)
- `npm run dev` (build all files for development)
- `npm run lint-release` (install dependencies and run linting)
- `npm run lint-css` (lint CSS)
- `npm run lint-js` (lint JS)
- `npm run lint` (run all lints)
- `npm run format-js` (format JS using eslint)
- `npm run format` (alias for `npm run format-js`)

## Contributing

We don't know everything! We welcome pull requests and spirited, but respectful, debates. Please contribute via [pull requests on GitHub](https://github.com/HoG/theme-scaffold/compare).

1. Fork it!
2. Create your feature branch: `git checkout -b feature/my-new-feature`
3. Commit your changes: `git commit -am 'Added some great feature!'`
4. Push to the branch: `git push origin feature/my-new-feature`
5. Submit a pull request

## Support Level

**Active:** House of Giants is actively working on this, and we expect to continue work for the foreseeable future including keeping tested up to the most recent version of WordPress. Bug reports, feature requests, questions, and pull requests are welcome.
