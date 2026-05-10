@echo off
REM Database User Creator - Windows Batch Wrapper
REM This script creates MySQL users and grants permissions interactively
REM Location: C:\custom-xampp\create-db-user.bat

setlocal enabledelayedexpansion

REM Check if PHP exists
if not exist "C:\custom-xampp\php\php.exe" (
    echo Error: PHP not found at C:\custom-xampp\php\php.exe
    echo Make sure XAMPP is installed at C:\custom-xampp
    pause
    exit /b 1
)

REM Check if MySQL is running
tasklist /FI "IMAGENAME eq mysqld.exe" 2>nul | find /I /N "mysqld.exe">nul
if errorlevel 1 (
    echo Warning: MySQL does not appear to be running
    echo Please start MySQL from XAMPP Control Panel before continuing
    echo.
    pause
)

REM Run the PHP script
echo Starting Database User Creator...
echo.

"C:\custom-xampp\php\php.exe" "C:\custom-xampp\tools\create-db-user.php"

if errorlevel 1 (
    echo.
    echo Operation completed with errors. Check the output above.
    pause
) else (
    echo.
    echo Operation completed successfully.
    pause
)

endlocal
