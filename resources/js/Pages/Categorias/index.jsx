import React from 'react';
import { Link ,usePage } from '@inertiajs/react';

export default function Categories({ categories ,mesa}) {


    return (
        <>
            <div className="flex flex-col items-center mt-10 h-[500px] overflow-y-auto">
                <h1 className="font-semibold text-base sm:text-lg md:text-xl lg:text-2xl mb-5">Categorías</h1>
                <div className="flex flex-col items-center justify-center ">
                    {categories.map((category) => (
                        <Link
                            key={category.cat_id}
                            href={route('categories.show',{category: category.cat_id, mesa: mesa})}
                            className="bg-green-500 w-44 h-10 mt-5 flex items-center justify-center rounded-md shadow-md hover:bg-green-600 transition"
                        >
                            {category.cat_name}
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="90" fill="currentColor" className="pl-1 bi bi-arrow-right" viewBox="0 0 16 16">
                                <path fillRule="evenodd" d="M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8"/>
                            </svg>
                        </Link>
                    ))}
                </div>
            </div>

            <div className='flex flex-col items-center justify-center mt-10'>
                <Link
                    href={route('mesas.show', { mesa: mesa })}
                    className="w-40 h-14 bg-green-400 text-black text-lg font-medium py-3 rounded-md shadow-md hover:bg-green-600 transition text-center"
                >
                    Ver Pedido
                </Link>
            </div>
        </>
    );
}

