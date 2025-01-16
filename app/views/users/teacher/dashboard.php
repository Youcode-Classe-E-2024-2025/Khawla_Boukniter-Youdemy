<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-2xl font-semibold text-gray-900">Tableau de bord Enseignant</h1>

        <!-- Statistiques -->
        <div class="mt-6 grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total des cours</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['total_courses'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total des étudiants</dt>
                                <dd class="text-lg font-medium text-gray-900"><?= $stats['total_students'] ?></dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des cours -->
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
                                        <p class="mt-1 text-sm text-gray-500"><?= $course['student_count'] ?> étudiants inscrits</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2 mt-2">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                    <?= htmlspecialchars($course['category_name'] ?? '') ?>
                                </span>
                            </div>
                            <div class="flex flex-wrap gap-1.5 mt-2">
                                <?php
                                if (!empty($course['tag_names'])) {
                                    $tags = explode(',', $course['tag_names']);
                                    $displayTags = array_slice($tags, 0, 3);
                                    foreach ($displayTags as $tag):
                                ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-xs text-gray-500 hover:text-gray-700 transition-colors">
                                            #<?= htmlspecialchars(trim($tag)) ?>
                                        </span>
                                    <?php
                                    endforeach;
                                    if (count($tags) > 3):
                                    ?>
                                        <span class="text-xs text-gray-400">...</span>
                                <?php
                                    endif;
                                }
                                ?>
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
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>