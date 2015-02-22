$(document).ready(function() {
  
  // On click, remove class on active element, add it to the new one
  $('header nav a').click(function(e) {
    
    $('header nav a.active').removeClass('active');
    $(this).addClass('active');
    
    // Scroll to anchor
    $('html,body').animate({scrollTop: $($(this).attr('href')).offset().top - 70},'slow');
    
    e.preventDefault();
    return false;
    
  });
  
  // On scroll, remove class on active element and add it to the new one
  
  $(document).scroll(function() {
     
     var position = Math.floor($(this).scrollTop() / 800) + 1;
    
     $('header nav a.active').removeClass('active');
     $('header nav a.link-' + position).addClass('active');
    
  });
  
  var r_select = $('#dash_head').text();
  // On Hover, displays hovered row data to 4 boxes
  $('table tbody tr').hover(
    function() {
      var s1 = (r_select == "Program Dashboard") ? dec2Percent($(this).find('td:last-child').text()) : $(this).find('td:first-child').text();
      var s2 = (r_select == "Program Dashboard") ? $(this).find('td:nth-child(5)').text() : $(this).find('td:nth-child(3)').text();
      var s3 = (r_select == "Program Dashboard") ? $(this).find('td:nth-child(6)').text() : $(this).find('td:nth-child(4)').text();
      var s4 = (r_select == "Program Dashboard") ? $(this).find('td:nth-child(7)').text() : $(this).find('td:nth-child(5)').text();

      $('#stat_a1').html(s1);
      $('#stat_b1').html(s2);
      $('#stat_c1').html(s3);
      $('#stat_d1').html(s4);

    }, function() {
      $('#stat_a1').html("''");
      $('#stat_b1').html("''");
      $('#stat_c1').html("''");
      $('#stat_d1').html("''");
  });

  // Correct the table header column width
  $('')

});

//Change border color 
function changeBorderColor (id) {
  document.getElementById('program').style.border = "7px solid #a1a1a1";
  document.getElementById('machine').style.border = "7px solid #a1a1a1";
  document.getElementById(id).style.border = "7px solid red";
  disableInputField('document.mainform', 'select_program', 'm_id, m_date, all_machine');
  disableInputField('document.mainform', 'select_machine', 'p_id, p_date, all_program');
}

function dec2Percent(num) {
  return num*100 + "%";
}

//Toggle enable/disable forms 
function enableob(o) { eval(o+".disabled = false"); }
function disableob(o) { eval(o+".disabled = true"); }
function disableInputField(formstr,chkobstr,obstr) {
var checked = eval(formstr+"."+chkobstr+".checked");
var obs = obstr.split(",");
  for (i = 0; i < obs.length; i++) {
    obs[i] = formstr+"."+obs[i];
  }
  if (checked == false) {
    for (i = 0; i < obs.length; i++) {
      enableob(obs[i]);
    }
  }
  else {
    for (i = 0; i < obs.length; i++) {
      disableob(obs[i]);
    }
  }
}

//Zoom-in and Zoom-out font sizes
var fontSize = 1;
function zoomIn() {
    fontSize += 0.1;
    document.body.style.fontSize = fontSize + "em";
}
function zoomOut() {
    fontSize -= 0.1;
    document.body.style.fontSize = fontSize + "em";
}