@extends('layouts.app')

@push('head')
    @vite(['resources/js/ecommerce.js'])
    <link nonce="{{ $nonce }}" rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link nonce="{{ $nonce }}" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />
@endpush

@section('content')
    @inertia
@endsection

@section('footer')
    <footer id="footer" class="bg-gray-800 text-white py-12 hidden">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">ShopNow</h3>
                    <p class="text-gray-400">
                        Your one-stop shop for all your needs. Quality
                        products at affordable prices.
                    </p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Quick Links</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white">Home</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white">Products</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white">Categories</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white">Deals</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Help</h4>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white">Contact</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white">FAQ</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white">Shipping</a>
                        </li>
                        <li>
                            <a href="#" class="text-gray-400 hover:text-white">Returns</a>
                        </li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <p class="text-gray-400 mb-2">
                        <i class="fas fa-map-marker-alt mr-2"></i> 123 Main
                        St, City
                    </p>
                    <p class="text-gray-400 mb-2">
                        <i class="fas fa-phone mr-2"></i> +1 234 567 890
                    </p>
                    <p class="text-gray-400">
                        <i class="fas fa-envelope mr-2"></i>
                        info@shopnow.com
                    </p>
                    <div class="flex space-x-4 mt-4">
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-gray-400 hover:text-white"><i class="fab fa-pinterest"></i></a>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2023 ShopNow. All rights reserved.</p>
            </div>
        </div>
    </footer>
@endsection
