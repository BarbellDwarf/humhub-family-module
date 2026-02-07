2. Reference Documentation Setup
Open these URLs in browser tabs for Copilot context:

https://docs.humhub.org/docs/develop/modules/

https://github.com/humhub/calendar/blob/master/integration/BirthdayCalendarQuery.php

https://docs.humhub.org/docs/develop/modules-base-class/

https://docs.humhub.org/docs/develop/models/

Session 1: Core Documentation & Module Foundation (20-25 min)
File 1: README.md
Prompt for Copilot:

text
Create a comprehensive README.md for a HumHub Family module that includes:

# Family Management Module for HumHub

## Overview
Description of module - manage family relationships and children profiles with birthday calendar integration for church/community use

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
1. Download or clone this repository
2. Copy the entire `family` folder to `protected/modules/` in your HumHub installation
3. Navigate to Administration → Modules
4. Find "Family Management" and click Enable
5. The database tables will be created automatically

## Usage
### Adding Children
1. Navigate to your profile or another user's profile (if you have permissions)
2. Look for the "Children" widget in the profile sidebar
3. Click "Add Child"
4. Fill in: First Name, Last Name, Birth Date
5. Optionally select Mother/Father from existing users
6. Click Save

### Birthday Calendar
Children's birthdays automatically appear in the HumHub Calendar module as:
"[Child Name] ([Parent Name]'s child)"

### Privacy
- Mother/Father fields are optional and only displayed if specified
- Only profile owners and administrators can manage children
- Children do not need user accounts

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
File 2: DEVELOPMENT.md
Prompt for Copilot:

text
Create a DEVELOPMENT.md file that documents the technical architecture:

# Development Documentation

## Module Architecture

### Directory Structure
family/
├── config.php # Module configuration and event listeners
├── module.json # Module metadata
├── Module.php # Main module class
├── Events.php # Event handlers for calendar integration
├── models/
│ └── Child.php # Child ActiveRecord model
├── migrations/
│ └── m260206_220000_initial.php # Database schema
├── controllers/
│ └── ChildController.php # CRUD operations for children
├── views/
│ ├── child/
│ │ ├── create.php # Create child view
│ │ ├── edit.php # Edit child view
│ │ └── _form.php # Shared form partial
│ └── widgets/
│ └── children.php # Profile widget view
├── widgets/
│ └── ChildrenWidget.php # Profile sidebar widget
└── permissions/
└── ManageFamily.php # Permission class (future)

text

## Database Schema

### child table
- `id` - Primary key
- `user_id` - Parent user (FK to user table)
- `first_name` - Child's first name
- `last_name` - Child's last name  
- `birth_date` - Child's date of birth
- `mother_id` - Optional FK to user table
- `father_id` - Optional FK to user table
- `created_at` - Timestamp
- `updated_at` - Timestamp

## Event System

### Calendar Integration
Hooks into `humhub\modules\calendar\interfaces\CalendarService::EVENT_GET_BIRTHDAYS`

The Events::onBirthdayQuery() method:
1. Queries all children from the database
2. Calculates ages and formats birthday data
3. Creates calendar entries for each child
4. Links to parent's profile page
5. Appends to calendar results

## Widget System

### ChildrenWidget
Displays on user profile sidebar showing:
- List of all children for profile owner
- Add/Edit/Delete controls for authorized users
- Conditional parent links (only shown if set)

## URL Routes
- `/family/child/create` - Add new child
- `/family/child/edit?id={id}` - Edit existing child  
- `/family/child/delete?id={id}` - Delete child

## Access Control
- Profile owners can manage their own children
- Administrators can manage all children
- Other users cannot edit/delete children

## Extending the Module

### Adding Custom Fields
Edit `models/Child.php` and add:
1. New validation rules in rules()
2. New attribute labels in attributeLabels()
3. Update migration to add database columns
4. Update form view to include new fields

### Custom Permissions
Implement `permissions/ManageFamily.php` for granular control

## Testing Checklist
See TESTING.md for comprehensive testing procedures

## Contributing
1. Fork the repository
2. Create feature branch
3. Make changes with proper PHPDoc comments
4. Test thoroughly
5. Submit pull request with description

## Code Standards
- Follow Yii2 coding standards
- Use HumHub conventions for modules
- Add PHPDoc comments to all classes and methods
- Use proper namespacing: `humhub\modules\family`
File 3: TESTING.md
Prompt for Copilot:

text
Create comprehensive TESTING.md file with testing procedures:

# Testing Guide

## Installation Testing

### 1. Module Installation
- [ ] Copy module to `protected/modules/family` directory
- [ ] Module appears in Administration → Modules list
- [ ] Enable button works without errors
- [ ] Database migration runs successfully
- [ ] Check `child` table exists in database

## Functional Testing

### 2. Creating Children
- [ ] Navigate to own profile
- [ ] "Children" widget appears in sidebar
- [ ] Click "Add Child" opens create form
- [ ] Required fields validated (first_name, last_name, birth_date)
- [ ] Birth date picker works correctly
- [ ] Mother/Father dropdowns populated with users
- [ ] Can leave Mother/Father blank
- [ ] Submit creates child record
- [ ] Redirects back to profile
- [ ] Success message displayed
- [ ] Child appears in widget

### 3. Editing Children
- [ ] Edit icon appears next to child name
- [ ] Click edit opens form with existing data
- [ ] Can modify all fields
- [ ] Can change Mother/Father or set to blank
- [ ] Submit updates record
- [ ] Changes reflected in widget
- [ ] Success message displayed

### 4. Deleting Children
- [ ] Delete icon appears next to child name
- [ ] Confirmation prompt shown
- [ ] Delete removes record from database
- [ ] Child removed from widget
- [ ] Success message displayed

### 5. Display Features
- [ ] Child's age calculated correctly from birth_date
- [ ] Full name displayed properly
- [ ] Mother/Father links shown only when specified
- [ ] Mother/Father names are clickable profile links
- [ ] Empty state shows when no children added
- [ ] Widget only shows for user profiles (not spaces)

## Calendar Integration Testing

### 6. Birthday Calendar
- [ ] Calendar module is installed and enabled
- [ ] Navigate to Calendar
- [ ] Child birthdays appear on correct dates
- [ ] Birthday format: "[Child Name] ([Parent Name]'s child)"
- [ ] Click birthday links to parent profile
- [ ] Multiple children show multiple entries
- [ ] Birthday years calculated correctly (shows current age)

## Permission Testing

### 7. Access Control
- [ ] Profile owner can add children to own profile
- [ ] Profile owner can edit own children
- [ ] Profile owner can delete own children
- [ ] Other users cannot edit others' children
- [ ] Other users cannot delete others' children
- [ ] Admin users can manage all children
- [ ] Guest users cannot access forms

## Edge Case Testing

### 8. Data Validation
- [ ] Cannot submit without required fields
- [ ] Birth date must be valid date
- [ ] Birth date cannot be in future
- [ ] First/Last names handle special characters
- [ ] Names handle unicode (international names)
- [ ] Long names don't break layout

### 9. Relationship Testing
- [ ] Can select same user as both mother and father (edge case)
- [ ] Can change mother/father to null
- [ ] Deleted parent users don't break child records
- [ ] Child with deleted parent still shows in widget
- [ ] Calendar works with deleted parent references

## Integration Testing

### 10. Profile Stream Compatibility
- [ ] Works with profileDisableStream = true setting
- [ ] Widget displays correctly with/without stream
- [ ] No JavaScript errors in console
- [ ] No PHP errors in logs

### 11. Module Interactions
- [ ] Works with Advanced Profile module if installed
- [ ] Compatible with custom profile field modules
- [ ] Doesn't conflict with other sidebar widgets
- [ ] Calendar integration doesn't interfere with user birthdays

## Performance Testing

### 12. Load Testing
- [ ] Profile loads quickly with 0 children
- [ ] Profile loads quickly with 10+ children
- [ ] Calendar performs well with 100+ child birthdays
- [ ] Database queries optimized (check query count)

## Browser Testing

### 13. Cross-Browser Compatibility
- [ ] Chrome/Edge - all features work
- [ ] Firefox - all features work
- [ ] Safari - all features work
- [ ] Mobile browsers - responsive layout
- [ ] Date picker works in all browsers

## Cleanup Testing

### 14. Module Disabling
- [ ] Can disable module in admin panel
- [ ] Widget removed from profiles
- [ ] Calendar integration stops
- [ ] No errors after disabling

### 15. Module Uninstallation
- [ ] Data persists after disable (for re-enable)
- [ ] Optional: Clean uninstall removes child table

## Regression Testing

After any code changes, re-run:
- [ ] Installation testing (section 1)
- [ ] Creating children (section 2)
- [ ] Calendar integration (section 6)
- [ ] Access control (section 7)

## Automated Testing (Future)

Considerations for unit/integration tests:
- Child model validation tests
- Events::onBirthdayQuery() output tests
- Access control permission tests
- Widget rendering tests
File 4: CHANGELOG.md
Prompt for Copilot:

text
Create a CHANGELOG.md file following Keep a Changelog format:

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
File 5: module.json
Prompt for Copilot:

text
Create a HumHub module.json file for a "family" module with:
- id: family
- name: Family Management
- description: Manage family relationships and children profiles with birthday calendar integration for church and community use
- keywords: family, children, relationships, birthday, calendar, church
- version: 1.0.0
- humhub min version: 1.15
- humhub max version: 2.0
- authors: [your name/organization]
- homepage: [GitHub repo URL]
- namespace: humhub\modules\family
File 6: LICENSE
Prompt for Copilot:

text
Create a LICENSE file using AGPLv3 license (same as HumHub core).
Include standard GNU Affero General Public License v3.0 text.
Session 2: Module Core Classes (20-25 min)
File 7: Module.php
Prompt for Copilot:

text
Create a HumHub Module.php class in namespace humhub\modules\family that:
- Extends humhub\components\Module
- Has PHPDoc comments explaining the module purpose
- Implements install() method that runs migrations automatically
- Implements disable() method with proper cleanup
- Includes getConfigUrl() method returning null (no special config needed)
- References the HumHub module structure from documentation
- Includes version constant matching module.json
- Has proper error handling

Example structure:
/**
 * Family Management Module
 * 
 * Allows users to add children to their profiles without creating accounts,
 * with automatic birthday calendar integration.
 * 
 * @package humhub\modules\family
 */
File 8: config.php
Prompt for Copilot:

text
Create HumHub module config.php that includes comprehensive documentation:

<?php
/**
 * Family Module Configuration
 * 
 * This file defines:
 * - Event listeners for calendar birthday integration
 * - URL routing rules for child CRUD operations  
 * - Profile widget registration
 * - Module namespace and ID
 */

return [
    'id' => 'family',
    'class' => 'humhub\modules\family\Module',
    'namespace' => 'humhub\modules\family',
    
    // Event listeners
    'events' => [
        // Hook into calendar birthday queries to add children's birthdays
        [
            'class' => 'humhub\modules\calendar\interfaces\CalendarService',
            'event' => 'EVENT_GET_BIRTHDAYS',
            'callback' => ['humhub\modules\family\Events', 'onBirthdayQuery']
        ],
        
        // Register children widget on user profile sidebar
        [
            'class' => 'humhub\modules\user\widgets\ProfileSidebar',
            'event' => 'init',
            'callback' => ['humhub\modules\family\Events', 'onProfileSidebar']
        ],
    ],
    
    // URL routing rules
    'urlManagerRules' => [
        'family/child/create' => 'family/child/create',
        'family/child/edit' => 'family/child/edit',
        'family/child/delete' => 'family/child/delete',
    ],
];

Include detailed comments explaining each section.
Session 3: Database Layer (20-25 min)
File 9: migrations/m260206_220000_initial.php
Prompt for Copilot:

text
Create a comprehensive HumHub/Yii2 migration file with extensive PHPDoc documentation:

<?php
/**
 * Initial migration for Family Management module
 * 
 * Creates the 'child' table for storing children profiles linked to user accounts.
 * 
 * Table Structure:
 * - id: Primary key
 * - user_id: Parent user reference (required, FK to user table)
 * - first_name: Child's first name (required, max 100 chars)
 * - last_name: Child's last name (required, max 100 chars)
 * - birth_date: Date of birth (required)
 * - mother_id: Optional reference to user acting as mother (FK to user table)
 * - father_id: Optional reference to user acting as father (FK to user table)
 * - created_at: Record creation timestamp
 * - updated_at: Record update timestamp
 * 
 * Foreign Keys:
 * - user_id → user.id (CASCADE on delete - removes children if parent deleted)
 * - mother_id → user.id (SET NULL on delete - preserves child if mother deleted)
 * - father_id → user.id (SET NULL on delete - preserves child if father deleted)
 * 
 * Indexes:
 * - user_id for quick lookup of parent's children
 * - birth_date for birthday calendar queries
 * - mother_id and father_id for relationship queries
 */

Create the migration with:
- Proper use of $this->createTable()
- All field definitions with appropriate types
- Foreign key constraints with proper ON DELETE actions
- Indexes for performance
- Both up() and down() methods
- Comments explaining design decisions
File 10: models/Child.php
Prompt for Copilot:

text
Create a comprehensive Yii2 ActiveRecord model with extensive documentation:

<?php
namespace humhub\modules\family\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use humhub\modules\user\models\User;

/**
 * Child Model
 * 
 * Represents a child profile linked to a parent user.
 * Children do not have their own user accounts but appear in the parent's profile
 * and have their birthdays integrated into the calendar system.
 * 
 * @property int $id
 * @property int $user_id Parent user ID
 * @property string $first_name Child's first name
 * @property string $last_name Child's last name
 * @property string $birth_date Date of birth (Y-m-d format)
 * @property int|null $mother_id Optional mother user ID
 * @property int|null $father_id Optional father user ID
 * @property int $created_at Creation timestamp
 * @property int $updated_at Update timestamp
 * 
 * @property User $user Parent user relation
 * @property User|null $mother Mother user relation
 * @property User|null $father Father user relation
 */

Include:
- tableName() method
- rules() with validation:
  - first_name, last_name, birth_date required
  - first_name, last_name max 100 chars
  - birth_date must be valid date, not in future
  - user_id required, must exist in user table
  - mother_id, father_id optional, must exist if provided
- attributeLabels() for form display
- behaviors() with TimestampBehavior
- Relations: getUser(), getMother(), getFather()
- Helper methods:
  - getAge() - calculates current age from birth_date
  - getDisplayName() - returns "FirstName LastName"
  - getParentDisplayName() - returns parent's display name
- PHPDoc comments for all methods explaining purpose and return types
Session 4: Calendar Integration (20-25 min)
File 11: Events.php
Prompt for Copilot:

text
Create a comprehensive Events handler class with detailed documentation:

<?php
namespace humhub\modules\family;

use Yii;
use humhub\modules\family\models\Child;
use humhub\modules\family\widgets\ChildrenWidget;
use yii\base\Event;

/**
 * Event Handlers for Family Module
 * 
 * This class contains static methods that respond to HumHub events:
 * 1. Calendar birthday queries - injects children's birthdays
 * 2. Profile sidebar initialization - adds children widget
 */
class Events
{
    /**
     * Handle calendar birthday query event
     * 
     * Called by the Calendar module when collecting birthdays to display.
     * This method:
     * 1. Queries all children with birthdays
     * 2. Formats each child's birthday as a calendar entry
     * 3. Links birthday to parent's profile
     * 4. Appends results to the calendar event
     * 
     * Birthday Entry Format:
     * - Title: "[Child Name] ([Parent Name]'s child)"
     * - Date: Child's birth_date (recurring annually)
     * - Link: Parent's profile URL
     * - Type: Birthday event type
     * 
     * @param Event $event Calendar birthday query event
     * @return void
     */
    public static function onBirthdayQuery($event)
    {
        // Implementation with detailed inline comments
    }
    
    /**
     * Handle profile sidebar initialization
     * 
     * Adds the ChildrenWidget to user profile sidebars.
     * Only displays on user profiles (not spaces).
     * 
     * @param Event $event Profile sidebar init event
     * @return void
     */
    public static function onProfileSidebar($event)
    {
        // Implementation with comments
    }
}

Create complete implementation matching BirthdayCalendarQuery.php pattern.
Include error handling and null checks.
Add comments explaining calendar data structure.
Session 5: Profile Widget (25-30 min)
File 12: widgets/ChildrenWidget.php
Prompt for Copilot:

text
Create a comprehensive widget class with full documentation:

<?php
namespace humhub\modules\family\widgets;

use Yii;
use humhub\components\Widget;
use humhub\modules\family\models\Child;
use humhub\modules\user\models\User;

/**
 * Children Profile Widget
 * 
 * Displays a list of children on user profiles with management controls.
 * 
 * Features:
 * - Shows all children for the profile owner
 * - Displays child name, age, and birthdate
 * - Shows mother/father links when specified
 * - Provides Add/Edit/Delete controls for authorized users
 * - Handles empty state gracefully
 * 
 * Display Rules:
 * - Always visible to profile owner (can add children)
 * - Visible to others if profile has children
 * - Only shows on user profiles, not spaces
 * - Edit/Delete controls only for owner or admins
 * 
 * @property User $user The profile owner
 */

Include:
- $user property with getter/setter
- run() method that:
  - Checks if viewing user profile (not space)
  - Queries children for $user->id
  - Determines if current user can edit (owner or admin)
  - Renders view with data
- PHPDoc comments explaining logic
- Error handling for invalid user
File 13: views/widgets/children.php
Prompt for Copilot:

text
Create a comprehensive widget view with inline documentation:

<?php
/**
 * Children Widget View
 * 
 * Displays list of children in profile sidebar panel.
 * 
 * Available variables:
 * @var $children Child[] Array of child models
 * @var $user User Profile owner
 * @var $canEdit bool Whether current user can manage children
 * 
 * Display format per child:
 * - Name (calculated age) - Birthdate
 * - Mother: [linked name] (if set)
 * - Father: [linked name] (if set)
 * - [Edit] [Delete] icons (if canEdit)
 */

use humhub\modules\family\models\Child;
use humhub\modules\user\models\User;
use humhub\widgets\PanelMenu;
use yii\helpers\Html;
use yii\helpers\Url;

Create view with:
- HumHub panel/card structure using proper classes
- Panel heading "Children" with count badge
- List-group for children with proper styling
- Each child item showing:
  - Full name in bold
  - Age (e.g., "8 years old") calculated from birth_date
  - Formatted birthdate
  - Mother link (Html::a to mother's profile) - only if mother_id set
  - Father link (Html::a to father's profile) - only if father_id set
- Edit/Delete action buttons using FontAwesome icons (if canEdit)
- Delete with JavaScript confirmation
- "Add Child" button at bottom (if canEdit)
- Empty state message: "No children added yet." with muted styling
- Responsive layout
- Accessibility: proper aria-labels
- Include inline comments explaining structure
Session 6: CRUD Controller (25-30 min)
File 14: controllers/ChildController.php
Prompt for Copilot:

text
Create a comprehensive controller with extensive documentation:

<?php
namespace humhub\modules\family\controllers;

use Yii;
use humhub\components\Controller;
use humhub\modules\family\models\Child;
use humhub\modules\user\models\User;
use yii\web\NotFoundHttpException;
use yii\web\ForbiddenHttpException;

/**
 * Child Controller
 * 
 * Handles CRUD operations for child profiles.
 * 
 * Actions:
 * - create: Add new child to current user's profile
 * - edit: Modify existing child (owner/admin only)
 * - delete: Remove child (owner/admin only)
 * 
 * Access Control:
 * - Users can only manage children on their own profile
 * - Administrators can manage all children
 * - Guests cannot access any actions
 * 
 * After successful operations, redirects to profile with flash message.
 */

Include:
- behaviors() with AccessControl:
  - All actions require login
  - actions ['create','edit','delete'] check permissions
  
- actionCreate():
  - Creates new Child model
  - Sets user_id to current user
  - Loads POST data
  - Validates and saves
  - Flash success message
  - Redirects to user profile
  - On error, renders create view with errors
  
- actionEdit($id):
  - Finds child by ID
  - Checks authorization (canEdit method)
  - Loads POST data
  - Validates and saves
  - Flash success message
  - Redirects to profile
  - Throws 404 if not found, 403 if not authorized
  
- actionDelete($id):
  - Finds child by ID
  - Checks authorization
  - Deletes record
  - Flash success message
  - Redirects to profile
  - Throws 404 if not found, 403 if not authorized
  
- Protected method canEdit($child):
  - Returns true if current user is owner OR admin
  - Helper for access control
  
- Protected method findModel($id):
  - Finds child by ID
  - Throws NotFoundHttpException if not found
  - Returns Child model

Include extensive PHPDoc comments and inline error handling comments.
Add logging for important operations.
Session 7: Forms and Views (30-35 min)
File 15: views/child/_form.php
Prompt for Copilot:

text
Create a comprehensive form partial with full documentation:

<?php
/**
 * Child Form Partial
 * 
 * Shared form used by both create.php and edit.php views.
 * 
 * Available variables:
 * @var $model Child The child model (new or existing)
 * @var $form ActiveForm The form widget instance
 * 
 * Form Fields:
 * - first_name: Text input (required)
 * - last_name: Text input (required)
 * - birth_date: Date picker (required, cannot be future date)
 * - mother_id: Dropdown of female users (optional, with "Not specified" option)
 * - father_id: Dropdown of male users (optional, with "Not specified" option)
 * 
 * Note: Mother/Father dropdowns could be all users or filtered by gender
 * depending on your user profile fields setup.
 */

use humhub\modules\family\models\Child;
use humhub\modules\user\models\User;
use humhub\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;

Create form with:
- ActiveForm widget with HumHub styling
- Text fields for first_name and last_name with proper attributes
- Date input for birth_date:
  - Use HTML5 date input or HumHub date picker
  - Set max date to today (no future dates)
  - Default format: Y-m-d
- Dropdowns for mother_id and father_id:
  - Populated from User::find()->all()
  - Items: ArrayHelper::map(users, 'id', 'displayName')
  - Prompt text: "-- Not specified --"
  - Allow clearing selection
- Submit button "Save Child" with primary styling
- Cancel link back to profile: Url::toRoute(['/user/profile', 'uguid' => Yii::$app->user->identity->guid])
- Proper field hints/help text
- Bootstrap/HumHub form styling classes
- Include inline comments explaining choices
File 16: views/child/create.php
Prompt for Copilot:

text
Create comprehensive create view with documentation:

<?php
/**
 * Create Child View
 * 
 * Allows users to add a new child to their profile.
 * 
 * @var $model Child New child model instance
 */

use humhub\modules\family\models\Child;
use humhub\widgets\GridView;
use yii\helpers\Html;

Create view with:
- Page title: "Add Child"
- Breadcrumbs showing: Home > Profile > Add Child
- HumHub panel/card wrapper with proper classes
- Panel heading "Add Child to Your Profile"
- Panel body containing:
  - Optional help text explaining purpose
  - Render of _form.php partial
- Use HumHub layout components
- Include comments explaining structure
File 17: views/child/edit.php
Prompt for Copilot:

text
Create comprehensive edit view with documentation:

<?php
/**
 * Edit Child View
 * 
 * Allows authorized users to modify child information.
 * 
 * @var $model Child Existing child model instance
 */

use humhub\modules\family\models\Child;
use yii\helpers\Html;

Create view with:
- Dynamic page title: "Edit Child: {model->displayName}"
- Breadcrumbs: Home > Profile > Children > Edit
- HumHub panel wrapper
- Panel heading showing child's current name
- Panel body containing:
  - Optional warning about changing birth date (affects calendar)
  - Render of _form.php partial
- Delete button option (requires confirmation)
- Use HumHub styling
- Include comments
Session 8: Permissions (Optional but Recommended) (15-20 min)
File 18: permissions/ManageFamily.php
Prompt for Copilot:

text
Create a permission class for future granular control:

<?php
namespace humhub\modules\family\permissions;

use humhub\modules\user\models\User;

/**
 * ManageFamily Permission
 * 
 * Controls who can manage family/children profiles.
 * 
 * Default: All users can manage their own children.
 * Can be restricted via group permissions in future versions.
 * 
 * Potential future use cases:
 * - Restrict feature to certain user groups
 * - Allow space-level family management
 * - Enable/disable per-profile basis
 */
class ManageFamily extends \humhub\libs\BasePermission
{
    /**
     * @inheritdoc
     */
    public $defaultState = self::STATE_ALLOW;

    /**
     * @inheritdoc
     */
    protected $fixedGroups = [
        User::USERGROUP_USER
    ];

    /**
     * @inheritdoc
     */
    protected $title = "Manage Family";

    /**
     * @inheritdoc
     */
    protected $description = "Allows users to add and manage children on their profiles";

    /**
     * @inheritdoc
     */
    protected $moduleId = 'family';
}

Include detailed comments about when and how this could be used.
Session 9: Configuration Examples (10-15 min)
File 19: docs/CONFIGURATION.md
Prompt for Copilot:

text
Create a configuration guide for advanced use cases:

# Configuration Guide

## Basic Setup

The Family module works out of the box with no configuration required.
Simply install and enable the module as described in README.md.

## Advanced Configuration

### Disabling Profile Streams

If you want to disable profile activity streams while keeping space streams
(common for church/community setups), add to `protected/config/common.php`:

```php
'modules' => [
    'user' => [
        'profileDisableStream' => true
    ],
    'family' => [
        'class' => 'humhub\modules\family\Module',
    ],
],
Customizing Widget Visibility
To control where the Children widget appears, modify config.php:

php
// Example: Only show on specific user groups
'events' => [
    [
        'class' => 'humhub\modules\user\widgets\ProfileSidebar',
        'event' => 'init',
        'callback' => function($event) {
            $user = $event->sender->user;
            // Only show for specific group
            if ($user->isInGroup('Members')) {
                $event->sender->addWidget(
                    \humhub\modules\family\widgets\ChildrenWidget::class,
                    ['user' => $user]
                );
            }
        }
    ],
],
Calendar Integration Settings
The module automatically integrates with the Calendar module.
To customize birthday display format, extend Events.php.

Database Configuration
The module uses standard HumHub database connection.
For high-traffic sites, consider adding indexes:

sql
CREATE INDEX idx_child_birthdate ON child (birth_date);
CREATE INDEX idx_child_user_id ON child (user_id);
These are included in the migration by default.

Performance Optimization
For installations with thousands of users:

Enable HumHub caching (see HumHub docs)

Consider pagination for children lists (if users have many children)

Database query optimization is built-in

Custom Fields
To add custom fields (e.g., school, grade):

Create new migration adding columns

Update Child model rules() and attributeLabels()

Update _form.php to include new fields

Update children.php widget view to display new fields

Example migration:

php
$this->addColumn('child', 'school', 'VARCHAR(100) NULL');
$this->addColumn('child', 'grade', 'VARCHAR(20) NULL');
Internationalization
To add translations:

Create messages/[language]/base.php

Add translation strings

Use Yii::t('FamilyModule.base', 'string') in code

Integration with Other Modules
The Family module is designed to work alongside:

Calendar module (required for birthday integration)

Advanced Profile module (compatible)

Custom Pages module (compatible)

Mail module (future: birthday notifications)

See DEVELOPMENT.md for extending functionality.

text

***

## Session 10: API Documentation (Optional) (15 min)

### File 20: `docs/API.md`
**Prompt for Copilot:**
Create API documentation for developers extending the module:

API Documentation
Models
Child Model
Class: humhub\modules\family\models\Child

Properties:

id (int): Primary key

user_id (int): Parent user ID

first_name (string): Child's first name

last_name (string): Child's last name

birth_date (string): Date of birth (Y-m-d format)

mother_id (int|null): Optional mother user ID

father_id (int|null): Optional father user ID

Relations:

user: Returns parent User model

mother: Returns mother User model or null

father: Returns father User model or null

Methods:

getAge()
Returns the child's current age in years.

php
$child = Child::findOne(1);
echo $child->getAge(); // Returns: 8
getDisplayName()
Returns formatted full name.

php
echo $child->getDisplayName(); // Returns: "John Smith"
getParentDisplayName()
Returns parent's display name.

php
echo $child->getParentDisplayName(); // Returns: "Jane Smith"
Usage Examples:

php
// Create new child
$child = new Child();
$child->user_id = Yii::$app->user->id;
$child->first_name = 'John';
$child->last_name = 'Smith';
$child->birth_date = '2015-03-15';
$child->save();

// Query children for user
$children = Child::findAll(['user_id' => $userId]);

// Get children with mothers
$children = Child::find()
    ->where(['user_id' => $userId])
    ->andWhere('mother_id IS NOT NULL')
    ->all();

// Delete all children for user
Child::deleteAll(['user_id' => $userId]);
Events
Calendar Birthday Event
Event: humhub\modules\calendar\interfaces\CalendarService::EVENT_GET_BIRTHDAYS

Handler: humhub\modules\family\Events::onBirthdayQuery

Purpose: Injects children's birthdays into calendar

Event Data Structure:

php
$event->result[] = [
    'title' => 'John Smith (Jane Smith\'s child)',
    'start' => '2026-03-15',
    'allDay' => true,
    'url' => '/user/profile?uguid=xxx',
    'className' => 'calendar-birthday',
];
Profile Sidebar Event
Event: humhub\modules\user\widgets\ProfileSidebar::init

Handler: humhub\modules\family\Events::onProfileSidebar

Purpose: Adds ChildrenWidget to profile sidebar

Widgets
ChildrenWidget
Class: humhub\modules\family\widgets\ChildrenWidget

Usage:

php
echo \humhub\modules\family\widgets\ChildrenWidget::widget([
    'user' => $user
]);
Properties:

user (User): The profile owner

Controllers
ChildController
Class: humhub\modules\family\controllers\ChildController

Actions:

create: Add new child

edit?id={id}: Edit existing child

delete?id={id}: Delete child

URL Examples:

text
/family/child/create
/family/child/edit?id=5
/family/child/delete?id=5
Database Schema
child Table
sql
CREATE TABLE `child` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `birth_date` date NOT NULL,
  `mother_id` int(11) DEFAULT NULL,
  `father_id` int(11) DEFAULT NULL,
  `created_at` int(11) DEFAULT NULL,
  `updated_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_user_id` (`user_id`),
  KEY `idx_birth_date` (`birth_date`),
  KEY `idx_mother_id` (`mother_id`),
  KEY `idx_father_id` (`father_id`),
  CONSTRAINT `fk_child_user` FOREIGN KEY (`user_id`) 
    REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_child_mother` FOREIGN KEY (`mother_id`) 
    REFERENCES `user` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_child_father` FOREIGN KEY (`father_id`) 
    REFERENCES `user` (`id`) ON DELETE SET NULL
);
Extension Points
Adding Custom Fields
Extend Child model and update form:

php
// In Child.php
public function rules() {
    return array_merge(parent::rules(), [
        ['school', 'string', 'max' => 100],
        ['grade', 'string', 'max' => 20],
    ]);
}

// In _form.php
<?= $form->field($model, 'school')->textInput() ?>
<?= $form->field($model, 'grade')->textInput() ?>
Custom Birthday Format
Override Events::onBirthdayQuery():

php
// Custom title format
$title = sprintf(
    '%s (%d years old) - %s\'s child',
    $child->getDisplayName(),
    $child->getAge(),
    $child->getParentDisplayName()
);
Permissions
Use ManageFamily permission for access control:

php
use humhub\modules\family\permissions\ManageFamily;

if ($user->can(new ManageFamily())) {
    // User can manage family
}
text

***

## Session 11: Example Use Cases (10 min)

### File 21: `docs/EXAMPLES.md`
**Prompt for Copilot:**
Create practical examples document:

Use Case Examples
Church Community Management
Scenario 1: Member Directory with Children
Goal: Display all families with children in a church directory

Implementation:

php
// In custom controller
$families = User::find()
    ->joinWith('children')
    ->where(['not', ['child.id' => null]])
    ->all();

foreach ($families as $family) {
    echo $family->displayName . " Family:\n";
    foreach ($family->children as $child) {
        echo "- " . $child->getDisplayName() . 
             " (Age: " . $child->getAge() . ")\n";
    }
}
Scenario 2: Birthday Reminder Emails
Goal: Send weekly email with upcoming children's birthdays

Implementation:

php
// In cron job or scheduled task
$nextWeek = date('Y-m-d', strtotime('+7 days'));
$today = date('Y-m-d');

$upcomingBirthdays = Child::find()
    ->where([
        'between', 
        'DATE_FORMAT(birth_date, "%m-%d")',
        date('m-d'),
        date('m-d', strtotime('+7 days'))
    ])
    ->all();

foreach ($upcomingBirthdays as $child) {
    // Send email to parent
    Yii::$app->mailer->compose()
        ->setTo($child->user->email)
        ->setSubject('Upcoming Birthday Reminder')
        ->setTextBody("Reminder: {$child->getDisplayName()}'s 
                       birthday is on {$child->birth_date}")
        ->send();
}
Scenario 3: Children's Ministry Roster
Goal: Generate age-grouped lists for Sunday school

Implementation:

php
// Group children by age ranges
$infants = Child::find()
    ->where(['between', 
             'TIMESTAMPDIFF(YEAR, birth_date, CURDATE())', 
             0, 2])
    ->all();

$preschool = Child::find()
    ->where(['between', 
             'TIMESTAMPDIFF(YEAR, birth_date, CURDATE())', 
             3, 5])
    ->all();

$elementary = Child::find()
    ->where(['between', 
             'TIMESTAMPDIFF(YEAR, birth_date, CURDATE())', 
             6, 11])
    ->all();

$youth = Child::find()
    ->where(['between', 
             'TIMESTAMPDIFF(YEAR, birth_date, CURDATE())', 
             12, 18])
    ->all();
Community Organization
Scenario 4: Family Event Planning
Goal: Create family-friendly events based on children's ages

Implementation:

php
// Find families with children in specific age range
$familiesWithYoungKids = User::find()
    ->joinWith(['children' => function($query) {
        $query->where([
            'between',
            'TIMESTAMPDIFF(YEAR, birth_date, CURDATE())',
            3, 8
        ]);
    }])
    ->all();

// Invite these families to age-appropriate event
foreach ($familiesWithYoungKids as $parent) {
    // Send invitation
}
Scenario 5: Parent Connections
Goal: Connect parents with children of similar ages

Implementation:

php
// Find other parents with kids within 2 years age range
$myChildren = Child::findAll(['user_id' => $currentUserId]);

foreach ($myChildren as $myChild) {
    $similarAgeKids = Child::find()
        ->where([
            'between',
            'TIMESTAMPDIFF(YEAR, birth_date, CURDATE())',
            $myChild->getAge() - 2,
            $myChild->getAge() + 2
        ])
        ->andWhere(['!=', 'user_id', $currentUserId])
        ->all();
    
    // Suggest connections
}
Custom Widgets
Scenario 6: Dashboard Birthday Widget
Goal: Show upcoming birthdays on dashboard

Implementation:

php
// Create custom widget
class UpcomingBirthdaysWidget extends Widget
{
    public function run()
    {
        $birthdays = Child::find()
            ->where([
                'between',
                'DATE_FORMAT(birth_date, "%m-%d")',
                date('m-d'),
                date('m-d', strtotime('+30 days'))
            ])
            ->orderBy('DATE_FORMAT(birth_date, "%m-%d")')
            ->limit(10)
            ->all();
        
        return $this->render('upcoming-birthdays', [
            'birthdays' => $birthdays
        ]);
    }
}
Reports
Scenario 7: Member Statistics Report
Goal: Generate demographics report

Implementation:

php
// Statistics
$totalFamilies = User::find()
    ->joinWith('children')
    ->distinct()
    ->count();

$totalChildren = Child::find()->count();

$avgChildrenPerFamily = $totalChildren / $totalFamilies;

$ageDistribution = Child::find()
    ->select([
        'TIMESTAMPDIFF(YEAR, birth_date, CURDATE()) as age',
        'COUNT(*) as count'
    ])
    ->groupBy('age')
    ->asArray()
    ->all();
Scenario 8: Export Family Data
Goal: Export to CSV for external systems

Implementation:

php
// CSV export
header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="families.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, [
    'Parent Name', 
    'Child Name', 
    'Age', 
    'Birth Date'
]);

$children = Child::find()
    ->with(['user', 'mother', 'father'])
    ->all();

foreach ($children as $child) {
    fputcsv($output, [
        $child->getParentDisplayName(),
        $child->getDisplayName(),
        $child->getAge(),
        $child->birth_date
    ]);
}

fclose($output);
Integration Examples
Scenario 9: Space-Level Family Management
Goal: Allow families to be managed at space/group level

Future Implementation:

php
// Extend Child model to support spaces
$this->addColumn('child', 'space_id', 'INT NULL');
$this->addForeignKey(
    'fk_child_space',
    'child',
    'space_id',
    'space',
    'id',
    'CASCADE'
);

// Query children in space
$spaceChildren = Child::findAll(['space_id' => $spaceId]);
Scenario 10: Photo Galleries
Goal: Link children to photo albums

Future Implementation:

php
// Integration with gallery module
$child->addPhoto($photoFile);
$photos = $child->getPhotos()->all();
See DEVELOPMENT.md for extending these examples.

text

***

## Post-Development Tasks

### Final Documentation Review
Create file: `docs/DEPLOYMENT.md`

**Prompt for Copilot:**
Create deployment checklist for production use:

Deployment Guide
Pre-Deployment Checklist
Code Review
 All files have proper PHPDoc comments

 No debug code or var_dump() statements

 Error handling implemented throughout

 SQL injection prevention verified (using parameterized queries)

 XSS prevention verified (using Html::encode())

 CSRF protection enabled (Yii2 default)

Testing
 Complete TESTING.md checklist

 Test with production-like data volume

 Cross-browser testing complete

 Mobile responsiveness verified

 Performance testing passed

Security
 Access control verified

 Permissions properly enforced

 No sensitive data in logs

 Database backups configured

Deployment Steps
1. Backup Production
bash
# Backup database
mysqldump -u user -p humhub_db > backup_$(date +%Y%m%d).sql

# Backup files
tar -czf humhub_backup_$(date +%Y%m%d).tar.gz /path/to/humhub
2. Install Module
bash
# Upload module to server
scp -r family/ user@server:/path/to/humhub/protected/modules/

# Set proper permissions
chmod -R 755 /path/to/humhub/protected/modules/family
chown -R www-data:www-data /path/to/humhub/protected/modules/family
3. Enable Module
Log in as administrator

Navigate to Administration → Modules

Find "Family Management"

Click "Enable"

Verify migration runs successfully

4. Verify Installation
 Check PHP error log for issues

 Test adding a child

 Verify calendar integration

 Test edit/delete operations

 Confirm widget displays correctly

5. Configure (Optional)
Set any custom config options in common.php

Adjust permissions if needed

Configure cron jobs for birthday reminders (future feature)

Post-Deployment
Monitoring
Monitor error logs: /path/to/humhub/protected/runtime/logs/

Check database performance

Watch for user-reported issues

User Communication
Announce new feature to users

Provide usage instructions

Collect feedback

Maintenance
Regular database backups

Monitor module updates

Plan future enhancements based on feedback

Rollback Plan
If issues occur:

bash
# Disable module in admin panel first

# Or remove from database
mysql -u user -p humhub_db
DELETE FROM module_enabled WHERE module_id = 'family';

# Restore backup if needed
mysql -u user -p humhub_db < backup_YYYYMMDD.sql
Support Resources
GitHub Issues: [repo URL]

HumHub Community Forum

Module documentation in docs/

text

***

## Summary of Deliverables

Your GitHub repository will contain:

humhub-family-module/
├── README.md # Main documentation
├── DEVELOPMENT.md # Technical architecture
├── TESTING.md # Testing procedures
├── CHANGELOG.md # Version history
├── LICENSE # AGPLv3 license
├── .gitignore # Git ignore rules
├── module.json # Module metadata
├── Module.php # Main module class
├── config.php # Module configuration
├── Events.php # Event handlers
├── models/
│ └── Child.php # Child model
├── migrations/
│ └── m260206_220000_initial.php # Database migration
├── controllers/
│ └── ChildController.php # CRUD controller
├── views/
│ ├── child/
│ │ ├── create.php # Create view
│ │ ├── edit.php # Edit view
│ │ └── _form.php # Form partial
│ └── widgets/
│ └── children.php # Widget view
├── widgets/
│ └── ChildrenWidget.php # Profile widget
├── permissions/
│ └── ManageFamily.php # Permission class
└── docs/
├── CONFIGURATION.md # Configuration guide
├── API.md # API documentation
├── EXAMPLES.md # Use case examples
└── DEPLOYMENT.md # Deployment guide

text

## Estimated Timeline
- **Documentation & Setup:** 1 hour
- **Core Development:** 2-3 hours
- **Testing & Refinement:** 1-2 hours
- **Documentation Review:** 30 minutes
- **Total:** 4.5-6.5 hours
