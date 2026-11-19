if (-not (Test-Path "artisan")) {
    Write-Host "[!] File 'artisan' tidak ditemukan di folder ini." -ForegroundColor Red
    Write-Host "    Pastikan berada di direktori root project Laravel." -ForegroundColor DarkGray
    exit 1
}

$isAdmin = ([Security.Principal.WindowsPrincipal][Security.Principal.WindowsIdentity]::GetCurrent()).IsInRole([Security.Principal.WindowsBuiltInRole]::Administrator)

Write-Host "=====================================================" -ForegroundColor Cyan
Write-Host "[1/4] Menyalakan Apache & MySQL..." -ForegroundColor Yellow
if ($isAdmin) {
    net start MySQL84 > $null 2>&1
    net start Apache2.4 > $null 2>&1
    netsh advfirewall firewall add rule name="Laravel 8000" dir=in action=allow protocol=TCP localport=8000 > $null 2>&1
    netsh advfirewall firewall add rule name="Apache 80" dir=in action=allow protocol=TCP localport=80 > $null 2>&1
}
else {
    Start-Process cmd -ArgumentList '/c net start MySQL84 & net start Apache2.4 & netsh advfirewall firewall add rule name="Laravel 8000" dir=in action=allow protocol=TCP localport=8000 & netsh advfirewall firewall add rule name="Apache 80" dir=in action=allow protocol=TCP localport=80' -Verb RunAs -Wait -WindowStyle Hidden
}

Write-Host "[2/4] Menyalakan Mailpit..." -ForegroundColor Yellow
if (-not (Get-Process -Name mailpit -ErrorAction SilentlyContinue)) {
    Start-Process 'C:\laragon\bin\mailpit\1.22.3\mailpit.exe' -WindowStyle Hidden
}

Write-Host "[3/4] Menyiapkan browser..." -ForegroundColor Yellow
Start-Job -ScriptBlock { Start-Sleep -Seconds 1; Start-Process 'http://127.0.0.1:8000' } | Out-Null

# --- Deteksi IP Address Aktif untuk Akses HP / LAN ---
$defaultRoute = Get-NetRoute -DestinationPrefix '0.0.0.0/0' -ErrorAction SilentlyContinue | Sort-Object RouteMetric | Select-Object -First 1
$primaryIP = $null
if ($defaultRoute) {
    $primaryIP = (Get-NetIPAddress -InterfaceIndex $defaultRoute.InterfaceIndex -AddressFamily IPv4 -ErrorAction SilentlyContinue).IPAddress
}
$allIPs = Get-NetIPAddress -AddressFamily IPv4 -ErrorAction SilentlyContinue |
    Where-Object { $_.InterfaceAlias -notmatch 'Loopback' -and $_.IPAddress -notmatch '^169\.254\.' }

Write-Host "=====================================================" -ForegroundColor Cyan
Write-Host " [i] DETEKSI ALAMAT IP & AKSES SERVER" -ForegroundColor Cyan
Write-Host "-----------------------------------------------------" -ForegroundColor DarkGray
Write-Host "  * Akses dari Komputer ini (Localhost):" -ForegroundColor White
Write-Host "    -> http://127.0.0.1:8000" -ForegroundColor Green

if ($primaryIP) {
    Write-Host "  * Akses dari HP Android / Perangkat Lain ($($defaultRoute.InterfaceAlias)):" -ForegroundColor White
    Write-Host "    -> http://$($primaryIP):8000" -ForegroundColor Yellow
    Write-Host "    [!] PENTING: WAJIB sertakan port :8000 di belakang IP!" -ForegroundColor Magenta
    Write-Host "        Jika mengetik 'http://$($primaryIP)' tanpa :8000," -ForegroundColor DarkGray
    Write-Host "        yang terbuka adalah halaman default Laragon, bukan OSIS." -ForegroundColor DarkGray
}

$otherIPs = $allIPs | Where-Object { $_.IPAddress -ne $primaryIP }
if ($otherIPs) {
    Write-Host "  * Interface Jaringan Lainnya:" -ForegroundColor DarkGray
    foreach ($item in $otherIPs) {
        Write-Host "    - $($item.InterfaceAlias): http://$($item.IPAddress):8000" -ForegroundColor DarkGray
    }
}

Write-Host "=====================================================" -ForegroundColor Cyan
Write-Host "[4/4] Menjalankan php artisan serve..." -ForegroundColor Green
Write-Host "=====================================================" -ForegroundColor Cyan
Write-Host "Tekan CTRL + C untuk mematikan server & semua layanan." -ForegroundColor Magenta
Write-Host "-----------------------------------------------------" -ForegroundColor DarkGray

try {
    php artisan serve --host=0.0.0.0 --port=8000
}
finally {
    Write-Host "`n=====================================================" -ForegroundColor Cyan
    Write-Host "[-] Mematikan Apache, MySQL, dan Mailpit..." -ForegroundColor Yellow
    if ($isAdmin) {
        net stop Apache2.4 > $null 2>&1
        net stop MySQL84 > $null 2>&1
    }
    else {
        Start-Process cmd -ArgumentList '/c net stop Apache2.4 & net stop MySQL84' -Verb RunAs -Wait -WindowStyle Hidden
    }
    Stop-Process -Name mailpit -Force -ErrorAction SilentlyContinue
    Write-Host "[OK] Semua layanan berhasil DIMATIKAN. Port dan RAM bersih." -ForegroundColor Green
    Write-Host "=====================================================" -ForegroundColor Cyan
}