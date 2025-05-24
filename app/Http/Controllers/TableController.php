<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Product;
use App\Models\Invoce;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;



class TableController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tables = Table::all();
        // return response()->json($tables);
        return Inertia::render('Mesas/index', [
            'tables' => $tables
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
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {   

        $table = Table::find($id);
        if ($table->tab_state == 0) {
            // Crear una nueva factura
            $fechaHoraActual = Carbon::now('Europe/Madrid');
            $factura = new Invoce();
            $factura->inv_date_begin = $fechaHoraActual;
            $factura->inv_totalAmount = 0;
            $factura->inv_finish = 0;
            $factura->inv_date_finish = null;
            $factura->inv_tableId = $id;
            $factura->save();

            $table->tab_state = 1;
            $table->save();
        }
        // $nameMesa = Table::where('tab_id', $id)->pluck('tab_name')->first();
        $nameMesa = $table->tab_name;
        
        $facturaReciente = Invoce::where('inv_tableId', $id)->orderBy('inv_date_begin', 'desc')->first();
        $invoceId = $facturaReciente->inv_id;

        // $products = Product::whereHas('invoces', function ($query) use ($invoceId, $id) {
        //     $query->where('inv_id', $invoceId)
        //             ->whereHas('table', function ($query) use ($id) {
        //                 $query->where('tab_id', $id);
        //             });
        // })->get();

        // $products = DB::table('invoce_details')
        //     ->join('products', 'invoce_details.invDet_proId', '=', 'products.pro_id')
        //     ->join('invoces', 'invoce_details.invDet_invoceId', '=', 'invoces.inv_id')
        //     ->join('tables', 'invoces.inv_tableId', '=', 'tables.tab_id')
        //     ->where('tables.tab_id', $id)
        //     ->where('invoces.inv_id', $invoceId)
        //     ->select('products.pro_id', 'products.pro_name', 'products.pro_price', 'products.pro_categoryID')
        //     ->get();

        $products = Product::select('products.pro_id', 'products.pro_name', 'products.pro_price', DB::raw('COUNT(*) as total'))
                ->join('invoce_details', 'products.pro_id', '=', 'invoce_details.invDet_proId')
                ->join('invoces', 'invoce_details.invDet_invoceId', '=', 'invoces.inv_id')
                ->join('tables', 'invoces.inv_tableId', '=', 'tables.tab_id')
                ->where('tables.tab_id', $id)
                ->where('invoces.inv_id', $invoceId)
                ->groupBy('products.pro_id', 'products.pro_name', 'products.pro_price')
                ->get();

    
    
        //  Devolver los productos en formato JSON
        // return response()->json([
        //     'table_id' => $nameMesa,
        //     'products' => $products
        // ]);

        // return response()->json($table);
        

        return Inertia::render('Mesas/show', [
            'table' => $table,
            'products' => $products
        ]);

    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Table $table)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // if (!$table) {
        //     return response()->json(['mensaje' => 'mesa no encontrada'], 404);
        // };
        $table = Table::find($id);
        // $invo = Invoce::find($table->tab_id);
        $invoce_finish = Invoce::where('inv_tableId', $id)->orderBy('inv_date_begin', 'desc')->first();
        $invoce_finish->inv_finish = 1;
        $invoce_finish->inv_date_finish = Carbon::now('Europe/Madrid');
        $invoce_finish->save();
        $table->tab_state = 0;
        $table->save();
    
        // Puedes opcionalmente retornar una respuesta o redireccionar a otra página
        // return response()->json(['mensaje' => 'Registro actualizado con éxito']);
        return redirect()->route('mesas.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Table $table)
    {
        //
    }

    /**
     * Usar metodo para redirigir a una mesa en especifico
     */
    public function redirectToTable(Request $request)  {
    
    
        $mesa = $request->input('mesa');
        return redirect()->route('categories.index', ['mesa' => $mesa]);
        
    }

    public function uploadCSV(Request $request) {
        try {
            if (!$request->hasFile('file')) {
                return response()->json(['error' => 'No se recibió ningún archivo.'], 400);
            }
            $file = $request->file('file');
            $path = Storage::putFile('uploads', $file);
            $fullPath = Storage::path($path);
    
            if (!Storage::exists($path)) {
                return response()->json(['error' => 'El archivo no se guardó correctamente.'], 500);
            }
    
            //  Leer el contenido del archivo CSV
            $csvData = [];
            if (($handle = fopen($fullPath, 'r')) !== false) {
                $headers = fgetcsv($handle, 1000, ","); //  Leer la primera fila como encabezados
                
                //  Verificar si la columna
                $requiredColumns = ['tab_id', 'tab_name'];
                $missingColumns = array_diff($requiredColumns, $headers);

                if (!empty($missingColumns)) {
                    fclose($handle);
                    Storage::delete($path); // Eliminar el archivo si no es válido

                    return response()->json([
                        'error' => 'El archivo CSV no tiene las siguientes columnas requeridas: ' . implode(", ", $missingColumns)
                    ], 400);
                }
    
                //  Obtener la posición de `cat_name` en el CSV
                $catIdIndex = array_search('tab_id', $headers);
                $catNameIndex = array_search('tab_name', $headers);

    
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    if (isset($data[$catIdIndex]) && isset($data[$catNameIndex]) ) {
                        $csvData[] = [
                            'tab_id' => $data[$catIdIndex],
                            'tab_name' => $data[$catNameIndex],
                        ];
                    }
                }
                fclose($handle);
            }
    
            // Insertar los datos en la base de datos
            foreach ($csvData as $row) {

                Table::updateOrCreate(
                    ['tab_id' => $row['tab_id']], 
                    [
                        'tab_name' => $row['tab_name'],
                    ]
                );

                // $category = Category::firstOrNew(['cat_id' => $row['cat_id']]);
                // $category->cat_name = trim($row['cat_name'], '"');
                // $category->cat_isActive = (int) $row['cat_isActive'];
                // $category->save();
            }
    
            
            // Eliminar el archivo después de procesarlo
            Storage::delete($path);
    
            return response()->json([
                'mensaje' => 'Mesas importadas correctamente',
                'contenido' => $csvData,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al procesar el archivo:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }
    
    
    public function exportCSV(){
        $mesas = Table::all();

        $csvFileName = 'mesas.csv';
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName"
        ];

        $callback = function () use ($mesas) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['tab_id', 'tab_name']);

            foreach ($mesas as $mesa) {
                fputcsv($file, [$mesa->tab_id, $mesa->tab_name]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

}
