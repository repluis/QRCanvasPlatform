# QRCanvasPlatform - Arquitectura del Proyecto

## Objetivo

El proyecto seguirá una arquitectura basada en Domain Driven Design (DDD), Clean Architecture y principios SOLID.

El backend y el frontend estarán completamente separados a nivel de responsabilidades aunque pertenezcan al mismo proyecto Laravel.

---

# Backend

Framework

- Laravel 13

Arquitectura

- Domain Driven Design
- Clean Architecture
- SOLID
- Repository Pattern
- Service Pattern
- Dependency Injection
- DTO Pattern
- Action / UseCase Pattern

No se permitirá lógica de negocio dentro de:

- Controllers
- Models
- Requests
- Resources

Toda la lógica irá dentro del dominio.

---

## Estructura Backend

```
src/
│
├── Shared/
│   ├── Domain/
│   ├── Infrastructure/
│   ├── Application/
│   └── Support/
│
├── Auth/
├── Users/
├── Roles/
├── Pages/
├── QR/
├── Analytics/
├── Domains/
├── Campaigns/
├── Media/
└── Settings/
```

Cada módulo tendrá exactamente esta estructura:

```
Module/
├── Application/
│   ├── DTO/
│   ├── Actions/
│   ├── Services/
│   ├── Queries/
│   └── Commands/
├── Domain/
│   ├── Entities/
│   ├── Repositories/
│   ├── Contracts/
│   ├── ValueObjects/
│   ├── Exceptions/
│   ├── Events/
│   └── Policies/
└── Infrastructure/
    ├── Controllers/
    ├── Persistence/
    │   ├── Repositories/
    │   └── Models/
    ├── Resources/
    ├── Mappers/
    └── Routes/
```

---

## Ejemplo

```
Pages/
├── Application/
│   ├── Actions/
│   │   ├── CreatePageAction.php
│   │   ├── UpdatePageAction.php
│   │   └── DeletePageAction.php
│   └── DTO/
│       ├── CreatePageDTO.php
│       └── UpdatePageDTO.php
├── Domain/
│   ├── Entities/
│   │   └── Page.php
│   └── Repositories/
│       └── PageRepositoryInterface.php
└── Infrastructure/
    ├── Controllers/
    │   └── PageController.php
    └── Persistence/
        ├── Repositories/
        │   └── PageRepository.php
        └── Models/
            └── PageModel.php
```

---

# Flujo Backend

```
Request
    ↓
Controller
    ↓
Action
    ↓
Repository Interface
    ↓
Repository
    ↓
Model
    ↓
Database
```

Nunca un Controller hablará directamente con un Model.

Nunca un Controller tendrá reglas de negocio.

---

# Frontend

## Tecnologías

- Vue 3
- Inertia
- Vite
- Pinia
- TailwindCSS
- PrimeVue
- VueUse
- Vue Router (únicamente si en el futuro se separa el frontend del backend)

## Arquitectura

Feature Based Architecture.

No se organizará por tipo de archivo sino por funcionalidad.

---

## Estructura Frontend

```
resources/
└── js/
    ├── app.js
    ├── Layouts/
    ├── Components/
    ├── Composables/
    ├── Stores/
    ├── Services/
    ├── Utils/
    ├── Types/
    ├── Constants/
    └── Pages/
        ├── Auth/
        ├── Dashboard/
        ├── Pages/
        ├── QR/
        ├── Analytics/
        ├── Campaigns/
        ├── Users/
        ├── Domains/
        └── Settings/
```

---

## Cada Feature

```
Pages/
├── Components/
├── Composables/
├── Services/
├── Types/
├── Views/
│   ├── Index.vue
│   ├── Create.vue
│   ├── Edit.vue
│   └── Show.vue
└── Stores/
```

---

# Componentes Globales

```
Components/
├── Buttons/
├── Inputs/
├── Cards/
├── Dialogs/
├── Tables/
├── Forms/
├── Charts/
├── Navigation/
├── Layouts/
├── Icons/
├── Loading/
├── Alerts/
└── EmptyStates/
```

Los componentes globales deberán ser reutilizables.

No contendrán lógica de negocio.

---

# Composables Globales

```
Composables/
├── usePagination.js
├── useFilters.js
├── useDialog.js
├── useNotification.js
├── useCrud.js
├── usePermissions.js
└── useDebounce.js
```

Toda lógica reutilizable deberá vivir aquí.

---

# Stores Globales (Pinia)

```
Stores/
├── auth.store.js
├── theme.store.js
├── notification.store.js
└── permission.store.js
```

Las stores de un módulo vivirán dentro de ese módulo.

---

# Services

Toda comunicación HTTP irá aquí.

Nunca dentro de los componentes.

```
page.service.js
├── create()
├── update()
├── delete()
├── get()
└── getAll()
```

---

# Tipos

```
Types/
├── page.ts
├── user.ts
└── analytics.ts
```

Nunca utilizar objetos `any`.

---

# Constantes

```
Constants/
├── roles.js
├── permissions.js
├── routes.js
└── themes.js
```

---

# Utils

```
Utils/
├── date.js
├── currency.js
├── format.js
├── validators.js
└── string.js
```

---

# Layouts

```
Layouts/
├── AppLayout.vue
├── GuestLayout.vue
└── AuthLayout.vue
```

---

# Organización de Componentes

- Cada componente deberá cumplir una única responsabilidad.
- Un componente mayor de 300 líneas deberá dividirse.
- No se permitirá lógica de negocio dentro de un componente.

---

# Convenciones de Nomenclatura

| Tipo | Convención | Ejemplo |
|------|-----------|---------|
| Componentes | PascalCase | `UserCard.vue` |
| Composables | camelCase con prefijo `use` | `useUsers.js` |
| Stores | camelCase con sufijo `.store` | `user.store.js` |
| Servicios | camelCase con sufijo `.service` | `user.service.js` |
| DTOs | PascalCase | `CreateUserDTO.php` |
| Actions | PascalCase | `CreateUserAction.php` |
| Repositories | PascalCase | `UserRepository.php` |
| Interfaces | PascalCase | `UserRepositoryInterface.php` |

---

# Flujo Frontend

```
Vista
  ↓
Componentes
  ↓
Composable
  ↓
Service
  ↓
Laravel (Inertia)
```

---

# Principios

- Single Responsibility
- Open Closed
- Liskov Substitution
- Interface Segregation
- Dependency Inversion
- KISS
- DRY
- YAGNI
- Convention over Configuration
- Feature First
- Composition API
- No Business Logic in Views
- No HTTP in Components
- No Direct Database Access
- No Duplicated Code

---

# Librerías

## Backend

- Laravel
- Spatie Permission
- Spatie Media Library
- Laravel Pint
- PHPStan
- Pest

## Frontend

- Vue 3
- Inertia
- PrimeVue
- Pinia
- TailwindCSS
- VueUse
- Axios
- Zod
- Vue Sonner
- Floating UI

---

# Objetivo Final

El proyecto deberá poder crecer sin modificar su arquitectura.

Cada módulo será independiente.

Cada módulo podrá evolucionar sin afectar a los demás.

Toda funcionalidad nueva deberá respetar esta estructura.

No se aceptarán implementaciones que rompan esta arquitectura.

---

# Regla del Proyecto

Este documento deberá guardarse en la raíz del repositorio con el nombre:

```
ARCHITECTURE.md
```

Será considerado una **regla oficial del proyecto**. Todo nuevo desarrollo, refactorización o contribución deberá cumplir obligatoriamente con las convenciones y principios definidos en este archivo. Antes de implementar cualquier funcionalidad, se deberá revisar este documento para asegurar que el código mantiene la arquitectura establecida.
