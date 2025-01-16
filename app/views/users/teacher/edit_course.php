<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Modifier le cours</h1>
            <p class="mt-2 text-sm text-gray-600">
                Modifiez les informations de votre cours.
            </p>
        </div>

        <div class="bg-white shadow rounded-lg">
            <?php if ($course): ?>
                <form action="<?= base_url('teacher/edit_course/' . $course['id']) ?>" method="POST" class="space-y-8 divide-y divide-gray-200 p-8">
                    <?= csrf_field() ?>

                    <div class="space-y-8 divide-y">
                        <div>
                            <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                                <div class="sm:col-span-4">
                                    <label for="titre" class="block text-sm font-medium text-gray-700">
                                        Titre du cours *
                                    </label>
                                    <div class="mt-1">
                                        <input type="text"
                                            name="titre"
                                            id="title"
                                            value="<?= $course ? htmlspecialchars($course['titre']) : '' ?>"
                                            required
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
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
                                            class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"><?= $course ? htmlspecialchars($course['description']) : '' ?></textarea>
                                    </div>
                                </div>

                                <div class="sm:col-span-6">
                                    <label class="block text-sm font-medium text-gray-700">Type de contenu *</label>
                                    <div class="mt-1 flex space-x-12">
                                        <div class="flex items-center">
                                            <input type="radio" id="video" name="content_type" value="video"
                                                <?= $course['content_type'] === 'video' ? 'checked' : '' ?>
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                            <label for="video" class="ml-3 block text-sm font-medium text-gray-700">
                                                Vidéo
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="document" name="content_type" value="document"
                                                <?= $course['content_type'] === 'document' ? 'checked' : '' ?>
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                            <label for="document" class="ml-3 block text-sm font-medium text-gray-700">
                                                Document
                                            </label>
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
                                            <?php foreach ($categories as $category): ?>
                                                <option value="<?= $category['id'] ?>" <?= $category['id'] == $course['categorie_id'] ? 'selected' : '' ?>>
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
                                        <div class="relative">
                                            <button type="button"
                                                id="tagSelector"
                                                class="w-full flex items-center justify-between text-left rounded-md border border-gray-200 bg-white px-3 py-2 text-sm">
                                                <span>Sélectionner des tags</span>
                                                <svg class="h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="none" stroke="currentColor">
                                                    <path d="M6 8l4 4 4-4" strokeWidth="1.5" strokeLinecap="round" strokeLinejoin="round" />
                                                </svg>
                                            </button>

                                            <div id="tagsDropdown"
                                                class="absolute z-10 w-full mt-1 hidden bg-white rounded-md shadow-lg max-h-60 overflow-auto border border-gray-200">
                                                <?php foreach ($tags as $tag): ?>
                                                    <div class="tag-option px-3 py-2 cursor-pointer hover:bg-gray-100 text-sm"
                                                        data-value="<?= $tag['id'] ?>"
                                                        data-name="<?= htmlspecialchars($tag['name']) ?>">
                                                        <?= htmlspecialchars($tag['name']) ?>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>

                                        <div id="selectedTags" class="flex flex-wrap gap-2">
                                            <?php foreach ($courseTags as $tag): ?>
                                                <div class="inline-flex items-center gap-1 px-2 py-1 rounded-md text-sm bg-indigo-50 text-indigo-700">
                                                    <?= htmlspecialchars($tag['name']) ?>
                                                    <button type="button" data-value="<?= $tag['id'] ?>" class="hover:text-indigo-900">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>

                                        <select name="tags[]" id="tags" multiple class="hidden" required>
                                            <?php foreach ($tags as $tag): ?>
                                                <option value="<?= $tag['id'] ?>"
                                                    <?php foreach ($courseTags as $courseTag): ?>
                                                    <?= $courseTag['id'] == $tag['id'] ? 'selected' : '' ?>
                                                    <?php endforeach; ?>>
                                                    <?= htmlspecialchars($tag['name']) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5">
                        <div class="flex justify-end">
                            <button type="button" onclick="history.back()"
                                class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50">
                                Annuler
                            </button>
                            <button type="submit"
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                                Mettre à jour
                            </button>
                        </div>
                    </div>
                </form>
            <?php else: ?>
                <div class="text-center py-12">
                    <h2 class="text-2xl font-bold text-gray-900">Cours non trouvé</h2>
                    <p class="mt-2 text-gray-600">Le cours que vous cherchez n'existe pas ou a été supprimé.</p>
                    <a href="<?= base_url('dashboard') ?>" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                        Retour au tableau de bord
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tagSelector = document.getElementById('tagSelector');
        const tagsDropdown = document.getElementById('tagsDropdown');
        const selectedTagsContainer = document.getElementById('selectedTags');
        const hiddenSelect = document.getElementById('tags');
        let selectedTags = new Set(Array.from(hiddenSelect.selectedOptions).map(option => option.value));

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
                    selectedTags.delete(value);
                    option.classList.remove('bg-indigo-50', 'text-indigo-900');
                } else {
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

        // Initialize selected tags
        updateSelectedTags();
    });
</script>