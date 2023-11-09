function createToast(msg) {
    return `
    <div class="flash notice">
        <button onclick="this.parentElement.remove();">
            <i class="fi fi-rs-cross-small"></i>
        </button>
        <div class="text">
            <h3>Error</h3>
            <p>${msg}</p>
        </div>
    </div>
`;
}


function closeToast() {
    setTimeout(function() {
        document.querySelector('.flash.notice:last-of-type').remove();
    }, 4500);
}

function filterByCategory(items, $el) {
        $category = $el.data('category');
        let order = $el.data('order') == "1" ? "0" : "1";

        $tableHead = $el.closest('tr').find('th[data-category]');
        $tableHead.attr('data-order', "0");
        $tableHead.data('order', "0");

        $el.data('order', order);
        $el.attr('data-order', order);

        items.sort(function(a,b) {
            var first, second;

            if ($category == "client") {
                first = a["client"]["email"];
                second = b["client"]["email"];
            } else if ($category == "orderDate") {
                first = a["orderDate"]["timestamp"];
                second = b["orderDate"]["timestamp"];
            } else {
                first = a[$category];
                second = b[$category];
            }


            if (typeof first == "string" && typeof second == "string") {
                first = first.toLocaleUpperCase();
                second = second.toLocaleUpperCase();
            }


            if (second > first) {
                return order == "1" ? -1 : 1;
            } else if (second < first) {
                return order == "1" ? 1 : -1;
            }

            return 0;
        });

        return items;
}