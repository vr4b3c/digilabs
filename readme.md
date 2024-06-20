# digilabs
 Testovací úkol digilabs


## Požadavky

- PHP 8.3
- Composer
- Webový server (např. Apache, Nginx) nebo interní PHP server

## Instalace

1. Naklonujte repozitář nebo stáhněte ZIP s projektem.
    ```bash
    git clone https://github.com/vr4b3c/digilab.git
    cd digilab
    ```

2. Nainstalujte závislosti pomocí Composeru.
    ```bash
    composer install
    ```

3. Zajistěte, že složky `log`, `temp` a `temp/sessions` mají správná oprávnění.
    ```bash
    chmod -R 0777 log temp sessions
    ```

4. Spusťte interní PHP server.
    ```bash
    php -S localhost:8000 -t www
    ```

5. Otevřete webový prohlížeč a přejděte na adresu `http://localhost:8000`.


