<?php
/** @var \App\Model\Page $page */

use App\Model\Component\Paragraph;
?>

<section class="max-w-4xl mx-auto">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Contact Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
            <div class="mb-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-blue-500 rounded-full flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        <?php echo htmlspecialchars($page->title); ?>
                    </h1>
                </div>
                
                <?php if (!empty($page->intro)): ?>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">
                        <?php echo htmlspecialchars($page->intro); ?>
                    </p>
                <?php endif; ?>
            </div>

            <div class="space-y-6">
                <?php foreach ($page->components as $component): ?>
                    <?php if ($component instanceof Paragraph): ?>
                        <?php if (!empty($component->caption)): ?>
                            <div class="border-l-4 border-blue-500 pl-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">
                                    <?php echo htmlspecialchars($component->caption); ?>
                                </h3>
                                <?php if (!empty($component->text)): ?>
                                    <div class="text-gray-700 dark:text-gray-300">
                                        <?php echo $component->text; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php elseif (!empty($component->text)): ?>
                            <div class="text-gray-700 dark:text-gray-300">
                                <?php echo $component->text; ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
            <h2 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-6">
                Send a Message
            </h2>
            
            <form class="space-y-4" method="POST" action="?action=contact">
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Your Name *
                    </label>
                    <input 
                        type="text" 
                        id="name" 
                        name="name" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="John Doe"
                    >
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Your Email *
                    </label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="john@example.com"
                    >
                </div>

                <div>
                    <label for="subject" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Subject *
                    </label>
                    <input 
                        type="text" 
                        id="subject" 
                        name="subject" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="How can we help?"
                    >
                </div>

                <div>
                    <label for="message" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Message *
                    </label>
                    <textarea 
                        id="message" 
                        name="message" 
                        rows="5" 
                        required
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"
                        placeholder="Your message here..."
                    ></textarea>
                </div>

                <button 
                    type="submit"
                    class="w-full bg-gradient-to-r from-blue-500 to-purple-500 hover:from-blue-600 hover:to-purple-600 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300 transform hover:scale-[1.02] focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                >
                    Send Message
                </button>
            </form>
        </div>
    </div>
</section>
