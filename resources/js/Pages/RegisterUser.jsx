import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import MobileHeader from '@/Components/MobileHeader';
import Header from '@/Components/Header';


export default function RegisterUser() {


    const { data, setData, post, processing, errors, reset } = useForm({
        name: '',
        id_Empleado: generateRandomNumbers(5),
        password: '',
        password_confirmation: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('register.store'), {
            onFinish: () => reset('password', 'password_confirmation'),
        });
    };

    function generateRandomNumbers(count) {
        let randomNumbers = '';
    
        for (let i = 0; i < count; i++) {
            const randomNumber = Math.floor(Math.random() * 10); // Genera un dígito entre 0 y 9
            randomNumbers += randomNumber.toString(); // Concatena el dígito a la cadena
        }
    
        return randomNumbers;
    }
    return (
       
        <>
        <Navbar />
        <MobileHeader title="Crear Cuenta" backRoute={route('dashboard')} />
        <Header title="Crear Cuenta" />

        <div className='flex flex-col justify-center items-center'>

            <div className='flex flex-col items-center '>
                <form onSubmit={submit}>
                    
                    <div className="mt-4">
                        <InputLabel htmlFor="id_Empleado" className='text-base sm:text-lg md:text-xl lg:text-2xl' value="Numero Empleado" />

                        <TextInput
                            id="id_Empleado"
                            type="text"
                            name="id_Empleado"
                            value={data.id_Empleado}
                            // className="mt-1 block w-full"
                            className="lg:w-64"
                            autoComplete="username"
                            onChange={(e) => setData('id_Empleado', e.target.value)}
                            required
                            disabled 
                        />

                        <InputError message={errors.id_Empleado} className="mt-2" />
                    </div>
                    
                    <div className='mt-4'>
                        <InputLabel htmlFor="name" className='text-base sm:text-lg md:text-xl lg:text-2xl' value="Nombre" />

                        <TextInput
                            id="name"
                            name="name"
                            value={data.name}
                            // className="mt-1 block w-full"
                            className="lg:w-64"
                            autoComplete="name"
                            isFocused={true}
                            onChange={(e) => setData('name', e.target.value)}
                            required
                        />

                        <InputError message={errors.name} className="mt-2" />
                    </div>

                    <div className="mt-4">
                        <InputLabel htmlFor="password" className='text-base sm:text-lg md:text-xl lg:text-2xl'  value="Contraseña" />

                        <TextInput
                            id="password"
                            type="password"
                            name="password"
                            value={data.password}
                            className="lg:w-64"
                            autoComplete="new-password"
                            onChange={(e) => setData('password', e.target.value)}
                            required
                        />

                        <InputError message={errors.password} className="mt-2" />
                    </div>

                    <div className="mt-4">
                        <InputLabel
                            htmlFor="password_confirmation"
                            value="Confirmar Contraseña"
                            className='text-base sm:text-lg md:text-xl lg:text-2xl'
                        />

                        <TextInput
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            value={data.password_confirmation}
                            // className="mt-1 block w-full"
                            className="lg:w-64"
                            autoComplete="new-password"
                            onChange={(e) =>
                                setData('password_confirmation', e.target.value)
                            }
                            required
                        />

                        <InputError
                            message={errors.password_confirmation}
                            className="mt-2"
                        />
                    </div>

                    <div className="mt-4 flex items-center justify-end">
                        
                        <PrimaryButton className="ms-4" disabled={processing}>
                            Registrar
                        </PrimaryButton>
                    </div>
                </form>
            </div>

        </div>
        </>

    );
}
