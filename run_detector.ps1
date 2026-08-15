$ErrorActionPreference = "Stop"

Set-Location -Path $PSScriptRoot

if (-not (Test-Path ".env.detector")) {
    Copy-Item ".env.detector.example" ".env.detector"
    Write-Host "File .env.detector dibuat dari .env.detector.example"
}

if (-not (Test-Path ".venv")) {
    python -m venv .venv
}

& "$PSScriptRoot\.venv\Scripts\python.exe" -m pip install --upgrade pip
& "$PSScriptRoot\.venv\Scripts\python.exe" -m pip install -r "$PSScriptRoot\requirements.txt"

# Load variabel dari .env.detector
Get-Content "$PSScriptRoot\.env.detector" | ForEach-Object {
    $line = $_.Trim()
    if ($line -and -not $line.StartsWith("#")) {
        $parts = $line -split "=", 2
        if ($parts.Count -eq 2) {
            [System.Environment]::SetEnvironmentVariable($parts[0], $parts[1], "Process")
        }
    }
}

Write-Host "Menjalankan AI detector..."
& "$PSScriptRoot\.venv\Scripts\python.exe" "$PSScriptRoot\detect.py"
