# Juegos

Los juegos son parte fundamental de la plataforma.

## Gestión de juegos

Los juegos son la base, y pueden ser cargados por el usuario administrador directamente desde la plataforma.

Actualmente, la plataforma es compatible con juegos HTML5 que no reciben parámetros y los exportados en versión HTML5 desde [GameMaker 2](https://www.yoyogames.com/gamemaker/features).

### Requisitos juegos normales HTML5

- Debe ser subido en una carpeta comprimida.
- La carpeta comprimida debe pesar como máximo 10MB.
    - Si se quiere subir un juego más pesado se tienen que subir los archivos manualmente e insertar una fila en la tabla games en la base de datos.

### Requisitos de juegos a cargar de GameMaker 2

Los juegos cargados requieren las sigientes requisitos:

- Exportación desde GameMaker 2 (HTML5)
- Tamaños de pantalla escenario permitido: 960x540px en resolución 16:9
    - Existe otro tamaño que estuvo en pruebas, en resolución 3:4 (vertical) de 540x720px.
- Mantener carpetas por defecto (`/html5game`). Algunas personas modifican este parámetro definido por defecto en GM2.

Para activar la integración de los juegos con el servidor (plataforma) deben considerar la integración con objetos precreados (conversar con Álvaro).

## Gestión de parámetros de juegos

Los parámetros de juegos especifican el nombre y tipo del parámetro que permite el juego.

Cada parámetro de juego admite los tipos: `int`, `boolean`, `float` y `string`.

El administrador de juegos, puede agregar todos los parámetros que requiera a cada juego.

Estos parámetros serán entregados al juego en el evento de carga en el [flujo de comunicación del juego y plataforma](game-flow.md). 

## Gestión de instancias de juego

Las instancias de juego, son creadas en base a los juegos.

Las instancias de juego deben ser creadas al interior de un [experimento](./experiments.md).

## Gestión de instancias de parámetros de juego

Las instancias de parámetros de juego, entregan un valor de un parámetro de juego.

Por ejemplo, según el esquema, el parámetro de juego "version" tiene dos instancias de parámetro (una para cada instancia de juego), donde se define el valor de la variable en cada versión.

---

Para comprender de mejor manera el modelo de datos utilizado por los juegos y sus parámetros, revisa el [diccionario de datos asociado](../dd/games.md).