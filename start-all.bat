@echo off
echo ===================================================
echo   Starting School Nexus 3-Tier Laravel Ecosystem
echo ===================================================
echo [1/3] Launching Admin Portal on http://localhost:8000 ...
start "School App - Admin Portal (8000)" cmd /k "cd /d "%~dp0admin" && php artisan serve --port=8000"

echo [2/3] Launching Teacher Portal on http://localhost:8001 ...
start "School App - Teacher Portal (8001)" cmd /k "cd /d "%~dp0teacher" && php artisan serve --port=8001"

echo [3/3] Launching Student Portal on http://localhost:8002 ...
start "School App - Student Portal (8002)" cmd /k "cd /d "%~dp0student" && php artisan serve --port=8002"

echo.
echo All 3 Laravel applications are running connected to the unified database!
echo Admin Portal:   http://localhost:8000
echo Teacher Portal: http://localhost:8001
echo Student Portal: http://localhost:8002
echo ===================================================
pause
