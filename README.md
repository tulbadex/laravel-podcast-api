<p align="center">
  <img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo">
  <h1 align="center">Podcast Platform API</h1>
</p>

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-10.x-FF2D20?style=flat&logo=laravel" alt="Laravel Version">
  <img src="https://img.shields.io/badge/PHP-8.2-777BB4?style=flat&logo=php" alt="PHP Version">
  <img src="https://img.shields.io/badge/MySQL-8.0-4479A1?style=flat&logo=mysql" alt="MySQL Version">
  <img src="https://img.shields.io/badge/Redis-DC382D?style=flat&logo=redis" alt="Redis">
  <img src="https://img.shields.io/badge/License-MIT-blue.svg" alt="License">
</p>

## About Podcast Platform API

A robust backend API for a podcast streaming platform built with Laravel. This implementation features:

- **RESTful endpoints** for podcasts, episodes, and categories
- **JWT Authentication** with Laravel Sanctum
- **Redis caching** for improved performance
- **Rate limiting** for API protection
- **Swagger documentation** for all endpoints
- **Dockerized** development environment
- **Repository pattern** implementation
- **Comprehensive testing** with PHPUnit

## API Features

### Core Functionality
- 🎙️ Podcast management (CRUD operations)
- 🎧 Episode management with audio metadata
- 📂 Category organization
- 🔍 Search and filtering capabilities
- ⭐ Featured content highlighting

### Technical Highlights
- 🏗 Clean architecture with separation of concerns
- 📄 OpenAPI documentation with Swagger
- ⚡ Redis caching layer
- 🔒 Rate-limited endpoints
- 🐳 Docker-compose ready
- ✅ 85%+ test coverage

## Getting Started

### Prerequisites
- Docker and Docker Compose
- PHP 8.2+
- Composer

### Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/tulbadex/laravel-podcast-api.git
   cd laravel-podcast-api
   ```

2. Setup environment:
   ```bash
   cp .env.example .env
   ```

3. Start services:
   ```bash
   docker-compose up -d --build
   ```

4. Install dependencies:
   ```bash
   docker-compose exec app composer install
   ```

5. Generate application key:
   ```bash
   docker-compose exec app php artisan key:generate
   ```

6. Run database migrations:
   ```bash
   docker-compose exec app php artisan migrate --seed
   ```

7. Generate API documentation:
   ```bash
   docker-compose exec app php artisan l5-swagger:generate
   ```

## API Documentation

Access the interactive Swagger documentation at:  
[http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

![Swagger UI Preview](https://i.imgur.com/JcQ8Mdl.png)

## Development

### Running Tests
```bash
docker-compose exec app php artisan test
```

### Monitoring Logs
```bash
docker-compose logs -f app
```

## Deployment

For production deployment:

1. Update `.env` with production values
2. Run:
   ```bash
   docker-compose -f docker-compose.prod.yml up -d --build
   ```

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add some amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## Security

If you discover any security vulnerabilities, please email security@example.com instead of creating an issue.

## License

This project is open-source software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

<p align="center">
  Built with ❤️ using <a href="https://laravel.com" target="_blank">Laravel</a>
</p>