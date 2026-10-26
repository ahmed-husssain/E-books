
    <style>
        .footer-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }
        
        .footer-card {
            background: rgba(30, 41, 59, 0.3);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(148, 163, 184, 0.1);
        }
        
        .link-hover {
            position: relative;
            overflow: hidden;
        }
        
        .link-hover::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: -100%;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #10b981, transparent);
            transition: left 0.5s ease;
        }
        
        .link-hover:hover::after {
            left: 100%;
        }
        
        .social-icon {
            transition: all 0.3s ease;
        }
        
        .social-icon:hover {
            transform: translateY(-3px) scale(1.1);
            filter: drop-shadow(0 4px 8px rgba(16, 185, 129, 0.3));
        }
        
        .floating-elements {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .floating-dot {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(16, 185, 129, 0.3);
            border-radius: 50%;
            animation: float 6s ease-in-out infinite;
        }
        
        .floating-dot:nth-child(1) {
            top: 20%;
            left: 10%;
            animation-delay: 0s;
        }
        
        .floating-dot:nth-child(2) {
            top: 60%;
            left: 80%;
            animation-delay: 2s;
        }
        
        .floating-dot:nth-child(3) {
            top: 40%;
            left: 60%;
            animation-delay: 4s;
        }
        
        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); opacity: 0.3; }
            50% { transform: translateY(-20px) rotate(180deg); opacity: 0.8; }
        }
        
        .pulse-border {
            animation: 3s ease-in-out infinite;
        }
        
        @keyframes {
            0%, 100% { border-color: rgba(148, 163, 184, 0.1); }
            50% { border-color: rgba(16, 185, 129, 0.3); }
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #10b981, #34d399);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
    </style>
        <footer class="py-8 px-6 w-full relative overflow-hidden bg-gray-900">
            <!-- Floating Background Elements -->
            <div class="floating-elements">
                <div class="floating-dot"></div>
                <div class="floating-dot"></div>
                <div class="floating-dot"></div>
            </div>
            
            <!-- Main Content -->
            <div class="container mx-auto relative z-10">
                <div class="grid md:grid-cols-4 gap-8">
                    <!-- Logo Section -->
                    <div class="footer-card rounded-2xl p-6">
                        <img src="../../Logos/logo_white.png"
                             alt="Story Shelf Logo"
                             class="w-48 h-auto object-contain cursor-pointer mb-4 hover:scale-105 transition-transform duration-300" />
                        <p class="text-slate-300 leading-relaxed text-sm">
                            Discover your next great read with our curated collection of books and engaging reading competitions.
                        </p>
                        <div class="mt-4 flex items-center space-x-2">
                            <div class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></div>
                            <span class="text-emerald-400 text-xs font-medium">Active Community</span>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="footer-card rounded-2xl p-6">
                        <h4 class="text-lg font-bold text-gradient mb-6 flex items-center">
                            <i class="ri-link mr-2"></i>
                            Quick Links
                        </h4>
                        <ul class="space-y-3">
                            <li>
                                <a href="../Pages/home.php" class="link-hover text-slate-300 hover:text-emerald-400 transition-all duration-300 flex items-center group">
                                    <i class="ri-home-line mr-2 group-hover:text-emerald-400"></i>
                                    <span>Home</span>
                                </a>
                            </li>
                            <li>
                                <a href="../Pages/books.php" class="link-hover text-slate-300 hover:text-emerald-400 transition-all duration-300 flex items-center group">
                                    <i class="ri-book-open-line mr-2 group-hover:text-emerald-400"></i>
                                    <span>Books</span>
                                </a>
                            </li>
                            <li>
                                <a href="../Pages/category.php" class="link-hover text-slate-300 hover:text-emerald-400 transition-all duration-300 flex items-center group">
                                    <i class="ri-grid-line mr-2 group-hover:text-emerald-400"></i>
                                    <span>Categories</span>
                                </a>
                            </li>
                            <li>
                                <a href="../Pages/competitions.php" class="link-hover text-slate-300 hover:text-emerald-400 transition-all duration-300 flex items-center group">
                                    <i class="ri-trophy-line mr-2 group-hover:text-emerald-400"></i>
                                    <span>Competition</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Support -->
                    <div class="footer-card rounded-2xl p-6">
                        <h4 class="text-lg font-bold text-gradient mb-6 flex items-center">
                            <i class="ri-customer-service-line mr-2"></i>
                            Support
                        </h4>
                        <ul class="space-y-3">
                            <li>
                                <a href="About.php" class="link-hover text-slate-300 hover:text-emerald-400 transition-all duration-300 flex items-center group">
                                    <i class="ri-information-line mr-2 group-hover:text-emerald-400"></i>
                                    <span>About Us</span>
                                </a>
                            </li>
                            <li>
                                <a href="contact.php" class="link-hover text-slate-300 hover:text-emerald-400 transition-all duration-300 flex items-center group">
                                    <i class="ri-mail-line mr-2 group-hover:text-emerald-400"></i>
                                    <span>Contact</span>
                                </a>
                            </li>
                            <li>
                                <a href="contact.php#faq" class="link-hover text-slate-300 hover:text-emerald-400 transition-all duration-300 flex items-center group">
                                    <i class="ri-question-line mr-2 group-hover:text-emerald-400"></i>
                                    <span>FAQ</span>
                                </a>
                            </li>
                        </ul>
                    </div>

                    <!-- Connect -->
                    <div class="footer-card rounded-2xl p-6">
                        <h4 class="text-lg font-bold text-gradient mb-6 flex items-center">
                            <i class="ri-share-line mr-2"></i>
                            Connect
                        </h4>
                        <div class="flex space-x-4 mb-4">
                            <a href="https://facebook.com" class="social-icon text-slate-300 hover:text-emerald-400 transition-all duration-300 text-2xl bg-slate-800/50 p-3 rounded-full border border-slate-700 hover:border-emerald-400">
                                <i class="ri-facebook-fill"></i>
                            </a>
                            <a href="https://x.com" class="social-icon text-slate-300 hover:text-emerald-400 transition-all duration-300 text-2xl bg-slate-800/50 p-3 rounded-full border border-slate-700 hover:border-emerald-400">
                                <i class="ri-twitter-x-fill"></i>
                            </a>
                            <a href="https://linkedin.com" class="social-icon text-slate-300 hover:text-emerald-400 transition-all duration-300 text-2xl bg-slate-800/50 p-3 rounded-full border border-slate-700 hover:border-emerald-400">
                                <i class="ri-linkedin-fill"></i>
                            </a>
                        </div>
                        <div class="text-slate-400 text-xs flex flex-col items-center text-center">
                            <p class="mb-1">
                                <i class="ri-mail-line mr-1"></i>
                                newsletter@storyshelf.com
                            </p>
                            <p>
                                <i class="ri-group-line mr-1"></i>
                                Join 10K+ readers
                            </p>
                        </div>

                    </div>
                </div>

                <!-- Bottom Section -->
                <div class="mt-6 pt-4 border-t border-slate-700/50">
                    <div class="flex flex-col items-center justify-center text-center">
                        <p class="text-slate-400 text-sm flex items-center justify-center">
                            <i class="ri-copyright-line mr-1"></i>
                            2025 Story Shelf. All rights reserved.
                        </p>
                    </div>
                </div>
        </footer>


