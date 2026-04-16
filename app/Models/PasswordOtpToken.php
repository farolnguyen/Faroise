<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class PasswordOtpToken extends Model
{
    protected $fillable = ['email', 'otp', 'expires_at', 'used_at'];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at'    => 'datetime',
        ];
    }

    public static function generate(string $email): string
    {
        self::where('email', $email)->delete();

        $plain = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        self::create([
            'email'      => $email,
            'otp'        => Hash::make($plain),
            'expires_at' => now()->addMinutes(10),
        ]);

        return $plain;
    }

    public static function verify(string $email, string $plain): bool
    {
        $token = self::where('email', $email)
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->latest()
            ->first();

        if (!$token || !Hash::check($plain, $token->otp)) {
            return false;
        }

        $token->update(['used_at' => now()]);
        return true;
    }
}
