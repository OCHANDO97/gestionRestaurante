import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import DeleteUserForm from './Partials/DeleteUserForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';
import { Link, useForm, usePage } from '@inertiajs/react';
import Navbar from '@/Components/Navbar';
import MobileHeader from "@/Components/MobileHeader";
import Header from "@/Components/Header";



export default function Edit() {

    
    return (
        <>
            <Navbar />
            <MobileHeader title="Perfil" backRoute={route('dashboard')} />
            <Header title="Perfil" />

            <div className="flex flex-col justify-center items-center">
                <UpdatePasswordForm className="mt-10 text-base sm:text-lg md:text-xl lg:text-2xl" />
            </div>
            </>
    );
}
