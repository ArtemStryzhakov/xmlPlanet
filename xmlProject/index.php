<?php
$xml=simplexml_load_file("stars.xml");
// väljastab massivist getChildrens
function getSystem($xml){
    $array=getPlanets($xml);
    return $array;
}
// väljastab  laste andmed
function getPlanets($people){
    $result=array($people);
    $childs=$people -> planet;

    if(empty($childs))
        return $result;

    foreach ($childs as $child){
        $array=getPlanets($child);
        $result=array_merge($result, $array);
    }
    return $result;
}

function getParent($peoples, $people){
    if ($people == null) return null;
    foreach ($peoples as $parent){
        if (!hasChilds($parent)) continue;
        foreach ($parent->planet as $child){
            if($child->name == $people->name){
                return $parent;
            }
        }
    }
    return null;
}
function hasChilds($people){
    return !empty($people -> planet);
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
    global $peoples;
    $result=array();
    foreach ($peoples as $people){
        $parent=getParent($peoples, $people);
        if (empty($parent)) continue;
        if (substr(strtolower($people -> name), 0, strlen($searchWord)) == strtolower($searchWord)){
            array_push($result, $people);
        }
    }
    return $result;
}

$peoples=getSystem($xml);

?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Sun System</title>
</head>
<header><p>Sun System</p></header>
<body>
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
        position:absolute;
        background-color: rgba(255, 255, 255, 0.6);
        overflow: scroll;
        height: 550px;
    }

    #secondDiv {
        padding: 20px 50px;
        background-color: rgba(255, 255, 255, 0.4);
        align-items: center;
        justify-content: center;
        width: 42.5%;
        margin: auto;
        border-radius: 20px;
        margin-right: 200px;
    }

    table {
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

<div id="firstDiv">
    <div id="code">
        <?php
        highlight_file("index.php")
        ?>
    </div>
    <div id="secondDiv">
        <table border="1">
            <tr>
                <th>Sun</th>
                <th>Planets</th>
                <th>Moons</th>
            </tr>
            <tr>
                <?php
                foreach ($peoples as $people){
                    $parent=getParent($peoples, $people);
                    if (empty($parent)) continue;

                    $parentOfParent=getParent($peoples, $parent);
                    echo '<tr>';

                    echo '<td>'. $parent -> name.'</td>';
                    echo '<td>'. $people -> name.'</td>';
                    if ($people -> moons -> name == null) {
                        echo '<td>'.'---'.'</td>';
                    }
                    else{
                        echo '<td>'. getAllMoons($people).'</td>';
                    }
                    echo '</tr>';
                }
                ?>
            </tr>
        </table>
        <hr>
        <form action="?" method="post"><br>
            <label for="planetName" style="font-family: 'Arial Black'">Planet name:</label>
            <br>
            <input type="text" name="search" placeholder="name...">
            <button>OK</button>
        </form>
        <br>
        <table border="1">
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
                    foreach ($result as $people){
                        $parent=getParent($peoples, $people);
                        if (empty($parent)) continue;

                        $parentOfParent=getParent($peoples, $parent);
                        echo '<tr>';

                        echo '<td>'. $parent -> name.'</td>';
                        echo '<td>'. $people -> name.'</td>';
                        if ($people -> moons -> name == null) {
                            echo '<td>'.'---'.'</td>';
                        }
                        else{
                            echo '<td>'. getAllMoons($people).'</td>';
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