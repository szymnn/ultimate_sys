## Założenia:
- stworzenie api pozwalającego na rejestracje/logowanie odświerzenie tokenu
- API ma zwracać dane w JSON
- Api ma korzystać z JWT
## Przygotowanie:
- utworzenie czystego projektu
- zainicjowanie git-a
- dodanie biblioteki JWT
- stworzenie API oraz rutów
- dodanie biblioteki swagger

## Endpoints:
- /api/login - logowanie [POST]
- /api/register - rejestracja [POST]
- /api/logout - wylogowania [GET]
- /api/user_info - dane użytkownika [GET]
- /api/refresh   - odświerzenie tokenu [GET]
- /api/documentation - dokumentacja `swagger`


## Pierwsze uruchomienie:
- Należy dodać bazę danych do projektu w .env oraz wszystkie dane logowania
- wpisać komendę php artisan migrate

## Testowy Serwer:
[https://portfolio.sk99.pl/api/documentation]()

## Prace nad projektem step by step:
  - Edycja pliku `app.php`:
    - dodanie do provider:
        - Tymon\JWTAuth\Providers\LaravelServiceProvider'
    - dodanie do aliasów
        - 'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,
          'JWTFactory' => Tymon\JWTAuth\Facades\JWTFactory::class,
  - Publikacja i utworzenie tokena :
    - php artisan vendor:publish --provider="Tymon\JWTAuth\Providers\LaravelServiceProvider"
    - php artisan jwt:secret
  - Stworznie middleware JWT - który będzie odpowiadał za logowanie/ rejestracje
  - Edycja modelu `USER` - dpdanie bibliotek JWT:
    - use Tymon\JWTAuth\Contracts\JWTSubject;
    - rozszerzamy klasę o interface JWTSubject (implements JWTSubject)
    - dodajemy funkcje:
      - public function getJWTIdentifier()
      - public function getJWTCustomClaims()
  - Edycja KERNELU - dodanie łączenia się z JWT
  - Stworzenie kontrolera użytkowników `AuthController`
  - Utworzenie routingu
  - Dodanie biblioteki `swagger`
  - Implementacja biblioteki

## Pierwsze testy były wykonywane na postman
1. Uruchomienie postman
2. Dodanie nowego linku np. localhost/_nazwa-projektu_/api/register
3. Ustawienie opcję POST
4. Ustawienie postman na BODY

## Opcja 1:
	- Zaznaczamy form-data i w polu klucz(key) wpisujemy:
	--name
	--email
	--password
	- W polu wartość dowolne dane - zostaną one zwalidowane
## Opcja 2:
	- Zaznaczyć pocję RAW
	- Zmienić opcję z TEXT na JSON
	- wstawić odpowiednie dane np. dla rejestracji:
	 {
		 "name"  	: "test123",
		 "email" 	: "asd123@asd.pl",
		 "password"	: "testpass123"
	 }

Dla logowania zmieniamy tylko ROUTE z /api/register na api/login

#ROUTE dla wylogowania:
- Aby móc się wylogować z api oraz chodzić po zabezpieczonych routach należy w header podać token:
  - W postmanie należy wpisać `Barrer <utworzony token z logowania>`
- Konieczne jest przestawienie metody na `GET`
- Po tej operacji uzyskany jest dostęp do zabezpieczonych endpoin-tów

