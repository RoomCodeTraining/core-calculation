<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Token API généré</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background-color: #f8f9fa; padding: 30px; border-radius: 8px;">
        <h1 style="color: #2c3e50; margin-bottom: 20px;">Votre token API a été généré</h1>

        <p>Bonjour {{ $user->name }},</p>

        <p>Votre nouveau token API a été généré avec succès. Vous pouvez maintenant l'utiliser pour accéder à l'API.</p>

        <div style="background-color: #ffffff; border: 2px solid #e0e0e0; border-radius: 4px; padding: 15px; margin: 20px 0;">
            <p style="margin: 0; font-weight: bold; color: #2c3e50; margin-bottom: 10px;">Votre token API :</p>
            <code style="display: block; background-color: #f4f4f4; padding: 10px; border-radius: 4px; word-break: break-all; font-size: 14px; color: #d63384;">{{ $apiToken }}</code>
        </div>

        <p style="color: #dc3545; font-weight: bold; margin-top: 20px;">⚠️ Important :</p>
        <ul style="color: #666;">
            <li>Conservez ce token en sécurité et ne le partagez pas</li>
            <li>Ce token vous permet d'accéder à l'API</li>
            <li>Si vous perdez ce token, vous pouvez en générer un nouveau depuis votre interface d'administration</li>
        </ul>

        <p style="margin-top: 30px;">Cordialement,<br>L'équipe</p>
    </div>
</body>
</html>


