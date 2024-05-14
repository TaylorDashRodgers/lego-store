<?php session_start(); ?>
<?php include('Unit6_header.php'); ?>
<link rel="stylesheet" type="text/css" href="Unit6_login.css">

<main>

    <?php
    if (isset($_GET['err'])) {
        echo '<p class="error-message">' . htmlspecialchars($_GET['err']) . '</p>';
    }
    ?>

    <form action="Unit6_login.php" method="post">
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>

        <button type="submit">Login</button>
    </form>

    <div class="options">
        <a href="Unit6_store.php" class="button">Continue as Guest</a>
        <a href="#">Forgot Password</a>
        <label><input type="checkbox" name="remember"> Remember Me</label>
    </div>
</main>

<?php include('Unit6_footer.php'); ?>