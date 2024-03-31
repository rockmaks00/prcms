$(document).ready(function () {
    $('#applyFilter').click(function() {
        const dateFilter = $('#dateFilter').val();
        if(dateFilter) {
            window.location.href = window.location.pathname + '?creation_date=' + dateFilter;
        }
    });

    $('#clearFilter').click(function() {
        window.location.href = window.location.pathname;
    });

    $('#uploadCsv').click(function () {
        const $input = $('input[name="uploadCsv"]');
        const file = $input[0].files[0];

        if (file) {
            let formData = new FormData();
            formData.append('csv', file);

            $.ajax({
                url: $input.attr('data-set'),
                type: 'POST',
                data: formData,
                async: true,
                processData: false,
                contentType: false,
                success: function (response) {
                    const data = JSON.parse(response);
                    if (data.status === 200) {
                        alert('Файл загружен');
                        location.reload(true);
                    }
                }
            });
        } else {
            alert('Загружаемый файл отсутствует');
        }
    });
});