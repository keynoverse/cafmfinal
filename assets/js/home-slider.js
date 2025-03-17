document.addEventListener('DOMContentLoaded', function() {
    // Slider configuration
    const sliderConfig = {
        images: [
            'assets/images/facility-management-1.jpg',
            'assets/images/facility-management-2.jpg',
            'assets/images/facility-management-3.jpg'
        ],
        interval: 5000, // 5 seconds between slides
        transition: 1000 // 1 second transition
    };

    // Initialize the slider
    initSlider(sliderConfig);
});

function initSlider(config) {
    const heroSection = document.querySelector('.hero');
    if (!heroSection) return;

    // Create slider container
    const sliderContainer = document.createElement('div');
    sliderContainer.className = 'hero-slider';
    
    // Create slides
    config.images.forEach((image, index) => {
        const slide = document.createElement('div');
        slide.className = `slide ${index === 0 ? 'active' : ''}`;
        slide.style.backgroundImage = `url('${image}')`;
        sliderContainer.appendChild(slide);
    });
    
    // Create slider controls
    const controls = document.createElement('div');
    controls.className = 'slider-controls';
    
    config.images.forEach((_, index) => {
        const dot = document.createElement('div');
        dot.className = `slider-dot ${index === 0 ? 'active' : ''}`;
        dot.dataset.slide = index;
        dot.addEventListener('click', () => {
            goToSlide(index);
        });
        controls.appendChild(dot);
    });
    
    // Insert elements into the DOM
    heroSection.insertBefore(sliderContainer, heroSection.firstChild);
    heroSection.appendChild(controls);
    
    // Set up automatic sliding
    let currentSlide = 0;
    let slideInterval = setInterval(nextSlide, config.interval);
    
    function nextSlide() {
        goToSlide((currentSlide + 1) % config.images.length);
    }
    
    function goToSlide(index) {
        // Update slides
        const slides = document.querySelectorAll('.slide');
        slides[currentSlide].classList.remove('active');
        slides[index].classList.add('active');
        
        // Update dots
        const dots = document.querySelectorAll('.slider-dot');
        dots[currentSlide].classList.remove('active');
        dots[index].classList.add('active');
        
        // Update current slide index
        currentSlide = index;
        
        // Reset interval
        clearInterval(slideInterval);
        slideInterval = setInterval(nextSlide, config.interval);
    }
    
    // Pause slider on hover
    heroSection.addEventListener('mouseenter', () => {
        clearInterval(slideInterval);
    });
    
    heroSection.addEventListener('mouseleave', () => {
        slideInterval = setInterval(nextSlide, config.interval);
    });
} 