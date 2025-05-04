# 🏦 Banking System - Domain-Driven Design (DDD)

🚀 **Banking System** is a sample implementation of a banking system based on **Domain-Driven Design (DDD)** principles using PHP 8.3. The application supports bank account management, deposits and withdrawals, and takes into account transaction fees and operation limits.

---

## 🏗️ Project Architecture and DDD Approach

The project is designed following **Domain-Driven Design (DDD)** principles, meaning the code is divided into **layers**:

- **Domain** (business logic, entities, exceptions, repositories)
- **Application** (services, use cases)
- **Infrastructure** (repositories, in-memory implementations)

The application could have been written in **fewer files**, but the goal was to **demonstrate DDD principles**.<br>
The project **does not include the CQRS pattern** in order to keep the structure simple – all logic is handled in **BankAccountService** without separating commands and queries.

## 📂 Directory Structure

The project is divided into **three main layers** according to **DDD** principles:

│── **/src**<br>
│   │── **/Domain**            # Domain layer (business logic)<br>
│   │   │── **/Entity**        # Core entities (BankAccount, Payment)<br>
│   │   │── **/ValueObject**   # Value objects (Currency)<br>
│   │   │── **/Exception**     # Domain exception definitions<br>
│   │   │── **/Repository**    # Abstract repository interfaces<br>
│   │── **/Application**       # Application layer (services)<br>
│   │   │── **/Service**       # Services implementing use cases<br>
│   │── **/Infrastructure**    # Infrastructure layer (repositories)<br>
│   │   │── **/Persistence**   # Repository implementations (InMemory)<br>
│── **/tests**                 # PHPUnit unit tests<br>
│   │── **BankAccountServiceTest.php**<br>
│   │── **BankAccountTest.php**<br>
│   │── **PaymentTest.php**<br>
│── **.gitignore**            # Git ignored files<br>
│── **composer.json**         # Composer configuration<br>
│── **ecs.php**               # Project uses Easy Coding Standard (ECS) for code style checks and automatic formatting<br>
│── **README.md**             # Project documentation<br>

## 📌 Install Composer Dependencies

```sh
composer install
```

## ✅ Unit Tests
The project includes **unit tests** written in **PHPUnit**, which verify the correctness of the implementation.

### 📌 Running the Tests
To run the unit tests, execute the following commands:
```sh
vendor/bin/phpunit tests/BankAccountServiceTest.php
vendor/bin/phpunit tests/BankAccountTest.php
vendor/bin/phpunit tests/PaymentTest.php
