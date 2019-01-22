# restaurant-api
El objetivo de este proyecto es la construcción de una API Rest prooverá información acerca de los platos que se sirven en un restaurante. 

## Requisitos funcionales
1. Una base de datos que almacene los platos y sus alérgenos.
2. Una API Rest que devuelva los alérgenos de un plato dado, o los platos en los que aparece un alérgeno concreto, y permite añadir ingredientes, platos y alérgenos.

## Tecnologias
Las tecnologías utilizadas son PHP y MySQL utilizando el framework Symfony4. El desarrollo se realizó sobre PHPStorm Jetbrains,y MySQL Workbench como gestor cliente de bases de datos, sobre un sistema operativo Ubuntu18. 

### Bundles utilizados 

1. symfony/orm-pack: Paquete ORM para instalar el bundle de Doctrine.
2. jms/serializer-bundle: Bundle que permite serializar nuestra data de salida en un formato personalizado (xml, json, entre otros)
3. friendsofsymfony/rest-bundle: Bundle para convertir nuestros métodos dentro de los controladores, en recursos o servicios http (GET, POST, PUT, entre otros) utilizando anotaciones
4. sensio/framework-extra-bundle: Bundle necesario por FOSRestBundle para el soporte de rutas.
5. nelmio/api-doc-bundle: Bundle para generar la documentación del RESTful API.

## Arquitectura del sistema

La arquitectura del proyecto es la propia de un sistema Symfony, donde existe un desacoplamiento entre las capas del proyecto dado que se basa en el diseño web a tres capas MVC. 
 1. El modelo representa la información con la que trabaja la aplicación, resolviendo la lógica de negocio y el acceso a los datos
 2. La vista transforma el modelo en una página web, encargándose de la presentación visual de los datos.
 3. El controlador se encarga de procesar las peticiones del usuario, de decidir cual es la acción que se ejecutaráa continuación y de realizar los cambios en la vista y en el modelo  

## Modelo Lógico

Para darle solución al problema planteado se crearon las clases: Dishes(Platos), Ingredients(Ingredientes) y Allergens(Alergenos). 
1. Un plato puede tener de uno a muchos ingredientes, y un ingrediente puede estar en varios platos. 
2. Un ingrediente puede tener de cero a muchos allergenos, y un allergeno puede estar en varios ingredientes. 

Para el modelado de la relaciones utilizando Doctrine se tomaron las siguientes decisiones: 
1.  Se considera una relación unidirecional donde  Dishes es el propietario de la relación Dishes-Ingredients, y Ingredients es el propietario de la relación Ingredients-Allergens. Esto implica que Dishes tiene facultad para persistir ingredientes en la bases de datos e Ingrdients tiene facultad para persisitir Allergenes. De esta manera es posible enviar una petición al API Rest donde Dishes contenga los ingredientes existan o no, y a su vez los ingredientes contengan allergens existan o no. 
2. Es posible insertar ingredientes con sus alérgenos. 
3. No es posible insertar un alérgeno sin que esté asociado a un ingrediente. 

## Ejecutar el proyecto

1. Actualizar todos los bundles a través del composer update.   
```
composer update
```
2. Configurar la conexión para el acceso a la bases de datos. 
```
DATABASE_URL=mysql://root:pass@127.0.0.1:3306/api_restuarants_bd
```
3. Craer la bases y todas sus entidades
```
php bin/console doctrine:database:create
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```
4. Llenar la bases de datos con valores de prueba  
```
php bin/console doctrine:fixtures:load

```
5. Ejecutar el servidor.
```
php bin/console server:start
```
Si desea cargar la bases de datos directamnete, en este respositorio se encuentra el backup. En ese caso solo es necesario ejecutar los pasos 1,2 y 5. 

## Documentación del API Rest 
Para acceder a la documentación generada del API Rest se accede a través de la siguiente dirección. 
```
http:// localhost:8000/api/doc
```
Desde la documentación es posible ver todas las rutas para consultar el API y realizarle pruebas manuales a las funcionalidades. 

## Formato JSON que espera el API Rest. 
Para insertar un plato con sus ingredientes y sus alergenos.
```
{
	"name": "Nombre del plato",
	"description": "Descripcion del plato",
	"calories": "Calorias: 15kcl",
	"price": 27.50,
	"dishesIngredients": [{
		"name": "Nombre del ingrediente 1"
	}, {
		"name": "Nombre del ingrediente 2",
		"ingredientsAllergens": [{
			"name": "Nombre del Alergeno 1"
		}, {
			"name": "Nombre del Alergeno 2"
		}]
	}]
}
```

Para insertar un ingrediente con sus alergenos. 
```
 {
		"name": "Nombre del ingrediente",
		"ingredientsAllergens": [{
			"name": "Nombre del Alergeno 1"
		}, {
			"name": "Nombre del Alergeno 2 "
		}]
	}

```


## Testing del API rest
Para probar las funcionalidades realizadas se implementaron pruebas unitarias, las cuales pueden ser ejecutadas a través del siguiente comando: 
```
./vendor/bin/phpunit 

```
