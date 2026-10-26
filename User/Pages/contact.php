<?php
include "../../Config/db.php";
session_start();

// Check if user is logged in
if (!isset($_SESSION['user'])) {
    echo '<script>
        alert("Please Login To View This Page");
        window.location.href = "../../Auth/login.php";
    </script>';
    exit();
}


// Initialize form status
$formStatus = '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $name = $_SESSION['user'];
        $email = $_SESSION['email'];
        $subject = $_POST['subject'] ?? '';
        $message = $_POST['message'] ?? '';

        // Validate input
        if (empty($subject) || empty($message)) {
            $formStatus = 'Please fill in all required fields.';
        } else {
            $stmt = $conn->prepare("INSERT INTO feedback (Name, Email, Subject, Message) VALUES (?, ?, ?, ?)");
            if ($stmt === false) {
                throw new Exception('Failed to prepare statement: ' . $conn->error);
            }
            $stmt->bind_param("ssss", $name, $email, $subject, $message);

            if ($stmt->execute()) {
                $formStatus = 'Thank you for your message!';
            } else {
                $formStatus = 'Error submitting your message. Please try again.';
            }
            $stmt->close();
        }
    } catch (Exception $e) {
        $formStatus = 'An error occurred: ' . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us - E - Books</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.2.0/fonts/remixicon.css" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        slate: {
                            950: '#020617',
                            900: '#0f172a',
                            800: '#1e293b',
                            700: '#334155',
                            600: '#475569',
                            500: '#64748b',
                            400: '#94a3b8',
                            300: '#cbd5e1'
                        },
                        emerald: {
                            400: '#34d399',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        .scroll-animate {
            opacity: 0;
            transform: translateY(12px);
            transition: opacity 280ms cubic-bezier(0.16, 1, 0.3, 1), transform 280ms cubic-bezier(0.16, 1, 0.3, 1);
        }
        .scroll-animate.visible {
            opacity: 1;
            transform: translateY(0);
        }
        @media (prefers-reduced-motion: reduce) {
            .scroll-animate {
                opacity: 1 !important;
                transform: none !important;
                transition-duration: 150ms !important;
            }
        }
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #1e293b; /* slate-800 */
        }

        ::-webkit-scrollbar-thumb {
            background-color: #10b981; /* emerald-500 */
            border-radius: 8px;
            border: 2px solid #1e293b; /* matches track */
        }

        ::-webkit-scrollbar-thumb:hover {
            background-color: #059669; /* emerald-600 */
        }
    </style>
</head>
<body class="bg-slate-950 text-white">
<?php include "../Components/nav.php";?>
    <div class="px-6">
    <!-- Page Header -->
    <section class="pt-32 pb-12 sm:px-6 relative">
        <div class="container mx-auto relative">
            <div id="page-header" class="text-center scroll-animate">
                <h1 class="text-6xl lg:text-7xl font-black mb-6 text-white">
                    Get In <span class="text-emerald-400">Touch</span>
                </h1>
                <p class="text-slate-400 text-xl max-w-3xl mx-auto font-light leading-relaxed">
                    Have questions, suggestions, or just want to say hello? We'd love to hear from you!
                </p>
            </div>
        </div>
    </section>

    <!-- Contact Content -->
    <section class="pb-24 sm:px-6">
        <div class="container mx-auto">
            <div class="grid lg:grid-cols-2 gap-10">
                <!-- Contact Form -->
                <div id="contact-form" class="scroll-animate">
                    <div class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-3xl p-10 shadow-2xl">
                        <h2 class="text-4xl font-black text-white mb-8">Send us a Message</h2>
                        <form id="contactForm" method="POST" class="space-y-6">
                            <!-- Name -->
                            <div>
                                <label for="name" class="block text-slate-300 font-semibold mb-3 text-lg">Full Name</label>
                                <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($_SESSION['user']); ?>" required class="w-full backdrop-blur-xl bg-slate-700/50 border border-slate-600/50 rounded-2xl px-6 py-4 text-slate-300 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 text-lg transition-all duration-300 cursor-not-allowed" readonly>
                            </div>

                            <!-- Email -->
                            <div>
                                <label for="email" class="block text-slate-300 font-semibold mb-3 text-lg">Email Address</label>
                                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($_SESSION['email']); ?>" required class="w-full backdrop-blur-xl bg-slate-700/50 border border-slate-600/50 rounded-2xl px-6 py-4 text-slate-300 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 text-lg transition-all duration-300 cursor-not-allowed" readonly>
                            </div>

                            <!-- Subject -->
                            <div>
                                <label for="subject" class="block text-slate-300 font-semibold mb-3 text-lg">Subject</label>
                                <select id="subject" name="subject" required class="w-full backdrop-blur-xl bg-slate-700/50 border border-slate-600/50 rounded-2xl px-6 py-4 text-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 text-lg transition-all duration-300">
                                    <option value="order">Order Issue</option>
                                    <option value="product">Product Query</option>
                                    <option value="returns">Returns & Refunds</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <!-- Message -->
                            <div>
                                <label for="message" class="block text-slate-300 font-semibold mb-3 text-lg">Message</label>
                                <textarea id="message" name="message" rows="6" required class="w-full backdrop-blur-xl bg-slate-700/50 border border-slate-600/50 rounded-2xl px-6 py-4 text-slate-300 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 focus:border-emerald-500/50 text-lg transition-all duration-300 resize-none" placeholder="Tell us how we can help you..."></textarea>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-2xl shadow-emerald-500/20 py-4 text-xl font-bold rounded-2xl transition-all duration-300 hover:scale-105 flex items-center justify-center">
                            <i class="ri-send-plane-fill text-white text-2xl mr-3"></i>
                                Send Message
                            </button>
                        </form>
                        <div id="formStatus" class="mt-4 text-sm text-center font-medium <?php echo $formStatus ? ($formStatus === 'Thank you for your message!' ? 'text-emerald-400' : 'text-red-400') : ''; ?>">
                            <?php echo htmlspecialchars($formStatus); ?>
                        </div>
                    </div>
                </div>

                <!-- Contact Information -->
                <div id="contact-info" class="space-y-8 scroll-animate">
                    <!-- Contact Details -->
                    <div class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-3xl p-10 shadow-2xl">
                        <h3 class="text-3xl font-black text-white mb-8">Contact Information</h3>
                        
                        <div class="space-y-8">
                            <!-- Email -->
                            <div class="flex items-start space-x-6">
                                <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-emerald-500/30 flex-shrink-0">
                                <i class="ri-mail-send-line text-white text-3xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-white mb-2">Email Us</h4>
                                    <p class="text-slate-400 text-lg">hello@E - Books.com</p>
                                </div>
                            </div>

                            <!-- Phone -->
                            <div class="flex items-start space-x-6">
                                <div class="w-16 h-16 bg-blue-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-blue-500/30 flex-shrink-0">
                                <i class="ri-phone-line text-white text-3xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-white mb-2">Call Us</h4>
                                    <p class="text-slate-400 text-lg">+1 (555) 123-4567</p>
                                </div>
                            </div>

                            <!-- Address -->
                            <div class="flex items-start space-x-6">
                                <div class="w-16 h-16 bg-purple-500 rounded-2xl flex items-center justify-center shadow-2xl shadow-purple-500/30 flex-shrink-0">
                                <i class="ri-map-pin-line text-white text-3xl"></i>
                                </div>
                                <div>
                                    <h4 class="text-xl font-bold text-white mb-2">Visit Us</h4>
                                    <p class="text-slate-400 text-lg">123 Book Street</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- FAQ Section -->
                    <div id="faq" class="backdrop-blur-2xl bg-slate-800/30 border border-slate-700/50 rounded-3xl p-10 shadow-2xl">
                        <h3 class="text-3xl font-black text-white mb-8">Frequently Asked Questions</h3>
                        
                        <div class="space-y-6">
                            <!-- FAQ Item 1 -->
                            <div class="border-b border-slate-700/50 pb-6">
                                <h4 class="text-xl font-bold text-white mb-3">How do I participate in competitions?</h4>
                                <p class="text-slate-400 leading-relaxed">Visit our Competition page to see all ongoing and upcoming competitions. Simply click "Enter Competition" and follow the submission guidelines.</p>
                            </div>

                            <!-- FAQ Item 2 -->
                            <div class="border-b border-slate-700/50 pb-6">
                                <h4 class="text-xl font-bold text-white mb-3">Can I return or exchange books?</h4>
                                <p class="text-slate-400 leading-relaxed">Yes! We offer a 30-day return policy for physical books in original condition. Digital books can be refunded within 7 days of purchase.</p>
                            </div>

                            <!-- FAQ Item 3 -->
                            <div class="border-b border-slate-700/50 pb-6">
                                <h4 class="text-xl font-bold text-white mb-3">How do I track my order?</h4>
                                <p class="text-slate-400 leading-relaxed">Once your order ships, you'll receive a tracking number via email. You can also check your order status in your account dashboard.</p>
                            </div>

                            <!-- FAQ Item 4 -->
                            <div>
                                <h4 class="text-xl font-bold text-white mb-3">Do you offer international shipping?</h4>
                                <p class="text-slate-400 leading-relaxed">Yes, we ship worldwide! Shipping costs and delivery times vary by location. Digital books are available instantly worldwide.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </section>

    <?php
    include "../Components/footer.php";

     $conn->close(); 
     ?>

    <script>
        // Scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '50px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                }
            });
        }, observerOptions);

        // Observe all scroll-animate elements
        document.querySelectorAll('.scroll-animate').forEach(el => {
            observer.observe(el);
        });

        // Reset form on successful submission
        <?php if ($formStatus === 'Thank you for your message!'): ?>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('contactForm').reset();
            });
        <?php endif; ?>
        document.addEventListener("DOMContentLoaded", function () {
            const navLinks = document.querySelectorAll("nav a");

            navLinks.forEach(link => {
                link.classList.remove("text-emerald-400");

                if (link.closest(".lg\\:flex")) { 
                    link.classList.add("text-slate-300");
                } else if (link.closest(".mobile-menu")) { 
                    link.classList.add("text-slate-300");
                }

                const underline = link.querySelector("span");
                if (underline) {
                    // Reset underline to default hover state
                    underline.classList.remove("w-full");
                    underline.classList.add("w-0", "group-hover:w-full", "bg-emerald-400");
                }
            });

            // Desktop active link
            const desktopActiveLink = document.querySelector('nav a[href="../Pages/Contact.php"]');
            if (desktopActiveLink) {
                desktopActiveLink.classList.remove("text-slate-300");
                desktopActiveLink.classList.add("text-emerald-400");

                const activeUnderline = desktopActiveLink.querySelector("span");
                if (activeUnderline) {
                    activeUnderline.classList.remove("w-0", "group-hover:w-full");
                    activeUnderline.classList.add("w-full", "bg-emerald-400");
                }
            }

            // Mobile active link
            const mobileActiveLink = document.querySelector('nav a[href="contact.php"]');
            if (mobileActiveLink) {
                mobileActiveLink.classList.remove("text-slate-300");
                mobileActiveLink.classList.add("text-emerald-400");
            }
        });
    </script>
</body>
</html>