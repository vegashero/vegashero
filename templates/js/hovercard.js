jQuery(".vh-item")
  .on('mouseenter', function(){
      var div = $(this);
      div.stop(true, true).animate({
          margin: -10,
          width: "+=20",
          height: "+=20"
      }, 'fast');
  })
  .on('mouseleave', function(){
      var div = $(this);
      div.stop(true, true).animate({
          margin: -10,
          width: "-=20",
          height: "-=20"
      }, 'fast');
  });
