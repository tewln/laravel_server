<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderHistory extends Model
{
    use HasFactory;

    /**
     * Имя таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'db_project.orders_history';
    
    /**
     * Отключение автоинкремента для первичного ключа
     *
     * @var bool
     */
    public $incrementing = false;
    
    /**
     * Определение первичного ключа
     *
     * @var array
     */
    protected $primaryKey = ['user_id', 'created_at', 'update_date'];
    
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
        'created_at',
        'valid_until',
        'order_status',
        'update_date',
    ];

    /**
     * Атрибуты, которые должны быть приведены к нативным типам.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'valid_until' => 'datetime',
        'update_date' => 'datetime',
    ];

    /**
     * Получить связанный заказ.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, ['user_id', 'created_at'], ['user_id', 'created_at']);
    }

    /**
     * Получить пользователя, которому принадлежит заказ.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}