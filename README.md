# Family Management Module for HumHub

## Overview
Manage family relationships and children profiles with birthday calendar integration for church/community use.

## Features
- Add children to user profiles without creating separate accounts
- Track children's birthdays
- Optional mother/father user linking
- Automatic birthday calendar integration
- Privacy-respecting (only shows parent links when specified)

## Requirements
- HumHub 1.15 or higher
- Calendar module (for birthday integration)

## Installation
1. Download or clone this repository.
2. Copy the entire `family` folder to `protected/modules/` in your HumHub installation.
3. Navigate to Administration → Modules.
4. Find "Family Management" and click Enable.
5. The database tables will be created automatically.

## Usage
### Adding Children
1. Navigate to your profile or another user's profile (if you have permissions).
2. Look for the "Children" widget in the profile sidebar.
3. Click "Add Child".
4. Fill in: First Name, Last Name, Birth Date.
5. Optionally select Mother/Father from existing users.
6. Click Save.

### Birthday Calendar
Children's birthdays automatically appear in the HumHub Calendar module as:
"[Child Name] ([Parent Name]'s child)"

### Privacy
- Mother/Father fields are optional and only displayed if specified.
- Only profile owners and administrators can manage children.
- Children do not need user accounts.

## Configuration
No additional configuration required. Module works out of the box after installation.

## Development
See DEVELOPMENT.md for technical documentation and contribution guidelines.

## Support
For issues or questions, please open a GitHub issue.

## License
This module is licensed under the same license as HumHub (AGPLv3).

## Credits
Developed for church and community management needs.
