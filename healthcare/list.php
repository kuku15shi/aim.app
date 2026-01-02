<?php 
include '../includes/db.php'; 
$type = isset($_GET['type']) ? $_GET['type'] : 'government';
$title = strtoupper(str_replace('_', ' ', $type)); // e.g., GOVERNMENT HEALTHCARE

$sql = "SELECT * FROM healthcare_centers WHERE type='$type' ORDER BY name";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .healthcare-body {
            background-color: var(--primary-bg) !important;
            min-height: 100vh;
        }
        .header-title {
            text-align: center;
            font-weight: 900;
            font-size: 22px;
            color: var(--text-dark);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 15px 0;
            position: relative;
            display: inline-block;
        }
        .header-title::after {
            content: '';
            display: block;
            width: 40px;
            height: 3px;
            background: var(--sage-green);
            margin: 8px auto 0;
            border-radius: 2px;
        }
        .list-container {
            padding: 20px;
            display: flex;
            flex-direction: column;
            gap: 18px;
        }
        .hospital-btn {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            padding: 25px 20px;
            border-radius: 24px;
            font-size: 16px;
            font-weight: 800;
            color: var(--text-dark);
            text-align: center;
            text-decoration: none;
            box-shadow: 0 8px 20px var(--shadow-color);
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 90px;
            position: relative;
            overflow: hidden;
        }
        .hospital-btn::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            height: 100%;
            width: 6px;
            background: var(--sage-green);
            opacity: 0.7;
            transition: width 0.3s ease;
        }
        .hospital-btn:hover {
            transform: translateY(-5px) scale(1.02);
            box-shadow: 0 15px 30px rgba(0,0,0,0.1);
        }
        .hospital-btn:hover::before {
            width: 10px;
            opacity: 1;
        }
    </style>
</head>
<body class="healthcare-body">
<div class="app-container" style="background: transparent; box-shadow: none;">
    <div class="d-flex align-items-center p-3">
        <a href="index.php" class="text-dark me-3" style="font-size: 24px;"><i class="fas fa-arrow-left"></i></a>
        <div style="flex:1; margin-right: 15px;">
            <input type="text" class="form-control rounded-pill border-0 shadow-sm" placeholder="Search......" style="padding: 10px 20px;">
        </div>
        <div id="theme-toggle" class="theme-toggle-pill">
            <i class="fas fa-sun" id="theme-icon"></i>
        </div>
    </div>

    <h2 class="header-title"><?php echo $title; ?></h2> <!-- e.g., GOVERNMENT HEALTHCARE -->

    <div class="list-container">
        <?php if ($result->num_rows > 0): ?>
            <?php while($row = $result->fetch_assoc()): ?>
                <a href="view.php?id=<?php echo $row['id']; ?>" class="hospital-btn">
                    <?php echo htmlspecialchars($row['name']); ?>
                </a>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="text-center text-white mt-5">No centers found in this category.</div>
        <?php endif; ?>
    </div>
    
    <!-- Spacer -->
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
