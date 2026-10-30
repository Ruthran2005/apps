# SchoolNexus — 3-Tier Laravel School Application

A modern, responsive, multi-portal School Management System composed of **three dedicated Laravel applications** connected to **one unified, shared database**.

---

## 🏛️ System Architecture

```text
d:\school app\backend/
├── admin/               --> Admin Portal (Port: 8000) - School Administration & Oversight
├── teacher/             --> Teacher Portal (Port: 8001) - Attendance, Grades & Coursework
├── student/             --> Student Portal (Port: 8002) - Report Cards, Submissions & Fees
├── shared_database/     --> Shared Centralized Database
│   └── school.sqlite    --> Unified SQLite database file
├── start-all.bat        --> 1-click startup script for all 3 apps (Windows CMD)
└── start-all.ps1        --> 1-click startup script for all 3 apps (PowerShell)
```

---

## 🚀 Quick Start (Running the Applications)

### Option 1: 1-Click Launch (All 3 Portals)
Double-click `start-all.bat` or run in PowerShell:
```powershell
.\start-all.ps1
```

### Option 2: Running Portals Individually
Open 3 terminal windows:

```bash
# Terminal 1: Admin Portal (Port 8000)
cd admin
php artisan serve --port=8000

# Terminal 2: Teacher Portal (Port 8001)
cd teacher
php artisan serve --port=8001

# Terminal 3: Student Portal (Port 8002)
cd student
php artisan serve --port=8002
```

---

## 🌐 Application URLs & Portal Previews

| Portal | URL | Theme | Description |
| :--- | :--- | :--- | :--- |
| **1. Admin Portal** | [http://localhost:8000](http://localhost:8000) | Slate / Indigo | Student & Faculty Directory, Curriculum, Finance & Fee Oversight, Campus-wide Announcements |
| **2. Teacher Portal** | [http://localhost:8001](http://localhost:8001) | Emerald / Teal | Class Rosters, Daily Attendance Register, Gradebook & Exam Marks, Assignment Creation & Grading |
| **3. Student Portal** | [http://localhost:8002](http://localhost:8002) | Purple / Violet | Academic GPA, Official Report Cards, Attendance Calendar, Homework Turn-in, Timetable & Fee Ledger |

---

## 💾 Unified Database Configuration

All three projects are configured to query and mutate the exact same centralized database in real-time.

### Default Connection (Shared SQLite):
Works instantly out of the box with zero external server dependencies:
```env
DB_CONNECTION=sqlite
DB_DATABASE="D:/school app/backend/shared_database/school.sqlite"
```

### Optional MySQL / MariaDB Connection:
If you prefer running MySQL (e.g., via XAMPP):
1. Create a database named `school_db` in phpMyAdmin or MySQL CLI:
   ```sql
   CREATE DATABASE school_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
2. In all 3 projects' `.env` files (`admin/.env`, `teacher/.env`, `student/.env`), uncomment MySQL and comment SQLite:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=school_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```
3. Run migrations and seed from `admin`:
   ```bash
   cd admin
   php artisan migrate:fresh --seed
   ```

---

## 🔑 Pre-Seeded Demo Accounts & Credentials

All demo user accounts are pre-seeded and use the password: `password`

### 1. Administration
- **Email:** `admin@school.com`
- **Name:** Principal Arthur Hayes
- **Role:** Administrator

### 2. Faculty / Teachers
- **Physics & STEM:** `teacher@school.com` (Dr. Sarah Jenkins)
- **Advanced Mathematics:** `math.teacher@school.com` (Prof. Marcus Vance)
- **World Literature:** `english.teacher@school.com` (Ms. Elena Rostova)

> *Tip: On the Teacher Portal, use the profile switcher at the bottom-left of the sidebar to test other teachers.*

### 3. Students
- **Grade 10-A (Roll 101):** `student@school.com` (Alex Morgan)
- **Grade 10-A (Roll 102):** `emma.watson@school.com` (Emma Watson)
- **Grade 10-B (Roll 103):** `liam.smith@school.com` (Liam Smith)
- **Grade 9-A (Roll 104):** `sophia.chen@school.com` (Sophia Chen)

> *Tip: On the Student Portal, use the profile switcher at the bottom-left of the sidebar to test any student account.*

---

## 📱 Modern & Responsive UI Highlights
- **Tailwind CSS & Plus Jakarta Sans typography:** Clean, modern enterprise styling with distinct visual branding for each portal role.
- **Glassmorphic and modern card widgets:** KPI stat cards, attendance progress bars, grade badges, and timetable slots.
- **Full Mobile Responsiveness:** Collapsible slide-over drawer menus on mobile and responsive grids.
- **Inter-Portal Quick Jumper:** Direct jump buttons in the top header and sidebar allow switching between Admin, Teacher, and Student portals in one click.
- **Real-Time Database Sync:** Enrolling a student in Admin immediately makes them available in Teacher's attendance roll call, and homework submitted by a student is immediately available for grading by the teacher.
