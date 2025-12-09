# Stripe environment configuration

This app expects Stripe configuration to come from environment variables (no secrets in the repo).

Required variables (see `env.example`):
- STRIPE_MODE (`live` or `test`; defaults to `test` if unset)
- STRIPE_LIVE_PUBLISHABLE_KEY
- STRIPE_LIVE_SECRET_KEY
- STRIPE_LIVE_WEBHOOK_SECRET
- STRIPE_TEST_PUBLISHABLE_KEY
- STRIPE_TEST_SECRET_KEY
- STRIPE_TEST_WEBHOOK_SECRET

Setup locally:
1) Copy `env.example` to `.env`.
2) Fill in the values above.
3) Ensure `.env` stays untracked (already in `.gitignore`).

Deployment:
- Set the same variables in your hosting environment (e.g., server env, Docker secrets, or CI/CD secret manager).
- Choose `STRIPE_MODE` appropriately per environment.

