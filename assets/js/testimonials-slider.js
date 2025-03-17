document.addEventListener('DOMContentLoaded', function() {
    // Simple testimonial slider
    const testimonials = document.querySelectorAll('.testimonial-item');
    const dots = document.querySelectorAll('.testimonial-dots .dot');
    let currentIndex = 0;

    function showTestimonial(index) {
        testimonials.forEach(item => item.style.display = 'none');
        dots.forEach(dot => dot.classList.remove('active'));
        
        testimonials[index].style.display = 'block';
        dots[index].classList.add('active');
    }

    dots.forEach((dot, index) => {
        dot.addEventListener('click', () => {
            currentIndex = index;
            showTestimonial(currentIndex);
        });
    });

    // Auto-rotate testimonials
    let testimonialInterval = setInterval(() => {
        currentIndex = (currentIndex + 1) % testimonials.length;
        showTestimonial(currentIndex);
    }, 5000);

    // Pause on hover
    const testimonialContainer = document.querySelector('.testimonials-slider');
    if (testimonialContainer) {
        testimonialContainer.addEventListener('mouseenter', () => {
            clearInterval(testimonialInterval);
        });
        
        testimonialContainer.addEventListener('mouseleave', () => {
            testimonialInterval = setInterval(() => {
                currentIndex = (currentIndex + 1) % testimonials.length;
                showTestimonial(currentIndex);
            }, 5000);
        });
    }

    // Initialize
    showTestimonial(currentIndex);
}); 