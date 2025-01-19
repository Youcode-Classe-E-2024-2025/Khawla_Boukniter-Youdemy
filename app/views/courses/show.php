<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Course Header -->
        <div class="bg-white shadow overflow-hidden rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between items-start">
                    <div>
                        <h1 class="text-3xl font-bold text-gray-900"><?= htmlspecialchars($course['titre']) ?></h1>
                        <p class="mt-2 text-sm text-gray-500">
                            Par <?= htmlspecialchars($course['teacher_prenom'] . ' ' . $course['teacher_nom']) ?>
                        </p>
                    </div>
                    <div class="flex items-center space-x-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                            <?= htmlspecialchars($course['category_name'] ?? '') ?>
                        </span>
                        <span class="text-sm text-gray-500"><?= $course['student_count'] ?> étudiants inscrits</span>
                    </div>
                </div>
            </div>

            <!-- Course Content -->
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <div class="prose max-w-none">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Description du cours</h2>
                    <p class="text-gray-700"><?= nl2br(htmlspecialchars($course['description'] ?? '')) ?></p>
                </div>

                <!-- Tags -->
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-500">Tags</h3>
                    <div class="mt-2 flex flex-wrap gap-2">
                        <?php foreach ($tags as $tag): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <?= htmlspecialchars($tag['name']) ?>
                            </span>
                        <?php endforeach; ?>
                    </div>
                </div>

                <!-- Course Type -->
                <div class="mt-6">
                    <h3 class="text-sm font-medium text-gray-500">Type de contenu</h3>
                    <p class="mt-2 text-sm text-gray-700">
                        <?= $course['content_type'] === 'video' ? 'Vidéo' : 'Document' ?>
                    </p>
                </div>

                <!-- Course Content Display -->
                <div class="mt-8 border-t border-gray-200 pt-8">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Contenu du cours</h2>

                    <?php if ($course['content_type'] === 'video'): ?>
                        <div class="aspect-w-16 aspect-h-9">
                            <video
                                src="<?= htmlspecialchars($course['content_url']) ?>"
                                class="w-full h-96 rounded-lg shadow-lg"
                                allowfullscreen>
                            </video>
                        </div>
                    <?php elseif ($course['content_type'] === 'document'): ?>
                        <div class="bg-white rounded-lg shadow-lg p-4">
                            <iframe
                                src="<?= base_url($course['content_url']) ?>"
                                class="w-full h-screen"
                                type="application/pdf">
                            </iframe>
                        </div>
                        <?php if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 1): ?>
                            <?php if ($isEnrolled): ?>
                                <button disabled class="inline-flex items-center px-4 py-2 mt-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600">
                                    Déjà inscrit
                                </button>
                            <?php else: ?>
                                <form action="<?= base_url('courses/' . $course['id'] . '/enroll') ?>" method="POST">
                                    <button type="submit" class="inline-flex items-center px-4 py-2 mt-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                        S'inscrire au cours
                                    </button>
                                </form>
                            <?php endif; ?>
                        <?php else: ?>
                            <form action="<?= base_url('login') ?>" method="POST">
                                <button type="submit" class="inline-flex items-center px-4 py-2 mt-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                                    Connectez-vous pour vous inscrire
                                </button>
                            </form>

                        <?php endif; ?>
                    <?php else: ?>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <a href="<?= htmlspecialchars($course['content_url']) ?>"
                                target="_blank"
                                class="inline-flex items-center text-indigo-600 hover:text-indigo-700">
                                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                Aucun contenu disponible pour le moment.
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
</div>