# Changelog
All notable changes to the Family Management module will be documented here.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - 2026-02-06

### Added
- Initial release of Family Management module
- Child profile management (create, edit, delete)
- Birth date tracking for children
- Optional mother/father user relationships
- Profile sidebar widget displaying children
- Automatic birthday calendar integration
- Privacy-respecting display (parent links only shown when specified)
- Age calculation from birth date
- Database migration for child table
- Comprehensive documentation (README, DEVELOPMENT, TESTING)

### Security
- Access control: only profile owners and admins can manage children
- Input validation on all child data fields
- Foreign key constraints for data integrity

## [Future Considerations]

### Planned Features
- Profile pictures for children
- Additional custom fields (school, notes, etc.)
- Family tree visualization
- Anniversary tracking (baptism, confirmation, etc.)
- Export family data to PDF
- Bulk import from CSV
- Email reminders for upcoming birthdays
- Permission granularity (per-space family management)

[Unreleased]: https://github.com/yourusername/humhub-family-module/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/yourusername/humhub-family-module/releases/tag/v1.0.0
