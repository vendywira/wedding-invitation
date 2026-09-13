# Wedding Invitation Template System - Design Spec

## Overview

Multi-tenant template system allowing admins to switch between completely different wedding invitation UIs while sharing the same data layer (Event, Guest, Message).

## Goals

- Multiple independent templates with different layouts, colors, fonts, animations
- Admin can activate/deactivate templates
- Section ordering and visibility configurable per template
- Custom assets (audio, video, images) per template
- All templates consume same data contract from database

## Non-Goals

- Admin dashboard templating (out of scope)
- Email templating (out of scope)
- Template builder/WYSIWYG (templates are code-based)

## Database Schema

### New table: `wedding_templates`

```sql
CREATE TABLE wedding_templates (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,           -- "Elegant Floral"
    slug VARCHAR(255) UNIQUE NOT NULL,    -- "elegant-floral"
    description TEXT NULL,
    thumbnail VARCHAR(255) NULL,          -- preview image path
    is_active BOOLEAN DEFAULT FALSE,      -- only one active at a time
    sections_config JSON,                 -- section order & visibility
    styling_config JSON,                  -- colors, fonts, animation settings
    assets_config JSON,                   -- custom audio, video, background images
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

### JSON Structures

**sections_config:**
```json
{
  "order": ["hero", "couple", "events", "gallery", "gift", "rsvp", "footer"],
  "enabled": {
    "hero": true,
    "couple": true,
    "events": true,
    "gallery": true,
    "gift": true,
    "rsvp": true,
    "footer": true
  }
}
```

**styling_config:**
```json
{
  "primary_color": "#e44d26",
  "secondary_color": "#f26161",
  "font_heading": "Playfair Display",
  "font_body": "Lato",
  "animation_style": "fade",            -- fade, slide, zoom, none
  "layout_style": "centered"            -- centered, fullwidth, split
}
```

**assets_config:**
```json
{
  "background_audio": "sound-bg.mp3",
  "hero_video": "wedding-bg-2.mp4",
  "hero_image": "gallery/slide1.jpg",
  "favicon": "images/favicon.png"
}
```

## File Structure

```
resources/views/wedding/
├── templates/
│   ├── elegant-floral/              ← Template 1 (current, migrated)
│   │   ├── index.blade.php         ← Main layout
│   │   ├── sections/
│   │   │   ├── hero.blade.php
│   │   │   ├── couple.blade.php
│   │   │   ├── events.blade.php
│   │   │   ├── gallery.blade.php
│   │   │   ├── gift.blade.php
│   │   │   ├── rsvp.blade.php
│   │   │   └── footer.blade.php
│   │   ├── css/
│   │   │   └── template.css
│   │   └── js/
│   │       └── template.js
│   └── modern-minimal/             ← Template 2 (new)
│       ├── index.blade.php
│       ├── sections/
│       │   └── ... (same section names)
│       ├── css/
│       └── js/
```

## Controller Logic

### WeddingController@show

```php
public function show(Request $request, $guest = null)
{
    $template = WeddingTemplate::where('is_active', true)->firstOrFail();
    
    // Same existing logic for guest, event, messages...
    
    $viewPath = "wedding.templates.{$template->slug}.index";
    
    return response()
        ->view($viewPath, compact('guestData', 'messages', 'event', 'metaData', 'template'))
        ->header('Content-Type', 'text/html; charset=utf-8')
        ->header('X-Robots-Tag', $metaData['robots_meta']);
}
```

### WeddingTemplate Model

```php
class WeddingTemplate extends Model
{
    protected $fillable = [
        'name', 'slug', 'description', 'thumbnail',
        'is_active', 'sections_config', 'styling_config', 'assets_config'
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'sections_config' => 'array',
        'styling_config' => 'array',
        'assets_config' => 'array',
    ];
    
    // Only one active at a time
    public static function boot()
    {
        static::saving(function ($model) {
            if ($model->is_active) {
                static::where('id', '!=', $model->id)->update(['is_active' => false]);
            }
        });
    }
    
    public function getSectionsAttribute()
    {
        return $this->sections_config['order'] ?? [];
    }
    
    public function isSectionEnabled($section): bool
    {
        return $this->sections_config['enabled'][$section] ?? true;
    }
}
```

## Admin Routes

```php
// Template management
Route::get('/admin/templates', [TemplateController::class, 'index'])->name('admin.templates.index');
Route::post('/admin/templates', [TemplateController::class, 'store'])->name('admin.templates.store');
Route::put('/admin/templates/{id}', [TemplateController::class, 'update'])->name('admin.templates.update');
Route::delete('/admin/templates/{id}', [TemplateController::class, 'destroy'])->name('admin.templates.destroy');
Route::post('/admin/templates/{id}/activate', [TemplateController::class, 'activate'])->name('admin.templates.activate');
Route::get('/admin/templates/{id}/preview', [TemplateController::class, 'preview'])->name('admin.templates.preview');
```

## Implementation Phases

### Phase 1: Database & Model
- Migration for `wedding_templates` table
- `WeddingTemplate` model with casts and boot logic
- Seed default template from current invitation

### Phase 2: Refactor Current Template
- Extract current `invitation.blade.php` into template folder structure
- Split into sections (hero, couple, events, gallery, gift, rsvp, footer)
- Update controller to use template system
- Verify current behavior unchanged

### Phase 3: Admin UI
- Template list page with thumbnails
- Activate/deactivate template
- Preview template before activating
- Basic config editor (section toggle, colors)

### Phase 4: Second Template
- Create "modern-minimal" template with different layout
- Demonstrate the system works with multiple templates

## Data Contract

All templates receive these variables:
- `$guestData` - Guest model instance
- `$messages` - Collection of Message models
- `$event` - Event model instance
- `$metaData` - Array with SEO/meta info
- `$template` - WeddingTemplate model instance

## Testing

- Current invitation renders correctly after refactor
- Switching templates changes UI completely
- Only one template active at a time
- Section toggle works (enable/disable sections)
- Guest data persists across template switches

## Risks

- **Asset conflicts**: Each template uses namespaced assets (CSS/JS) to avoid conflicts
- **Performance**: Template config cached in config cache
- **SEO**: Meta tags generated by controller, not template (consistent across templates)
