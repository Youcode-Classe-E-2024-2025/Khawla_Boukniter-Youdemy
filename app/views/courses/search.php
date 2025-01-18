<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Résultats de recherche</h1>
            <p class="mt-2 text-sm text-gray-600">
                <?= count($courses) ?> résultat(s) pour "<?= htmlspecialchars($keyword) ?>"
            </p>
        </div>

        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($courses as $course): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                                <?= htmlspecialchars($course['category_name']) ?>
                            </span>
                        </div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">
                            <?= htmlspecialchars($course['titre']) ?>
                        </h3>
                        <p class="text-gray-600 mb-4 line-clamp-3">
                            <?= htmlspecialchars($course['description']) ?>
                        </p>
                        <div class="flex items-center justify-between">
                            <div class="text-sm">
                                <p class="text-gray-900 font-medium">
                                    <?= htmlspecialchars($course['teacher_prenom'] . ' ' . $course['teacher_nom']) ?>
                                </p>
                            </div>
                            <a href="<?= base_url('courses/' . $course['id']) ?>"
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                Voir le cours
                            </a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>