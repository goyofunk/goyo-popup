# Goyo Popup

A WordPress popup plugin with a Korean default interface and English (en_US) translations.

## Features

- Image, high-resolution image, and text-only popups.
- Desktop/mobile targeting, page targeting, and publication schedules.
- Popup ordering, preview, positioning, shadows, and overlay settings.
- Daily dismissal based on the WordPress site timezone.

## Installation

1. Place this directory at `wp-content/plugins/goyo-popup/`.
2. Activate **Goyo Popup** in WordPress.
3. Open **팝업창 / Popups** in the administration menu.

The entry file is `goyo-popup.php`. Keep the folder name `goyo-popup` when creating an installation ZIP.


## Translation

- `languages/goyo-popup.pot`: translation template.
- `languages/goyo-popup-en_US.po`: editable English translations.
- `languages/goyo-popup-en_US.mo`: compiled English translations loaded by WordPress.

Select English (United States) in WordPress's site language or user language settings. The Korean source strings remain the fallback. After editing the PO file, regenerate the MO file with a gettext-compatible translation editor, keeping the `goyo-popup-en_US` filename.


