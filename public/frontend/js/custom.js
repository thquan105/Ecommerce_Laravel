$(document).ready(function () {

    loadwishlist();
    loadcart();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
        }
    });

    function loadwishlist() {
        $.ajax({
            type: "GET",
            url: "/load-wishlist-count",
            success: function (response) {
                console.log();
                $(".wishlist-count").attr("data-notify", response.count);
            },
        });
    }

    function loadcart() {
        $.ajax({
            type: "GET",
            url: "/load-cart-count",
            success: function (response) {
                console.log();
                $(".cart-count").attr("data-notify", response.count);
            },
        });
    }


    $('.js-addwish-b2').on('click', function (e) {
        e.preventDefault();

    });

    $('.js-addwish-b2').each(function () {
        var product_slug = $(this).attr("product-slug");
        $(this).on('click', function () {


            $.ajax({
                type: "POST",
                url: "/wishlists",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                    product_slug: product_slug,
                },
                success: function (response) {
                    if (response.status == 401) {
                        window.location = '/login';
                    } else {
                        loadwishlist();
                        swal(response, "is added to wishlist !", "success");
                        $(this).off('click');
                    }
                },
                error: function (xhr, textStatus, errorThrown) {
                    if (xhr.status == 401) {
                        console.log(xhr);
                        window.location = '/login';
                    }

                    if (xhr.status == 422) {
                        alert(xhr.responseText);
                    }
                },
            });
            $(this).addClass('js-addedwish-b2');
        });
    });


});   