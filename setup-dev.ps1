# ASI System - Automatic System Inventory
# PowerShell Script untuk setup development environment

Write-Host "========================================" -ForegroundColor Cyan
Write-Host "    ASI System - Development Setup" -ForegroundColor Cyan
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""

# Function to check if command exists
function Test-Command($cmdname) {
    return [bool](Get-Command -Name $cmdname -ErrorAction SilentlyContinue)
}

# Function to display error and exit
function Exit-WithError($message) {
    Write-Host "[ERROR] $message" -ForegroundColor Red
    Write-Host ""
    Read-Host "Press Enter to exit"
    exit 1
}

# Function to display success message
function Write-Success($message) {
    Write-Host "[SUCCESS] $message" -ForegroundColor Green
}

# Function to display info message
function Write-Info($message) {
    Write-Host "[INFO] $message" -ForegroundColor Yellow
}

# Function to display warning message
function Write-Warning($message) {
    Write-Host "[WARNING] $message" -ForegroundColor Magenta
}

# Check prerequisites
Write-Info "Checking prerequisites..."

if (-not (Test-Command "php")) {
    Exit-WithError "PHP tidak ditemukan. Pastikan PHP 8.4+ sudah terinstall dan ada di PATH.`nDownload: https://www.php.net/downloads"
}

if (-not (Test-Command "composer")) {
    Exit-WithError "Composer tidak ditemukan. Pastikan Composer sudah terinstall dan ada di PATH.`nDownload: https://getcomposer.org/download/"
}

if (-not (Test-Command "node")) {
    Exit-WithError "Node.js tidak ditemukan. Pastikan Node.js 16+ sudah terinstall dan ada di PATH.`nDownload: https://nodejs.org/"
}

if (-not (Test-Command "npm")) {
    Exit-WithError "NPM tidak ditemukan. Pastikan NPM sudah terinstall bersama Node.js."
}

Write-Success "All prerequisites found!"
Write-Host ""

# Check PHP version
$phpVersion = php -r "echo PHP_VERSION;"
Write-Info "PHP Version: $phpVersion"

# Check Composer version
$composerVersion = composer --version --no-ansi 2>$null | Select-String -Pattern "Composer version ([0-9.]+)" | ForEach-Object { $_.Matches[0].Groups[1].Value }
Write-Info "Composer Version: $composerVersion"

# Check Node.js version
$nodeVersion = node --version
Write-Info "Node.js Version: $nodeVersion"

# Check NPM version
$npmVersion = npm --version
Write-Info "NPM Version: $npmVersion"
Write-Host ""

# Setup environment file
if (-not (Test-Path ".env")) {
    Write-Info "Copying .env.example to .env..."
    Copy-Item ".env.example" ".env"
    Write-Success ".env file created!"
    Write-Warning "Please edit .env file to configure your database settings."
} else {
    Write-Info ".env file already exists."
}
Write-Host ""

# Install PHP dependencies
Write-Info "Installing PHP dependencies..."
try {
    composer install --no-interaction
    Write-Success "PHP dependencies installed!"
} catch {
    Exit-WithError "Failed to install PHP dependencies: $($_.Exception.Message)"
}
Write-Host ""

# Generate application key
Write-Info "Generating application key..."
try {
    php artisan key:generate --no-interaction
    Write-Success "Application key generated!"
} catch {
    Write-Warning "Failed to generate application key: $($_.Exception.Message)"
}
Write-Host ""

# Install Node.js dependencies
Write-Info "Installing Node.js dependencies..."
try {
    npm install
    Write-Success "Node.js dependencies installed!"
} catch {
    Exit-WithError "Failed to install Node.js dependencies: $($_.Exception.Message)"
}
Write-Host ""

# Build assets
Write-Info "Building assets..."
try {
    npm run build
    Write-Success "Assets built successfully!"
} catch {
    Write-Warning "Failed to build assets: $($_.Exception.Message)"
}
Write-Host ""

# Create storage link
Write-Info "Creating storage link..."
try {
    php artisan storage:link
    Write-Success "Storage link created!"
} catch {
    Write-Warning "Failed to create storage link: $($_.Exception.Message)"
}
Write-Host ""

# Check database configuration
Write-Info "Checking database configuration..."
$dbConnection = Select-String -Path ".env" -Pattern "DB_CONNECTION=(.+)" | ForEach-Object { $_.Matches[0].Groups[1].Value }
$dbHost = Select-String -Path ".env" -Pattern "DB_HOST=(.+)" | ForEach-Object { $_.Matches[0].Groups[1].Value }
$dbDatabase = Select-String -Path ".env" -Pattern "DB_DATABASE=(.+)" | ForEach-Object { $_.Matches[0].Groups[1].Value }

Write-Info "Database Connection: $dbConnection"
Write-Info "Database Host: $dbHost"
Write-Info "Database Name: $dbDatabase"
Write-Host ""

# Test database connection
Write-Info "Testing database connection..."
try {
    $migrationStatus = php artisan migrate:status 2>&1
    if ($LASTEXITCODE -eq 0) {
        Write-Success "Database connection successful!"
        
        # Ask if user wants to run migrations
        $runMigrations = Read-Host "Do you want to run database migrations and seeders? (y/n)"
        if ($runMigrations -eq "y" -or $runMigrations -eq "Y") {
            Write-Info "Running database migrations and seeders..."
            try {
                php artisan migrate --seed --no-interaction
                Write-Success "Database migrations and seeders completed!"
                Write-Host ""
                Write-Host "========================================" -ForegroundColor Green
                Write-Host "         Default Login Accounts" -ForegroundColor Green
                Write-Host "========================================" -ForegroundColor Green
                Write-Host "Administrator:" -ForegroundColor Cyan
                Write-Host "  Email: admin@asi.com" -ForegroundColor White
                Write-Host "  Password: admin123" -ForegroundColor White
                Write-Host ""
                Write-Host "Petugas:" -ForegroundColor Cyan
                Write-Host "  Email: petugas@asi.com" -ForegroundColor White
                Write-Host "  Password: petugas123" -ForegroundColor White
                Write-Host "========================================" -ForegroundColor Green
            } catch {
                Write-Warning "Failed to run migrations: $($_.Exception.Message)"
            }
        }
    } else {
        Write-Warning "Database connection failed. Please check your .env configuration."
        Write-Info "Make sure your database server is running and credentials are correct."
    }
} catch {
    Write-Warning "Could not test database connection: $($_.Exception.Message)"
}
Write-Host ""

# Clear caches
Write-Info "Clearing application caches..."
try {
    php artisan config:clear
    php artisan cache:clear
    php artisan view:clear
    php artisan route:clear
    Write-Success "Caches cleared!"
} catch {
    Write-Warning "Failed to clear some caches: $($_.Exception.Message)"
}
Write-Host ""

# Final setup completion
Write-Host "========================================" -ForegroundColor Green
Write-Host "        Setup Completed Successfully!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Next steps:" -ForegroundColor Cyan
Write-Host "1. Edit .env file if needed (database, mail, etc.)" -ForegroundColor White
Write-Host "2. Run 'php artisan serve' to start the development server" -ForegroundColor White
Write-Host "3. Run 'npm run dev' in another terminal for hot reload (optional)" -ForegroundColor White
Write-Host "4. Access your application at http://localhost:8000" -ForegroundColor White
Write-Host ""
Write-Host "For quick start, you can also run:" -ForegroundColor Cyan
Write-Host "  .\start-asi.bat" -ForegroundColor White
Write-Host ""

# Ask if user wants to start the server now
$startServer = Read-Host "Do you want to start the development server now? (y/n)"
if ($startServer -eq "y" -or $startServer -eq "Y") {
    Write-Host ""
    Write-Info "Starting Laravel development server..."
    Write-Info "Server will be available at: http://localhost:8000"
    Write-Info "Press Ctrl+C to stop the server"
    Write-Host ""
    php artisan serve --host=0.0.0.0 --port=8000
}

Write-Host ""
Write-Host "Thank you for using ASI System!" -ForegroundColor Green
Read-Host "Press Enter to exit"