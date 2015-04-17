<!-- use double percentage signs!!! -->

<div id="slider" class="nivoSlider">
    <img src="%1$s/screen1.jpg" alt="" />
    <img src="%1$s/screen2.jpg" alt="" title="#htmlcaption" />
    <img src="%1$s/screen3.jpg" alt="" title="This is an example of a caption" />
    <img src="%1$s/screen4.jpg" alt="" />
</div>
<div id="htmlcaption" class="nivo-html-caption">
    <strong>This</strong> is an example of a <em>HTML</em> caption with <a href="#">a link</a>.
</div>

<script type="text/javascript">
      $(window).load(function() {
          $("#slider").nivoSlider();
      });
</script>