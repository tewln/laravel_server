<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Peripheral;
use App\Models\Component;
use App\Models\Software;
use App\Models\WarehouseInventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Отобразить список всех товаров
     */
    public function index(Request $request)
    {
        $query = Product::query();        // Фильтрация по типу продукта
        if ($request->has('type') && !empty($request->type)) {
        $query->where('product_type', $request->type);
        }

        // Фильтрация по имени (поиск)
        if ($request->has('search') && !empty($request->search)) {
        $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        // Фильтрация по ценовому диапазону
        if ($request->has('min_price') && !empty($request->min_price)) {
        $query->where('price', '>=', $request->min_price);
        }
        if ($request->has('max_price') && !empty($request->max_price)) {
            $query->where('price', '<=', $request->max_price);
        }

        // Сортировка
        $sortField = $request->input('sort', 'name');
        $sortDirection = $request->input('direction', 'asc');
        $allowedSortFields = ['name', 'price', 'company', 'product_type'];
        if (!in_array($sortField, $allowedSortFields)) {
            $sortField = 'name';
        }
        
        $query->orderBy($sortField, $sortDirection);

        $products = $query->paginate(15);
        return view('products.index', compact('products'));
    }

    /**
     * Отобразить конкретный товар
     */
    public function show($id)
    {
        $product = Product::findOrFail($id);

        // Получаем дополнительные данные в зависимости от типа продукта
        $details = null;
        if ($product->product_type === 'peripheral') {
            $details = $product->peripheral;
        } elseif ($product->product_type === 'component') {
            $details = $product->component;
        } elseif ($product->product_type === 'software') {
            $details = $product->software;
        }

        // Получаем информацию о наличии на складах
        $inventory = WarehouseInventory::where('product_id', $id)
            ->with('warehouse')
            ->get();

        return view('products.show', compact('product', 'details', 'inventory'));
    }

    /**
     * Показать наличие товара по регионам
     */
    public function availability($id)
    {
        // Получение наличия товаров по регионам на основе запроса из репозитория
        $availability = DB::table('db_project.warehouses as w')
            ->join('db_project.warehouse_inventory as wi', 'w.id', '=', 'wi.warehouse_id')
            ->select('w.region', DB::raw('SUM(wi.quantity) as total_quantity'))
            ->where('wi.product_id', $id)
            ->where('w.end_date', '>', DB::raw('CURRENT_DATE'))
            ->groupBy('w.region')
            ->get();

        $product = Product::findOrFail($id);

        return view('products.availability', compact('product', 'availability'));
    }
    
    /**
     * Проверить наличие товара в конкретном регионе
     */
    public function checkAvailabilityInRegion($productId, $region, $quantity = 1)
    {
        // Используем функцию из SQL для проверки наличия товара
        $available = DB::selectOne(
            "SELECT db_project.check_product_availability(?, ?, ?) as available", 
            [$productId, $region, $quantity]
        );
        
        return response()->json(['available' => $available->available]);
    }

    /**
     * Получить популярные товары
     */
    public function getPopularProducts()
    {
        // Получаем самые популярные товары на основе заказов
        $popularProducts = DB::table('db_project.order_lists as ol')
            ->join('db_project.products as p', 'ol.product_id', '=', 'p.id')
            ->select('p.id', 'p.name', 'p.price', 'p.product_type', 
                     DB::raw('SUM(ol.quantity) as total_ordered'))
            ->groupBy('p.id', 'p.name', 'p.price', 'p.product_type')
            ->orderBy('total_ordered', 'desc')
            ->limit(10)
            ->get();
            
        return view('products.popular', compact('popularProducts'));
    }
}