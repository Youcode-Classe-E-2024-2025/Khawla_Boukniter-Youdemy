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
        <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
        <link href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css" rel="stylesheet" type="text/css" />
    </head>

<body>
    <!-- Navigation -->
    <nav class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <a href="<?= isset($_SESSION['user_id']) ? base_url('dashboard') : base_url('/') ?>" class="flex items-center text-xl font-bold text-indigo-600">
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
                                <a href="<?= base_url('browse') ?>" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Cours
                                </a>
                                <a href="<?= base_url('student/courses') ?>" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Mes cours
                                </a>
                                <a href="<?= base_url('dashboard') ?>" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Dashboard
                                </a>
                            <?php elseif ($_SESSION['user_role'] === 3): ?>
                                <a href="<?= base_url('dashboard') ?>" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Dashboard
                                </a>
                                <a href="<?= base_url('users/admin/inscriptions') ?>" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Inscriptions
                                </a>
                                <a href="<?= base_url('users/admin/courses') ?>" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Cours
                                </a>
                                <a href="<?= base_url('users/admin/categories-tags') ?>" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Catégories & Tags
                                </a>
                                <a href="<?= base_url('users/admin/users') ?>" class="text-gray-900 inline-flex items-center px-1 pt-1">
                                    Utilisateurs
                                </a>
                            <?php endif; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="flex-1 flex items-center justify-center px-6">
                    <form action="<?= base_url('search') ?>" method="GET" class="w-full max-w-md">
                        <div class="relative group">
                            <input type="search"
                                name="q"
                                placeholder="Rechercher un cours"
                                class="block w-full px-3 py-1.5 bg-gray-100 border-2 border-transparent rounded-full text-sm text-gray-900 placeholder-gray-500 focus:bg-white focus:border-indigo-500 transition-all duration-300 ease-in-out"
                                value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                            <button type="submit" class="absolute right-1.5 top-1/2 transform -translate-y-1/2 p-1.5 hover:bg-gray-200 rounded-full transition-colors duration-200">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </button>
                        </div>
                    </form>
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

</body>

</html>