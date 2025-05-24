import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import 'flowbite';
import imgRestaurante from '../../assets/restaurante.jpg';
import { Link ,usePage} from '@inertiajs/react';
import NavLink from '@/Components/NavLink';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';
import Navbar from '@/Components/Navbar';

export default function Dashboard() {
    const { auth } = usePage().props; // Accede a las props globales
    const canViewFacturas = auth.canViewFacturas; // Extrae el valor
    const canCreateAccount = auth.canCreateAccount;
    const canUser = auth.canUser;
    const canImportar = auth.canImportar;
    const canComanda = auth.canComanda;
    const canPerfil = auth.canPerfil;
    // console.log(canComanda);
    
    return (
        <>
        <Navbar />
        <div
            className="h-screen w-screen bg-cover bg-center flex flex-col justify-center items-center"
            style={{ backgroundImage: `url(${imgRestaurante})` }}
        >
            <div className="text-white text-center mb-10">
                <h1 className="text-4xl font-bold mb-2">Bienvenido</h1>
                <h1 className="text-2xl">Sistema Restaurante</h1>
            </div>

            <div className="flex flex-col items-center gap-4 sm:hidden">
            {canPerfil && (
                <Link
                        href={route('profile.edit')}
                        className="w-48 bg-green-500 text-center text-black text-lg font-medium py-3 rounded-md shadow-md hover:bg-green-600 transition"
                    >
                        PERFIL
                </Link>
                )
            }
                {canComanda && (
                    <Link
                            href={route('mesas.index')}
                            className="w-48 bg-green-500 text-center text-black text-lg font-medium py-3 rounded-md shadow-md hover:bg-green-600 transition"
                        >
                            COMANDAS - MESAS
                    </Link>
                    )
                }
                {canViewFacturas && (
                     <Link
                     href={route('invoces.index')}
                     className="w-48 bg-green-500 text-center text-black text-lg font-medium py-3 rounded-md shadow-md hover:bg-green-600 transition"
                 >
                    FACTURAS
                 </Link>
                 )
                }
                
                {canCreateAccount && (
                <Link
                    href={route('register.create')}
                    className="w-48 bg-green-500 text-center text-black text-lg font-medium py-3 rounded-md shadow-md hover:bg-green-600 transition"
                >
                    CREAR CUENTA
                </Link>       
                )}
                {
                canUser && (
                    <Link
                    href={route('user.index')}
                    className="w-48 bg-green-500 text-center text-black text-lg font-medium py-3 rounded-md shadow-md hover:bg-green-600 transition"
                    >
                        EMPLEADOS
                    </Link>
                )
                }
                {
                canImportar && (
                    <Link
                        href={route('data')}
                        className="w-48 bg-green-500 text-center text-black text-lg font-medium py-3 rounded-md shadow-md hover:bg-green-600 transition"
                    >
                        IMPORTACIONES
                    </Link>
                )
                }

               
               

            </div>
        </div>
        
        

        {/* <div
            className="h-screen w-screen bg-cover bg-center flex flex-col "
            style={{
                height: 'calc(100vh - 36px)',
                backgroundImage: `url(${imgRestaurante})`,
            }}
        >
            <div className="text-white text-center mb-10">
                <h1 className="text-4xl font-bold mb-2">Bienvenido</h1>
                <h1 className="text-2xl">Sistema Restaurante</h1>
            </div>
        </div> */}
        </> 
    );
}
