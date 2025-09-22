# REDAXO 5 "Neues" Addon - News Management System

**ALWAYS reference these instructions first and fallback to search or bash commands only when you encounter unexpected information that does not match the info here.**

## Overview

"Neues" is a news management addon for REDAXO 5 CMS built with YForm/YOrm. It provides news article management in the backend and frontend display via fragments. Supports multilingual websites, multidomains, RSS feeds, and RESTful API endpoints.

## Working Effectively

### Bootstrap the Development Environment
- **Composer Install**: `composer install --no-interaction` - takes ~8 seconds, downloads 35 packages. May show GitHub authentication warnings (safe to ignore).
- **PHP Requirements**: PHP 8.2+, tested with PHP 8.3.6
- **REDAXO Dependencies**: Requires REDAXO 5.18.3+, YForm 4.1.1+, yform_field 2.11.0+

### Code Quality and Validation
- **PHP Syntax Check**: `php -l <filename>` - takes <0.1 seconds per file
- **Check All PHP Files**: `find . -name "*.php" -not -path "./vendor/*" -exec php -l {} \;` - takes ~1.1 seconds for 26 files
- **PHP CS Fixer (Dry Run)**: `composer cs-dry` - takes ~0.7 seconds, checks 26 files
- **PHP CS Fixer (Fix)**: `composer cs-fix` - takes ~0.7 seconds, fixes code style issues
- **ALWAYS run `composer cs-fix` before committing** - required by CI (.github/workflows/code-style.yml)

### Project Structure
```
/home/runner/work/neues/neues/
├── .github/workflows/          # CI/CD (code-style.yml, publish-to-redaxo-org.yml)
├── docs/                      # Complete documentation in German
├── fragments/neues/           # Frontend display templates (Bootstrap 5)
├── install/                   # Database schema, cronjobs, URL profiles
├── lib/                       # Core classes (Entry, Category, Author, APIs)
├── pages/                     # REDAXO backend pages
├── boot.php                   # Addon initialization
├── composer.json              # Only dev dependencies (PHP CS Fixer)
├── package.yml                # REDAXO addon configuration
└── install.php                # Installation/update logic
```

## Core Functionality

### Database Models (YOrm)
- **Entry**: News articles with status (DELETED=-2, DRAFT=-1, PLANNED=0, ONLINE=1)
- **Category**: News categories for organization
- **Author**: News authors/editors
- **EntryLang**: Multilingual content support

### Frontend Display System
- **Fragment-based rendering**: Templates in `fragments/neues/`
- **entry.php**: Single article view with images, schema.org markup
- **list.php**: Article list with pagination
- **list-entry.php**: Individual list item template
- **pagination.php**: Pagination controls
- **Bootstrap 5 compatible** out of the box

### API Endpoints
- **RSS Feed**: `index.php?rex-api-call=neues_rss`
  - Filter by category: `&category_id=3`
  - Filter by language: `&lang_id=2`
  - Filter by domain: `&domain_id=1`
- **REST API** (if YForm REST plugin enabled):
  - `/neues/entry/5.0.0/` - News entries
  - `/neues/category/5.0.0/` - Categories  
  - `/neues/author/5.0.0/` - Authors

## Testing and Validation

### Manual Validation Scenarios
Since there are **NO automated tests**, you MUST manually validate:

1. **PHP Syntax Validation**:
   ```bash
   find . -name "*.php" -not -path "./vendor/*" -exec php -l {} \;
   ```

2. **Code Style Validation**:
   ```bash
   composer cs-dry  # Check only
   composer cs-fix  # Fix issues
   ```

3. **Fragment Functionality** (requires REDAXO installation):
   - Verify news entry display renders correctly
   - Test news list pagination
   - Validate RSS feed XML output
   - Check REST API endpoints return valid JSON

4. **Database Schema Validation** (requires REDAXO):
   - Install addon in REDAXO backend
   - Create test news entries, categories, authors
   - Verify multilingual functionality
   - Test URL profile integration

### Common Development Tasks

#### Adding New Features
1. **Model Changes**: Edit classes in `lib/` (Entry.php, Category.php, Author.php)
2. **Frontend Changes**: Modify fragments in `fragments/neues/`
3. **Database Changes**: Update `install/update_scheme.php` and `install/tableset.json`
4. **API Changes**: Modify `lib/Api/Restful.php` or `lib/Api/Rss.php`

#### Debugging
- **Check REDAXO logs**: Look in REDAXO's error logs
- **PHP error_log**: Monitor PHP error logs during development
- **YForm debugging**: Use YForm's built-in debugging features

## CRITICAL Development Guidelines

### NEVER CANCEL Commands
- Build operations are fast (typically <10 seconds)
- **Composer install**: typically ~8 seconds, max 15 seconds with network delays
- **PHP CS Fixer**: typically ~0.7 seconds, max 2 seconds for full check/fix

### File Locations for Customization
- **Theme-based**: `theme/private/fragments/neues/` (overrides addon fragments)
- **Project-based**: `redaxo/addons/project/fragments/neues/` (overrides addon fragments)

### Integration Points
- **URL2 Addon**: URL profiles for SEO-friendly URLs (see `docs/06_url.md`)
- **YRewrite**: Multi-domain support
- **Cronjobs**: Auto-publishing, external sync (if cronjob addon available)

## Common Outputs and References

### Repository Root Structure
```
boot.php                    # Addon bootstrap
composer.json              # Dev dependencies only
package.yml                 # REDAXO addon config
install.php                # Installation logic (4154 bytes)
fragments/neues/           # 5 frontend template files
lib/                       # 7 core PHP classes
docs/                      # 11 documentation files (German)
pages/                     # 4 backend interface files
```

### Dependencies from package.yml
```yaml
requires:
    php: '>8.2,<9'
    redaxo: '^5.18.3'
    packages:
        yform: '>4.1.1,<6'
        yform_field: ">=2.11.0,<4"
```

### Composer Scripts Available
```json
"cs-dry": "php-cs-fixer fix -v --ansi --dry-run --config=.php-cs-fixer.dist.php"
"cs-fix": "php-cs-fixer fix -v --ansi --config=.php-cs-fixer.dist.php"
```

## Installation Context

This is a **REDAXO 5 addon**, not a standalone application. It:
- Cannot be "run" independently
- Requires full REDAXO 5 installation to function
- Integrates with REDAXO's backend interface
- Uses REDAXO's database and user management
- Extends REDAXO with news management functionality

**For development without full REDAXO**: Focus on code quality, syntax validation, and fragment template development. Full functional testing requires REDAXO installation.