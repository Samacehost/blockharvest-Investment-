# BlockHarvest Next-Gen Investment Platform

## Quick Access Credentials

- **Super-Admin Portal**: `admin@blockharvest.top` / `password123`
- **Investor Dashboard**: `investor@blockharvest.top` / `password123`

---

## Automated Deployment Script (`deploy.sh`)

To deploy on your server (VPS, Dedicated, cPanel, or Shared Hosting), run:

```bash
# Optional: if your web root is in public_html
cd public_html

# Make deploy script executable & run
chmod +x deploy.sh
./deploy.sh
```

The script automatically detects environment steps, runs Composer, Artisan migrations, seeders, route/config caches, storage symlinks, NPM builds, and permission fixes. If any step is unavailable in your hosting environment, it gracefully skips it and proceeds to the next step!
