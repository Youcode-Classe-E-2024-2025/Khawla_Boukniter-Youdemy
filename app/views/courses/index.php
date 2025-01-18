<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-gray-900">Découvrez nos cours</h1>
            <p class="mt-4 text-xl text-gray-600">Explorez notre catalogue de cours et commencez votre apprentissage</p>
        </div>

        <!-- Course Grid -->
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">
            <?php foreach ($courses as $course): ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden transition-transform hover:scale-105">
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
                            <div class="flex items-center">
                                <div class="text-sm">
                                    <p class="text-gray-900 font-medium">
                                        <?= htmlspecialchars($course['teacher_prenom'] . ' ' . $course['teacher_nom']) ?>
                                    </p>
                                    <p class="text-gray-500">
                                        <?= $course['student_count'] ?> étudiants
                                    </p>
                                </div>
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

        <div class="mt-8 flex justify-center">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <!-- Previous Page -->
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif; ?>

                <!-- Page Numbers -->
                <?php for ($i = 1; $i <= $pages; $i++): ?>
                    <a href="?page=<?= $i ?>"
                        class="relative inline-flex items-center px-4 py-2 border <?= $i === $page ? 'bg-indigo-50 border-indigo-500 text-indigo-600 z-10' : 'border-gray-300 bg-white text-gray-500 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <!-- Next Page -->
                <?php if ($page < $pages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    </div>
</div>