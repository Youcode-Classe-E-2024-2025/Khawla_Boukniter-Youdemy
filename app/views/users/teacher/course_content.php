<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Contenu du cours</h1>
        </div>

        <!-- Steps -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button class="border-transparent text-gray-500 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Informations générales
                    </button>
                    <button class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Contenu du cours
                    </button>
                </nav>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <form action="<?= base_url('teacher/courses/store') ?>" method="POST" enctype="multipart/form-data" class="space-y-8 p-8">
                <?php if ($_SESSION['course_data']['content_type'] === 'video'): ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Importer une vidéo
                        </label>
                        <input type="file"
                            name="video_content"
                            accept="video/*"
                            required
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        <p class="mt-2 text-sm text-gray-500">Format MP4 uniquement. Taille maximale: 100MB</p>
                    </div>
                <?php else: ?>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Contenu du document
                        </label>
                        <textarea name="document_content"
                            rows="10"
                            required
                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                    </div>
                <?php endif; ?>

                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="history.back()" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Retour
                    </button>
                    <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Créer le cours
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>