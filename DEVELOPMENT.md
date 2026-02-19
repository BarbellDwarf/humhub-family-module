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
│ ├── m260206_220000_initial.php # Database schema
│ └── m260214_220000_child_user_account.php # Child account linking update
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

## Database Schema

### child table
- `id` - Primary key
- `user_id` - Parent user (FK to user table)
- `first_name` - Child's first name
- `last_name` - Child's last name  
- `birth_date` - Child's date of birth (nullable when linked to an account)
- `child_user_id` - Optional FK to user table (linked child account)
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
