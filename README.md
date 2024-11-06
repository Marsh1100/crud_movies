# Proyecto Administración de Veterinaria
Este proyecto es un CRUD (Crear, Leer, Actualizar, Eliminar) diseñado para gestionar información de películas. La API está desarrollada en PHP puro, proporcionando endpoints para la manipulación de datos en la base de datos. El frontend se implementa utilizando JavaScript y Bootstrap.
Para el entorno de desarrollo local, el servidor se configura utilizando XAMPP.

### Pre-requisitos 📋
- **XAMPP** (o cualquier servidor que soporte PHP y MySQL).
- **PHP 7+**.
- **Base de datos MySQL**.

## Instrucciones de Instalación 🔧
1. **Clonar el repositorio**:
   ```bash
   git clone https://github.com/Marsh1100/crud_movies
2. **Mover el proyecto a la carpeta de XAMPP**:
 Colocar el proyecto clonado en la carpeta htdocs de tu instalación de XAMPP.

3. **Configuración de la base de datos**
  - Accede a phpMyAdmin desde tu navegador ingresando a http://localhost/phpmyadmin.
  - Importa el [archivo SQL](https://github.com/Marsh1100/crud_movies/blob/main/data/bdmovies.sql) que incluye la creación de la base de datos con las tablas necesarias y algunos datos iniciales. 

4. **Configuración de la conexión a la base de datos**

En el archivo de configuración [config.php](https://github.com/Marsh1100/crud_movies/blob/main/backend/database/config.php) actualiza las credenciales de acceso a la base de datos.
```php
<?php
define('DB_HOST', 'localhost'); 
define('DB_NAME', 'dbmovies');
define('DB_USER', 'root');
define('DB_PASS', '');
```
5. **Ejecutar el servidor**
   - Inicia XAMPP y activa los módulos de Apache y MySQL.
   - Accede a la API en http://localhost/crud-movies.
6. **Ejecutar el frontend**
   - Abre el archivo index.html en un navegador para ver y probar el frontend.
  
## Enpoints de la API 🎬🍿
- `GET    http://localhost/crud_movies/backend/movie` - Listar todas las películas.
- `GET    http://localhost/crud_movies/backend/movie?id={id}` - Obtener una película por su ID.
- `POST   http://localhost/crud_movies/backend/movie` - Crear una nueva película.
- `PUT    http://localhost/crud_movies/backend/movie?id={id}` - Actualizar una película existente.
- `DELETE http://localhost/crud_movies/backend/movie?id={id}` - Eliminar una película.
  
- `GET    http://localhost/crud_movies/backend/country` - Listar todos los países.
- `GET    http://localhost/crud_movies/backend/genre` - Listar todos los generos.

## Construido con 🛠️
- **Backend:** PHP puro (PDO para la conexión con MySQL).
- **Frontend:** JavaScript, HTML, CSS, Bootstrap.
- **Base de Datos:** MySQL.






