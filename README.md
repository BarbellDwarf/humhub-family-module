# Family Management Module for HumHub

## Overview
Adds a Family tab to HumHub profiles for managing spouses and children, with optional links to existing user accounts.
You can store manual details for non-user family members while still surfacing birthdays in the Calendar module for reminders.
Designed for church and community contexts where family relationships need to be tracked alongside user profiles.

## Features
- Add spouses and children to user profiles
- Link spouses/children to existing user accounts or store manual details
- Relationship type support for children (child, grandchild, stepchild, foster child)
- Track birthdays (uses linked profile birthdays when available)
- Automatic birthday calendar integration
- Profile links for family members with accounts
- Family management tab integrated into profile layout
- Optional family diagram tab (configurable)
- Privacy-respecting (manual details used only when no account is linked)

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
### Adding a Spouse
1. Navigate to your profile or another user's profile (if you have permissions).
2. Open the Family tab from the profile menu.
3. Click "Add Spouse".
4. Optionally link a spouse user account.
5. If no account is linked, enter First Name, Last Name, Birth Date.
6. Click Save.

### Adding Children
1. Navigate to your profile or another user's profile (if you have permissions).
2. Open the Family tab from the profile menu to manage spouse/children.
3. Look for the "Children" widget in the profile sidebar.
4. Click "Add Child".
5. Optionally link a child user account.
6. If no account is linked, enter First Name, Last Name, Birth Date.
7. Choose the relation type (Child/Grandchild/Stepchild/Foster child).
8. Click Save.

### Family Diagram
1. Enable the diagram section in Administration → Modules → Family Management → Configure.
2. Open the Family tab in a user profile to see the diagram beneath the spouse/children sections.

### Birthday Calendar
Spouse and child birthdays automatically appear in the HumHub Calendar module as:
"[Family Member Name] ([Parent Name]'s child)" or "[Spouse Name] ([User Name]'s spouse)"

### Privacy
- Only profile owners and administrators can manage children.
- Children do not need user accounts; manual details are used when no account is linked.

## Configuration
- Enable/disable the Family Diagram section in the module configuration screen.

## Development
See DEVELOPMENT.md for technical documentation and contribution guidelines.

## Support
For issues or questions, please open a GitHub issue.

## License
This module is licensed under the MIT License. See LICENSE for details.

## Credits
Developed for church and community management needs.
