<?
session_start();
include $_SERVER['DOCUMENT_ROOT'] . "/common/conf/config.inc.php";
include $_SERVER['DOCUMENT_ROOT'] . "/backoffice/auth/auth.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/category/category.lib.php";
include $_SERVER['DOCUMENT_ROOT'] . "/module/shop/shop.lib.php";

if(!in_array("product_manage",$_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["AUTH"]) && $_SESSION[$_SITE["DOMAIN"]]["ADMIN"]["GRADE"]!="ROOT"):
    jsMsg("권한이 없습니다.");
    jsHistory("-1");
endif;

if($_POST['evnMode']=="createCategory"){
    //DB연결
    $dblink = SetConn($_conf_db["main_db"]);

    $makeRS = addCategory(mysqli_real_escape_string($GLOBALS['dblink'], $_POST["s_category"]), mysqli_real_escape_string($GLOBALS['dblink'], $_POST["s_depth"]), mysqli_real_escape_string($GLOBALS['dblink'], $_POST["new_name"]));

    if($makeRS==true){
        jsGo("category.php?cat_no=".$_POST["s_cat_no"],"","");
    }else{
        jsMsg("카테고리 생성에 실패 하였습니다.");
        jsHistory("-1") ;
    }

    //DB해제
    SetDisConn($dblink);

}else if($_REQUEST['evnMode']=="sort_up"){
    //DB연결
    $dblink = SetConn($_conf_db["main_db"]);

    $editRS = sortupCategory(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']));

    if($editRS==true){
        jsGo("category.php?cat_no=".$_REQUEST["s_cat_no"],"","");
    }else{
        jsMsg("정렬순서 변경에 실패 하였습니다.");
        jsHistory("-1") ;
    }

    //DB해제
    SetDisConn($dblink);

}else if($_REQUEST['evnMode']=="sort_down"){
    //DB연결
    $dblink = SetConn($_conf_db["main_db"]);

    $editRS = sortdownCategory(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']));

    if($editRS==true){
        jsGo("category.php?cat_no=".$_REQUEST["s_cat_no"],"","");
    }else{
        jsMsg("정렬순서 변경에 실패 하였습니다.");
        jsHistory("-1") ;
    }

    //DB해제
    SetDisConn($dblink);

}else if($_POST['evnMode']=="editCategory"){
    //DB연결
    $dblink = SetConn($_conf_db["main_db"]);

    $editRS = editCategory (mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cat_no']), mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cat_name']), mysqli_real_escape_string($GLOBALS['dblink'], $_POST['cat_content']));

    if($editRS==true){
        //jsGo("category_info.php?cat_no=".$_POST['cat_no'],"","");
        jsGo($_POST['rt_url'],"","저장되었습니다.");
    }else{
        jsMsg("정보수정에 실패 하였습니다.");
        jsHistory("-1") ;
    }

    //DB해제
    SetDisConn($dblink);

}else if($_REQUEST['evnMode']=="deleteCategory"){
    //DB연결
    $dblink = SetConn($_conf_db["main_db"]);

    $editRS = deleteCategory(mysqli_real_escape_string($GLOBALS['dblink'], $_REQUEST['cat_no']));

    if($editRS==true){
        jsGo("category.php?cat_no=".$_REQUEST["s_cat_no"],"","");
    }else{
        jsMsg("카테고리 삭제에 실패 하였습니다.");
        jsHistory("-1") ;
    }

    //DB해제
    SetDisConn($dblink);

}else if($_REQUEST['evnMode']=="insert"){
    //DB연결
    $dblink = SetConn($_conf_db["main_db"]);

    $RS = insertCategoryBanner($_POST['cat_no']);

    if($RS==true){
        jsGo("category_info.php?cat_no=".$_POST['cat_no'],"","");
    }else{
        jsMsg("브랜드 배너 등록에 실패 하였습니다.");
        jsHistory("-1") ;
    }

    //DB해제
    SetDisConn($dblink);

}else if($_REQUEST['evnMode']=="update"){

    //DB연결
    $dblink = SetConn($_conf_db["main_db"]);

    $idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_POST['idx']));

    $RS = updateCategoryBanner($idx);

    if($RS==true){
        jsGo("category_info.php?cat_no=".$_POST['cat_no'],"","");
    }else{
        jsMsg("브랜드 배너 수정에 실패 하였습니다.");
        jsHistory("-1") ;
    }

    //DB해제
    SetDisConn($dblink);


}else if($_REQUEST['evnMode']=="delete"){

    //DB연결
    $dblink = SetConn($_conf_db["main_db"]);

    $idx = mysqli_real_escape_string($GLOBALS['dblink'], trim($_REQUEST['idx']));

    $RS = deleteCategoryBanner($idx);

    if($RS==true){
        jsGo("category_info.php?cat_no=".$_POST['cat_no'],"","");
    }else{
        jsMsg("브랜드 배너 삭제에 실패 하였습니다.");
        jsHistory("-1") ;
    }

    //DB해제
    SetDisConn($dblink);

}
?>