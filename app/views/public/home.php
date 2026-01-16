<?php
// app/views/public/home.php
// Note: $page_title is passed from controller
?>

<!-- Hero Section -->
<section class="relative bg-gradient-to-r from-primary to-secondary text-white py-20">
  <div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto text-center">
      <h1 class="text-5xl md:text-6xl font-bold mb-6">Welcome to Luxury Hotel</h1>
      <p class="text-xl md:text-2xl mb-8 text-gray-100">Experience world-class hospitality and comfort in the heart of the city</p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="index.php?action=room-search" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
          <i class="fas fa-search mr-2"></i> Search Rooms
        </a>
        <a href="index.php?action=rooms" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition duration-300">
          <i class="fas fa-bed mr-2"></i> View All Rooms
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Quick Search Section -->
<section class="py-12 bg-gray-50">
  <div class="container mx-auto px-4">
    <div class="max-w-5xl mx-auto">
      <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">Find Your Perfect Stay</h2>
        <form method="GET" action="index.php?action=room-search" class="grid grid-cols-1 md:grid-cols-4 gap-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Check-in</label>
            <input type="date" name="check_in" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Check-out</label>
            <input type="date" name="check_out" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent" required>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Guests</label>
            <select name="guests" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
              <option value="1">1 Guest</option>
              <option value="2">2 Guests</option>
              <option value="3">3 Guests</option>
              <option value="4">4 Guests</option>
              <option value="5">5+ Guests</option>
            </select>
          </div>
          <div class="flex items-end">
            <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white px-6 py-2 rounded-lg font-semibold transition duration-300">
              <i class="fas fa-search mr-2"></i> Search
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="py-16">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Why Choose Us</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="text-center p-6 rounded-lg hover:shadow-lg transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-bed text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Luxurious Rooms</h3>
        <p class="text-gray-600">Elegantly designed rooms with modern amenities for your comfort</p>
      </div>
      <div class="text-center p-6 rounded-lg hover:shadow-lg transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-utensils text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Fine Dining</h3>
        <p class="text-gray-600">Exquisite cuisine prepared by world-class chefs</p>
      </div>
      <div class="text-center p-6 rounded-lg hover:shadow-lg transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-spa text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Spa & Wellness</h3>
        <p class="text-gray-600">Relax and rejuvenate with our premium spa services</p>
      </div>
      <div class="text-center p-6 rounded-lg hover:shadow-lg transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-swimming-pool text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Pool & Recreation</h3>
        <p class="text-gray-600">Enjoy our outdoor pool and recreational facilities</p>
      </div>
      <div class="text-center p-6 rounded-lg hover:shadow-lg transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-wifi text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">Free WiFi</h3>
        <p class="text-gray-600">High-speed internet access throughout the hotel</p>
      </div>
      <div class="text-center p-6 rounded-lg hover:shadow-lg transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-concierge-bell text-primary text-2xl"></i>
        </div>
        <h3 class="text-xl font-semibold mb-2 text-gray-800">24/7 Service</h3>
        <p class="text-gray-600">Round-the-clock concierge and room service</p>
      </div>
    </div>
  </div>
</section>

<!-- Room Types Preview -->
<section class="py-16 bg-gray-50">
  <div class="container mx-auto px-4">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-bold mb-4 text-gray-800">Our Room Types</h2>
      <p class="text-gray-600 max-w-2xl mx-auto">Choose from our selection of beautifully designed rooms</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
        <div class="h-48 bg-gradient-to-br from-primary to-secondary"></div>
        <div class="p-6">
          <h3 class="text-xl font-semibold mb-2 text-gray-800">Standard Room</h3>
          <p class="text-gray-600 mb-4">Comfortable and cozy rooms perfect for solo travelers</p>
          <div class="flex items-center justify-between">
            <span class="text-primary font-bold">From $99/night</span>
            <a href="index.php?action=rooms&type=standard" class="text-primary hover:underline">View Details</a>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
        <div class="h-48 bg-gradient-to-br from-secondary to-primary"></div>
        <div class="p-6">
          <h3 class="text-xl font-semibold mb-2 text-gray-800">Deluxe Room</h3>
          <p class="text-gray-600 mb-4">Spacious rooms with premium amenities</p>
          <div class="flex items-center justify-between">
            <span class="text-primary font-bold">From $149/night</span>
            <a href="index.php?action=rooms&type=deluxe" class="text-primary hover:underline">View Details</a>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
        <div class="h-48 bg-gradient-to-br from-primary to-secondary"></div>
        <div class="p-6">
          <h3 class="text-xl font-semibold mb-2 text-gray-800">Executive Suite</h3>
          <p class="text-gray-600 mb-4">Luxurious suites with separate living area</p>
          <div class="flex items-center justify-between">
            <span class="text-primary font-bold">From $249/night</span>
            <a href="index.php?action=rooms&type=suite" class="text-primary hover:underline">View Details</a>
          </div>
        </div>
      </div>
      <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
        <div class="h-48 bg-gradient-to-br from-secondary to-primary"></div>
        <div class="p-6">
          <h3 class="text-xl font-semibold mb-2 text-gray-800">Presidential Suite</h3>
          <p class="text-gray-600 mb-4">Ultimate luxury with panoramic views</p>
          <div class="flex items-center justify-between">
            <span class="text-primary font-bold">From $499/night</span>
            <a href="index.php?action=rooms&type=presidential" class="text-primary hover:underline">View Details</a>
          </div>
        </div>
      </div>
    </div>
    <div class="text-center mt-8">
      <a href="index.php?action=rooms" class="inline-block bg-primary hover:bg-primary/90 text-white px-8 py-3 rounded-lg font-semibold transition duration-300">
        View All Rooms
      </a>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-primary to-secondary text-white">
  <div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-4xl font-bold mb-4">Ready to Book Your Stay?</h2>
      <p class="text-xl mb-8 text-gray-100">Experience luxury hospitality at its finest</p>
      <a href="index.php?action=room-search" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 inline-block">
        <i class="fas fa-calendar-check mr-2"></i> Book Now
      </a>
    </div>
  </div>
</section>
