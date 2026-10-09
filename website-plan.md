Now let's move on to the public site portfolio website. look at @project-plan for docs. I was thinking of a single page with 4 sections on top: home, experiences, projects, contacts. Then I'd start by showing the profile Tagline (e.g., "Hi, I'm Viktor"), followed below in the center by the bio. After that, I'd move to the experience section, where we show my work experiences in a chronological list, including company name, description, and technologies used. For the design, I'd follow something like a vertical list with a dot at each experience with the date, and next to it the description and company name with the corresponding dates, and below maybe some tags with the technologies used. Then we move to the projects section, where I'd make cards with the info for each personal project, including title, description, technologies used, and any links. I'd do for example 2 or 3 cards per "row." I'm not sure whether to use the image with the cover image flag as the main image. From there, it will be possible to click to enter the detail page for that project, having a page with the full description and a carousel of images. But I'd leave this point for last — only when we've completed everything will we decide whether to make the detail page. Finally, I'd have the contacts section with the various profile links. There will also be a button labeled "get in touch," which will open a modal with a form for creating contact messages. As for the site's style, I'd keep something very minimal, with light animations. I'd include a light and dark mode toggle, light by default. Obiously all based to the resources we have in filament and database tables

Design System

Typography (Google Fonts)


Headings/Names: DM Serif Display (serif) — used for hero name, project card titles, logo
Body text: DM Sans (weight 300/400/500) — navigation, descriptions, buttons
Monospace accents: DM Mono (weight 400/500) — dates, tags, meta info, clock, footer copy


Colors (CSS custom properties, light mode)

css--bg: #fafaf8;           /* main background — warm cream */
--bg-alt: #f5f5f0;       /* card hover background */
--surface: #ffffff;       /* card backgrounds */
--border: rgba(0,0,0,0.08);
--border-mid: rgba(0,0,0,0.12);
--border-strong: rgba(0,0,0,0.22);
--text: #1a1a1a;          /* primary text */
--text-mid: #555555;      /* secondary text */
--text-dim: #909090;      /* tertiary/muted text */
--accent: #1a1a1a;        /* accent = text color (no bright accents) */

Mettiti a lavoro
