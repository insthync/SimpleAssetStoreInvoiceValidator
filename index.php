<?php
$apiUrl = "https://api.assetstore.unity3d.com/publisher/v1/invoice/verify.json";
$apiKey = "yourApiKey";
$package = "yourPackage";
$files = array('ver' => 'yourFilePath');

if (!empty($_GET['invoice']) && !empty($_GET['version']))
{
    $invoice = $_GET['invoice'];
    $version = $_GET['version'];
    set_time_limit(0);
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $apiUrl."?key=".$apiKey."&invoice=".$invoice,
    ));
    $resp = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($resp, true);
    $found = false;
    if (!empty($result['invoices']))
    {
        $invoices = $result['invoices'];
        foreach ($invoices as $invoice)
        {
            if ($invoice['package'] === $package)
            {
                $found = true;
                break;
            }
        }
    }
    if ($found && !empty($files[$version]))
    {
        $file = $files[$version];
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="'.basename($file).'"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    }
    else
    {
        echo 'Invalid invoice [ <a href="index.php">Back</a> ]';
    }
}
else
{
?>
<html>
    <head><title><?php echo $package; ?> - Invoice Validation</title></head>
    <body>
    <form method="GET" action="index.php">
    Invoice: <input type="text" name="invoice" />
    Version:
    <select name="version">
    <?php
    foreach ($files as $version => $filePath) {
        ?>
        <option value="<?php echo $version; ?>"><?php echo $version; ?></option>
        <?php
    }
    ?>
    </select>
    <input type="submit" text="Download" />
    </form>
    </body>
</html>
<?php
}
?>