<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function log($action, $module, $description, $userId = null, $userName = 'Admin')
    {
        try {
            self::create([
                'user_id' => $userId,
                'user_name' => $userName,
                'action' => $action,
                'module' => $module,
                'description' => $description,
                'ip_address' => request()->ip() ?? '127.0.0.1',
            ]);
        } catch (\Throwable $e) {
            // Silently continue if audit log fails
        }
    }
}
