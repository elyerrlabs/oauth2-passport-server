<footer id="footer"
    class="bg-gradient-to-r from-[var(--color-primary)] to-[var(--color-secondary)] text-white py-8 mt-0">
    <div class="container mx-auto px-4">
        <!-- Main information -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-8">
            <div class="mb-6 md:mb-0 text-center md:text-left">
                <h3 class="text-xl font-bold mb-2 flex items-center justify-center md:justify-start">
                    <i class="mdi mdi-security-network mr-2"></i>
                    OAuth2 Passport Server
                </h3>
                <p class="text-blue-100">Secure authentication system with OAuth2</p>
                <p class="text-blue-100 mt-1">yerel9212@yahoo.es</p>
            </div>

            <div class="flex space-x-4">
                <!-- Email -->
                <a href="mailto:yerel9212@yahoo.es"
                    class="bg-white/20 hover:bg-white/30 transition-all duration-300 p-3 rounded-full"
                    title="Send email">
                    <i class="mdi mdi-email text-xl"></i>
                </a>

                <!-- GitHub -->
                <a href="https://github.com/elyerr" target="_blank" rel="noopener noreferrer"
                    class="bg-white/20 hover:bg-white/30 transition-all duration-300 p-3 rounded-full"
                    title="GitHub Profile">
                    <i class="mdi mdi-github text-xl"></i>
                </a>
            </div>
        </div>

        <!-- Repositories -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- GitHub -->
            <div class="bg-white/10 rounded-lg p-4 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center mb-3">
                    <i class="mdi mdi-github text-2xl mr-2"></i>
                    <h4 class="font-semibold">GitHub</h4>
                </div>
                <p class="text-blue-100 text-sm mb-2">Main project repository</p>
                <a href="https://github.com/elyerr/oauth2-passport-server" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center text-blue-200 hover:text-white transition-colors text-sm">
                    <i class="mdi mdi-link mr-1"></i>
                    elyerr/oauth2-passport-server
                </a>
            </div>

            <!-- GitLab -->
            <div class="bg-white/10 rounded-lg p-4 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center mb-3">
                    <i class="mdi mdi-gitlab text-2xl mr-2"></i>
                    <h4 class="font-semibold">GitLab</h4>
                </div>
                <p class="text-blue-100 text-sm mb-2">GitLab repository</p>
                <a href="https://gitlab.com/elyerr/oauth2-passport-server" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center text-blue-200 hover:text-white transition-colors text-sm">
                    <i class="mdi mdi-link mr-1"></i>
                    elyerr/oauth2-passport-server
                </a>
            </div>

            <!-- Docker Hub -->
            <div class="bg-white/10 rounded-lg p-4 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center mb-3">
                    <i class="mdi mdi-docker text-2xl mr-2"></i>
                    <h4 class="font-semibold">Docker Hub</h4>
                </div>
                <p class="text-blue-100 text-sm mb-2">Official Docker images</p>
                <a href="https://hub.docker.com/u/elyerr" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center text-blue-200 hover:text-white transition-colors text-sm">
                    <i class="mdi mdi-link mr-1"></i>
                    elyerr/oauth2-passport-server
                </a>
            </div>

            <!-- Packagist -->
            <div class="bg-white/10 rounded-lg p-4 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-center mb-3">
                    <i class="mdi mdi-package-variant text-2xl mr-2"></i>
                    <h4 class="font-semibold">Packagist</h4>
                </div>
                <p class="text-blue-100 text-sm mb-2">PHP Composer package</p>
                <a href="https://packagist.org/packages/elyerr" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center text-blue-200 hover:text-white transition-colors text-sm">
                    <i class="mdi mdi-link mr-1"></i>
                    elyerr
                </a>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-white/20 pt-6 text-center">
            <p class="text-blue-100">
                &copy; {{ now()->format('Y') }} OAuth2 Passport Server. All rights reserved.
            </p>
            <p class="text-blue-200 text-sm mt-1">
                Developed with <i class="mdi mdi-heart text-red-400"></i> by Elyerr
            </p>
        </div>
    </div>
</footer>
