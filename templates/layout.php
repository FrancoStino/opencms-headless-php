<!DOCTYPE html>
<html lang="<?php echo $locale ?? 'en'; ?>" class="scroll-smooth">
<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="icon" href="data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 viewBox=%220 0 100 100%22><text y=%22.9em%22 font-size=%2290%22>🚀</text></svg>">

		<!-- Compiled CSS (Tailwind + Custom Styles) -->
		<!-- To rebuild: npm run build:css or npx tailwindcss -i ./css/input.css -o ./public/dist/output.css --minify -->
		<link rel="stylesheet" href="/dist/output.css">

	</head>
	<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 min-h-screen flex flex-col">
		<!-- Header -->
		<header class="bg-white dark:bg-gray-800 shadow-sm sticky top-0 z-50">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
				<div class="flex justify-between items-center">
					<a href="/" class="flex items-center gap-3 hover:opacity-80 transition-opacity">
						<div class="text-3xl">🚀</div>
						<div>
							<h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">OpenCms Headless</h1>
							<p class="text-sm text-gray-500 dark:text-gray-400">Demo Application</p>
						</div>
					</a>

					<div class="flex items-center gap-6">
						<!-- Navigation Menu -->
						<nav class="hidden md:flex items-center gap-6">
							<a href="/" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">
								Home
							</a>
							<a href="/contatti" class="text-gray-700 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 font-medium transition-colors">
								Contatti
							</a>
						</nav>

						<!-- Dark Mode Toggle -->
						<button onclick="toggleDarkMode()" class="p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" aria-label="Toggle dark mode">
							<svg class="w-5 h-5 hidden dark:block" fill="currentColor" viewBox="0 0 20 20">
								<path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 000 2h1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/>
							</svg>
							<svg class="w-5 h-5 dark:hidden" fill="currentColor" viewBox="0 0 20 20">
								<path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/>
							</svg>
						</button>

						<!-- Mobile Menu Toggle -->
						<button onclick="toggleMobileMenu()" class="md:hidden p-2 rounded-lg bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 transition-colors" aria-label="Toggle menu">
							<svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
								<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
							</svg>
						</button>
				</div>

				<!-- Mobile Menu -->
				<div id="mobile-menu" class="hidden md:hidden pb-4 space-y-2">
					<a href="/" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
						Home
					</a>
					<a href="/contatti" class="block px-4 py-2 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg transition-colors">
						Contatti
					</a>
				</div>
			</div>
		</header>

		<!-- Main Content -->
		<main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
			<?php require $view; ?>
		</main>

		<!-- Footer -->
		<footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-auto">
			<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
				<div class="flex flex-col md:flex-row justify-between items-center gap-4">
					<p class="text-sm text-gray-600 dark:text-gray-400">
						&copy; <?php echo date ( 'Y' ); ?> OpenCms Headless Demo. Built with PHP & TailwindCSS.
					</p>
					<div class="flex gap-4 text-sm text-gray-600 dark:text-gray-400">
						<a href="https://documentation.opencms.org/" target="_blank" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
							Documentation
						</a>
						<span>•</span>
						<a href="https://github.com/alkacon/opencms-core" target="_blank" class="hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
							GitHub
						</a>
					</div>
				</div>
			</div>
		</footer>

		<!-- Custom JavaScript -->
		<script src="/js/app.js" defer></script>
	</body>
</html>
