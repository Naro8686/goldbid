// Slick slider init

$('.slider').slick({
  arrows: true,
  dots: false,
  infinite: true,
  speed: 1000,
  slidesToShow: 1,
  centerMode: true,
  variableWidth: true,
  draggable: false
});

$('.slider')
  .on('beforeChange', function(event, slick, currentSlide, nextSlide){
    $('.slick-list').addClass('do-transition')
  })
  .on('afterChange', function(){
    $('.slick-list').removeClass('do-transition')
  });