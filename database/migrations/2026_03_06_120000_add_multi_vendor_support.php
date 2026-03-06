<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('users', 'username')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('username')->nullable()->unique()->after('name');
            });
        }

        if (! Schema::hasColumn('users', 'login_code')) {
            Schema::table('users', function (Blueprint $table) {
                $table->string('login_code', 30)->nullable()->unique()->after('username');
            });
        }

        if (! Schema::hasColumn('users', 'is_admin')) {
            Schema::table('users', function (Blueprint $table) {
                $table->boolean('is_admin')->default(false)->after('login_code');
            });
        }

        if (! Schema::hasColumn('capital', 'user_id')) {
            Schema::table('capital', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        if (! Schema::hasColumn('products', 'user_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        if (! Schema::hasColumn('sales', 'user_id')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->after('id')->constrained()->cascadeOnDelete();
            });
        }

        $makeUniqueEmail = static function (string $preferred): string {
            $email = $preferred;
            $index = 1;

            while (DB::table('users')->where('email', $email)->exists()) {
                $email = 'user' . $index . '+' . Str::random(4) . '@example.local';
                $index++;
            }

            return $email;
        };

        $makePaymentReference = static function (): string {
            do {
                $reference = 'PAY-' . strtoupper(Str::random(10));
            } while (DB::table('users')->where('payment_reference', $reference)->exists());

            return $reference;
        };

        $ensureSystemUser = function (string $username, string $name, string $defaultEmail, string $password, string $loginCode, bool $isAdmin) use ($makeUniqueEmail, $makePaymentReference): int {
            $existing = DB::table('users')->where('username', $username)->first();
            if ($existing) {
                DB::table('users')->where('id', $existing->id)->update([
                    'is_admin' => $isAdmin ? 1 : (int) ($existing->is_admin ?? 0),
                    'login_code' => $existing->login_code ?: $loginCode,
                ]);

                return (int) $existing->id;
            }

            $data = [
                'name' => $name,
                'username' => $username,
                'email' => $makeUniqueEmail($defaultEmail),
                'password' => Hash::make($password),
                'login_code' => $loginCode,
                'is_admin' => $isAdmin,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if (Schema::hasColumn('users', 'payment_reference')) {
                $data['payment_reference'] = $makePaymentReference();
            }

            return (int) DB::table('users')->insertGetId($data);
        };

        $adminId = $ensureSystemUser('admin', 'Admin', 'admin@example.local', 'Admin@12345', 'ADMIN001', true);
        $fadiId = $ensureSystemUser('fadi', 'فادي', 'fadi@example.local', 'Fadi@12345', 'FADI001', false);

        User::query()
            ->whereNull('username')
            ->orderBy('id')
            ->get()
            ->each(function (User $user): void {
                $username = 'user' . $user->id;
                $loginCode = 'USR' . str_pad((string) $user->id, 4, '0', STR_PAD_LEFT);

                $user->forceFill([
                    'username' => $username,
                    'login_code' => $user->login_code ?: $loginCode,
                ])->save();
            });

        User::query()
            ->whereNull('login_code')
            ->orderBy('id')
            ->get()
            ->each(function (User $user): void {
                do {
                    $code = strtoupper(Str::random(8));
                } while (User::query()->where('login_code', $code)->exists());

                $user->forceFill(['login_code' => $code])->save();
            });

        DB::table('products')->whereNull('user_id')->update(['user_id' => $fadiId]);
        DB::table('capital')->whereNull('user_id')->update(['user_id' => $fadiId]);

        DB::table('sales')
            ->whereNull('user_id')
            ->update([
                'user_id' => DB::raw('(SELECT products.user_id FROM products WHERE products.id = sales.product_id)'),
            ]);

        DB::table('users')->where('id', $adminId)->update(['is_admin' => 1]);
    }

    public function down(): void
    {
        if (Schema::hasColumn('sales', 'user_id')) {
            Schema::table('sales', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }

        if (Schema::hasColumn('products', 'user_id')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }

        if (Schema::hasColumn('capital', 'user_id')) {
            Schema::table('capital', function (Blueprint $table) {
                $table->dropConstrainedForeignId('user_id');
            });
        }

        Schema::table('users', function (Blueprint $table) {
            $drop = [];
            if (Schema::hasColumn('users', 'username')) {
                $drop[] = 'username';
            }
            if (Schema::hasColumn('users', 'login_code')) {
                $drop[] = 'login_code';
            }
            if (Schema::hasColumn('users', 'is_admin')) {
                $drop[] = 'is_admin';
            }

            if ($drop !== []) {
                $table->dropColumn($drop);
            }
        });
    }
};
