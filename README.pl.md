# Ewidencja polowań - elektroniczna książka polowań

Ewidencja polowań to system umożliwiający mysliwym zgłoszenie chęci polowania wraz z jego późniejszym edytowaniem. 
System spełnia wymogi prawne [regulaminu polowań](https://www.pzlow.pl/przepisy-prawne/regulamin-polowan/).

Spełnia takie wymagania prawne jak:

- Każde polowanie myśliwy może odwołać przed czasem rozpoczęcia, zakończyć wcześniej niż czas 
  zakończenie oraz edytować po zakończeniu w celu dodania upolowanej zwierzyny i ilości oddanych strzałów.
- Myśliwy nie może dokonać wpisu do książki łowieckiej nie wcześniej niż 24 godziny przed rozpoczęciem polowania.
- Data zakończenia polowania musi być mniejsza niż godzina 9:00, gdyż jest to godzina domykania polowań.

Posiada takie opcje jak:

- Zarzadzanie (dodawanie, edycja, blokowanie) użytkownikami poprzez administratora.
- Zarządzanie (dodawanie, edycja, blokowanie) obwodami i rewirami poprzez administratora.
- Dodawanie polowań, możliwość ich odwołania jeśli się jeszcze nie rozpoczęły i późniejsza możliwość ich edycji oraz 
  zakończenia poprzez użytkownika.
- Dodawanie upoważnienia dla danego obwodu poprzez użytkownika.
- Możliwość edycji swoich danych poprzez użytkownika.
- Proste przełączanie się pomiędzy obwodami.

## Wymagania

- PHP 7.4 (lub nowsza wersja, ale nie testowana)
- Serwer WWW, taki jak Nginx z regułą przepisywania adresów lub Apache z .htaccess
- [Composer](https://getcomposer.org/)
- Obsługiwana baza danych przez [Laravel](https://laravel.com/docs/8.x/database#introduction) (MySQL lub Postgres powinny działać
  w porządku)

## Instalacja

Aby zainstalować aplikację, najpierw skopiuj jej pliki do folderu, w którym chcesz ją uruchomić, ustaw folder „public” 
jako główny katalog serwera WWW i ustaw "OpenBaseDir" na folder, w którym znajduje się kod aplikacji.

Następnie należy zainstalować zależności za pomocą [Composer'a](https://getcomposer.org/). Można to zrobić przez
uruchomienie polecenia `composer install` w głównym folderze projektu.

Skopiuj plik `.env.example` do pliku `.env` i uruchom polecenie, aby wygenerować klucz aplikacji. 
Polecenie do wygenerowania klucza:

```shell
php artisan key:generate
```

Następnie możesz dostosować plik konfiguracyjny zgodnie z potrzebami. Przykład, którego używam, znajduje się poniżej. 
Musisz tylko podmień url na którym działa aplikacja, ustawić poprawne połączenie z bazą danych i jeśli nie masz "apc" 
dla PHP, ustawić sterowniki pamięci podręcznej i sesji na "file".

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

Po poprawnym skonfigurowaniu bazy danych należy wykonać migrację za pomocą polecenia:

```shell
php artisan migrate
```

Po udanej migracji zaleca się zaimportowanie danych domyślnych, takich jak domyślne konto administratora o
loginie "akowalski" i haśle "password" (oczywiście użytkownik może być edytowany nawet po zaimportowaniu poprzez 
edycję na bazie danych). Możesz również zaimportować przykładową listę zwierząt, z których myśliwy będzie musiał 
wybierać upolowane zwierzyny.

```shell
php artisan db:seed --class=UserSeeder
php artisan db:seed --class=AnimalSeeder
```

## Licencja

Kod rozpowszechniany na licencji GNU General Public License v3.0. Zobacz LICENSE.txt, aby uzyskać więcej informacji.
