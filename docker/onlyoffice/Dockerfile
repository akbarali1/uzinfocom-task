FROM ubuntu:latest

RUN apt-get update && apt-get install -y \
    curl \
    sudo \
    && rm -rf /var/lib/apt/lists/*

ARG user
ARG uid

RUN useradd -m -u $uid -s /bin/bash $user

RUN mkdir -p /var/www/onlyoffice/Data && \
    chown -R $user:$user /var/www/onlyoffice/Data

USER $user

CMD ["/bin/bash"]