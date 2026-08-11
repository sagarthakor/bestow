{{--
    Product photo widget, shared by every screen that picks a belt/size and then
    has to show what it looks like: belt cutting, the belt and roll formulas, and
    production. Include it once per page inside the javascript section - it needs
    jQuery, and it renders its own popup markup.

    Markup: give the thumbnail <img> the class `product-photo` and let
    ProductPhoto.load() fill it. Clicking any `product-photo` opens the popup.

        <img class="product-photo" data-title="Belt">
        <script>ProductPhoto.load(productId, $('#thatImg'));</script>
--}}

<style>
    .product-photo-box { display: inline-block; text-align: center; }
    .product-photo {
        max-height: 80px; max-width: 90px; border: 1px solid #e3e8ef; border-radius: 4px;
        background: #fff; cursor: zoom-in; padding: 2px;
    }
    .product-photo:hover { border-color: #b7c6d8; }
    .product-photo-empty {
        display: inline-block; min-width: 62px; padding: 6px 8px; font-size: 11px;
        color: #9aa5b4; border: 1px dashed #dfe4ec; border-radius: 4px; background: #fbfcfe;
    }

    #productPhotoPopup {
        display: none; position: fixed; z-index: 1080; left: 0; top: 0;
        width: 100%; height: 100%; overflow: auto; background: rgba(15, 22, 32, .85);
    }
    #productPhotoPopup .pp-inner { margin: 40px auto; max-width: 760px; text-align: center; }
    #productPhotoPopup .pp-main {
        max-width: 100%; max-height: 72vh; background: #fff; border-radius: 4px; padding: 4px;
    }
    #productPhotoPopup .pp-title { color: #f1f4f8; margin: 12px 0 6px; font-size: 15px; }
    #productPhotoPopup .pp-thumbs { margin-top: 10px; }
    #productPhotoPopup .pp-thumbs img {
        height: 54px; width: 54px; object-fit: cover; margin: 0 4px; cursor: pointer;
        border: 2px solid transparent; border-radius: 4px; background: #fff;
    }
    #productPhotoPopup .pp-thumbs img.active { border-color: #4c9ffe; }
    #productPhotoPopup .pp-close {
        position: absolute; top: 14px; right: 28px; color: #fff; font-size: 38px;
        line-height: 1; font-weight: bold; cursor: pointer;
    }
    #productPhotoPopup .pp-close:hover { color: #ffb3b3; }
</style>

<div id="productPhotoPopup">
    <span class="pp-close" title="Close">&times;</span>
    <div class="pp-inner">
        <img class="pp-main" id="productPhotoPopupImg" src="">
        <div class="pp-title" id="productPhotoPopupTitle"></div>
        <div class="pp-thumbs" id="productPhotoPopupThumbs"></div>
    </div>
</div>

<script>
    window.ProductPhoto = (function () {
        var url = "{{ route('admin.product.photo') }}";
        // Answers are reused across rows - the same size gets picked again and
        // again on a cutting entry, and the photo cannot change while typing.
        var cache = {};

        function paint($img, data) {
            var images = (data && data.images) || [];
            var $box = $img.closest('.product-photo-box');
            var $empty = $box.find('.product-photo-empty');

            if (!images.length) {
                $img.hide().attr('src', '').removeData('images');
                $empty.show().text(data && data.found ? 'No photo' : '');
                return;
            }

            $img.attr('src', images[0])
                .data('images', images)
                .data('title', (data && data.name) || '')
                .show();
            $empty.hide();
        }

        function load(productId, $img, done) {
            if (!$img || !$img.length) { return; }

            if (!productId) {
                paint($img, null);
                if (done) { done(null); }
                return;
            }

            if (cache[productId]) {
                paint($img, cache[productId]);
                if (done) { done(cache[productId]); }
                return;
            }

            $.get(url, {product: productId}, function (res) {
                cache[productId] = res;
                paint($img, res);
                if (done) { done(res); }
            }).fail(function () {
                paint($img, null);
                if (done) { done(null); }
            });
        }

        function open(images, title, index) {
            if (!images || !images.length) { return; }

            index = index || 0;
            $('#productPhotoPopupImg').attr('src', images[index]);
            $('#productPhotoPopupTitle').text(title || '');

            var thumbs = '';
            if (images.length > 1) {
                images.forEach(function (src, i) {
                    thumbs += '<img src="' + src + '" data-index="' + i + '"' +
                        (i === index ? ' class="active"' : '') + '>';
                });
            }
            $('#productPhotoPopupThumbs').html(thumbs);
            $('#productPhotoPopup').data('images', images).data('title', title).show();
        }

        $(function () {
            $(document).on('click', '.product-photo', function () {
                var images = $(this).data('images');
                if (!images || !images.length) {
                    // Rendered straight into the markup rather than loaded.
                    var src = $(this).attr('src');
                    images = src ? [src] : [];
                }
                open(images, $(this).data('title'));
            });

            $(document).on('click', '#productPhotoPopupThumbs img', function () {
                var $popup = $('#productPhotoPopup');
                open($popup.data('images'), $popup.data('title'), parseInt($(this).data('index'), 10));
            });

            $(document).on('click', '#productPhotoPopup .pp-close, #productPhotoPopup', function (e) {
                // Clicking the picture itself should not close it.
                if (e.target.id === 'productPhotoPopupImg') { return; }
                $('#productPhotoPopup').hide();
            });

            $(document).on('keyup', function (e) {
                if (e.key === 'Escape') { $('#productPhotoPopup').hide(); }
            });
        });

        return {load: load, open: open};
    })();
</script>
