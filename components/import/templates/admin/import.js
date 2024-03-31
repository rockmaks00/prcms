$(function () {
    $('#uploadCsv').click(function (e) {
        e.preventDefault();

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