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

### حالة الحالية: (تم إضافة fix migration للـ prod DB)

- [x] الخطة تمت الموافقة عليها
- [x] تم تعديل الـ migration  
- [x] تم الاختبار المحلي ✅ (الميجريشن [6] Ran ✓)
- [x] تم النشر ✅ (git push نجح)
- [x] **تم إضافة fix migration** `2026_03_21_125852_fix_capital_bank_amount_column_for_production.php` لإصلاح DB الإنتاج
- [x] الخطة تمت الموافقة عليها
- [x] تم تعديل الـ migration
- [x] تم الاختبار المحلي ✅ (الميجريشن [6] Ran ✓)
- [x] تم النشر ✅ (git push نجح)

**تم:** doctrine/dbal مثبت ✅ composer.json + lock محدث
