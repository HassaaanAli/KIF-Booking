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
    <div class="flex flex-col items-center justify-start w-full h-full">
        <div id="floormap" class="flex items-center justify-center w-full bg-black aspect-video"></div>
    </div>

    <!-- Dialog Modal -->
    <div id="blockDialog" class="fixed inset-0 z-50 items-center justify-center hidden bg-opacity-50 bg-black/35">
        <div class="w-full max-w-md mx-4 bg-white rounded-lg shadow-xl">
            <div class="flex items-center justify-between px-6 py-4 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Block ID: <span id="dialogBlockId"></span></h3>
                <button id="closeDialog" class="text-2xl text-gray-400 hover:text-gray-600">&times;</button>
            </div>

            <div class="px-6 py-4">
                <!-- Add your form here -->
                <div id="dialogContent">
                    <p class="mb-4 text-gray-600">Add your form content here</p>
                </div>
            </div>
        </div>
    </div>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const floormap = document.getElementById('floormap');
            fetch('/img/fm.svg')
                .then(response => response.text())
                .then(svgData => {
                    floormap.innerHTML = svgData;

                    console.log('floormap loaded successfully');
                })
                .catch(error => {
                    console.error('Error loading floormap:', error);
                });

            // Wait a bit for the SVG to be inserted into DOM
            setTimeout(() => {
                // Get all <g> elements with IDs inside the SVG, excluding the parent "Floor Map"
                const svgElement = floormap.querySelector('svg');
                if (svgElement) {
                    const floorMapGroup = svgElement.querySelector('g#Floor\\ Map, g[id="Floor Map"]');
                    const interactiveElements = floorMapGroup ? floorMapGroup.querySelectorAll('g[id]') :
                [];

                    console.log(`Found ${interactiveElements.length} interactive elements`);

                    // Add hover effects to each element
                    interactiveElements.forEach(gElement => {
                        const rect = gElement.querySelector('rect');
                        if (rect) {
                            // Store original colors
                            const originalFill = '#7F7F7F';
                            const originalOpacity = '0.5';
                            const hoverFill = '#4A90E2'; // Blue hover color
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
                                const dialogBlockId = document.getElementById(
                                    'dialogBlockId');

                                dialogBlockId.textContent = blockId;
                                dialog.classList.remove('hidden');
                                dialog.classList.add('flex');
                            });
                        }
                    });
                }
            }, 100);

            // Close dialog functionality
            const dialog = document.getElementById('blockDialog');
            const closeBtn = document.getElementById('closeDialog');

            closeBtn.addEventListener('click', function() {
                dialog.classList.add('hidden');
                dialog.classList.remove('flex');
            });

            // Close dialog when clicking outside
            dialog.addEventListener('click', function(e) {
                if (e.target === dialog) {
                    dialog.classList.add('hidden');
                    dialog.classList.remove('flex');
                }
            });
        });
    </script>
@endsection
