<?php
/*******w******** 

    Name: Yulin
    Date: 2024/05/24
    Description: User Input Validation Assignment

****************/
// Helper function to validate Canadian postal codes
function is_valid_postal_code($postal_code) {
    $regex = "/^[A-Za-z]\d[A-Za-z] ?\d[A-Za-z]\d$/";
    return preg_match($regex, $postal_code);
}

// Get current year
$current_year = date("Y");

// Initialize error array
$errors = [];

// Validate full name
$fullname = filter_input(INPUT_POST, 'fullname', FILTER_SANITIZE_STRING);
if (empty($fullname)) {
    $errors[] = "Full name is required.";
}

// Validate address
$address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
if (empty($address)) {
    $errors[] = "Address is required.";
}

// Validate city
$city = filter_input(INPUT_POST, 'city', FILTER_SANITIZE_STRING);
if (empty($city)) {
    $errors[] = "City is required.";
}

// Validate province
$province = filter_input(INPUT_POST, 'province', FILTER_SANITIZE_STRING);
$valid_provinces = ["AB", "BC", "MB", "NB", "NL", "NS", "ON", "PE", "QC", "SK", "NT", "NU", "YT"];
if (empty($province) || !in_array($province, $valid_provinces)) {
    $errors[] = "Valid province is required.";
}

// Validate postal code
$postal = filter_input(INPUT_POST, 'postal', FILTER_SANITIZE_STRING);
if (empty($postal) || !is_valid_postal_code($postal)) {
    $errors[] = "Valid Canadian postal code is required.";
}

// Validate email
$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
if (empty($email)) {
    $errors[] = "Valid email address is required.";
}

// Validate card type
$cardtype = filter_input(INPUT_POST, 'cardtype', FILTER_SANITIZE_STRING);
if (empty($cardtype)) {
    $errors[] = "Card type is required.";
}

// Validate card name
$cardname = filter_input(INPUT_POST, 'cardname', FILTER_SANITIZE_STRING);
if (empty($cardname)) {
    $errors[] = "Name on card is required.";
}

// Validate card number
$cardnumber = filter_input(INPUT_POST, 'cardnumber', FILTER_VALIDATE_INT);
if (empty($cardnumber) || strlen((string)$cardnumber) != 10) {
    $errors[] = "Card number must be a 10-digit integer.";
}

// Validate card month
$month = filter_input(INPUT_POST, 'month', FILTER_VALIDATE_INT, ["options" => ["min_range" => 1, "max_range" => 12]]);
if (empty($month)) {
    $errors[] = "Valid card expiry month is required.";
}

// Validate card year
$year = filter_input(INPUT_POST, 'year', FILTER_VALIDATE_INT);
if (empty($year) || $year < $current_year || $year > $current_year + 5) {
    $errors[] = "Valid card expiry year is required.";
}

// Validate quantities
$quantities = [];
for ($i = 1; $i <= 5; $i++) {
    $qty = filter_input(INPUT_POST, "qty$i", FILTER_VALIDATE_INT);
    if ($qty !== null && $qty !== false && $qty >= 0) {
        $quantities["item$i"] = $qty;
    } elseif ($qty !== null && $qty !== false) {
        $errors[] = "Quantity for item $i must be a non-negative integer.";
    }
}

// Check if there are any errors
if (!empty($errors)) {
    echo "<h1>Form could not be processed due to the following errors:</h1>";
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>$error</li>";
    }
    echo "</ul>";
    exit;
}

// Define products and prices
$products = [
    "item1" => ["name" => "MacBook", "price" => 1899.99],
    "item2" => ["name" => "Razer Mouse", "price" => 79.99],
    "item3" => ["name" => "WD Hard Drive", "price" => 179.99],
    "item4" => ["name" => "Google Nexus", "price" => 249.99],
    "item5" => ["name" => "Yamaha Drums", "price" => 119.99],
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <title>Thanks for your order!</title>
</head>
<style>
    @import "http://fonts.googleapis.com/css?family=Carrois+Gothic";
    body { margin: 10px auto; width: 700px; font-family: Carrois Gothic; border-radius: 10px; }
    h1, h2 { padding: 2px; }
    h1 { font-size: 22px; }
    table { font-size: 14px; border: 2px solid #000; width: 580px; margin: 0 auto 1em; border-radius: 10px; }
    td { border: 1px solid #000; padding: 2px; margin: 3px; }
    #rollingrick { margin: 10px auto; width: 650px; }
    .alignright { text-align: right; }
    .bold { font-weight: 700; }
    .invoice { border: #000 solid 2px; padding: 5px; width: 660px; margin: 0 auto; color: #000; border-radius: 10px; padding-bottom: 25px; }
</style>
<body>
    <!-- Remember that alternative syntax is good and html inside php is bad -->
    <div class="invoice">
        <h2>Thanks for your order <?php echo $fullname; ?>.</h2>
        <h3>Here's a summary of your order:</h3> 
        <table>
            <tr>
                <td colspan="4"><h3>Address Information</h3></td>
            </tr>
            <tr>
                <td class="alignright"><span class="bold">Address:</span></td>
                <td><?php echo $address; ?></td>
                <td class="alignright"><span class="bold">City:</span></td>
                <td><?php echo $city; ?></td>
            </tr>
            <tr>
                <td class="alignright"><span class="bold">Province:</span></td>
                <td><?php echo $province; ?></td>
                <td class="alignright"><span class="bold">Postal Code:</span></td>
                <td><?php echo $postal; ?></td>
            </tr>
            <tr>
                <td colspan="2" class="alignright"><span class="bold">Email:</span></td>
                <td colspan="2"><a href="/cdn-cgi/l/email-protection" class="__cf_email__" data-cfemail="8af8eff9eff8cafbfba4e9e5e7"><?php echo $email; ?></a></td>
            </tr>
        </table>
        <table>
            <tr>
                <td colspan="3"><h3>Order Information</h3></td>
            </tr>
            <tr>
                <td><span class="bold">Quantity</span></td>
                <td><span class="bold">Description</span></td>
                <td><span class="bold">Cost</span></td>
            </tr>
            <?php 
            $total_cost = 0;
            foreach ($quantities as $item => $qty) {
                if ($qty > 0) {
                    $product = $products[$item];
                    $cost = $qty * $product["price"];
                    $total_cost += $cost;
                    echo "<tr><td>$qty</td><td>{$product['name']}</td><td class='alignright'>" . number_format($cost, 2) . "</td></tr>";
                }
            }
            ?>
            <tr>
                <td colspan="2" class="alignright"><span class="bold">Totals</span></td>
                <td class="alignright"><span class="bold">$<?php echo number_format($total_cost, 2); ?></span></td>
            </tr>
        </table>
    </div>
</body>
</html>
