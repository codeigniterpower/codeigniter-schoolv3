# Como comenzar
===========================

Este documento le indicara instrucciones de como comenzar desarrollar y que usar en este proyecto

* [Como comenzar a trabajar](#como-comenzar-a-trabajar)
 * [1 Requisitos para trabajar](#1-requisitos-para-trabajar)
 * [2 Configurar tu entorno](#2-configurar-tu-entorno)
 * [3 clonar las fuentes](#3-clonar-las-fuentes)
 * [4 Cargar en Geany y ver en web](#4-cargar-en-geany-y-ver-en-web)
 * [5 Inicializar la base de datos](#5-inicializar-la-base-de-datos)
* [Estructura de desarrollo](#estructura-de-desarrollo)
 * [Modelo de datos y base de datos](#modelo-de-datos-y-base-de-datos)
 * [Codigo y fuentes](#codigo-y-fuentes)
 * [Querys SQL](#querys-sql)
 * [codigo PHP](#codigo-php)
 * [Como trabajar git](#como-trabajar-git)
* [Logica aplicacion web](#logica-aplicacion-web)
 * [Inicio sesion y modelo usuario](#inicio-sesion-y-modelo-usuario)


## Como comenzar a trabajar
---------------------------

***Crear un directorio `Devel` en home, cambiarse a este y alli 
clonar el repo, iniciar y arrancar el editor Geany.***

Todo esto se explica en detalle a continuacion por partes

El sistema esta con uan base php en codeigniter 3 pero sin embargo tiene una logica 
de permisologia y dos naturalezas de usuario, significa que cada modulo no funciona 
solo por invocarlo, debe pertenecer a una permisologia y el usuario debe estar asociado 
a un perfil de permisologia, igualmente para el menu que se muestra.

En la seccion [Inicio sesion y modelo usuario](#inicio-sesion-y-modelo-usuario)
se detalla este sistema de sesiones.

La estructura de los assets y de la medai esta muy populada, empleando exageradamente varias 
librerias que terminan siendo complicadas de administrar por la gran cantidad, 
adicional el directorio media esta siendo empleado tanto para los archivos de 
medios del sistema asi como para las subidas de los usuarios.

Sobre la forma de desarrollo en la seccion [Estructura de desarrollo](#estructura-de-desarrollo)
se detallan las dificultades y logicas para empezar a trabajar.

### 1 Requisitos para trabajar

* sistema linux soportado: Debian: 7, 8, 9; Buntu 14, 16, 17, 18, 21; VenenuX 7, 8, 9; Devuan 1, 2, 5
* crear o su suaurio tener que llamarse `general` y usar este como el usuario principal en su pc de desarrollo
* git (manejador de repositorio y proyecto) `apt-get install git git-core giggle`
* mysql (manejador y servidor DB que hara de pivote) `apt-get install mysql-client mysql-server` (no hacer si tiene percona)
* odbc, myodbc, freetds (coneccion DB mysql, ODBC para sybase y mssql) `apt-get install tdsodbc`
* geany (editor para manejo php asi como ver el preview) `apt-get install geany geany-plugin-webhelper`
* lighttpd (webserver localmente para trabajar el webview) `apt-get install lighttpd`
* php (interprete) en debian/buntu `apt-get install php-cgi php-mysql php-odbc php-gd php-mcrypt php-curl`
* curl (invocar urls) `apt-get install curl`

Se recomienda usar mysql-workbench con `apt-get install mysql-workbench` para carga y trabajo con data sql.

Para mayor info leer el archivo [INSTALL.md](INSTALL.md)

### 2 Configurar su entorno

**IMPORTANTE** ejecute cada bloque de comandos separado por una linea en blanco, 
**cada linea en blanco es y separa otro lote de comandos a ejecutar** al mismo tiempo!

Debe leer el archivo [INSTALL.md](INSTALL.md).

### 3 clonar las fuentes y cargar la base de datos

Se usa git para tener las fuentes y se arranca el IDE geany para codificar, 
ejecute como usuario `general` de su pc, debe clonar las fuentes en Devel de home:

``` 
mkdir -p ~/Devel
cd Devel
git clone --recursive https://gitlab.com/codeigniterpower/codeigniter-schoolv3/
```

**IMPORTANTE** ahora pra probar su codigo redirija

### 4 Inicializar la base de datos


Debe leer el archivo [INSTALL.md](INSTALL.md).

**NOTA IMPORTANTE** esto es asumiendo que su base de datos esta configurada como se indico, 
si no es asi debe ejecutar los pasos documentados en el proyecto https://proyectos.tijerazo.net/soporte/manuales-maquinas-linux


### 5 Cargar en Geany y ver en web

El editor para elproyecto es Geany o Kate, no se da soporte a otro editor

* abrir el geany
    * ir a menu->herramientas->admincomplementos
    * activar webhelper(ayudante web), treebrowser(visor de arbol) y addons(añadidos extras)
    * activar vc y gitchangebar para poder trabajar con el repo git
    * aceptar y probar el visor web (que se recarga solo) abajo en la ultima pestaña de las de abajo
* en el menu proyectos abrir, cargar el archivo `Devel/elgasto/elgasto.geany` y cargara el proyecto
    * en la listado seleccionar el proyecto o el directorio `~/Devel/elgasto`
* depsues abajo ubicar en las pestañas la vista web que es un mininavegador
    * cargar abajo en la ultima pestaña de webpreview la ruta http://127.0.0.1/Devel/ y visitar elgasto

**NOTA IMPORTANTE** su usuario DEBE DE llamarse `general`

# Estructura de desarrollo
===========================

Este es un proyecto basado en codeigniter como base php, pero que emplea 
una gran cantidad de librerias javascrip, y muchas llamadas server side.

Esta es la mayor desventaja de este proyecto requiriendo que el perfil 
de desarrollo sea forzosamente alguien muy costoso.

## Codigo y fuentes

El directorio [mvc](mvc) contiene el codigo fuente del sistema, 
se trabajara SQL con percona y PHP con framework codeigniter2 y se maneja con GIT, 
abajo se describe cada uno y como comenzar de ultimo.

En el directorio [docs](docs) esta el archivo `database.sql` el cual habra cargado 
esto en el servidor localhost de la maquina instalado en "localhost" y especificar o 
corregir la conexcion en el archivo `mvc/config/*/database.php` del grupo correspondiente

### medias, Javascripts y CSS

Estos archivos van en el directorio `assets`, y en un futuro migrados a `elgastofiles` 
en donde estaran tanto las cargas (uploads) asi como los assets futuros.

Aqui hay varias librerias javascript y el dominio de todas es imposible, debera 
hacer referencia y ayuda de todas estas entre varios personas y allegados.

## Modelo de datos y base de datos

El directorio contiene el modelo, imagenes y scripts SQL, 
se usa una DB central que actualiza la tablas de usuarios y modulos, y 
se conecta a sybase para obtener los datos de reportes. (Esto en un futuro)

* base de datos MySQL/MariaDB, Sybase. Se emplea MySQL solo para pintar los reportes en tablas al vuelo.
* modelado de datos en mysqlworkbench formato script STANDARD usuario no especificado
* formato tablas en pares cabecera/detalle como maximo la tabla detalle incluye el nombre cabecera separado por `_`
* no hay llaves foraneas, integracion de los datos viene data por la aplicacion, puesto se maneja otras db
* no hay llaves foraneas, permitiendo la manipulacion de los datos para modularizacion y flexibilidad

Para iniciar una conexcion en un php dentro del framework sera asi en un controlador, vista o modelo:

``` php
	$dbmy = $this->load->database('oasisdb', TRUE);
	$driverconected = $dbmy->initialize();
	if($driverconected != TRUE)
		return FALSE;
	$queryprovprod = $dbmy->query("SELECT * FROM tabla");
	$arreglo_reporte = $queryprovprod->result_array();
```

**IMPORTANTE** este codigo y las consultas deben realizarse 
en archivos php en el directorio `mvc/models` pero 
el framework permite que dicho codigo se ejecute en cualquier lado.

### Codigo PHP

Se emplea Codeigniter version 3, se describe mas abajo como iniciar el codigo, 
el empleo de el codeigniter 2 es porque es uan aplicacion heredada, 
se describe como funciona aqui:

* **mvc/controllers** cada archivo representa una llamada web y determina que se mostrara
* **mvc/views** aqui se puede separar la presentacion de los datos desde el controller
* **mvc/libraries** toma los datos y los amasa, moldea y manipula para usarse al momento o temporal
* **mvc/models** toma los datos y los amasa, modea y prepara para ser presentados o guardados

Para establecer una equivalentea "mvc" es lo mismo que el directorio "applications" de 
el codeigniter, y el directorio "appsys" el lo mismo que el directorio "system" de codeigniter.

### Modulos y Menu automatico

Los **Modulos** seran sub directorios dentro del directorio de controladores, 
cada sub directorio sera un modulo del sistema, y dentro cada clase controller 
sera una llamada web url, ademas de los que ya esten en el directorio `elalmacenwebweb/controllers` 
que tambien seran una llamada web url.

El **Menu** es un sistema traido desde la base de datos, desde la tabla menus y cada entrada 
debe estar en secuencia y cada entrada solo aparecera si existe un respectivo permiso en la tabla de permisos, 
hay dos niveles de menu, el menu principal que es todo lo de primer nivel (directorios y los index) 
y el menu de cada modulo, que se construye pasando el nombre del subdirectorio (solo los controlers).

## Como trabajar con git

El repositorio principal contine adentro el de codeigniter, de esta forma si se actualiza, 
si tiene contenido nuevo, hay que primero traerlo al principal, 
y despues actualizar la referencia de esta marca, entonces el repositorio principal tendra los cambios marcados.

**POR ENDE**: los commits dentro de un submodulo son independientes del git principal

1. primero debe **"coordinar" el repo git**, esto es mantenerlos actualizados con fetch y pull
2. segundo haga **"coordinar" estos submodulos tambien**, por si cada uno tiene alguna actualizacion
3. despues debe **check y pull en los submodulos antes** de hacer commit y push en el principal
4. ya entonces **con todo al dia, puede editar archivos** para trabajar en el desarrollo
5. entonces terminado de editar realize **adicion al repo de estos cambios**
6. despues **haga commit de estos cambios para registrarlos** en el historial del repo
7. y **push hacia el repo remoto para que otros** asi puedan tambien coordinar los cambios

``` bash
git fetch && git pull

git submodule init && git submodule update --rebase

git submodule foreach git checkout master && git submodule foreach git pull

editor archivo.nuevo # (o abres el geany aqui y trabajas)

git add <archivo.nuevo> # agregas este archivo nuevo o mejor en el geany le das commit y hace todo

git commit -a -m 'actualizado el repo adicionado <archivo.nuevo> modificaciones'

git push
```

En la sucesion de comandos se trajo todo trabajo realizado en los submodulos 
y actualiza "su marca" en el principal, despues que tiene todo a lo ultimo coordinado 
se edita un archivo nuevo y se acomete

**IMPORTANTE** Geany debe tener los plugins addons y filetree cargados y activados 
en caso contrario debe leer y hacer lo que esta en el documento de https://proyectos.tijerazo.net/soporte/manuales-maquinas-linux


# Logica aplicacion web
---------------------------

## Inicio sesion y modelo usuario

Este proyecto emplea un esquema de migracion hibrida, se emplea una db base mas no central, donde se hace 
pivote de usuario, acceso y acciones, despues se conecta a otras db remotas pór odbc para presentar datos.

En la tabla `usuarios` se lista usuario y clave, pero su acceso se define realmente por `perfiles` 
ya que el profesor es un tipo de usuario especial que no aparece en la tabla de usuarios, 
este siemrpe tendra el perfil de profesor.

# Modulos

IMPORTANTE: (WIP) la tabla de modulos se actualiza sola en cada request.

En los directorios de vistas, modelo y controladores hay subdirectorios, cada uno de estos 
representara un modulo y cada uno abordara una funcionalidad especifica de la logica de la app.

