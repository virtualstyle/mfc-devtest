FROM nginx:stable-alpine

COPY vs-proxy/config/proxy.conf /etc/nginx/conf.d/default.conf
COPY common-config/nginx-error-codes.conf /etc/nginx/conf.d/error-codes.conf

COPY vs-proxy/www/default /usr/share/nginx/html/default
