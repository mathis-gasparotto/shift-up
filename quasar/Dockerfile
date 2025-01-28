FROM node:22.9.0-alpine3.19 AS base
LABEL maintainer="Mathis Gasparotto <mathis.gasparotto@hotmail.com>"

FROM base AS dependencies

WORKDIR /app

COPY package.json yarn.lock ./
RUN yarn global add @quasar/cli
RUN yarn install --non-interactive

COPY . .

##
# DEV LAYER
##
FROM dependencies AS development

ENV NODE_ENV=development

EXPOSE 9000
#ENTRYPOINT ["yarn", "dev"]
CMD ["yarn" ,"dev"]

##
# BUILDER LAYER
##
FROM dependencies AS builder

CMD ["yarn" ,"build:web"]
# CMD ["tail", "-f", "/dev/null"]

##
# PRODUCTION LAYER
##
FROM base AS production

ENV NODE_ENV=production

RUN apk update && \
  apk add --no-cache \
  apache2

## Copy the build from the builder layer
# COPY --from=builder --chown=www-data:www-data /app/dist/pwa /var/www/localhost/htdocs
COPY --from=builder /app/dist/pwa /var/www/localhost/htdocs

EXPOSE 80

CMD ["httpd", "-D", "FOREGROUND"]
