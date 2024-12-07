$(document).ready(function () {
    // Xử lý nút Get
    $('#get-cards-btn').click(function () {
        const gameId = $('#gameSelect').val();
        const setId = $('#setSelect').val();
        loadCardTable(gameId, setId);
    });

    // Xử lý khi chọn game
    $('#gameSelect').change(function () {
        const gameId = $(this).val();
        const setSelect = $('#setSelect');

        if (gameId) {
            $.ajax({
                url: '/views/stores/actions/get_sets.php',
                method: 'GET',
                data: { game_id: gameId },
                success: function (response) {
                    setSelect.html('<option value="">All Sets</option>' + response);
                    setSelect.prop('disabled', false);
                }
            });
        } else {
            setSelect.html('<option value="">All Sets</option>');
            setSelect.prop('disabled', true);
        }
    });

    // Xử lý nút Save
    $(document).on('click', '.store-products__btn-save', function () {
        const cardId = $(this).data('card-id');
        const row = $(this).closest('tr');
        const quantity = row.find('.store-products__quantity').val();
        const price = row.find('.store-products__price').val();
        const shipping = row.find('.store-products__shipping').val();

        $.ajax({
            url: '/views/stores/actions/save_card.php',
            method: 'POST',
            data: {
                card_id: cardId,
                quantity: quantity,
                price: price,
                shipping: shipping
            },
            dataType: 'json',
            success: function (response) {
                if (response.success) {
                    showToast(response.message);
                } else {
                    showToast(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                showToast('Error saving changes. Please try again.');
            }
        });
    });

    // Lấy giá trị từ URL khi trang load
    const urlParams = new URLSearchParams(window.location.search);
    const gameId = urlParams.get('game_id');
    const setId = urlParams.get('set_id');

    if (gameId) {
        $('#gameSelect').val(gameId);
        // Load sets cho game được chọn
        $.ajax({
            url: '/views/stores/actions/get_sets.php',
            method: 'GET',
            data: { game_id: gameId },
            success: function (response) {
                const setSelect = $('#setSelect');
                setSelect.html('<option value="">All Sets</option>' + response);
                setSelect.prop('disabled', false);
                if (setId) {
                    setSelect.val(setId);
                }
                // Load card table với giá trị từ URL
                loadCardTable(gameId, setId);
            }
        });
    }
});

function loadCardTable(gameId = '', setId = '') {
    // Cập nhật URL với parameters mới
    const newUrl = updateUrlParameters({
        game_id: gameId,
        set_id: setId
    });
    window.history.pushState({ path: newUrl }, '', newUrl);

    $.ajax({
        url: '/views/stores/tabs/card_table.php',
        method: 'GET',
        data: {
            game_id: gameId,
            set_id: setId
        },
        success: function (response) {
            $('#cardTableContainer').html(response);
        },
        error: function (xhr, status, error) {
            console.error('Error loading card table:', error);
        }
    });
}

function updateUrlParameters(params) {
    const url = new URL(window.location.href);
    Object.keys(params).forEach(key => {
        if (params[key]) {
            url.searchParams.set(key, params[key]);
        } else {
            url.searchParams.delete(key);
        }
    });
    return url.toString();
}
