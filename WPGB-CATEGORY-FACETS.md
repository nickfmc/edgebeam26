# WP Grid Builder Category Facets - Setup Guide

## What Was Added

### 1. ACF Field Group for Categories
**File:** `acf-json/group_category_styling.json`

Two new fields added to all blog categories:
- **Category Color** - Color picker field for the active state
- **Category Icon** - Image upload field for category icons (SVG/PNG recommended)

### 2. WP Grid Builder Integration
**File:** `inc/gdt-custom.php`

Added PHP functions that:
- Automatically inject category icons into facet buttons
- Add color data attributes for styling
- Include inline CSS for basic styling (fallback)

### 3. Advanced SCSS Styling
**Files:** 
- `src/scss/components/_wpgb-facets.scss` (new)
- `src/site.scss` (updated to import)

Comprehensive styling for:
- Icon display and sizing
- Active state with custom colors
- Hover effects
- Pill-style facets
- Checkbox/radio facets
- Responsive design
- Dark mode support

## How To Use

### Step 1: Sync ACF Fields
1. Go to WordPress Admin → Custom Fields
2. Click "Sync Available" if prompted
3. The "Category Styling" field group should now be active

### Step 2: Configure Categories
1. Go to Posts → Categories
2. Edit each category
3. You'll see two new fields:
   - **Category Color**: Choose a color for the active state
   - **Category Icon**: Upload an icon (recommended: SVG 50x50px or PNG)

### Step 3: Create WP Grid Builder Facets
1. In WP Grid Builder, create a new facet
2. Set **Source** to "Taxonomy"
3. Choose **Taxonomy** as "Category"
4. The icons and colors will automatically appear!

### Step 4: Compile Styles
Run your webpack build task to compile the new SCSS:
```bash
npm run build
# or for development with watch:
npm run dev
```

## Customization

### Adjust Icon Size
Edit `src/scss/components/_wpgb-facets.scss`:
```scss
.category-icon {
  width: 24px;  // Change from 20px
  height: 24px; // Change from 20px
}
```

### Change Active State Style
In `_wpgb-facets.scss`, modify:
```scss
&.wpgb-active {
  background-color: var(--category-color) !important;
  // Add your custom styles here
}
```

### Disable Icon Color Inversion
If you don't want icons to invert on active state:
```scss
&.wpgb-active .category-icon {
  filter: none; // Remove the invert filter
}
```

## Troubleshooting

### Icons Not Showing
- Check that WP Grid Builder is active
- Verify facet is using "Category" taxonomy
- Clear WordPress cache
- Make sure icons are uploaded to categories

### Colors Not Working
- Compile SCSS files (`npm run build`)
- Clear browser cache
- Check that category has a color assigned
- Use browser inspector to verify `data-category-color` attribute exists

### Styling Issues
- The inline CSS in `gdt-custom.php` provides fallback styles
- Main styling comes from `_wpgb-facets.scss` after compilation
- Check that `wp-grid-builder` stylesheet handle exists for inline styles

## WP Grid Builder Documentation
For more customization options, see:
https://docs.wpgridbuilder.com/resources/filter-facets/

## Files Modified
- ✅ `acf-json/group_category_styling.json` (created)
- ✅ `inc/gdt-custom.php` (modified)
- ✅ `src/scss/components/_wpgb-facets.scss` (created)
- ✅ `src/site.scss` (modified)
