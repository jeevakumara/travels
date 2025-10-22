<?php
require __DIR__ . '/php/config.php';
session_start();

$secret = $_GET['key'] ?? '';
if ($secret !== 'local-admin') exit('Access denied'); // keep your simple gate [improve later]

// CSRF token setup for forms
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission (Add / Update service)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        exit('Invalid CSRF token');
    }

    $id = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $price = $_POST['price'] ?? null;
    $currency = trim($_POST['currency'] ?? 'INR');
    $duration = trim($_POST['duration'] ?? '');
    $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;

    // Upload directory
    $uploadDir = __DIR__ . '/php/uploads/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

    $imageName = null;

    if (isset($_FILES['image']) && is_uploaded_file($_FILES['image']['tmp_name'])) {
        $tmpName = $_FILES['image']['tmp_name'];
        $origName = $_FILES['image']['name'] ?? '';
        $size = $_FILES['image']['size'] ?? 0;

        // 5 MB size limit
        if ($size > 5 * 1024 * 1024) {
            $error = "Image too large (max 5 MB).";
        } else {
            $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            $allowedExt = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $allowedMime = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $tmpName);
            finfo_close($finfo);

            // exif_imagetype is a second check for images
            $imgType = @exif_imagetype($tmpName);

            if (!in_array($ext, $allowedExt, true) || !in_array($mime, $allowedMime, true) || $imgType === false) {
                $error = "Invalid image file type.";
            } else {
                // Generate safe filename
                $imageName = 'srv_' . bin2hex(random_bytes(8)) . '.' . $ext;

                // Move securely
                if (!move_uploaded_file($tmpName, $uploadDir . $imageName)) {
                    $error = "Failed to save uploaded image.";
                }
            }
        }
    }

    if (empty($error)) {
        if ($title !== '') {
            if ($id) {
                // Update with optional image
                $sql = "UPDATE services 
                        SET title=:title, summary=:summary, price=:price, currency=:currency, duration_label=:duration, is_active=:is_active";
                if ($imageName) $sql .= ", image=:image";
                $sql .= " WHERE id=:id";

                $stmt = $pdo->prepare($sql);
                $params = [
                    ':title' => $title,
                    ':summary' => $summary,
                    ':price' => $price,
                    ':currency' => $currency,
                    ':duration' => $duration,
                    ':is_active' => $is_active,
                    ':id' => $id
                ];
                if ($imageName) $params[':image'] = $imageName;
                $stmt->execute($params);
            } else {
                // Insert (image may be null if not uploaded)
                $stmt = $pdo->prepare("INSERT INTO services (title, summary, price, currency, duration_label, is_active, image) 
                                       VALUES (:title, :summary, :price, :currency, :duration, :is_active, :image)");
                $stmt->execute([
                    ':title' => $title,
                    ':summary' => $summary,
                    ':price' => $price,
                    ':currency' => $currency,
                    ':duration' => $duration,
                    ':is_active' => $is_active,
                    ':image' => $imageName
                ]);
            }

            header('Location: ' . $_SERVER['PHP_SELF'] . '?key=local-admin');
            exit;
        } else {
            $error = "Title is required.";
        }
    }
}

// Deletion handler (POST only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        exit('Invalid CSRF token');
    }
    $delId = (int)$_POST['delete_id'];
    // Optional: get image to unlink
    $imgStmt = $pdo->prepare("SELECT image FROM services WHERE id = :id");
    $imgStmt->execute([':id' => $delId]);
    $row = $imgStmt->fetch();
    if ($row) {
        $pdo->prepare("DELETE FROM services WHERE id = :id")->execute([':id' => $delId]);
        if (!empty($row['image'])) {
            @unlink($uploadDir . $row['image']);
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF'] . '?key=local-admin');
    exit;
}

// Fetch all services for admin table
$services = $pdo->query("SELECT * FROM services ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin - Services</title>
    <style>
        body{font-family:sans-serif;}
        .container{max-width:900px;margin:auto;padding:20px;}
        .card{border:1px solid #ddd;padding:15px;margin-bottom:15px;border-radius:8px;}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:8px;border-bottom:1px solid #ccc;text-align:left;}
        input,textarea,select{width:100%;padding:8px;margin:4px 0;}
        .btn{padding:8px 12px;margin-top:6px;display:inline-block;background:#007bff;color:#fff;text-decoration:none;border:none;border-radius:4px;cursor:pointer;}
        .btn-danger{background:#dc3545;}
        .thumb{width:80px;height:60px;object-fit:cover;border-radius:4px;border:1px solid #ccc;}
    </style>
</head>
<body>
<div class="container">
    <h1>Admin - Manage Services</h1>

    <?php if(isset($error)) echo "<p style='color:red;'>".htmlspecialchars($error)."</p>"; ?>

    <div class="card">
        <h3>Add / Edit Service</h3>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
            <input type="hidden" name="id" id="service_id">
            <label>Title</label>
            <input type="text" name="title" id="service_title" required>
            <label>Summary</label>
            <textarea name="summary" id="service_summary"></textarea>
            <label>Price</label>
            <input type="number" step="0.01" name="price" id="service_price">
            <label>Currency</label>
            <input type="text" name="currency" id="service_currency" value="INR">
            <label>Duration</label>
            <input type="text" name="duration" id="service_duration">
            <label>Image</label>
            <input type="file" name="image" id="service_image" accept="image/*">
            <label>Status</label>
            <select name="is_active" id="service_active">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
            <button class="btn" type="submit">Save Service</button>
        </form>
    </div>

    <div class="card">
        <h3>All Services</h3>
        <?php if(!$services): ?>
            <p>No services added yet.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th><th>Image</th><th>Title</th><th>Summary</th><th>Price</th><th>Duration</th><th>Status</th><th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($services as $s): ?>
                    <tr>
                        <td><?php echo (int)$s['id']; ?></td>
                        <td>
                            <?php if (!empty($s['image'])): ?>
                                <img class="thumb" src="<?php echo htmlspecialchars($basePath . '/php/uploads/' . $s['image']); ?>" alt="thumb">
                            <?php else: ?>
                                —
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($s['title']); ?></td>
                        <td><?php echo htmlspecialchars($s['summary']); ?></td>
                        <td><?php echo htmlspecialchars($s['currency']).' '.htmlspecialchars((string)$s['price']); ?></td>
                        <td><?php echo htmlspecialchars($s['duration_label']); ?></td>
                        <td><?php echo $s['is_active'] ? 'Active' : 'Inactive'; ?></td>
                        <td>
                            <button class="btn" onclick="editService(
                                <?php echo (int)$s['id']; ?>,
                                '<?php echo addslashes($s['title']); ?>',
                                '<?php echo addslashes($s['summary']); ?>',
                                '<?php echo addslashes((string)$s['price']); ?>',
                                '<?php echo addslashes($s['currency']); ?>',
                                '<?php echo addslashes($s['duration_label']); ?>',
                                '<?php echo addslashes((string)$s['is_active']); ?>'
                            )">Edit</button>

                            <form method="POST" style="display:inline" onsubmit="return confirm('Delete this service?');">
                                <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                                <input type="hidden" name="delete_id" value="<?php echo (int)$s['id']; ?>">
                                <button class="btn btn-danger" type="submit">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<script>
function editService(id,title,summary,price,currency,duration,is_active){
    document.getElementById('service_id').value = id;
    document.getElementById('service_title').value = title;
    document.getElementById('service_summary').value = summary;
    document.getElementById('service_price').value = price;
    document.getElementById('service_currency').value = currency;
    document.getElementById('service_duration').value = duration;
    document.getElementById('service_active').value = is_active;
    window.scrollTo(0,0);
}
</script>
</body>
</html>
