<?php
// app/Services/IdempotencyService.php

namespace App\Services;

use App\Models\IdempotencyKey;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class IdempotencyService
{
    // Key validate ও cache check
    public function check(string $key, string $endpoint): ?array
    {
        $record = IdempotencyKey::where('key', $key)
                                ->where('user_id', Auth::id())
                                ->where('endpoint', $endpoint)
                                ->first();

        if (!$record) return null;

        // Expire হলে পুরনো record মুছো
        if ($record->isExpired()) {
            $record->delete();
            return null;
        }

        // Cache hit — পুরনো response ফেরত দাও
        return [
            'status' => $record->response_status,
            'body'   => $record->response_body,
        ];
    }

    // Successful response save করো
    public function store(
        string $key,
        string $endpoint,
        int $status,
        array $body
    ): void {
        IdempotencyKey::updateOrCreate(
            [
                'key'      => $key,
                'user_id'  => Auth::id(),
                'endpoint' => $endpoint,
            ],
            [
                'response_status' => $status,
                'response_body'   => $body,
                'expires_at'      => now()->addHours(24),
            ]
        );
    }

    // Random key generate করুন (frontend-এ ব্যবহারের জন্য)
    public static function generate(): string
    {
        return \Str::uuid()->toString();
    }

    // পুরনো expired keys cleanup
    public static function cleanup(): void
    {
        IdempotencyKey::where('expires_at', '<', now())->delete();
    }

}