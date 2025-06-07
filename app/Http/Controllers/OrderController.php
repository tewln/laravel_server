<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderList;
use App\Models\OrderHistory;
use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{    
    /**
     * Показать список заказов текущего пользователя
     */
    public function index()
    {
        $userId = Auth::id();
        $orders = DB::table('db_project.orders')
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('orders.index', compact('orders'));
    }
    
    /**
     * Показать детальную информацию о заказе
     */
    public function show($userId, $createdAt)
    {
        $createdAt = urldecode($createdAt);
        
        // Проверка доступа (только владелец заказа или администратор)
        $currentUser = Auth::user();
        $currentUserId = $currentUser->id;
        
        if ($currentUserId != $userId && !($currentUser->authData && $currentUser->authData->role === 'admin')) {
            return abort(403, 'Unauthorized action.');
        }
        
        // Находим заказ по составному ключу
        $order = Order::where('user_id', $userId)
            ->where('created_at', $createdAt)
            ->firstOrFail();
            
        // Получаем позиции заказа
        $orderItems = OrderList::where('user_id', $userId)
            ->where('created_at', $createdAt)
            ->with('product')
            ->get();
            
        // Рассчитываем общую стоимость
        $totalCost = $order->calculateTotal();
        
        // Получаем историю заказа
        $history = OrderHistory::where('user_id', $userId)
            ->where('created_at', $createdAt)
            ->orderBy('update_date', 'desc')
            ->get();
            
        return view('orders.show', compact('order', 'orderItems', 'totalCost', 'history'));
    }

    /**
     * Получить статистику по заказам по регионам
     */
    public function orderStatsByRegion()
    {
        // Только для администраторов
        $currentUser = Auth::user();
        if (!$currentUser || !($currentUser->authData && $currentUser->authData->role === 'admin')) {
            return abort(403, 'Unauthorized action.');
        }
        
        // Статистика заказов по регионам на основе анализа из репозитория
        $regionStats = DB::table('db_project.orders as o')
            ->join('db_project.users as u', 'o.user_id', '=', 'u.id')
            ->join('db_project.order_lists as ol', function($join) {
                $join->on('o.user_id', '=', 'ol.user_id')
                    ->on('o.created_at', '=', 'ol.created_at');
            })
            ->join('db_project.products as p', 'ol.product_id', '=', 'p.id')
            ->select(
                'u.region',
                DB::raw('COUNT(DISTINCT o.user_id, o.created_at) as order_count'),
                DB::raw('SUM(p.price * ol.quantity) as total_value')
            )
            ->groupBy('u.region')
            ->get();
        
        return view('orders.stats', compact('regionStats'));
    }
      /**
     * Получить динамику заказов по дням
     */
    public function orderDynamics()
    {
        // Только для администраторов
        $currentUser = Auth::user();
        if (!$currentUser || !($currentUser->authData && $currentUser->authData->role === 'admin')) {
            return abort(403, 'Unauthorized action.');
        }
        
        // Динамика заказов по дням на основе анализа из репозитория
        $orderDynamics = DB::table('db_project.orders')
            ->select(
                DB::raw('DATE(created_at) as order_date'),
                DB::raw('COUNT(*) as order_count')
            )
            ->groupBy('order_date')
            ->orderBy('order_date')
            ->get();
            
        return view('orders.dynamics', compact('orderDynamics'));
    }
}