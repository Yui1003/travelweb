/**
 * Main Script for Wanderlust Travel & Tourism Website
 */

document.addEventListener('DOMContentLoaded', function() {
    // Enable Bootstrap tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Package booking form functionality
    const bookingForm = document.getElementById('bookingForm');
    if (bookingForm) {
        const travelersInput = document.getElementById('numTravelers');
        const travelDateInput = document.getElementById('travelDate');
        const pricePerPersonEl = document.getElementById('pricePerPerson');
        const totalPriceEl = document.getElementById('totalPrice');
        
        // Set minimum date for travel date input
        const today = new Date();
        const tomorrow = new Date(today);
        tomorrow.setDate(tomorrow.getDate() + 1);
        const tomorrowFormatted = tomorrow.toISOString().split('T')[0];
        travelDateInput.setAttribute('min', tomorrowFormatted);
        
        function calculateTotal() {
            if (travelersInput && pricePerPersonEl && totalPriceEl) {
                const pricePerPerson = parseFloat(pricePerPersonEl.dataset.price);
                const numTravelers = parseInt(travelersInput.value);
                const totalPrice = pricePerPerson * numTravelers;
                
                totalPriceEl.textContent = '₱' + totalPrice.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                });
                
                // Update hidden total price input
                document.getElementById('totalPriceInput').value = totalPrice;
            }
        }
        
        // Calculate total on input change
        if (travelersInput) {
            travelersInput.addEventListener('input', calculateTotal);
            // Initial calculation
            calculateTotal();
        }
    }
    
    // Search destination functionality
    const searchForm = document.querySelector('form[action="destinations.php"]');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            const searchInput = this.querySelector('input[name="search"]');
            if (searchInput && searchInput.value.trim() === '') {
                e.preventDefault();
                alert('Please enter a destination to search.');
            }
        });
    }
});