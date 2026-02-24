<?php
/**
 * Registration Page
 * Handles both private person and company registration forms
 */

header('Content-Type: text/html; charset=utf-8');

// Include AuthController (handles POST submission)
require_once(__DIR__ . '/controllers/AuthController.php');

$result = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $auth = new AuthController();
    $result = $auth->register($_POST);
}
?>
<!DOCTYPE html>
<html lang="fi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASIO - Rekisteröidy</title>
    <link rel="stylesheet" href="public/css/style.css">
</head>
<body>
    <!-- Fixed Header (same as home page) -->
    <header class="header" role="banner">
        <div class="header-content">
            <div class="header-left">
                <div class="logo" role="img" aria-label="ASIO logo">ASIO</div>
            </div>
            <div class="header-right">
                <a href="index.php" class="btn btn-secondary" aria-label="Go back to home page">
                    <span aria-hidden="true">🏠</span> Etusivu
                </a>
                <button class="btn btn-secondary" onclick="alert('Login - Coming Soon')" aria-label="Log in to your account">
                    <span aria-hidden="true">🔑</span> Kirjaudu sisään
                </button>
                <a href="#" class="btn btn-primary" aria-label="View my reservations">
                    <span aria-hidden="true">📋</span> Omat varaukset
                </a>
            </div>
        </div>
    </header>

    <!-- Breadcrumb Navigation -->
    <nav class="breadcrumb" aria-label="Breadcrumb navigation">
        <ol>
            <li><a href="index.php">Tilavaraukset</a></li>
            <li><span aria-current="page">Rekisteröidy</span></li>
        </ol>
    </nav>

    <!-- Main Container -->
    <div class="container">
        <!-- Left Sidebar -->
        <aside class="sidebar" role="complementary">
            <nav class="navigation" aria-label="Main navigation">
                <ul>
                    <li><a href="index.php" aria-label="Go to home page"><span class="nav-icon home-icon" aria-hidden="true">🏠</span>Etusivu</a></li>
                    <li><a href="#" aria-label="View auditoriums"><span class="nav-icon" aria-hidden="true">›</span> Auditoriot</a></li>
                    <li><a href="#" aria-label="View conference rooms"><span class="nav-icon" aria-hidden="true">›</span> Kokoustilat</a></li>
                    <li><a href="#" aria-label="View information about the service"><span class="nav-icon" aria-hidden="true">›</span> Tietoa palvelusta</a></li>
                </ul>
            </nav>
            <div class="info-box" role="contentinfo">
                <div class="info-content">
                    <p><b>Asio Business Park Oy Ltd.</b></p>
                    <p>Kyyluodontie 5 A, 00200 Helsinki</p>
                    <p>p. 096822929</p>
                    <p><a href="mailto:asio@asio.fi" aria-label="Email service provider">asio@asio.fi</a></p>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="main-content" role="main">
            <h1>Rekisteröidy</h1>
            <p class="subtitle">Luo uusi käyttäjätili varauspalveluun</p>

            <!-- Alert: success or error after submission -->
            <?php if ($result !== null): ?>
                <div class="alert <?php echo $result['success'] ? 'alert-success' : 'alert-error'; ?>"
                     id="form-alert"
                     role="alert"
                     aria-live="polite">
                    <span class="alert-icon" aria-hidden="true">
                        <?php echo $result['success'] ? '✅' : '❌'; ?>
                    </span>
                    <span class="alert-message"><?php echo htmlspecialchars($result['message']); ?></span>
                    <button class="alert-close" onclick="dismissAlert()" aria-label="Sulje ilmoitus">✕</button>
                    <div class="alert-progress" id="alert-progress"></div>
                </div>
            <?php endif; ?>

            <?php if (!($result['success'] ?? false)): ?>
            <div class="register-card">
                <!-- User type toggle -->
                <div class="user-type-toggle" role="group" aria-labelledby="user-type-label">
                    <p id="user-type-label" class="toggle-label">Rekisteröitymistyyppi</p>
                    <div class="toggle-buttons">
                        <button type="button"
                                class="toggle-btn active"
                                id="btn-private"
                                onclick="switchType('private')"
                                aria-pressed="true">
                            <span aria-hidden="true">👤</span> Yksityishenkilö
                        </button>
                        <button type="button"
                                class="toggle-btn"
                                id="btn-company"
                                onclick="switchType('company')"
                                aria-pressed="false">
                            <span aria-hidden="true">🏢</span> Yritys
                        </button>
                    </div>
                </div>

                <form id="register-form"
                      method="POST"
                      action="register.php"
                      novalidate
                      aria-label="Registration form">

                    <input type="hidden" name="user_type" id="user_type" value="private">

                    <!-- ── Private person fields ── -->
                    <div id="private-fields">
                        <p class="form-section-heading">Henkilötiedot</p>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="first_name">Etunimi <span class="required" aria-hidden="true">*</span></label>
                                <input type="text"
                                       id="first_name"
                                       name="first_name"
                                       autocomplete="given-name"
                                       value="<?php echo htmlspecialchars($_POST['first_name'] ?? ''); ?>"
                                       aria-required="true">
                            </div>
                            <div class="form-group">
                                <label for="last_name">Sukunimi <span class="required" aria-hidden="true">*</span></label>
                                <input type="text"
                                       id="last_name"
                                       name="last_name"
                                       autocomplete="family-name"
                                       value="<?php echo htmlspecialchars($_POST['last_name'] ?? ''); ?>"
                                       aria-required="true">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="phone_private">Puhelinnumero</label>
                            <input type="tel"
                                   id="phone_private"
                                   name="phone"
                                   autocomplete="tel"
                                   value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                        </div>
                    </div>

                    <!-- ── Company fields ── -->
                    <div id="company-fields" style="display:none;">
                        <p class="form-section-heading">Yrityksen tiedot</p>
                        <div class="form-group">
                            <label for="company_name">Yrityksen nimi <span class="required" aria-hidden="true">*</span></label>
                            <input type="text"
                                   id="company_name"
                                   name="company_name"
                                   autocomplete="organization"
                                   value="<?php echo htmlspecialchars($_POST['company_name'] ?? ''); ?>"
                                   aria-required="true">
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="business_id">Y-tunnus <span class="required" aria-hidden="true">*</span></label>
                                <input type="text"
                                       id="business_id"
                                       name="business_id"
                                       placeholder="1234567-8"
                                       value="<?php echo htmlspecialchars($_POST['business_id'] ?? ''); ?>"
                                       aria-required="true">
                            </div>
                            <div class="form-group">
                                <label for="phone_company">Puhelinnumero</label>
                                <input type="tel"
                                       id="phone_company"
                                       name="phone"
                                       autocomplete="tel"
                                       value="<?php echo htmlspecialchars($_POST['phone'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="contact_person">Yhteyshenkilö <span class="required" aria-hidden="true">*</span></label>
                            <input type="text"
                                   id="contact_person"
                                   name="contact_person"
                                   value="<?php echo htmlspecialchars($_POST['contact_person'] ?? ''); ?>"
                                   aria-required="true">
                        </div>
                        <div class="form-group">
                            <label for="billing_address">Laskutusosoite <span class="required" aria-hidden="true">*</span></label>
                            <textarea id="billing_address"
                                      name="billing_address"
                                      rows="3"
                                      aria-required="true"><?php echo htmlspecialchars($_POST['billing_address'] ?? ''); ?></textarea>
                        </div>
                    </div>

                    <!-- ── Shared fields (both types) ── -->
                    <div class="form-divider"></div>
                    <p class="form-section-heading">Kirjautumistiedot</p>

                    <div class="form-group">
                        <label for="email">Sähköpostiosoite <span class="required" aria-hidden="true">*</span></label>
                        <input type="email"
                               id="email"
                               name="email"
                               autocomplete="email"
                               value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>"
                               aria-required="true">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="password">Salasana <span class="required" aria-hidden="true">*</span></label>
                            <input type="password"
                                   id="password"
                                   name="password"
                                   autocomplete="new-password"
                                   aria-required="true"
                                   aria-describedby="password-hint">
                            <small id="password-hint" class="field-hint">Vähintään 8 merkkiä</small>
                        </div>
                        <div class="form-group">
                            <label for="password_confirm">Vahvista salasana <span class="required" aria-hidden="true">*</span></label>
                            <input type="password"
                                   id="password_confirm"
                                   name="password_confirm"
                                   autocomplete="new-password"
                                   aria-required="true">
                        </div>
                    </div>

                    <!-- ── Privacy consent ── -->
                    <div class="form-group consent-group">
                        <label class="checkbox-label">
                            <input type="checkbox"
                                   name="privacy_consent"
                                   id="privacy_consent"
                                   value="1"
                                   aria-required="true"
                                   <?php echo isset($_POST['privacy_consent']) ? 'checked' : ''; ?>>
                            <span>
                                Olen lukenut ja hyväksyn
                                <a href="#" aria-label="Read privacy notice">tietosuojaselosteen</a>
                                <span class="required" aria-hidden="true">*</span>
                            </span>
                        </label>
                    </div>

                    <!-- ── Submit ── -->
                    <div class="form-actions">
                        <button type="submit"
                                class="btn btn-submit"
                                id="submit-btn"
                                aria-label="Submit registration form">
                            <span id="btn-text">Lähetä rekisteröitymislomake</span>
                            <span id="btn-spinner" class="spinner" aria-hidden="true" style="display:none;"></span>
                        </button>
                    </div>

                    <p class="required-note"><span class="required" aria-hidden="true">*</span> Pakollinen kenttä</p>
                </form>
            </div>
            <?php endif; ?>
        </main>
    </div>

    <!-- Footer -->
    <footer class="footer" role="contentinfo">
        <div class="footer-logo">ASIO</div>
        <p>&copy; 2026 Asio Business Park Oy Ltd.</p>
    </footer>

    <script src="public/js/register.js"></script>
</body>
</html>
