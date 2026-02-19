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
- [ ] Family tab renders inside profile layout (profile header/menu visible)
- [ ] "Children" widget appears in sidebar
- [ ] Click "Add Child" opens create form
- [ ] Required fields validated when no account is linked (first_name, last_name, birth_date)
- [ ] Birth date picker works correctly
- [ ] Child account picker populated with users
- [ ] Can leave child account blank and enter manual details
- [ ] Selecting a child account syncs name and birthday from profile
- [ ] Relation type dropdown available with expected options
- [ ] Submit creates child record
- [ ] Redirects back to profile
- [ ] Success message displayed
- [ ] Child appears in widget

### 3. Editing Children
- [ ] Edit icon appears next to child name
- [ ] Click edit opens form with existing data
- [ ] Can modify manual fields when no account is linked
- [ ] Can change or clear linked child account
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
- [ ] Linked child names are clickable profile links
- [ ] Relation type label shown for non-child relations
- [ ] Action buttons remain visible in dark mode
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

### 9. Account Linking Testing
- [ ] Can link an existing user account to a child
- [ ] Can unlink an account and enter manual details
- [ ] Deleted child user accounts don't break child records
- [ ] Calendar works when linked account has no birthday

## Integration Testing

### 10. Profile Stream Compatibility
- [ ] Works with profileDisableStream = true setting
- [ ] Widget displays correctly with/without stream
- [ ] No JavaScript errors in console
- [ ] No PHP errors in logs

### 11. Family Diagram Section
- [ ] Enable “Family Diagram” in module configuration
- [ ] Diagram section appears in the Family profile tab
- [ ] Parents render correctly in diagram
- [ ] Children render correctly in diagram
- [ ] Grandchildren render for linked child accounts
- [ ] Direct grandchildren (relation type = Grandchild) render in diagram

### 12. Module Interactions
- [ ] Works with Advanced Profile module if installed
- [ ] Compatible with custom profile field modules
- [ ] Doesn't conflict with other sidebar widgets
- [ ] Calendar integration doesn't interfere with user birthdays

## Performance Testing

### 13. Load Testing
- [ ] Profile loads quickly with 0 children
- [ ] Profile loads quickly with 10+ children
- [ ] Calendar performs well with 100+ child birthdays
- [ ] Database queries optimized (check query count)

## Browser Testing

### 14. Cross-Browser Compatibility
- [ ] Chrome/Edge - all features work
- [ ] Firefox - all features work
- [ ] Safari - all features work
- [ ] Mobile browsers - responsive layout
- [ ] Date picker works in all browsers

## Cleanup Testing

### 15. Module Disabling
- [ ] Can disable module in admin panel
- [ ] Widget removed from profiles
- [ ] Calendar integration stops
- [ ] No errors after disabling

### 16. Module Uninstallation
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
