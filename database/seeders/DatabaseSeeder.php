<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

          // guardamos categorias en la BD
        $allCategorias = ['Entrantes', 'Platos principales', 'Ensaladas', 'Postres', 'Bebidas calientes', 'Bebidas frías', 'Desayunos', 'Sopas y cremas', 'Pizzas', 'Hamburguesas'];
        $categoria = new Category();

        foreach ($allCategorias as $nameCategoria) {
            $categoria = new Category();
            $categoria->cat_name = $nameCategoria;
            $categoria->save();
        }

        for ($i = 0; $i < 100; $i++) {
            $idCategoria = rand(1, 10);
            $producto = new Product();

            switch ($idCategoria) {
                case 1:
                    $allEntrantes = ["Croquetas", "Bruschetta", "Empanadas", "Alitas de pollo", "Calamares a la romana", "Champiñones rellenos", "Dip de espinacas", "Tortilla española", "Queso fundido", "Patatas bravas"];
                    $allprecios = [1, 1.50, 2.50, 3, 4.50];
                    $nombre = $allEntrantes[array_rand($allEntrantes)];
                    $repetidoName = Product::where('pro_name', $nombre)->exists();
                    if (!$repetidoName) {
                        $precio = $allprecios[array_rand($allprecios)];
                        addProducto($producto, $nombre, $precio, $idCategoria);
                    }
                    break;

                case 2:
                    $allPlatosPrincipales = ["Hamburguesa", "Pizza", "Filete de ternera", "Salmón a la parrilla", "Lasagna", "Pollo al horno", "Cordero asado", "Paella", "Pasta carbonara", "Tacos de carne"];
                    $allprecios = [8, 12.99, 15.99, 18.99, 20.99];
                    $nombre = $allPlatosPrincipales[array_rand($allPlatosPrincipales)];
                    $repetidoName = Product::where('pro_name', $nombre)->exists();
                    if (!$repetidoName) {
                        $precio = $allprecios[array_rand($allprecios)];
                        addProducto($producto, $nombre, $precio, $idCategoria);
                    }
                    break;
                case 3:
                    $allEnsaladas = ["Ensalada César", "Ensalada Caprese", "Ensalada Griega", "Ensalada Waldorf", "Ensalada de Pollo", "Ensalada de Frutas", "Ensalada de Quinoa", "Ensalada de Espinacas", "Ensalada de Tomate y Mozzarella", "Ensalada de Remolacha"];
                    $allprecios = [1.50,2.50,3.50,4,5,6.50];
                    $nombre = $allEnsaladas[array_rand($allEnsaladas)];
                    $repetidoName = Product::where('pro_name', $nombre)->exists();
                    if (!$repetidoName) {
                        $precio = $allprecios[array_rand($allprecios)];
                        addProducto($producto, $nombre, $precio, $idCategoria);
                    }
                    break;
                case 4:
                    $allPostres = ["Tarta de chocolate", "Helado de vainilla", "Flan de caramelo", "Cheesecake de fresa", "Crema catalana", "Mousse de chocolate", "Brownie con helado", "Pastel de manzana", "Galletas caseras", "Arroz con leche"];
                    $allprecios = [1.50,2.50,3.50,4,5];
                    $nombre = $allPostres[array_rand($allPostres)];
                    $repetidoName = Product::where('pro_name', $nombre)->exists();
                    if (!$repetidoName) {
                        $precio = $allprecios[array_rand($allprecios)];
                        addProducto($producto, $nombre, $precio, $idCategoria);
                    }
                    break;
                case 5:
                    $allBebidasCalientes = ["Café americano", "Capuchino", "Chocolate caliente", "Té de hierbas", "Café espresso", "Café con leche", "Mocha", "Café con crema", "Café irlandés", "Café cortado"];
                    $allprecios =[1.50,2.50,3.50,4,5];
                    $nombre = $allBebidasCalientes[array_rand($allBebidasCalientes)];
                    $repetidoName = Product::where('pro_name', $nombre)->exists();
                    if (!$repetidoName) {
                        $precio = $allprecios[array_rand($allprecios)];
                        addProducto($producto, $nombre, $precio, $idCategoria);
                    }
                    break;
                case 6:
                    $allBebidasFrias = ["Refresco de cola", "Agua mineral", "Limonada", "Zumo de naranja", "Té frío", "Batido de fresa", "Cerveza", "Cóctel sin alcohol", "Granizado de limón", "Mojito"];
                    $allprecios = [1.50,2.50,3.50,4,5] ;
                    $nombre = $allBebidasFrias[array_rand($allBebidasFrias)];
                    $repetidoName = Product::where('pro_name', $nombre)->exists();
                    if (!$repetidoName) {
                        $precio = $allprecios[array_rand($allprecios)];
                        addProducto($producto, $nombre, $precio, $idCategoria);
                    }
                case 7:
                    $allDesayunos = ["Tostadas con mermelada", "Croissant de chocolate", "Huevos revueltos", "Bol de cereales", "Yogur con frutas", "Pan con tomate", "Muffin de arándanos", "Zumo de manzana", "Té inglés", "Café con leche"];
                    $allprecios = [3.50,4,5,5.50,7,7.50];
                    $nombre = $allDesayunos[array_rand($allDesayunos)];
                    $repetidoName = Product::where('pro_name', $nombre)->exists();
                    if (!$repetidoName) {
                        $precio = $allprecios[array_rand($allprecios)];
                        addProducto($producto, $nombre, $precio, $idCategoria);
                    }
                    break;

                case 8:
                    $allSopasCremas = ["Sopa de tomate", "Crema de calabaza", "Sopa de cebolla", "Crema de champiñones", "Sopa de lentejas", "Crema de espárragos", "Sopa de pollo", "Crema de zanahoria", "Sopa minestrone", "Crema de brócoli"];
                    $allprecios = [3.50,4,5,5.50] ;
                    $nombre = $allSopasCremas[array_rand($allSopasCremas)];
                    $repetidoName = Product::where('pro_name', $nombre)->exists();
                    if (!$repetidoName) {
                        $precio = $allprecios[array_rand($allprecios)];
                        addProducto($producto, $nombre, $precio, $idCategoria);
                    }
                    break;

                case 9:
                    $allPizzas = ["Pizza margarita", "Pizza pepperoni", "Pizza hawaiana", "Pizza cuatro quesos", "Pizza vegetariana", "Pizza marinera", "Pizza barbacoa", "Pizza carbonara", "Pizza prosciutto", "Pizza caprese"];
                    $allprecios = [4,5,5.50,6,6.50,7];
                    $nombre = $allPizzas[array_rand($allPizzas)];
                    $repetidoName = Product::where('pro_name', $nombre)->exists();
                    if (!$repetidoName) {
                        $precio = $allprecios[array_rand($allprecios)];
                        addProducto($producto, $nombre, $precio, $idCategoria);
                    }
                    break;

                case 10:
                    $allHamburguesas = ["Hamburguesa clásica", "Hamburguesa con queso", "Hamburguesa vegetariana", "Hamburguesa doble", "Hamburguesa de pollo", "Hamburguesa de ternera", "Hamburguesa con bacon", "Hamburguesa BBQ", "Hamburguesa picante", "Hamburguesa gourmet"];
                    $allprecios = [4,5,5.50,6,6.50,7];
                    $nombre = $allHamburguesas[array_rand($allHamburguesas)];
                    $repetidoName = Product::where('pro_name', $nombre)->exists();
                    if (!$repetidoName) {
                        $precio = $allprecios[array_rand($allprecios)];
                        addProducto($producto, $nombre, $precio, $idCategoria);
                    }
                    break;

                default:
                    # code...
                    break;
            }
            
             }


        $mesas = [
            ["tab_name" => "mesa1", "tab_state" => 0],
            ["tab_name" => "mesa2", "tab_state" => 0],
            ["tab_name" => "mesa3", "tab_state" => 0],
            ["tab_name" => "mesa4", "tab_state" => 0],
            ["tab_name" => "mesa5", "tab_state" => 0],
            ["tab_name" => "mesa6", "tab_state" => 0],

        ];

        foreach ($mesas as $mesa) {
            DB::table("tables")->insert($mesa);
        }
        
    }


}

function addProducto(Product $producto, $nombre, $precios, $id)
        {
            $producto->pro_name = $nombre;
            $producto->pro_price = $precios;
            $producto->pro_categoryID = $id;
            $producto->save();
        }
