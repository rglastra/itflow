# ITFlow Docker Deployment

This directory contains everything needed to run ITFlow in Docker, including the application code and Docker configuration.

## Quick Start

### Prerequisites
- Docker and Docker Compose installed
- Your own fork of this repository (recommended for MSPs)

### Setup

1. **Create your environment file:**
   ```bash
   cp .env.example .env
   ```

2. **Edit `.env` file with your settings:**
   ```bash
   TZ=America/Edmonton                    # Your timezone
   ITFLOW_DB_PASS=your_secure_password   # Generate with: openssl rand -base64 32
   ROOT_DOMAIN=yourdomain.com            # Your domain
   ```

3. **Start the containers:**
   ```bash
   docker compose up -d
   ```

4. **Complete setup:**
   - Navigate to your domain (or http://localhost:8080 if testing locally)
   - You'll be redirected to setup.php
   - Enter database credentials:
     - Username: `itflow`
     - Password: The value from `ITFLOW_DB_PASS` in your `.env` file
     - Database: `itflow`
     - Server: `itflow-db`

5. **If not using SSL (local testing only):**
   - After setup completes, you'll need to modify config.php
   - The config.php is stored in the persistent volume at `/var/itflow-data/config.php`
   - Set: `$config_https_only = FALSE;`

## Architecture

### Container Structure
- **itflow**: Alpine-based container running Apache + PHP 8.4
- **itflow-db**: MariaDB 11.4 database

### Persistent Data
All critical data is stored in Docker volumes:
- **itflow-data**: Contains `config.php` and `uploads/` directory
- **itflow-db**: MariaDB database files

**⚠️ CRITICAL: config.php Protection**
- `config.php` is NEVER copied into the Docker image
- It's stored in the persistent volume `/var/itflow-data/`
- The entrypoint script creates a symlink from the volume to the app directory
- `.dockerignore` prevents accidental inclusion during image builds
- `.gitignore` prevents accidental commits to version control

### Build Process
When you run `docker compose up`, the build process:
1. Copies application code into the image (excluding ignored files)
2. Configures Apache and PHP
3. Sets up cron jobs for ITFlow maintenance tasks
4. On container start, the entrypoint script:
   - Links `config.php` from persistent storage (if it exists)
   - Links `uploads/` from persistent storage
   - Updates config values from environment variables
   - Starts Apache and cron

## Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `TZ` | `Etc/UTC` | Container timezone |
| `ITFLOW_NAME` | `ITFlow` | Company name in ITFlow |
| `ITFLOW_URL` | `demo.itflow.org` | Your domain (without http/https) |
| `ITFLOW_HTTPS` | `true` | Whether to enforce HTTPS |
| `ITFLOW_PORT` | `8080` | Internal Apache port |
| `ITFLOW_LOG_LEVEL` | `info` | Apache log level (emerg, alert, crit, error, warn, notice, info, debug) |
| `ITFLOW_DB_HOST` | `itflow-db` | Database hostname |
| `ITFLOW_DB_NAME` | `itflow` | Database name |
| `ITFLOW_DB_USER` | `itflow` | Database user |
| `ITFLOW_DB_PASS` | *required* | Database password |

## Deployment Scenarios

### Development (Local)
```yaml
# Use default ports and no reverse proxy
docker compose up -d
# Access at http://localhost:8080
```

### Production (Coolify)
Coolify will:
1. Pull from your GitHub repository
2. Build the Docker image from the repository code
3. Run the containers with your configured environment variables
4. Handle reverse proxy and SSL termination

## Maintenance

### Viewing Logs
```bash
docker compose logs -f itflow        # Application logs
docker compose logs -f itflow-db     # Database logs
```

### Backup Database
```bash
docker exec itflow-db mysqldump -u itflow -p itflow > backup.sql
```

### Updating ITFlow
```bash
git pull origin master     # Update your code
docker compose build       # Rebuild image
docker compose up -d       # Restart containers
```

### Accessing config.php
The config.php file is stored in the persistent volume. To edit it:
```bash
docker exec -it itflow vi /var/itflow-data/config.php
```

## Troubleshooting

### Can't access setup page
- Check if containers are running: `docker compose ps`
- Check logs: `docker compose logs itflow`
- Ensure port 8080 is not already in use

### Database connection failed
- Verify `ITFLOW_DB_PASS` matches in both `.env` and setup form
- Check database container: `docker compose logs itflow-db`
- Ensure database container is healthy: `docker compose ps`

### Lost config.php
**Don't panic!** Your config.php is safe in the persistent volume:
- It's stored at `/var/itflow-data/config.php` in the container
- Backed by the `itflow-data` Docker volume
- Never gets deleted unless you explicitly remove the volume

To verify it exists:
```bash
docker exec itflow ls -la /var/itflow-data/config.php
```

## Cron Jobs

The following cron jobs run automatically:
- `cron.php` - Daily maintenance (1:00 AM)
- `ticket_email_parser.php` - Parse ticket emails (every minute)
- `mail_queue.php` - Send queued emails (every minute)
- `certificate_refresher.php` - Update SSL certificate info (2:00 AM)
- `domain_refresher.php` - Update domain info (3:00 AM)

## Network Configuration

### Default Networks
- **wan**: External-facing network for reverse proxy access
- **itflow-db**: Internal network for database communication

### With Reverse Proxy (Traefik/Nginx/Coolify)
The reverse proxy should:
- Forward headers: `X-Forwarded-Proto`, `X-Forwarded-For`
- Handle SSL termination
- Connect to the `wan` network to reach the `itflow` container

## Security Notes

1. **Never commit `.env` file** - it contains sensitive passwords
2. **Use strong database passwords** - generate with `openssl rand -base64 32`
3. **config.php is protected** - multiple layers prevent accidental loss:
   - Persistent volume storage
   - .dockerignore exclusion
   - .gitignore exclusion
4. **Keep ITFlow updated** - regularly pull updates from upstream
5. **Regular backups** - backup both database and `itflow-data` volume

## Migration from Separate Repositories

If you're migrating from the old setup with separate `itflow` and `itflow-docker` repositories:

1. This repository now contains everything in one place
2. Update your Coolify deployment to point to this single repository
3. The build context is now the root of this repository
4. Your existing `itflow-data` volume will be preserved during the transition
