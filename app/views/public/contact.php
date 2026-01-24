<?php
// app/views/public/contact.php
// Note: $user_data, $page_title are passed from controller
?>

<!-- Hero Section -->
<section class="bg-gradient-to-r from-primary to-secondary text-white py-12">
  <div class="container mx-auto px-4">
    <h1 class="text-4xl font-bold mb-2">Contact Us</h1>
    <p class="text-xl text-gray-100">We'd love to hear from you. Send us a message and we'll respond as soon as possible.</p>
  </div>
</section>

<!-- Contact Section -->
<section class="py-16">
  <div class="container mx-auto px-4">
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
      <!-- Contact Form -->
      <div>
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Send us a Message</h2>

        <?php if (!empty($error)): ?>
          <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <?php echo htmlspecialchars($error); ?>
          </div>
        <?php endif; ?>

        <?php if (!empty($success)): ?>
          <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            Thank you for your message. We will get back to you soon.
          </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=contact" class="space-y-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
            <input type="text" name="name"
              value="<?php echo htmlspecialchars($user_data['first_name'] ?? '') . ' ' . htmlspecialchars($user_data['last_name'] ?? ''); ?>"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
              required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
            <input type="email" name="email"
              value="<?php echo htmlspecialchars($user_data['email'] ?? ''); ?>"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
              required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
            <input type="tel" name="phone"
              value="<?php echo htmlspecialchars($user_data['phone'] ?? ''); ?>"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent">
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Subject *</label>
            <input type="text" name="subject"
              value="<?php echo htmlspecialchars($_SESSION['old']['subject'] ?? ''); ?>"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
              required>
          </div>

          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
            <textarea name="message" rows="6"
              class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary focus:border-transparent"
              required><?php echo htmlspecialchars($_SESSION['old']['message'] ?? ''); ?></textarea>
          </div>

          <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white px-6 py-3 rounded-lg font-semibold transition duration-300">
            <i class="fas fa-paper-plane mr-2"></i> Send Message
          </button>
        </form>
      </div>

      <!-- Contact Information -->
      <div>
        <h2 class="text-3xl font-bold mb-6 text-gray-800">Get in Touch</h2>
        <div class="space-y-6">
          <div class="flex items-start">
            <div class="bg-primary/10 p-3 rounded-lg mr-4">
              <i class="fas fa-map-marker-alt text-primary text-xl"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-800 mb-1">Address</h3>
              <p class="text-gray-600">123 Luxury Street, City Center<br>12345, Country</p>
            </div>
          </div>

          <div class="flex items-start">
            <div class="bg-primary/10 p-3 rounded-lg mr-4">
              <i class="fas fa-phone text-primary text-xl"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-800 mb-1">Phone</h3>
              <p class="text-gray-600">+1 (123) 456-7890<br>+1 (123) 456-7891</p>
            </div>
          </div>

          <div class="flex items-start">
            <div class="bg-primary/10 p-3 rounded-lg mr-4">
              <i class="fas fa-envelope text-primary text-xl"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-800 mb-1">Email</h3>
              <p class="text-gray-600">info@luxuryhotel.com<br>reservations@luxuryhotel.com</p>
            </div>
          </div>

          <div class="flex items-start">
            <div class="bg-primary/10 p-3 rounded-lg mr-4">
              <i class="fas fa-clock text-primary text-xl"></i>
            </div>
            <div>
              <h3 class="font-semibold text-gray-800 mb-1">Business Hours</h3>
              <p class="text-gray-600">
                Monday - Friday: 9:00 AM - 6:00 PM<br>
                Saturday: 10:00 AM - 4:00 PM<br>
                Sunday: Closed
              </p>
            </div>
          </div>
        </div>

        <!-- Social Media -->
        <div class="mt-8">
          <h3 class="font-semibold text-gray-800 mb-4">Follow Us</h3>
          <div class="flex space-x-4">
            <a href="#" class="bg-primary/10 hover:bg-primary text-white p-3 rounded-lg transition duration-300">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="bg-primary/10 hover:bg-primary text-white p-3 rounded-lg transition duration-300">
              <i class="fab fa-twitter"></i>
            </a>
            <a href="#" class="bg-primary/10 hover:bg-primary text-white p-3 rounded-lg transition duration-300">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="bg-primary/10 hover:bg-primary text-white p-3 rounded-lg transition duration-300">
              <i class="fab fa-linkedin"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Map Section -->
<section class="py-8 bg-gray-50">
  <div class="container mx-auto px-4">
    <div class="bg-white rounded-lg shadow-lg overflow-hidden">
      <div class="h-96 bg-gray-200 flex items-center justify-center">
        <div class="text-center text-gray-500">
          <i class="fas fa-map-marked-alt text-6xl mb-4"></i>
          <p class="text-xl">Interactive Map</p>
          <p class="text-sm">Map integration can be added here</p>
        </div>
      </div>
    </div>
  </div>
</section>
