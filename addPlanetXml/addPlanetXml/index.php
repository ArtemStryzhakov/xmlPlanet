<?php
$xml=simplexml_load_file("stars.xml");
// väljastab massivist getChildrens
function getSystem($xml){
    $array=getPlanets($xml);
    return $array;
}
// väljastab  laste andmed
function getPlanets($planets){
    $result=array($planets);
    $childs=$planets -> planet;

    if(empty($childs))
        return $result;

    foreach ($childs as $child){
        $array=getPlanets($child);
        $result=array_merge($result, $array);
    }
    return $result;
}

function getParent($allObj, $planets){
    if ($planets == null) return null;
    foreach ($allObj as $sun){
        if (!hasChilds($sun)) continue;
        foreach ($sun->planet as $child){
            if($child->name == $planets->name){
                return $sun;
            }
        }
    }
    return null;
}
function hasChilds($planets){
    return !empty($planets -> planet);
}

function getAllMoons($items){
    $moons = $items -> moons -> name;
    $allMoons = null;

    for ($i = 0; $i < count($moons); $i++){
        $allMoons .= " | ".$moons[$i]." | ";
    }
    return $allMoons;
}

// Searching planets in table
function searchByPlanetName($searchWord){
    global $allObj;
    $result=array();
    foreach ($allObj as $planets){
        $sun=getParent($allObj, $planets);
        if (empty($sun)) continue;
        if (substr(strtolower($planets -> name), 0, strlen($searchWord)) == strtolower($searchWord)){
            array_push($result, $planets);
        }
    }
    return $result;
}

$allObj=getSystem($xml);

if(isset($_POST['submit'])){
    /*$xmlDoc = new DOMDocument("1.0","UTF-8");
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->load('stars.xml');
    $xmlDoc->formatOutput = true;

    $xml_root = $xmlDoc->documentElement;
    $xmlDoc->appendChild($xml_root);

    $xml_toode = $xmlDoc->createElement("sunName");
    $xmlDoc->appendChild($xml_toode);

    $xml_root->appendChild($xml_toode);

    unset($_POST['submit']);
    foreach($_POST as $voti=>$vaartus){
        if($voti == "sunName"){
            $kirje = $xmlDoc->createElement("name",$vaartus);
            $xml_toode->appendChild($kirje);
            continue;
        }
        $shtuka = $xmlDoc->createElement($voti);
        $kirje = $xmlDoc->createElement("name",$vaartus);
        $shtuka->appendChild($kirje);
        $xml_toode->appendChild($shtuka);
    }
    $xmlDoc->save('stars.xml');*/
    $xmlDoc = new DOMDocument("1.0","UTF-8");
    $xmlDoc->preserveWhiteSpace = false;
    $xmlDoc->load('stars.xml');
    $xmlDoc->formatOutput = true;

    $xml_root = $xmlDoc->documentElement;
    $xmlDoc->appendChild($xml_root);

    $xml_toode = $xmlDoc->createElement("star");
    $xmlDoc->appendChild($xml_toode);

    $xml_root->appendChild($xml_toode);

    unset($_POST['submit']);
    foreach($_POST as $voti=>$vaartus){
        if($voti == "moons"){
            $kirje = $xmlDoc->createElement("moons");
            $moons = $xmlDoc->createElement("name", $vaartus);
            $kirje->appendChild($moons);
            $xml_toode->appendChild($kirje);
            continue;
        }
        if($voti == "star"){
            $star = $xmlDoc->createElement("name", $vaartus);
            $xml_toode->appendChild($star);
            continue;
        }
        $planet = $xmlDoc->createElement("planet");
        $kirje = $xmlDoc->createElement("name",$vaartus);
        $planet->appendChild($kirje);
        $xml_toode->appendChild($planet);
    }
    $xmlDoc->save('stars.xml');
}
?>



<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toote lisamine</title>
</head>
<header><p>Sun System</p></header>
<style>
    body{
        background-image: url("background.jpg");
        height: 100%;
    }

    header{
        margin-top: 75px;
        background-color: rgba(0, 0, 0, 0.4);
        color: aliceblue;
        font-family: "Arial Black";
        font-style: italic;
        margin-bottom: 50px;
        text-align: center;
        font-size: 50px;
    }

    #firstDiv {
        margin: auto;
        display: flex;
    }

    #code {
        background-color: rgba(255, 255, 255, 0.6);
        overflow: scroll;
        height: 550px;
        border-radius: 20px;
        margin-right: 100px;
    }

    #secondDiv {
        padding: 20px 50px;
        background-color: rgba(255, 255, 255, 0.4);
        align-items: center;
        width: 700px;
        height: auto;
        border-radius: 20px;
        right: 0;
    }

    table {
        border: black;
        border-collapse: collapse;
        width: 810px;
        height: auto;
        font-family: "Comic Sans MS";
    }
    table tr th {
        background-color: black;
        color: aliceblue;
        text-align: center;
        height: 35px;
        font-size: 17px;
        font-family: "Arial Black";
    }

    table td {
        height: auto;
        text-align: center;
    }
</style>
<body>
<div style="width: max-content" id="firstDiv">
    <div id="code">
        <?php
        highlight_file("index.php")
        ?>
    </div>
    <div id="secondDiv">
        <h2>Toote sisestamine</h2>
        <table style="width:60%">
            <form action="" method="post" name="vorm1">
                <tr>
                    <td><label for="star">Star name:</label></td>
                    <td><input type="text" name="star" id="star" autofocus></td>
                </tr>
                <tr>
                    <td><label for="planet">Planet name:</label></td>
                    <td><input type="text" name="planet" id="planet"></td>
                </tr>
                <tr>
                    <td><label for="moons">Moon name:</label></td>
                    <td><input type="text" name="moons" id="moons"></td>
                </tr>
                <tr>
                    <td><input type="submit" name="submit" id="submit" value="Sisesta"></td>
                    <td></td>
                </tr>
            </form>
        </table>

        <br>
        <form action="?" method="post"><br>
            <label for="planetName" style="font-family: 'Arial Black'">Planet name:</label>
            <br>
            <input type="text" name="search" placeholder="name...">
            <button>OK</button>
        </form>
        <br>
        <table border="1" style="width: 100%">
            <tr>
                <th>Star</th>
                <th>Planets</th>
                <th>Moons</th>
            </tr>
            <tr>
                <?php
                foreach ($allObj[0]->star as $planets){

                    //$sun=getParent($allObj, $planets);
                    //if (empty($sun)) continue;

                    //$parentOfParent=getParent($allObj, $sun);
                    echo '<tr>';
                    //echo '<td>'. $sun -> sunName -> planet -> name.'</td>';
                    echo "<td>$planets->name</td>";
                    echo '<td>'. $planets -> planet -> name.'</td>';
                    if ($planets -> moons -> name == null) {
                        echo '<td>'.'---'.'</td>';
                    }
                    else{
                        //echo '<td>'. getAllMoons($planets).'</td>';
                        echo '<td>'.$planets -> moons -> name.'</td>';
                    }
                    echo '</tr>';
                }
                ?>
            </tr>
        </table>
        <hr>
        <table border="1" style="width: 100%">
            <tr>
                <th>Sun</th>
                <th>Planets</th>
                <th>Moons</th>
            </tr>
            <tr>
                <?php
                if (!empty($_POST['search'])){
                    $result=searchByPlanetName($_POST["search"]);
                    // sama tabel
                    foreach ($result as $planets){
                        $sun=getParent($allObj, $planets);
                        if (empty($sun)) continue;

                        $parentOfParent=getParent($allObj, $sun);
                        echo '<tr>';

                        echo '<td>'. $sun -> name.'</td>';
                        echo '<td>'. $planets -> name.'</td>';
                        if ($planets -> moons -> name == null) {
                            echo '<td>'.'---'.'</td>';
                        }
                        else{
                            echo '<td>'. getAllMoons($planets).'</td>';
                        }
                        echo '</tr>';
                    }
                }
                ?>
            </tr>
        </table>
    </div>
</div>
</body>
</html>
