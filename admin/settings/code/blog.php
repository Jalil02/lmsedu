<?php


if (isset($_POST['blogkat'])) {
    $col = "
        Ad=:ad,
        slug=:slug
    ";
    $arr = [
        "ad" => $_POST["ad"],
        "slug" => $_POST["slug"]
    ];

    $CRUD->Insert("blog_kat", $col, $arr) ? header("Location:blog-kateqoriya-siyahi.php?status=ok") : header("Location:blog-kateqoriya-elave.php?status=no");
}

if (isset($_POST['blogkatredakte'])) {
    $col = "
        Ad=:ad,
        slug=:slug
    ";
    $whr = "where id=:id";
    $arr = [
        "ad" => $_POST["ad"],
        "slug" => $_POST["slug"],
        "id" => $_POST["id"]
    ];

    $CRUD->Update("blog_kat", $col, $whr, $arr) ? header("Location:blog-kateqoriya-siyahi.php?status=ok") : header("Location:blog-kateqoriya-siyahi.php?status=no");
}

if (@$_GET["delblogkat"] == "ok") {
    $CRUD->Delete("blog_kat", @$_GET["id"]) ? header("Location:blog-kateqoriya-siyahi.php?status=ok") : header("Location:blog-kateqoriya-siyahi.php?status=no");
}

if (@$_GET["delblog"] == "ok") {
    $CRUD->Delete("blog", @$_GET["id"]) ? header("Location:blog-siyahi.php?status=ok") : header("Location:blog-siyahi.php?status=no");
}

if (isset($_POST["bkstatus"])) {
    require_once "../db.php";
    $db = new DBConnection;
    //class
    require_once "../class/umumi.php";
    require_once "../class/nizamlamalar.php";
    require_once "../class/haqqinda.php";
    require_once "../class/crud.php";

    $Umumi = new Umumi;
    $Nizam = new Nizamlamalar;
    $Haqqinda = new Haqqinda;
    $CRUD = new CRUD;

    $id = $_POST["id"];
    $where = "where id=:id";
    $arr = ["id" => $id];
    $st = $CRUD->Select("blog_kat", 0, $where, "status", $arr)["status"];

    $col = "status=:status";
    $array = [
        "status" => $st == 1 ? 0 : 1,
        "id" => $id
    ];
    echo $CRUD->Update("blog_kat", $col, $where, $array);
    exit;
}

if (isset($_POST["bstatus"])) {
    require_once "../db.php";
    $db = new DBConnection;
    //class
    require_once "../class/umumi.php";
    require_once "../class/nizamlamalar.php";
    require_once "../class/haqqinda.php";
    require_once "../class/crud.php";

    $Umumi = new Umumi;
    $Nizam = new Nizamlamalar;
    $Haqqinda = new Haqqinda;
    $CRUD = new CRUD;

    $id = $_POST["id"];
    $where = "where id=:id";
    $arr = ["id" => $id];
    $st = $CRUD->Select("blog", 0, $where, "status", $arr)["status"];

    $col = "status=:status";
    $array = [
        "status" => $st == 1 ? 0 : 1,
        "id" => $id
    ];
    echo $CRUD->Update("blog", $col, $where, $array);
    exit;
}

if (isset($_POST["blog_elave"])) {
    $olcu = $_FILES["sekil"]["size"];
    $ad = $_FILES["sekil"]["name"];
    $tip = $_FILES["sekil"]["type"];
    $tmp = $_FILES["sekil"]["tmp_name"];
    $tipler = ["image/png", "image/jpeg", "image/gif"];

    $dir = "../../sekil/blog/" . rand(100, 9999) . "-" . $ad;

    if ($olcu < 0 || !in_array($tip, $tipler)) {
        header("Location:blog-elave.php?status=no");
        exit;
    }

    $tags = [];
    $tags = explode(",", $_POST["tags"]);
    $tags = json_encode($tags);

    $col = "
    kat_id=:id,
    Basliq=:basliq,
    mezmun=:mezmun,
    tarix=:trx,
    tags=:tags,
    sekil=:img,
    slug=:slug
    ";
    $arr = [
        'id' => $_POST["kateqoriya"],
        'basliq' => $_POST["basliq"],
        'mezmun' => $_POST["mezmun"],
        'trx' => date("Y-m-d H:i:s"),
        'tags' => $tags,
        'img' => substr($dir, 6),
        'slug' => $Umumi->seo($_POST["basliq"])
    ];

    if ($CRUD->Insert("blog", $col, $arr)) {
        move_uploaded_file($tmp, $dir);
        header("Location:blog-siyahi.php?status=ok");
    } else {
        header("Location:blog-elave.php?status=no");
    }
    exit;
}


if (@$_GET["bax"] == "ok") {
    $bax = $CRUD->Select("blog", 0, "where id=" . @$_GET["id"]);
    if (empty($bax)) {
        header("Location:blog-siyahi.php?status=no");
        exit;
    }
    $arr = ["[", "]", "\""];
    $tags = $bax["tags"];
    $tags = str_replace($arr, "", $tags);
}

if (@$_GET["rdk"] == "ok") {
    $rdk = $CRUD->Select("blog", 0, "where id=" . @$_GET["id"]);
    if (empty($rdk)) {
        header("Location:blog-siyahi.php?status=no");
        exit;
    }
    $arr = ["[", "]", "\""];
    $tags = $rdk["tags"];
    $tags = str_replace($arr, "", $tags);
}


if (isset($_POST["blog_redakte"])) {
    $olcu = $_FILES["sekil"]["size"];
    $ad = $_FILES["sekil"]["name"];
    $tip = $_FILES["sekil"]["type"];
    $tmp = $_FILES["sekil"]["tmp_name"];
    $tipler = ["image/png", "image/jpeg", "image/gif"];
    $id = $_POST["id"];
    $ksekil = $_POST["ksekil"];
    $dir = "../../sekil/blog/" . rand(100, 9999) . "-" . $ad;

    if ($olcu > 0 && !in_array($tip, $tipler)) {
        header("Location:blog-elave.php?status=no"); //
        exit;
    }

    $tags = [];
    $tags = explode(",", $_POST["tags"]);
    $tags = json_encode($tags);

    $col = "
    kat_id=:id,
    Basliq=:basliq,
    mezmun=:mezmun,
    tags=:tags,
    sekil=:img,
    slug=:slug
    where id={$id}
    ";
    $arr = [
        'id' => $_POST["kateqoriya"],
        'basliq' => $_POST["basliq"],
        'mezmun' => $_POST["mezmun"],
        'tags' => $tags,
        'img' => $olcu > 0 ? substr($dir, 6) : substr($ksekil, 6),
        'slug' => $Umumi->seo($_POST["basliq"])
    ];

    if ($CRUD->Update("blog", $col, null, $arr)) {
        if ($olcu > 0) {
            unlink($ksekil);
            move_uploaded_file($tmp, $dir);
        }
        header("Location:blog-siyahi.php?status=ok");
    } else {
        header("Location:blog-redakte.php?status=no&id={$id}&rdk=ok");
    }
    exit;
}


$kdata = $CRUD->Select("blog_kat", 1);
$bdata = $CRUD->Select("blog", 1);
