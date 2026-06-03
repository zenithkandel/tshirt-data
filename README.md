# T-Shirt Size Collection

A premium, editorial-styled web app for collecting student t-shirt sizes by house, with a PIN-protected admin dashboard and JSON-based storage.

## Stack

- **Frontend:** HTML, CSS, JavaScript
- **Backend:** PHP
- **Storage:** JSON file (`data.json`)
- **Fonts:** Playfair Display, DM Sans, JetBrains Mono
- **Icons:** [Zenith Icons](https://zenithkandel.com.np/fontawesome/zenith-icons.js) (`fa-sharp fa-thin`)

## Structure

```
tshirt-data/
├── index.html          # Student submission form
├── styles.css          # All styling, animations, responsiveness
├── submit.php          # Form submission handler
├── get-data.php        # Data retrieval + analytics for admin
├── delete.php          # Entry deletion (PIN-protected)
├── data.json           # JSON data storage
├── admin/
│   └── index.html      # Admin dashboard
├── cursors/
│   ├── arrow.svg       # Default cursor
│   ├── pointer.svg     # Interactive cursor
│   └── text.svg        # Text input cursor
└── images/
    ├── tshirt-front.png
    └── tshirt-back.png
```

## Features

### Student Form (`index.html`)
- Split-panel layout: t-shirt flip card + submission form
- Flip card shows front/back on hover (no auto-flip)
- House selection: Gaurishankar, Byashrishi, Ratnachuli, Choyu (color-coded chips)
- Size selection: S, M, L, XL, XXL (pill button group)
- Duplicate roll number detection
- Success confirmation with submit-another option
- Fully responsive across all breakpoints

### Admin Dashboard (`admin/index.html`)
- PIN-protected access (PIN: `8023`)
- Login state persisted in `sessionStorage` (survives refresh)
- Sort preference saved in `sessionStorage`
- Stats cards: total submissions, houses, sizes
- Analytics bars with animated fill
- Sortable table (by roll number or submission time)
- Search/filter entries
- Delete individual entries
- CSV export
- Logout clears all session data

## Setup

1. Place the project in your XAMPP `htdocs` directory
2. Ensure PHP is running
3. Navigate to `http://localhost/tshirt-data/`

The `data.json` file is created automatically on first submission.

## Design

- **Background:** Warm beige
- **Accent:** Rust/terracotta (`#a85530`)
- **Aesthetic:** Minimal, editorial, antique-inspired
- **Animations:** Scale-in, fade-up, bar growth, float, shimmer
- **Cursors:** Custom SVG cursors from `/cursors` folder
