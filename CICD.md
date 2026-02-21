# CI/CD Setup

This repository now includes a GitHub Actions pipeline at `.github/workflows/ci-cd.yml`.

## CI (Continuous Integration)
Runs on:
- Every pull request
- Pushes to `main` or `master`

CI job does the following:
1. Checks out code
2. Sets up PHP `8.1` and `8.2`
3. Installs Composer dependencies
4. Prepares Laravel (`.env` + `key:generate`)
5. Runs tests with `php artisan test`

## CD (Continuous Deployment)
Deployment runs **only** when all of these are true:
- Event is a push to `main` or `master`
- Required deployment secrets are configured

### Required GitHub repository secrets
- `DEPLOY_HOST`
- `DEPLOY_USER`
- `DEPLOY_SSH_KEY`
- `DEPLOY_PATH`

### Optional secret
- `DEPLOY_PORT` (defaults to `22`)

## Deployment steps executed remotely
1. `git fetch --all`
2. `git reset --hard origin/<branch>`
3. `composer install --no-dev --prefer-dist --optimize-autoloader --no-interaction`
4. `php artisan migrate --force`
5. `php artisan optimize:clear`
6. `php artisan optimize`

If deployment secrets are not set, CI will still run and pass/fail normally, but deploy job will be skipped.
