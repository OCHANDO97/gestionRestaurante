import React,{ useState,useEffect } from 'react'
import Navbar from '@/Components/Navbar';
import { router,Link,useForm,usePage } from '@inertiajs/react';
import 'flowbite';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import PrimaryButton from '@/Components/PrimaryButton';
import MobileHeader from "@/Components/MobileHeader";
import Header from '@/Components/Header';

const index = ({users,allPermissions}) => {

  
  
  const handleDeleteUser = (userId) => {
    if (confirm('¿Estás seguro de que deseas eliminar este usuario?')) {
      router.delete(`/user/${userId}`, {
        onSuccess: () => alert('Usuario eliminado correctamente.'),
        onError: () => alert('Error al eliminar el usuario.'),
      });
    }
  };

  
  const [isModalOpen, setModalOpen] = useState(false);
  const [selectedUserId, setSelectedUserId] = useState(null);

  const { data, setData, put, processing, errors,reset  } = useForm({
    user_id: selectedUserId,
    password: '',
    password_confirmation: '',
  })


  function submit(e) {

      e.preventDefault()
      
      put(route('user.update', { user: selectedUserId }), { 

        preserveScroll: true,
        onSuccess: () => {
            reset();
            setModalOpen(false);
            alert('Contraseña actualizada correctamente');
        },
        onError: (errors) => {
            if (errors.password) {
                reset('password', 'password_confirmation');
            }
        },
      });

  }
  
  const openModal = (userId) => {
    setSelectedUserId(userId);
    setData('user_id', userId); // Establecer el ID del usuario en el formulario
    setModalOpen(true);
  };

  const closeModal = () => {
    setModalOpen(false);
  };

  const formatDate = (dateString) => {
    const date = new Date(dateString);
    return date.toLocaleDateString('es-ES', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    });
};

  return (
    <>
      <Navbar />
      <MobileHeader title="Lista de empleados" backRoute={route('dashboard')} />
      <Header title="Lista de empleados" />

      <table className="mt-4 table-auto border-collapse w-full">
        <thead>
          <tr className="bg-gray-800 text-white">
            <th className="px-4 py-2">Nombre</th>
            <th className="px-4 py-2">Id Empleado</th>
            <th className="px-4 py-2">Fecha de alta</th>
            <th className="px-4 py-2">Acciones</th>
            <th className="px-4 py-2">Permisos</th>
          </tr>
        </thead>
        <tbody>
          {users.map((use) => (
            <tr key={use.id} className="hover:bg-gray-100">
              <td className="border px-4 py-2">{use.name}</td>
              <td className="border px-4 py-2">{use.id_Empleado}</td>
              <td className="border px-4 py-2">{formatDate(use.created_at)}</td>

              {/* Acciones */}
              <td className="border px-4 py-2">
                <div className="flex flex-wrap gap-2">
                  <button
                    onClick={() => openModal(use.id)}
                    className="bg-blue-500 text-white px-2 py-1 rounded-md hover:bg-blue-600 transition"
                  >
                    Cambiar Contraseña
                  </button>
                  <button
                    onClick={() => handleDeleteUser(use.id)}
                    className="bg-red-500 text-white px-2 py-1 rounded-md hover:bg-red-600 transition"
                  >
                    Eliminar
                  </button>
                </div>
              </td>

              {/* Permisos */}
              <td className="border px-4 py-2">
                <div className="flex justify-center">
                  <Link
                    href={route('users.perfil', use.id)}
                    className="bg-green-500 text-white px-2 py-1 rounded-md hover:bg-green-600 transition"
                  >
                    Ver perfil
                  </Link>
                </div>
              </td>
            </tr>
          ))}
        </tbody>
      </table>

      {isModalOpen && (
          <div
              id="authentication-modal"
              className="flex overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full h-full bg-black bg-opacity-50"
          >
              <div className="relative p-4 w-full max-w-md max-h-full">
                  <div className="relative bg-white rounded-lg shadow dark:bg-gray-700">
                      <div className="flex items-center justify-between p-4 border-b rounded-t dark:border-gray-600 border-gray-200">
                          <button
                              type="button"
                              onClick={closeModal}
                              className="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                          >
                              <svg
                                  className="w-3 h-3"
                                  xmlns="http://www.w3.org/2000/svg"
                                  fill="none"
                                  viewBox="0 0 14 14"
                              >
                                  <path
                                      stroke="currentColor"
                                      strokeLinecap="round"
                                      strokeLinejoin="round"
                                      strokeWidth="2"
                                      d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"
                                  />
                              </svg>
                              <span className="sr-only">Close modal</span>
                          </button>
                      </div>
                      <div className="p-4">

                        <form onSubmit={submit} className="space-y-4">
                              <input type="hidden" name="_token" value={window.Laravel.csrfToken} />
                                <div>
                                    <InputLabel
                                        htmlFor="current_password"
                                        value="Contraseña"
                                        className='sm:text-lg md:text-xl lg:text-2xl'
                                    />

                                    <TextInput
                                        id="current_password"
                                        // ref={currentPasswordInput}
                                        value={data.password}
                                        onChange={(e) =>
                                            setData('password', e.target.value)
                                        }
                                        type="password"
                                        className="lg:w-64"
                                        autoComplete="current-password"
                                    />

                                    <InputError
                                        message={errors.password}
                                        className="mt-2 sm:text-lg md:text-xl lg:text-2xl"
                                    />
                                </div>
                                <div>
                                    <InputLabel
                                        htmlFor="password_confirmation"
                                        value="Confirmar Contraseña"
                                        className='sm:text-lg md:text-xl lg:text-2xl'
                                    />

                                    <TextInput
                                        id="password_confirmation"
                                        // ref={currentPasswordInput}
                                        value={data.password_confirmation}
                                        onChange={(e) =>
                                            setData('password_confirmation', e.target.value)
                                        }
                                        type="password"
                                        className="lg:w-64"
                                        autoComplete="current-password"
                                    />

                                    <InputError
                                        message={errors.password_confirmation}
                                        className="mt-2 sm:text-lg md:text-xl lg:text-2xl"
                                    />
                                </div>
                              <PrimaryButton disabled={processing}>Guardar</PrimaryButton>
                              {/* <PrimaryButton >Guardar</PrimaryButton> */}

                        </form>

                        {/* <form onSubmit={submit}>
                            <input type="password" value={data.password} onChange={e => setData('password', e.target.value)} />
                            {errors.password && <div>{errors.password}</div>}
                            <input type="password" value={data.password_confirmation} onChange={e => setData('password_confirmation', e.target.value)} />
                            {errors.password_confirmation && <div>{errors.password_confirmation}</div>}
                            {/* <input type="checkbox" checked={data.remember} onChange={e => setData('remember', e.target.checked)} /> Remember Me }
                            <button type="submit" disabled={processing}>Login</button>
                          </form> */}

                      </div>
                  </div>
              </div>
          </div>
      )}



    </>
  )
}

export default index
