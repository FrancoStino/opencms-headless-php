<?php
/** @var array $localeContent */
/** @var array $settings */

// Get slides from Image array (not Slide)
$slides = $localeContent['Image'] ?? [];
$rotationTime = $settings['rotationTime'] ?? 3000;
$showArrows = ($settings['showArrows'] ?? 'true') === 'true';
$showDots = ($settings['showDots'] ?? 'true') === 'true';
$baseUrl = getenv('OPENCMS_SERVER') ?: 'http://172.30.0.5:8080';

// Fix image URLs and structure
foreach ($slides as &$slide) {
    // Image is in Uri.link, not Image.link
    if (isset($slide['Uri']['link'])) {
        $imageUrl = $slide['Uri']['link'];
        // Replace mercury.local with correct server
        $imageUrl = str_replace('http://mercury.local', $baseUrl, $imageUrl);
        $imageUrl = str_replace('https://mercury.local', $baseUrl, $imageUrl);
        // Fix escaped slashes
        $imageUrl = str_replace('\\/', '/', $imageUrl);
        // Handle relative URLs
        if (strpos($imageUrl, 'http') !== 0) {
            $imageUrl = $baseUrl . $imageUrl;
        }
        // Force HTTP
        $imageUrl = str_replace('https://', 'http://', $imageUrl);
        // Use image proxy
        $slide['imageUrl'] = '/image-proxy.php?url=' . urlencode($imageUrl);
    }
    
    // Normalize title structure
    $slide['Title'] = $slide['TitleLine1'] ?? '';
    $slide['Subtitle'] = $slide['TitleLine2'] ?? '';
    $slide['Supertitle'] = $slide['SuperTitle'] ?? '';
}
unset($slide);
?>

<?php if (!empty($slides)): ?>
<section class="relative w-full bg-gray-900 dark:bg-black overflow-hidden">
    <!-- Slider Container -->
    <div class="relative h-[500px] lg:h-[600px]" id="hero-slider">
        <?php foreach ($slides as $index => $slide): ?>
            <div class="slider-slide absolute inset-0 transition-opacity duration-1000 <?php echo $index === 0 ? 'opacity-100' : 'opacity-0'; ?>" 
                 data-slide="<?php echo $index; ?>">
                <!-- Background Image -->
                <?php if (!empty($slide['imageUrl'])): ?>
                    <div class="absolute inset-0">
                        <img src="<?php echo htmlspecialchars($slide['imageUrl']); ?>" 
                             alt="<?php echo htmlspecialchars($slide['Title'] ?? ''); ?>"
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent"></div>
                    </div>
                <?php endif; ?>
                
                <!-- Content -->
                <div class="relative h-full flex items-center">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
                        <div class="max-w-2xl">
                            <?php if (!empty($slide['Supertitle'])): ?>
                                <div class="text-lg lg:text-xl text-blue-400 font-semibold mb-2 animate-fade-in">
                                    <?php echo htmlspecialchars($slide['Supertitle']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['Title'])): ?>
                                <h1 class="text-4xl lg:text-6xl font-bold text-white mb-4 animate-fade-in">
                                    <?php echo htmlspecialchars($slide['Title']); ?>
                                </h1>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['Subtitle'])): ?>
                                <div class="text-xl text-gray-200 mb-8 animate-fade-in-delay">
                                    <?php echo htmlspecialchars($slide['Subtitle']); ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if (!empty($slide['Link']['link'])): ?>
                                <a href="<?php echo htmlspecialchars($slide['Link']['link']); ?>" 
                                   class="inline-block bg-white text-gray-900 font-semibold py-3 px-8 rounded-lg hover:bg-gray-100 transition-colors duration-300 animate-fade-in-delay-2">
                                    <?php echo htmlspecialchars($slide['Link']['text'] ?? 'Learn More'); ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <?php if (count($slides) > 1): ?>
        <!-- Navigation Arrows -->
        <?php if ($showArrows): ?>
            <button onclick="previousSlide()" 
                    class="absolute left-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white p-3 rounded-full transition-all duration-300 z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button onclick="nextSlide()" 
                    class="absolute right-4 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/30 backdrop-blur-sm text-white p-3 rounded-full transition-all duration-300 z-10">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        <?php endif; ?>
        
        <!-- Dots Navigation -->
        <?php if ($showDots): ?>
            <div class="absolute bottom-6 left-1/2 -translate-x-1/2 flex gap-2 z-10">
                <?php foreach ($slides as $index => $slide): ?>
                    <button onclick="goToSlide(<?php echo $index; ?>)" 
                            class="slider-dot w-3 h-3 rounded-full transition-all duration-300 <?php echo $index === 0 ? 'bg-white w-8' : 'bg-white/50'; ?>"
                            data-dot="<?php echo $index; ?>">
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>

<style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.8s ease-out;
    }
    .animate-fade-in-delay {
        animation: fadeIn 0.8s ease-out 0.2s both;
    }
    .animate-fade-in-delay-2 {
        animation: fadeIn 0.8s ease-out 0.4s both;
    }
</style>

<script>
    let currentSlide = 0;
    const totalSlides = <?php echo count($slides); ?>;
    const rotationTime = <?php echo $settings['rotationTime'] ?? 5000; ?>;
    let autoplayInterval;
    
    function showSlide(index) {
        const slides = document.querySelectorAll('.slider-slide');
        const dots = document.querySelectorAll('.slider-dot');
        
        slides.forEach((slide, i) => {
            if (i === index) {
                slide.classList.remove('opacity-0');
                slide.classList.add('opacity-100');
            } else {
                slide.classList.remove('opacity-100');
                slide.classList.add('opacity-0');
            }
        });
        
        dots.forEach((dot, i) => {
            if (i === index) {
                dot.classList.add('bg-white', 'w-8');
                dot.classList.remove('bg-white/50');
            } else {
                dot.classList.remove('bg-white', 'w-8');
                dot.classList.add('bg-white/50');
            }
        });
        
        currentSlide = index;
    }
    
    function nextSlide() {
        const next = (currentSlide + 1) % totalSlides;
        showSlide(next);
        resetAutoplay();
    }
    
    function previousSlide() {
        const prev = (currentSlide - 1 + totalSlides) % totalSlides;
        showSlide(prev);
        resetAutoplay();
    }
    
    function goToSlide(index) {
        showSlide(index);
        resetAutoplay();
    }
    
    function startAutoplay() {
        if (totalSlides > 1) {
            autoplayInterval = setInterval(nextSlide, rotationTime);
        }
    }
    
    function resetAutoplay() {
        clearInterval(autoplayInterval);
        startAutoplay();
    }
    
    // Start autoplay on load
    startAutoplay();
    
    // Pause on hover
    <?php if (($settings['pauseOnHover'] ?? 'true') === 'true'): ?>
    document.getElementById('hero-slider').addEventListener('mouseenter', () => {
        clearInterval(autoplayInterval);
    });
    document.getElementById('hero-slider').addEventListener('mouseleave', () => {
        startAutoplay();
    });
    <?php endif; ?>
</script>
<?php endif; ?>
