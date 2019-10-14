FROM nginx:stable-alpine

COPY mfc-devtest-ui/config/nginx.conf /etc/nginx/conf.d/nginx.conf
COPY common-config/nginx-error-codes.conf /etc/nginx/conf.d/error-codes.conf

COPY mfc-devtest-ui/www/dist /usr/share/nginx/html/default
