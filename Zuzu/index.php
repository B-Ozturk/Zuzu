<!DOCTYPE html>
<html>

<head>
<title>Zuzu</title>
    <link href="css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</head>

<body>
    <h1 class="text-center baner">| Sushi Heaven Zuzu |</h1>
    <?php
    require_once('module/process.php');
    
    if (isset($_POST["name"])) {

        echo $result == ""
            ? "<div class='notify'>Bedankt! uw bestelling komt eraan!</div>"
            : "<div class='notify'>$result</div>";
    }
    ?>

    <form id="orderform" method="post" target="_self">

        <label for="name">Volledige naam:</label>
        <input type="text" name="name" required value=""/>
        <br>
        <label for="email">Email:</label>
        <input type="email" name="email" required value=""/>
        <br>
        <label for="address">Address:</label>
        <input type="text" name="address" required value=""/>
        <br>
        <label for="zipcode">Postcode:</label>
        <input type="text" name="zipcode" required value=""/>
        <br>
        <label for="city">Stad:</label>
        <input type="text" name="city" required value=""/>

        <br>


        <form method="post">
            <select name="sushi_id">
                <option selected disabled value="">Kies een sushi!</option>
                <?php
                global $pdo;
                $sql = $pdo->prepare("SELECT * FROM sushi");
                $sql->execute();
                $result = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach ($result as $data) {
                    echo "
                    <option selected enabled id='$data[id]' value='$data[name]'>" . $data['name'] .  ". Vooraad: " . $data['amount'] . "</option>
                ";
                }
                ?>
            </select> <br>
            <p class=hoeveelheid>Aantal</p>
            <input type="number" name="amount" placeholder="1"> <br>

            <input type="submit" name="submit">
        </form>

        <?php
        global $pdo;
        if (isset($_POST['submit'])) {
            if ($_POST['amount'] != 0 && !empty($_POST['amount'])) {
                $amount = $_POST['amount'];
                $sushi = $_POST['sushi_id'];


                $sql = $pdo->prepare("SELECT * FROM sushi");
                $sql->execute();
                $result = $sql->fetchAll(PDO::FETCH_ASSOC);

                foreach ($result as $data) {
                    if ($data['name'] == $sushi) {
                        $sushiName = $data['name'];
                        $sushiPrice = $data['price'];
                        $id = $data['id'];
                        $sushiAmount = $amount;
                        if ($sushiAmount > 1) {
                            $sushiTotalPrice = $data['price'] * $sushiAmount;
                        } else {
                            $sushiTotalPrice = $sushiPrice;
                        }
                    }
                }

                $new_amount = $data['amount'] - $amount;

                if ($amount > $data['amount']) {
                    echo 'Je kunt niet meer bestellen dan onze vooraad.';
                } else {
                    $sql2 = $pdo->prepare("UPDATE sushi SET amount = $new_amount WHERE id = $id");
                    $sql2->execute();

                    echo "
                    <table>
        
                        <tr>
                            <th>Sushi</th>
                            <th>Prijs</th>
                            <th>Hoeveelheid</th>
                            <th>Totaal Prijs</th>
                        </tr>
                        <tr>
                            <td>" . $sushiName . "</td>
                            <td>&euro; " . $sushiPrice . "</td>
                            <td>" . $sushiAmount . "</td>
                            <td>&euro; " . $sushiTotalPrice . "</td>
                        </tr>
                    </table>";
                }
            } else {
                echo '<script>alert("Fill in all fields.");</script>';
            }
        }
        ?>



</body>

</html>