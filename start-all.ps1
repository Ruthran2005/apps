# Start all 3 Laravel projects simultaneously
$root = $PSScriptRoot

Write-Host "===================================================" -ForegroundColor Cyan
Write-Host "  Starting School Nexus 3-Tier Laravel Ecosystem" -ForegroundColor Cyan
Write-Host "===================================================" -ForegroundColor Cyan

Write-Host "[1/3] Launching Admin Portal on http://localhost:8000 ..." -ForegroundColor Blue
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$root\admin'; Write-Host '--- ADMIN PORTAL (Port 8000) ---' -ForegroundColor Blue; php artisan serve --port=8000"

Write-Host "[2/3] Launching Teacher Portal on http://localhost:8001 ..." -ForegroundColor Green
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$root\teacher'; Write-Host '--- TEACHER PORTAL (Port 8001) ---' -ForegroundColor Green; php artisan serve --port=8001"

Write-Host "[3/3] Launching Student Portal on http://localhost:8002 ..." -ForegroundColor Magenta
Start-Process powershell -ArgumentList "-NoExit", "-Command", "cd '$root\student'; Write-Host '--- STUDENT PORTAL (Port 8002) ---' -ForegroundColor Magenta; php artisan serve --port=8002"

Write-Host "`nAll 3 portals launched in separate windows!" -ForegroundColor Green
Write-Host "Admin Portal:   http://localhost:8000" -ForegroundColor Cyan
Write-Host "Teacher Portal: http://localhost:8001" -ForegroundColor Cyan
Write-Host "Student Portal: http://localhost:8002" -ForegroundColor Cyan
