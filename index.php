<?php
$apiUrl = "https://api.assetstore.unity3d.com/publisher/v1/invoice/verify.json";
$apiKey = "yourApiKey";
$package = "yourPackage";
$file = 'yourFilePath';

if (!empty($_GET['invoice']))
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $apiUrl."?key=".$apiKey."&invoice=".$_GET['invoice'],
    ));
    $resp = curl_exec($curl);
    curl_close($curl);
    $result = json_decode($resp, true);
    $found = false;
    foreach ($result['invoices'] as $invoice)
    {
        if ($invoice['package'] === $package)
        {
            $found = true;
            break;
        }
    }
    if ($found)
    {
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
    <input type="submit" text="Download" />
    </form>
    </body>
</html>
<?php
}
?>