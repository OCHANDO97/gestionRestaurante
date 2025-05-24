<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Table;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;



class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        
        $mesa = $request->query('mesa');
        // $categories = Category::all();
        $categories = Category::where('cat_isActive', 1)->get();
        // return response()->json($categories);

        return Inertia::render('Categorias/index', [
            'categories' => $categories,
            'mesa' => $mesa
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
    public function show(Category $category,Request $request)
    {
        $mesaId = $request->query('mesa'); 

        if ($category->cat_isActive == 0) {
            return redirect()->to('/categories?mesa=' . $mesaId)
            ->with('error', 'Esta categoría está inactiva.');
        }

        $category->load('products'); 
        // return response()->json($category);
        return Inertia::render('Categorias/show', [
            'category' => $category,
            'mesa' => $mesaId
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
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
    
                //  Verificar si la columna
                $requiredColumns = ['cat_id', 'cat_name','cat_isActive'];
                $missingColumns = array_diff($requiredColumns, $headers);

                if (!empty($missingColumns)) {
                    fclose($handle);
                    Storage::delete($path); // Eliminar el archivo si no es válido

                    return response()->json([
                        'error' => 'El archivo CSV no tiene las siguientes columnas requeridas: ' . implode(", ", $missingColumns)
                    ], 400);
                }
    
                //  Obtener la posición de `cat_name` en el CSV
                $catIdIndex = array_search('cat_id', $headers);
                $catNameIndex = array_search('cat_name', $headers);
                $catActiveIndex = array_search('cat_isActive', $headers);

    
                while (($data = fgetcsv($handle, 1000, ",")) !== false) {
                    if (isset($data[$catIdIndex]) && isset($data[$catNameIndex]) && isset($data[$catActiveIndex]) ) {
                        $csvData[] = [
                            'cat_id' => $data[$catIdIndex],
                            'cat_name' => $data[$catNameIndex],
                            'cat_isActive' => $data[$catActiveIndex]
                        ];
                    }
                }
                fclose($handle);
            }
    
            // Insertar los datos en la base de datos
            foreach ($csvData as $row) {

                \Log::info('Datos insertados:', [
                    'cat_id' => $row['cat_id'],
                    'cat_name' => trim($row['cat_name'], '"'),
                    'cat_isActive' => (int) $row['cat_isActive']
                ]);

             

                // Category::updateOrCreate(
                //     ['cat_id' => $row['cat_id']], 
                //     [
                //         'cat_name' => $row['cat_name'],
                //         // 'cat_isActive' => (int) $row['cat_isActive']
                //         'cat_isActive' => (int) $row['cat_isActive']

                //     ]
                // );

                $category = Category::firstOrNew(['cat_id' => $row['cat_id']]);
                $category->cat_name = trim($row['cat_name'], '"');
                $category->cat_isActive = (int) $row['cat_isActive'];
                $category->save();
            }
    
            
            // Eliminar el archivo después de procesarlo
            Storage::delete($path);
    
            return response()->json([
                'mensaje' => 'Categorías importadas correctamente',
                'contenido' => $csvData,
            ]);
        } catch (\Exception $e) {
            \Log::error('Error al procesar el archivo:', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Error en el servidor: ' . $e->getMessage()], 500);
        }
    }
    
    
    public function exportCSV(){
        $categorias = Category::all();

        $csvFileName = 'categorias.csv';
        $headers = [
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$csvFileName"
        ];

        $callback = function () use ($categorias) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['cat_id', 'cat_name','cat_isActive']); // Encabezados

            foreach ($categorias as $categoria) {
                fputcsv($file, [$categoria->cat_id, $categoria->cat_name, $categoria->cat_isActive]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

}
