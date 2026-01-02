<?php 
include '../includes/db.php'; 
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$sql = "SELECT * FROM healthcare_centers WHERE id=$id";
$res = $conn->query($sql);
$center = $res->fetch_assoc();

if (!$center) { die("Hospital not found"); }

// Fetch doctors grouped by department
$doc_sql = "SELECT * FROM doctors WHERE center_id=$id ORDER BY department, name";
$doc_res = $conn->query($doc_sql);

$doctors_by_dept = [];
while($doc = $doc_res->fetch_assoc()) {
    $dept = $doc['department'] ?: 'General';
    $doctors_by_dept[$dept][] = $doc;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($center['name']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .healthcare-body {
            background-color: var(--primary-bg) !important;
            min-height: 100vh;
        }
        .hospital-header {
            text-align: center;
            padding: 25px 20px;
        }
        .hospital-name {
            font-weight: 800;
            font-size: 26px;
            text-transform: uppercase;
            line-height: 1.2;
            background: linear-gradient(135deg, var(--text-dark) 0%, var(--text-muted) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 15px;
        }
        .info-card {
            background: var(--card-bg);
            border-radius: 24px;
            padding: 20px;
            margin: 0 20px 25px;
            text-align: center;
            box-shadow: 0 10px 30px var(--shadow-color);
            border: 1px solid var(--border-color);
        }
        .dept-title {
            font-weight: 800;
            font-size: 14px;
            text-transform: uppercase;
            color: var(--text-muted);
            letter-spacing: 1.5px;
            margin: 25px 0 12px;
            padding-left: 25px;
            position: relative;
        }
        .dept-title::before {
            content: '';
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            width: 4px;
            height: 4px;
            background: var(--sage-green);
            border-radius: 50%;
        }
        .doc-card {
            background: var(--card-bg);
            padding: 16px 20px;
            margin: 0 20px 10px;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.02);
            border: 1px solid var(--border-color);
            transition: transform 0.2s ease;
        }
        .doc-card:hover {
            transform: scale(1.01);
            background: var(--glass-bg);
        }
        .doc-name {
            font-weight: 700;
            font-size: 15px;
            color: var(--text-dark);
            margin-bottom: 2px;
        }
        .doc-details {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
        }
        /* Remove specific dark mode overrides as we now use vars */
    </style>
</head>
<body class="healthcare-body">
<div class="app-container" style="background: transparent;">
    
    <div class="d-flex align-items-center justify-content-between p-3">
        <a href="javascript:history.back()" class="text-dark me-3" style="font-size: 24px;"><i class="fas fa-arrow-left"></i></a>
        <div id="theme-toggle" class="theme-toggle-pill">
            <i class="fas fa-sun" id="theme-icon"></i>
        </div>
    </div>
    
    <div class="hospital-header">
        <h1 class="hospital-name"><?php echo htmlspecialchars($center['name']); ?></h1>
    </div>

    <div class="info-card">
        <p><strong><?php echo nl2br(htmlspecialchars($center['address'])); ?></strong></p>
        <?php if($center['phone']): ?>
            <p class="mb-0">Tel: <a href="tel:<?php echo $center['phone']; ?>"><?php echo $center['phone']; ?></a></p>
        <?php endif; ?>
        <?php if($center['description']): ?>
            <div class="alert alert-warning mt-2" style="font-size: 12px; font-weight: bold;">
                <?php echo nl2br(htmlspecialchars($center['description'])); ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Doctors List -->
    <?php foreach($doctors_by_dept as $dept => $docs): ?>
        <div class="dept-title"><?php echo htmlspecialchars($dept); ?></div>
        <?php foreach($docs as $doc): ?>
            <div class="doc-card">
                <div class="doc-name"><?php echo htmlspecialchars($doc['name']); ?></div>
                <div class="doc-details">
                    <?php if($doc['qualification']) echo htmlspecialchars($doc['qualification']) . "<br>"; ?>
                    <?php if($doc['designation']) echo htmlspecialchars($doc['designation']); ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endforeach; ?>
    
    <div style="height: 100px;"></div>



<!-- Bottom Navigation -->
<div class="bottom-nav">
    <a href="../index.php" class="nav-item">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="../updates/" class="nav-item">
        <i class="fas fa-bullhorn"></i>
        <span>Updates</span>
    </a>

    <a href="../news.php" class="nav-item">
        <i class="fas fa-newspaper"></i>
        <span>News</span>
    </a>
    <a href="#" class="nav-item">
        <i class="fas fa-shopping-bag"></i>
        <span>Shopping</span>
    </a>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="../assets/js/script.js"></script>
</body>
</html>
