import React, { useState, useEffect } from 'react';
import { Link, router  } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import MobileHeader from '@/Components/MobileHeader';
import Header from '@/Components/Header';

const todayDate = () => {
    const today = new Date();
    const year = today.getFullYear();
    const month = String(today.getMonth() + 1).padStart(2, '0');
    const day = String(today.getDate()).padStart(2, '0');
  
    return `${year}-${month}-${day}`; // Formato 'YYYY-MM-DD'
};

const index = ({ invoices,filters = {},total,cantidadFacturas }) => {
    
    
    const [fechaInicio, setFechaInicio] = useState(filters.inv_date_begin || todayDate());
    const [fechaFin, setFechaFin] = useState(filters.inv_date_finish || todayDate());
  
    
    const handleBuscar = (e) => {
        e.preventDefault();
      
        router.get('/invoces/search', {
          inv_date_begin: fechaInicio,
          inv_date_finish: fechaFin,
        });
    };

    return (
        <>
            
            <Navbar />
            <MobileHeader title="FACTURAS" backRoute={route('dashboard')} />

            <form
                    onSubmit={handleBuscar}
                    className="flex flex-col md:flex-row justify-center items-center gap-4 my-6"
                >
                    <input
                        type="date"
                        name="fecha_inicio"
                        value={fechaInicio}
                        onChange={(e) => setFechaInicio(e.target.value)}
                        required
                        className="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
                    />

                    <input
                        type="date"
                        name="fecha_fin"
                        value={fechaFin}
                        onChange={(e) => setFechaFin(e.target.value)}
                        required
                        className="px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 w-64"
                    />

                    <button
                        type="submit"
                        className="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 transition w-100 md:w-auto"
                    >
                        <svg
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                            className="h-5 w-5">
                            <path
                                fillRule="evenodd"
                                d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                                clipRule="evenodd" />
                        </svg>
                    </button>
            </form>
             
            {!total == 0 && (
                <div className="mt-4 text-center">
                    <div>Total: {total} €</div>
                    <div>Cantidad de facturas: {cantidadFacturas}</div>
                </div>
            )}
             

            <Header title="Facturas" />
            <div className="flex flex-wrap gap-4 justify-center">
            {
                invoices.data.map((invo) => {

                    // Agrupamos los productos por `pro_id`
                    const groupedProducts = invo.products.reduce((acc, product) => {
                        if (acc[product.pro_id]) {
                            acc[product.pro_id].count += 1; // Aumenta la cantidad
                            acc[product.pro_id].totalPrice += product.pro_price; // Suma el precio
                        } else {
                            acc[product.pro_id] = {
                                ...product,
                                count: 1, // Inicia la cantidad en 1
                                totalPrice: product.pro_price, // Inicia el precio total
                            };
                        }
                        return acc;
                    }, {});

                    // Convertimos el objeto agrupado a un array
                    const groupedArray = Object.values(groupedProducts);
                    const totalFactura = groupedArray.reduce((sum, product) => sum + product.totalPrice, 0);
                    const fechaFormateada = new Date(invo.inv_date_finish).toLocaleDateString('es-ES', {
                        day: '2-digit',
                        month: '2-digit',
                        year: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                        });

                    const [d, m, y] = fechaFormateada.split('/');
                    const fechaFinal = `${d}-${m}-${y}`;
                    return (
                        <div key={invo.id} className="max-w-md bg-white shadow-lg rounded-lg p-4">
                            <div className="text-center border-b pb-2">
                                <p className="text-sm text-gray-500">{fechaFinal}</p>

                            </div>
                            <div className="py-4 border-b">
                                {
                                groupedArray.map((product, index) => (
                                    <div key={index} className="flex justify-between text-sm">
                                        <span>{product.count} {product.pro_name}</span>
                                        <span>{product.totalPrice.toFixed(2)}€</span>
                                    </div>
                                ))
                                }
                            </div>
                            <div className="py-4">
                                <div className="flex justify-between text-sm font-semibold">
                                    <span>Total</span>
                                    <span>{totalFactura.toFixed(2)}€</span>
                                </div>
                            </div>

                            
                        </div>

                    
                    );
                })
            }

            </div>

            {/* Paginación */}
            <div className="hidden sm:flex justify-center mt-6 ">
            {invoices.links.map((link, index) => (
                <Link
                key={index}
                href={link.url ? `${link.url}&inv_date_begin=${fechaInicio}&inv_date_finish=${fechaFin}` : '#'}
                dangerouslySetInnerHTML={{ __html: link.label }}
                className={`px-4 py-2 border rounded-md mx-1 ${link.active ? 'bg-gray-500 text-white' : ''}`}
                />
            ))}
            </div>
            {/* Paginación reducida en pantallas pequeñas */}
            <div className="flex sm:hidden justify-center w-full space-x-2 ">
            {invoices.prev_page_url && (
                <Link
                href={`${invoices.prev_page_url}&inv_date_begin=${fechaInicio}&inv_date_finish=${fechaFin}`}
                className="px-4 py-2 border rounded-md bg-gray-300 hover:bg-gray-400 transition"
                >
                ← Anterior
                </Link>
            )}

            {invoices.next_page_url && (
                <Link
                href={`${invoices.next_page_url}&inv_date_begin=${fechaInicio}&inv_date_finish=${fechaFin}`}
                className="px-4 py-2 border rounded-md bg-gray-300 hover:bg-gray-400 transition"
                >
                Siguiente →
                </Link>
            )}
            </div>

        </>

    )
}

export default index
