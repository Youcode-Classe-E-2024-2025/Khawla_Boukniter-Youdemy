<!DOCTYPE html>
<html lang="fr">

<head>

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>EduFun - Plateforme de cours en ligne</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="<?= asset_url('css/style.css') ?>" rel="stylesheet">
        <?php if (isset($isHomePage) && $isHomePage): ?>
            <link href="<?= asset_url('css/home.css') ?>" rel="stylesheet">
        <?php endif; ?>
        <script src="https://cdn.tiny.cloud/1/ffjb0gppot6y2dmxj12rgbip06mamy2rzh7ptmck77bsmo1h/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
        <script>
            tinymce.init({
                selector: '#document_content',
                plugins: 'markdown anchor autolink charmap codesample emoticons link lists media searchreplace table visualblocks wordcount',
                toolbar: 'undo redo | blocks fontfamily fontsize | bold italic underline strikethrough | link image media table mergetags | align lineheight | checklist numlist bullist indent outdent | emoticons charmap | removeformat',
                height: 500,
                markdown: true,
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                }
            });
        </script>
    </head>

<body>
    <!-- Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="/" class="flex items-center text-xl font-bold text-indigo-600">
                        Youdemy
                    </a>
                    <div class="hidden sm:ml-6 sm:flex sm:space-x-8">

                        <?php if (isset($_SESSION['user_id'])): ?>
                            <?php if ($_SESSION['user_role'] === 2): ?>
                                <a href="<?= base_url('users/teacher/courses') ?>" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Mes cours
                                </a>
                                <a href="<?= base_url('dashboard') ?>" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Dashboard
                                </a>
                            <?php elseif ($_SESSION['user_role'] === 1): ?>
                                <a href="<?= base_url('users/student/courses') ?>" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Cours
                                </a>
                                <a href="/student/courses" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Mes cours
                                </a>
                            <?php elseif ($_SESSION['user_role'] === 3): ?>
                                <a href="/admin/dashboard" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Administration
                                </a>
                                <a href="/admin/management" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    management
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex items-center">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <span class="text-gray-900 mr-4">
                            <?= htmlspecialchars($_SESSION['user_name']) ?>
                        </span>
                        <a href="<?= base_url('logout') ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Déconnexion
                        </a>
                    <?php else: ?>
                        <a href="<?= base_url('login') ?>" class="text-gray-900 inline-flex items-center px-1 pt-1 mr-4">
                            Connexion
                        </a>
                        <a href="<?= base_url('register') ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium">
                            Inscription
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    <?php if (isset($_SESSION['error'])): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                <?= htmlspecialchars($_SESSION['success']) ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Contenu de la page -->
    <main class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <?php if (isset($content)) echo $content; ?>
    </main>

    <!-- <footer class="footer mt-auto py-3">
        <div class="container">
            <div class="row">
                <div class="col-md-4">
                    <h5 class="mb-3">ProjetManager</h5>
                    <p>Une solution simple et puissante pour la gestion de projets en équipe.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Liens rapides</h5>
                    <ul class="list-unstyled">
                        <li><a href="<?= base_url() ?>" class="text-decoration-none">Accueil</a></li>
                        <?php if (is_authenticated()): ?>
                            <li><a href="<?= base_url('dashboard') ?>" class="text-decoration-none">Tableau de bord</a></li>
                            <li><a href="<?= base_url('projects') ?>" class="text-decoration-none">Projets</a></li>
                        <?php else: ?>
                            <li><a href="<?= base_url('login') ?>" class="text-decoration-none">Connexion</a></li>
                            <li><a href="<?= base_url('register') ?>" class="text-decoration-none">Inscription</a></li>
                        <?php endif; ?>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="mb-3">Contact</h5>
                    <ul class="list-unstyled">
                        <li><i class="bi bi-envelope me-2"></i>contact@projetmanager.com</li>
                        <li><i class="bi bi-telephone me-2"></i>+33 1 23 45 67 89</li>
                        <li><i class="bi bi-geo-alt me-2"></i>Paris, France</li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-6">
                    <p>&copy; <?= date('Y') ?> ProjetManager. Tous droits réservés.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <ul class="list-inline mb-0" style="text-align: center;">
                        <li class="list-inline-item">
                            <a href="#" class="text-decoration-none">
                                <i class="bi bi-facebook"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="text-decoration-none">
                                <i class="bi bi-twitter"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="#" class="text-decoration-none">
                                <i class="bi bi-linkedin"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer> -->

    <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->
</body>

</html>