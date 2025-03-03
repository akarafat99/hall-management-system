<?php

/**
 * Popup Notification System
 * 
 * This file can be included in any PHP file to display a popup notification
 * in the top right corner with a countdown timer and close button.
 * 
 * Usage:
 * 1. Include this file in your PHP script
 * 2. Call showPopup() function with your message
 */

function showPopup($message = "Notification", $duration = 5000)
{
    // Generate a unique ID for this popup instance
    $popupId = 'popup_' . uniqid();

    // Output the HTML and JavaScript for the popup
    echo <<<HTML
    <!-- Popup Notification -->
    <div id="{$popupId}" class="popup-notification shadow">
        <div class="popup-content">
            <div class="popup-header">
                <span class="countdown-text">Closing in <span class="countdown">5</span></span>
                <button type="button" class="close-btn" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="popup-body">
                {$message}
            </div>
        </div>
    </div>

    <!-- Bootstrap CSS (if not already included) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Custom CSS for popup styling -->
    <style>
        .popup-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            width: 300px;
            background-color: white;
            border-radius: 5px;
            z-index: 9999;
            opacity: 0;
            transform: translateY(-20px);
            transition: opacity 0.3s, transform 0.3s;
        }
        
        .popup-notification.show {
            opacity: 1;
            transform: translateY(0);
        }
        
        .popup-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
            background-color: #f8f9fa;
            border-top-left-radius: 5px;
            border-top-right-radius: 5px;
        }
        
        .popup-body {
            padding: 15px;
        }
        
        .close-btn {
            background: none;
            border: none;
            font-size: 1.5rem;
            font-weight: 700;
            line-height: 1;
            color: #000;
            opacity: 0.5;
            cursor: pointer;
        }
        
        .close-btn:hover {
            opacity: 0.75;
        }
        
        .countdown-text {
            font-size: 0.875rem;
            color: #6c757d;
        }
    </style>

    <!-- JavaScript for popup functionality -->
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var popup = document.getElementById("{$popupId}");
        var closeBtn = popup.querySelector(".close-btn");
        var countdownEl = popup.querySelector(".countdown");
        
        setTimeout(function() { 
            popup.classList.add("show"); 
        }, 100);
        
        var totalTime = {$duration};
        var timeLeft = Math.floor(totalTime / 1000);
        countdownEl.textContent = timeLeft;
        
        var countdownInterval = setInterval(function() {
            timeLeft--;
            countdownEl.textContent = timeLeft;
            
            if (timeLeft <= 0) {
                clearInterval(countdownInterval);
                closePopup();
            }
        }, 1000);
        
        closeBtn.addEventListener("click", function() {
            clearInterval(countdownInterval);
            closePopup();
        });
        
        function closePopup() {
            popup.classList.remove("show");
            setTimeout(function() {
                popup.remove();
            }, 300);
        }
        
        setTimeout(function() {
            if (popup && document.body.contains(popup)) {
                clearInterval(countdownInterval);
                closePopup();
            }
        }, totalTime);
    });
    </script>
HTML;
}
?>

<?php
// Example usage
// showPopup("Your notification message here!", 10000);
?>