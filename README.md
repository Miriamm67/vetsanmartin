# 🐾 Sistema Web de Gestión de Historiales Clínicos — Veterinaria "San Martín"

Proyecto de Grado — Instituto Tecnológico "Puerto de Mejillones"
Postulante: Luz Miriam Calle Pizza · Tutor técnico: Cat. Hernando Sanabria

## Tecnologías
Laravel 11 · PHP 8.3 · MySQL 8 · Bootstrap 5 · DomPDF · Bootstrap responsive

## Instalación
1. `composer install`
2. Copia `.env.example` a `.env` y configura tu base de datos
3. `php artisan key:generate`
4. Crea la BD `vet_sanmartin` y ejecuta `php artisan migrate --seed`
5. `php artisan serve` → http://127.0.0.1:8000

## Credenciales de prueba
|           Rol             | Usuario | Contraseña |
|---------------------------|---------|------------|
| Administrador/Veterinario | admin   | password   |

## Módulos implementados
- Autenticación con bcrypt y RBAC (2 roles)
- CRUD propietarios y mascotas (eliminación lógica)
- Consultas con formato de historia clínica (anamnesis, examen, métodos complementarios)
- Vacunas con alertas a 30 días
- Recetas e historiales en PDF (DomPDF)
- Portal del cliente (solo lectura)
- Bitácora de auditoría automática