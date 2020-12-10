# API SOAP
Prueba técnica PHP con protocolo SOAP

## El link del poryecto 
Navegar hasta [https://apiwalletsoap.herokuapp.com/](https://apiwalletsoap.herokuapp.com/). 

## Aplicaciones necesarias para gestionar el proyecto
* **PHP** v7.3.6 o superior.
* **Laravael** v8
* **Composer** v2.0.8 o superior.
* **Habilitar extension=soap** en la configuracion de PHP.

## Dependencias utilizadas 
* **Zoap** Instant SOAP server for Laravel and Lumen

## Cotrolladores creados
* **AuthController:** Maneja las funciones para el registro y autenticacion de usuarios
* **WalletController:** Maneja las operaciones sobre la billetera.

## Modelos creados
* **User:** Se guarda la informaciond de los usuarios Nombre, Apellido, Documento, Telefono, Email y Password.
* **Wallet:** Guarda el Saldo de la billetera y esta asociada a un usuario.
* **WalletRecords:** Guarda un Historial de Transacciones de la Billetera, tiene un tipo (Compra o Recargar),un estado (Aprovado / Pendiente) y un Token de identificacion con que pueden ser verfidicadas.

## Misc
* **SoapServer:** Se encarga de crear el archivo WSDL para el uso a traves de soap.

## Cómo ejecutar el proyecto de manera local
* Navegar a la raíz del proyecto.
* Ejecutar el comando *composer install*. Esperar a que se instalen todas las dependencias.
* Llenar el archivo .env con los valores necesarios. Mas importantes: 
* Ejecutar el comando *php artisan serve*.
* Navegar hasta [http://localhost:8000](http://localhost:8000)

