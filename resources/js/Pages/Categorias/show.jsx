import React from 'react'
import { Link, usePage } from '@inertiajs/react';

export default function show({category,mesa}) {

  return (
    <div className="flex flex-col items-center my-20">
        <p className='font-semibold text-base sm:text-lg md:text-xl lg:text-2xl'>{category.cat_name}</p>

        <div className="flex flex-wrap gap-8 justify-center my-20">
            {category.products.map((product, index) => (
                <div
                    key={product.pro_id}
                    onClick={() => {
                        document.getElementById(`form-${product.pro_id}`).submit();
                    }}
                    className="h-24 w-28 bg-green-400 text-center flex flex-col pt-6"
                >
                    {/* Formulario oculto */}
                    <form
                        id={`form-${product.pro_id}`}
                        method="POST"
                        action={route('addProductoToFacturaOnMesa', {
                            mesa: mesa,
                            producto: product.pro_id,
                        })}
                    >
                        <input type="hidden" name="_token" value={window.Laravel.csrfToken} />
                    </form>

                    {/* Contenido visible */}
                    <div className="">{index + 1}</div>
                    <div className="pt-5 leading-3">{product.pro_name}</div>
                </div>
            ))}
        </div>
      
        <Link 
            href={route('categories.index',{mesa:mesa})}

            className="font-semibold text-base sm:text-lg md:text-xl lg:text-2xl bg-green-500 w-44 h-10 mt-5 flex items-center justify-center rounded-md shadow-md hover:bg-green-600 transition"> 
            Categorias
        </Link>

       
    </div>
  )
}
