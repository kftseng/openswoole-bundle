# Swoole Server Task Transport (Symfony Messenger)

## Usage

1. Install `symfony/messenger` package in your application

    ```sh
    composer require symfony/messenger
    ```

2. Configure Swoole Transport

    ```yaml
    # config/packages/messenger.yaml
    framework:
        messenger:
            transports:
                swoole: swoole://pipe
            routing:
                '*': swoole
    ```

3. Now follow official Symfony Messenger guide to create messages, handlers and optionally different transports.

    https://symfony.com/doc/current/messenger.html

## Implementation Notes

Swoole Pipe Transport sends the given message to all worker instances
