FROM node:20.12.2-alpine3.19 as base
LABEL maintainer="Mathis Gasparotto <mathis.gasparotto@hotmail.com>"

FROM base as dependencies

WORKDIR /app

COPY package.json yarn.lock ./
RUN yarn install

##
# DEV LAYER
##
FROM dependencies as development

ENTRYPOINT ["yarn", "dev"]
#CMD ["yarn", "dev"]

##
# BUILDER LAYER
##
FROM dependencies as builder

COPY --link . .

RUN yarn build --standalone

##
# PRODUCTION LAYER
##
FROM base as production

## Copy the build from the builder layer
