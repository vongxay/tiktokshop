FROM dunglas/frankenphp:latest-php8.3

# Install PDO MySQL extension
RUN install-php-extensions pdo_mysql mysqli mbstring gd

# Copy application files
COPY . /app

WORKDIR /app

# Set permissions
RUN chmod -R 755 /app

# Expose port
EXPOSE 8080

# Use Caddy (FrankenPHP) to serve
CMD ["frankenphp", "run", "--config", "/app/Caddyfile"]
