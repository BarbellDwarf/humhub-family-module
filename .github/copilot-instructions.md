# Copilot Instructions for `family-tree` HumHub module

## Build, test, and lint

This repository does not define module-local automated build/lint/test commands (`composer.json`, `phpunit.xml`, `codeception.yml`, `package.json`, and `Makefile` are not present in this module directory).

Use the manual test suite in `TESTING.md`:
- Full regression pass: run the checklist sections referenced in `TESTING.md` (especially sections 1, 2, 6, and 7 after changes).
- Single targeted test: run one checklist section that matches your change (for example, only section **6. Birthday Calendar** when changing calendar integration).

## High-level architecture

- **Module bootstrap and wiring**
  - `config.php` registers module metadata, profile menu integration (`ProfileMenu::EVENT_INIT`), and calendar integration events (`getItemTypes`, `findItems`).
  - `Module.php` sets `isSpaceModule = false` (profile-only module behavior) and runs pending migrations during `install()`.

- **Profile-facing family UI flow**
  - `IndexController` is a `ContentContainerController` restricted to `User` containers and renders the main family tab (`views/index/index.php`).
  - It composes spouse + children data and optional diagram data (`buildDiagramData`, `getChildFamilies`) before rendering.
  - Child and spouse CRUD are split into `ChildController` and `SpouseController`, each enforcing owner/admin checks and redirecting back to `/family/index/index` with `cguid`.

- **Domain model behavior**
  - `models/Child.php` and `models/Spouse.php` support two data modes: linked user account or manual profile fields.
  - Effective display/birthday data always resolve from linked user profile first, then manual fields (`getEffectiveBirthDate()`, `getDisplayName()`).
  - Schema capability checks (`supportsChildUserAccount()`, `supportsRelationType()`) are used to keep runtime compatible across migration states.
  - `Spouse` maintains reverse spouse records automatically (`createBidirectionalLink`, cleanup in `afterSave`/`afterDelete`).

- **Calendar integration pipeline**
  - `Events::onGetCalendarItemTypes()` and `Events::onFindCalendarItems()` delegate to child/spouse integration classes.
  - `integration/*CalendarQuery.php` finds birthdays in requested ranges; `*CalendarEntry.php` maps records to calendar events.
  - Child integration intentionally skips linked users with their own profile birthday to avoid duplicate birthday entries.

- **Configuration + presentation**
  - Admin toggle for family diagram is handled by `ConfigController` + `FamilyConfigureForm` via module settings key `enableDiagramTab`.
  - Styling is centralized in `assets/FamilyAsset.php` and `resources/css/family.css`; views register this asset where family UI controls/diagram appear.

## Key conventions for this codebase

- Always use translation keys with category `FamilyModule.base` (see `messages/en/base.php`).
- Routes and redirects for profile context use `cguid` and point back to `['/family/index/index', 'cguid' => ...]`.
- User-picker fields (`child_user_guid`, `spouse_user_guid`) may arrive as JSON-array strings (e.g., `'[]'`); models normalize these in `beforeValidate()`.
- When a linked account is selected, corresponding manual fields are cleared and treated as fallback-only data.
- Keep permission logic aligned with existing pattern: authenticated access via `AccessControl`, then owner/admin checks per record.
- If adding new child/spouse attributes, update both migration(s) and model capability/validation paths so mixed-schema installs continue to work.
- Register `FamilyAsset` in any new view that uses family action button classes or diagram styling.
