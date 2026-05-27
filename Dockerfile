FROM php:8.4-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    nodejs \
    npm \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    libicu-dev \
    libonig-dev

# Clear apt cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Configure and install PHP extensions needed for Laravel & Spatie Medialibrary
RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath gd intl

# Get modern Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Create system user to match Arch Linux host UID/GID (prevents permission issues)
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy the entrypoint script and make it executable (while we are still root)
COPY docker/app/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port 9000 and start php-fpm server
EXPOSE 9000

# Change current user to www for security and file ownership
USER www

# Set the entrypoint
ENTRYPOINT ["entrypoint.sh"]

# Default command remains the same
CMD ["php-fpm"]
