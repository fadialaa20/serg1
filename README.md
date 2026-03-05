# نظام إدارة أرباح التاجر - Laravel

مشروع Laravel 10 لإدارة رأس المال والمشتريات والمبيعات وحساب الأرباح، بواجهة عربية RTL.

## تشغيل محلي

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan serve
```

## النشر على Render

المشروع مجهز مسبقًا بملفات:
- `Dockerfile`
- `docker/start.sh`
- `render.yaml`

### 1) ارفع المشروع إلى GitHub

```bash
git init
git add .
git commit -m "Prepare Laravel app for Render deployment"
git branch -M main
git remote add origin <YOUR_GITHUB_REPO_URL>
git push -u origin main
```

### 2) أنشئ قاعدة بيانات MySQL خارج Render

Render غالبًا لا يوفر MySQL مُدارة بشكل افتراضي، لذلك استخدم مزود MySQL خارجي (مثل PlanetScale أو Aiven أو أي استضافة MySQL).

احتفظ بهذه القيم:
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### 3) أنشئ Web Service على Render

1. ادخل Render > `New` > `Blueprint`.
2. اختر نفس مستودع GitHub.
3. Render سيقرأ `render.yaml` تلقائيًا.

### 4) أضف متغيرات البيئة المطلوبة

من إعدادات الخدمة في Render أضف:
- `APP_URL` = رابط خدمتك على Render (مثال: `https://your-app.onrender.com`)
- `APP_KEY` = ناتج الأمر التالي محليًا:

```bash
php artisan key:generate --show
```

وأضف بيانات MySQL الخارجية:
- `DB_HOST`
- `DB_PORT`
- `DB_DATABASE`
- `DB_USERNAME`
- `DB_PASSWORD`

### 5) أول نشر

عند أول تشغيل:
- `docker/start.sh` ينفذ `php artisan migrate --force` تلقائيًا (طالما `RUN_MIGRATIONS=true`).
- بعد اكتمال البناء، افتح رابط الخدمة.

## ملاحظات مهمة

- اجعل `APP_DEBUG=false` في الإنتاج.
- إذا أردت تعطيل المايغريشن التلقائي لاحقًا، غيّر:
  - `RUN_MIGRATIONS=false`
- التخزين المؤقت مفعل تلقائيًا عند الإقلاع (`config:cache`, `route:cache`, `view:cache`).
