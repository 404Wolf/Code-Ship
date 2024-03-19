FROM ubuntu

RUN apt-get update

# Install Nginx
RUN apt-get install nginx -y

# Install PH
ENV DEBIAN_FRONTEND=noninteractive
RUN apt-get install php-fpm -y

# Only allow traffic on port 80
RUN apt-get install ufw -y
RUN ufw allow 'Nginx HTTP'

# Remove default configuration files
RUN rm /etc/php/*/fpm/php.ini

# Copy configuration files
COPY nginx.conf /etc/nginx/nginx.conf
COPY static /usr/share/nginx/html
COPY php.ini /etc/php/*/fpm/php.ini

# Create test.php
RUN echo "<?PHP echo 'It works!' ?>" /var/www/html/test.php

# Expose port 80
EXPOSE 80

# Start Nginx
CMD ["nginx", "-g", "daemon off;"]