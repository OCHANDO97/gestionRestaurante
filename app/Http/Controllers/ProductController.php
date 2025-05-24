<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\QueryException;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        return Inertia::render('Products/index', [
            'products' => $products
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
    public function show(Product $product)
    {
        // $products = Product::where('idCategoria', $id)->get();
        $product = Product::find($product->pro_id);
        // $products = $product->categorias();
        // var_dump($product);
        return Inertia::render('Products/show', [
            'product' => $product,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        //
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
    
                //  Verificar si la columnas está presente
                $requiredColumns = ['pro_id', 'pro_name', 'pro_price', 'pro_categoryID'];
                $missingColumns = array_diff($requiredColumns, $headers);

                if (!empty($missingColumns)) {
                    fclose($handle);
                    Storage::delete($path); // Eliminar el archivo si no es válido

                    return response()->json([
                        'error' => 'El archivo CSV no tiene las siguientes columnas requeridas: ' . implode(", ", $missingColumns)
                    ], 400);
                }
            
                $catIdIndex = array_search('pro_id', $headers);
                $catNameIndex = array_search('pro_name', $headers);
                $catPriceIndex = array_search('pro_price', $headers);
                $catCategoryIndex = array_search('pro_categoryID', $headers);
                
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    if (isset($data[$catIdIndex]) && isset($data[$catNameIndex]) && isset($data[$catPriceIndex]) && isset($data[$catCategoryIndex])) {
                        $csvData[] = [
                            'pro_id' => $data[$catIdIndex],
                            'pro_name' => $data[$catNameIndex],
                            'pro_price' => $data[$catPriceIndex],
                            'pro_categoryID' => $data[$catCategoryIndex],

                        ];
                    }
                }
                fclose($handle);
            }
            // Insertar los datos en la base de datos
            foreach ($csvData as $row) {


                try {
                    if ($row['pro_price'] <= 0) {
                        return response()->json([
                            'error' => "El precio del producto '{$row['pro_name']}' (ID: {$row['pro_id']}) es incorrecto."
                        ], 400);
                    }
                    Product::updateOrCreate(
                        ['pro_id' => $row['pro_id']], 
                        [
                            'pro_name' => $row['pro_name'],
                            'pro_price' => $row['pro_price'],
                            'pro_categoryID' => $row['pro_categoryID']
                        ]
                    );
                } catch (QueryException $e) {
                    if ($e->getCode() == 23000) { // Código de error para violación de clave foránea
                        return response()->json([
                            'error' => "No se puede asignar la categoría con ID {$row['pro_categoryID']} porque no existe en la base de datos."
                        ], 400);
                    }
                    throw $e;
                }

            }
    
            // Eliminar el archivo después de procesarlo
            Storage::delete($path);
    
            return response()->json([
                'mensaje' => 'Productos importados correctamente',
                'contenido' => $csvData,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al procesar el archivo:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }
    

    public function exportCSV()
    {
        $listaProductos = Product::all();

        $csvFileName = 'listaProductos.csv';
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName"
        ];

        $callback = function () use ($listaProductos) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['pro_id', 'pro_name','pro_price','pro_categoryID']);

            foreach ($listaProductos as $pro) {
                fputcsv($file, [$pro->pro_id, $pro->pro_name, $pro->pro_price, $pro->pro_categoryID]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

}
