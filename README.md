# 🎵 Faroise — Ambient Sound Mixer

> Mix ambient sounds to help you focus, relax, or drift into sleep. Free, beautiful, no distractions.

**Live:** [faroise.online](https://faroise.online)

---

## About

Faroise is a personal web application that lets users layer multiple ambient sounds — rain, forest, café noise, ocean waves, and more — into custom mixes. Whether you need deep focus for work, a calming backdrop for sleep, or just a moment of peace, Faroise helps you build your perfect soundscape.

This project was built and is maintained by **Farol Nguyen** as a personal project.

---

## Features

- 🎛️ **Sound Mixer** — Layer up to 9 ambient sounds simultaneously with individual volume control
- 💾 **Save Mixes** — Create an account and save your favourite combinations
- 🌐 **Share Mixes** — Share your mix via a unique URL
- 🔍 **Explore** — Browse public mixes created by other users
- ⏱️ **Sleep Timer** — Auto-stop all sounds after a set duration
- 🌙 **Sleep Mode** — Fullscreen ambient display for falling asleep
- 🔔 **Alarm** — Wake up to your favourite mix
- 🏷️ **Tags & Categories** — Filter sounds by mood or category
- 📱 **PWA** — Installable as a mobile/desktop app
- 🔐 **OTP Password Reset** — Secure email-based verification for password changes

---

## Tech Stack

| Layer | Technology |
|-------|-----------|
| Backend | Laravel 13 + PHP 8.4 |
| Frontend | Alpine.js + TailwindCSS |
| Audio | Howler.js |
| Database | MySQL 8 |
| Auth | Laravel Breeze (Blade) |
| Deployment | Docker + GitHub Actions CI/CD |
| Web Server | Nginx |
| SSL | Let's Encrypt (auto-renew) |

---

## Local Development

### Requirements
- PHP 8.4+
- Composer
- Node.js 22+
- MySQL 8

### Setup

```bash
git clone https://github.com/your-username/faroise.git
cd faroise

composer install
npm install

cp .env.example .env
php artisan key:generate

# Configure DB in .env, then:
php artisan migrate --seed

npm run dev
```

---

## Deployment

This project uses **GitHub Actions** for CI/CD. On every push to `main`:

1. Docker image is built (multi-stage: Node → Composer → PHP-FPM)
2. Image is pushed to GitHub Container Registry
3. VPS pulls the new image and restarts containers
4. `php artisan migrate --force` runs automatically

See `.github/workflows/deploy.yml` for the full pipeline.

---

## Project Structure

```
app/
├── Http/Controllers/       # Route controllers
│   └── Admin/              # Admin panel controllers
├── Http/Requests/Admin/    # Form Request validation classes
├── Mail/                   # Mailables (OTP email)
├── Models/                 # Eloquent models
resources/
├── js/
│   ├── app.js              # Alpine.js entry point
│   └── modules/            # JS feature modules (audio, timer, sleep…)
├── views/
│   ├── home/               # Home page partials
│   ├── admin/              # Admin panel views
│   └── layouts/            # App layouts
docker/
├── nginx/                  # Nginx configs (HTTP + HTTPS)
└── php/                    # PHP-FPM config
```

---

## Contact

This project is developed and maintained by **Farol Nguyen**.

For any questions, suggestions, or feedback, feel free to reach out:

📧 **farolnguyen824@gmail.com**

---

## License

This is a personal project. All rights reserved © Farol Nguyen.
