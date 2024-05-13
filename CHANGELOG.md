# Changelog

## 6.0.0 - 2024-05-13

### Changed
- Now requires PHP `8.2.0+`.
- Now requires Craft `5.0.0+`.

## 5.0.6 - 2023-10-25

### Fixed
- Fix sticky toolbar.

## 5.0.5 - 2023-03-15

### Fixed
- Fix incorrect markup for code blocks. (thanks @bencroker).
- Fix selecting no linkable headers. (thanks @bencroker).

## 5.0.4 - 2023-01-06

### Fixed
- Add field normaliser for `enabledToolbarIconNames`, to assist with migrating older Doxter field settings for fields that haven’t been updated recently.
- Fix an error where field settings wouldn’t save when selecting no toolbar icons.

## 5.0.3 - 2023-01-06

### Changed
- Only admins are now allowed to access plugin settings.

### Fixed
- Fix field settings not working correctly when setting toolbar icons

## 5.0.2 - 2022-10-23

### Fixed
- Fix bug in parse options. (thanks @bencroker).

## 5.0.1 - 2022-07-27

### Added
- Add English translations.

### Fixed
- Fix a type error with `parseAttributes()`. (thanks @bencroker).

## 5.0.0 - 2022-07-26

### Changed
- Now requires PHP `8.0.2+`.
- Now requires Craft `4.0.0+`.
- Renamed `verbb\doxter\services\DoxterService` to `verbb\doxter\services\Service`.

### Removed
- Remove `CraftQL` plugin support.

## 4.0.0 - 2022-07-26

> {note} The plugin’s package name has changed to `verbb/doxter`. Doxter will need be updated to 4.0 from a terminal, by running `composer require verbb/doxter && composer remove selvinortiz/doxter`.

### Changed
- Migration to `verbb/doxter`.
- Now requires Craft 3.7+.

## 3.5.0 - 2020-07-15

### Updated
- Updated Doxter to use the same markdown parser used by Craft under the hood

## 3.5.0-beta-2 - 2019-07-20

### Added
- Added `cebe/markdown` as a dependency

### Updated
- Updated the markdown parser abstraction to use `cebe/markdown` under the hood
- Updated the markdown parsing flavor to be **GitHub Flavored Markdown**

### Fixed
- Fixed issue where settings could not be saved if header parsing was disabled
- Fixed several small compatability issues with **Craft 3.4**

### Removed
- Removed Parsedown as a dependency

## 3.5.0-beta - 2019-07-20

### Added
- Added an experimental feature to allow Doxter to parse files

### Updated
- Updated hyphenation config to be set explicitely

## 3.4.9 - 2019-12-30

### Update
- Update Parsedown Extra to latest stable release (0.8.1)

## 3.4.8 - 2019-05-26

### Fixed
- Fixed support for Feed Me imports

## 3.4.7 - 2019-01-11

### Added
- Added support for Table of Contents 🎉

## 3.4.6 - 2019-01-09

### Fixed
- Fixed issue where shortcodes were parsed and (incorrectly) wrapped with `<p>` tags
- Fixes issue where parsing order caused some shortcode attributes to be mangled

## 3.4.5 - 2019-01-09

### Added
- Added an easy way for you to add your own [Shortcodes](https://selvinortiz.com/plugins/doxter/shortcodes) without touching PHP
- Added support for a new `verbatim` attribute in shortcode tags that will display the shortcode _as is_ (think code demos)
- Added support for shortcode tag registration via `config/doxter.php`
- Added logging of unregistered shortcodes and missing shortcode templates

### Updated
- Updated the `ShortcodeModel` class to make it easy for you to work with it in your shortcode templates
- Updated **footnote** support to address issues in a previous version of **Parsedown**
- Updated parsing order to allow Shortcodes to be parsed before Markdown
- Updated typography support with protection against **widows** and **orphans**

### Removed
- Removed support for registering shortcodes using the event system
- Removed all shortcodes originally shipped with Doxter to reduce codebase and let you write your custom markup easily

## 3.4.4 - 2019-01-06

### Added
- Added [Dark Mode] support

### Update
- Updated typography support with better _whitespace_ and _widow_ control

[Dark Mode]: https://github.com/mattgrayisok/craft-dark-mode

## 3.4.3 - 2019-01-05

### Added
- Added a new **Dark Mode**

### Removed
- Removed editor preview support
- Removed full-screen editing support
- Removed README notes related to todos

### Updated
- Updated support for live preview

## 3.4.2 - 2018-12-29

### Added
- Added new icon
- Added new `[esc]` shortcode to escape shortcodes for demo purposes

## 3.4.1 - 2018-12-29

### Removed
- Removed explicit require autoloader call in `Doxter::init()`

## 3.4.0 - 2018-12-29

### Updated
- Updated from MIT to Craft (commercial) license

## 3.4.0-beta-1 - 2018-12-28 [CRITICAL]

### Fixed issue [#22]
- Fixed issue [#22] where Doxter would puke HTML instead of Markdown on secondary sites

### Updated
- Updated the return value of the Doxter field in string context (`__toString()`)

{warning} You must update your templates from `{{ entry.doxterField }}` to `{{ entry.doxterField.getHtml() }}`

[#22]: https://github.com/selvinortiz/craft-plugin-doxter/issues/22

## 3.3.0-beta-3 - 2018-12-20

### Fixed
- Fixed issue where footnotes would not parse properly
- Fixed several issues with the markdown editor

## 3.2.0 - 2018-11-28

### Fixed
- Fixed critical issue where Doxter could cause Twig to load prematurely

### Added
- Added CraftQL support for `text` and `html` fields

## 3.1.10 - 2018-05-24

### Fixed
- Fixed broken code blocks after parsing order was updated

## 3.1.9 - 2018-05-11

### Updated
- Updated version and dependencies

## 3.1.8 - 2018-05-11

### Fixed
- Fixed issue where smart typography did not apply before parsing markdown

## 3.1.7 - 2018-04-18

### Fixed
- Fixed issue where full-screen editing mode would hide editor behind the sidebar

## 3.1.6 - 2018-04-18

### Fixed
- Fixed issue where switching content tabs would not trigger a change
- Fixed issue where an unstable version of the markdown parser was in use

## 3.1.5 - 2018-03-23

### Added
- Added plugins settings
- Added field settings

## 3.1.4 - 2017-07-28

### Fixed
- Fixed issue #11 where asset selector modal would only show images
- Fixed several formatting issues in `README` (Hat tip to Brandon Kelly)

## 3.1.3 - 2017-05-17

### Fixed
- Fixed issue where `value.raw` in the field, could break the dashboard

## 3.1.2 - 2017-05-12 [CRITICAL]

### Removed
- Removes dependency on `FitVids` for responsive video embed shortcode

## 3.1.1 - 2017-04-17 [CRITICAL]

### Fixed
- Fixed issue [#6](https://github.com/selvinortiz/craft-plugin-doxter/issues/6) where parsing prevented the creation of new entries

> {note} Sooner or later you'll do something stupid that seems lazy, like not test if entries can be created when it contains one of your fields. Well, this was pure stupidity and not laziness, I assure you 😂 Sorry everyone!

## 3.1.0 - 2017-04-16

### Added
- Added emoji support as requested on issue [#5](https://github.com/selvinortiz/craft-plugin-doxter/issues/5) 👍
- Added (back) support for reference tag parsing
- Added full documentation as a Wiki

### Fixed
- Fixed full viewport editing while in the Doxter field
- Fixed issue [#4](https://github.com/selvinortiz/craft-plugin-doxter/issues/4) where `entry.doxterField` would render escaped html

### Changed
- Updated codebase to adhere to Craft Coding Standards

## 3.0.8 - 2017-04-11

### Fixed
- Fixed full viewport editing from issue [#2](https://github.com/selvinortiz/craft-plugin-doxter/issues/2)

### Changed
- Updated plugin.(css|js) to use spaces
- Updated plugin.js to include semi-colons where missing

## 3.0.7 - 2017-04-02

### Fixed
- Fixed issue where `hasSettings` is no longer supported

### Changed
- Updated all dependencies to the latest stable version

## 3.0.6 - 2017-02-06

### Fixed
- Fixed critical issue where the Shortcode parser would pee himself if fields were empty

## 3.0.5 - 2017-02-06

### Added
- Added SmartyPants as a dependency for handling typography
- Added back support for **Shortcodes**
- Added back the `doxter()` global to access the public API
- Added a new `DoxterEvent` to notify about parsing events
- Updates plugin icons

## 3.0.4 - 2017-01-03

### Added
- Added prettier icons

### Updated
- Updated the way components were accessed for improved type hinting

### Fixed
- Fixed an issue where the variable class was loaded

## 3.0.3 - 2017-01-02

### Added
- Added cover image and updated icons

## 3.0.1 - 2017-01-02

### Added
- Added changelog and documentation URLs

### Fixed
- Fixed an issue where parsers were not instantiated correctly

## 3.0.0 - 2017-01-02
- Initial (beta) release for Craft 3
