import React from 'react'
import { usePage} from '@inertiajs/react';

const Navbar = () => {

const { auth } = usePage().props; // Accede a las props globales
const canViewFacturas = auth.canViewFacturas; // Extrae el valor
const canCreateAccount = auth.canCreateAccount;
const canUser = auth.canUser;
const canImportar = auth.canImportar;
const canComanda = auth.canComanda;
const canPerfil = auth.canPerfil;

return (
    
    <nav className="sm:bg-gray-800 text-white flex justify-center">

        <div className="mx-auto">
            <div className="flex flex-1 items-center justify-center">
                <div className="hidden sm:block">
                    <div className="flex space-x-4">
                        {canPerfil && (
                            <a href={route('profile.edit')} className="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">PERFIL</a>
                        )
                        }
                        {canComanda && (
                            <a href={route('mesas.index')} className="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">COMANDAS - MESAS</a>
                           )
                        }
                        {canViewFacturas && (
                            <a href={route('invoces.index')} className="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">FACTURAS</a>
                            )
                        }
                        {canCreateAccount && (
                            <a href={route('register.create')} className="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">CREAR CUENTA</a>
                            )    
                        }
                        {canUser && (
                            <a href={route('user.index')} className="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">EMPLEADOS</a>
                        )
                        }
                        {canImportar && ( 
                            <a href={route('data')} className="rounded-md px-3 py-2 text-sm font-medium text-gray-300 hover:bg-gray-700 hover:text-white">
                            IMPORTACIONES</a>
                        )}

                    </div>
                </div>
            </div>
            
        </div>
    </nav>
  )
}

export default Navbar
