function logout() {
  $('#overlay-screen').css('display','block');
  $('#input-boxes', 'input').val('');
}

$().ready(function() {
  $('#signup-button').click(function(event) {
    event.preventDefault();
    window.location.replace("/login/register.php");
  });

  $('#return-to-image-upload').click(function(event) {
    event.preventDefault();
    window.location.replace("/login/login.php");
    $('#overlay-screen').css('display', 'none');
  });
});
