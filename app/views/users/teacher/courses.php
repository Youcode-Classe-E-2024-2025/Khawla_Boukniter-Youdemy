<div class="mt-8">
    <div class="flex items-center justify-between">
        <h2 class="text-lg font-medium text-gray-900">Mes cours</h2>
        <a href="<?= base_url('teacher/courses/create') ?>" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
            Créer un cours
        </a>
    </div>

    <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
        <?php foreach ($courses as $course): ?>
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-1 truncate">
                            <div class="flex items-center space-x-3">
                                <h3 class="text-sm font-medium text-gray-900 truncate"><?= htmlspecialchars($course['titre'] ?? '') ?></h3>
                                <!-- <p class="mt-1 text-sm text-gray-500"><?= $course['student_count'] ?> étudiants inscrits</p> -->
                                <a href="<?= base_url('courses/' . $course['id']) ?>"
                                    class="inline-flex items-center px-3 py-2 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <!-- Voir détails -->
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                            <?= htmlspecialchars($course['category_name'] ?? '') ?>
                        </span>
                    </div>
                    <div class="mt-4 flex flex-wrap gap-2">

                        <?php
                        $maxTags = 3;
                        foreach (array_slice($course['tags'], 0, $maxTags) as $tag): ?>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs text-gray-500 hover:text-gray-700 transition-colors">
                                #<?= htmlspecialchars($tag['name'] ?? '') ?>
                            </span>
                        <?php endforeach; ?>
                        <?php if (count($course['tags']) > $maxTags): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                +<?= count($course['tags']) - $maxTags ?>
                            </span>
                        <?php endif; ?>
                    </div>

                    <div class="mt-4 flex space-x-3">
                        <a href="<?= base_url('teacher/edit_course/' . $course['id']) ?>" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Modifier
                        </a>
                        <form action="<?= base_url('teacher/delete_course/' . $course['id']) ?>" method="POST" class="inline">
                            <button type="submit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce cours ?')"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700">
                                Supprimer
                            </button>
                        </form>
                        <a href="<?= base_url('teacher/courses/' . $course['id'] . '/enrollments') ?>"
                            class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            inscriptions
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
    <?php if ($totalPages > 1): ?>
        <div class="mt-8 flex justify-center">
            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                <?php if ($currentPage > 1): ?>
                    <a href="?page=<?= $currentPage - 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium <?= $i === $currentPage ? 'text-indigo-600 bg-indigo-50' : 'text-gray-700 hover:bg-gray-50' ?>">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>

                <?php if ($currentPage < $totalPages): ?>
                    <a href="?page=<?= $currentPage + 1 ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </a>
                <?php endif; ?>
            </nav>
        </div>
    <?php endif; ?>
</div>