(function ($) {
    $(".wsd_customer_type").on("change", function () {
        if ($(this).val() === "individual") {
            $(".customers-select").addClass("enable-me");
        } else {
            $(".customers-select").removeClass("enable-me");
        }
    });

    let url = wcme_obj.ajaxurl;

    $(".wsd-customers-select").select2({
        width: "100%",
        tags: true,
        multiple: true,
        minimumInputLength: 2,
        minimumResultsForSearch: 10,
        ajax: {
            url: url + "?&action=" + wcme_obj.action,
            data: function (params) {
                return {
                    search: params.term,
                    type: "public"
                };
            }
        }
    });



})(jQuery);
