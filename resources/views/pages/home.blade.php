@extends('layout.layout')

@php
@endphp

@section('meta_title', 'Kuwait International Fair')
@section('meta_description', '')

@section('og')
    <meta property="og:title" content="Kuwait International Fair" />
    <meta property="og:description" content="" />
    {{-- <meta property="og:image" content="{{ asset('img/profile_pic.png') }}" /> --}}
    <meta property="og:url" content="{{ url()->current() }}" />
    <link rel="canonical" href="{{ url()->current() }}" />
@endsection

@section('content')
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
        <div class="px-4 py-4 mx-auto sm:px-6 lg:px-10">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <img src="/img/header.svg" alt="" srcset="">
                </div>

                <!-- Navigation -->
                <nav class="flex items-center space-x-8">
                    <a href="#" class="text-sm font-medium text-gray-700 transition-colors hover:text-gray-900">
                        Events
                    </a>
                    <a href="#" class="text-sm font-medium text-gray-700 transition-colors hover:text-gray-900">
                        My Bookings
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="px-10 py-8">
        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-green-800">{{ session('success') }}</p>
            </div>
        @endif

        <!-- Error Messages -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <ul class="list-disc list-inside text-red-800">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Title -->
        <div class="mb-6">
            <h1 class="mb-2 text-3xl font-bold text-gray-900">{{ $event?->name ?? 'No Event Available' }}</h1>
            <p class="text-gray-600">
                @if ($hall)
                    {{ $hall->name }} - Select your booth location on the interactive floor map
                @else
                    No halls available for this event
                @endif
            </p>
        </div>

        <!-- Legend with Filters -->
        <div class="flex flex-wrap gap-6 mb-6">
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="radio" name="status" value="all"
                    class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-blue-500" checked>
                <span class="text-sm text-gray-700">All</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="radio" name="status" value="available"
                    class="w-4 h-4 text-gray-600 border-gray-300 focus:ring-gray-500">
                <span class="text-sm text-gray-700">Available</span>
            </label>
            <label class="flex items-center space-x-2 cursor-pointer">
                <input type="radio" name="status" value="booked"
                    class="w-4 h-4 text-red-600 border-gray-300 focus:ring-red-500">
                <span class="text-sm text-gray-700">Booked </span>
            </label>
        </div>

        <!-- Floor Map -->
        <div class="overflow-hidden bg-white border border-gray-200 rounded-lg shadow-sm">
            <div id="floormap" class="flex items-center justify-center w-full p-4 bg-gray-50"></div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="mt-16 bg-white border-t border-gray-200">
        <div class="px-4 pt-12 pb-6 mx-auto sm:px-6 lg:px-10">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
                <!-- Column 1: Kuwait International Fair -->
                <div class="flex flex-col items-start justify-start gap-4">
                    <div class="flex items-center space-x-3">
                        <img src="/img/header.svg" alt="" srcset="">
                    </div>
                    <p class="text-sm text-gray-600">
                        Professional event booth booking platform for conferences and exhibitions.
                    </p>
                </div>

                <!-- Column 2: Platform -->
                <div>
                    <h3 class="mb-4 text-sm font-semibold text-gray-900">Platform</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="text-sm text-gray-600 transition-colors hover:text-gray-900">
                                Browse Events
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Column 3: Support -->
                <div>
                    <h3 class="mb-4 text-sm font-semibold text-gray-900">Support</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="text-sm text-gray-600 transition-colors hover:text-gray-900">
                                Help Center
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-sm text-gray-600 transition-colors hover:text-gray-900">
                                Contact Us
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-sm text-gray-600 transition-colors hover:text-gray-900">
                                Guidelines
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Column 4: Legal -->
                <div>
                    <h3 class="mb-4 text-sm font-semibold text-gray-900">Legal</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="#" class="text-sm text-gray-600 transition-colors hover:text-gray-900">Terms of
                                Service
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-sm text-gray-600 transition-colors hover:text-gray-900">Privacy
                                Policy
                            </a>
                        </li>
                        <li>
                            <a href="#" class="text-sm text-gray-600 transition-colors hover:text-gray-900">
                                Cancellation
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Copyright -->
            <div class="pt-8 mt-8 border-t border-gray-200">
                <p class="text-sm text-center text-gray-600">&copy; 2025 Kuwait International Fair. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Dialog Modal -->
    <div id="blockDialog" class="fixed inset-0 z-50 items-center justify-center hidden bg-opacity-50 bg-black/35">
        <div class="w-full max-w-md mx-4 bg-white rounded-lg shadow-xl">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Booth: <span id="dialogBlockId"></span></h3>
                <button id="closeDialog" type="button" class="text-2xl text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <!-- Booked Message (hidden by default) -->
            <div id="bookedMessage" class="hidden px-6 py-8 text-center">
                <div class="flex items-center justify-center w-16 h-16 mx-auto mb-4 bg-red-100 rounded-full">
                    <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h4 class="mb-2 text-lg font-semibold text-gray-900">Booth Not Available</h4>
                <p class="mb-6 text-gray-600">This booth has already been booked.</p>
                <button id="bookedCloseBtn" type="button"
                    class="px-4 py-2 text-sm font-medium text-white bg-gray-600 rounded-md hover:bg-gray-700">
                    Close
                </button>
            </div>

            <!-- Submission Form -->
            <form id="submissionForm" action="{{ route('submissions.store') }}" method="POST" class="px-6 py-4">
                @csrf
                <input type="hidden" name="event_id" value="{{ $event?->id }}">
                <input type="hidden" name="hall_id" value="{{ $hall?->id }}">
                <input type="hidden" id="booth_id" name="booth_id" value="">

                <div class="space-y-4">
                    <div>
                        <label for="phone_number" class="block mb-2 text-sm font-medium text-gray-700">
                            Phone Number <span class="text-red-500">*</span>
                        </label>
                        <input type="tel" id="phone_number" name="phone_number" required
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="+965 XXXX XXXX">
                    </div>

                    <div>
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700">
                            Email (Optional)
                        </label>
                        <input type="email" id="email" name="email"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="example@domain.com">
                    </div>

                    <div>
                        <label for="company_name" class="block mb-2 text-sm font-medium text-gray-700">
                            Company Name (Optional)
                        </label>
                        <input type="text" id="company_name" name="company_name"
                            class="block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                            placeholder="Your company name">
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" id="cancelBtn"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                            Submit Request
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const floormap = document.getElementById('floormap');
            let interactiveElements = [];
            let submissions = {};

            // Handle status radio button changes
            const statusRadios = document.querySelectorAll('input[name="status"]');
            statusRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('status', this.value);
                    window.history.pushState({}, '', url);
                    filterBooths(this.value);
                });
            });

            // Set the correct radio button based on URL parameter
            const urlParams = new URLSearchParams(window.location.search);
            const statusParam = urlParams.get('status') || 'all';

            const radio = document.querySelector(`input[name="status"][value="${statusParam}"]`);
            if (radio) {
                radio.checked = true;
            }

            // Filter booths based on status
            function filterBooths(filterValue) {
                interactiveElements.forEach(gElement => {
                    const boothId = gElement.getAttribute('id');
                    const submission = submissions[boothId];

                    let shouldShow = true;

                    if (filterValue === 'available') {
                        // Show only booths without submission, or with rejected status
                        shouldShow = !submission || submission.status === 'rejected';
                    } else if (filterValue === 'booked') {
                        // Show only booths with pending or approved status
                        shouldShow = submission && (submission.status === 'pending' || submission.status ===
                            'approved');
                    }
                    // If 'all', shouldShow remains true

                    if (shouldShow) {
                        gElement.style.display = '';
                        gElement.style.opacity = '1';
                    } else {
                        gElement.style.display = 'none';
                    }
                });
            }
            @if ($hall && $hall->getFirstMediaUrl('floor_map'))
                fetch('{{ $hall->getFirstMediaUrl('floor_map') }}')
                    .then(response => response.text())
                    .then(svgData => {
                        floormap.innerHTML = svgData;
                        console.log('floormap loaded successfully');
                    })
                    .catch(error => {
                        console.error('Error loading floormap:', error);
                    });
            @else
                floormap.innerHTML = '<p class="p-8 text-gray-500">No floor map available for this hall</p>';
            @endif

            // Wait a bit for the SVG to be inserted into DOM
            setTimeout(() => {
                const svgElement = floormap.querySelector('svg');
                if (svgElement) {
                    const floorMapGroup = svgElement.querySelector('g#Floor\\ Map, g[id="Floor Map"]');
                    const elements = floorMapGroup ? floorMapGroup.querySelectorAll('g[id]') : [];
                    interactiveElements = Array.from(elements);

                    console.log(`Found ${interactiveElements.length} interactive elements`);

                    // Get submissions data from PHP
                    submissions = @json($submissions);
                    console.log('Submissions:', submissions);

                    // Add hover effects to each element
                    interactiveElements.forEach(gElement => {
                        const rect = gElement.querySelector('rect');
                        if (rect) {
                            const boothId = gElement.getAttribute('id');

                            // Determine original color based on submission status
                            let originalFill =
                                '#7F7F7F'; // Default gray for rejected or no submission
                            const originalOpacity = '0.5';

                            // Check if this booth has a submission
                            if (submissions[boothId]) {
                                const status = submissions[boothId].status;
                                if (status === 'approved') {
                                    originalFill = '#EF4444'; // Red
                                } else if (status === 'pending') {
                                    originalFill = '#F97316'; // Orange
                                }
                                // If rejected, keep gray (#7F7F7F)
                            }

                            // Set the booth color based on status
                            rect.setAttribute('fill', originalFill);
                            rect.setAttribute('fill-opacity', originalOpacity);

                            const hoverFill = '#3B82F6'; // Blue hover color to match legend
                            const hoverOpacity = '0.8';

                            // Add mouseenter event
                            gElement.addEventListener('mouseenter', function() {
                                rect.setAttribute('fill', hoverFill);
                                rect.setAttribute('fill-opacity', hoverOpacity);
                                gElement.style.cursor = 'pointer';
                            });

                            // Add mouseleave event
                            gElement.addEventListener('mouseleave', function() {
                                rect.setAttribute('fill', originalFill);
                                rect.setAttribute('fill-opacity', originalOpacity);
                            });

                            // Add click event to show dialog
                            gElement.addEventListener('click', function() {
                                const blockId = gElement.getAttribute('id');
                                const dialog = document.getElementById('blockDialog');
                                const dialogBlockId = document.getElementById('dialogBlockId');
                                const boothIdInput = document.getElementById('booth_id');
                                const submissionForm = document.getElementById('submissionForm');
                                const bookedMessage = document.getElementById('bookedMessage');

                                // Set booth ID in dialog title
                                dialogBlockId.textContent = blockId;
                                boothIdInput.value = blockId;

                                // Check if booth is booked (pending or approved)
                                const submission = submissions[blockId];
                                const isBooked = submission && (submission.status === 'pending' || submission.status === 'approved');

                                if (isBooked) {
                                    // Show booked message, hide form
                                    submissionForm.classList.add('hidden');
                                    bookedMessage.classList.remove('hidden');
                                } else {
                                    // Show form, hide booked message
                                    submissionForm.classList.remove('hidden');
                                    bookedMessage.classList.add('hidden');
                                }

                                // Show dialog
                                dialog.classList.remove('hidden');
                                dialog.classList.add('flex');
                            });
                        }
                    });

                    // Apply initial filter based on URL parameter
                    filterBooths(statusParam);
                }
            }, 100);

            // Close dialog functionality
            const dialog = document.getElementById('blockDialog');
            const closeBtn = document.getElementById('closeDialog');
            const cancelBtn = document.getElementById('cancelBtn');
            const bookedCloseBtn = document.getElementById('bookedCloseBtn');
            const submissionForm = document.getElementById('submissionForm');

            function closeDialog() {
                dialog.classList.add('hidden');
                dialog.classList.remove('flex');
                // Reset form
                submissionForm.reset();
            }

            closeBtn.addEventListener('click', closeDialog);
            cancelBtn.addEventListener('click', closeDialog);
            bookedCloseBtn.addEventListener('click', closeDialog);

            // Close dialog when clicking outside
            dialog.addEventListener('click', function(e) {
                if (e.target === dialog) {
                    closeDialog();
                }
            });
        });
    </script>
@endsection
