<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    /**
     * Имя таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'db_project.orders';
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
    protected $primaryKey = ['user_id', 'created_at'];
    
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
    ];
    
    /**
     * Атрибуты, которые должны быть приведены к нативным типам.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'valid_until' => 'datetime',
    ];

    /**
     * Получить пользователя, который сделал заказ.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Получить список товаров в заказе.
     */
    public function orderLists(): HasMany
    {
        return $this->hasMany(OrderList::class, 'user_id', 'user_id')
                    ->where('created_at', $this->created_at);
    }

    /**
     * Получить историю изменения заказа.
     */
    public function history(): HasMany
    {
        return $this->hasMany(OrderHistory::class, ['user_id', 'created_at'], ['user_id', 'created_at']);
    }
    
    /**
     * Рассчитать общую стоимость заказа.
     */
    public function calculateTotal()
    {
        return $this->orderLists->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }
}