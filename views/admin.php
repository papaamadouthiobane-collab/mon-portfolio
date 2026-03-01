<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin - Messages</title>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; padding: 40px; }
        .container { max-width: 1000px; margin: auto; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h1 { color: #333; border-bottom: 2px solid #007bff; padding-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { text-align: left; padding: 12px; border-bottom: 1px solid #ddd; }
        th { background-color: #007bff; color: white; }
        tr:hover { background-color: #f1f1f1; }
        .email { color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📩 Messages reçus</h1>
        <div style="text-align: right;">
    <a href="index.php?action=admin&logout=1" style="color: red; text-decoration: none; font-weight: bold;">
        ❌ Déconnexion
    </a>
</div>
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($messages as $m): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($m['nom']) ?></strong></td>
                    <td><a href="mailto:<?= $m['email'] ?>" class="email"><?= htmlspecialchars($m['email']) ?></a></td>
                    <td><?= nl2br(htmlspecialchars($m['contenu'])) ?></td>
                    <td><small><?= $m['created_at'] ?? 'Récemment' ?></small></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <br>
        <a href="index.php">⬅ Retour au site</a>
    </div>
</body>
</html>