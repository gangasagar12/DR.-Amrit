@echo off
set "PHP_DIR=%LOCALAPPDATA%\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe"
set "PATH=%PHP_DIR%;%PATH%"
set "PHPRC=%PHP_DIR%"

echo ============================================================
echo   Starting Dr. Amrit Jaishi Dental Portfolio Server
echo   URL: http://localhost:8000
echo   Admin: http://localhost:8000/admin/login
echo ============================================================
echo Press Ctrl+C to stop the server.
echo.

start http://localhost:8000
php -S localhost:8000 router.php
pause
