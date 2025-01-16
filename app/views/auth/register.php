<!-- <form action="<?= base_url('register') ?>" method="POST">
    <h2>Inscription</h2>
    <label for="nom">Nom:</label>
    <input type="text" id="nom" name="nom" required>

    <label for="prenom">Prénom:</label>
    <input type="text" id="prenom" name="prenom" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="password">Mot de passe:</label>
    <input type="password" id="password" name="password" required>

    <label for="role_id">Rôle:</label>
    <select id="role_id" name="role_id" required>
        <option value="1">Étudiant</option>
        <option value="2">Professeur</option>
        <option value="3">Administrateur</option>
    </select>

    <button type="submit">S'inscrire</button>
</form>

<?php if (isset($_SESSION['signup_error'])): ?>
    <div class="error"><?php echo $_SESSION['signup_error']; unset($_SESSION['signup_error']); ?></div>
<?php endif; ?> -->

<link href="<?= asset_url('css/style.css') ?>" rel="stylesheet">

<body class="flex center">
  <div class="page-grid grid">
    <section class="banner grid center">
      <img src="https://blogger.googleusercontent.com/img/a/AVvXsEghdXVxxAlp1t_cKedjD8EZ9toGFYAPe_c40Sdogsj12fzOjV65u4rsHBVo7jnViKGu7pFKEncx3FcIh9GAJqz0IXOwuX1HW5hA_9uy58L4bOj-uZUWTysmgi9WMf1FTdD_EmL2xeVFsJhSf3gr8c5tiGX8frQdZtUv1ny-LEui0HDr0RmCCmY0bUaA" alt="">
    </section>
    <section class="form-wrapper grid center">
      <form action="<?= base_url('register') ?>" method="POST" class="form-section">
        <div class="header">
          <h1>Inscription</h1>
          <p>Bienvenue</p>
          <?php if (isset($_SESSION['error'])): ?>
              <div class="error"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
          <?php endif; ?>
        </div>
        <div id="step1">
          <div class="field">
            <label for="nom">Nom</label>
            <input type="text" placeholder="Entrez votre nom" name="nom" required>
          </div>
          <div class="field">
            <label for="prenom">Prénom</label>
            <input type="text" placeholder="Entrez votre prénom" name="prenom" required>
          </div>
          <div class="field">
            <label for="email">Email</label>
            <input type="email" placeholder="Entrez votre email" name="email" required>
          </div>
          <div class="field">
            <label for="password">Mot de passe</label>
            <input type="password" placeholder="Entrez votre mot de passe" name="password" required>
          </div>
          <button type="button" class="btn btn-primary" id="nextStep">Suivant</button>
        </div>

        <div id="step2" style="display:none;">
          <div class="field">
            <label for="role_id">Rôle</label>
            <select name="role_id" id="role_id" required>
              <option value="1">Étudiant</option>
              <option value="2">Professeur</option>
              <option value="3">Administrateur</option>
            </select>
          </div>
          <button type="submit" class="btn btn-primary">S'inscrire</button>
          <button type="button" class="btn btn-secondary" id="backStep">Retour</button>
        </div>
        
        <!-- <button type="submit" class="btn btn-primary">Sign in</button> -->
        
        <div class="footer">
          <p>Already have an account? <a href="<?= base_url('login') ?>">Sign up</a></p>
        </div>
      </form>
    </section>
  </div>
  <script>
    document.getElementById('nextStep').addEventListener('click', function() {
      document.getElementById('step1').style.display = 'none';
      document.getElementById('step2').style.display = 'block';
    });

    document.getElementById('backStep').addEventListener('click', function() {
      document.getElementById('step2').style.display = 'none';
      document.getElementById('step1').style.display = 'block';
    });
  </script>
</body>