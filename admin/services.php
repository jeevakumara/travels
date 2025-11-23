<?php
require_once __DIR__ . '/../config.php';

// Security Check: Enforce Admin Session
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ' . $basePath . '/admin/login.php');
    exit;
}

// CSRF token setup for forms (already handled in config/login but good to ensure)
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// Handle form submission (Add / Update service)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        exit('Invalid CSRF token');
    }

    $id = $_POST['id'] ?? null;
    $title = trim($_POST['title'] ?? '');
    $summary = trim($_POST['summary'] ?? '');
    $price = $_POST['price'] ?? null;
    $currency = trim($_POST['currency'] ?? 'INR');
    $duration = trim($_POST['duration'] ?? '');
    $is_active = isset($_POST['is_active']) ? (int)$_POST['is_active'] : 1;

    // Upload directory (adjusted for admin/ folder)
    $uploadDir = __DIR__ . '/../php/uploads/';
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

            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        } else {
            $error = "Title is required.";
        }
    }
}

// Deletion handler (POST only)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
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
            $uploadDir = __DIR__ . '/../php/uploads/';
            @unlink($uploadDir . $row['image']);
        }
    }
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch all services for admin table
$services = $pdo->query("SELECT * FROM services ORDER BY id DESC")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Services</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo $basePath; ?>/assets/css/site.css">
    <style>
        /* Admin Specific Overrides */
        body { background: #f4f6f9; }
        .admin-container { max-width: 1200px; margin: 0 auto; padding: var(--space-6); }
        .admin-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: var(--space-6); }
        .card { background: white; border-radius: var(--radius-xl); padding: var(--space-6); box-shadow: var(--shadow-md); margin-bottom: var(--space-6); }
        .thumb { width: 60px; height: 60px; object-fit: cover; border-radius: var(--radius-md); }
        .table th { background: #f8fafc; color: var(--color-primary); font-weight: 600; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="admin-header">
            <div>
                <h1 class="m-0">Manage Services</h1>
                <a href="index.php" class="text-sm text-muted hover:text-primary">← Back to Dashboard</a>
            </div>
        </div>

        <?php if(isset($error)): ?>
            <div class="flash-message flash-error" style="position: static; transform: none; width: 100%; margin-bottom: var(--space-6);">
                <div class="flash-content">
                    <span class="flash-text"><?php echo htmlspecialchars($error); ?></span>
                </div>
            </div>
        <?php endif; ?>

        <div class="grid-2">
            <!-- Form Section -->
            <div class="card">
                <h3 class="mb-4">Add / Edit Service</h3>
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token']); ?>">
                    <input type="hidden" name="id" id="service_id">
                    
                    <div class="form-field">
                        <label class="form-label">Title</label>
                        <input type="text" name="title" id="service_title" class="form-input" required>
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">Summary</label>
                        <textarea name="summary" id="service_summary" class="form-textarea" rows="3"></textarea>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-label">Price</label>
                            <input type="number" step="0.01" name="price" id="service_price" class="form-input">
                        </div>
                        <div class="form-field">
                            <label class="form-label">Currency</label>
                            <input type="text" name="currency" id="service_currency" value="INR" class="form-input">
                        </div>
                    </div>
                    
                    <div class="form-row">
                        <div class="form-field">
                            <label class="form-label">Duration</label>
                            <input type="text" name="duration" id="service_duration" class="form-input" placeholder="e.g. 3 Days">
                        </div>
                        <div class="form-field">
                            <label class="form-label">Status</label>
                            <select name="is_active" id="service_active" class="form-select">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-field">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" id="service_image" class="form-input" accept="image/*">
                        <span class="form-helper">Max 5MB. JPG, PNG, WEBP allowed.</span>
                    </div>
                    
                    <button class="btn btn-full" type="submit">Save Service</button>
                    <button type="button" class="btn btn-full ghost mt-2" onclick="resetForm()">Clear Form</button>
                </form>
            </div>

            <!-- List Section -->
            <div class="card">
                <h3 class="mb-4">All Services</h3>
                <?php if(!$services): ?>
                    <p class="text-muted">No services added yet.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Title</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($services as $s): ?>
                                    <tr>
                                        <td>
                                            <?php if (!empty($s['image'])): ?>
                                                <img class="thumb" src="<?php echo htmlspecialchars($basePath . '/php/uploads/' . $s['image']); ?>" alt="thumb">
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo htmlspecialchars($s['title']); ?></strong>
                                            <div class="text-xs text-muted"><?php echo htmlspecialchars($s['duration_label'] ?? ''); ?></div>
                                        </td>
                                        <td><?php echo htmlspecialchars($s['currency']).' '.htmlspecialchars((string)$s['price']); ?></td>
                                        <td>
                                            <?php if($s['is_active']): ?>
                                                <span class="badge badge-accent">Active</span>
                                            <?php else: ?>
                                                <span class="badge badge-warning">Inactive</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="flex gap-2">
                                                <button class="btn btn-sm outline" onclick="editService(
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
                                                    <button class="btn btn-sm btn-danger" type="submit">Del</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
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
    function resetForm() {
        document.getElementById('service_id').value = '';
        document.querySelector('form').reset();
    }
    </script>
</body>
</html>
