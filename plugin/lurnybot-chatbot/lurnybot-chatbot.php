<?php
/*
Plugin Name: LurnyBot
Description: LurnyBot is a Gen AI-powered chatbot for websites, leveraging LLMs to generate responses using company data, website content, and PDFs. This plugin adds a chatbot button to the bottom-right of every page, toggling a chatbot iframe for user interaction.
Version: 1.1
Author: Lurny Innovative Labs
*/

// Hook to the wp_footer action to inject the chatbot button and iframe
function inject_chatbot_popup() {
    ?>
    <div id="chatbot-footer" style="
        position: fixed; 
        bottom: 20px; 
        right: 20px; 
        z-index: 9999; 
        display: flex; 
        flex-direction: column; 
        align-items: center;
    ">
        <!-- Toggle Button -->
        <button
            id="chatbot-toggle-button"
            style="
                background: none;
                border: none;
                border-radius: 50%;
                width: 60px;
                height: 60px;
                padding: 0;
                cursor: pointer;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
                z-index: 10000;
                overflow: hidden;
            "
            onclick="toggleChatbot()"
        >
            <img
                src="<?php echo plugin_dir_url(__FILE__) . 'LurnyBot.jpeg'; ?>"
                alt="Chatbot"
                style="
                    width: 100%;
                    height: 100%;
                    object-fit: cover;
                    border-radius: 50%;
                "
            />
        </button>

        <!-- Chatbot Iframe -->
        <div
            id="chatbot-popup"
            style="
                width: 375px;
                height: 667px;
                position: fixed;
                bottom: 0;
                right: 90px; /* Adjusted to ensure it's to the left of the toggle button */
                border-radius: 15px;
                overflow: hidden;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
                z-index: 9999;
                display: none; /* Hide by default */
                transition: all 0.3s ease-in-out; /* Smooth transition for opening/closing */
            "
        >
            <iframe
                src="https://lurnybot-emvees.azurewebsites.net/"
                style="
                    width: 100%;
                    height: 100%;
                    border: none;
                    background: transparent;
                "
                title="Chatbot"
            ></iframe>
        </div>
    </div>

    <style>
        /* Mobile adjustments */
        @media (max-width: 768px) {
            #chatbot-popup {
                width: 90vw;  /* 90% of the viewport width */
                height: 90vh;  /* 90% of the viewport height */
                left: 50%;     /* Center horizontally */
                bottom: 5vh;   /* Adjust padding from the bottom */
                transform: translateX(-50%);  /* Center it properly */
                border-radius: 15px;
                z-index: 10000;
            }

            #chatbot-toggle-button {
                width: 50px; /* Smaller toggle button on mobile */
                height: 50px;
            }

            /* Only hide toggle button on mobile when the popup is active */
            #chatbot-popup.active ~ #chatbot-toggle-button {
                display: none;
            }
        }

        /* Desktop adjustments */
        @media (min-width: 769px) {
            #chatbot-popup {
                width: 375px;
                height: 667px;
                position: fixed;
                bottom: 0;
                right: 90px; /* Keep it to the left of the toggle button */
                display: block;
            }

            /* Ensure toggle button is always visible on desktop */
            #chatbot-toggle-button {
                display: block;
            }
        }
    </style>

    <script>
        function toggleChatbot() {
            var popup = document.getElementById('chatbot-popup');
            var toggleButton = document.getElementById('chatbot-toggle-button');
            var display = popup.style.display === 'block' ? 'none' : 'block';
            popup.style.display = display;

            // Hide the toggle button only on mobile when the popup is open
            if (window.innerWidth <= 768) {
                if (display === 'block') {
                    toggleButton.style.display = 'none';
                } else {
                    toggleButton.style.display = 'block';
                }
            }
        }

        window.addEventListener('message', function(event) {
            if (event.data === 'closeChat') {
                document.getElementById('chatbot-popup').style.display = 'none';
                if (window.innerWidth <= 768) {
                    document.getElementById('chatbot-toggle-button').style.display = 'block';
                }
            }
        });
    </script>
    <?php
}
add_action('wp_footer', 'inject_chatbot_popup');
