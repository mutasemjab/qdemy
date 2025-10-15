<script>
    // Simple animation for category cards
    document.addEventListener('DOMContentLoaded', function() {
        const cards = document.querySelectorAll('.category-card, .subcategory-card, .file-card');
        
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });

        // Add success animation after registration
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('success') === 'registered') {
            console.log('Registration successful!');
        }
    });

    // Form validation
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let hasError = false;

            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    field.style.borderColor = '#dc3545';
                    hasError = true;
                } else {
                    field.style.borderColor = '#e1e5e9';
                }
            });

            if (hasError) {
                e.preventDefault();
                alert('يرجى ملء جميع الحقول المطلوبة');
            }
        });
    }
</script>