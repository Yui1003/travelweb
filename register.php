<?php
// Include header
include 'includes/header.php';

// Redirect if already logged in
if (isLoggedIn()) {
    header("Location: index.php");
    exit;
}

// Check if form is submitted
$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $username = sanitizeInput($_POST['username']);
    $email = sanitizeInput($_POST['email']);
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];
    $fullName = sanitizeInput($_POST['full_name']);
    $phone = sanitizeInput($_POST['phone']);
    
    // Validate passwords match
    if ($password !== $confirmPassword) {
        $message = '<div class="alert alert-danger">Passwords do not match. Please try again.</div>';
    } else {
        // Register user
        $result = registerUser($conn, $username, $email, $password, $fullName, $phone);
        
        if ($result['success']) {
            // Redirect to dashboard based on role
            if (isAdmin()) {
                header("Location: admin-dashboard.php");
            } elseif (isOperator()) {
                header("Location: operator-dashboard.php");
            } else {
                header("Location: traveler-dashboard.php");
            }
            exit;
        } else {
            $message = '<div class="alert alert-danger">' . $result['message'] . '</div>';
        }
    }
}
?>

<!-- Page Content -->
<section class="auth-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="auth-card">
                    <div class="row g-0">
                        <div class="col-md-6 d-none d-md-block">
                            <div class="auth-image h-100" style="background-image: url('https://images.unsplash.com/photo-1464746133101-a2c3f88e0dd9');"></div>
                        </div>
                        <div class="col-md-6">
                            <div class="auth-form" data-aos="fade-left">
                                <h2>Create an Account</h2>
                                
                                <?php echo $message; ?>
                                
                                <form method="post" action="register.php">
                                    <div class="mb-3">
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <input type="text" class="form-control" id="full_name" name="full_name" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control" id="username" name="username" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email Address</label>
                                        <input type="email" class="form-control" id="email" name="email" required>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone Number</label>
                                        <input type="tel" class="form-control" id="phone" name="phone">
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="password" class="form-label">Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="password" name="password" required>
                                            <span class="input-group-text">
                                                <i class="fas fa-eye password-toggle" role="button"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm Password</label>
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="confirm_password" name="confirm_password" required>
                                            <span class="input-group-text">
                                                <i class="fas fa-eye password-toggle-confirm" role="button"></i>
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="terms" name="terms" required>
                                        <label class="form-check-label" for="terms">I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></label>
                                    </div>
                                    
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">Register</button>
                                    </div>
                                </form>
                                
                                <div class="auth-footer">
                                    <p>Already have an account? <a href="login.php">Login</a></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    const passwordToggle = document.querySelector('.password-toggle');
    if (passwordToggle) {
        passwordToggle.addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }
    
    // Confirm password visibility toggle
    const passwordToggleConfirm = document.querySelector('.password-toggle-confirm');
    if (passwordToggleConfirm) {
        passwordToggleConfirm.addEventListener('click', function() {
            const confirmPasswordInput = document.getElementById('confirm_password');
            const type = confirmPasswordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            confirmPasswordInput.setAttribute('type', type);
            
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    }
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?>