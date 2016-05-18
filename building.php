<?php include_once("config.php"); ?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <link rel="stylesheet" href="main.css">
  <script src='http://code.jquery.com/jquery-latest.js'></script>
</head>

<body>

<div id="main">
  <a href="index.html"><img src="polytech_logo.svg" id="logo"></a>
  <div id="authorized">
    <img src="icon3.png" id="icon">
    <span id="username"></span>
    <div class = "exit">
      <a href="instruction.html">Инструкции</a>
      <a href="sign_in.html" >Выход</a>
    </div>
  </div>

  <hr>
  <div class = "exit back">
    <a href="index.html">Назад</a>
  </div>
  <div id="title">
    <span id="building">
    <?php
      $corp = $_GET["corp"];
      mysqli_select_db ( $db , $dbname );
      $result = mysqli_query($db,"
        SELECT Name FROM Corps WHERE id='$corp'
        ");
      $row = mysqli_fetch_row($result);
      echo $row[0];
      mysqli_close($db);
    ?>
    </span>
  </div>
  <div id="blocks">
    <form id="auditor_Form" action="check.php" method="POST">
      <span><h2>Выбор аудитории:</h2></span>
      <input type="text" class="input" maxlength="30" placeholder="Введите номер аудитории" id="number" name="number" />
      <input type="hidden" name="corp" value='<?php echo$_GET["corp"];?>'>
      <button type="submit" id="button" onclick="setRoom()">Создать</button>
    </form>
 <!-- <?php
      $corp = $_GET["corp"];
      mysqli_select_db ($db , $dbname );
      $result = mysqli_query($db,"
        SELECT NumberAudit FROM Auditorium WHERE Corps_id='$corp'
        ");

      while($rows_res = mysqli_fetch_array($result)){
        $name_company = $rows_res['CompanyName'];
        $id_company = $rows_res['IDCompany'];
      }
      $row = mysqli_fetch_row($result);
      echo $row[0];
      mysqli_close($db);
    ?> -->
    <form action="check.php" id="room-list" style="display: none;">
      <ul>
        <a href="check.php?room_id=105" class="refer" onclick="setRoom(this)"><li class="list">105</li></a>
        <a href="check.php" class="refer" onclick="setRoom(this)"><li class="list">142</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">230</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">232</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">240</li></a>
      </ul>
      <ul>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">739</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">685</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">20</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">456</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">171</li></a>
      </ul>
      <ul>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">185</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">116a</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">116b</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">505</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">102</li></a>
      </ul>
      <ul>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">312</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">316</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">335</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">218</li></a>
        <a href="check.html" class="refer" onclick="setRoom(this)"><li class="list">101</li></a>
      </ul>
    </form>
  </div>

  <button class="digramm" id="dtoggle" onclick="toggleDiagram()">Показать диаграмму видов аудиторий</button>
  <div id="piechart" style="margin: auto; margin-left: 100px; opacity: 0;"></div>

</div>
</div>

<?php
  if($_GET["success"] == 'true') :
    echo "<div id='error_box'>
        <p id='error_message'></p>
      </div>";
?>
<script type="text/javascript">
  $('#error_message').html('Изменения сохранены');
  $("#error_box").fadeIn(500).delay(1500).fadeOut(500);
</script>

<?php
  else:
    endif;
?>



<!-- при вводе номера аудитории сортируется список "по совпедению"  -->
<script type='text/javascript'>

$(function() {
$('#number').keyup(function() {
var val = this.value;
var re = new RegExp('^' + val,'i');
$('#room-list a').each(function (){
$(this).toggle(re.test($(this).text()));
});
});
});
    username.textContent = localStorage.login;
   //<!-- building.textContent = localStorage.building;-->
</script>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1.1','packages':['corechart']}]}"></script>


<script>
  google.setOnLoadCallback(drawChart);

  var deletedRooms = JSON.parse(localStorage.deletedRooms || '[]');
  if (deletedRooms.length) {
    for (var i = 0; i < deletedRooms.length; i++) {
      $('.refer .list:contains(' + deletedRooms[i] + ')').remove();
    }
  }

  $('#room-list').show();

  function randNumber() {
    return Math.floor((Math.random() * 100) + 1);
  }
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Type', 'Number'],
      ['Лекции',  randNumber()],
      ['Практики', randNumber()],
      ['Компьютерные', randNumber()],
      ['Лабораторные', randNumber()]
    ]);
    var options = {
      title: 'Виды аудиторий в здании',
      backgroundColor: 'transparent',
      width: 600,
      height: 400
    };
    var chart = new google.visualization.PieChart(document.getElementById('piechart'));
    chart.draw(data, options);
  }
  function toggleDiagram() {
    if (piechart.style.opacity == 0) {
      piechart.style.opacity = 1;
      dtoggle.textContent = 'Скрыть диаграмму видов аудиторий';
    } else {
      piechart.style.opacity = 0;
      dtoggle.textContent = 'Показать диаграмму видов аудиторий';
    }
  }
  function setRoom(link) {
    var value = link ? link.textContent : 'Новая аудитория';
    localStorage.setItem('room', value);
  }
</script>

</body>
</html>
