# **TWT** AT

![](/images/twt.jpg)

## Introduction:

This is my try to use the **Laravel** framework to rewrite a web project of **backend** development named AT in the **TWT**.

## Get Started

### Prerequisites

To set up the environment, you need to have following dependencies installed.

- `PHP` >= 7.25
- `MySQl `>=5.7
- `composer`

### Installation

First, You need to obtain the package.

```
git clone git@github.com:TWT-At/Server.git
cd Server
```

Then, you need to install dependencies.

```
composer install
```

## Config

You can copy `.env` five from the `.env.example`, configure the basic information.

```
/*You can configure the name and url of the project*/
APP_NAME=at
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://localhost

LOG_CHANNEL=stack

/*You can configure mysql here*/
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=

BROADCAST_DRIVER=log
CACHE_DRIVER=file
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

