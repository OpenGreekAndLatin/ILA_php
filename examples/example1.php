<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Intra-Language Alignment </title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.2.0/css/bootstrap.min.css">
<style>

    /* Alignment style classes */
    .notAligned{
        background-color:#E84A3F;
        color:#FFF;
    }
    .aligned{
        background-color:#BDB76B
    }
    .Aligned-complete{
        background-color:#318203;
        color:#FFF;
    }
    .Aligned-removedNonAlphanumeric{
        background-color:#6BC934;
        color:#FFF;
    }
    .Aligned-case{
        background-color:#6BC934;
        color:#FFF;
    }
    .Aligned-removeddiacritics{
        background-color:#A6DE85;
        color:#000;
    }
    .Aligned-removeddiacritics{
        background-color:#A6DE85;
        color:#000;
    }
    .Aligned-levenshtein{
        background-color:#5C9678;
        color:#FFF;
    }
    .Gap{
        background-color:#F0FA82;
    }
    .Aligned-combination{
        background-color:#469C5A;
        color:#FFF;
    }
    .ht:hover .tooltip {
        display:block;
    }
    .cellpoint{

        width:8px;
        border-radius:3px;
        margin: 1px;
        padding:3px;

    }
    .label{
        font-size:12px;
        padding: 4px;
        font-weight: 700;
    }
    .badge{
        background-color: #FFFFFF;color: #222222;

    }
    .table{
        padding:3px;
        margin: 2px;
        width: auto;
    }



</style>
</head>
<body>
    <div class="wrapper" style="margin:auto;background-color: #EEE; width: 90%">
        <?php
        require_once ("../iAlignment/iAligner.php");
        require_once ("../iAlignment/Viewer.php");
        $sentence[0]="ἐπεὶ δ ἐστὶν ἄνεμος πλῆθός τι τῆς ἐκ γῆς ξηρᾶς ἀναθυμιάσεως κινούμενον περὶ τὴν γῆν";
        $sentence[1]="ἄνεμος ἐστι πλῆθος καὶ θερμῆς καὶ ξηρᾶς ἀναθυμιάσεως κινούμενον περὶ γῆν";
        $ialigner=new iAligner();
        $viewer= new Viewer();
        $alignmnent=$ialigner->PairwiseAlignment($sentence[0],$sentence[1]);
        $viewer->setAlignment($alignmnent);
        echo $viewer->pairwiseAlignment_to_htmltable();
        echo "<br>";
        //echo $viewer->pairwiseAlignment_to_coloredText();
        //echo "<br>";
        echo $viewer->pairwiseAlignment_statisitcs();
        echo "<br> <h3>The longest common substring</h3>";
        echo $viewer->pairwiseAlignment_longestcommonsubstring();
        ?>
    </div>
</body>
