<?php

namespace App\Http\Controllers;

use App\Models\Invoce;
use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Carbon\Carbon;


class InvoceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $invoces = Invoce::all();
        // $products = $invoces->products; // Obtiene los productos relacionados
        
        $invoices = Invoce::with(['table', 'products'])
            ->where('inv_finish', 1) 
            ->orderBy('inv_date_begin', 'desc')
            ->paginate(5);

       
        // return response()->json($invoices);
        return Inertia::render('Invoces/index', [
            'invoices' => $invoices
            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Invoce $invoce)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoce $invoce)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Invoce $invoce)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoce $invoce)
    {
        //
    }

    public function search(Request $request) 
    {
        $fechaInicio = $request->query('inv_date_begin');
        $fechaFin = $request->query('inv_date_finish');

        $invoices = Invoce::with(['table', 'products'])
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('inv_date_begin', [
                    $fechaInicio . ' 00:00:00',
                    $fechaFin . ' 23:59:59'
                ])->where('inv_finish', 1); 
            }, function ($query) {
                $query->where('inv_finish', 1); // 👈 También si no hay fechas
            })
            ->orderBy('inv_date_begin', 'desc')
            ->paginate(5);


        $total = DB::table('invoces')
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('inv_date_begin', [
                    $fechaInicio . ' 00:00:00',
                    $fechaFin . ' 23:59:59'
                ])->where('inv_finish', 1); 
            })->sum('inv_totalAmount');

        $cantidadFacturas = DB::table('invoces')
            ->when($fechaInicio && $fechaFin, function ($query) use ($fechaInicio, $fechaFin) {
                $query->whereBetween('inv_date_begin', [
                    $fechaInicio . ' 00:00:00',
                    $fechaFin . ' 23:59:59'
                ]);
            })
            ->count();

 
        return Inertia::render('Invoces/index', [
           
            'invoices' => $invoices->appends([
                'inv_date_begin' => request('inv_date_begin'),
                'inv_date_finish' => request('inv_date_finish')
            ]),
            'filters' => request()->only(['inv_date_begin', 'inv_date_finish']),
            'total' => $total,
            'cantidadFacturas' => $cantidadFacturas,
        ]);
    }

    function addProductoToFacturaOnMesa(Request $request, Table $mesa, Product $producto) {

        $facturaReciente = Invoce::where('inv_tableId', $mesa->tab_id)->orderBy('inv_date_begin', 'desc')->first();
        
        $producto = Product::find($producto->pro_id);

        // Verificar si se encontraron la factura y la producto
        if ($facturaReciente && $producto) {
            // Guardar la relación en la tabla pivot
            $facturaReciente->products()->attach($producto->pro_id);
            $facturaReciente->inv_totalAmount += $producto->pro_price;
            $facturaReciente->save();
            
            return redirect()->route('mesas.show',$mesa->tab_id);
        } 
    }


    public function removeProductoFromFacturaOnMesa(Request $request, Table $mesa, Product $producto){
        // Obtener la factura más reciente
        $facturaReciente = Invoce::where('inv_tableId', $mesa->tab_id)
            ->orderBy('inv_date_begin', 'desc')
            ->first();

        // Verificar si la factura existe
        if (!$facturaReciente) {
            return redirect()->route('mesas.show', $mesa->tab_id)
                ->with('error', 'No se encontró una factura asociada a esta mesa.');
        }

        // Verificar si el producto existe en la tabla pivot y obtener `invDet_id`
        $pivotEntry = DB::table('invoce_details')
            ->where('invDet_invoceId', $facturaReciente->inv_id)
            ->where('invDet_proId', $producto->pro_id)
            ->first();

        if ($pivotEntry) {
            // Eliminar la entrada específica
            DB::table('invoce_details')
                ->where('invDet_id', $pivotEntry->invDet_id)
                ->delete();

            // Restar el precio del producto eliminado del total de la factura
            $facturaReciente->inv_totalAmount -= $producto->pro_price;
            $facturaReciente->save();

            return redirect()->route('mesas.show', $mesa->tab_id)
                ->with('success', 'Producto eliminado correctamente.');
        }

        return redirect()->route('mesas.show', $mesa->tab_id)
            ->with('error', 'El producto no está asociado a esta factura.');
    }



    
}
