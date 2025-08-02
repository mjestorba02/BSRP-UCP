@extends('layouts.dapp')

@section('content')

@include('dpartials.nav')
@include('dpartials.sidebar')

<div class="p-4 sm:ml-64 font-[Poppins]">
    <div class="p-4 rounded-lg dark:border-gray-700 mt-14">
        @if(view()->exists($mainContent))
            @include($mainContent)
        @else
            <div class="text-red-500">Content view not found: {{ $mainContent }}</div>
        @endif
    </div>
</div>


@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.css" rel="stylesheet" />
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
    function displayPhilippineTime() {
        // Create a date object for Philippine time (UTC+8)
        const options = {
            timeZone: 'Asia/Manila',
            weekday: 'long',
            year: 'numeric',
            month: 'long',
            day: 'numeric',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit',
            hour12: true
        };

        // Get the formatted date and time string
        const philippineDateTime = new Date().toLocaleString('en-PH', options);

        // Update the element with the current time
        const timeElement = document.getElementById('philippineTime');
        if (timeElement) {
            timeElement.textContent = philippineDateTime;
        }
    }

    // Initial call to display the time
    displayPhilippineTime();

    // Update the time every second
    setInterval(displayPhilippineTime, 1000);

    // Add event listener to ensure the function runs after DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
    displayPhilippineTime();
    });
</script>
@endpush