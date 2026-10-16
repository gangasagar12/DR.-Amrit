# Dr. Amrit Jaishi - Dynamic Dental Doctor Portfolio Website

A complete, production-ready, dynamic, and modern **Dental Doctor Portfolio Website** with a **Secure Admin Panel** built for **Dr. Amrit Jaishi**, BDS (BPKIHS Dharan), Oral and Dental Surgeon at **Dhangadhi Sanjeevani Hospital**, Farwest Nepal.

The frontend layout and design matches the exact visual theme provided in `img/theme.webp`:
- Deep petrol teal (`#073d43` to `#0e5e67`) curved hero wave with white typography and star badges
- Pill buttons with cyan hover glows (`#00c2cb`)
- Social proof with overlapping patient avatars and 5 gold stars
- Doctor portrait card with rounded borders and circular overlapping experience badge (`4+ Years of Experience`)
- 3-column specialized services layout with center doctor figure
- Step-by-step treatment path (`01`, `02`, `03` cards with image thumbnails)
- Dark-mode patient reviews section with 5.0 rating cards
- Full clinical details, emergency care guidance, and direct hospital booking
- Direct blog publishing engine with TinyMCE rich text editor and server-side XSS sanitization

Built with **PHP 8.2+**, **MySQL / MariaDB (PDO with prepared statements)**, **HTML5**, **Vanilla CSS3**, and **Vanilla JavaScript**. Designed for seamless local execution on **XAMPP (Windows)** and standard **PHP/MySQL Apache shared hosting** environments without requiring Node.js, React, or build tools.

---

## 1. Single Unified Database Setup

All table schemas, constraints, foreign keys, and authentic seed data for **Dr. Amrit Jaishi** are contained in a **single, self-contained SQL file**:
```
c:\xampp\htdocs\doctor\database\database.sql
```

Importing this single file in phpMyAdmin or the MySQL command line sets up the complete application with zero errors:
- `admin_users`: Pre-configured doctor admin user (`admin@dentaldoctor.com` / `Admin@12345`).
- `doctor_profile`: Authentic bio, BDS degree from BPKIHS Dharan, Dhangadhi Sanjeevani Hospital practice details, phone, and email.
- `services`: 8 core dental services with real clinical photography (Emergency Dental Care, Dental Crown & Bridge, RCT, Gum Care, Scaling, Extractions, Wisdom Tooth Removal, Braces).
- `clinics` & `clinic_schedules`: Dhangadhi Sanjeevani Hospital (Permanent Workplace, Sunday–Friday 10:00 AM – 5:00 PM).
- `testimonials`: Patient reviews from Dhangadhi Sanjeevani Hospital.
- `blogs` & `blog_categories`: Educational oral health articles authored by Dr. Amrit Jaishi.
- `site_settings`: Site brand name, contact numbers, hospital phone, address, and social links.

---

## 2. Local Installation on Windows with XAMPP

### Step 1: Start XAMPP
1. Open the **XAMPP Control Panel**.
2. Click **Start** for both **Apache** and **MySQL**.

### Step 2: Project Location
Place this repository in your XAMPP `htdocs` directory:
```
c:\xampp\htdocs\doctor\
```

### Step 3: Create the MySQL Database
1. Open your web browser and navigate to **phpMyAdmin**: [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/)
2. Click **New** in the left sidebar.
3. Enter database name: `dental_doctor_db`
4. Choose collation: `utf8mb4_unicode_ci` and click **Create**.

### Step 4: Import the Single SQL File
1. Select `dental_doctor_db` in phpMyAdmin.
2. Click the **Import** tab.
3. Choose the file: `c:\xampp\htdocs\doctor\database\database.sql`
4. Click **Import**.
*(The single file creates all 14 tables and seeds all data automatically).*

### Step 5: Configure Database Connection (If Needed)
The project connects to MySQL at `localhost:3306`, user `root`, no password by default. If your local XAMPP configuration has a password, copy `.env.example` to `.env` in the project root:
```ini
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_NAME=dental_doctor_db
DB_USER=root
DB_PASS=
```

### Step 6: Launch and Test the Application
Open your browser and visit:
- **Public Website**: [http://localhost/doctor/](http://localhost/doctor/)
- **About Dr. Amrit**: [http://localhost/doctor/about](http://localhost/doctor/about)
- **Services**: [http://localhost/doctor/services](http://localhost/doctor/services)
- **Hospital & Clinic**: [http://localhost/doctor/clinics](http://localhost/doctor/clinics)
- **Articles & Health Tips**: [http://localhost/doctor/blog](http://localhost/doctor/blog)
- **Contact & Booking**: [http://localhost/doctor/contact](http://localhost/doctor/contact)
- **Admin Dashboard**: [http://localhost/doctor/admin/login](http://localhost/doctor/admin/login)

---

## 3. Administrator Credentials

- **Admin Login URL**: `http://localhost/doctor/admin/login`
- **Email**: `admin@dentaldoctor.com`
- **Password**: `Admin@12345`

---

## 4. Admin Management Modules

- **Dashboard**: Real-time KPI counts (Appointments, Services, Clinics, Articles, Inquiries).
- **Profile Management**: Update doctor's name, qualifications, bio, clinical philosophy, languages, contact details, and portrait photo.
- **Service Management**: Add, edit, reorder, activate/deactivate dental treatments, upload procedure images, and customize SEO meta tags.
- **Hospital & Clinic Management**: Configure clinical practice locations, set Permanent Workplace vs. Visiting Consultant status, consultation hours, and visiting days.
- **Blog & Educational Publishing**: Direct publishing workflow with TinyMCE rich text editor, draft saving, server-side XSS sanitization, image uploads, and SEO fields.
- **Appointment Request Management**: Filter appointments by status (**Pending**, **Confirmed**, **Completed**, **Cancelled**), update appointment status, and record private internal clinical notes.
- **Patient Inquiry Management**: View contact messages, mark as read/unread, and archive messages.
- **Website Settings**: Configure brand title, contact phone numbers, emergency notice, footer copyright, and optional SMTP email credentials.

---

## 5. Security & Architecture Highlights

1. **Prepared Statements**: All database operations use PDO prepared statements with strict parameter binding to eliminate SQL injection risks.
2. **Strict Server-Side HTML Sanitization**: Rich-text HTML content from the blog editor is sanitized using a strict tag and attribute allowlist with `DOMDocument`, eliminating `script`, `onerror`, `onclick`, and `javascript:` URIs.
3. **CSRF Protection**: State-changing forms are protected with cryptographically secure, session-bound CSRF tokens.
4. **Password Hashing**: Passwords are encrypted using PHP `password_hash()` with `PASSWORD_BCRYPT`.
5. **Brute Force Protection**: Admin authentication includes IP-based login rate limiting (max 5 failed attempts per 15 minutes).
6. **Upload Hardening**: Uploaded files undergo MIME-type validation, randomized naming, extension allowlisting, and an `.htaccess` script execution prevention barrier.

---

## 6. Shared Hosting Deployment

1. **Upload Files**: Upload the project directory to your hosting server via FTP or cPanel File Manager.
2. **Database Import**: In cPanel phpMyAdmin, create a new database and import `database/database.sql`.
3. **Environment Setup**: Create a `.env` file with your production database credentials.
4. **Apache Rewrite**: The included root `.htaccess` and `public/.htaccess` files handle clean URLs and protect private directories (`app/`, `config/`, `database/`, `storage/`).
5. **HTTPS**: Install an SSL certificate (e.g. Free Let's Encrypt in cPanel) and enforce HTTPS.

---

&copy; 2026 Dr. Amrit Jaishi, BDS (BPKIHS Dharan) &bull; Oral and Dental Surgeon &bull; Dhangadhi Sanjeevani Hospital, Kailali, Nepal.
