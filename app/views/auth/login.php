<!-- <form action="<?= base_url('login') ?>" method="POST">
    <h2>Connexion</h2>
    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>
    
    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required>
    
    <button type="submit">Se connecter</button>
    
    <p>Pas encore inscrit ? <a href="<?= base_url('register') ?>">Inscrivez-vous ici</a></p>
</form> -->


<link href="<?= asset_url('css/style.css') ?>" rel="stylesheet">

<body class="flex center">
  <div class="page-grid grid">
    <section class="banner grid center">
      <img src="https://blogger.googleusercontent.com/img/a/AVvXsEghdXVxxAlp1t_cKedjD8EZ9toGFYAPe_c40Sdogsj12fzOjV65u4rsHBVo7jnViKGu7pFKEncx3FcIh9GAJqz0IXOwuX1HW5hA_9uy58L4bOj-uZUWTysmgi9WMf1FTdD_EmL2xeVFsJhSf3gr8c5tiGX8frQdZtUv1ny-LEui0HDr0RmCCmY0bUaA" alt="">
    </section>
    <section class="form-wrapper grid center">
      <form action="<?= base_url('login') ?>" method="POST" class="form-section">
        <div class="header">
          <h1>Welcome Back</h1>
          <p>Welcome back, Please enter your details</p>
          <?php if (isset($_SESSION['error'])): ?>
              <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
          <?php endif; ?>
        </div>
        <div class="field">
          <label for="email">Email</label>
          <input type="text" placeholder="Enter Your name" name="email">
        </div>
        <div class="field">
          <label for="password">Password</label>
          <input type="password" placeholder="Enter Your password" name="password">
        </div>
        <button type="submit" class="btn btn-primary">Sign in</button>
        
        <div class="footer">
          <p>Dont have an account? <a href="<?= base_url('register') ?>">Sign up</a></p>
        </div>
      </form>
    </section>
  </div>
</body>