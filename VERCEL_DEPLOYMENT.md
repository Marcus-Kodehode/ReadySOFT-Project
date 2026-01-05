# Vercel Deployment Guide for ReadySOFT Laravel Project

## Prerequisites
- Vercel account
- GitHub repository connected to Vercel

## Environment Variables to Set in Vercel Dashboard

Go to your Vercel project settings → Environment Variables and add:

```
APP_KEY=base64:YOUR_GENERATED_KEY_HERE
APP_URL=https://your-project.vercel.app
DB_CONNECTION=mysql
DB_HOST=your-database-host
DB_PORT=3306
DB_DATABASE=your-database-name
DB_USERNAME=your-database-user
DB_PASSWORD=your-database-password
```

### Generate APP_KEY
Run locally: `php artisan key:generate --show`

## Important Notes

1. **Database**: Vercel serverless functions are stateless. You need an external database:
   - PlanetScale (MySQL)
   - Supabase (PostgreSQL)
   - AWS RDS
   - Railway

2. **File Storage**: Use cloud storage for uploads:
   - AWS S3
   - Cloudinary
   - Vercel Blob

3. **Sessions**: Use `cookie` or `database` driver (not `file`)

4. **Cache**: Use `array` or external cache (Redis via Upstash)

5. **Queue**: Use `sync` or external queue service

## Deployment Steps

1. Push code to GitHub
2. Connect repository to Vercel
3. Set environment variables in Vercel dashboard
4. Deploy!

## Troubleshooting

- If build fails, check Vercel build logs
- Ensure all environment variables are set
- Check that `api/index.php` exists
- Verify `public/build` directory is created during build
