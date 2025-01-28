<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel



## Dependencias

Tener instalado [XAMPP](https://www.apachefriends.org/index.html) (version 7.3)
Tener instalado [Composer](https://getcomposer.org/)

## Proceso de Instalacion

<ol>
    <li> Descargar o clonar el respositorio en alguna carpeta local </li>
    <li> Abrir el proyecto con algún editor de código favorito (Visual studio Code) </li>
    <li> Ejecutar XAMMP e iniciar los módulos de Apache y MySQL</li>
    <li> Abrir una terminal desde su editor favorito</li>
    <li> Previo a inicializar el proyecto, comprobar que tenga instalado todas las dependencias necesarias. Ejecutar los siguientes comandos:</li>
</ol>

```bash
php -v

composer -v
```
<ol start="6"> 
    <li>Una vez finalizado la comprobación de las dependencias necesarias, ejecutar los siguientes comandos para la configuración del proyecto (ejecutar en la terminal):
        <ul>
            <li>Este comando nos va a instalara todas las dependecias de composer
            ```bash
            composer install
            ```
            </li>
            <li>En el directorio de nuestro proyecto buscar el archivo .env.example, duplíquelo, al archivo duplicado cambiar de nombre como .env, este archivo se debe modificar según las configuraciones de nuestro proyecto. A continuación se muestra cómo debería de quedar
            ```bash
            DB_CONNECTION=mysql
            DB_HOST=127.0.0.1
            DB_PORT=3306
            DB_DATABASE=dbfarmacia
            DB_USERNAME=root
            DB_PASSWORD=
            ```
            </li>
            <li>Ejecutar el comando para crear la Key de seguridad
            ```bash
            php artisan key:generate
            ```
            </li>
            <li>Ingrese al administrador de PHP MyAdmin y cree una nueva base de datos, el nombre es opcional, pero por defecto nombrarla tal como se espesifico en el paso anterior, dbfarmacia</li>
            <li>Correr la migracion del proyecto
            ```bash
            php artisan migrate
            ```
            </li>
            <li>Ejecute los seeders, esto creará un usuario administrador, puede revisar las credenciales en el archivo (database/seeders/UserSeeder)
            ```bash
            php artisan db:seed
            ```
            </li>
            <li>Ejecutar el proyecto
            ```bash
            php artisan serve
            ```
            </li>
        </ul>
    </li> 
</ol>


