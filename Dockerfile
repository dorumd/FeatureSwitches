FROM php:7.4-cli as application

RUN apt-get update -qq && apt-get install git -y -qq
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY . /FeatureSwitches
WORKDIR /FeatureSwitches
RUN composer install

CMD [ "./vendor/bin/phpunit", "--testdox" ]
