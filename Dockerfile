FROM composer:2 as builder
WORKDIR /FeatureSwitches/
COPY composer.* ./
RUN composer install

FROM php:7.4-cli as application

RUN apt-get update -qq && apt-get install git zip unzip -y -qq
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . /FeatureSwitches
WORKDIR /FeatureSwitches
COPY --from=builder /FeatureSwitches/vendor /FeatureSwitches/vendor

CMD [ "/FeatureSwitches/vendor/bin/phpunit", "--testdox" ]
