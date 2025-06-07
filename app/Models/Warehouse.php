<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    /**
     * Имя таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'warehouses';
    
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
        'name',
        'region',
        'start_date',
        'end_date',
    ];
    
    /**
     * Атрибуты, которые должны быть приведены к нативным типам.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Получить инвентарь склада.
     */
    public function inventory()
    {
        return $this->hasMany(WarehouseInventory::class, 'warehouse_id');
    }

    /**
     * Получить поставки на склад.
     */
    public function deliveries()
    {
        return $this->hasMany(Delivery::class, 'warehouse_id');
    }
    
    /**
     * Проверить, активен ли склад на текущую дату.
     */
    public function isActive()
    {
        $currentDate = now();
        
        if ($this->end_date && $this->end_date->format('Y-m-d') === '5999-12-31') {
            return $currentDate >= $this->start_date;
        }
        
        return $currentDate->between($this->start_date, $this->end_date);
    }
    
    /**
     * Получить отформатированную дату закрытия
     */
    public function getFormattedEndDateAttribute()
    {
        if (!$this->end_date) {
            return 'Не указана';
        }
        
        // Если дата 5999-12-31, то склад работает бессрочно
        if ($this->end_date->format('Y-m-d') === '5999-12-31') {
            return 'Бессрочно';
        }
        
        return $this->end_date->format('d.m.Y');
    }
}