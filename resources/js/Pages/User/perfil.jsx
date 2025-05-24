import { useForm, router, Link } from '@inertiajs/react';
import { useEffect, useState } from 'react';
import Navbar from '@/Components/Navbar';
import MobileHeader from "@/Components/MobileHeader";
import Header from '@/Components/Header';

const Perfil = ({ user, allPermissions }) => {
  const [selected, setSelected] = useState(user.permissions.map(p => p.name));

  const togglePermission = (perm) => {
    if (selected.includes(perm)) {
      setSelected(selected.filter(p => p !== perm));
    } else {
      setSelected([...selected, perm]);
    }
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    router.post(route('users.permissions.update', { user: user.id }), {
      permissions: selected,
    }, {
      onSuccess: () => alert('Permisos actualizados correctamente'),
    });
  };

  return (
    <>
      <Navbar />
      <MobileHeader title="Ver Perfiles" backRoute={route('user.index')} />
      <Header title="Ver Perfiles" />
    
      <div className="p-6 max-w-xl mx-auto">
        <h1 className="text-xl font-bold mb-4">Perfil de {user.name}</h1>
        <form onSubmit={handleSubmit} className="max-w-md mx-auto p-4 bg-white shadow rounded">
          <h2 className="text-lg font-bold mb-4">Asignar Permisos</h2>
          
          <div className="space-y-2">
            {allPermissions.map((perm) => (
              <label key={perm} className="flex items-center gap-2">
                <input
                  type="checkbox"
                  value={perm}
                  checked={selected.includes(perm)}
                  onChange={() => togglePermission(perm)}
                />
                {perm}
              </label>
            ))}
          </div>

          <div className="mt-4 flex gap-2">
            <button type="submit" className="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
              Guardar
            </button>
            <button
              type="button"
              onClick={() => setSelected([])}
              className="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600"
            >
              Quitar todos
            </button>
          </div>
        </form>
      </div>
    </>

  );
};



export default Perfil;
