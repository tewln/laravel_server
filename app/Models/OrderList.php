<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderList extends Model
{
    use HasFactory;

    /**
     * Имя таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'db_project.order_lists';
    
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
    protected $primaryKey = ['user_id', 'created_at', 'product_id'];
    
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
    ];

    /**
     * Атрибуты, которые должны быть приведены к нативным типам.
     *
     * @var array
     */
    protected $casts = [
        'created_at' => 'datetime',
    ];

    /**
     * Получить заказ, к которому относится эта запись.
     */
    public function order()
    {
        return $this->belongsTo(Order::class, ['user_id', 'created_at'], ['user_id', 'created_at']);
    }

    /**
     * Получить продукт, связанный с этой записью заказа.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Получить историю изменения этой записи заказа.
     */
    public function history()
    {
        return $this->hasMany(OrderListHistory::class, ['user_id', 'created_at', 'product_id'], ['user_id', 'created_at', 'product_id']);
    }
}