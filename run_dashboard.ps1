$ErrorActionPreference = "Stop"

Set-Location -Path "$PSScriptRoot\dashboard"

if (-not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env"
    Write-Host "File .env dibuat dari .env.example"
}

php artisan key:generate --force | Out-Null
php artisan migrate --force
php artisan storage:link

Write-Host "Menjalankan Laravel di http://127.0.0.1:8000"
php artisan serve --host=127.0.0.1 --port=8000
