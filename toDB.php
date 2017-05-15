<?php
/**
 * Created by IntelliJ IDEA.
 * User: Cronos
 * Date: 5/11/2017
 * Time: 7:37 PM
 */

function my_implode($separator,$array){
    $temp = '';
    foreach($array as $key => $item){
        $temp .= $item;
        if($key != sizeof($array)-1){
            $temp .= $separator;
        }
    }
    return $temp;
}
function arrayToQuotesCSV($array){
    $temp = '';
    foreach ($array as $key => $item) {
        $temp .= json_encode($item);
        if ($key != sizeof($array) - 1) {
            $temp .= ', ';
        }
    }
    return $temp;
}
//months mapper for chart
function monthMapper($v)
{
    if ($v==1){return "January";}
    elseif ($v==2){return "February";}
    elseif ($v==3){return "March";}
    elseif ($v==4){return "April";}
    elseif ($v==5){return "May";}
    elseif ($v==6){return "June";}
    elseif ($v==7){return "July";}
    elseif ($v==8){return "August";}
    elseif ($v==9){return "September";}
    elseif ($v==10){return "October";}
    elseif ($v==11){return "November";}
    elseif ($v==12){return "December";}
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wishlist";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->prepare("INSERT INTO wishes (country, region, months, days, travelAs, kids, rooms, housing, meal, facilities, preferences, kidsBirthdays)
    VALUE (:country, :region, :months, :days, :travelAs, :kids, :rooms, :housing, :meal, :facilities, :preferences, :kidsBirthdays)");
    $stmt->bindParam(':country', $country);
    $stmt->bindParam(':region', $region);
    $stmt->bindParam(':months', $months);
    $stmt->bindParam(':days', $days);
    $stmt->bindParam(':travelAs', $travelAs);
    $stmt->bindParam(':kids', $kids);
    $stmt->bindParam(':rooms', $rooms);
    $stmt->bindParam(':housing', $housing);
    $stmt->bindParam(':meal', $meal);
    $stmt->bindParam(':facilities', $facilities);
    $stmt->bindParam(':preferences', $preferences);
    $stmt->bindParam(':kidsBirthdays', $kidsBirthdays);
//VALIDATION

if(isset($_POST['country'])) {
    $country = my_implode(', ', $_POST['country']);
}
if(isset($_POST['region'])) {
    $region = $_POST['region'];
}
if(isset($_POST['months'])) {
    $months = my_implode(', ', $_POST['months']);
}
if(isset($_POST['days']) AND is_numeric($_POST['days'])) {
    $days = $_POST['days'];
}
if(isset($_POST['travelAs'])) {
    $travelAs = my_implode(', ', $_POST['travelAs']);
}
if(isset($_POST['kids']) AND is_numeric($_POST['kids'])) {
    $kids = $_POST['kids'];
}
if(isset($_POST['rooms'])) {
    $rooms = my_implode(', ', $_POST['rooms']);
}
if(isset($_POST['housing'])) {
    $housing = my_implode(', ', $_POST['housing']);
}
if(isset($_POST['meal'])) {
    $meal = my_implode(', ', $_POST['meal']);
}
if(isset($_POST['facilities'])) {
    $facilities = my_implode(', ', $_POST['facilities']);
}
if(isset($_POST['preferences'])) {
    $preferences = $_POST['preferences'];
}
if(isset($_POST['kidsBirthdays'])){
    $kidsBirthdays = my_implode(', ', $_POST['kidsBirthdays']);
}


    $stmt->execute();

    echo "Your form has been successfully submited. <br>";

    $sql = "SELECT * FROM wishes";
    $result = $conn->query($sql);
    $sql = $conn->prepare($sql);
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $arrayData = $sql->fetchAll();
    $country = '';
    $months = '';
    $travelAs = '';
    $rooms = '';
    $housing = '';
    $meal = '';
    $facilities = '';
    foreach($arrayData as $row){
        $country .= $row['country'].', ';
        $months .= $row['months'].', ';
        $travelAs .= $row['travelAs'].', ';
        $rooms .= $row['rooms'].', ';
        $housing .= $row['housing'].', ';
        $meal .= $row['meal'].', ';
        $facilities .= $row['facilities'].', ';
    }
    function stripTojsonString($category){
//        cat = string(a,a,a,b,b,a,b)
        $category = explode(", ", $category);
//        cat = array(a,a,a,b,b,a,b)
        $category = array_count_values($category);
//        cat = array('a' =>4, 'b' =>3)
        $ArrayRessults = [];
        $noDup = '';
        $countList = '';
        foreach($category as $key => $item){
            if($key!==''){
                $noDup .= json_encode($key);
                $countList .= $item;
                $noDup .= ", ";
                $countList .= ", ";
            }
        }
//        noDuplicate = string(a, b) counter = string(3, 4)
        $ArrayRessults[0] = rtrim($noDup,', ');
        $ArrayRessults[1] = rtrim($countList,', ');
        return $ArrayRessults;
    }

}

catch(PDOException $e)
{
    echo "Error: " . $e->getMessage();
}
$conn = null;

//mapping months for chart
$monthArr = array();
$monthArr = (explode(', ',stripToJsonString($months)[0]));
$mappedMonthArr = array_map("monthMapper",$monthArr);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Statistics</title>
    <link rel="stylesheet" type="text/css" href="style/stats.css"
</head>
<body>
    <canvas id="country" width="100"></canvas>
    <canvas id="months" width="300" height="110"></canvas>
    <canvas id="travelAs" width="300" height="110"></canvas>
    <canvas id="rooms" width="300" height="110"></canvas>
    <canvas id="housing" width="300" height="110"></canvas>
    <canvas id="meal" width="300" height="110"></canvas>
    <canvas id="facilities" width="300" height="110"></canvas>
<script src="js/jquery-3.2.1.js"></script>
<script src="js/Chart.js"></script>
<script>

//    var Chart = require('chart.js');
    var ctxcountry = $("#country");
    var country = new Chart(ctxcountry, {
        type: 'horizontalBar',
        data: {
            labels: [ <?php echo stripTojsonString($country)[0]; ?> ],
            datasets: [{
                label: '# of times wished',
                data: [<?php echo stripTojsonString($country)[1]; ?>],
                borderWidth: 1
            }]
        },
        options: {
            title: {
                display: true,
                text: 'Most preferred locations:'
            },
                scales: {
                yAxes: [{
                    categoryPercentage: 1,
                    barPercentage: 1,
                    ticks: {
                        beginAtZero:true
                    }
                }]
            }
        }
    });

var ctxmonths = $("#months");
var months = new Chart(ctxmonths,{
    type: 'pie',
    data: {
        labels: [<?php echo arrayToQuotesCSV($mappedMonthArr); ?>],
        datasets: [
            {
                data: [<?php echo stripTojsonString($months)[1];?>],
                backgroundColor: [
                    "#3b9bc7",
                    "#6d7895",
                    "#816b81",
                    "#637f9f",
                    "#a94f59",
                    "#bd4145",
                    "#c73b3b",
                    "#b3484f",
                    "#955d6d",
                    "#5986a9",
                    "#4f8db3",
                    "#4594bd"
                ],
                hoverBackgroundColor: [
                    "#3b9bc7",
                    "#6d7895",
                    "#816b81",
                    "#637f9f",
                    "#a94f59",
                    "#bd4145",
                    "#c73b3b",
                    "#b3484f",
                    "#955d6d",
                    "#5986a9",
                    "#4f8db3",
                    "#4594bd"
                ]
            }]
    },
    options: {
        title: {
            display: true,
            text: 'Most preferred months:'
        },
        animation: {
            animateScale: true
        }
    }
});

var ctxtravelAs = $("#travelAs");
var travelAs = new Chart(ctxtravelAs,{
    type: 'pie',
    data: {
        labels: [<?php echo stripTojsonString($travelAs)[0];?>],
        datasets: [
            {
                data: [<?php echo stripTojsonString($travelAs)[1];?>],
                backgroundColor: [
                    "#c70503",
                    "#d68a14",
                    "#eae139",
                    "#62e43f",
                    "#0bc3ff",
                    "#3b05ff"

                ],
                hoverBackgroundColor: [
                    "#c70503",
                    "#d68a14",
                    "#eae139",
                    "#62e43f",
                    "#0bc3ff",
                    "#3b05ff"
                ]
            }]
    },
    options: {
        title: {
            display: true,
            text: 'Most preferred groups for travelling:'
        },
        animation: {
            animateScale: true
        }
    }
});

var ctxrooms = $("#rooms");
var rooms = new Chart(ctxrooms,{
    type: 'pie',
    data: {
        labels: [<?php echo stripTojsonString($rooms)[0];?>],
        datasets: [
            {
                data: [<?php echo stripTojsonString($rooms)[1];?>],
                backgroundColor: [
                    "#c70503",
                    "#d68a14",
                    "#eae139",
                    "#62e43f",
                    "#0bc3ff",
                    "#3b05ff"

                ],
                hoverBackgroundColor: [
                    "#c70503",
                    "#d68a14",
                    "#eae139",
                    "#62e43f",
                    "#0bc3ff",
                    "#3b05ff"
                ]
            }]
    },
    options: {
        title: {
            display: true,
            text: 'Most preferred types of rooms:'
        },
        animation: {
            animateScale: true
        }
    }
});

var ctxhousing = $("#housing");
var housing = new Chart(ctxhousing,{
    type: 'pie',
    data: {
        labels: [<?php echo stripTojsonString($housing)[0];?>],
        datasets: [
            {
                data: [<?php echo stripTojsonString($housing)[1];?>],
                backgroundColor: [
                    "#c70503",
                    "#d68a14",
                    "#eae139",
                    "#62e43f",
                    "#0bc3ff",
                    "#3b05ff"

                ],
                hoverBackgroundColor: [
                    "#c70503",
                    "#d68a14",
                    "#eae139",
                    "#62e43f",
                    "#0bc3ff",
                    "#3b05ff"
                ]
            }]
    },
    options: {
        title: {
            display: true,
            text: 'Most preferred housing types:'
        },
        animation: {
            animateScale: true
        }
    }
});

var ctxfacilities = $("#facilities");
var facilities = new Chart(ctxfacilities, {
    type: 'bar',
    data: {
        labels: [ <?php echo stripTojsonString($facilities)[0]; ?> ],
        datasets: [{
            label: '# of times wished',
            data: [<?php echo stripTojsonString($facilities)[1]; ?>],
             backgroundColor: [
             'rgba(255, 99, 132, 0.2)',
             'rgba(54, 162, 235, 0.2)',
             'rgba(255, 206, 86, 0.2)',
             'rgba(75, 192, 192, 0.2)',
             'rgba(153, 102, 255, 0.2)',
             'rgba(255, 159, 64, 0.2)'
             ],
             borderColor: [
             'rgba(255,99,132,1)',
             'rgba(54, 162, 235, 1)',
             'rgba(255, 206, 86, 1)',
             'rgba(75, 192, 192, 1)',
             'rgba(153, 102, 255, 1)',
             'rgba(255, 159, 64, 1)'
             ],
            borderWidth: 1
        }]
    },
    options: {
        title: {
            display: true,
            text: 'Most preferred facilities:'
        },
        scales: {
            yAxes: [{
                categoryPercentage: 1,
                barPercentage: 1,
                ticks: {
                    beginAtZero:true
                }
            }]
        }
    }
});

</script>
</body>
</html>
