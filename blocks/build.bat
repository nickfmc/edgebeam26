@echo off
REM Build script for GDT custom blocks
REM Run this from the blocks directory

echo Building GDT Custom Blocks...

REM Check if node_modules exists
if not exist "node_modules" (
    echo Installing dependencies...
    npm install
    if %errorlevel% neq 0 (
        echo Error installing dependencies!
        pause
        exit /b 1
    )
)

REM Build all blocks from src/ to build/
echo Compiling all blocks from src/ to build/...
npm run build

if %errorlevel% neq 0 (
    echo Error building blocks!
    pause
    exit /b 1
)

echo.
echo Build complete! All blocks compiled to build/ directory.
echo Ready to use in WordPress!
echo.
pause
