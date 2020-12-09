</div>
<!-- Fin de container -->

<!-- jQuery -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>

<!-- Bootstrap JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<!-- Liberría bootbox -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootbox.js/4.4.0/bootbox.min.js"></script>

<!-- Para poder eliminar productos, con JS -->
<script>
    $(document).on('click', '.delete-object', function () {

        var id = $(this).attr('delete-id');

        bootbox.confirm({
            message: "<h4>¿Seguro?</h4>",
            buttons: {
                confirm: {
                    label: '<span class="glyphicon glyphicon-ok"></span> Si',
                    className: 'btn-danger'
                },
                cancel: {
                    label: '<span class="glyphicon glyphicon-remove"></span> No',
                    className: 'btn-primary'
                }
            },
            callback: function (result) {

                if (result == true) {
                    $.post('delete_product.php', {
                        object_id: id
                    }, function (data) {
                        location.reload();
                    }).fail(function () {
                        alert('Imposible eliminar.');
                    });
                }
            }
        });

        return false;
    });
</script>

</body>
</html>
