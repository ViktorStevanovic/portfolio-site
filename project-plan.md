# Portfolio Site — Project Plan

## Stack

- **Framework:** Laravel 11+
- **Frontend:** Blade + Livewire (or Inertia.js + Vue/React)
- **CSS:** Tailwind CSS
- **Admin Panel:** Filament PHP (recommended) or custom-built
- **Database:** MySQL / PostgreSQL
- **Storage:** Laravel Filesystem (local or S3)
- **Deployment:** VPS + Forge / Laravel Cloud / Railway

---

## Database Schema

### users

| Column     | Type         | Notes               |
|------------|--------------|----------------------|
| id         | bigint PK    | auto-increment       |
| name       | string       |                      |
| email      | string       | unique               |
| password   | string       |                      |
| timestamps |              |                      |

Single admin user. Auth via Laravel Breeze/Fortify.

---

### profiles

| Column         | Type         | Notes                          |
|----------------|--------------|--------------------------------|
| id             | bigint PK    |                                |
| user_id        | FK → users   | one-to-one                     |
| job_title      | string       | e.g. "Backend Engineer"        |
| tagline        | string null  | e.g. "Building things that scale" |
| bio            | text         | summary/about me               |
| photo          | string null  | path to profile photo           |
| cv_path        | string null  | path to current CV file         |
| cv_downloads   | int default 0| download counter               |
| github_url     | string null  |                                |
| linkedin_url   | string null  |                                |
| twitter_url    | string null  |                                |
| website_url    | string null  |                                |
| email_public   | string null  | public contact email            |
| timestamps     |              |                                |

---

### companies

| Column     | Type         | Notes                    |
|------------|--------------|--------------------------|
| id         | bigint PK    |                          |
| name       | string       |                          |
| address    | string null  |                          |
| website    | string null  |                          |
| logo       | string null  | path to logo image       |
| timestamps |              |                          |

---

### technology_fields

| Column     | Type         | Notes                          |
|------------|--------------|--------------------------------|
| id         | bigint PK    |                                |
| name       | string       | e.g. "Backend", "Frontend"     |
| code       | string       | unique slug, e.g. "backend"    |
| order      | int default 0| display order                  |
| timestamps |              |                                |

---

### technologies

| Column              | Type                | Notes                          |
|---------------------|---------------------|--------------------------------|
| id                  | bigint PK           |                                |
| technology_field_id | FK → technology_fields |                             |
| name                | string              | e.g. "Laravel"                 |
| code                | string              | unique slug, e.g. "laravel"    |
| icon                | string null         | path or icon class             |
| proficiency         | tinyint null        | 1-100 scale (optional)         |
| order               | int default 0       | display order within field     |
| timestamps          |                     |                                |

---

### experiences

| Column       | Type                | Notes                              |
|--------------|---------------------|------------------------------------|
| id           | bigint PK           |                                    |
| company_id   | FK → companies      |                                    |
| role         | string              | e.g. "Senior Backend Engineer"     |
| description  | text null           | what you did there                 |
| type         | enum                | full_time, freelance, contract, internship |
| start_date   | date                |                                    |
| end_date     | date null           | null = current position            |
| order        | int default 0       | manual display order               |
| is_visible   | boolean default true| toggle visibility on public site   |
| timestamps   |                     |                                    |

---

### experience_technology (pivot)

| Column        | Type              |
|---------------|-------------------|
| experience_id | FK → experiences  |
| technology_id | FK → technologies |

Composite primary key on both columns.

---

### projects

| Column         | Type                | Notes                            |
|----------------|---------------------|----------------------------------|
| id             | bigint PK           |                                  |
| name           | string              |                                  |
| code           | string              | unique slug for URLs             |
| status         | enum                | in_progress, completed, archived |
| short_description | string null      | for list/card view               |
| description    | text null           | full description (markdown)      |
| repository_url | string null         | GitHub/GitLab link               |
| demo_url       | string null         | live demo link                   |
| order          | int default 0       | display order                    |
| is_visible     | boolean default true|                                  |
| is_featured    | boolean default false| highlight on homepage           |
| timestamps     |                     |                                  |

---

### project_images

| Column     | Type            | Notes                        |
|------------|-----------------|------------------------------|
| id         | bigint PK       |                              |
| project_id | FK → projects   |                              |
| image      | string          | file path                    |
| caption    | string null     |                              |
| is_cover   | boolean default false | used as thumbnail in list |
| order      | int default 0   | carousel order               |
| timestamps |                 |                              |

---

### project_technology (pivot)

| Column        | Type              |
|---------------|-------------------|
| project_id    | FK → projects     |
| technology_id | FK → technologies |

Composite primary key on both columns.

---

### contact_messages

| Column     | Type            | Notes                     |
|------------|-----------------|---------------------------|
| id         | bigint PK       |                           |
| name       | string          |                           |
| email      | string          |                           |
| subject    | string null     |                           |
| message    | text            |                           |
| read_at    | timestamp null  | null = unread             |
| timestamps |                 |                           |

---

### visit_logs

| Column     | Type            | Notes                     |
|------------|-----------------|---------------------------|
| id         | bigint PK       |                           |
| route      | string          | e.g. "/projects/my-app"   |
| method     | string          | GET, POST                 |
| ip         | string null     |                           |
| user_agent | string null     |                           |
| referrer   | string null     |                           |
| created_at | timestamp       | no updated_at needed      |

---

## Routes

### Public (frontoffice)

```
GET  /                          → HomeController@index
GET  /about                     → PageController@about (or part of home)
GET  /projects                  → ProjectController@index
GET  /projects/{code}           → ProjectController@show
GET  /experience                → ExperienceController@index
GET  /contact                   → ContactController@show
POST /contact                   → ContactController@send
GET  /cv/download               → CvController@download
```

### Admin (backoffice) — prefix: /admin, middleware: auth

```
GET  /admin                     → DashboardController@index

# Profile
GET  /admin/profile             → ProfileController@edit
PUT  /admin/profile             → ProfileController@update
POST /admin/profile/cv          → ProfileController@uploadCv

# Companies
GET  /admin/companies           → CompanyController@index
GET  /admin/companies/create    → CompanyController@create
POST /admin/companies           → CompanyController@store
GET  /admin/companies/{id}/edit → CompanyController@edit
PUT  /admin/companies/{id}      → CompanyController@update
DELETE /admin/companies/{id}    → CompanyController@destroy

# Technology Fields — same CRUD pattern
# Technologies — same CRUD pattern
# Experiences — same CRUD pattern
# Projects — same CRUD pattern (+ image upload/reorder)
# Contact Messages — index, show, markAsRead, delete
# Visit Logs — index (with filters/date range)
```

---

## Middleware

### LogVisit (applied to public route group)

```php
class LogVisit
{
    public function handle(Request $request, Closure $next)
    {
        // Skip bots, asset requests, etc.
        if (!$request->is('admin/*') && $request->isMethod('GET')) {
            VisitLog::create([
                'route'      => $request->path(),
                'method'     => $request->method(),
                'ip'         => $request->ip(),
                'user_agent' => $request->userAgent(),
                'referrer'   => $request->header('referer'),
            ]);
        }

        return $next($request);
    }
}
```

---

## Model Relationships Summary

```
User        hasOne    Profile
Profile     belongsTo User

Company     hasMany   Experience

TechnologyField hasMany Technology
Technology  belongsTo TechnologyField

Experience  belongsTo  Company
Experience  belongsToMany Technology (pivot: experience_technology)

Project     belongsToMany Technology (pivot: project_technology)
Project     hasMany       ProjectImage

ProjectImage belongsTo Project

Technology  belongsToMany Experience
Technology  belongsToMany Project
```

---

## Admin Panel Recommendation: Filament PHP

Rather than building CRUD screens from scratch, **Filament** gives you a production-ready admin in hours:

- Auto-generates list/create/edit pages from your models
- Built-in file upload, image preview, rich text editor
- Drag-and-drop reordering (for your `order` fields)
- Relationship managers (attach technologies inline)
- Dashboard with widgets (visit stats, message count)
- Built on Livewire + Tailwind — matches the stack

If you prefer building custom, go with Blade + Livewire for the admin
and keep it behind auth middleware.

---

## Public Site Page Breakdown

### Homepage
- Profile photo, name, job title, tagline
- Short bio
- Featured projects (is_featured = true)
- Technologies grouped by field
- CTA: download CV, contact, view all projects

### Projects List (/projects)
- Grid/card layout
- Cover image thumbnail, name, status badge, short_description
- Technology tags
- Filter by technology field or status

### Project Detail (/projects/{code})
- Full description (rendered from markdown)
- Screenshot carousel
- Technology list with icons
- Links: repository, demo

### Experience (/experience)
- Timeline or list layout
- Company logo, role, dates, type badge
- Description
- Technologies used

### Contact (/contact)
- Simple form: name, email, subject, message
- Social links from profile

### CV Download (/cv/download)
- Serves the file from storage
- Increments cv_downloads counter

---

## Admin Dashboard Widgets

- Total visits (today / this week / this month)
- Most visited pages (top 5)
- CV download count
- Unread contact messages count
- Quick links to manage projects/experiences

---

## Suggested Implementation Order

### Phase 1 — Foundation
1. Laravel project setup + Tailwind + Filament install
2. Migrations for all tables
3. Models with relationships
4. Seeders (seed yourself as admin user, a few sample records)
5. Auth setup (single admin user)

### Phase 2 — Admin Panel
6. Profile management (bio, photo, CV upload)
7. Companies CRUD
8. Technology Fields + Technologies CRUD
9. Experiences CRUD (with company select, technology attach)
10. Projects CRUD (with technology attach, image upload/reorder)
11. Contact messages viewer

### Phase 3 — Public Site
12. Homepage layout
13. Projects list + detail pages
14. Experience page
15. Contact form + email sending
16. CV download route
17. Visit logging middleware

### Phase 4 — Polish
18. SEO: meta tags, Open Graph, sitemap.xml
19. Caching strategy (page/fragment cache)
20. Responsive design pass
21. Performance (image optimization, lazy loading)
22. Deployment setup

### Phase 5 (future) — Blog
23. Blog posts CRUD in admin
24. Public blog list + detail
25. RSS feed
