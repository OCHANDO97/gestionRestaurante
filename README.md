# 🍽️ Gestión de Restaurante

**Gestión de Restaurante** es una aplicación web que permite administrar integralmente un restaurante, gestionando comandas, facturación, productos, mesas y empleados, con control de acceso mediante roles y permisos.

---

## 📦 Características principales

- ✅ Gestión de **comandas** y **facturación**
- ✅ Visualización de **mesas ocupadas y disponibles**
- ✅ Registro y administración de **usuarios** y **empleados**
- ✅ Asignación de **roles y permisos** con control de acceso
- ✅ Importación de **mesas**, **categorías** y **productos** mediante archivos CSV
- ✅ Generación automática de **ID de empleado** para acceso al sistema
- ✅ Interfaz moderna con **Laravel + Inertia.js (React)**
- ✅ Totalmente **dockerizada** para facilitar instalación y despliegue

---

## 👥 Registro de empleados

Al registrar un nuevo empleado, se genera automáticamente un `id_empleado` de **5 dígitos aleatorios**, el cual será utilizado para iniciar sesión en el sistema.

---

## 📥 Importación de datos

Puedes importar fácilmente mesas, categorías y productos desde archivos CSV. Los archivos de ejemplo se encuentran en la carpeta `/dataEjemplo`.

### 🪑 Mesas

```csv
tab_id,tab_name
1,"Mesa 1"
2,"Mesa 2"
🗂️ Categorías
El campo cat_isActive determina si la categoría será visible en la aplicación.

csv
Copiar
Editar
cat_id,cat_name,cat_isActive
1,Entrantes,1
2,"Platos principales",0
3,Bebidas,1
4,Pizzas,1
🍽️ Productos
El campo pro_categoryID indica a qué categoría pertenece el producto.
pro_price representa el precio del producto.

csv
Copiar
Editar
pro_id,pro_name,pro_price,pro_categoryID
1,Empanadas,2.5,1
2,Croquetas,1.5,1
3,Hamburguesa,4,2
4,Heineken,3.5,3
5,Pepperoni,5.5,4
Los CSV pueden ser cargados en cualquier momento para actualizar o mejorar el menú del restaurante.

🐳 Instalación con Docker
La aplicación está dockerizada para facilitar la configuración y el despliegue.

🔧 Requisitos
Tener Docker y Docker Compose instalados

⚙️ Pasos de instalación
Instalar dependencias de PHP:

bash
Copiar
Editar
docker compose run --rm composer
Instalar dependencias de JavaScript y levantar Vite:

bash
Copiar
Editar
docker compose up -d node
Construir y levantar la app junto con la base de datos:

bash
Copiar
Editar
docker compose up -d --build app db
Ejecutar migraciones y seeders:

bash
Copiar
Editar
docker compose exec app php artisan migrate
docker compose exec app php artisan db:seed --class=RolePermissionSeeder
docker compose exec app php artisan db:seed --class=AsigneRole
🌐 Acceso a la aplicación
Accede desde tu navegador a:
👉 http://localhost:8000

👨‍💼 Usuario administrador de ejemplo
ID Empleado: 39599

Contraseña: 12345678
