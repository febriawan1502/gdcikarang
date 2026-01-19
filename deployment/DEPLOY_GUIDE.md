# Panduan Deployment Gudang Pojok

Berikut adalah langkah-langkah untuk deploy aplikasi ke server Linux dan menghubungkannya dengan domain `cikarang.bhumiteduh.my.id`.

## 1. Persiapan Server
Pastikan kode sudah ada di server, misal di folder `/var/www/gudangpojok`.
Install dependencies:
```bash
cd /var/www/gudangpojok
composer install --optimize-autoloader --no-dev
npm install && npm run build
cp .env.example .env
php artisan key:generate
php artisan migrate --force
```

## 2. Setup Service Aplikasi (Port 8000)
Kita akan menggunakan Systemd agar aplikasi jalan terus di background pada port 8000.

1. Edit file `deployment/gudangpojok.service` dan sesuaikan:
   - `User` & `Group`: Sesuaikan dengan user linux kamu.
   - `WorkingDirectory`: Sesuaikan dengan lokasi folder project.
   - `ExecStart`: Pastikan path php benar.

2. Copy ke systemd:
   ```bash
   sudo cp deployment/gudangpojok.service /etc/systemd/system/
   sudo systemctl daemon-reload
   sudo systemctl enable gudangpojok
   sudo systemctl start gudangpojok
   ```

3. Cek status:
   ```bash
   sudo systemctl status gudangpojok
   # Pastikan statusnya Active (running)
   ```

## 3. Setup Nginx (Reverse Proxy)
Nginx akan menerima request dari domain dan meneruskannya ke localhost:8000.

1. Copy config nginx:
   ```bash
   sudo cp deployment/nginx-cikarang.bhumiteduh.my.id.conf /etc/nginx/sites-available/cikarang.bhumiteduh.my.id
   ```

2. Enable site:
   ```bash
   sudo ln -s /etc/nginx/sites-available/cikarang.bhumiteduh.my.id /etc/nginx/sites-enabled/
   ```

3. Test dan Restart Nginx:
   ```bash
   sudo nginx -t
   sudo systemctl restart nginx
   ```

## 4. Setup HTTPS (SSL)
Gunakan Certbot untuk mengamankan domain dengan HTTPS (Gratis).

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d cikarang.bhumiteduh.my.id
```

Selesai! Aplikasi sekarang bisa diakses di https://cikarang.bhumiteduh.my.id
