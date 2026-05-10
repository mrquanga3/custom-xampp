@echo off
setlocal

if not exist "C:\custom-xampp\php\php.exe" (
    echo Error: PHP not found at C:\custom-xampp\php\php.exe
    pause
    exit /b 1
)

tasklist /FI "IMAGENAME eq mysqld.exe" 2>nul | find /I "mysqld.exe" >nul
if errorlevel 1 (
    echo Warning: MySQL does not appear to be running.
    echo Start MySQL from XAMPP Control Panel before continuing.
    echo.
    pause
)

echo Starting Database User Permission Updater...
echo.

"C:\custom-xampp\php\php.exe" "C:\custom-xampp\tools\update-db-user.php"

echo.
if errorlevel 1 (echo Operation completed with errors.) else (echo Operation completed successfully.)
pause
endlocal
