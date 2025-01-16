<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- En-tête -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Créer un nouveau cours</h1>
            <p class="mt-2 text-sm text-gray-600">
                Commencez par remplir les informations générales de votre cours.
            </p>
        </div>

        <!-- Étapes de création -->
        <div class="mb-8">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Informations générales
                    </button>
                    <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" disabled>
                        Contenu du cours
                    </button>
                </nav>
            </div>
        </div>

        <div class="bg-white shadow rounded-lg">
            <form action="<?= base_url('teacher/courses/store') ?>" method="POST" class="space-y-8 divide-y divide-gray-200 p-8">
                <?= csrf_field() ?>

                <div class="space-y-8 divide-y ">
                    <div>
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Informations de base</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                Ces informations seront affichées publiquement sur la page de votre cours.
                            </p>
                        </div>

                        <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <div class="sm:col-span-4">
                                <label for="titre" class="block text-sm font-medium text-gray-700">
                                    Titre du cours *
                                </label>
                                <div class="mt-1">
                                    <input type="text"
                                        name="titre"
                                        id="title"
                                        required
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    Un titre clair et accrocheur qui décrit votre cours (5-60 caractères)
                                </p>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="description" class="block text-sm font-medium text-gray-700">
                                    Description *
                                </label>
                                <div class="mt-1">
                                    <textarea id="description"
                                        name="description"
                                        rows="4"
                                        required
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">
                                    Une description détaillée qui explique ce que les étudiants apprendront (20-1000 caractères)
                                </p>
                            </div>

                            <div class="sm:col-span-6">
                                <label for="content_type" class="block text-sm font-medium text-gray-700">
                                    Type de contenu *
                                </label>
                                <div class="mt-1">
                                    <select id="content_type"
                                        name="content_type"
                                        required
                                        class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                        <option value="">Sélectionnez un type de contenu</option>
                                        <option value="video">Vidéo</option>
                                        <option value="document">Document</option>
                                    </select>
                                </div>
                                <div class="sm:col-span-6">
                                    <label for="content_url" class="block text-sm font-medium text-gray-700">
                                        URL du contenu *
                                    </label>
                                    <div class="mt-1">
                                        <input type="text" name="content_url" id="content_url" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" required>
                                    </div>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="categorie_id" class="block text-sm font-medium text-gray-700">
                                    Catégorie *
                                </label>
                                <div class="mt-1">
                                    <select id="category"
                                        name="categorie_id"
                                        required
                                        class="shadow-sm h-[2.3rem] focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border border-gray-200 rounded-md">
                                        <option value="">Sélectionnez une catégorie</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?= $category['id'] ?>">
                                                <?= htmlspecialchars($category['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                            <div class="sm:col-span-3">
                                <label for="tags" class="block text-sm font-medium text-gray-700">
                                    Tags *
                                </label>

                                <div class="mt-1 space-y-2">
                                    <!-- Custom tag selector -->
                                    <div class="relative">
                                        <button
                                            type="button"
                                            id="tagSelector"
                                            class="w-full flex items-center justify-between text-left rounded-md border border-gray-200 bg-white px-3 py-2 text-sm "
                                            aria-haspopup="listbox">
                                            <span>Sélectionner des tags</span>
                                            <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                                <path d="M6 8l4 4 4-4" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                            </svg>
                                        </button>

                                        <!-- Tags dropdown -->
                                        <div
                                            id="tagsDropdown"
                                            class="absolute z-10 w-full mt-1 hidden bg-white rounded-md shadow-lg max-h-60 overflow-auto border border-gray-200"
                                            role="listbox">
                                            <?php foreach ($tags as $tag): ?>
                                                <div
                                                    class="tag-option px-3 py-2 cursor-pointer hover:bg-gray-100 text-sm"
                                                    data-value="<?= $tag['id'] ?>"
                                                    data-name="<?= htmlspecialchars($tag['name']) ?>">
                                                    <?= htmlspecialchars($tag['name']) ?>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <!-- Selected tags -->
                                    <div id="selectedTags" class="flex flex-wrap gap-2"></div>

                                    <!-- Hidden select for form submission -->
                                    <select name="tags[]" id="tags" multiple class="hidden" required>
                                        <?php foreach ($tags as $tag): ?>
                                            <option value="<?= $tag['id'] ?>">
                                                <?= htmlspecialchars($tag['name']) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="pt-8">
                        <div class="sm:col-span-6">
                            <label class="block text-sm font-medium text-gray-700">
                                Vidéo de présentation
                            </label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-md">
                                <div class="space-y-1 text-center">
                                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                    </svg>
                                    <div class="flex text-sm text-gray-600">
                                        <label for="preview_video" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span>Télécharger une vidéo</span>
                                            <input id="preview_video" name="preview_video" type="file" class="sr-only" accept="video/*">
                                        </label>
                                    </div>
                                    <p class="text-xs text-gray-500">
                                        MP4 jusqu'à 100MB
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>

                <!-- Boutons de navigation -->
                <div class="pt-5">
                    <div class="flex justify-end">
                        <button type="button"

                            class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <a href="<?= base_url('dashboard') ?>">Annuler</a>
                        </button>
                        <button type="submit"
                            name="action"
                            value="next"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Continuer vers le contenu
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tagSelector = document.getElementById('tagSelector');
        const tagsDropdown = document.getElementById('tagsDropdown');
        const selectedTagsContainer = document.getElementById('selectedTags');
        const hiddenSelect = document.getElementById('tags');
        let selectedTags = new Set();

        // Toggle dropdown
        tagSelector.addEventListener('click', () => {
            tagsDropdown.classList.toggle('hidden');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!tagSelector.contains(e.target) && !tagsDropdown.contains(e.target)) {
                tagsDropdown.classList.add('hidden');
            }
        });

        // Handle tag selection
        document.querySelectorAll('.tag-option').forEach(option => {
            option.addEventListener('click', () => {
                const value = option.dataset.value;
                const name = option.dataset.name;

                if (selectedTags.has(value)) {
                    // Remove tag
                    selectedTags.delete(value);
                    option.classList.remove('bg-indigo-50', 'text-indigo-900');
                } else {
                    // Add tag
                    selectedTags.add(value);
                    option.classList.add('bg-indigo-50', 'text-indigo-900');
                }

                updateSelectedTags();
                updateHiddenSelect();
            });
        });

        function updateSelectedTags() {
            selectedTagsContainer.innerHTML = '';
            document.querySelectorAll('.tag-option').forEach(option => {
                if (selectedTags.has(option.dataset.value)) {
                    const tag = document.createElement('div');
                    tag.className = 'inline-flex items-center gap-1 px-2 py-1 rounded-md text-sm bg-indigo-50 text-indigo-700';
                    tag.innerHTML = `
                    ${option.dataset.name}
                    <button type="button" data-value="${option.dataset.value}" class="hover:text-indigo-900">
                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                `;
                    selectedTagsContainer.appendChild(tag);

                    tag.querySelector('button').addEventListener('click', (e) => {
                        const value = e.currentTarget.dataset.value;
                        selectedTags.delete(value);
                        document.querySelector(`.tag-option[data-value="${value}"]`)
                            .classList.remove('bg-indigo-50', 'text-indigo-900');
                        updateSelectedTags();
                        updateHiddenSelect();
                    });
                }
            });

            tagSelector.firstElementChild.textContent =
                selectedTags.size > 0 ? `${selectedTags.size} tag(s) sélectionné(s)` : 'Sélectionner des tags';
        }

        function updateHiddenSelect() {
            Array.from(hiddenSelect.options).forEach(option => {
                option.selected = selectedTags.has(option.value);
            });
        }
    });
</script>