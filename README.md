<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400"></a></p>

<p align="center">
<a href="https://travis-ci.org/laravel/framework"><img src="https://travis-ci.org/laravel/framework.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains over 1500 video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the Laravel [Patreon page](https://patreon.com/taylorotwell).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Cubet Techno Labs](https://cubettech.com)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[Many](https://www.many.co.uk)**
- **[Webdock, Fast VPS Hosting](https://www.webdock.io/en)**
- **[DevSquad](https://devsquad.com)**
- **[Curotec](https://www.curotec.com/)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Instalacion
```
composer install
```

Configurar la conexion a la DB en el archivo **.env** tomar de ejemplo **.env.example**

Ejemplo:
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=fixup
DB_USERNAME=root
DB_PASSWORD=secret
```

Dar permisos de escritura a las carpetas:

```
./storage/logs
./storage/framework/sessions
```

Generar las claves de encriptación

```
php artisan key:generate
```

```
php artisan passport:install
```

Si en la DB no estan creadas las tablas de passport

```
php  artisan migrate
```

Crear si no existe y dar permiso de escritura al archivo

```
./public/exports/listado_localidades_onsite_2.csv
```

## INSTALACIÓN WINDOWS

1) instalar paquete XAMP (ej. XAMPP)



1.a) Instalar Composer (ejecutable)

https://getcomposer.org/download/



1.b) Instalar Git (ejecutable)

https://git-scm.com/downloads



1.c) Instalar Visual Studio Code (ejecutable)

https://code.visualstudio.com/download

Paquetes útiles a instalar:
- Git History
- GitLens — Git supercharged
- php cs fixer
- PHP Intelephense


2) Crear la BD vacía en PhpMyAdmin --> onsitebd



3) importar dump de BD

1ro: estructura_dump / 2do: configuracion_dump / 3ro: users_dump / 4to: example_dump (si corresponde)



4) Con Git --> Descargar el proyecto Laravel desde el repositorio de Bitbucket en 

/xampp/htdocs



4.a) importar dir de imágenes 

public/



5) Configurar Laravel --> archivo .env 

(tomar .env.example para configurar)


MAILTRAP – crear cuenta y configurar en el .env (11)


6) Correr el comando para instalar librerías

composer install



7) Correr el comando para instalar librerías (si corresponde)

npm install



8) Correr el comando de limpieza

limpieza.sh



9) Agregar virtual host en Windows

C:\Windows\System32\drivers\etc\hosts --> 127.0.0.1 fixup.local onsite.local

C:\xampp\apache\conf\extra\httpd-vhosts.conf -->

<VirtualHost *:80>

 DocumentRoot C:/xampp/htdocs/

 ServerName localhost

</VirtualHost>

<VirtualHost *:80>

   ServerName speedup.nova.local

   DocumentRoot "C:/xampp/htdocs/onsite-repo/public"

   <Directory "C:/xampp/htdocs/onsite-repo/public">

       AllowOverride All

       Options Indexes FollowSymLinks

       Allow from all

       Require all granted

   </Directory>

</VirtualHost>



10) Para ingresar al sistema --> http://onsite.local/

user: admin.bgh@aziende.global / admin.default@aziende.global

pass: test1234


11) Mailtrap --> darse de alta en el servicio y actualizar .env


## Commit / Push / Pull

1. Se debe crear una rama por cada ticket --> nombreapellido/xxx-zzzz, reemplazando nombreapellido por el nombre y apellido del developer, xxx por el id del ticket y zzzz por título del ticket, sin espacios en blanco, (por ejemplo nicolasfuentes/spedres-68-endpoint-getsucursales)
para subir las modificaciones particulares y dicha rama, debe extender de la rama dev


   ```
   git pull origin dev
   ```

2. Antes de empezar a trabajar con un ticket, correr:

   ```
   git pull origin dev
   ```

3. Para subir cambios:

   1. Hacer commit de los cambios a subir (mensaje = código de ticket + título de ticket).

   2. Correr:

      ```
      git pull origin dev
      ```

      para sincronizar con la rama `dev`.

   3. Hacer push.

*Nota: las ramas Master, Dev, Pre y Prod, solo las manipulan los implementadores.*


## JIRA

1. Cuando se empieza a trabajar en un ticket, pasar a "EN CURSO".

2. Cuando se termina un ticket, pasar a "EN TEST" y cargar las horas trabajadas en "seguimiento de tiempo".


LARAVEL VERSION 8.20.1

