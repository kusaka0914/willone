FROM woa-web-base

RUN apt-get update && apt-get install --no-install-recommends -y strace vim emacs wget \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/*

ENV SQLDEF_VERSION=v0.16.7
RUN wget https://github.com/k0kubun/sqldef/releases/download/$SQLDEF_VERSION/mysqldef_linux_amd64.tar.gz \
  && tar -C /usr/local/bin -xzvf mysqldef_linux_amd64.tar.gz \
  && rm mysqldef_linux_amd64.tar.gz

RUN pecl install xdebug

RUN composer global require

WORKDIR /var/www/html
