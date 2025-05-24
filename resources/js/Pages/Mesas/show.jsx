import React, { useState,useEffect } from 'react';
import { Link } from '@inertiajs/react';

const show = ({table,products}) => {


    const [totalPrecio, setTotalPrecio,] = useState(0);
    const [productCounts, setProductCounts] = useState(
            products.map((pro) => 
                pro.total
            ) 
        );
    
    

    const handleIncrement = async (index, mesa, producto) => {
        const updatedCounts = [...productCounts];
        updatedCounts[index] += 1;
        setProductCounts(updatedCounts);
    
        try {
            // Realiza la solicitud POST al backend
            const response = await axios.post(`/factura/mesa/${mesa}/addProducto/${producto}`, {
                // Si necesitas pasar datos adicionales, agrégalos aquí
            });
    
            if (response.status === 201) {
                console.log('Producto agregado a la factura');
            } else {
                console.error('Error al agregar el producto a la factura');
            }
        } catch (error) {
            console.error('Error en la solicitud:', error);
        }
    };

    const handleDecrement = async (index, mesa, producto) => {
        const updatedCounts = [...productCounts];
        if (updatedCounts[index] > 0) {
            updatedCounts[index] -= 1;
            setProductCounts(updatedCounts);
        }

        try {
            // Realiza la solicitud POST al backend
            const response = await axios.post(`/factura/mesa/${mesa}/removeProducto/${producto}`, {
                // Si necesitas pasar datos adicionales, agrégalos aquí
            });
    
            if (response.status === 201) {
                console.log('Producto agregado a la factura');
            } else {
                console.error('Error al agregar el producto a la factura');
            }
        } catch (error) {
            console.error('Error en la solicitud:', error);
        }
    };


    useEffect(() => {
        // Calcula el precio total basado en las cantidades actuales
        const total = products.reduce((sum, product, index) => {
            return sum + (product.pro_price * productCounts[index]);
        }, 0);
    
        // Formatea el precio con dos decimales
        let precio = total.toFixed(2);
        setTotalPrecio(precio);
       
        
        
    }, [products, productCounts]);

    
  return (
        <>
            <h1 className='my-10 text-center font-semibold text-base sm:text-lg md:text-xl lg:text-2xl'>{table.tab_name}</h1>

            <div className="bg-zinc-400 h-96 w-full overflow-y-auto">
                {products.map((pro, index) => (
                    <div
                        key={pro.pro_id}
                        className="flex items-center justify-center gap-4 h-20 w-full p-4 border-b "
                    >
                        {/* Botón Decrementar */}
                        <button
                            onClick={() => handleDecrement(index, table.tab_id, pro.pro_id)}
                            className="w-10 h-10 flex items-center justify-center border-2 border-black rounded-full hover:bg-black hover:text-white transition flex-shrink-0"
                        >
                            <span className="text-xl font-bold">-</span>
                        </button>

                        {/* Contador */}
                        <span className="text-lg font-medium flex-shrink-0 w-8 text-center">
                            {productCounts[index]}
                        </span>

                        {/* Botón Incrementar */}
                        <button
                            onClick={() => handleIncrement(index, table.tab_id, pro.pro_id)}
                            className="w-10 h-10 flex items-center justify-center border-2 border-black rounded-full hover:bg-black hover:text-white transition flex-shrink-0"
                        >
                            <span className="text-xl font-bold">+</span>
                        </button>

                        {/* Nombre del Producto */}
                        <h1 className="bg-zinc-500 px-4 py-2 rounded-md shadow-sm text-black flex-shrink w-40 text-center">
                            {pro.pro_name}
                        </h1>
                    </div>
                ))}

               
            </div>

            <div className='flex justify-around bg-zinc-400 h-10 w-full' >
                <h1 className='font-semibold text-base sm:text-lg md:text-xl lg:text-2xl'>Cantidad total: {productCounts.reduce((sum, count) => sum + count, 0)}</h1>
                <h1 className='font-semibold text-base sm:text-lg md:text-xl lg:text-2xl'>total: {totalPrecio}€</h1>
            </div>

            <div className="flex flex-wrap gap-4 justify-center items-center mt-5">

                <form
                    method="POST"
                    action={route('mesas.update',{ mesa: table.tab_id })}
                    className="inline"
                >
                    {/* CSRF Token necesario para Laravel */}
                    <input type="hidden" name="_token" value={window.Laravel.csrfToken} />
                    {/* Spoofing del método PUT */}
                    <input type="hidden" name="_method" value="PUT" />

                    <button
                        type="submit"
                        className="w-40 h-14 bg-green-500 text-black text-lg font-medium py-3 rounded-md shadow-md hover:bg-green-600 transition text-center"
                    >
                        FINALIZAR MESA
                    </button>
                </form>

                <Link
                    href={route('mesas.index')}
                    className="w-40 h-14 bg-green-500 text-black text-lg font-medium py-3 rounded-md shadow-md hover:bg-green-600 transition flex items-center justify-center "
                    // className="w-40 h-14 bg-green-500 text-black text-lg font-medium py-3 rounded-md shadow-md hover:bg-green-600 transition flex items-center justify-normal"

                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="90" fill="currentColor" className="pr-3 bi bi-arrow-left" viewBox="0 0 16 16">
                        <path fillRule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8"/>
                    </svg>
                    <span>MESAS</span>
                </Link>

                <Link
                    href={route('categories.index',{ mesa: table.tab_id })}
                    className="w-40 h-14 bg-green-500 text-black text-lg font-medium py-3 rounded-md shadow-md hover:bg-green-600 transition flex items-center justify-center"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="90" fill="currentColor" className="pl-2 pr-3 bi bi-cart" viewBox="0 0 16 16">
                        <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5M3.102 4l1.313 7h8.17l1.313-7zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4m7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4m-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2m7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2"/>
                    </svg>
                    AÑADIR PRODUCTO
                </Link>
                

            </div>

      </>
  )
}

export default show
