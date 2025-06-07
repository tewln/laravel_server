<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderListHistory extends Model
{
    use HasFactory;

    /**
     * Имя таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'db_project.order_lists_history';
    
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
    protected $primaryKey = ['user_id', 'created_at', 'product_id', 'update_date'];
    
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
        'product_id',
        'quantity',
        'update_date',
    ];

    /**
     * Атрибуты, которые должны быть приведены к нативным типам.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
        'update_date' => 'datetime',
    ];

    /**
     * Получить заказ, к которому относится эта запись истории.
     */
    public function order()
    {
        return $this->belongsTo(OrderHistory::class, ['user_id', 'created_at', 'update_date'], ['user_id', 'created_at', 'update_date']);
    }

    /**
     * Получить продукт, связанный с этой записью истории заказа.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}