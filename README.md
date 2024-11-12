# ESECorp

Sistema de administración de operaciones.

## Descripción

ESECorp es un sistema diseñado para la administración de operaciones, proporcionando una estructura robusta y flexible para manejar diversas funcionalidades empresariales.

## Requisitos

- PHP ^8.0
- Composer

## Instalación

1. Clona el repositorio:
```sh
git clone https://github.com/nandocdev/ese20241111.git
```
2. Navega al directorio del proyecto:
   ```sh
   cd esecorp
   ```
3. Instala las dependencias usando Composer:
   ```sh
   composer install
   ```

## Configuración

1. Copia el archivo de configuración de ejemplo:
   ```sh
   cp config/app.config.php.example config/app.config.php
   ```
2. Edita el archivo `config/app.config.php` con tus configuraciones específicas.

## Uso

Para iniciar la aplicación, ejecuta el siguiente comando:
```sh
composer run serve
```

## Estructura del Proyecto

- `app/`: Contiene los controladores de la aplicación.
- `config/`: Archivos de configuración.
- `core/`: Núcleo del sistema, incluyendo Bootstrap, FlexQuery, Handler, Libs, Orm, y Router.
- `public/`: Contiene el punto de entrada público de la aplicación.
- `src/`: Código fuente adicional.
- `vendor/`: Dependencias instaladas por Composer.

## Autores

- Fernando Castillo Valdés - [ferncastillov@outlook.com](mailto:ferncastillov@outlook.com)

## Licencia

Este proyecto está licenciado bajo la Licencia MIT. Consulta el archivo [LICENSE](LICENSE) para más detalles.
```
Asegúrate de ajustar cualquier detalle específico de tu proyecto según sea necesario.
```