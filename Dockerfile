FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

WORKDIR /var/www/html

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update && apt-get upgrade -y \
    && mkdir -p /etc/apt/keyrings \
    && apt-get install -y gnupg curl ca-certificates zip unzip git libcap2-bin libpng-dev \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0xb8dc7e53946656efbce4c1dd71daeaab4ad4cab6' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu noble main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y php8.4-cli php8.4-mysql php8.4-mbstring php8.4-xml php8.4-zip php8.4-bcmath php8.4-curl php8.4-gd \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY . .

RUN composer install --optimize-autoloader --no-dev --no-interaction

EXPOSE 8000

RUN echo '#!/bin/bash\nset -e\nphp artisan migrate --force\necho "Starting server on port $PORT"\nexec php artisan serve --host=0.0.0.0 --port=${PORT:-8000}' > /start.sh \
    && chmod +x /start.sh

CMD ["/bin/bash", "/start.sh"]
