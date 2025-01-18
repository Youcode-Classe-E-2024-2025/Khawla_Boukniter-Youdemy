<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Animated Welcome Header -->
        <div class="text-center mb-12 animate-fade-in">
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-500">
                Bienvenue <?= $_SESSION['user_name'] ?>
            </h1>
            <p class="mt-3 text-lg text-gray-600">
                Prêt à continuer votre apprentissage ?
            </p>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-12">
            <!-- Enrolled Courses Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-50">
                            <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold text-gray-900">Cours inscrits</h3>
                            <p class="text-3xl font-bold text-indigo-600"><?= $enrolled_courses ?></p>
                        </div>
                    </div>
                </div>
            </div>


        </div>


        <!-- Recent Courses -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">Mes cours récents</h3>
            </div>

            <div class="divide-y divide-gray-200">
                <?php if (!empty($recentCourses)): ?>
                    <?php foreach ($recentCourses as $course): ?>
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900">
                                        <?= htmlspecialchars($course['titre']) ?>
                                    </h4>
                                    <p class="mt-1 text-sm text-gray-500">
                                        Par <?= htmlspecialchars($course['teacher_prenom'] . ' ' . $course['teacher_nom']) ?>
                                    </p>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 mt-2">
                                        <?= htmlspecialchars($course['category_name']) ?>
                                    </span>
                                </div>
                                <a href="<?= base_url('courses/' . $course['id']) ?>"
                                    class="ml-6 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                    Continuer
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="p-12 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Pas encore de cours</h3>
                        <p class="mt-1 text-sm text-gray-500">Commencez à explorer nos cours disponibles.</p>
                        <div class="mt-6">
                            <a href="<?= base_url('browse') ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Explorer les cours
                            </a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>