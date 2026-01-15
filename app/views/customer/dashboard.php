<?php
// app/views/public/home.php
// Note: $page_title, $stats, $recentReservations, $availableRooms are passed from controller
?>

<!-- Hero Section -->
<div class="relative bg-gradient-to-r from-primary to-secondary text-white">
  <div class="absolute inset-0 bg-black opacity-50"></div>
  <div class="relative w-full px-4 py-20 md:py-32">
    <div class="max-w-7xl mx-auto">
      <div class="max-w-3xl">
        <h1 class="text-4xl md:text-6xl font-bold mb-4"><?php echo htmlspecialchars($page_title); ?></h1>
        <p class="text-xl md:text-2xl mb-8 opacity-90">Experience luxury and comfort at our premier hotel. Book your perfect stay today.</p>
        <a href="#booking-form" class="bg-white text-primary hover:bg-gray-100 font-semibold py-3 px-8 rounded-lg text-lg transition duration-300 inline-block">
          Book Now <i class="fas fa-arrow-right ml-2"></i>
        </a>
      </div>
    </div>
  </div>
</div>

<!-- Quick Booking Form -->
<div id="booking-form" class="w-full px-4 -mt-10 relative z-10">
  <div class="max-w-7xl mx-auto">
    <div class="bg-white rounded-2xl shadow-2xl p-6 md:p-8">
      <h2 class="text-2xl font-bold text-gray-800 mb-6">Find Your Perfect Room</h2>
      <form action="index.php?action=search-rooms" method="POST" class="space-y-6 md:space-y-0 md:grid md:grid-cols-5 md:gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Check-in Date</label>
          <input type="date" name="check_in" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Check-out Date</label>
          <input type="date" name="check_out" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Adults</label>
          <select name="adults" required
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            <option value="1">1 Adult</option>
            <option value="2" selected>2 Adults</option>
            <option value="3">3 Adults</option>
            <option value="4">4 Adults</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-700 mb-2">Children</label>
          <select name="children"
            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
            <option value="0" selected>0 Children</option>
            <option value="1">1 Child</option>
            <option value="2">2 Children</option>
            <option value="3">3 Children</option>
          </select>
        </div>
        <div class="flex items-end">
          <button type="submit"
            class="w-full bg-primary hover:bg-primary/90 text-white font-semibold py-3 px-6 rounded-lg transition duration-300 flex items-center justify-center">
            <i class="fas fa-search mr-2"></i> Find Rooms
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Statistics Section -->
<div class="w-full px-4 py-12">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-bold text-gray-800 mb-4">Why Choose Our Hotel</h2>
      <p class="text-gray-600 max-w-2xl mx-auto">Experience unparalleled service and amenities that make every stay memorable.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-6 text-white hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center">
          <div class="bg-white/20 p-3 rounded-lg mr-4">
            <i class="fas fa-calendar-alt text-2xl"></i>
          </div>
          <div>
            <h3 class="text-3xl font-bold"><?php echo $stats['total_reservations'] ?? '0'; ?></h3>
            <p class="text-white/80">Total Reservations</p>
          </div>
        </div>
      </div>

      <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-2xl p-6 text-white hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center">
          <div class="bg-white/20 p-3 rounded-lg mr-4">
            <i class="fas fa-bed text-2xl"></i>
          </div>
          <div>
            <h3 class="text-3xl font-bold"><?php echo $stats['available_rooms'] ?? '0'; ?></h3>
            <p class="text-white/80">Available Rooms</p>
          </div>
        </div>
      </div>

      <div class="bg-gradient-to-r from-yellow-500 to-orange-600 rounded-2xl p-6 text-white hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center">
          <div class="bg-white/20 p-3 rounded-lg mr-4">
            <i class="fas fa-star text-2xl"></i>
          </div>
          <div>
            <h3 class="text-3xl font-bold">4.8/5</h3>
            <p class="text-white/80">Guest Rating</p>
          </div>
        </div>
      </div>

      <div class="bg-gradient-to-r from-cyan-500 to-blue-600 rounded-2xl p-6 text-white hover:shadow-xl transition-shadow duration-300">
        <div class="flex items-center">
          <div class="bg-white/20 p-3 rounded-lg mr-4">
            <i class="fas fa-wifi text-2xl"></i>
          </div>
          <div>
            <h3 class="text-3xl font-bold">100%</h3>
            <p class="text-white/80">Free WiFi Coverage</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Featured Rooms -->
<div class="bg-gray-50 py-12">
  <div class="w-full px-4">
    <div class="max-w-7xl mx-auto">
      <div class="flex justify-between items-center mb-8">
        <div>
          <h2 class="text-3xl font-bold text-gray-800">Featured Rooms</h2>
          <p class="text-gray-600">Choose from our selection of luxurious rooms</p>
        </div>
        <a href="index.php?action=rooms"
          class="text-primary hover:text-primary/80 font-semibold flex items-center">
          View All <i class="fas fa-arrow-right ml-2"></i>
        </a>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <?php
        // Define fallback room types if no rooms are available from database
        $fallbackRooms = [
          [
            'type' => 'Standard Room',
            'available_count' => 0,
            'description' => 'Comfortable room with essential amenities',
            'price_per_night' => 89,
            'images' => ['primary' => 'images/room-1.jpg']
          ],
          [
            'type' => 'Deluxe Room',
            'available_count' => 0,
            'description' => 'Spacious room with premium features',
            'price_per_night' => 129,
            'images' => ['primary' => 'images/room-big-1.jpg']
          ],
          [
            'type' => 'Suite',
            'available_count' => 0,
            'description' => 'Luxurious living space with separate areas',
            'price_per_night' => 199,
            'images' => ['primary' => 'images/swimmingpool-1.jpg']
          ],
          [
            'type' => 'Presidential Suite',
            'available_count' => 0,
            'description' => 'Ultimate luxury experience',
            'price_per_night' => 399,
            'images' => ['primary' => 'images/default-room.jpg']
          ]
        ];

        // Use available rooms from database if they exist, otherwise use fallback
        $roomsToDisplay = (!empty($availableRooms) && is_array($availableRooms)) ? $availableRooms : $fallbackRooms;

        foreach ($roomsToDisplay as $room):
          // Get the primary image - use processed images from controller
          $primaryImage = 'images/default-room.jpg'; // Default fallback

          if (isset($room['images']['primary'])) {
            $primaryImage = $room['images']['primary'];
          } elseif (isset($room['images'][0])) {
            $primaryImage = $room['images'][0];
          } elseif (isset($room['primary_image']) && !empty($room['primary_image'])) {
            // Fallback: if images array wasn't processed, use primary_image directly
            $primaryImage = 'images/' . basename($room['primary_image']);
          }
        ?>
          <div class="bg-white rounded-xl shadow-md hover:shadow-xl transition-shadow duration-300 overflow-hidden flex flex-col">
            <!-- Room Image -->
            <div class="h-48 w-full overflow-hidden bg-gray-200 flex-shrink-0">
              <img src="<?php echo htmlspecialchars($primaryImage); ?>"
                alt="<?php echo htmlspecialchars($room['type']); ?>"
                class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                onerror="this.onerror=null; this.src='images/default-room.jpg';">
            </div>

            <div class="p-6 flex-1 flex flex-col">
              <div class="flex justify-between items-start mb-4">
                <div class="flex-1">
                  <h3 class="text-xl font-bold text-gray-800 mb-2"><?php echo htmlspecialchars($room['type']); ?></h3>
                  <div class="flex items-center flex-wrap gap-2">
                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2.5 py-0.5 rounded">
                      <?php echo $room['available_count'] ?? 0; ?> Available
                    </span>
                    <?php if (isset($room['capacity'])): ?>
                      <span class="text-gray-500 text-sm">
                        <i class="fas fa-user-friends mr-1"></i> <?php echo $room['capacity']; ?> Guests
                      </span>
                    <?php endif; ?>
                  </div>
                </div>
                <div class="w-12 h-12 rounded-lg flex items-center justify-center flex-shrink-0 ml-2"
                  style="background-color: <?php echo isset($room['color']) ? '#' . $room['color'] : '#667eea'; ?>">
                  <i class="fas <?php echo $room['icon'] ?? 'fa-home'; ?> text-white"></i>
                </div>
              </div>
              <p class="text-gray-600 mb-4 flex-1"><?php echo htmlspecialchars($room['description'] ?? ''); ?></p>

              <?php if (isset($room['size'])): ?>
                <p class="text-gray-500 text-sm mb-2">
                  <i class="fas fa-expand-arrows-alt mr-1"></i> Size: <?php echo $room['size']; ?>
                </p>
              <?php endif; ?>

              <?php if (isset($room['amenities'])):
                $amenities = json_decode($room['amenities'], true);
                if ($amenities): ?>
                  <div class="flex flex-wrap gap-1 mb-4">
                    <?php foreach ($amenities as $amenity => $value):
                      if ($value === true || $value === 'true' || $value === 1):
                        $amenityIcons = [
                          'tv' => 'fa-tv',
                          'wifi' => 'fa-wifi',
                          'aircon' => 'fa-snowflake',
                          'balcony' => 'fa-door-open',
                          'minibar' => 'fa-wine-bottle',
                          'private_pool' => 'fa-swimming-pool'
                        ];
                    ?>
                        <span class="text-xs bg-blue-50 text-blue-600 px-2 py-1 rounded">
                          <i class="fas <?php echo $amenityIcons[$amenity] ?? 'fa-check'; ?> mr-1"></i>
                          <?php echo ucfirst(str_replace('_', ' ', $amenity)); ?>
                        </span>
                    <?php endif;
                    endforeach; ?>
                  </div>
                <?php endif; ?>
              <?php endif; ?>

              <div class="flex justify-between items-center mt-auto pt-4 border-t border-gray-200">
                <div>
                  <span class="text-2xl font-bold text-gray-800">$<?php echo number_format($room['price_per_night'] ?? 0, 2); ?></span>
                  <span class="text-gray-500"> /night</span>
                </div>
                <button onclick="bookRoom('<?php echo htmlspecialchars($room['type']); ?>')"
                  class="bg-primary hover:bg-primary/90 text-white font-semibold py-2 px-4 rounded-lg transition duration-300 whitespace-nowrap">
                  Book Now
                </button>
              </div>
            </div>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- Amenities Section -->
<div class="w-full px-4 py-12">
  <div class="max-w-7xl mx-auto">
    <div class="text-center mb-12">
      <h2 class="text-3xl font-bold text-gray-800 mb-4">Hotel Amenities</h2>
      <p class="text-gray-600 max-w-2xl mx-auto">Enjoy our world-class facilities and services</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
      <?php
      $amenities = [
        ['icon' => 'fa-swimming-pool', 'title' => 'Swimming Pool', 'desc' => 'Heated outdoor pool'],
        ['icon' => 'fa-utensils', 'title' => 'Restaurant', 'desc' => 'Fine dining experience'],
        ['icon' => 'fa-dumbbell', 'title' => 'Fitness Center', 'desc' => '24/7 gym access'],
        ['icon' => 'fa-spa', 'title' => 'Spa', 'desc' => 'Relaxing treatments'],
        ['icon' => 'fa-wifi', 'title' => 'Free WiFi', 'desc' => 'High-speed internet'],
        ['icon' => 'fa-car', 'title' => 'Parking', 'desc' => 'Secure parking'],
        ['icon' => 'fa-concierge-bell', 'title' => '24/7 Reception', 'desc' => 'Always available'],
        ['icon' => 'fa-cocktail', 'title' => 'Bar', 'desc' => 'Signature cocktails']
      ];

      foreach ($amenities as $amenity):
      ?>
        <div class="text-center p-6 bg-white rounded-xl shadow-md hover:shadow-lg transition-shadow duration-300">
          <div class="w-16 h-16 bg-blue-50 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas <?php echo $amenity['icon']; ?> text-2xl text-primary"></i>
          </div>
          <h4 class="font-semibold text-gray-800 mb-2"><?php echo $amenity['title']; ?></h4>
          <p class="text-gray-600 text-sm"><?php echo $amenity['desc']; ?></p>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Testimonials -->
<div class="bg-gradient-to-r from-blue-50 to-purple-50 py-12">
  <div class="w-full px-4">
    <div class="max-w-7xl mx-auto">
      <div class="text-center mb-12">
        <h2 class="text-3xl font-bold text-gray-800 mb-4">What Our Guests Say</h2>
        <p class="text-gray-600">Read about experiences from our valued guests</p>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <?php
        $testimonials = [
          ['name' => 'Sarah Johnson', 'rating' => 5, 'text' => 'Absolutely wonderful stay! The room was spacious and the service was exceptional.', 'date' => '2 days ago'],
          ['name' => 'Michael Chen', 'rating' => 5, 'text' => 'Best hotel experience I\'ve had. The amenities were top-notch and staff were very helpful.', 'date' => '1 week ago'],
          ['name' => 'Emma Rodriguez', 'rating' => 5, 'text' => 'Perfect location, amazing views, and incredibly comfortable beds. Will definitely return!', 'date' => '3 days ago']
        ];

        foreach ($testimonials as $testimonial):
        ?>
          <div class="bg-white rounded-xl shadow-md p-6">
            <div class="flex items-center mb-4">
              <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center mr-4">
                <span class="text-primary font-bold"><?php echo strtoupper(substr($testimonial['name'], 0, 1)); ?></span>
              </div>
              <div>
                <h4 class="font-semibold text-gray-800"><?php echo $testimonial['name']; ?></h4>
                <div class="flex text-yellow-400">
                  <?php for ($i = 0; $i < $testimonial['rating']; $i++): ?>
                    <i class="fas fa-star"></i>
                  <?php endfor; ?>
                </div>
              </div>
            </div>
            <p class="text-gray-600 mb-4">"<?php echo $testimonial['text']; ?>"</p>
            <span class="text-gray-400 text-sm"><?php echo $testimonial['date']; ?></span>
          </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</div>

<!-- Call to Action -->
<div class="w-full px-4 py-12">
  <div class="max-w-7xl mx-auto">
    <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl p-8 md:p-12 text-white text-center">
      <h2 class="text-3xl font-bold mb-4">Ready to Book Your Stay?</h2>
      <p class="text-xl opacity-90 mb-8 max-w-2xl mx-auto">Experience luxury and comfort like never before. Book now to secure your dates!</p>
      <div class="flex flex-col md:flex-row gap-4 justify-center">
        <a href="#booking-form"
          class="bg-white text-primary hover:bg-gray-100 font-semibold py-3 px-8 rounded-lg text-lg transition duration-300">
          Book Now <i class="fas fa-calendar-check ml-2"></i>
        </a>
        <a href="tel:+1234567890"
          class="bg-transparent border-2 border-white hover:bg-white/10 font-semibold py-3 px-8 rounded-lg text-lg transition duration-300">
          <i class="fas fa-phone mr-2"></i> Call Us Now
        </a>
      </div>
    </div>
  </div>
</div>

<script>
  function bookRoom(roomType) {
    // Scroll to booking form and set room type
    document.querySelector('#booking-form').scrollIntoView({
      behavior: 'smooth'
    });

    // You can add logic here to pre-select the room type in a form
    // or redirect to booking page
    const roomValue = roomType.toLowerCase().replace(' room', '').replace(' ', '-');
    window.location.href = `index.php?action=book&room_type=${encodeURIComponent(roomValue)}`;
  }

  // Set minimum dates for check-in/out
  document.addEventListener('DOMContentLoaded', function() {
    const today = new Date().toISOString().split('T')[0];
    const checkInInputs = document.querySelectorAll('input[name="check_in"]');
    const checkOutInputs = document.querySelectorAll('input[name="check_out"]');

    checkInInputs.forEach(input => {
      input.min = today;
      input.addEventListener('change', function() {
        checkOutInputs.forEach(outInput => {
          outInput.min = this.value;
          if (outInput.value && outInput.value < this.value) {
            outInput.value = this.value;
          }
        });
      });
    });
  });
</script>
