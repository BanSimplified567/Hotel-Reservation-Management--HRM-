<?php
// app/views/public/gallery.php
// Note: $page_title is passed from controller
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-secondary text-white py-12">
  <div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold mb-2">Photo Gallery</h1>
    <p class="text-xl text-gray-100">Take a virtual tour of our beautiful hotel and facilities</p>
  </div>
</section>

<!-- Gallery Section -->
<section class="py-16">
  <div class="container mx-auto px-4">
    <!-- Gallery Filter -->
    <div class="flex flex-wrap justify-center gap-4 mb-12">
      <button class="filter-btn active px-6 py-2 bg-primary text-white rounded-lg font-semibold hover:bg-primary/90 transition duration-300" data-filter="all">
        All
      </button>
      <button class="filter-btn px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition duration-300" data-filter="rooms">
        Rooms
      </button>
      <button class="filter-btn px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition duration-300" data-filter="restaurant">
        Restaurant
      </button>
      <button class="filter-btn px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition duration-300" data-filter="spa">
        Spa
      </button>
      <button class="filter-btn px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition duration-300" data-filter="pool">
        Pool
      </button>
      <button class="filter-btn px-6 py-2 bg-gray-200 text-gray-700 rounded-lg font-semibold hover:bg-gray-300 transition duration-300" data-filter="events">
        Events
      </button>
    </div>

    <!-- Gallery Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="galleryGrid">
      <!-- Room Images -->
      <div class="gallery-item rooms overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300 cursor-pointer" data-category="rooms">
        <div class="h-64 bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
          <i class="fas fa-bed text-white text-6xl"></i>
        </div>
        <div class="p-4 bg-white">
          <h3 class="font-semibold text-gray-800">Luxury Suite</h3>
          <p class="text-sm text-gray-600">Elegant and spacious</p>
        </div>
      </div>

      <div class="gallery-item rooms overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300 cursor-pointer" data-category="rooms">
        <div class="h-64 bg-gradient-to-br from-secondary to-primary flex items-center justify-center">
          <i class="fas fa-bed text-white text-6xl"></i>
        </div>
        <div class="p-4 bg-white">
          <h3 class="font-semibold text-gray-800">Deluxe Room</h3>
          <p class="text-sm text-gray-600">Modern comfort</p>
        </div>
      </div>

      <div class="gallery-item rooms overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300 cursor-pointer" data-category="rooms">
        <div class="h-64 bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
          <i class="fas fa-bed text-white text-6xl"></i>
        </div>
        <div class="p-4 bg-white">
          <h3 class="font-semibold text-gray-800">Standard Room</h3>
          <p class="text-sm text-gray-600">Cozy and comfortable</p>
        </div>
      </div>

      <!-- Restaurant Images -->
      <div class="gallery-item restaurant overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300 cursor-pointer" data-category="restaurant">
        <div class="h-64 bg-gradient-to-br from-yellow-400 to-orange-500 flex items-center justify-center">
          <i class="fas fa-utensils text-white text-6xl"></i>
        </div>
        <div class="p-4 bg-white">
          <h3 class="font-semibold text-gray-800">Fine Dining</h3>
          <p class="text-sm text-gray-600">Exquisite cuisine</p>
        </div>
      </div>

      <div class="gallery-item restaurant overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300 cursor-pointer" data-category="restaurant">
        <div class="h-64 bg-gradient-to-br from-orange-500 to-yellow-400 flex items-center justify-center">
          <i class="fas fa-wine-glass text-white text-6xl"></i>
        </div>
        <div class="p-4 bg-white">
          <h3 class="font-semibold text-gray-800">Bar & Lounge</h3>
          <p class="text-sm text-gray-600">Relax and unwind</p>
        </div>
      </div>

      <!-- Spa Images -->
      <div class="gallery-item spa overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300 cursor-pointer" data-category="spa">
        <div class="h-64 bg-gradient-to-br from-green-400 to-teal-500 flex items-center justify-center">
          <i class="fas fa-spa text-white text-6xl"></i>
        </div>
        <div class="p-4 bg-white">
          <h3 class="font-semibold text-gray-800">Spa & Wellness</h3>
          <p class="text-sm text-gray-600">Relaxation and rejuvenation</p>
        </div>
      </div>

      <!-- Pool Images -->
      <div class="gallery-item pool overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300 cursor-pointer" data-category="pool">
        <div class="h-64 bg-gradient-to-br from-blue-400 to-cyan-500 flex items-center justify-center">
          <i class="fas fa-swimming-pool text-white text-6xl"></i>
        </div>
        <div class="p-4 bg-white">
          <h3 class="font-semibold text-gray-800">Swimming Pool</h3>
          <p class="text-sm text-gray-600">Outdoor pool area</p>
        </div>
      </div>

      <div class="gallery-item pool overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300 cursor-pointer" data-category="pool">
        <div class="h-64 bg-gradient-to-br from-cyan-500 to-blue-400 flex items-center justify-center">
          <i class="fas fa-umbrella-beach text-white text-6xl"></i>
        </div>
        <div class="p-4 bg-white">
          <h3 class="font-semibold text-gray-800">Poolside Bar</h3>
          <p class="text-sm text-gray-600">Refreshments by the pool</p>
        </div>
      </div>

      <!-- Events Images -->
      <div class="gallery-item events overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300 cursor-pointer" data-category="events">
        <div class="h-64 bg-gradient-to-br from-purple-400 to-pink-500 flex items-center justify-center">
          <i class="fas fa-glass-cheers text-white text-6xl"></i>
        </div>
        <div class="p-4 bg-white">
          <h3 class="font-semibold text-gray-800">Event Hall</h3>
          <p class="text-sm text-gray-600">Perfect for celebrations</p>
        </div>
      </div>

      <div class="gallery-item events overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300 cursor-pointer" data-category="events">
        <div class="h-64 bg-gradient-to-br from-pink-500 to-purple-400 flex items-center justify-center">
          <i class="fas fa-users text-white text-6xl"></i>
        </div>
        <div class="p-4 bg-white">
          <h3 class="font-semibold text-gray-800">Conference Room</h3>
          <p class="text-sm text-gray-600">Business meetings</p>
        </div>
      </div>

      <div class="gallery-item rooms overflow-hidden rounded-lg shadow-md hover:shadow-xl transition duration-300 cursor-pointer" data-category="rooms">
        <div class="h-64 bg-gradient-to-br from-indigo-400 to-purple-500 flex items-center justify-center">
          <i class="fas fa-couch text-white text-6xl"></i>
        </div>
        <div class="p-4 bg-white">
          <h3 class="font-semibold text-gray-800">Presidential Suite</h3>
          <p class="text-sm text-gray-600">Ultimate luxury</p>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
  // Gallery filter functionality
  document.addEventListener('DOMContentLoaded', function() {
    const filterButtons = document.querySelectorAll('.filter-btn');
    const galleryItems = document.querySelectorAll('.gallery-item');

    filterButtons.forEach(button => {
      button.addEventListener('click', function() {
        const filter = this.getAttribute('data-filter');

        // Update active button
        filterButtons.forEach(btn => {
          btn.classList.remove('active', 'bg-primary', 'text-white');
          btn.classList.add('bg-gray-200', 'text-gray-700');
        });
        this.classList.add('active', 'bg-primary', 'text-white');
        this.classList.remove('bg-gray-200', 'text-gray-700');

        // Filter items
        galleryItems.forEach(item => {
          if (filter === 'all' || item.getAttribute('data-category') === filter) {
            item.style.display = 'block';
            setTimeout(() => {
              item.style.opacity = '1';
              item.style.transform = 'scale(1)';
            }, 10);
          } else {
            item.style.opacity = '0';
            item.style.transform = 'scale(0.8)';
            setTimeout(() => {
              item.style.display = 'none';
            }, 300);
          }
        });
      });
    });
  });
</script>

<style>
  .gallery-item {
    transition: all 0.3s ease;
  }
</style>
