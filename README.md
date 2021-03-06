# Instalación del proyecto
## Scripts
### installation
Para preparar el entorno de desarrollo local se ha creado un script en la raíz del proyecto llamado `installation.sh` que simplifica todo el proceso. Si usas un sistema Unix como Linux o MacOS, ejecutalo con el comando `bash` indicado en el siguiente ejemplo:

```shell
bash installation.sh
```
Si tuvieras algun tipo de impedimento al ejecutarlo simplemente abrelo y ejecuta los comandos indicados en el interior asegurándote de que tienes [Docker](https://www.docker.com/) y [Docker-compose](https://docs.docker.com/compose/) instalado en tu sistema.

### create-commands-alias
Este comando crea los aliases para trabajar nuestros contenedores docker mas fácilmente, ***se libre de cambiarlos por un alias que te sea mas cómodo para ti***.
```shell
#! /bin/bash

alias _artisan="docker-compose run --rm artisan"
alias _composer="docker-compose run --rm composer"
alias _npm="docker-compose run --rm npm"

# Algunos ejemplos usando el alias:
_composer dump-autoload, _artisan migrate:fresh --seed, _npm install...
```

Date cuenta que los contenedores de **composer**, **artisan** y **npm** no estan levantados todo el tiempo como puede ser nginx ya que solo los necesitamos a la hora de ejecutar los comandos de cada uno optimizando asi el rendimiento.