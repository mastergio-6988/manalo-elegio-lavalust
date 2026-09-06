<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <style>
        body { margin: 0; background: #f5f7fb; color: #1f2937; font-family: Arial, sans-serif; }
        main { max-width: 960px; margin: 48px auto; padding: 0 24px; }
        h1 { margin: 0 0 8px; }
        p { margin: 0 0 24px; color: #4b5563; }
        .table-wrap { overflow-x: auto; background: #fff; border-radius: 10px; box-shadow: 0 2px 10px rgba(0, 0, 0, .08); }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 14px 16px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #1d4ed8; color: #fff; }
        tbody tr:last-child td { border-bottom: 0; }
        tbody tr:hover { background: #f8fafc; }
        .empty { text-align: center; color: #6b7280; }
    </style>
</head>
<body>
    <main>
        <h1>User Management</h1>
        <p>Users retrieved from the <code>mydb.users</code> database table.</p>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th>
                        <th>Username</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($users)): ?>
                        <tr><td class="empty" colspan="5">No users found.</td></tr>
                    <?php else: ?>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= htmlspecialchars((string) $user['id'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($user['firstname'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($user['lastname'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') ?></td>
                                <td><?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
