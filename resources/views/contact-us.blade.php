{{-- Extend the main layout provided --}}
@extends('layouts.client-app')

{{-- Set a specific title for this page --}}
@section('title', 'Contact Us - GymWithin')

{{-- The main content section --}}
@section('content')
    <div class="min-h-screen bg-[#111111] text-white font-sans">

        <section class="hero-background relative h-[40vh] flex items-center px-10 md:px-20">
            <div class="container mx-auto px-6 z-10">
                <div class="max-w-2xl loading-shield">
                    <h1 class="text-5xl md:text-6xl font-black font-bold mb-4 leading-tight">Get in Touch</h1>
                    <p class="text-lg md:text-xl text-gray-300">We're here to help you achieve your fitness goals.</p>
                </div>
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
                            <p class="text-gray-300">GymWithin HQ, MM, Dhammazedi Road, Yangon, Myanmar (Burma)</p>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-orange-500 text-xl"><i class="fas fa-phone-alt"></i></span>
                            <p class="text-gray-300">(+95) 988-821-8097</p>
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

                    @if(session('success'))
                        <div class="rounded-xl border border-green-500/30 bg-green-500/10 p-4 mb-4 text-green-100">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-4 mb-4 text-red-100">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div id="contact-form-message" class="hidden rounded-xl p-4 mb-4 text-sm"></div>

                    <form id="contact-form" action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input type="text" name="name" placeholder="Name" value="{{ old('name') }}"
                                class="contact-input">
                            <input type="email" name="email" placeholder="Email" value="{{ old('email') }}"
                                class="contact-input">
                        </div>
                        <input type="text" name="subject" placeholder="Subject" value="{{ old('subject') }}"
                            class="contact-input">
                        <textarea name="message" placeholder="Message" rows="5"
                            class="contact-input resize-none">{{ old('message') }}</textarea>

                        @if ($errors->any())
                            <div class="rounded-xl border border-red-500/30 bg-red-500/10 p-4 text-red-100">
                                <ul class="list-disc pl-5 text-sm">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <button type="submit"
                            class="w-full bg-[#f3863c] hover:bg-[#e0752d] text-white font-bold py-4 rounded-xl transition-all shadow-lg">
                            Send Message
                        </button>
                    </form>
                </div>

                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const form = document.getElementById('contact-form');
                        const messageBox = document.getElementById('contact-form-message');

                        if (!form || !messageBox) {
                            return;
                        }

                        form.addEventListener('submit', async function (event) {
                            event.preventDefault();

                            messageBox.classList.add('hidden');
                            messageBox.innerHTML = '';

                            const formData = new FormData(form);
                            const token = document.querySelector('input[name="_token"]').value;

                            try {
                                const response = await fetch(form.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': token,
                                        'Accept': 'application/json',
                                    },
                                    body: formData,
                                });

                                const data = await response.json();

                                if (!response.ok) {
                                    const errors = data.errors ? Object.values(data.errors).flat() : [data.message || 'Unable to submit message.'];
                                    messageBox.className = 'rounded-xl border border-red-500/30 bg-red-500/10 p-4 mb-4 text-red-100';
                                    messageBox.innerHTML = '<ul class="list-disc pl-5 text-sm">' + errors.map(err => '<li>' + err + '</li>').join('') + '</ul>';
                                    messageBox.classList.remove('hidden');
                                    return;
                                }

                                messageBox.className = 'rounded-xl border border-green-500/30 bg-green-500/10 p-4 mb-4 text-green-100';
                                messageBox.textContent = data.message || 'Thank you! Your message has been received.';
                                messageBox.classList.remove('hidden');
                                form.reset();
                            } catch (error) {
                                messageBox.className = 'rounded-xl border border-red-500/30 bg-red-500/10 p-4 mb-4 text-red-100';
                                messageBox.textContent = 'There was an error submitting the form. Please try again.';
                                messageBox.classList.remove('hidden');
                            }
                        });
                    });
                </script>
        </section>
    </div>
@endsection
