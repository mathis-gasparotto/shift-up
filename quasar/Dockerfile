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

COPY . .

RUN yarn build --standalone

# ##
# # PRODUCTION LAYER
# ##
# FROM base AS production

# ENV NODE_ENV=production

# ## Copy the build from the builder layer
