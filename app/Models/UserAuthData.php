<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAuthData extends Model
{
    use HasFactory;

    /**
     * Имя таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'users_auth_data';
    
    /**
     * Первичный ключ модели.
     *
     * @var string
     */
    protected $primaryKey = 'user_id';
    
    /**
     * Отключение автоинкремента для первичного ключа
     *
     * @var bool
     */
    public $incrementing = false;
    
    /**
     * Отключение временных меток
     *
     * @var bool
     */
    public $timestamps = false;
    /**
     * Атрибуты, которые можно массово присваивать.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'login',
        'password',
        'role',
    ];
    
    /**
     * Атрибуты, которые должны быть скрыты при сериализации.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Получить пользователя, связанного с этими аутентификационными данными.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}