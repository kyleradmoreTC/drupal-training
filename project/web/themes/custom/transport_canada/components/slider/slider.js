const swiper = new Swiper('.swiper', {
    // Optional parameters
    loop: true,
  
    // If we need pagination
    pagination: {
      el: '.swiper-pagination',
    },

    slidesPerView: 1,
  
    breakpoints: {
        460: {
          slidesPerView: 2,
          spaceBetween: 15,
        },
        960: {
          slidesPerView: 3,
          spaceBetween: 30,
        },
      },

    // Navigation arrows
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  
    // And if we need scrollbar
    scrollbar: {
      el: '.swiper-scrollbar',
    },
  });