<?php
// app/views/public/about.php
// Note: $hotel_info, $team_members, $statistics, $page_title are passed from controller
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-secondary text-white py-16">
  <div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto text-center">
      <h1 class="text-5xl font-bold mb-6">About Our Hotel</h1>
      <p class="text-xl mb-8 text-gray-100"><?php echo htmlspecialchars($hotel_info['description'] ?? 'Experience luxury and comfort at our premier hotel.'); ?></p>
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <a href="#history" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
          Our History
        </a>
        <a href="#team" class="bg-transparent border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition duration-300">
          Meet Our Team
        </a>
      </div>
    </div>
  </div>
</section>

<!-- Statistics Section -->
<section class="py-16 bg-gray-50">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Hotel Statistics</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
      <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-bed text-primary text-2xl"></i>
        </div>
        <div class="text-4xl font-bold text-primary mb-2"><?php echo $statistics['total_rooms'] ?? 0; ?></div>
        <div class="text-gray-600 font-semibold">Total Rooms</div>
      </div>
      <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-home text-primary text-2xl"></i>
        </div>
        <div class="text-4xl font-bold text-primary mb-2"><?php echo $statistics['room_types'] ?? 0; ?></div>
        <div class="text-gray-600 font-semibold">Room Types</div>
      </div>
      <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-users text-primary text-2xl"></i>
        </div>
        <div class="text-4xl font-bold text-primary mb-2"><?php echo $statistics['total_guests'] ?? 0; ?>+</div>
        <div class="text-gray-600 font-semibold">Happy Guests</div>
      </div>
      <div class="bg-white rounded-lg shadow-md p-6 text-center hover:shadow-xl transition duration-300">
        <div class="bg-primary/10 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-calendar-check text-primary text-2xl"></i>
        </div>
        <div class="text-4xl font-bold text-primary mb-2"><?php echo $statistics['total_reservations'] ?? 0; ?></div>
        <div class="text-gray-600 font-semibold">Reservations</div>
      </div>
    </div>
  </div>
</section>

<!-- History Section -->
<section id="history" class="py-16">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Our History</h2>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      <div>
        <div class="space-y-8">
          <div class="flex gap-6">
            <div class="flex-shrink-0">
              <div class="bg-primary text-white w-20 h-20 rounded-full flex items-center justify-center font-bold text-lg">
                <?php echo htmlspecialchars($hotel_info['established'] ?? '2005'); ?>
              </div>
            </div>
            <div>
              <h4 class="text-xl font-semibold mb-2 text-gray-800">Foundation</h4>
              <p class="text-gray-600">Our hotel was established with a vision to provide exceptional hospitality services.</p>
            </div>
          </div>
          <div class="flex gap-6">
            <div class="flex-shrink-0">
              <div class="bg-primary text-white w-20 h-20 rounded-full flex items-center justify-center font-bold text-lg">2010</div>
            </div>
            <div>
              <h4 class="text-xl font-semibold mb-2 text-gray-800">First Expansion</h4>
              <p class="text-gray-600">Expanded our facilities and added new room types to accommodate growing demand.</p>
            </div>
          </div>
          <div class="flex gap-6">
            <div class="flex-shrink-0">
              <div class="bg-primary text-white w-20 h-20 rounded-full flex items-center justify-center font-bold text-lg">2018</div>
            </div>
            <div>
              <h4 class="text-xl font-semibold mb-2 text-gray-800">Renovation Complete</h4>
              <p class="text-gray-600">Completed major renovations with modern amenities and eco-friendly features.</p>
            </div>
          </div>
          <div class="flex gap-6">
            <div class="flex-shrink-0">
              <div class="bg-primary text-white w-20 h-20 rounded-full flex items-center justify-center font-bold text-lg">2023</div>
            </div>
            <div>
              <h4 class="text-xl font-semibold mb-2 text-gray-800">Award Winning</h4>
              <p class="text-gray-600">Received "Best Luxury Hotel" award for exceptional service and facilities.</p>
            </div>
          </div>
        </div>
      </div>
      <div>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
          <div class="bg-white rounded-lg shadow-md p-6">
            <div class="bg-primary/10 w-12 h-12 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-bullseye text-primary text-xl"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2 text-gray-800">Our Mission</h3>
            <p class="text-gray-600"><?php echo htmlspecialchars($hotel_info['mission'] ?? 'To provide exceptional hospitality services.'); ?></p>
          </div>
          <div class="bg-white rounded-lg shadow-md p-6">
            <div class="bg-primary/10 w-12 h-12 rounded-full flex items-center justify-center mb-4">
              <i class="fas fa-eye text-primary text-xl"></i>
            </div>
            <h3 class="text-xl font-semibold mb-2 text-gray-800">Our Vision</h3>
            <p class="text-gray-600"><?php echo htmlspecialchars($hotel_info['vision'] ?? 'To be the most preferred luxury hotel brand globally.'); ?></p>
          </div>
        </div>
        <div class="bg-white rounded-lg shadow-md p-6">
          <h4 class="text-xl font-semibold mb-4 text-gray-800">Hotel Information</h4>
          <ul class="space-y-3">
            <li class="flex items-center text-gray-600">
              <i class="fas fa-map-marker-alt text-primary mr-3"></i>
              <?php echo htmlspecialchars($hotel_info['address'] ?? '123 Luxury Street, City Center'); ?>
            </li>
            <li class="flex items-center text-gray-600">
              <i class="fas fa-phone text-primary mr-3"></i>
              <?php echo htmlspecialchars($hotel_info['phone'] ?? '+1 (123) 456-7890'); ?>
            </li>
            <li class="flex items-center text-gray-600">
              <i class="fas fa-envelope text-primary mr-3"></i>
              <?php echo htmlspecialchars($hotel_info['email'] ?? 'info@luxuryhotel.com'); ?>
            </li>
            <li class="flex items-center text-gray-600">
              <i class="fas fa-building text-primary mr-3"></i>
              Established: <?php echo htmlspecialchars($hotel_info['established'] ?? '2005'); ?>
            </li>
            <li class="flex items-center text-gray-600">
              <i class="fas fa-trophy text-primary mr-3"></i>
              Awards: <?php echo htmlspecialchars($hotel_info['awards'] ?? 'Best Luxury Hotel 2023'); ?>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Team Section -->
<section id="team" class="py-16 bg-gray-50">
  <div class="container mx-auto px-4">
    <h2 class="text-3xl font-bold text-center mb-12 text-gray-800">Meet Our Team</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
      <?php foreach ($team_members ?? [] as $member): ?>
        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition duration-300">
          <div class="h-64 bg-gradient-to-br from-primary to-secondary flex items-center justify-center">
            <?php if (!empty($member['photo'])): ?>
              <img src="<?php echo htmlspecialchars($member['photo']); ?>" alt="<?php echo htmlspecialchars($member['name']); ?>" class="w-full h-full object-cover">
            <?php else: ?>
              <i class="fas fa-user-circle text-white text-8xl"></i>
            <?php endif; ?>
          </div>
          <div class="p-6">
            <h5 class="text-lg font-semibold mb-1 text-gray-800"><?php echo htmlspecialchars($member['name'] ?? 'Team Member'); ?></h5>
            <div class="text-primary font-semibold mb-2 text-sm"><?php echo htmlspecialchars($member['position'] ?? 'Staff'); ?></div>
            <p class="text-gray-600 text-sm mb-4"><?php echo htmlspecialchars($member['bio'] ?? ''); ?></p>
            <div class="flex justify-between items-center">
              <span class="text-primary text-sm">
                <i class="fas fa-clock mr-1"></i>
                <?php echo $member['experience_years'] ?? '0'; ?> exp.
              </span>
              <div class="flex gap-2">
                <a href="#" class="text-gray-400 hover:text-primary transition duration-300">
                  <i class="fab fa-linkedin"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-primary transition duration-300">
                  <i class="fab fa-twitter"></i>
                </a>
              </div>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<!-- CTA Section -->
<section class="py-16 bg-gradient-to-r from-primary to-secondary text-white">
  <div class="container mx-auto px-4">
    <div class="max-w-4xl mx-auto text-center">
      <h2 class="text-4xl font-bold mb-4">Experience Luxury Hospitality</h2>
      <p class="text-xl mb-8 text-gray-100">Book your stay with us and experience world-class service and comfort.</p>
      <a href="index.php?action=rooms" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 inline-block">
        <i class="fas fa-calendar-check mr-2"></i> Book Now
      </a>
    </div>
  </div>
</section>
