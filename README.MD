# ModDril Modular System

A lightweight Laravel modular domain system for building scalable, domain-driven applications.

---

## 🚀 Introduction

ModDril Modular System provides a clean and structured way to build Laravel applications using a **Domain-based modular architecture**.

Each module:

- Has its own Service Provider  
- Loads its own routes automatically  
- Can be enabled or disabled  
- Is fully isolated  
- Is registered dynamically  

Perfect for large applications, SaaS platforms, and Domain-Driven Design (DDD).

---

## 📦 Installation

Install via Composer:

~~~bash
composer require moddril/modularsystem
~~~

---

## ⚙ Publish Configuration (Optional)

~~~bash
php artisan vendor:publish --tag=moddril-config
~~~

This will publish:

```
config/moddril.php
```

---

## 🏗 Create a Module

~~~bash
php artisan make:module User
~~~

This will generate:

```
app/Domains/User/
├── Http/
│   ├── Controllers/
│   └── Requests/
├── Services/
├── Repositories/
├── Providers/
│   └── UserServiceProvider.php
├── Routes/
│   └── web.php
```

It will also register the module inside:

```
app/Domains/modules.json
```

---

## 🔄 Enable / Disable Module

~~~bash
php artisan module:status {domain} {status}
~~~

Example:

~~~bash
php artisan module:status User true
php artisan module:status User false
~~~

---

## 📋 List All Modules

~~~bash
php artisan module:list
~~~

Example output:

```
+----------+----------+
| Module   | Status   |
+----------+----------+
| Admin    | Enabled  |
| User     | Disabled |
+----------+----------+
```

---

## 📄 modules.json Structure

Location:

```
app/Domains/modules.json
```

Example:

~~~json
{
    "Admin": {
        "enabled": true
    },
    "User": {
        "enabled": false
    }
}
~~~

Only enabled modules are automatically registered.

---

## ✅ Requirements

- PHP 8.1+
- Laravel 10+

---

## 📄 License

This package is open-sourced software licensed under the MIT license.