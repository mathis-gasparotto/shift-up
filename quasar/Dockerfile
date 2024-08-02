FROM node:20.12.2-alpine3.19 as base
LABEL maintainer="Mathis Gasparotto <mathis.gasparotto@hotmail.com>"

FROM base as dependencies

WORKDIR /app

COPY package.json yarn.lock ./
RUN yarn global add @quasar/cli
RUN yarn install --non-interactive --frozen-lockfile

COPY . .

##
# DEV LAYER
##
FROM dependencies as development

ENV NODE_ENV=development

EXPOSE 9000
#ENTRYPOINT ["yarn", "dev"]
CMD ["yarn" ,"dev"]

##
# BUILDER LAYER
##
FROM dependencies as builder

COPY . .

RUN yarn build --standalone

# ##
# # PRODUCTION LAYER
# ##
# FROM base as production

# ENV NODE_ENV=production

# ## Copy the build from the builder layer
