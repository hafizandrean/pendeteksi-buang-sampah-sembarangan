$ErrorActionPreference = "Stop"

Write-Host "== Final Check =="

Set-Location -Path "$PSScriptRoot\dashboard"

php artisan migrate:status
php artisan route:list
php artisan test

Set-Location -Path $PSScriptRoot
python -c "import ast, pathlib; ast.parse(pathlib.Path('detect.py').read_text(encoding='utf-8')); print('detect.py syntax_ok')"

Write-Host "Final check selesai: sistem siap operasional."
