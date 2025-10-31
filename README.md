# Kolonnawat — Travel Agency Website Template

A lightweight travel agency website/template built with plain HTML, CSS, JavaScript, and PHP for server-side pages (login/register/admin). This repo contains a ready-to-use static+PHP site you can run locally or deploy to a PHP-capable host.

## Quick overview

- Built from simple HTML, CSS and PHP files.
- Includes pages: home, destinations, galleries (country-specific PHP pages), contact, admin/registration panels, and uploads for images.
- Assets are under `css/`, `js/`, `img/`, `upload/` and third-party libs under `lib/`.

## Features

- Multiple static and PHP-rendered pages (gallery pages like `indiangallery.php`, `thaigallery.php`, etc.)
- Contact form wiring under `mail/` (contact.php + contact.js)
- Simple admin/register panels (use with care — no heavy authentication included out-of-the-box)
- Responsive layout using the styles in `css/style.css` and `scss/style.scss`

## Requirements

- PHP 7.4+ (for the `.php` pages to work)
- A web server environment (XAMPP, WAMP, MAMP, or a hosting provider supporting PHP)
- (Optional) MySQL if you plan to wire server-side persistence — this repo currently contains only front-end and PHP page templates, not a full DB schema.

> Note: GitHub Pages cannot run PHP. If you publish this repository to GitHub Pages, only the static HTML will work; PHP pages will not execute. For PHP functionality deploy to a PHP-capable host (shared hosting, VPS, or services like Render/Heroku with appropriate build steps).

## Quick start (Windows, PowerShell)

1. Install PHP (e.g., from windows.php.net) or XAMPP/WAMP and ensure `php` is on your PATH (if using built-in server).

2a. Using PHP built-in server (good for quick local testing):

   Open PowerShell in the project root and run:

   php -S localhost:8000

   Then open http://localhost:8000 in your browser.

2b. Using XAMPP / WAMP:

   - Move the `kolonnawat` folder into your webserver's document root (e.g., `C:\xampp\htdocs\kolonnawat`).
   - Start Apache from the XAMPP control panel.
   - Open http://localhost/kolonnawat in your browser.

## Project structure (important files/folders)

- `index1.html` — main landing page (there's also `index.html` variants in some forks)
- `css/` — primary styles (`style.css`, `style.min.css`)
- `js/` — site JavaScript (`main.js`)
- `lib/` — third-party libraries (owlcarousel, easing, tempusdominus, ...)
- `img/` — image assets (used by pages)
- `upload/` — uploaded images (used by gallery pages). NOTE: Do not expose this directory on production without server-side checks.
- `mail/` — contact form handler (`contact.php`, `contact.js`)
- `Register_*.php`, `login.php`, `registeradminpannel.php` — registration/login interfaces
- `adminpannel.php`, `registeradminpannel.php` — admin UI pages (exercise caution and secure them before production)
- `LICENSE.txt` — license file for this project (see below)

## Configuration

- Search for hardcoded values (email in `mail/contact.php`, paths in PHP includes) and update them for your environment.
- If you add server-side uploads or DB storage, validate and sanitize all inputs. This template does not include production-ready security.

## Security notes

- The `upload/` folder currently contains uploaded images; ensure in production you restrict execution in that directory (e.g., disable PHP execution) and validate file types/sizes.
- Admin and registration pages are minimal — add proper authentication, password hashing, and CSRF protections before using in a real site.

## Customization

- Replace images in `img/` and `upload/` with your own.
- Edit text content directly in the `.html` and `.php` files.
- Styles are in `scss/style.scss` (source) and `css/style.css` (compiled). If you modify SCSS, recompile to CSS.

## Publishing

- For full PHP support, deploy to a PHP-capable host (shared hosting, VPS, or a platform that supports PHP).
- For static-only hosting (GitHub Pages), convert PHP pages to HTML or pre-render them; PHP will not work on Pages.

## Contributing

- Fixes, enhancements and PRs are welcome. Please follow these basics:
  1. Create an issue describing the change.
  2. Open a pull request with a clear description and limited scope.
  3. Keep UI/code changes isolated and add brief notes for reviewers.

## License

This repository includes a `LICENSE.txt` file. Please review it for license details and attribution.

## Credits

- Template structure and assets belong to the original author(s) — please preserve license and credits in `LICENSE.txt`.

## Contact

If you need help publishing or customizing this template, open an issue or reach out via the repository contact information.

---

(README created and added to repository root)
