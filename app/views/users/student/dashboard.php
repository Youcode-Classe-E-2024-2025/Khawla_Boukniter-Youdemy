<div class="min-h-screen bg-gradient-to-b from-gray-50 to-gray-100 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Animated Welcome Header -->
        <div class="text-center mb-12 animate-fade-in">
            <h1 class="text-4xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-purple-500">
                Bienvenue <?= $_SESSION['user_name'] ?>
            </h1>
            <p class="mt-3 text-lg text-gray-600">
                Prêt à continuer votre apprentissage ?
            </p>
        </div>

        <!-- Stats Cards with Hover Effects -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 mb-12">
            <!-- Enrolled Courses Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-indigo-50">
                            <svg class="h-8 w-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold text-gray-900">Cours inscrits</h3>
                            <p class="text-3xl font-bold text-indigo-600">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Completed Courses Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-green-50">
                            <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold text-gray-900">Cours complétés</h3>
                            <p class="text-3xl font-bold text-green-600">0</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Card -->
            <div class="bg-white rounded-xl shadow-md overflow-hidden transform transition duration-300 hover:scale-105 hover:shadow-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="p-3 rounded-full bg-purple-50">
                            <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                            </svg>
                        </div>
                        <div class="ml-6">
                            <h3 class="text-lg font-semibold text-gray-900">En cours</h3>
                            <p class="text-3xl font-bold text-purple-600">0</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Courses Section -->
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <div class="px-6 py-5 border-b border-gray-200">
                <div class="flex items-center">
                    <h3 class="text-xl font-bold text-gray-900">Mes cours récents</h3>
                    <span class="ml-3 inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-indigo-100 text-indigo-800">
                        Nouveau
                    </span>
                </div>
            </div>
            <div class="divide-y divide-gray-200">
                <!-- Empty state -->
                <div class="p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">Pas de cours</h3>
                    <p class="mt-1 text-sm text-gray-500">Commencez à explorer nos cours disponibles.</p>
                    <div class="mt-6">
                        <a href="<?= base_url('browse') ?>" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            Explorer les cours
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>