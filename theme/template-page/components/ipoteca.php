<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script>
  $('input').change(function() {

    var msg = $('#form').serialize();

    $.ajax({
      url: '/wp-content/themes/realty/inc/ajax-ipoteca.php',
      method: 'POST',
      data: msg,
      success: function(data) {

        $('#result').html(data);

      }
    });

  });
</script>
