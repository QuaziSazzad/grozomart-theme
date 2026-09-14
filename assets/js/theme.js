(function($) {
    "use strict";

    const $documentOn = $(document);
    const $windowOn = $(window);

    /* ================================
       Widget-scoped Initializers
       Run on normal page load AND on Elementor editor's live widget render.
       Elements are marked with a data-flag after init so re-firing this
       function for the same DOM (Elementor triggers the hook on normal
       frontend loads too) never double-initializes a plugin instance.
    ================================ */
    function initThemeWidgets($scope) {

        /* ================================
           Mobile Menu Js Start
        ================================ */

        var $mobileMenu = $scope.find('#mobile-menu').not('.theme-inited');
        if ($mobileMenu.length && $.fn.meanmenu) {
            $mobileMenu.addClass('theme-inited').meanmenu({
                meanMenuContainer: '.mobile-menu',
                meanScreenWidth: "1199",
                meanExpand: ['<i class="far fa-plus"></i>'],
            });
        }

        var $mobileMenus = $scope.find('#mobile-menus').not('.theme-inited');
        if ($mobileMenus.length && $.fn.meanmenu) {
            $mobileMenus.addClass('theme-inited').meanmenu({
                meanMenuContainer: '.mobile-menus',
                meanScreenWidth: "19920",
                meanExpand: ['<i class="far fa-plus"></i>'],
            });
        }

        /* ================================
           Video & Image Popup Js Start
        ================================ */

        var $imgPopup = $scope.find('.img-popup').not('.theme-inited');
        if ($imgPopup.length && $.fn.magnificPopup) {
            $imgPopup.addClass('theme-inited').magnificPopup({
                type: "image",
                gallery: {
                    enabled: true,
                },
            });
        }

        var $videoPopup = $scope.find('.video-popup').not('.theme-inited');
        if ($videoPopup.length && $.fn.magnificPopup) {
            $videoPopup.addClass('theme-inited').magnificPopup({
                type: "iframe",
                callbacks: {},
            });
        }

        /* ================================
           Counterup Js Start
        ================================ */

        var $count = $scope.find('.count').not('.theme-inited');
        if ($count.length && $.fn.counterUp) {
            $count.addClass('theme-inited').counterUp({
                delay: 15,
                time: 4000,
            });
        }

        /* ================================
           Wow Animation Js Start
        ================================ */

        if (typeof WOW !== 'undefined') {
            new WOW().init();
        }

        /* ================================
           Nice Select Js Start
        ================================ */

        var $singleSelect = $scope.find('.single-select').not('.theme-inited');
        if ($singleSelect.length && $.fn.niceSelect) {
            $singleSelect.addClass('theme-inited').niceSelect();
        }

        /* ================================
           Parallaxie Js Start
        ================================ */

        var $parallaxie = $scope.find('.parallaxie').not('.theme-inited');
        if ($parallaxie.length && $.fn.parallaxie && $(window).width() > 991) {
            if ($(window).width() > 768) {
                $parallaxie.addClass('theme-inited').parallaxie({
                    speed: 0.55,
                    offset: 0,
                });
            }
        }

        /* ================================
           Hero Slider Js Start
        ================================ */

        var $heroSlider = $scope.find('.hero-slider').not('.swiper-initialized');
        if ($heroSlider.length && typeof Swiper !== 'undefined') {
            new Swiper($heroSlider[0], {
                spaceBetween: 30,
                speed: 1300,
                loop: true,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: ".array-prev",
                    prevEl: ".array-next",
                },
            });
        }

        /* ================================
           Shop Category Slider Js Start
        ================================ */

        var $shopCategorySlider = $scope.find('.shop-category-slider').not('.swiper-initialized');
        if ($shopCategorySlider.length && typeof Swiper !== 'undefined') {
            new Swiper($shopCategorySlider[0], {
                spaceBetween: 30,
                speed: 1300,
                loop: true,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
                navigation: {
                    nextEl: ".array-prev",
                    prevEl: ".array-next",
                },
                breakpoints: {
                    1399: {
                        slidesPerView: 8,
                    },
                    1199: {
                        slidesPerView: 7,
                    },
                    991: {
                        slidesPerView: 5,
                    },
                    767: {
                        slidesPerView: 4,
                    },
                    575: {
                        slidesPerView: 3,
                    },
                    400: {
                        slidesPerView: 2,
                    },
                    0: {
                        slidesPerView: 1,
                    },
                },
            });
        }

        /* ================================
           Testimonial Slider Js Start
        ================================ */

        var $testimonialSlider = $scope.find('.testimonial-slider').not('.swiper-initialized');
        if ($testimonialSlider.length && typeof Swiper !== 'undefined') {
            new Swiper($testimonialSlider[0], {
                spaceBetween: 30,
                speed: 1300,
                loop: true,
                autoplay: {
                    delay: 2000,
                    disableOnInteraction: false,
                },
                pagination: {
                    el: ".dot",
                    clickable: true,
                },
                navigation: {
                    nextEl: ".array-prevs",
                    prevEl: ".array-nexts",
                },
                breakpoints: {
                    1199: {
                        slidesPerView: 3,
                    },
                    991: {
                        slidesPerView: 2,
                    },
                    767: {
                        slidesPerView: 1.5,
                    },
                    575: {
                        slidesPerView: 1,
                    },
                    0: {
                        slidesPerView: 1,
                    },
                },
            });
        }

        /* ================================
           CountDown Js Start
        ================================ */

        var $countdowns = $scope.find('.countdown').not('.theme-inited');
        if ($countdowns.length) {
            $countdowns.addClass('theme-inited').each(function () {

                const $countdown = $(this);
                const dateAttr = $countdown.attr('data-countdown-date');
                const targetDate = dateAttr ? new Date(dateAttr).getTime() : new Date("2026-08-29 00:00:00").getTime();

                const countdownInterval = setInterval(function () {

                    const now = new Date().getTime();
                    const distance = targetDate - now;

                    if (distance <= 0) {

                        clearInterval(countdownInterval);

                        $countdown.find(".day").text("00");
                        $countdown.find(".hour").text("00");
                        $countdown.find(".min").text("00");
                        $countdown.find(".sec").text("00");

                        return;
                    }

                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                    const hours = Math.floor(
                        (distance % (1000 * 60 * 60 * 24)) /
                        (1000 * 60 * 60)
                    );
                    const minutes = Math.floor(
                        (distance % (1000 * 60 * 60)) /
                        (1000 * 60)
                    );
                    const seconds = Math.floor(
                        (distance % (1000 * 60)) / 1000
                    );

                    $countdown.find(".day").text(String(days).padStart(2, "0"));
                    $countdown.find(".hour").text(String(hours).padStart(2, "0"));
                    $countdown.find(".min").text(String(minutes).padStart(2, "0"));
                    $countdown.find(".sec").text(String(seconds).padStart(2, "0"));

                }, 1000);
            });
        }
    }

    $documentOn.ready(function() {

        initThemeWidgets($documentOn);

      /* ================================
       Dropdown menu Js Start
    ================================ */

     $documentOn.on("click", ".mean-expand", function () {
        let icon = $(this).find("i");

        if (icon.hasClass("fa-plus")) {
            icon.removeClass("fa-plus").addClass("fa-minus");
        } else {
            icon.removeClass("fa-minus").addClass("fa-plus");
        }
    });

     //>> Scrolldown Start <<//
        $("#scrollDown").on("click", function () {
            setTimeout(function () {
                $("html, body").animate({ scrollTop: "+=1000px" }, "slow");
            }, 1000);
        });


    /* ================================
        Sidebar Toggle & Sticky Item Logic
        ================================ */

        // Open offcanvas
        $(".sidebar__toggle").on("click", function () {
        $(".offcanvas__info").addClass("info-open");
        $(".offcanvas__overlay").addClass("overlay-open");

        // Hide sticky item
        $(".sidebar-sticky-item").fadeOut().removeClass("active");
        });

        // Close offcanvas
        $(".offcanvas__close, .offcanvas__overlay").on("click", function () {
        $(".offcanvas__info").removeClass("info-open");
        $(".offcanvas__overlay").removeClass("overlay-open");

        // Show sticky item
        $(".sidebar-sticky-item").fadeIn().addClass("active");
        });

        /* ================================
        Body Overlay Js Start
        ================================ */

        $(".body-overlay").on("click", function () {
        $(".offcanvas__area").removeClass("offcanvas-opened");
        $(".df-search-area").removeClass("opened");
        $(".body-overlay").removeClass("opened");

        // Show sticky item when overlay clicked
        $(".sidebar-sticky-item").fadeIn().addClass("active");
        });

        /* ================================
        Offcanvas Link Click (Optional)
        ================================ */

        $(".offcanvas a").on("click", function () {
        $(".sidebar-sticky-item").fadeIn().addClass("active");
    });


      /* ================================
       Sticky Header Js Start
    ================================ */

       $windowOn.on("scroll", function () {
        if ($(this).scrollTop() > 250) {
          $("#header-sticky").addClass("sticky");
        } else {
          $("#header-sticky").removeClass("sticky");
        }
      });

    /* ================================
      Custom Accordion Js Start
    ================================ */

    if ($('.accordion-box').length) {
        $(".accordion-box").on('click', '.acc-btn', function () {
            var outerBox = $(this).closest('.accordion-box');
            var target = $(this).closest('.accordion');
            var accBtn = $(this);
            var accContent = accBtn.next('.acc-content');

            if (target.hasClass('active-block')) {
                // Already open, so close it
                accBtn.removeClass('active');
                target.removeClass('active-block');
                accContent.slideUp(300);
            } else {
                // Close all others
                outerBox.find('.accordion').removeClass('active-block');
                outerBox.find('.acc-btn').removeClass('active');
                outerBox.find('.acc-content').slideUp(300);

                // Open clicked one
                accBtn.addClass('active');
                target.addClass('active-block');
                accContent.slideDown(300);
            }
        });
    }

    // Accordion Functionality
    $documentOn.on("click", ".sidebar-header", function () {
        $(this).parent().toggleClass("active");
    });

       /* ================================
        Mouse Cursor Animation Js Start
    ================================ */

    if ($(".mouseCursor").length > 0) {
        function itCursor() {
            var myCursor = jQuery(".mouseCursor");
            if (myCursor.length) {
                if ($("body")) {
                    const e = document.querySelector(".cursor-inner"),
                        t = document.querySelector(".cursor-outer");
                    let n, i = 0, o = !1;
                    window.onmousemove = function(s) {
                        if (!o) {
                            t.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)";
                        }
                        e.style.transform = "translate(" + s.clientX + "px, " + s.clientY + "px)";
                        n = s.clientY;
                        i = s.clientX;
                    };
                    $("body").on("mouseenter", "button, a, .cursor-pointer", function() {
                        e.classList.add("cursor-hover");
                        t.classList.add("cursor-hover");
                    });
                    $("body").on("mouseleave", "button, a, .cursor-pointer", function() {
                        if (!($(this).is("a", "button") && $(this).closest(".cursor-pointer").length)) {
                            e.classList.remove("cursor-hover");
                            t.classList.remove("cursor-hover");
                        }
                    });
                    e.style.visibility = "visible";
                    t.style.visibility = "visible";
                }
            }
        }
        itCursor();
    }

    /* ================================
        Back To Top Button Js Start
    ================================ */
    $windowOn.on('scroll', function() {
        // Reveal once the visitor has scrolled a screen's worth, rather than
        // only at the very bottom of the document.
        var showAfter = Math.max(300, $(window).height() * 0.5);

        $("#back-top").toggleClass("show", $(this).scrollTop() > showAfter);
    });

    $documentOn.on('click', '#back-top', function() {
        $('html, body').animate({ scrollTop: 0 }, 800);
        return false;
    });

    /* ================================
       Additional Quantity Controls Js Start
    ================================ */
        /**
         * Delegated and scoped to each .qty group rather than matching a
         * fixed list of ids (#qty, #qty2, #qty3) and pairing buttons to
         * inputs by index. The old approach only worked for the static
         * markup's hardcoded ids, so any stepper rendered dynamically — the
         * Shop Details widget gives its input a per-instance id so several
         * can share a page — was never wired up at all, and a page with an
         * uneven number of steppers paired buttons to the wrong input.
         */
        $documentOn.on('click', '.qtyminus, .qtyplus', function (e) {
            e.preventDefault();

            const button = this;
            const group = button.closest('.qty') || button.parentNode;
            const input = group ? group.querySelector('input[type="number"]') : null;

            if (!input) {
                return;
            }

            const min = input.hasAttribute('min') ? Number(input.getAttribute('min')) : 0;
            const max = input.hasAttribute('max') && input.getAttribute('max') !== '' ? Number(input.getAttribute('max')) : Infinity;
            const step = Number(input.getAttribute('step')) || 1;

            let value = Number(input.value) || min;
            value += button.classList.contains('qtyplus') ? step : -step;

            if (value < min) value = min;
            if (value > max) value = max;

            input.value = value;

            // WooCommerce (and any other listener) watches for a real change
            // before re-reading the quantity.
            input.dispatchEvent(new Event('change', { bubbles: true }));
        });


      // Quantity increment and decrement
    const quantityIncrement = document.querySelectorAll(".quantityIncrement");
    const quantityDecrement = document.querySelectorAll(".quantityDecrement");

    if (quantityIncrement.length && quantityDecrement.length) {
        quantityIncrement.forEach((increment) => {
            increment.addEventListener("click", function () {
                const input = increment.parentElement.querySelector("input");
                const value = parseInt(input.value || 0, 10); // Ensure valid number
                input.value = value + 1;
            });
        });

        quantityDecrement.forEach((decrement) => {
            decrement.addEventListener("click", function () {
                const input = decrement.parentElement.querySelector("input");
                const value = parseInt(input.value || 0, 10); // Ensure valid number
                if (value > 1) {
                    input.value = value - 1;
                }
            });
        });
    }

    /* ================================
       Payment Method Update Js Start
    ================================ */

    function updatePaymentMethod() {
        let paymentMethod = $("input[name='pay-method']:checked").val();
        $(".payment").html(paymentMethod);
    }

    // Initial load
    updatePaymentMethod();

    // On click of radio option
    $(".checkout-radio-single input[name='pay-method']").on("change", function () {
        updatePaymentMethod();
    });

    /* ================================
       CountDown Js Start
    ================================ */

    });

    //elementor front start
    $windowOn.on("elementor/frontend/init", function () {
        elementorFrontend.hooks.addAction(
            "frontend/element_ready/widget",
            initThemeWidgets
        );
    });

    /* ================================
      Price Ranage Js Start
    ================================ */

    /**
     * Scoped per .slider-wrapper: a page can have more than one dual-range
     * price slider (e.g. the Shop widget's Layout Two placed twice, or the
     * Elementor editor re-running this script inside its preview iframe
     * alongside the parent frame). The previous version used page-global
     * selectors ($(".range-min")/$(".range-max")/$(".price-value span")),
     * so .val() only ever read the FIRST slider on the page regardless of
     * which one the shopper dragged, and every wrapper's .slider-range got
     * sized from that one shared value — every slider beyond the first
     * appeared stuck. Each wrapper now tracks and updates only its own
     * inputs/text/fill.
     */
    $(".slider-wrapper").each(function () {
        const $wrapper = $(this);
        if ($wrapper.data("range-slider-inited")) {
            return;
        }
        $wrapper.data("range-slider-inited", true);

        const $min = $wrapper.find(".range-min");
        const $max = $wrapper.find(".range-max");
        const $text = $wrapper.closest(".price-filter-widget").find(".price-value span");

        // The fill lives inside an inset positioning context (see
        // .slider-range-track in the CSS) so its percentages measure the
        // thumb's real travel distance, not the wrapper's full width.
        $wrapper.find(".slider-range, .slider-range-track").remove();
        $wrapper.append('<div class="slider-range-track"><div class="slider-range"></div></div>');
        const $range = $wrapper.find(".slider-range");

        function updateSlider() {
            let minVal = parseInt($min.val(), 10);
            let maxVal = parseInt($max.val(), 10);

            if (minVal > maxVal - 10) {
                minVal = maxVal - 10;
                $min.val(minVal);
            }

            /**
             * Both inputs share the same bounds (e.g. min="2" max="90" for
             * a real price range, not always 0-based) — position along the
             * track is (value - bound_min) / (bound_max - bound_min), not
             * value / bound_max. Using bound_max alone as the divisor was
             * wrong whenever the floor wasn't 0, which shifted the fill.
             */
            const boundMin = parseInt($min.attr("min"), 10);
            const boundMax = parseInt($min.attr("max"), 10);
            const span = boundMax - boundMin;
            const minPct = ((minVal - boundMin) / span) * 100;
            const maxPct = ((maxVal - boundMin) / span) * 100;

            $range.css({
                left: minPct + "%",
                width: (maxPct - minPct) + "%"
            });

            $text.text(`$${minVal} – $${maxVal}`);
        }

        $wrapper.on("input", ".range-min, .range-max", updateSlider);

        updateSlider();
    });



     /* ================================
       Preloader Js Start
    ================================ */
    // Hide on load, but also fall back to a timeout: if any asset stalls,
    // `load` may never fire and the overlay would cover the page forever.
    function hidePreloader() {
        $(".preloader").fadeOut(600);
    }

    $windowOn.on('load', hidePreloader);
    setTimeout(hidePreloader, 5000);


  })(jQuery); // End jQuery
