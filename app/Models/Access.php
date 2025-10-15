<?php

declare(strict_types = 1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Access extends Model
{
    protected $table = 'accesses';

    protected $guarded = [];

    protected $casts = [
        'success'    => 'boolean',
        'metadata'   => 'array',
        'created_at' => 'datetime',
    ];

    public $timestamps = false;

    /**
     * Get the user that performed the login attempt
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for successful logins
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    /**
     * Scope for failed logins
     */
    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }

    /**
     * Scope for login attempts
     */
    public function scopeLoginAttempts($query)
    {
        return $query->where('action', 'login_attempt');
    }

    /**
     * Scope for logout actions
     */
    public function scopeLogouts($query)
    {
        return $query->where('action', 'logout');
    }

    /**
     * Scope for today's activities
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope for specific IP
     */
    public function scopeFromIp($query, $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /**
     * Get browser name from user agent
     */
    public function getBrowserAttribute()
    {
        if (strpos($this->user_agent, 'Chrome') !== false) {
            return 'Chrome';
        } elseif (strpos($this->user_agent, 'Firefox') !== false) {
            return 'Firefox';
        } elseif (strpos($this->user_agent, 'Safari') !== false) {
            return 'Safari';
        } elseif (strpos($this->user_agent, 'Edge') !== false) {
            return 'Edge';
        } else {
            return 'Outro';
        }
    }

    /**
     * Get operating system from user agent
     */
    public function getOperatingSystemAttribute()
    {
        if (strpos($this->user_agent, 'Windows') !== false) {
            return 'Windows';
        } elseif (strpos($this->user_agent, 'Mac') !== false) {
            return 'Mac';
        } elseif (strpos($this->user_agent, 'Linux') !== false) {
            return 'Linux';
        } elseif (strpos($this->user_agent, 'Android') !== false) {
            return 'Android';
        } elseif (strpos($this->user_agent, 'iOS') !== false) {
            return 'iOS';
        } else {
            return 'Outro';
        }
    }

    /**
     * Get device type from user agent
     */
    public function getDeviceTypeAttribute()
    {
        if (strpos($this->user_agent, 'Mobile') !== false ||
            strpos($this->user_agent, 'Android') !== false ||
            strpos($this->user_agent, 'iPhone') !== false) {
            return 'Mobile';
        } elseif (strpos($this->user_agent, 'Tablet') !== false ||
                  strpos($this->user_agent, 'iPad') !== false) {
            return 'Tablet';
        } else {
            return 'Desktop';
        }
    }

    /**
     * Get ativo badge class
     */
    public function getStatusBadgeAttribute()
    {
        if ($this->success) {
            return 'badge badge-success';
        } else {
            return 'badge badge-danger';
        }
    }

    /**
     * Get action label
     */
    public function getActionLabelAttribute()
    {
        switch ($this->action) {
            case 'login_attempt':
                return $this->success ? 'Login Realizado' : 'Tentativa de Login';
            case 'logout':
                return 'Logout';
            default:
                return ucfirst($this->action);
        }
    }
}
