<link href="<?= asset_url('css/style.css') ?>" rel="stylesheet">

<body class="flex center">
  <div class="page-grid grid">
    <section class="banner grid center">
      <img src="https://blogger.googleusercontent.com/img/a/AVvXsEghdXVxxAlp1t_cKedjD8EZ9toGFYAPe_c40Sdogsj12fzOjV65u4rsHBVo7jnViKGu7pFKEncx3FcIh9GAJqz0IXOwuX1HW5hA_9uy58L4bOj-uZUWTysmgi9WMf1FTdD_EmL2xeVFsJhSf3gr8c5tiGX8frQdZtUv1ny-LEui0HDr0RmCCmY0bUaA" alt="">
    </section>
    <section class="form-wrapper grid center">
      <form action="<?= base_url('login') ?>" method="POST" class="form-section">
        <?= csrf_field() ?>
        <div class="header">
          <h1>Welcome Back</h1>
          <p>Welcome back, Please enter your details</p>
          <?php if (isset($_SESSION['error'])): ?>
            <div class="error"><?php echo $_SESSION['error'];
                                unset($_SESSION['error']); ?></div>
          <?php endif; ?>
        </div>
        <div class="field">
          <label for="email">Email</label>
          <input type="text" placeholder="Enter Your name" name="email" required pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$">
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" placeholder="Enter Your password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Sign in</button>

        <div class="footer">
          <p>Dont have an account? <a href="<?= base_url('register') ?>">Sign up</a></p>
        </div>
      </form>
    </section>
  </div>

  <script>
    function validateLoginForm() {
      const email = document.querySelector('input[name="email"]').value;
      const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

      if (!emailRegex.test(email)) {
        alert('Veuillez entrer une adresse email valide');
        return false;
      }
      return true;
    }
  </script>
</body>