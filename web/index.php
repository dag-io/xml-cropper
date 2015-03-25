<?php

use DAG\Xml\Cropper\Cropper;

require '../vendor/autoload.php';

header("Cache-Control: no-cache, must-revalidate");

?><!DOCTYPE html>
<html>
<head>
    <title>XML Cropper</title>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">

    <!-- Optional theme -->
    <link rel="stylesheet"
          href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css">

    <style>
        .input-group {
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="panel panel-default">
        <div class="panel-heading"><strong>XML Cropper</strong></div>
        <div class="panel-body">

            <!-- Standar Form -->
            <h4>Select files from your computer</h4>

            <form action="" method="post" enctype="multipart/form-data"
                  id="js-upload-form">

                <div class="form-inline">
                    <div class="form-group">
                        <input type="file" name="xmlfile"
                               id="js-upload-file">
                    </div>
                </div>

                <!-- Drop Zone -->
                <h4>Or drag and drop files below</h4>

                <div class="input-group">
      <span class="input-group-btn">
        <button id="js-upload-submit type="
                submit" class="btn btn-default">Go!</button>
      </span>
                    <input name="xpath" type="text" class="form-control"
                           placeholder="xpath expression"
                           aria-describedby="basic-addon2" required
                           value="<?= isset($_POST['xpath']) ? htmlentities(
                               $_POST['xpath']
                           ) : "" ?>"/>
                    <!-- /input-group -->
                </div>
                <!-- /.row -->

            </form>

            <?php

            if (isset($_FILES['xmlfile'])) {
                $cropper = new Cropper();

                try {
                    $croppedXml = $cropper->crop(
                        $_FILES['xmlfile']['tmp_name'],
                        $_POST['xpath']
                    );
                    $croppedXml = preg_replace(
                        "/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/",
                        "\n",
                        $croppedXml
                    );
                    echo '<pre id="data">'.htmlentities($croppedXml).'</pre>';
                } catch (Exception $ex) {
                    echo '<div class="alert alert-danger" role="alert">
<span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span><span class="sr-only">Error:</span>
'.$ex->getMessage().'
</div>';
                }
            }
            ?>
        </div>
    </div>
</div>
<!-- /container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script
    src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>

<!-- Latest compiled and minified JavaScript -->
<script
    src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/zeroclipboard/2.2.0/ZeroClipboard.js"></script>
</body>
</html>
