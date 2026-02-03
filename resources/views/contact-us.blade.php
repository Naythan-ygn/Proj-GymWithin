{{-- Extend the main layout provided --}}
@extends('layouts.client-app')

{{-- Set a specific title for this page --}}
@section('title', 'Contact Us - GymWithin')

{{-- The main content section --}}
@section('content')
    <div class="min-h-screen bg-[#111111] text-white font-sans">

        <section class="hero-background relative h-[40vh] flex items-center px-10 md:px-20">
            <div class="z-10 max-w-2xl">
                <h1 class="text-5xl md:text-6xl font-bold mb-4 tracking-tight">Get in Touch</h1>
                <p class="text-lg md:text-xl text-gray-300">We're here to help you achieve your fitness goals.</p>
            </div>
            <div class="absolute inset-0 bg-gradient-to-r from-black/70 to-transparent"></div>
        </section>

        <section class="bg-[#111111] py-16 px-10 md:px-20">
            <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-16">

                <div class="space-y-8">
                    <h2 class="text-2xl font-bold mb-6">Reach Out to Us</h2>

                    <div class="space-y-6">
                        <div class="flex items-start gap-4">
                            <span class="text-orange-500 mt-1 text-xl"><i class="fas fa-map-marker-alt"></i></span>
                            <p class="text-gray-300">GymWithin HQ, 123 Fitness Ave, New York, NY 10001</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-orange-500 text-xl"><i class="fas fa-phone-alt"></i></span>
                            <p class="text-gray-300">+1 (555) 123-4567</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-orange-500 text-xl"><i class="fas fa-envelope"></i></span>
                            <p class="text-gray-300">support@gymwithin.com</p>
                        </div>
                    </div>

                    <div class="flex gap-4 text-2xl text-gray-400">
                        <a href="#" class="hover:text-orange-500 transition"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="hover:text-orange-500 transition"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="hover:text-orange-500 transition"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="hover:text-orange-500 transition"><i class="fab fa-linkedin"></i></a>
                    </div>

                    <div class="w-full h-64 rounded-xl overflow-hidden border border-gray-700 relative group">
                        <iframe class="w-full h-full grayscale invert opacity-80 contrast-125"
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d238.71600478717855!2d96.14063127566058!3d16.803715683117453!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x30c1eb4498d99301%3A0x51d853db5df2af6e!2sStrategy%20First%20International%20College%20-%20Teaching%20Centre%20PCT%20(Myaynigone)!5e0!3m2!1sen!2sus!4v1770137741577!5m2!1sen!2sus"
                            width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"></iframe>

                        <div class="absolute inset-0 pointer-events-none bg-orange-500/5 mix-blend-color"></div>
                    </div>
                </div>

                <div>
                    <h2 class="text-2xl font-bold mb-6">Send Us a Message</h2>
                    <form action="#" class="space-y-4">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" placeholder="Name" class="contact-input">
                            <input type="email" placeholder="Email" class="contact-input">
                        </div>
                        <input type="text" placeholder="Subject" class="contact-input">
                        <textarea placeholder="Message" rows="5" class="contact-input resize-none"></textarea>

                        <button type="submit"
                            class="w-full bg-[#f3863c] hover:bg-[#e0752d] text-white font-bold py-4 rounded-xl transition-all shadow-lg">
                            Send Message
                        </button>
                    </form>
                </div>

            </div>
        </section>
        
    </div>
@endsection