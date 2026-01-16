<?php
// app/views/public/amenities.php
// Note: $amenities, $page_title are passed from controller
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-secondary text-white py-12">
  <div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold mb-2">Hotel Amenities</h1>
    <p class="text-xl text-gray-100">Discover the world-class facilities and services we offer</p>
  </div>
</section>

<!-- Amenities Grid -->
<section class="py-16">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
      <!-- WiFi -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-wifi text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Free WiFi</h3>
        <p class="text-gray-600">High-speed internet access available throughout the hotel</p>
      </div>

      <!-- Swimming Pool -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-swimming-pool text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Swimming Pool</h3>
        <p class="text-gray-600">Outdoor pool with poolside bar and lounge area</p>
      </div>

      <!-- Fitness Center -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-dumbbell text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Fitness Center</h3>
        <p class="text-gray-600">State-of-the-art gym equipment available 24/7</p>
      </div>

      <!-- Spa -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-spa text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Spa & Wellness</h3>
        <p class="text-gray-600">Relax and rejuvenate with our premium spa services</p>
      </div>

      <!-- Restaurant -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-utensils text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Fine Dining</h3>
        <p class="text-gray-600">Multiple restaurants offering international and local cuisine</p>
      </div>

      <!-- Room Service -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-concierge-bell text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">24/7 Room Service</h3>
        <p class="text-gray-600">Round-the-clock room service for your convenience</p>
      </div>

      <!-- Parking -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-parking text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Free Parking</h3>
        <p class="text-gray-600">Complimentary parking available for all guests</p>
      </div>

      <!-- Business Center -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-briefcase text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Business Center</h3>
        <p class="text-gray-600">Fully equipped business center with meeting rooms</p>
      </div>

      <!-- Laundry -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-tshirt text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Laundry Service</h3>
        <p class="text-gray-600">Professional laundry and dry cleaning services</p>
      </div>

      <!-- Airport Shuttle -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-shuttle-van text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Airport Shuttle</h3>
        <p class="text-gray-600">Complimentary airport transfer service</p>
      </div>

      <!-- Concierge -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-headset text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Concierge Service</h3>
        <p class="text-gray-600">Expert concierge to assist with all your needs</p>
      </div>

      <!-- Pet Friendly -->
      <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mb-4">
          <i class="fas fa-paw text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Pet Friendly</h3>
        <p class="text-gray-600">We welcome your furry friends with special amenities</p>
      </div>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-primary to-secondary text-white">
  <div class="container mx-auto px-4 text-center">
    <h2 class="text-4xl font-bold mb-4">Experience Our Amenities</h2>
    <p class="text-xl mb-8 text-gray-100">Book your stay and enjoy all our world-class facilities</p>
    <a href="index.php?action=room-search" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 inline-block">
      <i class="fas fa-calendar-check mr-2"></i> Book Now
    </a>
  </div>
</section>
