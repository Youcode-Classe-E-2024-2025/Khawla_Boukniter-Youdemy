<div class="bg-gray-50">
    <!-- Hero Section with Background Image -->
    <div class="relative overflow-hidden bg-gradient-to-r from-indigo-900 to-purple-800">

        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-32">
            <div class="text-center">
                <h1 class="text-4xl font-extrabold tracking-tight text-white sm:text-5xl md:text-6xl">
                    <span class="block">Transformez votre vie</span>
                    <span class="block text-indigo-200">grâce à l'apprentissage</span>
                </h1>
                <p class="mt-6 max-w-lg mx-auto text-xl text-indigo-100 sm:max-w-3xl">
                    Développez vos compétences avec nos cours en ligne dispensés par des experts du monde entier
                </p>
                <div class="mt-10 max-w-sm mx-auto sm:max-w-none sm:flex sm:justify-center">
                    <div class="space-y-4 sm:space-y-0 sm:mx-auto sm:inline-grid sm:grid-cols-2 sm:gap-5">
                        <a href="<?= base_url('browse') ?>" class="flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 md:py-4 md:text-lg md:px-10">
                            Explorer les cours
                        </a>
                        <a href="<?= base_url('register') ?>" class="flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                            Commencer gratuitement
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:py-16 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-8 sm:grid-cols-3">
                <div class="text-center">
                    <span class="text-4xl font-extrabold text-indigo-600">1000+</span>
                    <p class="mt-2 text-lg font-medium text-gray-600">Étudiants actifs</p>
                </div>
                <div class="text-center">
                    <span class="text-4xl font-extrabold text-indigo-600">100+</span>
                    <p class="mt-2 text-lg font-medium text-gray-600">Cours disponibles</p>
                </div>
                <div class="text-center">
                    <span class="text-4xl font-extrabold text-indigo-600">50+</span>
                    <p class="mt-2 text-lg font-medium text-gray-600">Instructeurs experts</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Categories Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12">
            <span class="block">Explorez nos catégories</span>
            <span class="block text-indigo-600 text-xl mt-2">Trouvez le sujet qui vous passionne</span>
        </h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <?php foreach ($categories as $category): ?>
                <div class="group relative bg-white p-6 rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    <div class="flex flex-col items-center">
                        <div class="w-12 h-12 bg-indigo-100 rounded-lg flex items-center justify-center group-hover:bg-indigo-600 transition-colors duration-300">
                            <i class="fas fa-graduation-cap text-indigo-600 group-hover:text-white"></i>
                        </div>
                        <h3 class="mt-4 text-lg font-medium text-gray-900 group-hover:text-indigo-600">
                            <?= htmlspecialchars($category['name']) ?>
                        </h3>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- Featured Courses -->
    <div class="bg-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl font-extrabold text-gray-900 text-center mb-12">
                <span class="block">Cours les plus populaires</span>
                <span class="block text-indigo-600 text-xl mt-2">Rejoignez des milliers d'apprenants</span>
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                <?php foreach ($topCourses as $course): ?>
                    <div class="bg-white rounded-xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden">
                        <div class="aspect-w-16 aspect-h-9 bg-gray-200">
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-3">
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                    <?= $course['category_name'] ?? 'Catégorie' ?>
                                </span>
                            </div>
                            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                <?= htmlspecialchars($course['titre']) ?>
                            </h3>
                            <p class="text-sm text-gray-500 mb-4 line-clamp-2">
                                <?= htmlspecialchars($course['description']) ?>
                            </p>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <img class="h-10 w-10 rounded-full" src="https://ui-avatars.com/api/?name=<?= urlencode($course['teacher_prenom'] . ' ' . $course['teacher_nom']) ?>" alt="">
                                    </div>
                                    <span class="ml-2 text-sm font-medium text-gray-700">
                                        <?= htmlspecialchars($course['teacher_prenom'] . ' ' . $course['teacher_nom']) ?>
                                    </span>
                                </div>
                                <span class="flex items-center text-sm text-indigo-600">
                                    <i class="fas fa-users mr-1"></i>
                                    <?= number_format($course['student_count']) ?>
                                </span>
                            </div>
                        </div>
                        <a href="<?= base_url('courses/' . $course['id']) ?>"
                            class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            Voir le cours
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- CTA Section -->
    <div class="bg-indigo-700">
        <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-white sm:text-4xl">
                    <span class="block">Prêt à commencer votre voyage ?</span>
                </h2>
                <p class="mt-4 text-lg leading-6 text-indigo-100">
                    Rejoignez notre communauté d'apprenants dès aujourd'hui
                </p>
                <a href="<?= base_url('register') ?>" class="mt-8 inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-700 bg-white hover:bg-indigo-50 md:py-4 md:text-lg md:px-10">
                    S'inscrire gratuitement
                </a>
            </div>
        </div>
    </div>
</div>