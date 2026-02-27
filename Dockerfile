FROM ubuntu:24.04

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC

WORKDIR /var/www/html

# Timezone
RUN echo "Setting timezone..." \
    && ln -snf /usr/share/zoneinfo/$TZ /etc/localtime \
    && echo $TZ > /etc/timezone \
    && echo "Timezone set ✅"

# Install dependencies + PHP
RUN echo "Updating apt..." \
    && apt-get update && apt-get upgrade -y \
    && echo "Installing base packages..." \
    && mkdir -p /etc/apt/keyrings \
    && apt-get install -y gnupg curl ca-certificates zip unzip git libcap2-bin libpng-dev \
    && echo "Adding PHP repo..." \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0xb8dc7e53946656efbce4c1dd71daeaab4ad4cab6' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu noble main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && echo "Installing PHP..." \
    && apt-get install -y php8.4-cli php8.4-mysql php8.4-mbstring php8.4-xml php8.4-zip php8.4-bcmath php8.4-curl php8.4-gd php8.4-sqlite3 \
    && echo "Installing Composer..." \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && echo "Cleaning up..." \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && echo "Dependencies installed ✅"

# Copy project
RUN echo "Copying project files..."
COPY . .
RUN echo "Project copied ✅"

# Laravel setup
RUN echo "Setting up Laravel..." \
    && cp .env.example .env || echo ".env already exists or missing example" \
    && echo "Installing composer dependencies..." \
    && composer install --optimize-autoloader --no-dev --no-interaction \
    && echo "Generating app key..." \
    && php artisan key:generate || echo "Key generation skipped" \
    && echo "Fixing permissions..." \
    && chmod -R 775 storage bootstrap/cache \
    && echo "Laravel setup done ✅"

EXPOSE 8000

# Start script
RUN echo '#!/bin/bash\n\
set -e\n\
echo "Clearing caches..."\n\
php artisan config:clear && echo "Config cleared ✅"\n\
php artisan route:clear && echo "Routes cleared ✅"\n\
php artisan view:clear && echo "Views cleared ✅"\n\
echo "Running migrations..."\n\
php artisan migrate --force && echo "Migrations done ✅"\n\
echo "Starting server on port $PORT"\n\
exec php -S 0.0.0.0:${PORT:-8000} -t public\n' > /start.sh \
    && chmod +x /start.sh \
    && echo "Start script ready ✅"

CMD ["/bin/bash", "/start.sh"]
