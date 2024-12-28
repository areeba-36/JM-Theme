jQuery(document).ready(function ($) {
    let page = 1; // Initial page
    let loading = false; // Prevent multiple simultaneous loads

    $(window).scroll(function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 100 && !loading) {
            const maxPage = jm_ajax_params.max_page;

            if (page >= maxPage) return; // Stop if on the last page

            loading = true;
            page++;

            $.ajax({
                url: jm_ajax_params.ajax_url,
                type: 'POST',
                data: {
                    action: 'load_more_posts',
                    page: page,
                },
                success: function (data) {
                    if (data === 'no_more_posts') {
                        loading = false; // No more posts
                    } else {
                        $('#main').append(data);
                        loading = false;
                    }
                },
            });
        }
    });
});
