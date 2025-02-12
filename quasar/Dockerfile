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
CMD ["tail", "-f", "/dev/null"]
