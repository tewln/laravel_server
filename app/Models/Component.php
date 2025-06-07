<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Component extends Model
{
    use HasFactory;

    /**
     * Имя таблицы, связанной с моделью.
     *
     * @var string
     */
    protected $table = 'components';

    /**
     * Первичный ключ модели.
     *
     * @var string
     */
    protected $primaryKey = 'product_id';

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
        'product_id',
        'vendor',
        'category',
    ];

    /**
     * Получить данные о продукте, связанный с этим компонентом.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(
            Product::class,
            'product_id'
        );
    }
}