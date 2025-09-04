jQuery(document).ready(function($) {
    var $input = $("#smtp_receivers_input");
    var $hidden = $("#smtp_receivers");
    var $list = $(".smtp-receivers-list");

    function updateHiddenField() {
        var tags = [];
        $list.find(".smtp-receiver-tag").each(function() {
            tags.push($(this).text().replace("×", "").trim());
        });
        $hidden.val(tags.join(","));
    }

    function addReceiver(email) {
        if (email && isValidEmail(email)) {
            var $tag = $("<span>")
                .addClass("smtp-receiver-tag")
                .text(email)
                .append(
                    $("<button>")
                        .addClass("remove-receiver")
                        .attr("type", "button")
                        .attr("aria-label", "移除")
                        .html("&times;")
                );
            $list.append($tag);
            updateHiddenField();
        }
    }

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    $input.on("keydown", function(e) {
        if (e.key === "Enter" || e.key === ",") {
            e.preventDefault();
            var email = $(this).val().trim();
            if (email) {
                addReceiver(email);
                $(this).val("");
            }
        }
    });

    $list.on("click", ".remove-receiver", function() {
        $(this).parent().remove();
        updateHiddenField();
    });
}); 