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

## 5. Setup Auto Deployment (Optional)
Agar aplikasi otomatis ter-update saat push ke GitHub, lakukan langkah ini:

1.  Masuk ke Repository GitHub kamu > **Settings** > **Secrets and variables** > **Actions**.
2.  Klik **New repository secret** dan tambahkan 3 secret berikut:

    | Name | Value |
    |------|-------|
    | `SSH_HOST` | IP Address Server kamu (misal: `103.xxx.xxx.xxx`) |
    | `SSH_USERNAME` | Username login server (misal: `root` atau `ubuntu`) |
    | `SSH_PRIVATE_KEY` | Isi Private Key SSH kamu (Isi dari file `id_rsa` di komputer lokal atau hasil generate baru) |

    **Cara mendapatkan SSH_PRIVATE_KEY:**
    Jika servermu menggunakan SSH key untuk login, buka file private key di komputermu (biasanya di `~/.ssh/id_rsa`) dan copy isinya.
    Jika belum ada, kamu bisa generate di server (`ssh-keygen`), lalu tambahkan public key-nya ke `~/.ssh/authorized_keys` di server.

3.  Setelah disave, coba push perubahan ke branch `main`.
4.  Cek tab **Actions** di GitHub untuk melihat proses deployment berjalan.


## 6. Cara Update Manual (Jika tidak pakai Auto Deploy)
Jika kamu ingin mengupdate aplikasi di server secara manual, ikuti langkah ini:

1.  Login ke server via SSH.
2.  Masuk ke folder project:
    ```bash
    cd /var/www/gudangpojok
    ```
3.  Jalankan perintah update berikut:
    ```bash
    # 1. Ambil kode terbaru
    git pull origin main

    # 2. Update dependencies (PHP & JS)
    composer install --optimize-autoloader --no-dev
    npm install && npm run build

    # 3. Update database
    php artisan migrate --force

    # 4. Bersihkan cache
    php artisan optimize:clear
    php artisan config:cache
    php artisan route:cache
    php artisan view:cache

    # 5. Restart service agar kode baru jalan
    sudo systemctl restart gudangpojok
    ```
