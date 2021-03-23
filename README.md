# suplosBackEnd
Prueba suplos desarrollador backend

Servidor de base de datos
Servidor: MariaDB (via TCP/IP)
Tipo de servidor: MariaDB
Conexión del servidor: No se está utilizando SSL Documentación
Versión del servidor: 10.4.10-MariaDB - mariadb.org binary distribution

Servidor web
Apache/2.4.41 (Win64) OpenSSL/1.1.1c PHP/7.4.0
Versión del cliente de base de datos: libmysql - mysqlnd 7.4.0
extensión PHP: mysqliDocumentación curlDocumentación mbstringDocumentación
Versión de PHP: 7.4.0


#Como Correr
Correr código PHP en nuestras computadoras es muy simple.  Sólo vamos a necesitar un servidor web (ejemplo: Apache) y por supuesto, PHP. Si ademas queremos ejecutar código relacionado a bases de datos, necesitaremos también, un servidor de base de datos como bien puede ser: MySQL. Podríamos instalar todos estos componentes de manera individual pero lo mejor en este caso va a ser utilizar WAMP Server ya que nos permitirá contar con todo lo anterior de manera muy sencilla.

La instalacion y el uso de WAMP Server es muy fácil comparada a otros paquetes similares. No necesitamos configurar nada complicado, solo seguir los siguientes pasos a continuación:

1. Ir a la pagina oficial y descargar la ultima versión. https://www.wampserver.com/en/#download-wrapper
2. Instalarlo como cualquier otro programa dándole todo a «Siguiente».
3. Iniciarlo. Generalmente la ruta es: Inicio -> WampServer -> start WampServer
4. Abrir el navegador y tipear http://localhost (o http://127.0.0.1) Si ven la pagina por default de WampServer, la instación fue un éxito!
5. Ahora simplemente ingresen los archivos php que quieran probar en la carpeta «www» del directorio donde se instalo Wamp. Generalmente es «C:\wamp\www».
6. Por último, para ver el resultado de los scripts php, diríjanse con el navegador a http://localhost/tuarchivo.php (donde «tuarchivo.php» es el archivo php que queremos correr.)
