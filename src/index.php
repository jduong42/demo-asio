<?php
/**
 * Main entry point for the application - Home Page
 */

header('Content-Type: text/html; charset=utf-8');

// Include database connection
require_once(__DIR__ . '/config/db.php');

// Fetch spaces from database
$spaces_query = "SELECT * FROM spaces LIMIT 4";
$spaces_result = $conn->query($spaces_query);
$spaces = [];
if ($spaces_result && $spaces_result->num_rows > 0) {
    while ($row = $spaces_result->fetch_assoc()) {
        $spaces[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASIO - Space Booking Service</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <!-- Fixed Header -->
    <header class="header" role="banner">
        <div class="header-content">
            <div class="header-left">
                <div class="logo" role="img" aria-label="ASIO logo">ASIO</div>
            </div>
            <div class="header-right">
                <button class="btn btn-secondary" onclick="window.location.href='register.php'" aria-label="Create new user account">
                    <span aria-hidden="true">👤</span> Rekisteröidy
                </button>
                <button class="btn btn-secondary" onclick="alert('Login - Coming Soon')" aria-label="Log in to your account">
                    <span aria-hidden="true">🔑</span> Kirjaudu sisään
                </button>
                <a href="#" class="btn btn-primary" aria-label="View my reservations">
                    <span aria-hidden="true">📋</span> Omat varaukset
                </a>
                <div class="search-bar" role="search">
                    <label for="searchInput" class="sr-only">Search for spaces</label>
                    <input type="text" placeholder="Hae tilaa..." id="searchInput" aria-label="Hae tilaa painike.">
                    <button type="button" aria-label="Search"><span aria-hidden="true">🔍</span></button>
                </div>
            </div>
        </div>
    </header>

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb" aria-label="Breadcrumb navigation">
        <ol>
            <li><a href="index.php">Tilavaraukset</a></li>
        </ol>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- Left Sidebar -->
        <aside class="sidebar" role="complementary">
            <!-- Navigation -->
            <nav class="navigation" aria-label="Main navigation">
                <ul>
                    <li><a href="index.php" class="active" aria-label="Go to home page" aria-current="page"><span class="nav-icon home-icon" aria-hidden="true">🏠</span>Etusivu</a></li>
                    <li><a href="#" aria-label="View auditoriums"><span class="nav-icon" aria-hidden="true">›</span> Auditoriot</a></li>
                    <li><a href="#" aria-label="View conference rooms"><span class="nav-icon" aria-hidden="true">›</span>Kokoustilat</a></li>
                    <li><a href="#" aria-label="View information about the service"><span class="nav-icon" aria-hidden="true">›</span>Tietoa palvelusta</a></li>
                </ul>
            </nav>

            <!-- Service Provider Info Box -->
            <div class="info-box" role="contentinfo">
                <div class="info-content">
                    <p><b>Asio Business Park Oy Ltd.</b></p>
                    <p>Kyyluodontie 5 A, 00200 Helsinki</p>
                    <p>p. 096822929</p>
                    <p><a href="mailto:asio@asio.fi" aria-label="Email service provider">asio@asio.fi</a></p>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="main-content" role="main">
            <h2>Varaa - Maksa - Avaa</h2>
            <br></br>

            <!-- 2x2 Grid of Space Cards -->
            <div class="spaces-grid">
                <?php if (count($spaces) > 0): ?>
                    <?php foreach ($spaces as $space): ?>
                        <div class="space-card" 
                             onclick="showSpaceDetails(<?php echo $space['id']; ?>)" 
                             role="button" 
                             tabindex="0" 
                             aria-label="View details for <?php echo htmlspecialchars($space['label']); ?>">
                            <div class="space-image">
                                <img src="<?php echo htmlspecialchars($space['image_url']); ?>" 
                                     alt="Image of <?php echo htmlspecialchars($space['name']); ?>"
                                     onerror="this.src='public/images/placeholder.jpg'">
                            </div>
                            <div class="space-info">
                                <h3><?php echo htmlspecialchars($space['label']); ?></h3>
                                <p class="space-description">
                                    <span class="arrow" aria-hidden="true">&rarr;</span>
                                    <?php echo htmlspecialchars($space['description']); ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No spaces available at the moment.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="footer" role="contentinfo">
        <div class="footer-content">
            <div class="footer-logo" role="img" aria-label="ASIO company logo">ASIO</div>
        </div>
    </footer>

    <script src="public/js/main.js"></script>
</body>
</html>
