# restaurant-api
El objetivo de este proyecto es la construccion de una API Rest que le servirá la información que necesita acerca de los platos que se sirven en un restaurante. 

## Requisitos funcionales
1. Una base de datos que almacene los platos y sus alérgenos.
2. Una API Rest que devuelva los alérgenos de un plato dado, o los platos en los que aparece un alérgeno concreto, y permite añadir ingredientes, platos y alérgenos.

## Tecnologias
Las tecnologías a usar son PHP y MySQL utilizando el framework Symfony4 para el desarrollo de la solución. 

### Bundles necesarios 

1. symfony/orm-pack: Paquete ORM para instalar el bundle de Doctrine.
2. jms/serializer-bundle: Bundle que permite serializar nuestra data de salida en un formato personalizado (xml, json, entre otros)
3. friendsofsymfony/rest-bundle: Bundle para convertir nuestros métodos dentro de los controladores, en recursos o servicios http (GET, POST, PUT, entre otros) utilizando anotaciones
4. sensio/framework-extra-bundle: Bundle necesario por FOSRestBundle para el soporte de rutas.
5. nelmio/api-doc-bundle: Bundle para generar documentación de nuestro RESTful API.

## Modelado de la solución
