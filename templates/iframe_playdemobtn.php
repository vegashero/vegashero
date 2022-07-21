<div class="iframe_kh_wrapper">
  <div class="embed-bg-wrapper" style="background-image:url(%2$s);"></div>
  <div class="embed-overlay">
    <button class="play-demo-btn">%3$s</button>
    <div class="age-gate-text">%4$s</div>
  </div>
  <div class="kh-no-close"></div>
    <iframe width="" height="" class="singlegame-iframe" frameborder="0" scrolling="no" allowfullscreen src="about:blank" data-srcurl="%1$s" sandbox="allow-same-origin allow-scripts allow-popups allow-forms"></iframe>
    <script>
    jQuery(document).ready(function() {
      jQuery('.singlegame-iframe').hide();
    // load iframe with play now button and remove overlay elements
        jQuery('.play-demo-btn').on('click', function() {
            jQuery('.embed-overlay').remove();
            jQuery('.embed-bg-wrapper').remove();
            jQuery('.singlegame-iframe').show();
            jQuery('.singlegame-iframe').attr('src', jQuery('.singlegame-iframe').attr('data-srcurl'));
            jQuery('.singlegame-iframe').css('background-color', 'black');
        });
    }); 
    </script>
</div>
