# Authenticating requests

To authenticate requests, include an **`Authorization`** header with the value **`"Bearer {YOUR_AUTH_TOKEN}"`**.

All authenticated endpoints are marked with a `requires authentication` badge in the documentation below.

Pour obtenir un token, utilisez l'endpoint <code>POST /api/auth/login</code> avec vos identifiants. Le token sera retourné dans la réponse et devra être utilisé dans l'en-tête <code>Authorization: Bearer {token}</code> pour toutes les requêtes authentifiées.
