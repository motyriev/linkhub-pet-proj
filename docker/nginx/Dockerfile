FROM nginx:latest

RUN rm /etc/nginx/conf.d/default.conf

COPY default.conf /etc/nginx/conf.d/
#
#COPY . /var/www/html
#
#RUN chmod -R 755 /var/www/html
#
#WORKDIR /var/www/html

EXPOSE 80
