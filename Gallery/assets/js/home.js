// Select the scroll container
const scrollContainer = document.querySelector('.scroll-container');

// Scroll to the left
function scrollLeft() {
  scrollContainer.scrollBy({ left: -300, behavior: 'smooth' });
}

// Scroll to the right
function scrollRight() {
  scrollContainer.scrollBy({ left: 300, behavior: 'smooth' });
}

// Attach these functions to buttons (if you add them)
