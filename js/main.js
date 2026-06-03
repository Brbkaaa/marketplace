$(document).ready(function() {
    // Mobile nav toggle
    $('#navToggle').click(function() {
        $('#mainNav').toggleClass('active');
    });

    // Dark mode toggle (persists with localStorage)
    if (localStorage.getItem('darkMode') === 'on') {
        $('body').addClass('dark-mode');
        $('#darkToggle').text('☀️');
    }

    $('#darkToggle').click(function() {
        $('body').toggleClass('dark-mode');
        if ($('body').hasClass('dark-mode')) {
            $(this).text('☀️');
            localStorage.setItem('darkMode', 'on');
        } else {
            $(this).text('🌙');
            localStorage.setItem('darkMode', 'off');
        }
    });

    // Live product search filter
    $('#searchInput').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.product-card').each(function() {
            var title = $(this).find('h3').text().toLowerCase();
            $(this).toggle(title.indexOf(value) > -1);
        });
    });

    // Add to cart via AJAX (no page refresh)
    $('.btn-cart').click(function(e) {
        e.preventDefault();
        var $btn = $(this);
        var url = $btn.attr('href');
        var productId = url.match(/add=(\d+)/)[1];

        $.get('/marketplace/includes/add_to_cart.php?id=' + productId, function(data) {
            $btn.text('Added ✓').css({'background': 'hsl(160, 70%, 42%)', 'pointer-events': 'none'});
            setTimeout(function() {
                $btn.text('🛒 Add').css({'background': '', 'pointer-events': ''});
            }, 1500);
        });
    });
});
