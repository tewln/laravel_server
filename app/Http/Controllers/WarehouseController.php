<?php

namespace App\Http\Controllers;

use App\Models\Warehouse;
use App\Models\WarehouseInventory;
use App\Models\Delivery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class WarehouseController extends Controller
{    /**
     * Показать список всех складов
     */
    public function index(Request $request)
    {
        $query = Warehouse::query();
        if ($request->has('region') && !empty($request->region)) {
            $query->whereRaw("region = ?", [$request->region]);
        }
        if ($request->boolean('active_only')) {
            $query->where(function($q) {
                $q->where('end_date', '>', DB::raw('CURRENT_DATE'))
                  ->orWhere('end_date', '5999-12-31');
            });
        }$warehouses = $query->orderBy('region')->paginate(15);
        $regions = DB::table('db_project.warehouses')
            ->select('region')
            ->distinct()
            ->pluck('region');
        
        return view('warehouses.index', compact('warehouses', 'regions'));
    }

    /**
     * Показать информацию о конкретном складе
     */
    public function show($id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $inventory = WarehouseInventory::where('warehouse_id', $id)
            ->with('product')
            ->paginate(20);
            
        return view('warehouses.show', compact('warehouse', 'inventory'));
    }

    /**
     * Показать историю поставок на склад
     */
    public function deliveries(Request $request, $id)
    {
        $warehouse = Warehouse::findOrFail($id);
        $query = Delivery::where('warehouse_id', $id)
            ->with('product');
        if ($request->has('date_from') && !empty($request->date_from)) {
            $query->where('delivery_date', '>=', $request->date_from);
        }
        if ($request->has('date_to') && !empty($request->date_to)) {
            $query->where('delivery_date', '<=', $request->date_to);
        }
        $deliveries = $query->orderBy('delivery_date', 'desc')->paginate(15);
        $stats = DB::table('db_project.deliveries as d')
            ->join('db_project.products as p', 'd.product_id', '=', 'p.id')
            ->where('d.warehouse_id', $id)
            ->selectRaw('
                COUNT(*) as total_deliveries,
                SUM(d.quantity * p.price) as total_value,
                AVG(d.quantity * p.price) as avg_delivery_value
            ')
            ->first();
        return view('warehouses.deliveries', compact('warehouse', 'deliveries', 'stats'));
    }
    
    /**
     * Поиск товаров на складах
     */
    public function searchProducts(Request $request)
    {
        $query = DB::table('db_project.products as p')
            ->join('db_project.warehouse_inventory as wi', 'p.id', '=', 'wi.product_id')
            ->join('db_project.warehouses as w', 'wi.warehouse_id', '=', 'w.id')
            ->select('p.id', 'p.name', 'p.product_type', 'p.price', 'w.name as warehouse_name', 'w.region', 'wi.quantity')
            ->where(function($query) {
                $query->where('w.end_date', '>', DB::raw('CURRENT_DATE'))
                      ->orWhere('w.end_date', '5999-12-31');
            });
        if ($request->has('search')) {
            $query->where('p.name', 'like', '%' . $request->search . '%');
        }
        if ($request->has('region')) {
            $query->where('w.region', $request->region);
        }
        if ($request->has('product_type')) {
            $query->where('p.product_type', $request->product_type);
        }
        if ($request->boolean('in_stock')) {
            $query->where('wi.quantity', '>', 0);
        }        $products = $query->paginate(15);
        $regions = DB::table('db_project.warehouses')
            ->select('region')
            ->distinct()
            ->pluck('region');            
        return view('warehouses.search', compact('products', 'regions'));
    }
}