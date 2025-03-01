# 🏦 Banking System - Domain-Driven Design (DDD)

🚀 **Banking System** to przykładowa implementacja systemu bankowego zgodna z zasadami **Domain-Driven Design (DDD)** w języku PHP 8.3. Aplikacja pozwala na obsługę kont bankowych, wpłaty i wypłaty środków oraz uwzględnia opłaty transakcyjne i limity operacji.

---

## 🏗️ Architektura projektu i podejście DDD

Projekt został zaprojektowany zgodnie z zasadami **Domain-Driven Design (DDD)**, co oznacza, że kod został podzielony na **warstwy**:

- **Domain** (logika biznesowa, encje, wyjątki, repozytoria)
- **Application** (serwisy, przypadki użycia)
- **Infrastructure** (repozytoria, implementacje pamięciowe)

Aplikację można było zapisać w **mniejszej liczbie plików**. Jednak celem było **pokazanie zasad DDD**.<br>
Projekt **pomija wzorzec CQRS**, aby nie komplikować struktury – cała logika jest obsługiwana w **BankAccountService** bez oddzielania komend i zapytań.

## 📂 Struktura katalogów
Projekt został podzielony na **trzy główne warstwy** zgodnie z zasadami **DDD**:

│── **/src**<br>
│   │── **/Domain**            # Warstwa domenowa (logika biznesowa)<br>
│   │   │── **/Entity**        # Główne encje (BankAccount, Payment)<br>
│   │   │── **/ValueObject**   # Value Objects (Currency)<br>
│   │   │── **/Exception**     # Definicje wyjątków domenowych<br>
│   │   │── **/Repository**    # Abstrakcyjne repozytoria<br>
│   │── **/Application**       # Warstwa aplikacyjna (serwisy)<br>
│   │   │── **/Service**       # Serwisy realizujące use case’y<br>
│   │── **/Infrastructure**    # Warstwa infrastrukturalna (repozytoria)<br>
│   │   │── **/Persistence**   # Implementacja repozytoriów (InMemory)<br>
│── **/tests**                 # Testy jednostkowe PHPUnit<br>
│   │── **BankAccountTest.php**<br>
│   │── **PaymentTest.php**<br>
│── **composer.json**          # Konfiguracja Composer<br>
│── **README.md**             # Dokumentacja projektu<br>


---

## ✅ Testy jednostkowe

Projekt zawiera **testy jednostkowe** napisane w **PHPUnit**, które sprawdzają poprawność implementacji.

### 📌 Instalacja PHPUnit
PHPUnit jest już skonfigurowany w `composer.json` jako zależność deweloperska. Jeśli nie masz jeszcze zainstalowanych pakietów, uruchom:
```sh
composer install
```
### 📌 Uruchomienie testów
Aby uruchomić testy jednostkowe, wykonaj polecenie:
```sh
vendor/bin/phpunit tests/BankAccountTest.php
vendor/bin/phpunit tests/PaymentTest.php
