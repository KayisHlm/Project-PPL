# Server

## Email Notification Setup

This service sends automatic approval/rejection emails for seller onboarding. Configure the following environment variables (see `.env.example`):

```
SMTP_HOST=smtp.example.com
SMTP_PORT=587
SMTP_USER=your-smtp-user
SMTP_PASS=your-smtp-password
FROM_EMAIL="Platform Support" <no-reply@example.com>
FRONTEND_URL=http://localhost:5173
```

Then install dependencies and run the server:

```
npm install
npm run dev
```

## Admin Actions

- `POST /api/admin/sellers/:id/approve` – approves a seller, activates the account, and sends the activation email.
- `POST /api/admin/sellers/:id/reject` – rejects a seller. Request body must include `{ "reason": "..." }`; the reason is stored and included in the rejection email.

Emails are sent via Nodemailer; failures are logged but do not block status changes.
