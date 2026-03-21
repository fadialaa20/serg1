# TODO: إصلاح Migration للتوافق مع PostgreSQL

## الخطوات:

### 1. تعديل ملف الـ migration [حالي]
- تحديث `database/migrations/2026_03_21_115743_rename_app_amount_to_bank_amount_in_capitals_table.php`
  - استخدام Laravel Schema methods بدلاً من raw SQL للتوافق مع PG و MySQL.

### 2. اختبار محلي
- `php artisan migrate:rollback --step=1`
- `php artisan migrate`

### 3. نشر على Render
- commit & push

### حالة الحالية:
- [x] الخطة تمت الموافقة عليها
- [x] تم تعديل الـ migration
- [x] تم الاختبار المحلي ✅ (الميجريشن [6] Ran ✓)
- [x] تم النشر ✅ (git push نجح)

**ملاحظة:** تأكد من تثبيت `doctrine/dbal` إذا لم يكن موجوداً: `composer require doctrine/dbal`
