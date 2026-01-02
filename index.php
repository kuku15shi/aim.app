<?php
include 'includes/db.php';

// Fetch slider images
$sql = "SELECT * FROM slider_images ORDER BY uploaded_at DESC";
$result = $conn->query($sql);

// Fetch Notifications
$notif_sql = "SELECT * FROM notifications ORDER BY created_at DESC LIMIT 5";
$notif_result = $conn->query($notif_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Services</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="assets/css/style.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
</head>
<body>

<div class="app-container">
<!-- Sticky Top Area -->
<div class="sticky-top-area sticky-top">
    <!-- Top Header (Old System Restored) -->
    <div class="top-header">
        <div class="logo-section">
            <div class="logo-icon">
                <i class="fas fa-leaf"></i>
            </div>
            <div class="logo-text">
                <span class="main-text">MALAPPURAM</span><br>
                <span class="sub-text">CLICK ALL</span>
            </div>
        </div>
        <div class="header-icons align-items-center">
            <div id="theme-toggle" class="theme-toggle-pill me-2">
                <i class="fas fa-sun toggle-icon sun"></i>
                <i class="fas fa-moon toggle-icon moon"></i>
                <div class="toggle-ball"></div>
            </div>
            <i class="far fa-bell" data-bs-toggle="modal" data-bs-target="#notificationModal" style="cursor: pointer;"></i>
            <i class="fas fa-bars"></i>
        </div>
    </div>

    <!-- Search Bar Wrapper -->
    <div class="header-container">
        <!-- <div class="search-bar-wrapper">
            <i class="fas fa-search search-icon"></i>
            <input type="text" class="top-search-input" placeholder="Search......">
        </div> -->
    </div>
    <!-- Row 1 Categories -->
        <div class="categories-grid ">
            <a href="healthcare.php" class="category-item">
                <div class="icon-box"><i class="fas fa-hospital"></i></div>
                <span class="category-label">HEALTHCARE</span>
            </a>
            <div class="category-item">
                <div class="icon-box"><i class="fas fa-taxi"></i></div>
                <span class="category-label">TRANSPORTATION</span>
            </div>
            <div class="category-item">
                <div class="icon-box"><i class="fas fa-landmark"></i></div>
                <span class="category-label">GOVERNMENT SVC</span>
            </div>
        </div>

        <!-- Carousel -->
        <div class="carousel-container fixed-carousel">
            <div id="mainCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3000">
                <div class="carousel-inner">
                    <?php
                    if ($result->num_rows > 0) {
                        $first = true;
                        while($row = $result->fetch_assoc()) {
                            $active = $first ? 'active' : '';
                            echo '<div class="carousel-item ' . $active . '">';
                            if (!empty($row['link'])) {
                                echo '<a href="' . $row['link'] . '" onclick="return confirm(\'Do you want to visit this link?\')">';
                                echo '<img src="' . $row['image_path'] . '" class="d-block w-100" alt="Slide">';
                                echo '</a>';
                            } else {
                                echo '<img src="' . $row['image_path'] . '" class="d-block w-100" alt="Slide">';
                            }
                            echo '</div>';
                            $first = false;
                        }
                    } else {
                        echo '<div class="carousel-item active">';
                        echo '<img src="https://placehold.co/600x300/e0e0e0/000000?text=No+Slides+Available" class="d-block w-100" alt="Default Slide">';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>


</div>

<div class="content-wrapper">
<!-- Bottom Categories (3x3 Grid as per image) -->
<div class="categories-grid">
    <div class="category-item">
        <div class="icon-box"><i class="fas fa-apple-alt"></i></div>
        <span class="category-label">FOOD&WATER</span>
    </div>
    <div class="category-item">
        <div class="icon-box"><i class="fas fa-graduation-cap"></i></div>
        <span class="category-label">EDUCATION</span>
    </div>
    <div class="category-item">
        <div class="icon-box"><i class="fas fa-wallet"></i></div>
        <span class="category-label">FINANCIAL SVC</span>
    </div>
    
    <div class="category-item">
        <div class="icon-box"><i class="fas fa-home"></i></div>
        <span class="category-label">HOUSING</span>
    </div>
    <div class="category-item">
        <div class="icon-box"><i class="fas fa-trash"></i></div>
        <span class="category-label">SANITATION</span>
    </div>
    <div class="category-item">
        <div class="icon-box"><i class="fas fa-users"></i></div>
        <span class="category-label">SOCIAL WELFARE</span>
    </div>

    <div class="category-item">
        <div class="icon-box"><i class="fas fa-comments"></i></div>
        <span class="category-label">COMMUNICATION</span>
    </div>
    <div class="category-item">
        <div class="icon-box"><i class="fas fa-user-shield"></i></div>
        <span class="category-label">SECURITY&LAW</span>
    </div>
    <div class="category-item">
        <div class="icon-box"><i class="fas fa-briefcase"></i></div>
        <span class="category-label">EMPLOYMENT</span>
    </div>
</div>

</div> <!-- End content-wrapper -->

<!-- Spacer for fixed footer -->
<div style="height: 120px;"></div>

</div> <!-- End app-container -->

<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-bell"></i> Notifications</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <?php if ($notif_result->num_rows > 0): ?>
            <div class="list-group">
                <?php while($note = $notif_result->fetch_assoc()): ?>
                    <div class="list-group-item" id="notif-<?php echo $note['id']; ?>">
                        <div class="d-flex w-100 justify-content-between">
                            <h6 class="mb-1 text-danger"><?php echo htmlspecialchars($note['title']); ?></h6>
                            <button type="button" class="btn-close btn-sm" aria-label="Close" onclick="dismissNote(<?php echo $note['id']; ?>)"></button>
                        </div>
                        <small class="text-muted" style="font-size: 10px;"><?php echo date('d M, h:i A', strtotime($note['created_at'])); ?></small>
                        <p class="mb-1" style="font-size: 14px;"><?php echo htmlspecialchars($note['message']); ?></p>
                    </div>
                <?php endwhile; ?>
            </div>
          <?php else: ?>
            <p class="text-center text-muted my-3">No new notifications.</p>
          <?php endif; ?>
      </div>
    </div>
  </div>
</div>

<!-- Emergency Modal -->
<div class="modal fade" id="emergencyModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title"><i class="fas fa-exclamation-triangle"></i> Emergency Contacts</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body p-0">
        <div class="list-group list-group-flush">
            <!-- Police -->
            <a href="tel:100" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <div class="bg-light rounded-circle p-2 me-3 text-primary">
                    <i class="fas fa-user-shield fa-lg"></i>
                </div>
                <div>
                    <h6 class="mb-0">Police Control Room</h6>
                    <small class="text-muted">Tap to Call: 100</small>
                </div>
            </a>
            
            <!-- Ambulance -->
            <a href="tel:108" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <div class="bg-light rounded-circle p-2 me-3 text-danger">
                    <i class="fas fa-ambulance fa-lg"></i>
                </div>
                <div>
                    <h6 class="mb-0">Ambulance Service</h6>
                    <small class="text-muted">Tap to Call: 108</small>
                </div>
            </a>

            <!-- Fire Force -->
            <a href="tel:101" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <div class="bg-light rounded-circle p-2 me-3 text-warning">
                    <i class="fas fa-fire-extinguisher fa-lg"></i>
                </div>
                <div>
                    <h6 class="mb-0">Fire Force</h6>
                    <small class="text-muted">Tap to Call: 101</small>
                </div>
            </a>

            <!-- Women Helpline -->
            <a href="tel:1091" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <div class="bg-light rounded-circle p-2 me-3 text-danger">
                    <i class="fas fa-female fa-lg"></i>
                </div>
                <div>
                    <h6 class="mb-0">Women Helpline</h6>
                    <small class="text-muted">Tap to Call: 1091</small>
                </div>
            </a>

            <!-- Child Helpline -->
            <a href="tel:1098" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <div class="bg-light rounded-circle p-2 me-3 text-success">
                    <i class="fas fa-child fa-lg"></i>
                </div>
                <div>
                    <h6 class="mb-0">Child Helpline</h6>
                    <small class="text-muted">Tap to Call: 1098</small>
                </div>
            </a>

            <!-- Cyber Cell -->
            <a href="tel:1930" class="list-group-item list-group-item-action d-flex align-items-center p-3">
                <div class="bg-light rounded-circle p-2 me-3 text-info">
                    <i class="fas fa-laptop-code fa-lg"></i>
                </div>
                <div>
                    <h6 class="mb-0">Cyber Crime Helpline</h6>
                    <small class="text-muted">Tap to Call: 1930</small>
                </div>
            </a>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Bottom Navigation -->
<div class="bottom-nav">
    <a href="#" class="nav-item active">
        <i class="fas fa-home"></i>
        <span>Home</span>
    </a>
    <a href="#" class="nav-item">
        <i class="fas fa-bullhorn"></i>
        <span>Updates</span>
    </a>
    <a href="#" class="nav-item emergency-item" data-bs-toggle="modal" data-bs-target="#emergencyModal">
        <div class="emergency-circle">
            <i class="fas fa-bell"></i>
        </div>
        <span>Emergency</span>
    </a>
    <a href="news.php" class="nav-item">
        <i class="fas fa-newspaper"></i>
        <span>News</span>
    </a>
    <a href="#" class="nav-item">
        <i class="fas fa-shopping-bag"></i>
        <span>Shopping</span>
    </a>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Custom JS -->
<script src="assets/js/script.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var myCarousel = document.querySelector('#mainCarousel')
        var carousel = new bootstrap.Carousel(myCarousel, {
            interval: 3000,
            ride: 'carousel'
        })
    });

    // Notification Logic
    function dismissNote(id) {
        // Hide element
        var el = document.getElementById('notif-' + id);
        if(el) el.style.display = 'none';
        
        // Save to local storage
        var dismissed = JSON.parse(localStorage.getItem('dismissedNotes') || '[]');
        if(!dismissed.includes(id)) {
            dismissed.push(id);
            localStorage.setItem('dismissedNotes', JSON.stringify(dismissed));
        }
        
        checkEmpty();
    }

    function checkEmpty() {
        var list = document.querySelector('.list-group');
        if(list) {
            var visibleItems = Array.from(list.children).filter(function(child) {
                return child.style.display !== 'none';
            });
            
            if(visibleItems.length === 0) {
                 list.innerHTML = '<p class="text-center text-muted my-3">No new notifications.</p>';
            }
        }
    }

    // Hide already dismissed notes on load
    document.addEventListener('DOMContentLoaded', function() {
        var dismissed = JSON.parse(localStorage.getItem('dismissedNotes') || '[]');
        dismissed.forEach(function(id) {
            var el = document.getElementById('notif-' + id);
            if(el) el.style.display = 'none';
        });
        checkEmpty();
    });
</script>
</body>
</html>
