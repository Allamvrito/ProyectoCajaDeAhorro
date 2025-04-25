
# 🛠️ Guía para Correr el Proyecto Laravel Clonado desde GitHub

---

## ✅ Requisitos Previos

```bash
# Instalar fnm (Node Version Manager)
winget install Schniz.fnm

# Reinicia la terminal, luego instala Node.js
fnm install 22

# Verificar versiones
node -v
npm -v

# Instalar Laravel (requiere PHP ya instalado)
Set-ExecutionPolicy Bypass -Scope Process -Force; [System.Net.ServicePointManager]::SecurityProtocol = [System.Net.ServicePointManager]::SecurityProtocol -bor 3072; iex ((New-Object System.Net.WebClient).DownloadString('https://php.new/install/windows/8.4'))

# Instalar el instalador de Laravel (opcional)
composer global require laravel/installer
```

---

## 🧬 Pasos Después de Clonar un Proyecto Laravel

```bash
# Clonar el repositorio
git clone https://github.com/Allamvrito/ProyectoCajaDeAhorro.git
cd ProyectoCajaDeAhorro
```

### 1. Instalar dependencias PHP

```bash
composer install
```

### 2. Instalar dependencias JavaScript

```bash
npm install
npm run build   # para producción
# o
npm run dev     # para desarrollo
```

### 3. Copiar y Configurar `.env`

```bash
copy .env.example .env
```


### 4. Generar Clave de la App

```bash
php artisan key:generate
```

### 5. Ejecutar Migraciones y Seeders

```bash
php artisan migrate
php artisan db:seed
```
---

## 🚀 Correr el Servidor

```bash
php artisan serve
```

Abre en el navegador:

- `http://127.0.0.1:8000/admin` (ya que se usa Filament)

**Credenciales de acceso:**
- **Correo:** admin@example.com  
- **Contraseña:** admin123

---

## 🧹 Limpieza de Cachés (si algo falla)

```bash
php artisan config:clear
php artisan view:clear
php artisan cache:clear
```

---

## 📌 Comandos Útiles Adicionales

```bash
# Crear nuevo proyecto Laravel
composer create-project --prefer-dist laravel/laravel Prueba

# Crear modelo con migración
php artisan make:model NombreModelo -m

# Crear seeder
php artisan make:seeder NombreSeeder

# Ejecutar migraciones + seeders
php artisan migrate:fresh --seed

# Crear recurso CRUD con Filament
php artisan make:filament-resource NombreModelo

# Crear Usuario para Filament
php artisan make:filament-user

```
