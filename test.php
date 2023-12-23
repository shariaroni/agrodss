<!DOCTYPE html>
<html>
<head>
    <title>Word Processor Text Animation</title>
    <style>
        .word-processor {
            font-family: Arial, sans-serif;
            font-size: 24px;
            overflow: hidden;
            white-space: nowrap;
            border-right: 2px solid #000; /* Simulate cursor */
            animation: type 1s steps(40, end), blink-caret 0.75s step-end infinite;
        }

        @keyframes type {
            from {
                width: 0;
            }
            to {
                width: 100%;
            }
        }

        @keyframes blink-caret {
            from,
            to {
                border-color: transparent;
            }
            50% {
                border-color: #000;
            }
        }
    </style>
</head>
<body>
    <div class="word-processor">
        <?php
        // Text to animate
        $text = "Welcome to Word Processor Animation!";
        
        // Split text into individual characters
        $characters = str_split($text);
        
        // Output each character with a span element
        foreach ($characters as $char) {
            echo "<span>$char</span>";
        }
        ?>
    </div>
</body>
</html>
