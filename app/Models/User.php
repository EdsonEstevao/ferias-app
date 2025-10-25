<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'last_activity_at',
        'is_online',
        'last_ip',
        'user_agent',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_at' => 'datetime',
        ];
    }

     public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    /**
     * Relacionamento com os logs de auditoria onde o usuário é o responsável pela ação
     */
    public function auditLogsAsActor()
    {
        return $this->hasMany(AuditLog::class, 'user_id');
    }


    protected static function boot()
    {
        parent::boot();

        //Marcar como offine quando fizer logout
        static::updating(function ($user) {
            if($user->isDirty('is_online') && !$user->is_online) {
                $user->last_activity_at = null;
            }

        });
    }

    /**
     * Marcar usuario como online
     * @return void
     */
    public function markAsOnline(string $ip = "", string $userAgent = "")
    {
        $this->update([
            'is_online' => true,
            'last_activity_at' => now(),
            'last_ip' => $ip ?: $this->last_ip,
            'user_agent' => $userAgent ?: $this->user_agent,
        ]);

        // limpa cache
        Cache::forget('online_users_count');
    }

    /**
     * Marcar usuario como offline
     * @return void
     */
    public function markAsOffline()
    {
        $this->update([
            'is_online' => false,
            'last_activity_at' => null,
        ]);

        // limpa cache
        Cache::forget('online_users_count');
    }

    /**
     * Atualizar última atividade
     * @return void
     */

    public function updateLastActivityWithDetails(string $ip = "", string $userAgent = "")
    {

        //só atualiza a cada 30 segundos para evitar muitas queries

        if(!$this->last_activity_at || $this->last_activity_at->diffInSeconds(now()) > 30) {

            $updateData = [
                'is_online' => true,
                'last_activity_at' => now(),
            ];


            // Adicionar ip apenas se fornecido
            if($ip) {
                $updateData['last_ip'] = $ip;
            }


            // Adicionar user agent apenas se fornecido e limitar tamanho
            if($userAgent) {
                $updateData['user_agent'] = substr($userAgent, 0, 255);
            }


            $this->update($updateData);


            Cache::forget('online_users_count');
        }

    }

    public function updateLastActivity()
    {
        $this->updateLastActivityWithDetails();
    }

    /**
     * verificar se usuario esta online (considera inativo  após 5 minutos)
     * @return boolean
     */

    public function getIsCurrentlyOnlineAttribute(): bool
    {
        if (!$this->is_online || !$this->last_activity_at) {
            return false;
        }


        // return ( new DateTime( $this->last_activity_at))->diffInMinutes(new DateTime())->lte(5);
        return true;
    }

    /**
     * Scope para usuarios online
     * @return void
     */

    public function scopeOnline($query)
    {
        return $query->where('is_online', true)
                        ->where('last_activity_at', '>=', now()->subMinutes(5));
    }


    /**
     * Scope para usuarios ativos recentemente (ultima hora)
     * @return void
     */

    public function scopeRecentlyActive($query)
    {
        return $query->where('last_activity_at', '>=', now()->subHour());
    }

    /**
     * Obter tempo desde última atividade
     * @return void
     */

    public function getLastActivityHumansAttribute():string
    {
        if(!$this->last_acitivity_at) {
            return 'Nunca';
        }

        // return $this->last_activity_at->diffForHumans();
        return '';
    }
}
