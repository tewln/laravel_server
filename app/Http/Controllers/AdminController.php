<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    /**
     * Конструктор для проверки прав администратора
     */
    public function __construct()
    {
    }    /**
         * Проверяет, является ли текущий пользователь администратором
         */
    private function checkAdmin()
    {
        $user = Auth::user();
        if (!$user || !($user->authData && $user->authData->role === 'admin')) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Главная панель администратора
     */
    public function dashboard()
    {
        $this->checkAdmin();
        
        // Общая статистика по заказам
        $totalOrders = DB::table('db_project.orders')->count();
        $totalOrdersValue = DB::select("
            SELECT SUM(p.price * ol.quantity) as total_value
            FROM db_project.orders o
            JOIN db_project.order_lists ol ON o.user_id = ol.user_id AND o.created_at = ol.created_at
            JOIN db_project.products p ON ol.product_id = p.id
        ")[0]->total_value;
        
        // Количество пользователей
        $totalUsers = DB::table('db_project.users')->count();
        
        // Количество продуктов
        $totalProducts = DB::table('db_project.products')->count();
        
        // Количество товаров на складах
        $totalInventory = DB::table('db_project.warehouse_inventory')
            ->sum('quantity');
            
        // Последние заказы
        $latestOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        return view('admin.dashboard', compact(
            'totalOrders', 
            'totalOrdersValue', 
            'totalUsers', 
            'totalProducts', 
            'totalInventory',
            'latestOrders'
        ));
    }

    /**
     * Обработка ожидающих поставок (вызывает хранимую процедуру)
     */
    public function processDeliveries(Request $request)
    {
        $this->checkAdmin();
        try {
            DB::statement('CALL db_project.process_pending_deliveries()');
            return redirect()->route('admin.dashboard')->with('success', 'Поставки успешно обработаны!');
        } catch (\Exception $e) {
            return redirect()->route('admin.dashboard')->with('error', 'Ошибка при обработке поставок: ' . $e->getMessage());
        }
    }
}