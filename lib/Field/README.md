# Custom YForm Fields for Neues AddOn

This addon provides custom YForm field types that were previously part of the yform_field addon.

## Field Types

### domain
- **Purpose**: Domain selection from YRewrite domains
- **Usage**: `domain|name|label|multiple|size`
- **Description**: Provides a select field with all available YRewrite domains
- **Database**: varchar(191) or text for multiple selections

### datetime_local  
- **Purpose**: HTML5 datetime-local input
- **Usage**: `datetime_local|name|label|current_date|min|max`
- **Description**: Modern datetime input with browser native controls
- **Database**: datetime
- **Features**:
  - Automatic conversion between HTML5 format and database format
  - Optional current date pre-filling
  - Min/max date validation

### choice_status
- **Purpose**: Status selection with visual indicators  
- **Usage**: `choice_status|name|label|choices|[options...]`
- **Description**: Enhanced choice field with color-coded status display
- **Database**: int
- **Features**:
  - Color-coded display in list views
  - Special styling for online/offline/draft states
  - Inherits all choice field functionality

## Integration

The fields are automatically registered when the addon is loaded. Templates are provided for Bootstrap 3 styling.

## Migration from yform_field

This addon conflicts with yform_field addon to prevent compatibility issues. The custom fields provide the same functionality as the original yform_field types but are maintained as part of the neues addon.

## Styling

Custom CSS is provided in `assets/neues-fields.css` for proper field styling and status indicators.