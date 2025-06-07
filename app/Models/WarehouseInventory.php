<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WarehouseInventory extends Model
{
    use HasFactory;

    /**
     * Имя таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'db_project.warehouse_inventory';
    
    /**
     * Отключение автоинкремента для первичного ключа
     *
     * @var bool
     */
    public $incrementing = false;
    
    /**
     * Определение составного первичного ключа
     *
     * @var array
     */
    protected $primaryKey = ['warehouse_id', 'product_id'];
    
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
        'warehouse_id',
        'product_id',
        'quantity',
    ];

    /**
     * Получить склад, к которому относится эта запись инвентаря.
     */
    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id');
    }

    /**
     * Получить продукт, к которому относится эта запись инвентаря.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}