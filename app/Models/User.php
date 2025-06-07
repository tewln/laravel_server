<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasAdmin;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasAdmin;
    
    /**
     * Имя таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * Отключение автоинкремента для первичного ключа
     *
     * @var bool
     */
    public $incrementing = false;
    
    /**
     * Отключить временные метки
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Атрибуты, которые можно массово присваивать.
     *
     * @var list<string>
     */
    protected $fillable = [
        'surname',
        'firstname',
        'lastname',
        'birth_date',
        'region',
    ];

    /**
     * Атрибуты, которые должны быть скрыты при сериализации.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Атрибуты, которые должны быть приведены к нативным типам.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'birth_date' => 'date',
            'password' => 'hashed',
        ];
    }
    
    /**
     * Получить аутентификационные данные пользователя
     */
    public function authData()
    {
        return $this->hasOne(UserAuthData::class, 'user_id');
    }
    
    /**
     * Получить контакты пользователя
     */
    public function contacts()
    {
        return $this->hasMany(UserContact::class, 'user_id');
    }
    
    /**
     * Получить заказы пользователя
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id');
    }

    /**
     * Получить email пользователя через отношение с UserAuthData
     */
    public function getEmailAttribute()
{
    return $this->authData ? $this->authData->login : null;
}

    /**
     * Получить имя пользователя для отображения
     */
    public function getNameAttribute()
    {
        return $this->firstname . ' ' . $this->surname;
    }
}
