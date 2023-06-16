## GameHub
[](./public/assets/logo.svg)
## Guía de instalación de GameHub --Levantamiento de aplicación en entorno local.

# Prerrequisitos

- **[PHP 8.1 o mayor](https://windows.php.net/download#php-8.2)** para descargar [haz click aquí](https://windows.php.net/downloads/releases/php-8.2.7-Win32-vs16-x64.zip)
- **[MySQL](https://mariadb.org/)** para el motor de base de datos, en este caso MariaDB. Para descargar [haz click aquí](https://mariadb.org/download/?t=mariadb&p=mariadb&r=11.0.2&os=windows&cpu=x86_64&pkg=msi&m=insacom)
- **[Composer](https://getcomposer.org/download/)** para instalar las librerías de PHP. Para descargar [haz click aquí](https://getcomposer.org/Composer-Setup.exe)
- **[Nodejs NPM](https://nodejs.org/en/download)** para instalar las librerías de JavaScript. Para descargar [haz click aquí](https://nodejs.org/dist/v18.16.0/node-v18.16.0-x64.msi)
- **[Git](https://git-scm.com/download/win)** para instalar las librerías de JavaScript. Para descargar [haz click aquí](https://nodejs.org/dist/v18.16.0/node-v18.16.0-x64.msi)

Para usar PHP desde la CMD tendremos que seguir los siguientes pasos:

- Descomprimir la carpeta .zip de PHP en la ruta C:/Program Files/

- Crear una variable del de entorno del sistema
    Para esto vamos a proceder a ir a la búsqueda en windows y buscar y acceder a *editar las variables de entorno del sistema* -> *variables de entorno* -> en el cuadro de abajo doble Click en la variable Path -> examinar -> seleccionamos la carpeta descomprimida de PHP
    Aceptamos y cerramos.

    Para comprobar su correcto funcionamiento abre una terminal y ejecuta el comando php --version

    Para que no haya problema con las extensiones de php pueden copiar este archivo [php.ini] y sobreescribir el suyo en.

    Para que se refresquen los cambios deben ejecutar el comando php --ini


## Pasos a seguir post instalación de prerequisitos

Primero clonamos el repositorio en nuestro dispositivo con el siguiente comando -> git clone https://github.com/akselgalea/gamehub.git

- composer install
- npm install

Generamos el archivo de [variables de entorno (.env)](./docs/.env.example) para conectar nuestra base de datos y nuestro mailer.

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_basededatos
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña

Cuando terminemos de definir nuestras variables ejecutamos el comando php artisan key:generate

Luego corremos las migraciones (rellenamos la base de datos con las tablas y datos)

php artisan migrate:fresh --seed

Y listo! Ya podemos inicar nuestra plataforma.

Para lanzar la plataforma en el entorno local lanza los siguientes comandos en 2 pestañas diferentes de la terminal o CMD

php artisan serve
npm run dev

Eso es todo! Ahora puedes acceder a la plataforma mediante la siguiente URL [127.0.0.1:8000](127.0.0.1:8000)

Para dejar de hostear la página puedes usar CTRL + C o simplemente cerrar la terminal.

Al correr las migraciones se generan los siguientes usuarios de prueba:

    Usuario administrador:
        email: admin@pucv.cl
        password: password

    Usuario alumno:
        email: e1@pucv.cl
        password: password

        email: e2@pucv.cl
        password: password

        email: e3@pucv.cl
        password: password

También dejaremos un [archivo .sql](./docs/gamehub.sql) para rellenar la base de datos con datos de juegos para que prueben todas las funcionalidades.
(Para que estos estén en la plataforma deben descargar el .zip subido al aula virtual, ya que los juegos no se encuentran alojados en github)

Para acceder a todas las funcionalidades del sistema recomendamos el uso del usuario administrador.
