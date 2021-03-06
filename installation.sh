#! /bin/bash

if [ "$(uname)" == "Darwin" ]; then
    command -v brew >/dev/null 2>&1 || {
        echo >&2 "Installing Homebrew Now"
        /bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install.sh)"
    }

    brew cask install docker
    brew install docker-compose
elif [ "$(expr substr $(uname -s) 1 5)" == "Linux" ] || [ "$(uname)" == "cygwin"]; then
    if ! which docker || ! which docker-compose; then
        sudo apt install docker docker-compose -y
        sudo apt autoremove --purge
    fi
elif [ "$(expr substr $(uname -s) 1 10)" == "MINGW32_NT" ] || [ "$(expr substr $(uname -s) 1 10)" == "MINGW64_NT" ]; then
    echo -e "$(tput setaf 1) You need to install docker manually on windows, more info on $(tput setaf 2)https://docs.docker.com/docker-for-windows/install/"
fi

if test -f "./src/.env"; then

    docker-compose up --d --build --no-cache --remove-orphans
    docker-compose run --rm composer install
    docker-compose run artisan key:generate
    docker-compose run artisan telescope:install
    docker-compose run --rm artisan migrate:fresh --seed
    docker-compose run --rm npm install

    if test -f "./create-commands-alias.sh"; then
        /bin/bash ./create-commands-alias.sh
    fi
else
    echo -e "$(tput setaf 1) There are is no $(tput setaf 2).env $(tput setaf 1)file defined on /src directory. The $(tput setaf 2).env.example $(tput setaf 1)file on /src directory can guide you in the process.$(tput sgr0)"
fi
