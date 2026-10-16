$phpDir = "$env:LOCALAPPDATA\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe"
$env:Path = "$phpDir;$env:Path"
$env:PHPRC = $phpDir

Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "  Starting Dr. Amrit Jaishi Dental Portfolio Server" -ForegroundColor Green
Write-Host "  URL:   http://localhost:8000" -ForegroundColor Yellow
Write-Host "  Admin: http://localhost:8000/admin/login" -ForegroundColor Yellow
Write-Host "============================================================" -ForegroundColor Cyan
Write-Host "Press Ctrl+C to stop the server.`n"

Start-Process "http://localhost:8000"
& "$phpDir\php.exe" -S localhost:8000 router.php
