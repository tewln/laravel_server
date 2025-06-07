<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    /**
     * Имя таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'products';
    
    /**
     * Первичный ключ модели.
     *
     * @var string
     */
    protected $primaryKey = 'id';
    
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
        'product_type',
        'name',
        'company',
        'price',
        'description',
    ];

    /**
     * Атрибуты, которые должны быть приведены к определённым типам.
     *
     * @var array
     */
    protected $casts = [
        'product_type' => 'string',
        'price' => 'decimal:2',
    ];

    /**
     * Получить данные о периферии, связанную с этим продуктом.
     */
    public function peripheral(): HasOne
    {
        return $this->hasOne(
            Peripheral::class,
            'product_id'
        );
    }

    /**
     * Получить данные о компоненте, связанном с этим продуктом.
     */
    public function component(): HasOne
    {
        return $this->hasOne(
            Component::class,
            'product_id'
        );
    }

    /**
     * Получить данные о программном обеспечении, связанным с этим продуктом.
     */
    public function software(): HasOne
    {
        return $this->hasOne(
            Software::class,
            'product_id'
        );
    }

    /**
     * Получить записи о наличии продукта на складах.
     */
    public function inventories()
    {
        return $this->hasMany(
            WarehouseInventory::class,
            'product_id'
        );
    }

    /**
     * Получить записи о поставках продукта.
     */
    public function deliveries()
    {
        return $this->hasMany(
            Delivery::class,
            'product_id'
        );
    }

    /**
     * Получить записи заказов этого продукта.
     */
    public function orderLists()
    {
        return $this->hasMany(
            OrderList::class,
            'product_id'
        );
    }
}