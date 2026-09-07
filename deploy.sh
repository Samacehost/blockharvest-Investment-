#!/usr/bin/env bash

# ==============================================================================
# BlockHarvest Automated Production Deployment Script (deploy.sh)
# Designed for VPS, Dedicated Servers, cPanel, and Shared Hosting.
# Note: If any step fails or command is unavailable, it skips and proceeds!
# ==============================================================================

set -u

echo "================================================================="
echo "🚀 BlockHarvest Investment Platform - Production Deployment"
echo "================================================================="

# Helper function to print step headers
step() {
    echo ""
    echo "-----------------------------------------------------------------"
    echo "🔹 Step $1: $2"
    echo "-----------------------------------------------------------------"
}

# Helper function for graceful step execution
run_step() {
    local cmd="$1"
    local step_title="$2"
    
    echo "▶ Running: $step_title..."
    if eval "$cmd"; then
        echo "✅ Success: $step_title"
    else
        echo "⚠️ Warning: '$step_title' could not complete or command not found. Skipping to next step..."
    fi
}

# 1. Directory Navigation
step 1 "Navigating to Project Root Directory"
if [ -d "public_html" ]; then
    echo "📁 Found public_html directory. Changing directory..."
    cd public_html || echo "⚠️ Could not enter public_html, staying in current directory."
fi
echo "📍 Current Working Directory: $(pwd)"

# 2. Environment Configuration Check
step 2 "Verifying Environment Configuration (.env)"
if [ ! -f ".env" ]; then
    if [ -f ".env.example" ]; then
        echo "📄 Copying .env.example to .env..."
        cp .env.example .env
        echo "✅ Created .env from .env.example"
    else
        echo "⚠️ Warning: Neither .env nor .env.example found."
    fi
else
    echo "✅ .env file detected."
fi

# 3. Composer Dependencies
step 3 "Installing/Updating PHP Dependencies via Composer"
if command -v composer &> /dev/null; then
    run_step "composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction" "Composer Dependencies Installation"
else
    echo "⚠️ 'composer' command is not available in PATH. Skipping Composer installation..."
fi

# 4. Application Key Check
step 4 "Checking Application Encryption Key"
if command -v php &> /dev/null; then
    if grep -q "APP_KEY=base64:" .env 2>/dev/null; then
        echo "✅ Application key already set."
    else
        run_step "php artisan key:generate --force" "Generating Application Key"
    fi
else
    echo "⚠️ 'php' command is not available. Skipping Application Key check..."
fi

# 5. Database Migrations & Seeding
step 5 "Running Database Migrations & System Seeders"
if command -v php &> /dev/null; then
    run_step "php artisan migrate --force" "Database Migrations"
    run_step "php artisan db:seed --force" "Database System Seeders"
else
    echo "⚠️ 'php' command not available. Skipping Database Migrations..."
fi

# 6. Storage Symbolic Link
step 6 "Creating Storage Public Link"
if command -v php &> /dev/null; then
    run_step "php artisan storage:link --force" "Storage Symlink Creation"
else
    echo "⚠️ Skipping Storage Symlink..."
fi

# 7. Application Cache Optimization & Clearing
step 7 "Optimizing Application Caches"
if command -v php &> /dev/null; then
    run_step "php artisan config:clear" "Clear Config Cache"
    run_step "php artisan route:clear" "Clear Route Cache"
    run_step "php artisan view:clear" "Clear View Cache"
    run_step "php artisan cache:clear" "Clear Application Cache"
    
    # Cache optimization
    run_step "php artisan config:cache" "Cache Configuration"
    run_step "php artisan route:cache" "Cache Routes"
    run_step "php artisan view:cache" "Cache Views"
else
    echo "⚠️ Skipping PHP Artisan Caching..."
fi

# 8. Node.js & NPM Assets Build (Vite/Mix)
step 8 "Compiling Front-End Assets with NPM"
if command -v npm &> /dev/null; then
    run_step "npm ci || npm install" "Installing NPM Dependencies"
    run_step "npm run build" "Building Front-End Production Assets"
else
    echo "⚠️ 'npm' command is not available in PATH. Skipping NPM Asset Compilation..."
fi

# 9. Storage & Cache Permissions
step 9 "Setting Directory Permissions (Storage & Cache)"
if command -v chmod &> /dev/null; then
    run_step "chmod -R 775 storage bootstrap/cache 2>/dev/null || chmod -R 755 storage bootstrap/cache 2>/dev/null" "Updating Storage Permissions"
else
    echo "⚠️ 'chmod' command not available. Skipping Permission Settings..."
fi

# 10. Queue & Workers Restart
step 10 "Restarting Background Queue Workers"
if command -v php &> /dev/null; then
    run_step "php artisan queue:restart" "Restarting Queue Worker Processes"
else
    echo "⚠️ Skipping Queue Worker Restart..."
fi

echo ""
echo "================================================================="
echo "🎉 BlockHarvest Deployment Sequence Completed Successfully!"
echo "================================================================="
