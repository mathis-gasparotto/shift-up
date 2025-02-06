FROM node:22.9.0-alpine3.19 AS base
LABEL maintainer="Mathis Gasparotto <mathis.gasparotto@hotmail.com>"

FROM base AS dependencies

ENV NODE_ENV=development

WORKDIR /app

COPY package.json yarn.lock ./
RUN yarn global add @quasar/cli
RUN yarn install

##
# DEV LAYER
##
FROM base AS development
WORKDIR /app

COPY . .
COPY --from=dependencies /app/node_modules ./node_modules

EXPOSE 9000
#ENTRYPOINT ["yarn", "dev"]
CMD ["yarn" ,"dev"]

##
# BUILDER LAYER
##
FROM base AS builder

WORKDIR /app

COPY . .
COPY --from=dependencies /app/node_modules ./node_modules

RUN yarn build:web
# CMD ["yarn" ,"build:web"]
# CMD ["tail", "-f", "/dev/null"]

##
# PRODUCTION LAYER
##
FROM httpd:alpine AS production

# add the certificate
ADD ~/ssl/localhost.crt /usr/local/apache2/conf/server.crt
ADD ~/ssl/localhost.key /usr/local/apache2/conf/server.key

# RUN apk update && \
#   apk add --no-cache \
#   apache2

## Copy the build from the builder layer
# COPY --from=builder --chown=www-data:www-data /app/dist/pwa /var/www/localhost/htdocs
# COPY --from=builder /app/dist/pwa /var/www/localhost/htdocs
COPY --from=builder /app/dist/pwa /usr/local/apache2/htdocs/

EXPOSE 80

# CMD ["httpd", "-D", "FOREGROUND"]
