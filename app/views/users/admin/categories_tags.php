<div class="min-h-screen bg-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Categories Section -->
        <div class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Gestion des catégories</h2>
                <button onclick="document.getElementById('addCategoryModal').classList.remove('hidden')"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Ajouter une catégorie
                </button>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre de cours</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($categories as $category): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($category['name']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= $category['course_count'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <form action="<?= base_url('admin/categories/delete/' . $category['id']) ?>"
                                        method="POST" class="inline"
                                        onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
                                        <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tags Section -->
        <div>
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-900">Gestion des tags</h2>
                <button onclick="document.getElementById('addTagsModal').classList.remove('hidden')"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Ajouter des tags
                </button>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg p-6">
                <div class="flex flex-wrap gap-2">
                    <?php foreach ($tags as $tag): ?>
                        <div class="inline-flex items-center bg-gray-100 rounded-full px-3 py-1">
                            <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($tag['name']) ?></span>
                            <form action="<?= base_url('admin/tags/delete/' . $tag['id']) ?>"
                                method="POST" class="inline ml-2"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce tag ?')">
                                <button type="submit" class="text-red-600 hover:text-red-900">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Category Modal -->
    <div id="addCategoryModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ajouter une catégorie</h3>
                <form action="<?= base_url('admin/categories/add') ?>" method="POST">
                    <input type="text" name="name" required
                        class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        placeholder="Nom de la catégorie">
                    <div class="mt-4 flex justify-end">
                        <button type="button" onclick="document.getElementById('addCategoryModal').classList.add('hidden')"
                            class="mr-4 text-gray-600 hover:text-gray-900">Annuler</button>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Add Tags Modal -->
    <div id="addTagsModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 hidden">
        <div class="flex items-center justify-center min-h-screen">
            <div class="bg-white rounded-lg p-8 max-w-md w-full">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Ajouter des tags</h3>
                <form action="<?= base_url('admin/tags/add') ?>" method="POST">
                    <input name="tags" id="tagsInput" class="w-full border-gray-300 rounded-md shadow-sm">
                    <div class="mt-4 flex justify-end">
                        <button type="button" onclick="document.getElementById('addTagsModal').classList.add('hidden')"
                            class="mr-4 text-gray-600 hover:text-gray-900">Annuler</button>
                        <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    var input = document.querySelector('#tagsInput');
    new Tagify(input, {
        delimiters: null,
        maxTags: 10,
        placeholder: "Entrez vos tags"
    });
</script>