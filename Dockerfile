FROM mysql:5.7
ENV MYSQL_ROOT_PASSWORD root
ENV MYSQL_DATABASE pats
ENV MYSQL_USER pats
ENV MYSQL_PASSWORD 41xgroup69
ADD pats.sql /docker-entrypoint-initdb.d
EXPOSE 3306