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
            <form action="<?= base_url('teacher/courses/save_step1') ?>" method="POST" id="courseForm" class="space-y-8 p-8">
                <?php error_log("Form action URL: " . base_url('teacher/courses/save_step1')); ?>
                <?= csrf_field() ?>

                <div class="space-y-8 divide-y ">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Informations de base</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Ces informations seront affichées publiquement sur la page de votre cours.
                        </p>
                    </div>

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
                                <div class="flex items-center">
                                    <label class="block text-sm font-medium text-gray-700">
                                        Type de contenu *
                                    </label>
                                    <div class="ml-8 flex space-x-12">
                                        <div class="flex items-center">
                                            <input type="radio" id="video" name="content_type" value="video" required
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                            <label for="video" class="ml-3 block text-sm font-medium text-gray-700">
                                                Vidéo
                                            </label>
                                        </div>
                                        <div class="flex items-center">
                                            <input type="radio" id="document" name="content_type" value="document" required
                                                class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300">
                                            <label for="document" class="ml-3 block text-sm font-medium text-gray-700">
                                                Document
                                            </label>
                                        </div>
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
                                    <select name="tags[]" id="tags" multiple class="hidden">
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
                </div>

                <!-- Error messages section -->
                <div class="pt-5 border-t border-gray-200">
                    <?php if (isset($_SESSION['errors'])): ?>
                        <div class="rounded-md bg-red-50 p-4 mb-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Des erreurs ont été trouvées</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc pl-5 space-y-1">
                                            <?php foreach ($_SESSION['errors'] as $error): ?>
                                                <li><?= $error ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
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

<?php
unset($_SESSION['errors']);
unset($_SESSION['old']);
?>