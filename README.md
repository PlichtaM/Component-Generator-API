# Component Generator API

[![Laravel Version](https://img.shields.io/badge/Laravel-11.x-orange.svg)](https://laravel.com)

REST API do generowania interaktywnych komponentów w formie plików HTML/CSS/JS

## ✨ Funkcjonalności

- Tworzenie modułów poprzez API z parametrami:
  - Wymiary (width/height)
  - Kolor tła
  - Link docelowy
- Generowanie archiwum ZIP z gotowymi plikami
- Automatyczna konfiguracja środowiska przez Docker

## 🚀 Szybki start

### Wymagania wstępne

- Docker
- PHP 8.3+
- Composer

### Instalacja

Sklonuj repozytorium:

```bash
git clone https://github.com/PlichtaM/Component-Generator-API.git
cd component-generator
```

Zmień nazwę `.env.example` na `.env`
```bash
mv .env.example .env
```

Instalacja zależności:

```bash
composer install
```

Uruchom środowisko Docker:

```bash
./vendor/bin/sail up -d
```

Wykonaj migrację bazy danych:

```bash
./vendor/bin/sail artisan migrate
```

## 📚 Dokumentacja API

### Endpointy

`POST /api/modules`
Tworzy nowy moduł

**Parametry:**

```json
{
  "width": 300,
  "height": 200,
  "color": "#FF9B00",
  "link": "https://example.com"
}
```

Przykładowa odpowiedź:

```json
{
  "id": 1
}
```

`GET /api/modules/{id}/download`
Pobiera archiwum ZIP z komponentem

Przykładowe użycie:

```bash
curl -X GET http://localhost/api/modules/1/download --output module.zip
```

## 🧪 Testowanie

Utwórz nowy moduł:

```bash
curl -X POST -H "Content-Type: application/json" -d '{
    "width": 500,
    "height": 300,
    "color": "#FF9B00",
    "link": "https://appverk.com/pl"
}' http://localhost/api/modules
```

Pobierz wygenerowany komponent:

```bash
curl -X GET http://localhost/api/modules/1/download --output component.zip
```

Rozpakuj archiwum i otwórz `index.html `w przeglądarce

## 🛠️ Struktura plików w ZIP

```
module.zip
├── index.html    # Główny plik HTML
├── styles.css    # Style CSS modułu
└── script.js     # Logika kliknięcia
```



Autor: Mariusz Plichta
