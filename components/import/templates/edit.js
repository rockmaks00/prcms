$(document).ready(function () {
    $('#fieldSave').click(function () {
        const url = $(this).attr('data-url');
        const formData = $('#fieldForm').serialize();

        $.ajax({
            url: url + '/update/',
            type: 'POST',
            data: formData,
            async: true,
            success: function (response) {
                const data = JSON.parse(response);
                if (data.status === 200) {
                    alert('Запись обновлена');
                    location.reload(true);
                }
            }
        });
    });

    $('#fieldDelete').click(function () {
        const url = $(this).attr('data-url');
        const id = $('#field_id').val();

        let formData = new FormData();
        formData.append('field_id', id);

        $.ajax({
            url: url + '/delete/',
            type: 'POST',
            data: formData,
            async: true,
            processData: false,
            contentType: false,
            success: function (response) {
                const data = JSON.parse(response);
                if (data.status === 200) {
                    alert('Запись удалена');
                    window.location.href = url;
                }
            }
        });
    });
});