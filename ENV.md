# .env File Cleanup Guide

## ✅ KEEP These Settings

### Essential Application Settings
```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:v84OUWtADs/ZO85FSIXgcW67sU2Uk78WedrWrhgvwYo=
APP_DEBUG=true
APP_URL=http://api.talkproposals.test
```

### Database (Required)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=talk_proposals
DB_USERNAME=root
DB_PASSWORD=
```

### Sanctum SPA Authentication (Required)
```env
SANCTUM_STATEFUL_DOMAINS=talkproposals.test,api.talkproposals.test
```

### Localization (Useful)
```env
APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US
```

### Maintenance Mode
```env
APP_MAINTENANCE_DRIVER=file
```

### Security
```env
BCRYPT_ROUNDS=12
```

### Logging (Required)
```env
LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug
```

### Session (Required for Sanctum SPA)
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.talkproposals.test
```

### Broadcasting (USED - Real-time events)
```env
BROADCAST_CONNECTION=pusher
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=2084029
PUSHER_APP_KEY=7974732e8672fc34a3fe
PUSHER_APP_SECRET=f60945f5436907a2ce10
PUSHER_APP_CLUSTER=mt1
```

### Queue (Used for background jobs)
```env
QUEUE_CONNECTION=database
```

### Cache (Used)
```env
CACHE_STORE=database
```

### Filesystem (Used for proposal file uploads)
```env
FILESYSTEM_DISK=local
```

### Search - Algolia/Scout (USED - Full-text search)
```env
SCOUT_DRIVER=algolia
ALGOLIA_APP_ID=your_app_id
ALGOLIA_SECRET=your_admin_api_key
```
**Note:** Update `ALGOLIA_APP_ID` and `ALGOLIA_SECRET` with your actual Algolia credentials if using search.

### Mail (Simplified - using log driver)
```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 📝 Cleaned .env Template

Here's your cleaned `.env` file:

```env
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:v84OUWtADs/ZO85FSIXgcW67sU2Uk78WedrWrhgvwYo=
APP_DEBUG=true
APP_URL=http://api.talkproposals.test

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=talk_proposals
DB_USERNAME=root
DB_PASSWORD=

SANCTUM_STATEFUL_DOMAINS=talkproposals.test,api.talkproposals.test

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=.talkproposals.test

BROADCAST_CONNECTION=pusher
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=2084029
PUSHER_APP_KEY=7974732e8672fc34a3fe
PUSHER_APP_SECRET=f60945f5436907a2ce10
PUSHER_APP_CLUSTER=mt1

QUEUE_CONNECTION=database

CACHE_STORE=database

FILESYSTEM_DISK=local

SCOUT_DRIVER=algolia
ALGOLIA_APP_ID=your_app_id
ALGOLIA_SECRET=your_admin_api_key

MAIL_MAILER=log
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

---

## 🔍 Why Keep/Remove?

### ✅ Kept Because:
- **Pusher/Broadcasting**: Used for real-time events (proposal status changes, reviews)
- **Algolia/Scout**: Used for full-text search across proposals
- **Session**: Required for Sanctum SPA authentication
- **Filesystem**: Used for proposal PDF file uploads
- **Queue**: Used for background job processing

---

## 🚀 Next Steps

1. **Update Algolia credentials** if you're using search:
   ```env
   ALGOLIA_APP_ID=your_actual_app_id
   ALGOLIA_SECRET=your_actual_secret
   ```
