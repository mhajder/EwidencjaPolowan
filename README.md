# Ewidencja polowań - electronic hunting book

Ewidencja polowań is a system that allows hunters to report their desire to hunt and edit it later when it will end. The
system complies with the legal requirements of
the [rules of hunting](https://www.pzlow.pl/przepisy-prawne/regulamin-polowan/).

It meets such legal requirements as:

- Each hunt can be canceled by the hunter before the start time, ended earlier than the end time and edited after the
  end time, to add the hunted animals, and the number of shots fired.
- The hunter cannot make an entry in the hunting book not earlier than 24 hours before the beginning of the hunt.
- The end date of the hunt must be less than 9:00 am, because it is the time of automatic closing the hunts.

It has options such as:

- User management by the administrator
- District and hunting ground management by the administrator
- Adding hunts, possibility of canceling them if they have not started yet, possibility of editing and ending them later
  by the user.
- Adding authorization for a given district by the user.
- Possibility to edit own profile by the user.
- Simple switching between districts.

## Requirements

- PHP 7.4 (or newer versions but not tested)
- Web server like Nginx with rewrite rule or Apache with .htaccess
- [Composer](https://getcomposer.org/)
- A supported database by [Laravel](https://laravel.com/docs/8.x/database#introduction) (MySQL or Postgres should work
  fine)

## Installation

To install the application, first copy its files to the folder where you want to run it, set the "public" folder as the
main directory of the web server and set "OpenBaseDir" to the folder where the application code is located.

After that, you need to install dependencies using the [Composer](https://getcomposer.org/). This can be done by
running `composer install` command in main project folder.

Copy the `.env.example` file to the `.env` file and run the command to generate app key. The command to generate the
key:

```shell
php artisan key:generate
```

You can then customize the configuration file as desired. An example that I use is provided below. You only need to
replace the url on which the application is running, set the correct connection to the database and if you do not have "
apc" for PHP, set cache and sessions drivers to "file".

```dotenv
APP_NAME=EwidencjaPolowan
APP_ENV=production
APP_KEY=base64:<generated_base_64_encoded_key>
APP_DEBUG=false
APP_URL=https://subdomain.example.com

LOG_CHANNEL=stack
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=database
DB_USERNAME=username
DB_PASSWORD=password

BROADCAST_DRIVER=log
CACHE_DRIVER=apc
QUEUE_CONNECTION=sync
SESSION_DRIVER=apc
```

After the database is correctly configured, the migration should be performed using the following command:

```shell
php artisan migrate
```

After successful migration, it is recommended to import the default data, such as the default administrator with the
login "akowalski" and the password "password" (of course, the user can be edited even after importing on the database
side). You can also import a sample list of animals that a hunter will have to choose from.

```shell
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=AnimalSeeder
```

## License

Code distributed under the GNU General Public License v3.0 License. See LICENSE.txt for more information.
